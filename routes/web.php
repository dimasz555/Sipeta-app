<?php

use App\Http\Controllers\BokingController;
use App\Http\Controllers\CicilanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelolaBokingController;
use App\Http\Controllers\KelolaKonsumenController;
use App\Http\Controllers\KelolaPembatalanController;
use App\Http\Controllers\kelolaPembayaranController;
use App\Http\Controllers\KelolaProjectController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Crypt;


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

// Route::get('/error', function () {
//     // This route does not exist and will trigger the 404 page
// });

// Route Admin
Route::middleware('auth', 'role:admin')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/profil', [ProfileController::class, 'index'])->name('admin.profil');
    Route::put('/admin/edit-profil', [ProfileController::class, 'update'])->name('admin.profil.update');
    Route::put('/admin/update-password', [ProfileController::class, 'updatePassword'])->name('admin.password.update');

    Route::get('/admin/kelola-konsumen', [KelolaKonsumenController::class, 'index'])->name('index.konsumen');
    Route::post('/admin/kelola-konsumen/tambah', [KelolaKonsumenController::class, 'store'])->name('tambah.konsumen');
    Route::put('/admin/kelola-konsumen/edit', [KelolaKonsumenController::class, 'update'])->name('edit.konsumen');
    Route::delete('/admin/kelola-konsumen/hapus', [KelolaKonsumenController::class, 'destroy'])->name('hapus.konsumen');
    Route::put('/admin/kelola-konsumen/reset-password', [KelolaKonsumenController::class, 'resetPassword'])->name('resetpassword.konsumen');

    Route::get('/admin/kelola-project', [KelolaProjectController::class, 'index'])->name('index.project');
    Route::post('/admin/kelola-project/tambah', [KelolaProjectController::class, 'storeProject'])->name('tambah.project');
    Route::put('/admin/kelola-project/edit', [KelolaProjectController::class, 'updateProject'])->name('edit.project');
    Route::delete('/admin/kelola-project/hapus', [KelolaProjectController::class, 'destroyProject'])->name('hapus.project');
    Route::post('/admin/kelola-project/tambah-blok', [KelolaProjectController::class, 'storeBlok'])->name('tambah.blok');
    Route::put('/admin/kelola-project/edit-blok', [KelolaProjectController::class, 'updateBlok'])->name('edit.blok');
    Route::delete('/admin/kelola-project/hapus-blok', [KelolaProjectController::class, 'destroyBlok'])->name('hapus.blok');

    Route::get('/admin/kelola-boking', [KelolaBokingController::class, 'index'])->name('index.boking');
    Route::get('/search-user', [KelolaBokingController::class, 'searchUser'])->name('search.user');
    Route::post('/admin/kelola-boking/tambah', [KelolaBokingController::class, 'store'])->name('tambah.boking');
    Route::put('/admin/kelola-boking/edit', [KelolaBokingController::class, 'updateBoking'])->name('edit.boking');
    Route::post('/admin/kelola-boking/konfirmasi', [KelolaBokingController::class, 'confirmBoking'])->name('confirm.boking');
    Route::post('/admin/kelola-boking/batal', [KelolaBokingController::class, 'cancelBoking'])->name('cancel.boking');
    Route::delete('/admin/kelola-boking/hapus', [KelolaBokingController::class, 'destroyBoking'])->name('hapus.boking');

    Route::get('/admin/kelola-pembelian', [kelolaPembayaranController::class, 'index'])->name('index.pembelian');
    Route::get('/search-user-boking', [kelolaPembayaranController::class, 'searchUserBoking'])->name('search.user.boking');
    Route::post('/admin/kelola-pembelian/tambah', [kelolaPembayaranController::class, 'store'])->name('tambah.pembelian');
    Route::get('/admin/kelola-pembelian/{id}', [kelolaPembayaranController::class, 'detail'])->name('pembelian.detail');
    Route::put('/admin/kelola-pembelian/edit', [kelolaPembayaranController::class, 'update'])->name('edit.pembelian');

    Route::get('/admin/kelola-pembatalan', [KelolaPembatalanController::class, 'index'])->name('index.pembatalan');


});


// Route Konsumen
Route::middleware(['auth', 'role:konsumen'])->group(function () {
    Route::get('/profil', [ProfileController::class, 'index'])->name('profil');
    Route::put('/edit-profil', [ProfileController::class, 'update'])->name('profil.update');
    Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('password.update');

    Route::get('/riwayat-boking', [BokingController::class, 'index'])->name('index.riwayat.boking');

    Route::get('/pembayaran-kavling', [CicilanController::class, 'index'])->name('index.pembayaran.kavling');
    Route::get('/pembayaran-kavling/{id}', [CicilanController::class, 'detail'])->name('pembayaran.kavling.detail');

});

require __DIR__ . '/auth.php';
