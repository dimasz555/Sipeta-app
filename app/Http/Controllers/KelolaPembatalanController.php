<?php

namespace App\Http\Controllers;

use App\Models\Boking;
use App\Models\User;
use App\Models\Pembelian;
use App\Models\Project;
use App\Models\Blok;
use App\Models\Cicilan;
use App\Models\Pembatalan;
use Illuminate\Http\Request;

class KelolaPembatalanController extends Controller
{
    public function index()
    {
        try {
            $pembatalan = Pembatalan::all();
            // $pembatalan = Pembatalan::all();
            return view('pages.admin.kelolaPembatalan', [
                'pembatalan' => $pembatalan,
            ]);
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
}
