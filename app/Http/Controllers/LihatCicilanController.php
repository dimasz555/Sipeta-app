<?php

namespace App\Http\Controllers;

use App\Models\Boking;
use App\Models\User;
use App\Models\Pembelian;
use App\Models\Project;
use App\Models\Blok;
use App\Models\Cicilan;

use Illuminate\Http\Request;

class LihatCicilanController extends Controller
{
    public function index()
    {
        try {
            // Ambil data cicilan yang statusnya lunas
            $cicilanLunas = Cicilan::where('status', 'lunas')
                ->with([
                    'pembelian.user',
                    'pembelian.boking.project',
                    'pembelian.boking.blok'
                ])
                ->orderBy('tgl_bayar', 'desc')
                ->get()
                ->map(function ($cicilan) {
                    return [
                        'nama_user' => $cicilan->pembelian->user->name ?? '-',
                        'tanggal_bayar' => $cicilan->tgl_bayar,
                        'nama_project_blok' => ($cicilan->pembelian->boking->project->name ?? '-')
                            . ' - ' .
                            ($cicilan->pembelian->boking->no_blok ?? '-'),
                        'no_cicilan' => $cicilan->no_cicilan,
                        'jumlah_uang' => $cicilan->harga_cicilan,
                        'metode_pembayaran' => $cicilan->payment_by,
                    ];
                });

            return view('pages.admin.lihatCicilan', [
                'cicilanLunas' => $cicilanLunas
            ]);
        } catch (\Exception $e) {
            // Tangani kesalahan lain yang mungkin terjadi
            return response()->view('errors.404', [], 404);
        }
    }
}
