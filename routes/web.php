<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KonteksController;
use Illuminate\Support\Facades\Route;

use App\Models\Kantor;
use App\Models\Periode;

Route::get('/', function () {
    $masterData = \App\Models\Master::first();
    return view('welcome', compact('masterData'));
})->name('welcome');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'authenticate'])->middleware('throttle:5,1');

// Pendaftaran Siswa (publik, tanpa auth)
Route::prefix('daftar')->name('daftar.')->group(function () {
    Route::get('/step1',  [App\Http\Controllers\PendaftaranController::class, 'step1'])->name('step1');
    Route::post('/step1', [App\Http\Controllers\PendaftaranController::class, 'step1Store'])->name('step1.store');
    Route::get('/step2',  [App\Http\Controllers\PendaftaranController::class, 'step2'])->name('step2');
    Route::post('/step2', [App\Http\Controllers\PendaftaranController::class, 'step2Store'])->name('step2.store');
    Route::get('/step3',  [App\Http\Controllers\PendaftaranController::class, 'step3'])->name('step3');
    Route::post('/step3', [App\Http\Controllers\PendaftaranController::class, 'step3Store'])->name('step3.store');
    Route::get('/selesai',[App\Http\Controllers\PendaftaranController::class, 'selesai'])->name('selesai');
    // Verifikasi email siswa pendaftar
    Route::get('/verifikasi-email/{token}', [App\Http\Controllers\PendaftaranController::class, 'verifikasiEmail'])->name('verifikasi.email');
});



