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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class KelolaPembatalanController extends Controller
{
    public function index()
    {
        try {
            $boking = Boking::where('status', 'lunas')
                ->with('user')
                ->orderBy('tgl_boking', 'desc')
                ->get();

            $pembatalan = Pembatalan::with('pembelian.user') // Memuat data pembelian terkait
                ->whereHas('pembelian', function ($query) {
                    $query->where('status', 'batal'); // Pastikan pembelian yang dibatalkan
                })
                ->get();

            // Return view dengan data pembatalan
            return view('pages.admin.kelolaPembatalan', [
                'pembatalan' => $pembatalan,
                'boking' => $boking,
            ]);
        } catch (\Exception $e) {
            return response()->view('errors.404', [], 404);
        }
    }

    public function detail($id)
    {
        try {
            $decrypted_id = Crypt::decrypt($id);

            $pembatalan = Pembatalan::with(['pembelian.user', 'pembelian.boking.blok', 'pembelian.boking.project'])
                ->whereHas('pembelian', function ($query) {
                    $query->where('status', 'batal');
                })
                ->where('id', $decrypted_id)
                ->firstOrFail();

            return view('pages.admin.detailPembelian', [
                'pembelian' => $pembatalan->pembelian
            ]);
        } catch (DecryptException $e) {
            return response()->view('errors.404', [], 404);
        }
    }
}
