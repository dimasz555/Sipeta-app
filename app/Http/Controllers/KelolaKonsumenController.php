<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Laratrust\Models\Role;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;



class KelolaKonsumenController extends Controller
{
    public function index()
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Ambil semua user dengan role konsumen
        $konsumen = User::whereHasRole('konsumen')->get();

        return view('pages.admin.kelolaKonsumen', [
            'konsumen' => $konsumen,
            'user' => $user,
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Validasi data input
            $request->validate([
                'name' => 'required',
                'username' => ['required', 'string', 'min:5', 'max:255'],
                'phone' => 'required',
                'gender' => 'required',
            ]);

            // Cek apakah username sudah ada di user lain
            $existingUsername = User::where('username', $request->username)->first();
            if ($existingUsername) {
                Alert::toast('Username Sudah Terdaftar Oleh Akun Lain', 'error')->autoClose(10000);
                return redirect()->back();
            }

            // Cek apakah phone sudah ada di user lain
            $existingPhone = User::where('phone', $request->phone)->first();
            if ($existingPhone) {
                Alert::toast('Nomor Hp Sudah Terdaftar Oleh Akun Lain', 'error')->autoClose(10000);
                return redirect()->back();
            }

            // Membuat entri konsumen baru
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make('sipeta123'), // Password default
                'phone' => $request->phone,
                'gender' => $request->gender,
            ]);

            // Menambahkan role konsumen
            $konsumenRole = Role::where('name', 'konsumen')->first();
            $user->addRole($konsumenRole);

            Alert::toast('Data Konsumen Berhasil Ditambahkan.', 'success')->autoClose(10000);
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Terjadi Kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        // Validasi data input jika diperlukan
        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'phone' => 'required',
        ]);

        try {
            // Temukan user berdasarkan ID
            $user = User::where('id', $request->id)->firstOrFail();

            // Cek apakah username sudah ada di user lain (kecuali user saat ini)
            $existingUsername = User::where('username', $request->username)
                ->where('id', '!=', $user->id)
                ->first();
            if ($existingUsername) {
                // Redirect dengan alert toast jika username sudah digunakan
                Alert::toast('Username Sudah Terdaftar Oleh Akun Lain', 'error')->autoClose(10000);
                return redirect()->back();
            }

            // Cek apakah phone sudah ada di user lain (kecuali user saat ini)
            $existingPhone = User::where('phone', $request->phone)
                ->where('id', '!=', $user->id)
                ->first();
            if ($existingPhone) {
                // Redirect dengan alert toast jika phone sudah digunakan
                Alert::toast('Nomor Hp Sudah Terdaftar Oleh Akun Lain', 'error')->autoClose(10000);
                return redirect()->back();
            }

            // Update data user jika username dan phone belum digunakan
            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'gender' => $request->gender,
                'phone' => $request->phone,
            ]);

            // Redirect dan alert toast sukses
            Alert::toast('Data User Berhasil Diubah.', 'success')->autoClose(10000);
            return redirect()->back();
        } catch (\Exception $e) {
            // Menampilkan pesan kesalahan jika terjadi pengecualian
            Alert::toast('Terjadi kesalahan' . $e->getMessage(),  'error')->autoClose(10000);

            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        try {
            $konsumen = User::where('id', $request->id)->firstOrFail();

            $konsumen->delete($konsumen);

            Alert::toast('Data Konsumen Berhasil Dihapus.', 'success')->autoClose(10000);

            return redirect()->back();
        } catch (\Exception $e) {
            // Menampilkan pesan kesalahan jika terjadi pengecualian
            Alert::toast('Terjadi kesalahan: ' . $e->getMessage(), 'error')->autoClose(10000);

            return redirect()->back();
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $konsumen = User::where('id', $request->id)->firstOrFail();

            $konsumen->update([
                'password' => Hash::make('sipeta123')

            ]);

            Alert::toast('Password Konsumen Berhasil Direset', 'success')->autoClose(5000);
            return redirect()->back();
        } catch (\Exception $e) {
            // Menampilkan pesan kesalahan jika terjadi pengecualian
            Alert::toast('Terjadi kesalahan: ' . $e->getMessage(), 'error')->autoClose(5000);

            return redirect()->back();
        }
    }
}
