<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Webhook Telegram Bot (Dikecualikan dari RestrictApiAccess agar Telegram server bisa memanggil)
Route::post('/telegram/webhook', [\App\Http\Controllers\Api\TelegramWebhookController::class, 'handle']);

Route::middleware([\App\Http\Middleware\RestrictApiAccess::class])->group(function () {

    Route::get('/', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'API is running smoothly.'
        ]);
    });

    // Publik API (Dengan proteksi Rate Limiting 5x percobaan per menit)
    Route::middleware('throttle:5,1')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/landing', [\App\Http\Controllers\Api\LandingApiController::class, 'index']);
    });

    // Authenticated API (menggunakan token & proteksi DDoS ringan 100 req/menit)
    Route::middleware(['auth:sanctum', 'throttle:100,1'])->group(function () {
        // Chat Routes
        Route::get('/chat/contacts', [\App\Http\Controllers\Api\ChatController::class, 'getContacts']);
        Route::get('/chat', [\App\Http\Controllers\Api\ChatController::class, 'getConversations']);
        Route::get('/chat/{userId}', [\App\Http\Controllers\Api\ChatController::class, 'getMessages']);
        Route::post('/chat', [\App\Http\Controllers\Api\ChatController::class, 'sendMessage']);

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);

        // Profile
        Route::post('/profile/update', [\App\Http\Controllers\Api\ProfileController::class, 'update']);
        Route::post('/profile/photo', [\App\Http\Controllers\Api\ProfileController::class, 'updatePhoto']);

        // Beranda & Informasi
        Route::get('/beranda', [\App\Http\Controllers\Api\BerandaController::class, 'index']);
        Route::get('/pengumuman', [\App\Http\Controllers\Api\PengumumanController::class, 'index']);

        // Keuangan (Tidak dibekukan agar siswa bisa cek dan bayar tagihan)
        Route::get('/keuangan/tagihan', [\App\Http\Controllers\Api\KeuanganController::class, 'tagihan']);
        Route::get('/keuangan/riwayat', [\App\Http\Controllers\Api\KeuanganController::class, 'riwayat']);
        Route::get('/keuangan/bank', [\App\Http\Controllers\Api\KeuanganController::class, 'bank']);
        Route::post('/keuangan/bayar', [\App\Http\Controllers\Api\KeuanganController::class, 'bayar']);

        // Guru API (Tidak dibekukan oleh tagihan)
        Route::get('/guru/bank-soal/form-data', [\App\Http\Controllers\Api\GuruBankSoalController::class, 'formData']);
        Route::get('/guru/bank-soal', [\App\Http\Controllers\Api\GuruBankSoalController::class, 'index']);
        Route::post('/guru/bank-soal', [\App\Http\Controllers\Api\GuruBankSoalController::class, 'store']);
        Route::delete('/guru/bank-soal/{id}', [\App\Http\Controllers\Api\GuruBankSoalController::class, 'destroy']);
        Route::get('/guru/ujian/form-data', [\App\Http\Controllers\Api\GuruUjianController::class, 'formData']);
        Route::get('/guru/ujian', [\App\Http\Controllers\Api\GuruUjianController::class, 'index']);
        Route::post('/guru/ujian', [\App\Http\Controllers\Api\GuruUjianController::class, 'store']);
        Route::get('/guru/ujian/{id}', [\App\Http\Controllers\Api\GuruUjianController::class, 'show']);
        Route::delete('/guru/ujian/{id}', [\App\Http\Controllers\Api\GuruUjianController::class, 'destroy']);
        Route::post('/guru/ujian/{id}/soal', [\App\Http\Controllers\Api\GuruUjianController::class, 'tambahSoal']);
        Route::delete('/guru/ujian/{id}/soal/{soalId}', [\App\Http\Controllers\Api\GuruUjianController::class, 'hapusSoal']);
        Route::post('/guru/ujian/{id}/peserta', [\App\Http\Controllers\Api\GuruUjianController::class, 'setPeserta']);
        Route::get('/guru/ujian/{id}/monitoring', [\App\Http\Controllers\Api\GuruUjianController::class, 'monitoring']);
        Route::get('/guru/ujian/{id}/koreksi/{peserta_id}', [\App\Http\Controllers\Api\GuruUjianController::class, 'koreksi']);
        Route::post('/guru/ujian/{id}/koreksi/{peserta_id}', [\App\Http\Controllers\Api\GuruUjianController::class, 'simpanKoreksi']);

        // Fitur yang dibekukan jika ada tunggakan (Semua fitur kelas/akademik/ujian diblokir)
        Route::middleware('check_payment')->group(function () {
            // Akademik
            Route::get('/jadwal', [\App\Http\Controllers\Api\AkademikController::class, 'jadwal']);
            Route::get('/absensi', [\App\Http\Controllers\Api\AkademikController::class, 'absensi']);
            Route::get('/absensi/today', [\App\Http\Controllers\Api\AkademikController::class, 'absensiToday']);
            
            // CBT / Ujian
            Route::get('/cbt/ujian', [\App\Http\Controllers\Api\CbtApiController::class, 'daftarUjian']);
            Route::get('/cbt/ujian/{id}/soal', [\App\Http\Controllers\Api\CbtApiController::class, 'ambilSoal']);
            Route::get('/cbt/ujian/{id}/hasil', [\App\Http\Controllers\Api\CbtApiController::class, 'hasilUjian']);
            Route::post('/cbt/ujian/{id}/jawab', [\App\Http\Controllers\Api\CbtApiController::class, 'simpanJawaban']);
            Route::post('/cbt/ujian/{id}/selesai', [\App\Http\Controllers\Api\CbtApiController::class, 'selesaiUjian']);
        });
    });

});

