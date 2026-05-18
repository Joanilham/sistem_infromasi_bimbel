<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KonteksController;
use Illuminate\Support\Facades\Route;

use App\Models\Kantor;
use App\Models\Periode;

Route::get('/', function () {
    $masterData = \App\Models\Master::first();
    $pakets = \App\Models\PaketBimbingan::where('is_featured', true)->orderBy('urutan')->get();
    if($pakets->isEmpty()) {
        $pakets = \App\Models\PaketBimbingan::orderBy('urutan')->limit(3)->get();
    }
    $testimonials = \App\Models\Testimonial::where('is_active', true)->latest()->get();
    $faqs = \App\Models\Faq::where('is_active', true)->orderBy('urutan')->get();
    $galleries = \App\Models\Gallery::orderBy('urutan')->get();

    return view('welcome', compact('masterData', 'pakets', 'testimonials', 'faqs', 'galleries'));
})->name('welcome');

Route::get('/paket/{id}', function ($id) {
    $paket = \App\Models\PaketBimbingan::findOrFail($id);
    $masterData = \App\Models\Master::first();
    $testimonials = \App\Models\Testimonial::where('is_active', true)->latest()->limit(5)->get();
    return view('paket.detail', compact('paket', 'masterData', 'testimonials'));
})->name('paket.detail');

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
    Route::get('/logout', [AuthController::class, 'logout']);

    // ----------------------------------------------------------
    // AREA ADMINISTRATOR & STAFF
    // ensure_role memastikan siswa/guru tidak bisa masuk ke sini
    // ----------------------------------------------------------
    Route::middleware('ensure_role:Super Admin,Admin')->group(function () {

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

            $isSuperAdmin = auth()->check() && strtolower(auth()->user()->level) === 'super admin';
            $filterKantorId = $isSuperAdmin ? null : $kantorId;

            $pendaftaranMenunggu = \App\Models\PendaftaranSiswa::where('status', 'menunggu')
                ->when($filterKantorId, fn($q) => $q->where('kantor_id', $filterKantorId))
                ->count();
            $pembayaranBelumDikonfirmasi = \App\Models\PembayaranPendaftaran::where('status', 'menunggu')
                ->whereHas('pendaftaranSiswa', fn($q) => $q
                    ->when($filterKantorId, fn($q2) => $q2->where('kantor_id', $filterKantorId))
                )
                ->count();

            // Auto-create missing PembayaranSiswa records for active students in context
            if ($kantorId && $periodeId) {
                $studentsWithoutBilling = \App\Models\PesertaDidik::aktif()
                    ->inContext()
                    ->whereDoesntHave('pembayaran')
                    ->get();

                foreach ($studentsWithoutBilling as $student) {
                    $student->pembayaran()->create([
                        'total_harus_dibayar' => $student->paketBimbingan?->nominal ?? 0,
                        'biaya_pendaftaran'   => 0,
                        'diskon_persen'       => 0,
                        'diskon_nominal'      => 0,
                    ]);
                }
            }

            // Tagihan Jatuh Tempo Count
            $tagihanJatuhTempoCount = 0;
            if ($kantorId && $periodeId) {
                $tagihanRaw = \App\Models\PembayaranSiswa::with('transaksi')
                    ->whereHas('pesertaDidik', fn($q) => $q->inContext()->aktif())
                    ->where(function($q) {
                        $q->where('batas_waktu', '<=', \Carbon\Carbon::now()->addDays(7))
                          ->orWhereNull('batas_waktu');
                    })
                    ->get();
                $tagihanJatuhTempoCount = $tagihanRaw->filter(fn($p) => $p->kekurangan > 0)->count();
            }

            // Lead Tracking: 10 Pendaftar Terbaru
            $recentPendaftaran = \App\Models\PendaftaranSiswa::with(['paketBimbingan', 'pembayaran'])
                ->when($filterKantorId, fn($q) => $q->where('kantor_id', $filterKantorId))
                ->latest()
                ->limit(10)
                ->get();

            // --- DATA UNTUK GRAFIK (PESERTA DIDIK & KEUANGAN) ---
            $selectedPeriodeObj = $periodeId ? Periode::find($periodeId) : null;
            $yearStart = null;
            $yearEnd = null;
            if ($selectedPeriodeObj) {
                if (preg_match('/(\d{4})\/(\d{4})/', $selectedPeriodeObj->tahun_periode, $matches)) {
                    $yearStart = (int) $matches[1];
                    $yearEnd = (int) $matches[2];
                } elseif (preg_match('/(\d{4})/', $selectedPeriodeObj->tahun_periode, $matches)) {
                    $yearStart = (int) $matches[1];
                    $yearEnd = $yearStart;
                }
            }

            if (!$yearStart) {
                $yearStart = (int) date('Y');
                $yearEnd = $yearStart + 1;
            }

            $monthsList = [];
            $labels = [];
            if ($yearStart === $yearEnd) {
                for ($m = 1; $m <= 12; $m++) {
                    $monthsList[] = sprintf('%04d-%02d', $yearStart, $m);
                    $labels[] = \Carbon\Carbon::create($yearStart, $m, 1)->translatedFormat('F Y');
                }
            } else {
                for ($m = 7; $m <= 12; $m++) {
                    $monthsList[] = sprintf('%04d-%02d', $yearStart, $m);
                    $labels[] = \Carbon\Carbon::create($yearStart, $m, 1)->translatedFormat('F Y');
                }
                for ($m = 1; $m <= 6; $m++) {
                    $monthsList[] = sprintf('%04d-%02d', $yearEnd, $m);
                    $labels[] = \Carbon\Carbon::create($yearEnd, $m, 1)->translatedFormat('F Y');
                }
            }

            $pesertaMasukData = array_fill_keys($monthsList, 0);
            $pesertaKeluarData = array_fill_keys($monthsList, 0);
            $uangMasukData = array_fill_keys($monthsList, 0);
            $uangKeluarData = array_fill_keys($monthsList, 0);

            // 1. Peserta Didik
            $pesertas = \App\Models\PesertaDidik::inContext()
                ->where('periode_id', $periodeId)
                ->get();

            foreach ($pesertas as $p) {
                if ($p->created_at) {
                    $key = $p->created_at->format('Y-m');
                    if (array_key_exists($key, $pesertaMasukData)) {
                        $pesertaMasukData[$key]++;
                    }
                }
                if ($p->tanggal_keluar) {
                    $key = \Carbon\Carbon::parse($p->tanggal_keluar)->format('Y-m');
                    if (array_key_exists($key, $pesertaKeluarData)) {
                        $pesertaKeluarData[$key]++;
                    }
                }
            }

            // 2. Keuangan - Transaksi Pembayaran Siswa (Uang Masuk)
            $transaksiSpp = \App\Models\TransaksiPembayaran::whereHas('pembayaranSiswa.pesertaDidik', function($q) use ($kantorId, $periodeId) {
                    $q->where('kantor_id', $kantorId)->where('periode_id', $periodeId);
                })
                ->whereBetween('tanggal', [$yearStart === $yearEnd ? "{$yearStart}-01-01" : "{$yearStart}-07-01", $yearStart === $yearEnd ? "{$yearStart}-12-31" : "{$yearEnd}-06-30"])
                ->get();

            foreach ($transaksiSpp as $t) {
                if ($t->tanggal) {
                    $key = \Carbon\Carbon::parse($t->tanggal)->format('Y-m');
                    if (array_key_exists($key, $uangMasukData)) {
                        $uangMasukData[$key] += (int) $t->nominal;
                    }
                }
            }

            // 3. Keuangan - Pemasukan Lainnya (Uang Masuk)
            $pemasukanLain = \App\Models\Pemasukan::whereBetween('tanggal', [$yearStart === $yearEnd ? "{$yearStart}-01-01" : "{$yearStart}-07-01", $yearStart === $yearEnd ? "{$yearStart}-12-31" : "{$yearEnd}-06-30"])
                ->get();

            foreach ($pemasukanLain as $pl) {
                if ($pl->tanggal) {
                    $key = \Carbon\Carbon::parse($pl->tanggal)->format('Y-m');
                    if (array_key_exists($key, $uangMasukData)) {
                        $uangMasukData[$key] += (int) $pl->nominal;
                    }
                }
            }

            // 4. Keuangan - Pengeluaran (Uang Keluar)
            $pengeluaranList = \App\Models\Pengeluaran::whereBetween('tanggal', [$yearStart === $yearEnd ? "{$yearStart}-01-01" : "{$yearStart}-07-01", $yearStart === $yearEnd ? "{$yearStart}-12-31" : "{$yearEnd}-06-30"])
                ->get();

            foreach ($pengeluaranList as $pg) {
                if ($pg->tanggal) {
                    $key = \Carbon\Carbon::parse($pg->tanggal)->format('Y-m');
                    if (array_key_exists($key, $uangKeluarData)) {
                        $uangKeluarData[$key] += (int) $pg->nominal;
                    }
                }
            }

            $chartLabels = $labels;
            $chartPesertaMasuk = array_values($pesertaMasukData);
            $chartPesertaKeluar = array_values($pesertaKeluarData);
            $chartUangMasuk = array_values($uangMasukData);
            $chartUangKeluar = array_values($uangKeluarData);

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
                'pembayaranBelumDikonfirmasi',
                'tagihanJatuhTempoCount',
                'recentPendaftaran',
                'chartLabels',
                'chartPesertaMasuk',
                'chartPesertaKeluar',
                'chartUangMasuk',
                'chartUangKeluar'
            ));
        })->name('dashboard');


        // Ganti konteks kantor & periode aktif
        Route::post('/session/konteks', [KonteksController::class, 'update'])->name('session.konteks');

        // Profil Admin/Staff
        Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

        // Verifikasi Pendaftaran Siswa (Bisa diakses Super Admin & Admin)
        Route::get('/admin/pendaftaran', [App\Http\Controllers\PendaftaranController::class, 'adminIndex'])->name('admin.pendaftaran.index');
        Route::get('/admin/pendaftaran/{pendaftaran}', [App\Http\Controllers\PendaftaranController::class, 'adminShow'])->name('admin.pendaftaran.show');
        Route::post('/admin/pendaftaran/{pendaftaran}/verifikasi', [App\Http\Controllers\PendaftaranController::class, 'adminVerifikasi'])->name('admin.pendaftaran.verifikasi');

        // ----------------------------------------------------------
        // KHUSUS ADMINISTRATOR
        // ----------------------------------------------------------
        Route::middleware('ensure_role:Super Admin')->group(function () {
            Route::resource('kantor', \App\Http\Controllers\KantorController::class);
            Route::patch('/kantor/{id}/restore', [\App\Http\Controllers\KantorController::class, 'restore'])->name('kantor.restore');
            Route::resource('periode', \App\Http\Controllers\PeriodeController::class);
            Route::patch('/periode/{id}/restore', [\App\Http\Controllers\PeriodeController::class, 'restore'])->name('periode.restore');
            Route::resource('pengguna', \App\Http\Controllers\PenggunaController::class);
            Route::patch('/pengguna/{id}/toggle-active', [\App\Http\Controllers\PenggunaController::class, 'toggleActive'])->name('pengguna.toggle-active');
            Route::get('/master', [\App\Http\Controllers\MasterController::class, 'index'])->name('master.index');
            Route::put('/master', [\App\Http\Controllers\MasterController::class, 'update'])->name('master.update');
            Route::post('/master/test-wa', [\App\Http\Controllers\MasterController::class, 'testWhatsApp'])->name('master.test.wa');

            // Landing Page Management
            Route::get('/admin/landing-page', [\App\Http\Controllers\Admin\LandingPageController::class, 'index'])->name('admin.landing-page.index');
            Route::post('/admin/landing-page/general', [\App\Http\Controllers\Admin\LandingPageController::class, 'updateGeneral'])->name('admin.landing-page.update-general');
            Route::post('/admin/landing-page/testimonial', [\App\Http\Controllers\Admin\LandingPageController::class, 'storeTestimonial'])->name('admin.landing-page.testimonial.store');
            Route::delete('/admin/landing-page/testimonial/{testimonial}', [\App\Http\Controllers\Admin\LandingPageController::class, 'destroyTestimonial'])->name('admin.landing-page.testimonial.destroy');
            Route::post('/admin/landing-page/faq', [\App\Http\Controllers\Admin\LandingPageController::class, 'storeFaq'])->name('admin.landing-page.faq.store');
            Route::delete('/admin/landing-page/faq/{faq}', [\App\Http\Controllers\Admin\LandingPageController::class, 'destroyFaq'])->name('admin.landing-page.faq.destroy');
            
            // Gallery
            Route::post('/admin/landing-page/gallery', [\App\Http\Controllers\Admin\LandingPageController::class, 'storeGallery'])->name('admin.landing-page.gallery.store');
            Route::delete('/admin/landing-page/gallery/{gallery}', [\App\Http\Controllers\Admin\LandingPageController::class, 'destroyGallery'])->name('admin.landing-page.gallery.destroy');

            // Backup Database
            Route::get('/admin/backup', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('admin.backup.index');
            Route::post('/admin/backup', [\App\Http\Controllers\Admin\BackupController::class, 'create'])->name('admin.backup.create');
            Route::get('/admin/backup/download/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'download'])->name('admin.backup.download');
            Route::delete('/admin/backup/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'destroy'])->name('admin.backup.destroy');
            Route::post('/admin/backup/upload', [\App\Http\Controllers\Admin\BackupController::class, 'upload'])->name('admin.backup.upload');
            Route::post('/admin/backup/restore/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'restore'])->name('admin.backup.restore');
            Route::post('/admin/backup/toggle', [\App\Http\Controllers\Admin\BackupController::class, 'toggle'])->name('admin.backup.toggle');
        });

        // ----------------------------------------------------------
        // ADMINISTRATOR & STAFF: Fitur Operasional (wajib konteks)
        // ----------------------------------------------------------
        Route::middleware('konteks')->group(function () {
            Route::resource('paket-bimbingan', \App\Http\Controllers\PaketBimbinganController::class)->except(['show']);
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

            // ── Keuangan ──────────────────────────────────────────────────
            Route::prefix('keuangan')->name('keuangan.')->group(function () {

                // Pembayaran Siswa
                Route::get('/pembayaran', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'index'])->name('pembayaran.index');
                Route::get('/pembayaran/{pesertaDidik}', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'show'])->name('pembayaran.show');
                Route::put('/pembayaran/{pembayaranSiswa}', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'update'])->name('pembayaran.update');
                Route::post('/pembayaran/{pembayaranSiswa}/transaksi', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'storeTransaksi'])->name('pembayaran.transaksi.store');
                Route::get('/transaksi/{transaksiPembayaran}/edit', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'editTransaksi'])->name('transaksi.edit');
                Route::put('/transaksi/{transaksiPembayaran}', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'updateTransaksi'])->name('transaksi.update');
                Route::delete('/transaksi/{transaksiPembayaran}', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'destroyTransaksi'])->name('transaksi.destroy');

                // Pemasukan — static routes FIRST, parameter routes AFTER
                Route::get('/pemasukan', [\App\Http\Controllers\Keuangan\PemasukanController::class, 'index'])->name('pemasukan.index');
                Route::post('/pemasukan', [\App\Http\Controllers\Keuangan\PemasukanController::class, 'store'])->name('pemasukan.store');
                Route::get('/pemasukan/kategori', [\App\Http\Controllers\Keuangan\PemasukanController::class, 'indexKategori'])->name('pemasukan.kategori.index');
                Route::post('/pemasukan/kategori', [\App\Http\Controllers\Keuangan\PemasukanController::class, 'storeKategori'])->name('pemasukan.kategori.store');
                Route::delete('/pemasukan/kategori/{kategoriPemasukan}', [\App\Http\Controllers\Keuangan\PemasukanController::class, 'destroyKategori'])->name('pemasukan.kategori.destroy');
                Route::get('/pemasukan/{pemasukan}/edit', [\App\Http\Controllers\Keuangan\PemasukanController::class, 'edit'])->name('pemasukan.edit');
                Route::put('/pemasukan/{pemasukan}', [\App\Http\Controllers\Keuangan\PemasukanController::class, 'update'])->name('pemasukan.update');
                Route::delete('/pemasukan/{pemasukan}', [\App\Http\Controllers\Keuangan\PemasukanController::class, 'destroy'])->name('pemasukan.destroy');

                // Pengeluaran — static routes FIRST, parameter routes AFTER
                Route::get('/pengeluaran', [\App\Http\Controllers\Keuangan\PengeluaranController::class, 'index'])->name('pengeluaran.index');
                Route::post('/pengeluaran', [\App\Http\Controllers\Keuangan\PengeluaranController::class, 'store'])->name('pengeluaran.store');
                Route::get('/pengeluaran/kategori', [\App\Http\Controllers\Keuangan\PengeluaranController::class, 'indexKategori'])->name('pengeluaran.kategori.index');
                Route::post('/pengeluaran/kategori', [\App\Http\Controllers\Keuangan\PengeluaranController::class, 'storeKategori'])->name('pengeluaran.kategori.store');
                Route::delete('/pengeluaran/kategori/{kategoriPengeluaran}', [\App\Http\Controllers\Keuangan\PengeluaranController::class, 'destroyKategori'])->name('pengeluaran.kategori.destroy');
                Route::get('/pengeluaran/{pengeluaran}/edit', [\App\Http\Controllers\Keuangan\PengeluaranController::class, 'edit'])->name('pengeluaran.edit');
                Route::put('/pengeluaran/{pengeluaran}', [\App\Http\Controllers\Keuangan\PengeluaranController::class, 'update'])->name('pengeluaran.update');
                Route::delete('/pengeluaran/{pengeluaran}', [\App\Http\Controllers\Keuangan\PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');

                // Tagihan
                Route::get('/tagihan', [\App\Http\Controllers\Keuangan\TagihanController::class, 'index'])->name('tagihan.index');
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
