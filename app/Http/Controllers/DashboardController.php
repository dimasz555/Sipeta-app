<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
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



            return view('pages.admin.dashboard', [
                'totalUsers' => $totalUsers,
                'totalKonsumen' => $totalKonsumen,
                'totalAdmin' => $totalAdmin,
                // 'recentActivities' => $recentActivities,
            ]);
        } catch (\Exception $e) {
            // Log error (optional)
            Log::error('Dashboard error: ' . $e->getMessage());

            // Redirect ke halaman 404
            return response()->view('errors.404', [], 404);
        }
    }
}
