<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\System\KonteksController;
use Illuminate\Support\Facades\Route;

use App\Models\MasterData\Kantor;
use App\Models\MasterData\Periode;

Route::any('/403-waf', function () {
    abort(403, 'Akses Ditolak oleh Web Application Firewall');
});

Route::get('/system/platform-verify/refresh', function () {
    \App\Support\Security\PlatformIntegrity::clearCache();
    $verification = \App\Support\Security\PlatformIntegrity::verify(true);

    if ($verification['valid']) {
        return redirect('/')->with('success', 'Selamat! Lisensi sistem telah berhasil diverifikasi dan aktif.');
    }

    return redirect('/system/platform-verify')->with('error', 'Pembaruan lisensi belum aktif: ' . $verification['reason']);
})->name('platform.verify.refresh');

Route::match(['get', 'post'], '/system/platform-verify', function (\Illuminate\Http\Request $request) {
    if ($request->isMethod('post')) {
        $licenseKey = $request->input('license_key', '');
        $result = \App\Support\Security\PlatformIntegrity::saveKey($licenseKey);

        if ($result['success']) {
            return redirect('/')->with('success', $result['message']);
        }

        return redirect('/system/platform-verify')->with('error', $result['message']);
    }

    $verification = \App\Support\Security\PlatformIntegrity::verify();
    if ($verification['valid']) {
        return redirect('/')->with('success', 'Lisensi sistem aktif dan terverifikasi.');
    }

    return response()->view('errors.license-lock', [
        'reason' => $verification['reason'],
        'installation_id' => $verification['installation_id'] ?? \App\Support\Security\PlatformIntegrity::getInstallationId(),
        'data' => $verification['data'] ?? null,
    ], 423);
})->name('platform.verify');

Route::get('/', function () {
    $data = \Illuminate\Support\Facades\Cache::remember('welcome_page_data', 3600, function () {
        $masterData = \App\Models\MasterData\Master::first();
        $pakets = \App\Models\Akademik\PaketBimbingan::where('is_featured', true)->orderBy('urutan')->get();
        if($pakets->isEmpty()) {
            $pakets = \App\Models\Akademik\PaketBimbingan::orderBy('urutan')->limit(3)->get();
        }
        $testimonials = \App\Models\System\Testimonial::where('is_active', true)->latest()->get();
        $faqs = \App\Models\System\Faq::where('is_active', true)->orderBy('urutan')->get();
        $galleries = \App\Models\System\Gallery::orderBy('urutan')->get();
        $features = \App\Models\System\Feature::orderBy('order_num')->get();
        $mitras = \App\Models\System\MitraLogo::orderBy('order_num')->get();
        $featuredGurus = \App\Models\User::where('level', 'Guru')->where('is_featured', true)->get();

        return compact('masterData', 'pakets', 'testimonials', 'faqs', 'galleries', 'features', 'mitras', 'featuredGurus');
    });

    return view('welcome', $data);
})->name('welcome');

Route::get('/program-bimbingan', function () {
    $masterData = \App\Models\MasterData\Master::first();
    $pakets = \App\Models\Akademik\PaketBimbingan::orderBy('urutan')->get();
    return view('paket.index', compact('masterData', 'pakets'));
})->name('paket.index');

Route::get('/paket/{id}', function ($id) {
    $paket = \App\Models\Akademik\PaketBimbingan::findOrFail($id);
    $masterData = \App\Models\MasterData\Master::first();
    $testimonials = \App\Models\System\Testimonial::where('is_active', true)->latest()->limit(5)->get();
    return view('paket.detail', compact('paket', 'masterData', 'testimonials'));
})->name('paket.detail');

Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', [AuthController::class, 'authenticate'])->middleware('throttle:5,1');

// Lupa Password
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'store'])->name('password.email')->middleware('throttle:5,1');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'update'])->name('password.store');
});

