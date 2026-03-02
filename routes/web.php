<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

use App\Models\Kantor;
use App\Models\Periode;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    $kantors = Kantor::all();
    $periodes = Periode::all();
    return view('auth.login', compact('kantors', 'periodes'));
})->name('login');

Route::post('/login', [AuthController::class, 'authenticate'])->middleware('throttle:5,1');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $totalPesertaAktif = \App\Models\PesertaDidik::aktif()->count();
        $pesertaBaru7Hari  = \App\Models\PesertaDidik::aktif()
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        $totalPaketAktif   = \App\Models\PaketBimbingan::count();

        return view('dashboard', compact('totalPesertaAktif', 'pesertaBaru7Hari', 'totalPaketAktif'));
    })->name('dashboard');

    Route::middleware('role:administrator')->group(function () {
        Route::resource('kantor', \App\Http\Controllers\KantorController::class);
        Route::resource('periode', \App\Http\Controllers\PeriodeController::class);
        Route::resource('pengguna', \App\Http\Controllers\PenggunaController::class);
        Route::patch('/pengguna/{id}/toggle-active', [\App\Http\Controllers\PenggunaController::class, 'toggleActive'])->name('pengguna.toggle-active');
        Route::resource('paket-bimbingan', \App\Http\Controllers\PaketBimbinganController::class)->except(['create', 'edit', 'show']);
        Route::get('/peserta-didik/export', [\App\Http\Controllers\PesertaDidikController::class, 'export'])->name('peserta-didik.export');
        Route::get('/peserta-didik/keluar', [\App\Http\Controllers\PesertaDidikController::class, 'keluar'])->name('peserta-didik.keluar');
        Route::get('/peserta-didik/keluar/export', [\App\Http\Controllers\PesertaDidikController::class, 'exportKeluar'])->name('peserta-didik.keluar.export');
        Route::get('/peserta-didik/{id}/edit', [\App\Http\Controllers\PesertaDidikController::class, 'edit'])->name('peserta-didik.edit');
        Route::resource('peserta-didik', \App\Http\Controllers\PesertaDidikController::class)->except(['edit', 'show']);
        Route::resource('kelompok-belajar', \App\Http\Controllers\KelompokBelajarController::class)->except(['create', 'edit', 'show']);

        Route::get('/master', [\App\Http\Controllers\MasterController::class, 'index'])->name('master.index');
        Route::put('/master', [\App\Http\Controllers\MasterController::class, 'update'])->name('master.update');
    });

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
