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

    public function detail($id, Request $request)
    {
        try {
            $user = Auth::user();

            // Dekripsi ID
            $decryptedId = Crypt::decrypt($id);

            if ($request->query('payment_status') === 'success') {
                session()->forget([
                    'current_order_id',
                    'snap_token',
                    'cicilan_id',
                    'payment_session_time',
                    'pembelian_id'
                ]);
            }

            // Tampilkan toast jika ada session 'error'
            if ($request->session()->has('error')) {
                Alert::toast($request->session()->get('error'), 'error')->autoClose(10000);
            }

            // dd(session()->all());

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
                    case 'pending':
                        Alert::toast('Pembayaran Cicilan Pending', 'info')->autoClose(10000);
                        break;
                    case 'close':
                        Alert::toast('Pembayaran Dibatalkan', 'error')->autoClose(10000);
                        break;
                }
                // Redirect ke halaman yang sama tanpa parameter
                return redirect()->route('pembayaran.kavling.detail', ['id' => $id]);
            }

            // Ambil data pembelian terhadap cicilan dan validasi pengguna
            $pembayaran = Pembelian::with(['cicilans', 'pembatalan'])
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
            $decryptedId = Crypt::decrypt($id);

            $cicilan = Cicilan::with('pembelian')->where('id', $decryptedId)
                ->whereHas('pembelian', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->firstOrFail();

            if ($cicilan->status === 'sudah dibayar') {
                Alert::toast('Cicilan Sudah Dibayar', 'error')->autoClose(10000);
                return redirect()->back();
            }

            // Cek apakah order_id sudah ada di session
            if (!session()->has('current_order_id')) {
                $noTransaksi = $cicilan->no_transaksi;
                // Hapus 3 digit terakhir
                $baseNoTransaksi = substr($noTransaksi, 0, -3);
                // Menambahkan 3 digit baru dari timestamp
                $newOrderId = $baseNoTransaksi . substr(time(), -3);

                // Simpan order_id ke session
                session(['current_order_id' => $newOrderId]);

                // Update no_transaksi cicilan
                $cicilan->update(['no_transaksi' => $newOrderId]);
            }

            // Gunakan order_id dari session
            $orderId = session('current_order_id');

            // Set Midtrans Config
            \Midtrans\Config::$serverKey = config('midtrans.serverKey');
            \Midtrans\Config::$isProduction = config('midtrans.isProduction');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

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

            // Cek apakah snap token sudah ada di session
            if (!session()->has('snap_token')) {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                session(['snap_token' => $snapToken]);
            } else {
                $snapToken = session('snap_token');
            }

            session(['cicilan_id' => $cicilan->id]);
            session(['pembelian_id' => Crypt::encrypt($cicilan->pembelian->id)]);

            return view('pages.konsumen.paymentPage', [
                'snapToken' => $snapToken,
                'cicilan' => $cicilan,
            ]);
        } catch (DecryptException $e) {
            return response()->view('errors.404', [], 404);
        } catch (\Exception $e) {
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
                    return response()->json(['message' => 'Cicilan Tidak Ditemukan'], 404);
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
                        $pembelian->update(['status' => 'selesai', 'tgl_lunas' => now()]);
                    }

                    // Hapus session setelah pembayaran berhasil
                    session()->forget(['current_order_id', 'snap_token', 'cicilan_id']);

                    return response()->json(['status' => 'success']);
                } elseif (
                    $request->transaction_status == 'deny' ||
                    $request->transaction_status == 'expire' ||
                    $request->transaction_status == 'cancel'
                ) {
                    $cicilan->update(['status' => 'belum dibayar']);


                    // Hapus session jika pembayaran gagal
                    session()->forget(['current_order_id', 'snap_token', 'cicilan_id']);

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