// ============================================================
// GRUP UTAMA: harus login
// ============================================================
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ----------------------------------------------------------
    // AREA ADMINISTRATOR & STAFF
    // ensure_role memastikan siswa/guru tidak bisa masuk ke sini
    // ----------------------------------------------------------
    Route::middleware('ensure_role:administrator,staff')->group(function () {

        // Rute pemilihan konteks (wajib sebelum pakai fitur)
        Route::get('/select-context', [KonteksController::class, 'selectContext'])->name('konteks.select');

        // Dashboard Admin/Staff
        Route::get('/dashboard', function () {
            $kantorId = session('kantor_id');
            $periodeId = session('periode_id');

            $totalPesertaAktif = \Illuminate\Support\Facades\Cache::rememberForever("dash_total_peserta_{$kantorId}_{$periodeId}", fn() => \App\Models\PesertaDidik::aktif()->inContext()->count());
            $pesertaBaru7Hari  = \Illuminate\Support\Facades\Cache::rememberForever("dash_peserta_baru_{$kantorId}_{$periodeId}", fn() => \App\Models\PesertaDidik::aktif()->inContext()
                ->where('created_at', '>=', now()->subDays(7))
                ->count());
            $pesertaKeluar     = \Illuminate\Support\Facades\Cache::rememberForever("dash_peserta_keluar_{$kantorId}_{$periodeId}", fn() => \App\Models\PesertaDidik::keluar()->inContext()->count());
            $totalPaketAktif   = \Illuminate\Support\Facades\Cache::rememberForever("dash_total_paket_{$kantorId}_{$periodeId}", fn() => \App\Models\PaketBimbingan::inContext()->count());
            $totalTenagaPengajar = \App\Models\User::where('level', 'guru')->count();

            $selectedKantor  = session('kantor_id')  ? Kantor::find(session('kantor_id'))  : null;
            $selectedPeriode = session('periode_id') ? Periode::find(session('periode_id')) : null;

            $kantors  = \Illuminate\Support\Facades\Cache::remember('dash_kantors', 60, fn() => Kantor::all());
            $periodes = \Illuminate\Support\Facades\Cache::remember('dash_periodes', 60, fn() => Periode::all());

            // ── Panel Pesan: data notifikasi untuk admin/staff ──
            // Filter berdasarkan kantor yang sedang dipilih jika ada
            $pendaftaranMenunggu = \App\Models\PendaftaranSiswa::where('status', 'menunggu')
                ->when($kantorId, fn($q) => $q->where('kantor_id', $kantorId))
                ->count();
            $pembayaranBelumDikonfirmasi = \App\Models\PembayaranPendaftaran::where('status', 'menunggu')
                ->whereHas('pendaftaranSiswa', fn($q) => $q
                    ->when($kantorId, fn($q2) => $q2->where('kantor_id', $kantorId))
                )
                ->count();

            return view('dashboard', compact(
                'totalPesertaAktif',
                'pesertaBaru7Hari',
                'pesertaKeluar',
                'totalPaketAktif',
                'totalTenagaPengajar',
                'selectedKantor',
                'selectedPeriode',
                'kantors',
                'periodes',
                'pendaftaranMenunggu',
                'pembayaranBelumDikonfirmasi'
            ));
        })->name('dashboard');


        // Ganti konteks kantor & periode aktif
        Route::post('/session/konteks', [KonteksController::class, 'update'])->name('session.konteks');

        // Profil Admin/Staff
        Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

        // ----------------------------------------------------------
        // KHUSUS ADMINISTRATOR
        // ----------------------------------------------------------
        Route::middleware('ensure_role:administrator')->group(function () {
            Route::resource('kantor', \App\Http\Controllers\KantorController::class);
            Route::patch('/kantor/{id}/restore', [\App\Http\Controllers\KantorController::class, 'restore'])->name('kantor.restore');
            Route::resource('periode', \App\Http\Controllers\PeriodeController::class);
            Route::patch('/periode/{id}/restore', [\App\Http\Controllers\PeriodeController::class, 'restore'])->name('periode.restore');
            Route::resource('pengguna', \App\Http\Controllers\PenggunaController::class);
            Route::patch('/pengguna/{id}/toggle-active', [\App\Http\Controllers\PenggunaController::class, 'toggleActive'])->name('pengguna.toggle-active');
            Route::get('/master', [\App\Http\Controllers\MasterController::class, 'index'])->name('master.index');
            Route::put('/master', [\App\Http\Controllers\MasterController::class, 'update'])->name('master.update');
            Route::post('/master/test-wa', [\App\Http\Controllers\MasterController::class, 'testWhatsApp'])->name('master.test.wa');

            // Verifikasi Pendaftaran Siswa
            Route::get('/admin/pendaftaran', [App\Http\Controllers\PendaftaranController::class, 'adminIndex'])->name('admin.pendaftaran.index');
            Route::get('/admin/pendaftaran/{pendaftaran}', [App\Http\Controllers\PendaftaranController::class, 'adminShow'])->name('admin.pendaftaran.show');
            Route::post('/admin/pendaftaran/{pendaftaran}/verifikasi', [App\Http\Controllers\PendaftaranController::class, 'adminVerifikasi'])->name('admin.pendaftaran.verifikasi');
        });

        // ----------------------------------------------------------
        // ADMINISTRATOR & STAFF: Fitur Operasional (wajib konteks)
        // ----------------------------------------------------------
        Route::middleware('konteks')->group(function () {
            Route::resource('paket-bimbingan', \App\Http\Controllers\PaketBimbinganController::class)->except(['create', 'edit', 'show']);
            Route::get('/peserta-didik/export', [\App\Http\Controllers\PesertaDidikController::class, 'export'])->name('peserta-didik.export');
            Route::get('/peserta-didik/keluar', [\App\Http\Controllers\PesertaDidikController::class, 'keluar'])->name('peserta-didik.keluar');
            Route::get('/peserta-didik/keluar/export', [\App\Http\Controllers\PesertaDidikController::class, 'exportKeluar'])->name('peserta-didik.keluar.export');
            Route::get('/peserta-didik/{id}/edit', [\App\Http\Controllers\PesertaDidikController::class, 'edit'])->name('peserta-didik.edit');
            Route::resource('peserta-didik', \App\Http\Controllers\PesertaDidikController::class)->except(['edit', 'show']);
            Route::resource('kelompok-belajar', \App\Http\Controllers\KelompokBelajarController::class)->except(['create', 'edit', 'show']);
            Route::get('/guru/export', [\App\Http\Controllers\GuruController::class, 'export'])->name('guru.export');
            Route::get('/guru/keluar', [\App\Http\Controllers\GuruController::class, 'keluar'])->name('guru.keluar');
            Route::get('/guru/keluar/export', [\App\Http\Controllers\GuruController::class, 'exportKeluar'])->name('guru.keluar.export');
            Route::get('/guru/{id}/edit', [\App\Http\Controllers\GuruController::class, 'edit'])->name('guru.edit');
            Route::resource('guru', \App\Http\Controllers\GuruController::class)->except(['edit', 'show']);

            // Absensi
            Route::get('/absensi', [\App\Http\Controllers\AbsensiController::class, 'index'])->name('absensi.index');
            Route::post('/absensi/{peserta}/masuk', [\App\Http\Controllers\AbsensiController::class, 'masuk'])->name('absensi.masuk');
            Route::post('/absensi/{peserta}/pulang', [\App\Http\Controllers\AbsensiController::class, 'pulang'])->name('absensi.pulang');
            Route::post('/absensi/scan-masuk', [\App\Http\Controllers\AbsensiController::class, 'scanMasuk'])->name('absensi.scan.masuk');
            Route::post('/absensi/scan-pulang', [\App\Http\Controllers\AbsensiController::class, 'scanPulang'])->name('absensi.scan.pulang');
            Route::get('/absensi/rekap', [\App\Http\Controllers\AbsensiController::class, 'rekap'])->name('absensi.rekap');
        });
    });

    // ----------------------------------------------------------
    // AREA GURU
    // ensure_role memastikan hanya guru yang bisa masuk ke sini
    // ----------------------------------------------------------
    Route::middleware('ensure_role:guru')->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Guru\DashboardController::class, 'index'])->name('dashboard');

        // Profil Guru
        Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'editGuru'])->name('profile.edit');
        Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

        // Jadwal Mata Pelajaran
        Route::get('/jadwal', [\App\Http\Controllers\Guru\JadwalController::class, 'index'])->name('jadwal.index');
        Route::post('/jadwal', [\App\Http\Controllers\Guru\JadwalController::class, 'store'])->name('jadwal.store');
        Route::put('/jadwal/{id}', [\App\Http\Controllers\Guru\JadwalController::class, 'update'])->name('jadwal.update');
        Route::delete('/jadwal/{id}', [\App\Http\Controllers\Guru\JadwalController::class, 'destroy'])->name('jadwal.destroy');

        // Bank Soal
        Route::get('/bank-soal', [\App\Http\Controllers\Guru\BankSoalController::class, 'index'])->name('bank-soal.index');
        Route::post('/bank-soal', [\App\Http\Controllers\Guru\BankSoalController::class, 'store'])->name('bank-soal.store');
        Route::get('/bank-soal/{id}', [\App\Http\Controllers\Guru\BankSoalController::class, 'show'])->name('bank-soal.show');
        Route::put('/bank-soal/{id}', [\App\Http\Controllers\Guru\BankSoalController::class, 'update'])->name('bank-soal.update');
        Route::delete('/bank-soal/{id}', [\App\Http\Controllers\Guru\BankSoalController::class, 'destroy'])->name('bank-soal.destroy');
        Route::post('/bank-soal/{id}/soal', [\App\Http\Controllers\Guru\BankSoalController::class, 'storeSoal'])->name('bank-soal.soal.store');
        Route::delete('/bank-soal/{bankSoalId}/soal/{soalId}', [\App\Http\Controllers\Guru\BankSoalController::class, 'destroySoal'])->name('bank-soal.soal.destroy');

        // Ujian
        Route::get('/ujian', [\App\Http\Controllers\Guru\UjianController::class, 'index'])->name('ujian.index');
        Route::get('/ujian/create', [\App\Http\Controllers\Guru\UjianController::class, 'create'])->name('ujian.create');
        Route::post('/ujian', [\App\Http\Controllers\Guru\UjianController::class, 'store'])->name('ujian.store');
        Route::get('/ujian/{id}', [\App\Http\Controllers\Guru\UjianController::class, 'show'])->name('ujian.show');
        Route::get('/ujian/{id}/edit', [\App\Http\Controllers\Guru\UjianController::class, 'edit'])->name('ujian.edit');
        Route::put('/ujian/{id}', [\App\Http\Controllers\Guru\UjianController::class, 'update'])->name('ujian.update');
        Route::delete('/ujian/{id}', [\App\Http\Controllers\Guru\UjianController::class, 'destroy'])->name('ujian.destroy');
        Route::post('/ujian/{id}/publish', [\App\Http\Controllers\Guru\UjianController::class, 'publish'])->name('ujian.publish');
        Route::post('/ujian/{id}/close', [\App\Http\Controllers\Guru\UjianController::class, 'close'])->name('ujian.close');
    });

    // ----------------------------------------------------------
    // AREA SISWA
    // ensure_role memastikan hanya siswa yang bisa masuk ke sini
    // ----------------------------------------------------------
    Route::middleware('ensure_role:siswa')->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard');

        // Profil Siswa
        Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'editSiswa'])->name('profile.edit');
        Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    });
});