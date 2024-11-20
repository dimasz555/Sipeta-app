<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Blok;
use App\Models\Cicilan;
use App\Models\Pembelian;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;


class CicilanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pembayaran = $user->pembelians()->orderBy('tgl_pembelian', 'desc')->get();

        foreach ($pembayaran as $pb) {
            $pb->encrypted_id = Crypt::encrypt($pb->id);
        }

        return view('pages.konsumen.pembayaran', [
            'pembayaran' => $pembayaran,
            'user' => $user,
        ]);
    }

    public function detail($id)
    {
        try {
            $user = Auth::user();

            // Dekripsi ID
            $decryptedId = Crypt::decrypt($id);

            // Cek payment status dari parameter URL dan pastikan belum ada session
            if (request()->has('payment_status') && !session()->has('payment_shown')) {
                // Set session flash untuk menandai alert sudah ditampilkan
                session()->flash('payment_shown', true);

                switch (request()->payment_status) {
                    case 'success':
                        Alert::toast('Pembayaran Cicilan Berhasil', 'success')->autoClose(10000);
                        break;
                    case 'error':
                        Alert::toast('Pembayaran Cicilan Gagal', 'error')->autoClose(10000);
                        break;
                    case 'close':
                        Alert::toast('Pembayaran Dibatalkan', 'error')->autoClose(10000);
                        break;
                }

                // Redirect ke halaman yang sama tanpa parameter
                return redirect()->route('pembayaran.kavling.detail', ['id' => $id]);
            }

            // Ambil data pembelian terhadap cicilan dan validasi pengguna
            $pembayaran = Pembelian::with(['cicilans'])
                ->where('id', $decryptedId)
                ->where('user_id', $user->id)
                ->firstOrFail();

            return view('pages.konsumen.detailPembayaran', [
                'pembayaran' => $pembayaran,
            ]);
        } catch (DecryptException $e) {
            return response()->view('errors.404', [], 404);
        } catch (\Exception $e) {
            return response()->view('errors.404', [], 404);
        }
    }

    public function bayarCicilan($id)
    {
        try {
            $user = Auth::user();

            // Dekripsi ID yang diterima
            $decryptedId = Crypt::decrypt($id);

            // Ambil cicilan berdasarkan ID dan validasi apakah milik pengguna yang sedang login
            $cicilan = Cicilan::with('pembelian')->where('id', $decryptedId)
                ->whereHas('pembelian', function ($query) use ($user) {
                    // Pastikan cicilan milik pengguna yang sedang login
                    $query->where('user_id', $user->id);
                })
                ->firstOrFail();

            // Jika cicilan sudah dibayar, redirect kembali
            if ($cicilan->status === 'sudah dibayar') {
                Alert::toast('Cicilan Sudah Dibayar', 'error')->autoClose(10000);
            }

            // Mengambil no_transaksi
            $noTransaksi = $cicilan->no_transaksi;

            // Hapus 3 digit terakhir 
            $baseNoTransaksi = substr($noTransaksi, 0, -3);

            // Menambahkan 3 digit baru dari 
            $orderId = $baseNoTransaksi . substr(time(), -3);

            // Update no_transaksi
            $cicilan->no_transaksi = $orderId;
            $cicilan->save();

            // Set Midtrans Config
            \Midtrans\Config::$serverKey = config('midtrans.serverKey');
            \Midtrans\Config::$isProduction = config('midtrans.isProduction');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            // Buat parameter transaksi untuk Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $cicilan->harga_cicilan,
                ],
                'customer_details' => [
                    'first_name' => $cicilan->pembelian->user->name,
                    'phone' => $cicilan->pembelian->user->phone,
                ],
            ];

            // Dapatkan Snap Token dari Midtrans
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Redirect ke halaman pembayaran dengan pass snapToken
            return view('pages.konsumen.paymentPage', [
                'snapToken' => $snapToken,  // Passing snapToken to the view
                'cicilan' => $cicilan,
            ]);
        } catch (DecryptException $e) {
            // Jika ID tidak dapat didekripsi atau tidak valid
            return response()->view('errors.404', [], 404);
        } catch (\Exception $e) {
            // Log error untuk debugging dan tampilkan pesan error
            Log::error('Error: ' . $e->getMessage());
            return response()->view('errors.404', [], 404);
        }
    }


    public function callback(Request $request)
    {
        try {
            $serverKey = config('midtrans.serverKey');
            $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

            if ($hashed == $request->signature_key) {
                $cicilan = Cicilan::where('no_transaksi', $request->order_id)->first();

                if (!$cicilan) {
                    Log::error('Cicilan tidak ditemukan untuk order_id: ' . $request->order_id);
                    return response()->json(['message' => 'Cicilan tidak ditemukan'], 404);
                }

                if ($request->transaction_status == 'settlement') {
                    $cicilan->update([
                        'tgl_bayar' => now(),
                        'status' => 'lunas',
                    ]);

                    $pembelian = $cicilan->pembelian;
                    $allPaid = $pembelian->cicilans->every(function ($cicilan) {
                        return $cicilan->status === 'lunas';
                    });

                    if ($allPaid) {
                        $pembelian->update(['status' => 'selesai']);
                    }

                    return response()->json(['status' => 'success']);
                } elseif (
                    $request->transaction_status == 'deny' ||
                    $request->transaction_status == 'expire' ||
                    $request->transaction_status == 'cancel'
                ) {
                    $cicilan->update(['status' => 'belum dibayar']);
                    return response()->json(['status' => 'failed']);
                }
            }

            return response()->json(['status' => 'error', 'message' => 'Invalid signature']);
        } catch (\Exception $e) {
            Log::error('Error Midtrans Callback: ' . $e->getMessage());
            Alert::toast('Terjadi Kesalahan: ', 'error')->autoClose(10000);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