// Pendaftaran Siswa (publik, tanpa auth)
Route::prefix('daftar')->name('daftar.')->group(function () {
    Route::get('/step1',  [\App\Http\Controllers\Pendaftaran\PendaftaranController::class, 'step1'])->name('step1');
    Route::post('/step1', [\App\Http\Controllers\Pendaftaran\PendaftaranController::class, 'step1Store'])->name('step1.store');
    Route::get('/step2',  [\App\Http\Controllers\Pendaftaran\PendaftaranController::class, 'step2'])->name('step2');
    Route::post('/step2', [\App\Http\Controllers\Pendaftaran\PendaftaranController::class, 'step2Store'])->name('step2.store');
    Route::get('/step3',  [\App\Http\Controllers\Pendaftaran\PendaftaranController::class, 'step3'])->name('step3');
    Route::post('/step3', [\App\Http\Controllers\Pendaftaran\PendaftaranController::class, 'step3Store'])->name('step3.store');
    Route::get('/selesai',[\App\Http\Controllers\Pendaftaran\PendaftaranController::class, 'selesai'])->name('selesai');
    // Verifikasi email siswa pendaftar
    Route::get('/verifikasi-email/{token}', [\App\Http\Controllers\Pendaftaran\PendaftaranController::class, 'verifikasiEmail'])->name('verifikasi.email');
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
    Route::middleware('ensure_role:Super Admin,Admin')->group(function () {

        // Rute pemilihan konteks (wajib sebelum pakai fitur)
        Route::get('/select-context', [KonteksController::class, 'selectContext'])->name('konteks.select');

        // Pusat Notifikasi
        Route::get('/notifikasi', [\App\Http\Controllers\System\NotifikasiController::class, 'index'])->name('notifikasi.index');

        // Dashboard Admin/Staff
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');


        // Ganti konteks kantor & periode aktif
        Route::post('/session/konteks', [KonteksController::class, 'update'])->name('session.konteks');

        // Profil Admin/Staff
        Route::get('/profile', [\App\Http\Controllers\Auth\ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [\App\Http\Controllers\Auth\ProfileController::class, 'update'])->name('profile.update');

        // Verifikasi Pendaftaran Siswa (Bisa diakses Admin dengan hak kelola peserta)
        Route::middleware('check_permission:manage_peserta_didik')->group(function () {
            Route::get('/admin/pendaftaran', [\App\Http\Controllers\Pendaftaran\PendaftaranController::class, 'adminIndex'])->name('admin.pendaftaran.index');
            Route::get('/admin/pendaftaran/{pendaftaran}', [\App\Http\Controllers\Pendaftaran\PendaftaranController::class, 'adminShow'])->name('admin.pendaftaran.show');
            Route::post('/admin/pendaftaran/{pendaftaran}/verifikasi', [\App\Http\Controllers\Pendaftaran\PendaftaranController::class, 'adminVerifikasi'])->name('admin.pendaftaran.verifikasi');
        });

        // Audit Logs
        Route::get('/admin/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('admin.audit-logs.index');
        Route::delete('/admin/audit-logs/prune', [\App\Http\Controllers\Admin\AuditLogController::class, 'prune'])->name('admin.audit-logs.prune');
        Route::delete('/admin/audit-logs/{id}', [\App\Http\Controllers\Admin\AuditLogController::class, 'destroy'])->name('admin.audit-logs.destroy');


        // ----------------------------------------------------------
        // KHUSUS ADMINISTRATOR (Dan Admin dengan akses spesifik)
        // ----------------------------------------------------------
        Route::middleware(['ensure_role:Super Admin,Admin'])->group(function () {
            Route::middleware('check_permission:manage_bank')->group(function () {
                Route::resource('bank', \App\Http\Controllers\MasterData\BankController::class);
            });
            
            Route::middleware('check_permission:manage_kantor')->group(function () {
                Route::resource('kantor', \App\Http\Controllers\MasterData\KantorController::class);
                Route::patch('/kantor/{id}/restore', [\App\Http\Controllers\MasterData\KantorController::class, 'restore'])->name('kantor.restore');
            });
            
            Route::middleware('check_permission:manage_periode')->group(function () {
                Route::resource('periode', \App\Http\Controllers\MasterData\PeriodeController::class);
                Route::patch('/periode/{id}/restore', [\App\Http\Controllers\MasterData\PeriodeController::class, 'restore'])->name('periode.restore');
            });
            
            Route::middleware('check_permission:manage_pengguna')->group(function () {
                Route::resource('pengguna', \App\Http\Controllers\Auth\PenggunaController::class);
                Route::patch('/pengguna/{id}/toggle-active', [\App\Http\Controllers\Auth\PenggunaController::class, 'toggleActive'])->name('pengguna.toggle-active');
            });
            
            Route::middleware('check_permission:manage_master')->group(function () {
                Route::get('/master', [\App\Http\Controllers\MasterData\MasterController::class, 'index'])->name('master.index');
                Route::put('/master', [\App\Http\Controllers\MasterData\MasterController::class, 'update'])->name('master.update');
                Route::post('/master/test-wa', [\App\Http\Controllers\MasterData\MasterController::class, 'testWhatsApp'])->name('master.test.wa');
                Route::post('/master/test-email', [\App\Http\Controllers\MasterData\MasterController::class, 'testEmail'])->name('master.test.email');
            });

            // Landing Page Management
            Route::middleware('check_permission:manage_landing_page')->group(function () {
                Route::get('/admin/landing-page', [\App\Http\Controllers\Admin\LandingPageController::class, 'index'])->name('admin.landing-page.index');
                Route::post('/admin/landing-page/general', [\App\Http\Controllers\Admin\LandingPageController::class, 'updateGeneral'])->name('admin.landing-page.update-general');
                Route::post('/admin/landing-page/testimonial', [\App\Http\Controllers\Admin\LandingPageController::class, 'storeTestimonial'])->name('admin.landing-page.testimonial.store');
                Route::delete('/admin/landing-page/testimonial/{testimonial}', [\App\Http\Controllers\Admin\LandingPageController::class, 'destroyTestimonial'])->name('admin.landing-page.testimonial.destroy');
                Route::post('/admin/landing-page/faq', [\App\Http\Controllers\Admin\LandingPageController::class, 'storeFaq'])->name('admin.landing-page.faq.store');
                Route::delete('/admin/landing-page/faq/{faq}', [\App\Http\Controllers\Admin\LandingPageController::class, 'destroyFaq'])->name('admin.landing-page.faq.destroy');
                
                // Gallery
                Route::post('/admin/landing-page/gallery', [\App\Http\Controllers\Admin\LandingPageController::class, 'storeGallery'])->name('admin.landing-page.gallery.store');
                Route::delete('/admin/landing-page/gallery/{gallery}', [\App\Http\Controllers\Admin\LandingPageController::class, 'destroyGallery'])->name('admin.landing-page.gallery.destroy');

                // Feature
                Route::post('/admin/landing-page/feature', [\App\Http\Controllers\Admin\LandingPageController::class, 'storeFeature'])->name('admin.landing-page.feature.store');
                Route::delete('/admin/landing-page/feature/{feature}', [\App\Http\Controllers\Admin\LandingPageController::class, 'destroyFeature'])->name('admin.landing-page.feature.destroy');

                // Mitra Logo
                Route::post('/admin/landing-page/mitra', [\App\Http\Controllers\Admin\LandingPageController::class, 'storeMitra'])->name('admin.landing-page.mitra.store');
                Route::delete('/admin/landing-page/mitra/{mitra}', [\App\Http\Controllers\Admin\LandingPageController::class, 'destroyMitra'])->name('admin.landing-page.mitra.destroy');
            });

            // Backup Database
            Route::middleware('check_permission:manage_backup')->group(function () {
                Route::get('/admin/backup', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('admin.backup.index');
                Route::post('/admin/backup', [\App\Http\Controllers\Admin\BackupController::class, 'create'])->name('admin.backup.create');
                Route::get('/admin/backup/download/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'download'])->name('admin.backup.download');
                Route::delete('/admin/backup/bulk-destroy', [\App\Http\Controllers\Admin\BackupController::class, 'bulkDestroy'])->name('admin.backup.bulk_destroy');
                Route::delete('/admin/backup/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'destroy'])->name('admin.backup.destroy');
                Route::post('/admin/backup/upload', [\App\Http\Controllers\Admin\BackupController::class, 'upload'])->name('admin.backup.upload');
                Route::post('/admin/backup/restore/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'restore'])->name('admin.backup.restore');
                Route::post('/admin/backup/toggle', [\App\Http\Controllers\Admin\BackupController::class, 'toggle'])->name('admin.backup.toggle');
            });

            // Berita & Informasi (Pengumuman)
            Route::middleware('check_permission:manage_pengumuman')->group(function () {
                Route::resource('admin/pengumuman', \App\Http\Controllers\Admin\PengumumanController::class)->names('admin.pengumuman');
                Route::patch('/admin/pengumuman/{pengumuman}/toggle-active', [\App\Http\Controllers\Admin\PengumumanController::class, 'toggleActive'])->name('admin.pengumuman.toggle-active');
            });
        });

        // ----------------------------------------------------------
        // ADMINISTRATOR & STAFF: Fitur Operasional (wajib konteks)
        // ----------------------------------------------------------
        Route::middleware('konteks')->group(function () {
            Route::middleware('check_permission:manage_paket_bimbingan')->group(function () {
                Route::resource('paket-bimbingan', \App\Http\Controllers\Akademik\PaketBimbinganController::class)->except(['show']);
            });
            Route::middleware('check_permission:manage_peserta_didik')->group(function () {
                Route::get('/peserta-didik/export', [\App\Http\Controllers\Akademik\PesertaDidikController::class, 'export'])->name('peserta-didik.export');
                Route::get('/peserta-didik/keluar', [\App\Http\Controllers\Akademik\PesertaDidikController::class, 'keluar'])->name('peserta-didik.keluar');
                Route::get('/peserta-didik/keluar/export', [\App\Http\Controllers\Akademik\PesertaDidikController::class, 'exportKeluar'])->name('peserta-didik.keluar.export');
                Route::get('/peserta-didik/lulus', [\App\Http\Controllers\Akademik\PesertaDidikController::class, 'lulus'])->name('peserta-didik.lulus');
                Route::get('/peserta-didik/lulus/export', [\App\Http\Controllers\Akademik\PesertaDidikController::class, 'exportLulus'])->name('peserta-didik.lulus.export');
                Route::get('/peserta-didik/{id}/edit', [\App\Http\Controllers\Akademik\PesertaDidikController::class, 'edit'])->name('peserta-didik.edit');
                Route::delete('/peserta-didik/{id}/force', [\App\Http\Controllers\Akademik\PesertaDidikController::class, 'forceDestroy'])->name('peserta-didik.force-destroy');
                Route::resource('peserta-didik', \App\Http\Controllers\Akademik\PesertaDidikController::class)->except(['edit', 'show']);
                Route::resource('kelompok-belajar', \App\Http\Controllers\Akademik\KelompokBelajarController::class)->except(['show']);
            });
            Route::middleware('check_permission:manage_guru')->group(function () {
                Route::get('/guru/export', [\App\Http\Controllers\Akademik\GuruController::class, 'export'])->name('manajemen-guru.export');
                Route::get('/guru/keluar', [\App\Http\Controllers\Akademik\GuruController::class, 'keluar'])->name('manajemen-guru.keluar');
                Route::get('/guru/keluar/export', [\App\Http\Controllers\Akademik\GuruController::class, 'exportKeluar'])->name('manajemen-guru.keluar.export');
                Route::get('/guru/{id}/edit', [\App\Http\Controllers\Akademik\GuruController::class, 'edit'])->name('manajemen-guru.edit');
                Route::resource('guru', \App\Http\Controllers\Akademik\GuruController::class)->except(['edit', 'show'])->names([
                    'index'   => 'manajemen-guru.index',
                    'create'  => 'manajemen-guru.create',
                    'store'   => 'manajemen-guru.store',
                    'update'  => 'manajemen-guru.update',
                    'destroy' => 'manajemen-guru.destroy',
                ]);
            });

            // Absensi — Halaman Scan
            Route::middleware('check_permission:manage_absensi')->group(function () {
                Route::get('/absensi/masuk',  [\App\Http\Controllers\Akademik\AbsensiController::class, 'scanMasukPage'])->name('absensi.scan.masuk.page');
                Route::get('/absensi/pulang', [\App\Http\Controllers\Akademik\AbsensiController::class, 'scanPulangPage'])->name('absensi.scan.pulang.page');
                Route::get('/absensi/rekap',  [\App\Http\Controllers\Akademik\AbsensiController::class, 'rekap'])->name('absensi.rekap');
                // Absensi — API Scan (JSON)
                Route::post('/absensi/scan-masuk',  [\App\Http\Controllers\Akademik\AbsensiController::class, 'scanMasuk'])->name('absensi.scan.masuk');
                Route::post('/absensi/scan-pulang', [\App\Http\Controllers\Akademik\AbsensiController::class, 'scanPulang'])->name('absensi.scan.pulang');
                // Absensi — Manual Edit Admin
                Route::get('/absensi/detail/{id}', [\App\Http\Controllers\Akademik\AbsensiController::class, 'detail'])->name('absensi.detail');
                Route::post('/absensi/store-manual', [\App\Http\Controllers\Akademik\AbsensiController::class, 'storeManual'])->name('absensi.store.manual');
                // Absensi — Export
                Route::get('/absensi/export/rekap', [\App\Http\Controllers\Akademik\AbsensiController::class, 'exportRekap'])->name('absensi.export.rekap');
            });

            // ── Manajemen Jadwal (Admin) ──
            Route::middleware('check_permission:manage_jadwal')->group(function () {
                Route::get('/admin/jadwal', [\App\Http\Controllers\Admin\JadwalController::class, 'index'])->name('admin.jadwal.index');
                Route::get('/admin/jadwal/create', [\App\Http\Controllers\Admin\JadwalController::class, 'create'])->name('admin.jadwal.create');
                Route::post('/admin/jadwal', [\App\Http\Controllers\Admin\JadwalController::class, 'store'])->name('admin.jadwal.store');
                Route::get('/admin/jadwal/{id}/edit', [\App\Http\Controllers\Admin\JadwalController::class, 'edit'])->name('admin.jadwal.edit');
                Route::put('/admin/jadwal/{id}', [\App\Http\Controllers\Admin\JadwalController::class, 'update'])->name('admin.jadwal.update');
                Route::delete('/admin/jadwal/{id}', [\App\Http\Controllers\Admin\JadwalController::class, 'destroy'])->name('admin.jadwal.destroy');
                Route::get('/admin/jadwal/konflik', [\App\Http\Controllers\Admin\JadwalController::class, 'konflik'])->name('admin.jadwal.konflik');
                Route::post('/admin/jadwal/duplikasi', [\App\Http\Controllers\Admin\JadwalController::class, 'duplikasi'])->name('admin.jadwal.duplikasi');
            });

            // ── Rekapitulasi (Admin) ──
            Route::get('/admin/rekapitulasi', [\App\Http\Controllers\Admin\RekapitulasiController::class, 'index'])->name('admin.rekapitulasi.index');
                        Route::post('/admin/rekapitulasi/export-kustom', [\App\Http\Controllers\Admin\RekapitulasiController::class, 'exportKustom'])->name('admin.rekapitulasi.export-kustom');
Route::get('/admin/rekapitulasi/export', [\App\Http\Controllers\Admin\RekapitulasiController::class, 'export'])->name('admin.rekapitulasi.export');

            // ── Keuangan ──────────────────────────────────────────────────
            Route::prefix('keuangan')->name('keuangan.')->group(function () {

                // Pembayaran Siswa
                Route::middleware('check_permission:manage_pembayaran_siswa')->group(function () {
                    Route::get('/pembayaran', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'index'])->name('pembayaran.index');
                    Route::get('/pembayaran/{pesertaDidik}', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'show'])->name('pembayaran.show');
                    Route::put('/pembayaran/{pembayaranSiswa}', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'update'])->name('pembayaran.update');
                    Route::get('/pembayaran/{pembayaranSiswa}/rekap', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'cetakRekap'])->name('pembayaran.rekap');
                    Route::post('/pembayaran/{pembayaranSiswa}/transaksi', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'storeTransaksi'])->name('pembayaran.transaksi.store');
                    Route::get('/transaksi/{transaksiPembayaran}/edit', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'editTransaksi'])->name('transaksi.edit');
                    Route::put('/transaksi/{transaksiPembayaran}', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'updateTransaksi'])->name('transaksi.update');
                    Route::delete('/transaksi/{transaksiPembayaran}', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'destroyTransaksi'])->name('transaksi.destroy');
                    Route::post('/transaksi/{transaksiPembayaran}/verifikasi', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'verifikasiTransaksi'])->name('transaksi.verifikasi');
                    Route::post('/transaksi/{transaksiPembayaran}/tolak', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'tolakTransaksi'])->name('transaksi.tolak');
                    Route::get('/transaksi/{transaksiPembayaran}/struk', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'strukTransaksi'])->name('transaksi.struk');
                });

                // Pemasukan — static routes FIRST, parameter routes AFTER
                Route::middleware('check_permission:manage_pemasukan')->group(function () {
                    Route::get('/pemasukan', [\App\Http\Controllers\Keuangan\PemasukanController::class, 'index'])->name('pemasukan.index');
                    Route::post('/pemasukan', [\App\Http\Controllers\Keuangan\PemasukanController::class, 'store'])->name('pemasukan.store');
                    Route::get('/pemasukan/kategori', [\App\Http\Controllers\Keuangan\PemasukanController::class, 'indexKategori'])->name('pemasukan.kategori.index');
                    Route::post('/pemasukan/kategori', [\App\Http\Controllers\Keuangan\PemasukanController::class, 'storeKategori'])->name('pemasukan.kategori.store');
                    Route::delete('/pemasukan/kategori/{kategoriPemasukan}', [\App\Http\Controllers\Keuangan\PemasukanController::class, 'destroyKategori'])->name('pemasukan.kategori.destroy');
                    Route::get('/pemasukan/{pemasukan}/edit', [\App\Http\Controllers\Keuangan\PemasukanController::class, 'edit'])->name('pemasukan.edit');
                    Route::put('/pemasukan/{pemasukan}', [\App\Http\Controllers\Keuangan\PemasukanController::class, 'update'])->name('pemasukan.update');
                    Route::delete('/pemasukan/{pemasukan}', [\App\Http\Controllers\Keuangan\PemasukanController::class, 'destroy'])->name('pemasukan.destroy');
                });
                

                // Pengeluaran — static routes FIRST, parameter routes AFTER
                Route::middleware('check_permission:manage_pengeluaran')->group(function () {
                    Route::get('/pengeluaran', [\App\Http\Controllers\Keuangan\PengeluaranController::class, 'index'])->name('pengeluaran.index');
                    Route::post('/pengeluaran', [\App\Http\Controllers\Keuangan\PengeluaranController::class, 'store'])->name('pengeluaran.store');
                    Route::get('/pengeluaran/kategori', [\App\Http\Controllers\Keuangan\PengeluaranController::class, 'indexKategori'])->name('pengeluaran.kategori.index');
                    Route::post('/pengeluaran/kategori', [\App\Http\Controllers\Keuangan\PengeluaranController::class, 'storeKategori'])->name('pengeluaran.kategori.store');
                    Route::delete('/pengeluaran/kategori/{kategoriPengeluaran}', [\App\Http\Controllers\Keuangan\PengeluaranController::class, 'destroyKategori'])->name('pengeluaran.kategori.destroy');
                    Route::get('/pengeluaran/{pengeluaran}/edit', [\App\Http\Controllers\Keuangan\PengeluaranController::class, 'edit'])->name('pengeluaran.edit');
                    Route::put('/pengeluaran/{pengeluaran}', [\App\Http\Controllers\Keuangan\PengeluaranController::class, 'update'])->name('pengeluaran.update');
                    Route::delete('/pengeluaran/{pengeluaran}', [\App\Http\Controllers\Keuangan\PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');
                });

                // Tagihan
                Route::middleware('check_permission:manage_tagihan')->group(function () {
                    Route::get('/tagihan', [\App\Http\Controllers\Keuangan\TagihanController::class, 'index'])->name('tagihan.index');
                });
            });

        });
    });

    // ----------------------------------------------------------
    // AREA GURU
    // ensure_role memastikan hanya guru yang bisa masuk ke sini
    // ----------------------------------------------------------
    Route::middleware('ensure_role:guru')->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Guru\DashboardController::class, 'index'])->name('dashboard');

        // Profil Guru
        Route::get('/profile', [\App\Http\Controllers\Auth\ProfileController::class, 'editGuru'])->name('profile.edit');
        Route::patch('/profile', [\App\Http\Controllers\Auth\ProfileController::class, 'update'])->name('profile.update');

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
        Route::get('/ujian/{id}/koreksi/{peserta_id}', [\App\Http\Controllers\Guru\UjianController::class, 'koreksi'])->name('ujian.koreksi');
        Route::post('/ujian/{id}/koreksi/{peserta_id}', [\App\Http\Controllers\Guru\UjianController::class, 'simpanKoreksi'])->name('ujian.koreksi.store');

    });

    // ----------------------------------------------------------
    // AREA SISWA
    // ensure_role memastikan hanya siswa yang bisa masuk ke sini
    // ----------------------------------------------------------
    Route::middleware('ensure_role:siswa')->prefix('siswa')->name('siswa.')->group(function () {
        
        // ── Halaman Terkunci (Overdue) ──
        Route::get('/locked', function () {
            $user = auth()->user();
            $pembayaran = $user->pesertaDidik ? $user->pesertaDidik->pembayaran : null;
            return view('siswa.locked', compact('pembayaran'));
        })->name('locked');

        // ── Pembayaran Siswa (Pengecualian, selalu bisa diakses) ──
        Route::get('/pembayaran', [\App\Http\Controllers\Siswa\PembayaranController::class, 'index'])->name('pembayaran.index');
        Route::post('/pembayaran/konfirmasi', [\App\Http\Controllers\Siswa\PembayaranController::class, 'confirmPayment'])->name('pembayaran.konfirmasi');
        Route::get('/pembayaran/{transaksi}/nota', [\App\Http\Controllers\Siswa\PembayaranController::class, 'downloadNota'])->name('pembayaran.nota');

        // ── Rute yang Terkunci jika Cicilan Menunggak ──
        Route::middleware('check_installment_status')->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard');

            // Profil Siswa
            Route::get('/profile', [\App\Http\Controllers\Auth\ProfileController::class, 'editSiswa'])->name('profile.edit');
            Route::patch('/profile', [\App\Http\Controllers\Auth\ProfileController::class, 'update'])->name('profile.update');

            // QR Absensi Dinamis
            Route::get('/qr', [\App\Http\Controllers\Siswa\QrController::class, 'show'])->name('qr.show');
            Route::get('/qr/generate', [\App\Http\Controllers\Siswa\QrController::class, 'token'])->name('qr.token');
            Route::get('/qr/status', [\App\Http\Controllers\Siswa\QrController::class, 'status'])->name('qr.status');

            // ── CBT Ujian Siswa ──
            Route::get('/ujian', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'index'])->name('ujian.index');
            Route::get('/ujian/riwayat', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'riwayat'])->name('ujian.riwayat');
            Route::get('/ujian/{id}', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'show'])->name('ujian.show');
            Route::post('/ujian/{id}/mulai', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'mulai'])->name('ujian.mulai');
            Route::get('/ujian/{id}/soal/{no}', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'soal'])->name('ujian.soal');
            Route::post('/ujian/{id}/jawab', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'simpanJawaban'])->name('ujian.jawab')->middleware('throttle:5000,1');
            Route::post('/ujian/{id}/log-blur', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'logBlur'])->name('ujian.log-blur')->middleware('throttle:1000,1');
            Route::post('/ujian/{id}/submit', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'submit'])->name('ujian.submit');
            Route::get('/ujian/{id}/hasil', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'hasil'])->name('ujian.hasil');
            
            // ── Nilai / Hasil ──
            Route::get('/hasil', [\App\Http\Controllers\Siswa\CbtSiswaController::class, 'riwayat'])->name('hasil.index');

            // ── Jadwal Siswa ──
            Route::get('/jadwal', [\App\Http\Controllers\Siswa\JadwalController::class, 'index'])->name('jadwal.index');
        });
    });
});
