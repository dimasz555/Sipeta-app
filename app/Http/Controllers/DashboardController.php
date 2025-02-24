<?php

namespace App\Http\Controllers;

use App\Models\Boking;
use App\Models\Cicilan;
use App\Models\Pembelian;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
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
            })->where('status', 'lunas')->latest('tgl_bayar')->take(10)->get();

            $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : now()->startOfYear();
            $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : now()->endOfYear();

            // Ambil semua proyek dan hitung jumlah boking serta pembelian terkait
            $projects = Project::withCount(['bokings', 'bokings as pembelian_count' => function ($query) {
                // Menghitung jumlah pembelian terkait dengan boking (tanpa filter status)
                $query->whereHas('pembelian');
            }])->get();

            // Hitung total keseluruhan penjualan tanah yang masuk dalam rentang tanggal
            $totalPenjualan = Pembelian::where('status', '!=', 'batal')
                ->whereBetween('tgl_pembelian', [$startDate, $endDate])
                ->sum('harga');

            // Hitung total pemasukan dalam rentang tanggal
            $totalBoking = Boking::where('status', 'lunas')
                ->whereBetween('tgl_lunas', [$startDate, $endDate])
                ->sum('harga_boking');

            $totalDP = Pembelian::where('status', '!=', 'batal')
                ->whereBetween('tgl_pembelian', [$startDate, $endDate])
                ->sum('dp');

            $totalCicilan = Cicilan::where('status', 'lunas')
                ->whereBetween('tgl_bayar', [$startDate, $endDate])
                ->sum('harga_cicilan');

            $totalPemasukan = $totalBoking + $totalDP + $totalCicilan;

            // Hitung total piutang (sisa yang belum dibayar)
            $totalPiutang = $totalPenjualan - $totalPemasukan;


            // Daftar metode pembayaran
            $paymentMethods = ['bca', 'bsi', 'cash', 'midtrans'];

            // Query untuk data pembayaran berdasarkan tanggal
            $paymentData = Cicilan::whereBetween('tgl_bayar', [$startDate, $endDate])
                ->where('status', 'lunas')
                ->selectRaw('payment_by, SUM(harga_cicilan) as total')
                ->groupBy('payment_by')
                ->pluck('total', 'payment_by')
                ->toArray();

            // Pastikan semua metode pembayaran memiliki nilai
            foreach ($paymentMethods as $method) {
                if (!isset($paymentData[$method])) {
                    $paymentData[$method] = 0;
                }
            }

            // Query booking yang statusnya lunas dan masuk dalam filter tanggal
            $bookingQuery = Boking::where('status', 'lunas')
                ->whereBetween('tgl_lunas', [$startDate, $endDate])
                ->get()
                ->groupBy('project_id');

            // Query DP yang masuk sesuai tanggal pembelian
            $dpQuery = Pembelian::whereBetween('tgl_pembelian', [$startDate, $endDate])
                ->where('status', '!=', 'batal')
                ->get()
                ->groupBy('boking_id');

            // Query cicilan yang lunas di tanggal yang difilter
            $cicilanQuery = Cicilan::where('status', 'lunas')
                ->whereBetween('tgl_bayar', [$startDate, $endDate])
                ->get()
                ->groupBy('pembelian_id');

            // Ambil semua project
            $projects = Project::all();

            $projectIncomeData = $projects->map(function ($project) use ($bookingQuery, $dpQuery, $cicilanQuery) {
                // Hitung pemasukan dari booking (status lunas)
                $bokingIncome = isset($bookingQuery[$project->id])
                    ? $bookingQuery[$project->id]->sum('harga_boking')
                    : 0;

                // Hitung pemasukan dari DP
                $dpIncome = $project->bokings->flatMap(function ($boking) use ($dpQuery) {
                    return isset($dpQuery[$boking->id])
                        ? $dpQuery[$boking->id]->pluck('dp')
                        : [];
                })->sum();

                // Hitung pemasukan dari cicilan
                $cicilanIncome = $project->bokings->flatMap(function ($boking) use ($cicilanQuery) {
                    return $boking->pembelian && isset($cicilanQuery[$boking->pembelian->id])
                        ? $cicilanQuery[$boking->pembelian->id]->pluck('harga_cicilan')
                        : [];
                })->sum();

                return [
                    'name' => $project->name,
                    'booking' => $bokingIncome,
                    'dp' => $dpIncome,
                    'cicilan' => $cicilanIncome,
                    'total' => $bokingIncome + $dpIncome + $cicilanIncome
                ];
            })->toArray();

            // Query khusus untuk data DP per project
            $projectDPData = $projects->map(function ($project) use ($dpQuery) {
                // Hitung total DP untuk project ini
                $dpTotal = $project->bokings->flatMap(function ($boking) use ($dpQuery) {
                    return isset($dpQuery[$boking->id])
                        ? $dpQuery[$boking->id]->pluck('dp')
                        : [];
                })->sum();

                return [
                    'name' => $project->name,
                    'dp' => $dpTotal
                ];
            })->toArray();

            // dd($projectDPData);

            return view('pages.admin.dashboard', [
                'totalUsers' => $totalUsers,
                'totalKonsumen' => $totalKonsumen,
                'totalAdmin' => $totalAdmin,
                'recentActivities' => $recentActivities,
                'projects' => $projects,
                'totalPenjualan' => $totalPenjualan,
                'totalPemasukan' => $totalPemasukan,
                'totalPiutang' => $totalPiutang,
                'paymentData' => $paymentData,
                'paymentMethods' => $paymentMethods,
                'startDate' => $startDate->format('Y-m-d'),
                'endDate' => $endDate->format('Y-m-d'),
                'projectIncomeData' => $projectIncomeData,
                'projectDPData' => $projectDPData,
            ]);
        } catch (\Exception $e) {
            // Log error (optional)
            Log::error('Dashboard error: ' . $e->getMessage());

            // Redirect ke halaman 404
            return response()->view('errors.404', [], 404);
        }
    }
}
