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
})->name('login')->middleware('guest');

Route::post('/login', [AuthController::class, 'authenticate'])->middleware('throttle:5,1');

// Lupa Password
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'store'])->name('password.email')->middleware('throttle:5,1');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'update'])->name('password.store');
});

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

        // Pusat Notifikasi
        Route::get('/notifikasi', [\App\Http\Controllers\NotifikasiController::class, 'index'])->name('notifikasi.index');

        // Dashboard Admin/Staff
        Route::get('/dashboard', function () {
            $kantorId = session('kantor_id');
            $periodeId = session('periode_id');

            // Data Peserta Baru (7 Hari Terakhir)
            $listPesertaBaru = \Illuminate\Support\Facades\Cache::remember("dash_list_peserta_baru_{$kantorId}_{$periodeId}", 3600, function() {
                return \App\Models\PesertaDidik::aktif()->inContext()
                    ->where('created_at', '>=', now()->subDays(7))
                    ->select('id', 'nama_lengkap', 'asal_sekolah', 'created_at')
                    ->latest()
                    ->get();
            });

            // Data Peserta Keluar
            $listPesertaKeluar = \Illuminate\Support\Facades\Cache::remember("dash_list_peserta_keluar_{$kantorId}_{$periodeId}", 3600, function() {
                return \App\Models\PesertaDidik::keluar()->inContext()
                    ->select('id', 'nama_lengkap', 'asal_sekolah', 'tanggal_keluar')
                    ->latest('tanggal_keluar')
                    ->get();
            });

            $totalPesertaAktif = \Illuminate\Support\Facades\Cache::rememberForever("dash_total_peserta_{$kantorId}_{$periodeId}", fn() => \App\Models\PesertaDidik::aktif()->inContext()->count());
            $totalPaketAktif   = \Illuminate\Support\Facades\Cache::rememberForever("dash_total_paket_{$kantorId}_{$periodeId}", fn() => \App\Models\PaketBimbingan::inContext()->count());
            $totalTenagaPengajar = \App\Models\User::where('level', 'guru')->inContext()->count();

            $selectedKantor  = session('kantor_id')  ? Kantor::find(session('kantor_id'))  : null;
            $selectedPeriode = session('periode_id') ? Periode::find(session('periode_id')) : null;

            $kantors  = \Illuminate\Support\Facades\Cache::remember('dash_kantors', 60, fn() => Kantor::all());
            $periodes = \Illuminate\Support\Facades\Cache::remember('dash_periodes', 60, fn() => Periode::all());

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
                'listPesertaBaru',
                'listPesertaKeluar',
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
            Route::get('/guru/export', [\App\Http\Controllers\GuruController::class, 'export'])->name('manajemen-guru.export');
            Route::get('/guru/keluar', [\App\Http\Controllers\GuruController::class, 'keluar'])->name('manajemen-guru.keluar');
            Route::get('/guru/keluar/export', [\App\Http\Controllers\GuruController::class, 'exportKeluar'])->name('manajemen-guru.keluar.export');
            Route::get('/guru/{id}/edit', [\App\Http\Controllers\GuruController::class, 'edit'])->name('manajemen-guru.edit');
            Route::resource('guru', \App\Http\Controllers\GuruController::class)->except(['edit', 'show'])->names([
                'index'   => 'manajemen-guru.index',
                'create'  => 'manajemen-guru.create',
                'store'   => 'manajemen-guru.store',
                'update'  => 'manajemen-guru.update',
                'destroy' => 'manajemen-guru.destroy',
            ]);

            // Absensi — Halaman Scan
            Route::get('/absensi/masuk',  [\App\Http\Controllers\AbsensiController::class, 'scanMasukPage'])->name('absensi.scan.masuk.page');
            Route::get('/absensi/pulang', [\App\Http\Controllers\AbsensiController::class, 'scanPulangPage'])->name('absensi.scan.pulang.page');
            Route::get('/absensi/rekap',  [\App\Http\Controllers\AbsensiController::class, 'rekap'])->name('absensi.rekap');
            // Absensi — API Scan (JSON)
            Route::post('/absensi/scan-masuk',  [\App\Http\Controllers\AbsensiController::class, 'scanMasuk'])->name('absensi.scan.masuk');
            Route::post('/absensi/scan-pulang', [\App\Http\Controllers\AbsensiController::class, 'scanPulang'])->name('absensi.scan.pulang');
            // Absensi — Export
            Route::get('/absensi/export/rekap', [\App\Http\Controllers\AbsensiController::class, 'exportRekap'])->name('absensi.export.rekap');

            // ── Manajemen Jadwal (Admin) ──
            Route::get('/admin/jadwal', [\App\Http\Controllers\Admin\JadwalController::class, 'index'])->name('admin.jadwal.index');
            Route::post('/admin/jadwal', [\App\Http\Controllers\Admin\JadwalController::class, 'store'])->name('admin.jadwal.store');
            Route::put('/admin/jadwal/{id}', [\App\Http\Controllers\Admin\JadwalController::class, 'update'])->name('admin.jadwal.update');
            Route::delete('/admin/jadwal/{id}', [\App\Http\Controllers\Admin\JadwalController::class, 'destroy'])->name('admin.jadwal.destroy');
            Route::get('/admin/jadwal/konflik', [\App\Http\Controllers\Admin\JadwalController::class, 'konflik'])->name('admin.jadwal.konflik');
            Route::post('/admin/jadwal/duplikasi', [\App\Http\Controllers\Admin\JadwalController::class, 'duplikasi'])->name('admin.jadwal.duplikasi');
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

        // ── Bank Soal ──
        Route::get('/bank-soal/template', [\App\Http\Controllers\Guru\BankSoalController::class, 'template'])->name('bank-soal.template');
        Route::post('/bank-soal/import', [\App\Http\Controllers\Guru\BankSoalController::class, 'import'])->name('bank-soal.import');
        Route::resource('bank-soal', \App\Http\Controllers\Guru\BankSoalController::class);
        Route::get('/bank-soal-bab', [\App\Http\Controllers\Guru\BankSoalController::class, 'getBabByMapel'])->name('bank-soal.bab');
        Route::post('/bank-soal-mapel', [\App\Http\Controllers\Guru\BankSoalController::class, 'storeMapel'])->name('bank-soal.mapel.store');
        Route::post('/bank-soal-bab', [\App\Http\Controllers\Guru\BankSoalController::class, 'storeBab'])->name('bank-soal.bab.store');

        // ── Manajemen Ujian ──
        Route::resource('ujian', \App\Http\Controllers\Guru\UjianController::class);
        Route::get('/ujian/{id}/soal', [\App\Http\Controllers\Guru\UjianController::class, 'kelolaSoal'])->name('ujian.soal');
        Route::post('/ujian/{id}/soal', [\App\Http\Controllers\Guru\UjianController::class, 'tambahSoal'])->name('ujian.soal.store');
        Route::delete('/ujian/{id}/soal/{soalId}', [\App\Http\Controllers\Guru\UjianController::class, 'hapusSoal'])->name('ujian.soal.destroy');
        Route::post('/ujian/{id}/soal/reorder', [\App\Http\Controllers\Guru\UjianController::class, 'reorderSoal'])->name('ujian.soal.reorder');
        Route::get('/ujian/{id}/peserta', [\App\Http\Controllers\Guru\UjianController::class, 'kelolaPeserta'])->name('ujian.peserta');
        Route::post('/ujian/{id}/peserta', [\App\Http\Controllers\Guru\UjianController::class, 'setPeserta'])->name('ujian.peserta.store');
        Route::patch('/ujian/{id}/publish', [\App\Http\Controllers\Guru\UjianController::class, 'publish'])->name('ujian.publish');
        Route::patch('/ujian/{id}/arsipkan', [\App\Http\Controllers\Guru\UjianController::class, 'arsipkan'])->name('ujian.arsipkan');
        Route::get('/ujian/{id}/monitoring', [\App\Http\Controllers\Guru\UjianController::class, 'monitoring'])->name('ujian.monitoring');

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

        // QR Absensi Dinamis
        Route::get('/qr', [\App\Http\Controllers\Siswa\QrController::class, 'show'])->name('qr.show');
        Route::get('/qr/token', [\App\Http\Controllers\Siswa\QrController::class, 'token'])->name('qr.token');
        Route::get('/qr/status', [\App\Http\Controllers\Siswa\QrController::class, 'status'])->name('qr.status');

        // ── CBT Ujian Siswa ──
        Route::get('/ujian', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'index'])->name('ujian.index');
        Route::get('/ujian/riwayat', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'riwayat'])->name('ujian.riwayat');
        Route::get('/ujian/{id}', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'show'])->name('ujian.show');
        Route::post('/ujian/{id}/mulai', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'mulai'])->name('ujian.mulai');
        Route::get('/ujian/{id}/soal/{no}', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'soal'])->name('ujian.soal');
        Route::post('/ujian/{id}/jawab', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'simpanJawaban'])->name('ujian.jawab')->middleware('throttle:60,1');
        Route::post('/ujian/{id}/log-blur', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'logBlur'])->name('ujian.log-blur')->middleware('throttle:20,1');
        Route::post('/ujian/{id}/submit', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'submit'])->name('ujian.submit');
        Route::get('/ujian/{id}/hasil', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'hasil'])->name('ujian.hasil');
        // ── Nilai / Hasil ──
        Route::get('/hasil', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'riwayat'])->name('hasil.index');

        // ── Jadwal Siswa ──
        Route::get('/jadwal', [\App\Http\Controllers\Siswa\JadwalController::class, 'index'])->name('jadwal.index');
    });
});
