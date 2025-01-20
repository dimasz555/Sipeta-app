<?php

namespace App\Http\Controllers;

use App\Models\Boking;
use App\Models\User;
use App\Models\Project;
use App\Models\Blok;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KelolaBokingController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        $bloks = Blok::all();
        $user = User::all();
        $boking = Boking::all();
        $boking = Boking::orderBy('tgl_boking', 'desc')->get();
        return view('pages.admin.kelolaBoking', [
            'boking' => $boking,
            'projects' => $projects,
            'bloks' => $bloks,
            'user' => $user,
        ]);
    }

    public function searchUser(Request $request)
    {
        $search = $request->input('search');

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'konsumen');
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
            $request->merge([
                'harga_boking' => str_replace('.', '', $request->harga_boking),
            ]);

            // Validasi data input
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'project_id' => 'required|exists:projects,id',
                'blok_id' => 'required|exists:bloks,id',
                'no_blok' => 'required|string|max:255',
                'tgl_boking' => 'required|date',
                'harga_boking' => 'required|integer',
            ]);

            DB::beginTransaction();

            $boking = Boking::create([
                'user_id' => $request->user_id,
                'project_id' => $request->project_id,
                'blok_id' => $request->blok_id,
                'no_blok' => $request->no_blok,
                'tgl_boking' => $request->tgl_boking,
                'tgl_lunas' => null,
                'harga_boking' => $request->harga_boking,
                'status' => 'proses',
            ]);

            DB::commit();

            // dd($boking);

            Alert::toast('Data Boking Berhasil Ditambahkan', 'success')->autoClose(10000);
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('Terjadi Kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);
            return redirect()->back();
        }
    }

    public function updateBoking(Request $request)
    {
        try {
            $request->merge([
                'harga_boking' => str_replace('.', '', $request->harga_boking),
            ]);

            // Validasi data input
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'project_id' => 'required|exists:projects,id',
                'blok_id' => 'required|exists:bloks,id',
                'no_blok' => 'required|string',
                'tgl_boking' => 'required|date',
                'harga_boking' => 'required|integer',
            ]);

            // Temukan user berdasarkan ID
            $boking = Boking::where('id', $request->id)->firstOrFail();

            // dd($boking);
            $boking->update([
                'user_id' => $request->user_id,
                'project_id' => $request->project_id,
                'blok_id' => $request->blok_id,
                'no_blok' => $request->no_blok,
                'tgl_boking' => $request->tgl_boking,
                'harga_boking' => $request->harga_boking,
            ]);

            Alert::toast('Data Boking Berhasil Diperbaharui', 'success')->autoClose(10000);
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Terjadi Kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);
            return redirect()->back();
        }
    }

    public function confirmBoking(Request $request)
    {
        try {
            // Validasi data input
            $request->validate([
                'id' => 'required|exists:bokings,id',
            ]);

            // Temukan booking berdasarkan ID
            $boking = Boking::where('id', $request->id)->firstOrFail();

            $boking->update([
                'status' => 'lunas', // Mengubah status menjadi lunas
                'tgl_lunas' => now()->timezone('Asia/Jakarta'),
            ]);

            Alert::toast('Konfirmasi Boking Berhasil', 'success')->autoClose(10000);
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Terjadi Kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);
            return redirect()->back();
        }
    }

    public function cancelBoking(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:bokings,id',
            ]);

            $boking = Boking::findOrFail($request->id);
            $boking->status = 'batal';
            $boking->save();

            Alert::toast('Pembatalan Boking Berhasil', 'success')->autoClose(10000);
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Terjadi Kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);
            return redirect()->back();
        }
    }

    public function destroyBoking(Request $request)
    {
        try {
            $boking = Boking::where('id', $request->id)->firstOrFail();

            $boking->delete($boking);

            Alert::toast('Data Boking Berhasil Dihapus', 'success')->autoClose(10000);

            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Terjadi kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);

            return redirect()->back();
        }
    }
}
