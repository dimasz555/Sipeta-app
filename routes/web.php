<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelolaKonsumenController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use RealRashid\SweetAlert\Facades\Alert;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/tes', function () {
//     Alert::error('Success Title','Success Message');
//     return view('welcome');
// });

// Route::get('/error', function () {
//     // This route does not exist and will trigger the 404 page
// });
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth', 'role:admin')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/profil', [ProfileController::class, 'index'])->name('profil');
    Route::put('/admin/edit-profil', [ProfileController::class, 'update'])->name('profil.update');
    Route::put('/admin/update-password', [ProfileController::class, 'updatePassword'])->name('password.update');

    Route::get('/admin/kelola-konsumen', [KelolaKonsumenController::class, 'index'])->name('index.konsumen');


});


Route::middleware('auth', 'role:konsumen')->group(function () {
});

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
