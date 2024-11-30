<?php

namespace App\Http\Controllers;

use App\Models\Boking;
use App\Models\Cicilan;
use App\Models\Pembelian;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();

            // Pastikan user ada dan memiliki role
            if (!$user) {
                throw new \Exception('User not authenticated.');
            }

            // Menghitung jumlah total pengguna
            $totalUsers = User::count();

            // Menghitung jumlah pengguna dengan role admin
            $totalAdmin = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->count();

            // Menghitung jumlah pengguna dengan role 
            $totalKonsumen = User::whereHas('roles', function ($query) {
                $query->where('name', 'konsumen');
            })->count();

            // Mengambil aktivitas terbaru konsumen dalam membayar cicilan
            $recentActivities = Cicilan::whereHas('pembelian', function ($query) {
                $query->whereNotNull('user_id');
            })->where('status', 'lunas')->latest('tgl_bayar')->take(5)->get();

            // Ambil semua proyek dan hitung jumlah boking serta pembelian terkait
            $projects = Project::withCount(['bokings', 'bokings as pembelian_count' => function ($query) {
                // Menghitung jumlah pembelian terkait dengan boking (tanpa filter status)
                $query->whereHas('pembelian');
            }])->get();

            // Hitung total keseluruhan penjualan tanah (total harga dari semua pembelian)
            $totalPenjualan = Pembelian::where('status', '!=', 'batal')
                ->sum('harga');

            // Hitung total pemasukan
            $totalBoking = Boking::where('status', 'lunas')
                ->sum('harga_boking');

            $totalDP = Pembelian::where('status', '!=', 'batal')
                ->sum('dp');

            $totalCicilan = Cicilan::where('status', 'lunas')
                ->sum('harga_cicilan');

            $totalPemasukan = $totalBoking + $totalDP + $totalCicilan;

            // Hitung total piutang (sisa yang belum dibayar)
            $totalPiutang = $totalPenjualan - $totalPemasukan;

            // @dd($projects);

            return view('pages.admin.dashboard', [
                'totalUsers' => $totalUsers,
                'totalKonsumen' => $totalKonsumen,
                'totalAdmin' => $totalAdmin,
                'recentActivities' => $recentActivities,
                'projects' => $projects,
                'totalPenjualan' => $totalPenjualan,
                'totalPemasukan' => $totalPemasukan,
                'totalPiutang' => $totalPiutang,
            ]);
        } catch (\Exception $e) {
            // Log error (optional)
            Log::error('Dashboard error: ' . $e->getMessage());

            // Redirect ke halaman 404
            return response()->view('errors.404', [], 404);
        }
    }
}
