<?php

use App\Http\Controllers\api\JadwalController;
use App\Http\Controllers\api\PesanController;
use App\Http\Controllers\api\RiwayatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('jadwal', [JadwalController::class, 'index']); // List semua tiket
    Route::get('jadwal/{id}', [JadwalController::class, 'show']); // Detail tiket
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });
    Route::post('/pesan-tiket/{id}', [PesanController::class, 'store']);
    Route::get('/riwayat-pemesanan', [RiwayatController::class, 'index']);
});

Route::middleware(['auth:sanctum', 'ability:admin'])->group(function () {
    Route::post('jadwal', [JadwalController::class, 'store']); // Tambah tiket
    Route::put('jadwal/{id}', [JadwalController::class, 'update']); // Update tiket
    Route::delete('jadwal/{id}', [JadwalController::class, 'destroy']); // Hapus tiket

    Route::get('daftar', [PesanController::class, 'index']);
    Route::post('/update-status-lunas/{pemesananId}', [PesanController::class, 'updateStatusLunas']);
});