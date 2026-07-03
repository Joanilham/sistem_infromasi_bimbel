<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\MasterData\Kantor;
use App\Models\MasterData\Periode;
use App\Models\Akademik\PesertaDidik;
use App\Models\Akademik\PaketBimbingan;
use App\Models\User;
use App\Models\Pendaftaran\PendaftaranSiswa;
use App\Models\Keuangan\PembayaranPendaftaran;
use App\Models\Keuangan\PembayaranSiswa;
use App\Models\Keuangan\TransaksiPembayaran;
use App\Models\Keuangan\Pemasukan;
use App\Models\Keuangan\Pengeluaran;
use App\Models\System\AuditLog;

class DashboardController extends Controller
{
    public function index()
    {
        $kantorId = session('kantor_id');
        $periodeId = session('periode_id');

        // Data Peserta Baru (7 Hari Terakhir)
        $listPesertaBaru = Cache::remember("dash_list_peserta_baru_{$kantorId}_{$periodeId}", 3600, function() {
            return PesertaDidik::aktif()->inContext()
                ->where('created_at', '>=', now()->subDays(7))
                ->select('id', 'nama_lengkap', 'asal_sekolah', 'created_at')
                ->latest()
                ->get();
        });

        // Data Peserta Keluar
        $listPesertaKeluar = Cache::remember("dash_list_peserta_keluar_{$kantorId}_{$periodeId}", 3600, function() {
            return PesertaDidik::keluar()->inContext()
                ->select('id', 'nama_lengkap', 'asal_sekolah', 'tanggal_keluar')
                ->latest('tanggal_keluar')
                ->get();
        });

        $totalPesertaAktif = Cache::remember("dash_total_peserta_{$kantorId}_{$periodeId}", 3600, fn() => PesertaDidik::aktif()->inContext()->count());
        $totalPaketAktif = Cache::remember("dash_total_paket_{$kantorId}_{$periodeId}", 3600, fn() => PaketBimbingan::count());
        $totalTenagaPengajar = Cache::remember("dash_total_guru_{$kantorId}_{$periodeId}", 1800, fn() => User::where('level', 'guru')->inContext()->count());

        // 5 Aktivitas Terbaru untuk Activity Log Widget di Dashboard
        $isSuperAdmin = auth()->check() && strtolower(auth()->user()->level) === 'super admin';
        $auditCacheKey = "dash_audit_logs_{$kantorId}_{$periodeId}_" . ($isSuperAdmin ? 'sa' : 'staff');
        $recentAuditLogs = Cache::remember($auditCacheKey, 300, function() use ($isSuperAdmin) {
            return AuditLog::with('user:id,name,level')
                ->when(!$isSuperAdmin, function ($query) {
                    $query->whereHas('user', function ($q) {
                        $q->whereRaw('LOWER(level) != ?', ['super admin']);
                    })->orWhereNull('user_id');
                })
                ->latest()
                ->limit(5)
                ->get();
        });

        $selectedKantor  = session('kantor_id')  ? Kantor::find(session('kantor_id'))  : null;
        $selectedPeriode = session('periode_id') ? Periode::find(session('periode_id')) : null;

        $kantors  = Cache::remember('dash_kantors', 60, fn() => Kantor::all());
        $periodes = Cache::remember('dash_periodes', 60, fn() => Periode::all());

        $filterKantorId = $kantorId === 'all' ? null : $kantorId;

        $pendaftaranMenunggu = Cache::remember("dash_pendaftaran_tunggu_{$kantorId}_{$periodeId}", 1800, function() use ($filterKantorId, $periodeId) {
            return PendaftaranSiswa::where('status', 'menunggu')
                ->when($filterKantorId, fn($q) => $q->where('kantor_id', $filterKantorId))
                ->when($periodeId, fn($q) => $q->where('periode_id', $periodeId))
                ->count();
        });

        $pembayaranBelumDikonfirmasi = Cache::remember("dash_pembayaran_tunggu_{$kantorId}_{$periodeId}", 1800, function() use ($filterKantorId, $periodeId) {
            return PembayaranPendaftaran::where('status', 'menunggu')
                ->whereHas('pendaftaranSiswa', fn($q) => $q
                    ->when($filterKantorId, fn($q2) => $q2->where('kantor_id', $filterKantorId))
                    ->when($periodeId, fn($q2) => $q2->where('periode_id', $periodeId))
                )
                ->count();
        });

        // Tagihan Jatuh Tempo Count
        $tagihanJatuhTempoCount = Cache::remember("dash_tagihan_jt_{$kantorId}_{$periodeId}", 1800, function() use ($kantorId, $periodeId) {
            if (!$kantorId || !$periodeId) return 0;
            return PembayaranSiswa::withSum(['transaksi' => fn($q) => $q->where('status', 'sukses')], 'nominal')
                ->whereHas('pesertaDidik', fn($q) => $q->inContext()->aktif())
                ->where(function($q) {
                    $q->where('batas_waktu', '<=', Carbon::now()->addDays(7))
                      ->orWhereNull('batas_waktu');
                })
                ->havingRaw('total_harus_dibayar > COALESCE(transaksi_sum_nominal, 0)')
                ->count();
        });

        // Lead Tracking: 10 Pendaftar Terbaru
        $recentPendaftaran = Cache::remember("dash_recent_pendaftaran_{$kantorId}_{$periodeId}", 1800, function() use ($filterKantorId, $periodeId) {
            return PendaftaranSiswa::with(['paketBimbingan', 'pembayaran'])
                ->when($filterKantorId, fn($q) => $q->where('kantor_id', $filterKantorId))
                ->when($periodeId, fn($q) => $q->where('periode_id', $periodeId))
                ->latest()
                ->limit(10)
                ->get();
        });

        // --- DATA UNTUK GRAFIK (PESERTA DIDIK & KEUANGAN) ---
        $chartData = $this->generateChartData($periodeId, $filterKantorId);
        
        $chartLabels        = $chartData['labels'];
        $chartPesertaMasuk  = $chartData['pesertaMasuk'];
        $chartPesertaKeluar = $chartData['pesertaKeluar'];
        $chartUangMasuk     = $chartData['uangMasuk'];
        $chartUangKeluar    = $chartData['uangKeluar'];

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
            'recentAuditLogs',
            'chartLabels',
            'chartPesertaMasuk',
            'chartPesertaKeluar',
            'chartUangMasuk',
            'chartUangKeluar'
        ));
    }

    // ═══════════════════════════════════════════════════════
    // PRIVATE HELPERS
    // ═══════════════════════════════════════════════════════

    /**
     * Generate data for Dashboard Charts (Peserta Didik & Keuangan).
     */
    private function generateChartData($periodeId, $filterKantorId): array
    {
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
            $startDateStr = "{$yearStart}-01-01";
            $endDateStr = "{$yearStart}-12-31";
            for ($m = 1; $m <= 12; $m++) {
                $monthsList[] = sprintf('%04d-%02d', $yearStart, $m);
                $labels[] = Carbon::create($yearStart, $m, 1)->translatedFormat('M Y');
            }
        } else {
            $startDateStr = "{$yearStart}-07-01";
            $endDateStr = "{$yearEnd}-06-30";
            // Juli - Desember (Tahun Mulai)
            for ($m = 7; $m <= 12; $m++) {
                $monthsList[] = sprintf('%04d-%02d', $yearStart, $m);
                $labels[] = Carbon::create($yearStart, $m, 1)->translatedFormat('M Y');
            }
            // Januari - Juni (Tahun Selesai)
            for ($m = 1; $m <= 6; $m++) {
                $monthsList[] = sprintf('%04d-%02d', $yearEnd, $m);
                $labels[] = Carbon::create($yearEnd, $m, 1)->translatedFormat('M Y');
            }
        }

        $pesertaMasukData = array_fill_keys($monthsList, 0);
        $pesertaKeluarData = array_fill_keys($monthsList, 0);
        $uangMasukData = array_fill_keys($monthsList, 0);
        $uangKeluarData = array_fill_keys($monthsList, 0);

        $firstMonthKey = $monthsList[0];

        // SQL start date from Jan 1 to capture PPDB payments before academic year starts (July)
        $startDateStr = "{$yearStart}-01-01";
        // End date is Dec 31 for calendar year, Jun 30 for academic year
        $endDateStr = $yearStart === $yearEnd ? "{$yearStart}-12-31" : "{$yearEnd}-06-30";

        // 1. Peserta Didik
        $pesertaMasukRaw = PesertaDidik::inContext()
            ->where('periode_id', $periodeId)
            ->whereNotNull('created_at')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, count(id) as total')
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        $pesertaKeluarRaw = PesertaDidik::inContext()
            ->where('periode_id', $periodeId)
            ->whereNotNull('tanggal_keluar')
            ->selectRaw('DATE_FORMAT(tanggal_keluar, "%Y-%m") as month, count(id) as total')
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        // 2. Keuangan - Transaksi Pembayaran Siswa
        $transaksiSppRaw = TransaksiPembayaran::whereHas('pembayaranSiswa.pesertaDidik', function($q) use ($filterKantorId, $periodeId) {
                $q->when($filterKantorId, fn($q2) => $q2->where('kantor_id', $filterKantorId))
                  ->when($periodeId, fn($q2) => $q2->where('periode_id', $periodeId));
            })
            ->where('status', 'sukses')
            ->whereBetween('tanggal', [$startDateStr, $endDateStr])
            ->selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as month, sum(nominal) as total')
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        // 3. Keuangan - Pemasukan Lainnya
        $pemasukanLainRaw = Pemasukan::when($filterKantorId, fn($q) => $q->where('kantor_id', $filterKantorId))
            ->whereBetween('tanggal', [$startDateStr, $endDateStr])
            ->selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as month, sum(nominal) as total')
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        // 4. Keuangan - Pengeluaran
        $pengeluaranRaw = Pengeluaran::when($filterKantorId, fn($q) => $q->where('kantor_id', $filterKantorId))
            ->whereBetween('tanggal', [$startDateStr, $endDateStr])
            ->selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as month, sum(nominal) as total')
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        // Rekapitulasi Data
        foreach ($pesertaMasukRaw as $key => $total) {
            if ($key < $firstMonthKey) $key = $firstMonthKey;
            if (array_key_exists($key, $pesertaMasukData)) $pesertaMasukData[$key] += $total;
        }
        foreach ($pesertaKeluarRaw as $key => $total) {
            if ($key < $firstMonthKey) $key = $firstMonthKey;
            if (array_key_exists($key, $pesertaKeluarData)) $pesertaKeluarData[$key] += $total;
        }
        foreach ($transaksiSppRaw as $key => $total) {
            if ($key < $firstMonthKey) $key = $firstMonthKey;
            if (array_key_exists($key, $uangMasukData)) $uangMasukData[$key] += $total;
        }
        foreach ($pemasukanLainRaw as $key => $total) {
            if ($key < $firstMonthKey) $key = $firstMonthKey;
            if (array_key_exists($key, $uangMasukData)) $uangMasukData[$key] += $total;
        }
        foreach ($pengeluaranRaw as $key => $total) {
            if ($key < $firstMonthKey) $key = $firstMonthKey;
            if (array_key_exists($key, $uangKeluarData)) $uangKeluarData[$key] += $total;
        }

        return [
            'labels'        => $labels,
            'pesertaMasuk'  => array_values($pesertaMasukData),
            'pesertaKeluar' => array_values($pesertaKeluarData),
            'uangMasuk'     => array_values($uangMasukData),
            'uangKeluar'    => array_values($uangKeluarData),
        ];
    }
}

