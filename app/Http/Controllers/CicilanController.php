<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Blok;
use App\Models\Pembelian;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

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
}
