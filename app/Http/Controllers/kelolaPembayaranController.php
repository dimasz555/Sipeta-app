<?php

namespace App\Http\Controllers;

use App\Models\Boking;
use App\Models\User;
use App\Models\Pembelian;
use App\Models\Project;
use App\Models\Blok;
use App\Models\Cicilan;
use App\Models\Pembatalan;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Storage;

class KelolaPembayaranController extends Controller
{
    public function index()
    {
        try {
            $user = User::all();
            $boking = Boking::where('status', 'lunas')
                ->with('user')
                ->orderBy('tgl_boking', 'desc')
                ->get();

            // Ambil data pembelian dengan status 'lunas' dan 'proses'
            $pembelian = Pembelian::whereIn('status', ['selesai', 'proses'])
                ->orderBy('tgl_pembelian', 'desc')
                ->get();

            // Enkripsi ID 
            foreach ($pembelian as $pb) {
                $pb->encrypted_id = Crypt::encrypt($pb->id);
            }
            return view('pages.admin.kelolaPembayaran', [
                'boking' => $boking,
                'pembelian' => $pembelian,
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            // Tangani kesalahan lain yang mungkin terjadi
            return response()->view('errors.404', [], 404);
        }
    }

    public function searchUserBoking(Request $request)
    {
        $search = $request->input('search');

        // Mengambil pengguna yang memiliki role 'konsumen' dan memiliki booking dengan status 'lunas'
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'konsumen');
        })
            ->whereHas('bokings', function ($query) {
                $query->where('status', 'lunas');
            })
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->select('id', 'name')
            ->get();

        $results = $users->map(function ($user) {
            return ['id' => $user->id, 'text' => $user->name];
        });

