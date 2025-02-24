<?php

namespace App\Http\Controllers;

use App\Models\JenisPengeluaran;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KelolaPengeluaranController extends Controller
{
    public function index()
    {
        try {
            $jenis_pengeluaran = JenisPengeluaran::all();
            $pengeluaran = Pengeluaran::all();
            $pengeluaran = Pengeluaran::orderBy('tgl_pengeluaran', 'desc')->get();
            return view('pages.admin.kelolaPengeluaran', [
                'jenis_pengeluaran' => $jenis_pengeluaran,
                'pengeluaran' => $pengeluaran,
            ]);
        } catch (\Exception $e) {
            // Tangani kesalahan lain yang mungkin terjadi
            return response()->view('errors.404', [], 404);
        }
    }

    public function storeJenis(Request $request)
    {
        try {
            // Validasi data input
            $request->validate([
                'name' => 'required',
            ]);

            // Membuat entri project baru
            $jenis_pengeluaran = JenisPengeluaran::create([
                'name' => $request->name,
            ]);


            Alert::toast('Data Jenis Pengeluaran Berhasil Ditambahkan.', 'success')->autoClose(10000);
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Terjadi Kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);
            return redirect()->back();
        }
    }

    public function updateJenis(Request $request)
    {
        try {
            // Validasi data input jika diperlukan
            $request->validate([
                'name' => 'required',
            ]);

            // Temukan user berdasarkan ID
            $jenis = JenisPengeluaran::where('id', $request->id)->firstOrFail();

            $jenis->update([
                'name' => $request->name,
            ]);

            // Redirect dan alert toast sukses
            Alert::toast('Data Jenis Pengeluaran Berhasil Diupdate.', 'success')->autoClose(10000);
            return redirect()->back();
        } catch (\Exception $e) {
            // Menampilkan pesan kesalahan jika terjadi pengecualian
            Alert::toast('Terjadi kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);

            return redirect()->back();
        }
    }

    public function destroyJenis(Request $request)
    {
        try {
            $jenis = JenisPengeluaran::where('id', $request->id)->firstOrFail();

            $jenis->delete($jenis);

            Alert::toast('Data Jenis Pengeluaran Berhasil Dihapus.', 'success')->autoClose(10000);

            return redirect()->back();
        } catch (\Exception $e) {
            // Menampilkan pesan kesalahan jika terjadi pengecualian
            Alert::toast('Terjadi kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);

            return redirect()->back();
        }
    }

    public function storePengeluaran(Request $request)
    {
        try {
            $request->merge([
                'jumlah' => str_replace('.', '', $request->jumlah),
            ]);
            // Validasi data input
            $request->validate([
                'jenis_pengeluaran_id' => 'required|exists:jenis_pengeluaran,id',
                'deskripsi' => 'required|string|max:255',
                'tgl_pengeluaran' => 'required|date',
                'metode_pembayaran' => 'required|string|max:255',
                'jumlah' => 'required|integer|min:0',
                'kategori' => 'required|string|max:255'
            ]);

            // Membuat entri pengeluaran baru
            $pengeluaran = Pengeluaran::create([
                'jenis_pengeluaran_id' => $request->jenis_pengeluaran_id,
                'deskripsi' => $request->deskripsi,
                'tgl_pengeluaran' => $request->tgl_pengeluaran,
                'kode' => $request->kode,
                'metode_pembayaran' => $request->metode_pembayaran,
                'jumlah' => $request->jumlah,
                'kategori' => $request->kategori
            ]);

            Alert::toast('Data Pengeluaran Berhasil Ditambahkan.', 'success')->autoClose(10000);
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Terjadi Kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);
            return redirect()->back();
        }
    }

    public function updatePengeluaran(Request $request)
    {
        try {
            // Validasi data input
            $request->validate([
                'jenis_pengeluaran_id' => 'required|exists:jenis_pengeluaran,id',
                'deskripsi' => 'required|string|max:255',
                'tgl_pengeluaran' => 'required|date',
                'metode_pembayaran' => 'required|string|max:255',
                'jumlah' => 'required|integer|min:0',
                'kategori' => 'required|string|max:255'
            ]);

            // Temukan pengeluaran berdasarkan ID
            $pengeluaran = Pengeluaran::where('id', $request->id)->firstOrFail();

            $pengeluaran->update([
                'jenis_pengeluaran_id' => $request->jenis_pengeluaran_id,
                'deskripsi' => $request->deskripsi,
                'tgl_pengeluaran' => $request->tgl_pengeluaran,
                'kode' => $request->kode,
                'metode_pembayaran' => $request->metode_pembayaran,
                'jumlah' => $request->jumlah,
                'kategori' => $request->kategori
            ]);

            Alert::toast('Data Pengeluaran Berhasil Diupdate.', 'success')->autoClose(10000);
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Terjadi kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);
            return redirect()->back();
        }
    }

    public function destroyPengeluaran(Request $request)
    {
        try {
            $pengeluaran = Pengeluaran::where('id', $request->id)->firstOrFail();

            $pengeluaran->delete();

            Alert::toast('Data Pengeluaran Berhasil Dihapus.', 'success')->autoClose(10000);
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Terjadi kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);
            return redirect()->back();
        }
    }
}
