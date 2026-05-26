<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Publik API (Dengan proteksi Rate Limiting 5x percobaan per menit)
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated API (menggunakan token & proteksi DDoS ringan 100 req/menit)
Route::middleware(['auth:sanctum', 'throttle:100,1'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Profile
    Route::post('/profile/update', [\App\Http\Controllers\Api\ProfileController::class, 'update']);
    Route::post('/profile/photo', [\App\Http\Controllers\Api\ProfileController::class, 'updatePhoto']);

    // Beranda & Informasi
    Route::get('/beranda', [\App\Http\Controllers\Api\BerandaController::class, 'index']);
    Route::get('/pengumuman', [\App\Http\Controllers\Api\PengumumanController::class, 'index']);

    // Akademik
    Route::get('/jadwal', [\App\Http\Controllers\Api\AkademikController::class, 'jadwal']);
    Route::get('/absensi', [\App\Http\Controllers\Api\AkademikController::class, 'absensi']);
    Route::get('/absensi/today', [\App\Http\Controllers\Api\AkademikController::class, 'absensiToday']);

    // Keuangan
    Route::get('/keuangan/tagihan', [\App\Http\Controllers\Api\KeuanganController::class, 'tagihan']);
    Route::get('/keuangan/riwayat', [\App\Http\Controllers\Api\KeuanganController::class, 'riwayat']);
    Route::get('/keuangan/bank', [\App\Http\Controllers\Api\KeuanganController::class, 'bank']);
    Route::post('/keuangan/bayar', [\App\Http\Controllers\Api\KeuanganController::class, 'bayar']);

    // CBT / Ujian
    Route::get('/cbt/ujian', [\App\Http\Controllers\Api\CbtApiController::class, 'daftarUjian']);
    Route::get('/cbt/ujian/{id}/soal', [\App\Http\Controllers\Api\CbtApiController::class, 'ambilSoal']);
    Route::post('/cbt/ujian/{id}/jawab', [\App\Http\Controllers\Api\CbtApiController::class, 'simpanJawaban']);
    Route::post('/cbt/ujian/{id}/selesai', [\App\Http\Controllers\Api\CbtApiController::class, 'selesaiUjian']);
});