        return response()->json(['results' => $results]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'boking_id' => 'required|exists:bokings,id',
                'harga' => 'required|integer',
                'dp' => 'required|integer',
                'jumlah_bulan_cicilan' => 'required|integer',
                'harga_cicilan_perbulan' => 'required|integer',
                'pjb' => 'nullable|file|mimes:pdf|max:6048',
            ]);

            // Inisialisasi variabel
            $filePath = null;

            if ($request->hasFile('pjb')) {
                // Ambil nama asli file
                $originalName = $request->file('pjb')->getClientOriginalName();

                // Buat nama file baru dengan format yang diinginkan
                $filename = 'pjb_' . $request->user_id . '_' . Carbon::now()->timestamp . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.pdf';

                // Simpan file dengan nama baru
                $filePath = $request->file('pjb')->storeAs('pjb_files', $filename, 'public');
            }
            // dd($filePath);

            // Simpan data pembelian ke database
            $pembelian = Pembelian::create([
                'user_id' => $request->user_id,
                'boking_id' => $request->boking_id,
                'tgl_pembelian' => Carbon::now()->timezone('Asia/Jakarta'),
                'harga' => $request->harga,
                'dp' => $request->dp,
                'jumlah_bulan_cicilan' => $request->jumlah_bulan_cicilan,
                'harga_cicilan_perbulan' => $request->harga_cicilan_perbulan,
                'pjb' => $filePath,
                'status' => 'proses'
            ]);

            // Ambil harga cicilan per bulan dari pembelian
            $hargaCicilanPerBulan = $pembelian->harga_cicilan_perbulan;

            // Tentukan bulan dan tahun untuk cicilan pertama
            $bulanCicilanPertama = now()->month + 1;
            $tahunCicilanPertama = now()->year;

            // Generate data cicilan untuk setiap bulan
            for ($i = 0; $i < $pembelian->jumlah_bulan_cicilan; $i++) {
                // Generate nomor transaksi unik
                $noTransaksi = 'TRX-' . strtoupper(Str::random(12));

                // Hitung bulan dan tahun cicilan
                $tanggalCicilan = Carbon::createFromDate($tahunCicilanPertama, $bulanCicilanPertama + $i, 1);
                $bulanCicilan = $tanggalCicilan->month; // Mendapatkan bulan
                $tahunCicilan = $tanggalCicilan->year; // Mendapatkan tahun

                $cicilan = Cicilan::create([
                    'pembelian_id' => $pembelian->id,
                    'no_transaksi' => $noTransaksi,
                    'no_cicilan' => $i + 1,
                    'harga_cicilan' => $hargaCicilanPerBulan,
                    'status' => 'belum dibayar',
                    'bulan' => $bulanCicilan,
                    'tahun' => $tahunCicilan
                ]);
            }
            // dd($pembelian);

            Alert::toast('Data Pembelian Berhasil Ditambah', 'success')->autoClose(10000);
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Terjadi kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);
            return redirect()->back();
        }
    }

    public function detail($id)
    {
        try {

            // Dekripsi ID
            $decryptedId = Crypt::decrypt($id);

            $pembelian = Pembelian::with(['cicilans','pembatalan'])->findOrFail($decryptedId);
            return view('pages.admin.detailPembelian', [
                'pembelian' => $pembelian,
            ]);
        } catch (DecryptException $e) {
            // Redirect ke halaman 404
            return response()->view('errors.404', [], 404);
        } catch (\Exception $e) {
            // Tangani kesalahan lain yang mungkin terjadi
            return response()->view('errors.404', [], 404);
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'boking_id' => 'required|exists:bokings,id',
                'harga' => 'required|integer|min:1',
                'dp' => 'required|integer|min:0',
                'jumlah_bulan_cicilan' => 'required|integer|min:1',
                'harga_cicilan_perbulan' => 'required|integer|min:1',
                'pjb' => 'nullable|mimes:pdf|max:6048',
            ]);

            $pembelian = Pembelian::findOrFail($request->id);

            // cek jika harga cicilan perbulan berubah
            $hargaCicilanPerbulanChanged = $pembelian->harga_cicilan_perbulan != $request->harga_cicilan_perbulan;

            // Inisialisasi variabel
            $dataToUpdate = [
                'user_id' => $request->user_id,
                'harga' => $request->harga,
                'boking_id' => $request->boking_id,
                'dp' => $request->dp,
                'jumlah_bulan_cicilan' => $request->jumlah_bulan_cicilan,
                'harga_cicilan_perbulan' => $request->harga_cicilan_perbulan,
            ];

            if ($request->hasFile('pjb')) {
                $originalName = $request->file('pjb')->getClientOriginalName();
                $filename = 'pjb_' . $request->user_id . '_' . Carbon::now()->timestamp . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.pdf';
                $filePath = $request->file('pjb')->storeAs('pjb_files', $filename, 'public');
                $dataToUpdate['pjb'] = $filePath; // tambahkan jika ada file
            }

            $pembelian->update($dataToUpdate);
            // If harga_cicilan_perbulan changed, update existing cicilan records
            if ($hargaCicilanPerbulanChanged) {
                $pembelian->cicilans()->update([
                    'harga_cicilan' => $request->harga_cicilan_perbulan,
                ]);
            }

            // menghitung jumlah cicilan yang diperbaharui
            $currentCicilanCount = $pembelian->cicilans->count();

            if ($request->jumlah_bulan_cicilan < $currentCicilanCount) {
                // menghapus no cicicilan jika bulan cicilan dikurangi
                $pembelian->cicilans()->where('no_cicilan', '>', $request->jumlah_bulan_cicilan)->delete();
            } elseif ($request->jumlah_bulan_cicilan > $currentCicilanCount) {
                // mendapatkan harga cicilan 
                $hargaCicilanPerBulan = $pembelian->harga_cicilan_perbulan;

                // mendapatkan bulan dan tahun cicilan 
                $lastCicilan = $pembelian->cicilans()->orderBy('no_cicilan', 'desc')->first();
                $nextMonth = $lastCicilan ? $lastCicilan->bulan : now()->month;
                $nextYear = $lastCicilan ? $lastCicilan->tahun : now()->year;

                for ($i = $currentCicilanCount + 1; $i <= $request->jumlah_bulan_cicilan; $i++) {
                    // Increment month and adjust year if needed
                    $nextMonth++;
                    if ($nextMonth > 12) {
                        $nextMonth = 1;
                        $nextYear++;
                    }

                    Cicilan::create([
                        'pembelian_id' => $pembelian->id,
                        'no_transaksi' => 'TRX-' . strtoupper(Str::random(12)),
                        'no_cicilan' => $i,
                        'harga_cicilan' => $hargaCicilanPerBulan,
                        'status' => 'belum dibayar',
                        'bulan' => $nextMonth,
                        'tahun' => $nextYear,
                    ]);
                }
            }

            Alert::toast('Data Pembelian Berhasil Diperbaharui', 'success')->autoClose(10000);
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Terjadi kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);
            return redirect()->back();
        }
    }

    public function batalPembelian(Request $request)
    {
        try {
            $request->validate([
                'pembelian_id' => 'required|exists:pembelian,id',
                'alasan_pembatalan' => 'required|string',
                'jumlah_pengembalian' => 'required|integer|min:1',
            ]);

            // Ambil data pembelian berdasarkan ID yang dikirimkan
            $pembelian = Pembelian::findOrFail($request->pembelian_id);

            // Periksa apakah status pembelian sudah dibatalkan
            if ($pembelian->status === 'batal') {
                Alert::toast('Pembelian sudah dibatalkan sebelumnya.', 'warning')->autoClose(10000);
                return redirect()->back();
            }

            // Update status pembelian menjadi 'batal'
            $pembelian->status = 'batal';
            $pembelian->save();

            // Pembatalan untuk cicilan
            $cicilan = Cicilan::where('pembelian_id', $pembelian->id)
                ->where('status', 'belum dibayar') // Hanya cicilan yang belum dibayar yang diubah
                ->get();

            foreach ($cicilan as $c) {
                // Update status cicilan menjadi 'batal'
                $c->status = 'batal';
                $c->save();
            }

            // Insert data pembatalan ke tabel pembatalan
            Pembatalan::create([
                'pembelian_id' => $pembelian->id,
                'alasan_pembatalan' => $request->alasan_pembatalan,
                'tgl_pembatalan' => Carbon::now()->timezone('Asia/Jakarta'),
                'jumlah_pengembalian' => $request->jumlah_pengembalian,
            ]);

            Alert::toast('Pembelian Berhasil Dibatalkan', 'success')->autoClose(10000);
            return redirect()->back();
        } catch (\Throwable $e) {
            Alert::toast('Terjadi kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);
            return redirect()->back();
        }
    }

    public function uploadKwitansi(Request $request)
    {
        try {
            // Validasi file yang di-upload
            $request->validate([
                'kwitansi' => 'required|file|mimes:pdf,jpg,png|max:2048'
            ]);

            // Cari cicilan berdasarkan ID
            $cicilan = Cicilan::findOrFail($request->id);

            // Jika sudah ada file kwitansi sebelumnya, hapus file lama
            if ($cicilan->kwitansi) {
                Storage::delete('public/kwitansi/' . $cicilan->kwitansi);
            }

            // Ambil file yang di-upload
            $file = $request->file('kwitansi');

            // Buat nama file 
            $fileName = 'kwitansi_' . $cicilan->no_transaksi . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Simpan file di folder public/kwitansi
            $path = $file->storeAs('kwitansi', $fileName, 'public');

            // Update nama file kwitansi di database
            $cicilan->kwitansi = $fileName;
            $cicilan->save();

            Alert::toast('Kwitansi Berhasil Diupload', 'success')->autoClose(10000);
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Terjadi kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);
            return redirect()->back();
        }
    }
}
