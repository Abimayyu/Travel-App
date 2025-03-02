<?php

use App\Http\Controllers\admin\JadwalViewController;
use App\Http\Controllers\admin\PesanananController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\user\RiwayatController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/jadwal', [JadwalViewController::class, 'index'])->name('jadwal.index');
    Route::middleware('role:admin')->get('/daftar-pesanan', [PesanananController::class, 'index'])->name('daftar.index');
    Route::middleware('role:pemesan')->get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::middleware('role:admin')->get('/jadwal/create', [JadwalViewController::class, 'create'])->name('jadwal.create');
    Route::middleware('role:admin')->get('/jadwal/edit/{id}', [JadwalViewController::class, 'edit'])->name('jadwal.edit');

    Route::middleware('role:pemesan')->get('/invoice/{id}', [RiwayatController::class, 'show'])->name('invoice.show');

});


require __DIR__.'/auth.php';
