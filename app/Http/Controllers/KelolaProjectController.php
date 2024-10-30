<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Blok;
use Laratrust\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;

class KelolaProjectController extends Controller
{
    public function index()
    {
        $project = Project::all();
        $blok = Blok::all();

        return view('pages.admin.kelolaProject', [
            'project' => $project,
            'blok' => $blok,
        ]);
    }

    public function storeProject(Request $request)
    {
        try {
            // Validasi data input
            $request->validate([
                'name' => 'required',
                'lokasi' => 'required',
            ]);

            // Membuat entri project baru
            $project = Project::create([
                'name' => $request->name,
                'lokasi' => $request->lokasi,
            ]);


            Alert::toast('Data Project Berhasil Ditambahkan.', 'success')->autoClose(10000);
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Terjadi Kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);
            return redirect()->back();
        }
    }

    public function updateProject(Request $request)
    {
        try {
            // Validasi data input jika diperlukan
            $request->validate([
                'name' => 'required',
                'lokasi' => 'required',
            ]);

            // Temukan user berdasarkan ID
            $project = Project::where('id', $request->id)->firstOrFail();

            $project->update([
                'name' => $request->name,
                'lokasi' => $request->lokasi,
            ]);

            // Redirect dan alert toast sukses
            Alert::toast('Data Project Berhasil Diubah.', 'success')->autoClose(10000);
            return redirect()->back();
        } catch (\Exception $e) {
            // Menampilkan pesan kesalahan jika terjadi pengecualian
            Alert::toast('Terjadi kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);

            return redirect()->back();
        }
    }

    public function destroyProject(Request $request)
    {
        try {
            $project = Project::where('id', $request->id)->firstOrFail();

            $project->delete($project);

            Alert::toast('Data Project Berhasil Dihapus.', 'success')->autoClose(10000);

            return redirect()->back();
        } catch (\Exception $e) {
            // Menampilkan pesan kesalahan jika terjadi pengecualian
            Alert::toast('Terjadi kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);

            return redirect()->back();
        }
    }

    public function storeBlok(Request $request)
    {
        try {
            // Validasi data input
            $request->validate([
                'blok' => 'required',
            ]);

            // Membuat entri blok baru
            $blok = Blok::create([
                'blok' => $request->blok,
            ]);

            Alert::toast('Data Blok Berhasil Ditambahkan.', 'success')->autoClose(10000);
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Terjadi Kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);
            return redirect()->back();
        }
    }

    public function updateBlok(Request $request)
    {
        try {
            // Validasi data input jika diperlukan
            $request->validate([
                'blok' => 'required',
            ]);

            // Temukan user berdasarkan ID
            $blok = Blok::where('id', $request->id)->firstOrFail();

            $blok->update([
                'blok' => $request->blok,
            ]);

            // Redirect dan alert toast sukses
            Alert::toast('Data Blok Berhasil Diubah.', 'success')->autoClose(10000);
            return redirect()->back();
        } catch (\Exception $e) {
            // Menampilkan pesan kesalahan jika terjadi pengecualian
            Alert::toast('Terjadi kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);

            return redirect()->back();
        }
    }

    public function destroyBlok(Request $request)
    {
        try {
            $blok = Blok::where('id', $request->id)->firstOrFail();

            $blok->delete($blok);

            Alert::toast('Data Blok Berhasil Dihapus.', 'success')->autoClose(10000);

            return redirect()->back();
        } catch (\Exception $e) {
            // Menampilkan pesan kesalahan jika terjadi pengecualian
            Alert::toast('Terjadi kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);

            return redirect()->back();
        }
    }
}
