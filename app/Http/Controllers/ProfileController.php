<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Log;
use App\Models\User;





class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index()
    {
        return view('pages.profil');
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        // Validasi data input jika diperlukan
        $request->validate([
            'name' => 'required',
            'username' => 'required',
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
                Alert::toast('Username sudah digunakan oleh akun lain.', 'error')->autoClose(10000);
                return redirect()->back();
            }

            // Cek apakah phone sudah ada di user lain (kecuali user saat ini)
            $existingPhone = User::where('phone', $request->phone)
                ->where('id', '!=', $user->id)
                ->first();
            if ($existingPhone) {
                // Redirect dengan alert toast jika phone sudah digunakan
                Alert::toast('Nomor Hp sudah digunakan oleh akun lain.', 'error')->autoClose(10000);
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
            Alert::toast('Terjadi kesalahan: Silahkan coba edit profil anda kembali', 'error')->autoClose(10000);

            return redirect()->back();
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $validated = $request->validateWithBag('updatePassword', [
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed'],
            ], [
                'current_password.required' => 'Password lama wajib diisi.',
                'current_password.current_password' => 'Password lama tidak sesuai.',
                'password.required' => 'Password baru wajib diisi.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]);

            // Update password
            $request->user()->update([
                'password' => Hash::make($validated['password']),
            ]);
            // Flash message ke session
            Alert::toast('Password berhasil diubah.', 'success')->autoClose(5000);
        } catch (\Exception $e) {
            Alert::toast('Terjadi kesalahan: ' . $e->getMessage(), 'error')->autoClose(5000);
        }

        return redirect()->back();
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
