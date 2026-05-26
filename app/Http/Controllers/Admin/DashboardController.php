<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\Kantor;
use App\Models\Periode;
use App\Models\PesertaDidik;
use App\Models\PaketBimbingan;
use App\Models\User;
use App\Models\PendaftaranSiswa;
use App\Models\PembayaranPendaftaran;
use App\Models\PembayaranSiswa;
use App\Models\TransaksiPembayaran;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\AuditLog;

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
        $totalTenagaPengajar = User::where('level', 'guru')->inContext()->count();

        // 5 Aktivitas Terbaru untuk Activity Log Widget di Dashboard
        $recentAuditLogs = AuditLog::with('user:id,name,level')
            ->latest()
            ->limit(5)
            ->get();

        $selectedKantor  = session('kantor_id')  ? Kantor::find(session('kantor_id'))  : null;
        $selectedPeriode = session('periode_id') ? Periode::find(session('periode_id')) : null;

        $kantors  = Cache::remember('dash_kantors', 60, fn() => Kantor::all());
        $periodes = Cache::remember('dash_periodes', 60, fn() => Periode::all());

        $isSuperAdmin = auth()->check() && strtolower(auth()->user()->level) === 'super admin';
        $filterKantorId = $kantorId === 'all' ? null : $kantorId;

        $pendaftaranMenunggu = PendaftaranSiswa::where('status', 'menunggu')
            ->when($filterKantorId, fn($q) => $q->where('kantor_id', $filterKantorId))
            ->when($periodeId, fn($q) => $q->where('periode_id', $periodeId))
            ->count();
        $pembayaranBelumDikonfirmasi = PembayaranPendaftaran::where('status', 'menunggu')
            ->whereHas('pendaftaranSiswa', fn($q) => $q
                ->when($filterKantorId, fn($q2) => $q2->where('kantor_id', $filterKantorId))
                ->when($periodeId, fn($q2) => $q2->where('periode_id', $periodeId))
            )
            ->count();

        // Tagihan Jatuh Tempo Count
        $tagihanJatuhTempoCount = 0;
        if ($kantorId && $periodeId) {
            $tagihanRaw = PembayaranSiswa::with('transaksi')
                ->whereHas('pesertaDidik', fn($q) => $q->inContext()->aktif())
                ->where(function($q) {
                    $q->where('batas_waktu', '<=', Carbon::now()->addDays(7))
                      ->orWhereNull('batas_waktu');
                })
                ->get();
            $tagihanJatuhTempoCount = $tagihanRaw->filter(fn($p) => $p->kekurangan > 0)->count();
        }

        // Lead Tracking: 10 Pendaftar Terbaru
        $recentPendaftaran = PendaftaranSiswa::with(['paketBimbingan', 'pembayaran'])
            ->when($filterKantorId, fn($q) => $q->where('kantor_id', $filterKantorId))
            ->when($periodeId, fn($q) => $q->where('periode_id', $periodeId))
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
                $labels[] = Carbon::create($yearStart, $m, 1)->translatedFormat('F Y');
            }
        } else {
            for ($m = 7; $m <= 12; $m++) {
                $monthsList[] = sprintf('%04d-%02d', $yearStart, $m);
                $labels[] = Carbon::create($yearStart, $m, 1)->translatedFormat('F Y');
            }
            for ($m = 1; $m <= 6; $m++) {
                $monthsList[] = sprintf('%04d-%02d', $yearEnd, $m);
                $labels[] = Carbon::create($yearEnd, $m, 1)->translatedFormat('F Y');
            }
        }

        $pesertaMasukData = array_fill_keys($monthsList, 0);
        $pesertaKeluarData = array_fill_keys($monthsList, 0);
        $uangMasukData = array_fill_keys($monthsList, 0);
        $uangKeluarData = array_fill_keys($monthsList, 0);

        // 1. Peserta Didik
        $pesertas = PesertaDidik::inContext()
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
                $key = Carbon::parse($p->tanggal_keluar)->format('Y-m');
                if (array_key_exists($key, $pesertaKeluarData)) {
                    $pesertaKeluarData[$key]++;
                }
            }
        }

        // 2. Keuangan - Transaksi Pembayaran Siswa (Uang Masuk)
        $transaksiSpp = TransaksiPembayaran::whereHas('pembayaranSiswa.pesertaDidik', function($q) use ($filterKantorId, $periodeId) {
                $q->when($filterKantorId, fn($q2) => $q2->where('kantor_id', $filterKantorId))
                  ->when($periodeId, fn($q2) => $q2->where('periode_id', $periodeId));
            })
            ->whereBetween('tanggal', [$yearStart === $yearEnd ? "{$yearStart}-01-01" : "{$yearStart}-07-01", $yearStart === $yearEnd ? "{$yearStart}-12-31" : "{$yearEnd}-06-30"])
            ->get();

        foreach ($transaksiSpp as $t) {
            if ($t->tanggal) {
                $key = Carbon::parse($t->tanggal)->format('Y-m');
                if (array_key_exists($key, $uangMasukData)) {
                    $uangMasukData[$key] += (int) $t->nominal;
                }
            }
        }

        // 3. Keuangan - Pemasukan Lainnya (Uang Masuk)
        $pemasukanLain = Pemasukan::when($filterKantorId, fn($q) => $q->whereHas('user', fn($qu) => $qu->where('kantor_id', $filterKantorId)))
            ->whereBetween('tanggal', [$yearStart === $yearEnd ? "{$yearStart}-01-01" : "{$yearStart}-07-01", $yearStart === $yearEnd ? "{$yearStart}-12-31" : "{$yearEnd}-06-30"])
            ->get();

        foreach ($pemasukanLain as $pl) {
            if ($pl->tanggal) {
                $key = Carbon::parse($pl->tanggal)->format('Y-m');
                if (array_key_exists($key, $uangMasukData)) {
                    $uangMasukData[$key] += (int) $pl->nominal;
                }
            }
        }

        // 4. Keuangan - Pengeluaran (Uang Keluar)
        $pengeluaranList = Pengeluaran::when($filterKantorId, fn($q) => $q->whereHas('user', fn($qu) => $qu->where('kantor_id', $filterKantorId)))
            ->whereBetween('tanggal', [$yearStart === $yearEnd ? "{$yearStart}-01-01" : "{$yearStart}-07-01", $yearStart === $yearEnd ? "{$yearStart}-12-31" : "{$yearEnd}-06-30"])
            ->get();

        foreach ($pengeluaranList as $pg) {
            if ($pg->tanggal) {
                $key = Carbon::parse($pg->tanggal)->format('Y-m');
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
            'recentAuditLogs',
            'chartLabels',
            'chartPesertaMasuk',
            'chartPesertaKeluar',
            'chartUangMasuk',
            'chartUangKeluar'
        ));
    }
}
