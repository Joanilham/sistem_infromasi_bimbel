<?php

namespace App\Http\Controllers\Akademik;
use App\Models\MasterData\Periode;
use App\Models\System\Message;

use App\Http\Controllers\Controller;

use App\Models\Akademik\Absensi;
use App\Models\Akademik\PesertaDidik;
use App\Models\Akademik\PaketBimbingan;
use App\Models\Akademik\KelompokBelajar;
use App\Services\WhatsAppService;
use App\Http\Controllers\Siswa\QrController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\ExportsExcel;

class AbsensiController extends Controller
{
    use ExportsExcel;
    // ═══════════════════════════════════════════════════════════
    // HALAMAN UTAMA
    // ═══════════════════════════════════════════════════════════

    public function scanMasukPage()
    {
        return view('admin.absensi.scan-masuk');
    }

    public function scanPulangPage()
    {
        return view('admin.absensi.scan-pulang');
    }

    public function rekap(Request $request)
    {
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);
        $paketId = $request->input('paket_id');
        $kelompokId = $request->input('kelompok_id');
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $sort    = in_array($request->input('sort'), ['id', 'nama_lengkap']) ? $request->input('sort') : 'nama_lengkap';
        $order   = in_array($request->input('order'), ['asc', 'desc']) ? $request->input('order') : 'asc';

        $pesertaDidiks = PesertaDidik::aktif()
            ->inContext()
            ->when($paketId, fn($q) => $q->where('paket_bimbingan_id', $paketId))
            ->when($kelompokId, fn($q) => $q->where('kelompok_belajar_id', $kelompokId))
            ->when($search, function($q) use ($search) {
                $q->where(function($query) use ($search) {
                    $query->where('nama_lengkap', 'like', "%{$search}%")
                          ->orWhere('nisn', 'like', "%{$search}%");
                });
            })
            ->with('paketBimbingan')
            ->orderBy($sort, $order)
            ->paginate($perPage);

        $absensisMap = $this->buildAbsensiMap($pesertaDidiks->pluck('id'), $bulan, $tahun);
        $this->buildAbsensiStats($pesertaDidiks, $absensisMap, $bulan, $tahun);

        $pakets = PaketBimbingan::get();
        $kelompoks = KelompokBelajar::query()->get();

        return view('admin.absensi.rekap', compact('pesertaDidiks', 'bulan', 'tahun', 'pakets', 'kelompoks'));
    }

    public function detail(Request $request, $id)
    {
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        $peserta = PesertaDidik::inContext()->findOrFail($id);

        $absensis = Absensi::where('peserta_didik_id', $peserta->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get()
            ->keyBy(fn($a) => \Carbon\Carbon::parse($a->tanggal)->format('Y-m-d'));

        // Buat daftar tanggal dalam bulan ini
        $daysInMonth = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;
        $tanggalListRaw = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dateStr = \Carbon\Carbon::createFromDate($tahun, $bulan, $i)->format('Y-m-d');
            $tanggalListRaw[$dateStr] = $absensis->get($dateStr);
        }

        $perPage = (int) $request->input('per_page', 10);
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $currentPageItems = array_slice($tanggalListRaw, ($currentPage - 1) * $perPage, $perPage, true);
        
        $tanggalList = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            count($tanggalListRaw),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );



        return view('admin.absensi.detail', compact('peserta', 'bulan', 'tahun', 'tanggalList'));
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didiks,id',
            'tanggal'          => 'required|date',
            'status_masuk'     => 'required|in:hadir,izin,sakit,alpha',
            'jam_masuk'        => 'nullable|date_format:H:i',
            'jam_pulang'       => 'nullable|date_format:H:i',
            'keterangan'       => 'nullable|string'
        ]);

        $peserta = PesertaDidik::inContext()->findOrFail($request->peserta_didik_id);

        $absensi = Absensi::updateOrCreate(
            ['peserta_didik_id' => $peserta->id, 'tanggal' => $request->tanggal],
            [
                'status_masuk' => $request->status_masuk,
                'jam_masuk'    => $request->jam_masuk,
                'jam_pulang'   => $request->jam_pulang,
                'keterangan'   => $request->keterangan ?? 'Diperbarui secara manual oleh Admin'
            ]
        );

        return back()->with('success', 'Data absensi tanggal ' . \Carbon\Carbon::parse($request->tanggal)->format('d M Y') . ' berhasil diperbarui.');
    }

    // ═══════════════════════════════════════════════════════════
    // API SCAN (JSON)
    // ═══════════════════════════════════════════════════════════

    public function scanMasuk(Request $request)
    {
        $request->validate(['nisn' => 'required|string']);

        [$peserta, $error] = $this->resolveQrPeserta($request->nisn);
        if ($error) return $error;

        $tanggal = now()->toDateString();
        $absensi = Absensi::where('peserta_didik_id', $peserta->id)->where('tanggal', $tanggal)->first();

        if ($absensi && $absensi->jam_masuk) {
            return response()->json([
                'success' => true, 'sudah' => true,
                'message' => $peserta->nama_lengkap . ' sudah absen masuk pukul ' . substr($absensi->jam_masuk, 0, 5) . '.',
                'nama' => $peserta->nama_lengkap, 'nisn' => $peserta->nisn,
                'jam'  => substr($absensi->jam_masuk, 0, 5), 'tipe' => 'masuk',
            ]);
        }

        $absensi = Absensi::firstOrCreate(
            ['peserta_didik_id' => $peserta->id, 'tanggal' => $tanggal],
            ['status_masuk' => 'hadir', 'jam_masuk' => now()->format('H:i:s')]
        );
        if (!$absensi->jam_masuk) {
            $absensi->update(['jam_masuk' => now()->format('H:i:s'), 'status_masuk' => 'hadir']);
        }

        if (!$absensi->wa_masuk_sent) {
            $this->kirimWa($peserta, $absensi, 'masuk');
            $absensi->update(['wa_masuk_sent' => true]);
        }

        return response()->json([
            'success' => true, 'sudah' => false,
            'message' => '✅ ' . $peserta->nama_lengkap . ' berhasil absen masuk.',
            'nama' => $peserta->nama_lengkap, 'nisn' => $peserta->nisn,
            'jam'  => substr($absensi->jam_masuk, 0, 5), 'tipe' => 'masuk',
        ]);
    }

    public function scanPulang(Request $request)
    {
        $request->validate(['nisn' => 'required|string']);

        [$peserta, $error] = $this->resolveQrPeserta($request->nisn);
        if ($error) return $error;

        $tanggal = now()->toDateString();
        $absensi = Absensi::where('peserta_didik_id', $peserta->id)->where('tanggal', $tanggal)->first();

        if (!$absensi || !$absensi->jam_masuk) {
            return response()->json(['success' => false, 'message' => $peserta->nama_lengkap . ' belum absen masuk hari ini.']);
        }

        if ($absensi->jam_pulang) {
            return response()->json([
                'success' => true, 'sudah' => true,
                'message' => $peserta->nama_lengkap . ' sudah absen pulang pukul ' . substr($absensi->jam_pulang, 0, 5),
                'nama' => $peserta->nama_lengkap, 'nisn' => $peserta->nisn,
                'jam'  => substr($absensi->jam_pulang, 0, 5), 'tipe' => 'pulang',
            ]);
        }

        $absensi->update(['jam_pulang' => now()->format('H:i:s')]);

        if (!$absensi->wa_pulang_sent) {
            $this->kirimWa($absensi->fresh()->load('pesertaDidik')->pesertaDidik ?? $peserta, $absensi->fresh(), 'pulang');
            $absensi->update(['wa_pulang_sent' => true]);
        }

        return response()->json([
            'success' => true, 'sudah' => false,
            'message' => '✅ ' . $peserta->nama_lengkap . ' berhasil absen pulang.',
            'nama' => $peserta->nama_lengkap, 'nisn' => $peserta->nisn,
            'jam'  => substr($absensi->jam_pulang, 0, 5), 'tipe' => 'pulang',
        ]);
    }

    // ═══════════════════════════════════════════════════════════
    // EXPORT REKAP (Excel XML)
    // ═══════════════════════════════════════════════════════════

    public function exportRekap(Request $request)
    {
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);
        $rows  = PesertaDidik::aktif()->inContext()->with('paketBimbingan')->get();

        $absensisMap = $this->buildAbsensiMap($rows->pluck('id'), $bulan, $tahun);
        $this->buildAbsensiStats($rows, $absensisMap, $bulan, $tahun);

        $namaBulan = Carbon::create()->month($bulan)->translatedFormat('F');
        $title     = 'REKAP ABSENSI — ' . strtoupper($namaBulan) . ' ' . $tahun;
        $headers   = ['No', 'Nama Siswa', 'NISN', 'Paket Bimbel', 'Hadir', 'Izin', 'Sakit', 'Alpha', 'Total'];

        $xml = $this->xmlOpen($title, count($headers), '#1E40AF', '#2563EB', '#EFF6FF');
        $xml .= '<Worksheet ss:Name="Rekap"><Table ss:DefaultRowHeight="18">' . "\n";
        foreach ([30, 160, 90, 130, 60, 60, 60, 60, 60] as $w) {
            $xml .= '<Column ss:Width="' . $w . '"/>' . "\n";
        }
        $xml .= $this->xmlTitleRow($title, count($headers));
        $xml .= $this->xmlInfoRow('Periode: ' . $namaBulan . ' ' . $tahun . '  |  Diekspor: ' . now()->format('d/m/Y H:i'), count($headers));
        $xml .= $this->xmlHeaderRow($headers);

        foreach ($rows as $i => $p) {
            $total = $p->total_hadir + $p->total_izin + $p->total_sakit + $p->total_alpha;
            $sd    = $i % 2 === 0 ? 's_data' : 's_data2';
            $st    = $i % 2 === 0 ? 's_text' : 's_text2';
            $xml .= '<Row ss:Height="18">'
                . $this->xmlNum($i + 1, $sd)
                . $this->xmlStr($p->nama_lengkap, $sd)
                . $this->xmlStr($p->nisn, $st)
                . $this->xmlStr(optional($p->paketBimbingan)->nama_paket ?? '-', $sd)
                . $this->xmlNum($p->total_hadir, $sd)
                . $this->xmlNum($p->total_izin, $sd)
                . $this->xmlNum($p->total_sakit, $sd)
                . $this->xmlNum($p->total_alpha, $sd)
                . $this->xmlNum($total, $sd)
                . '</Row>' . "\n";
        }

        $xml .= '</Table></Worksheet></Workbook>';
        return $this->xlsResponse($xml, 'Rekap_Absensi_' . $namaBulan . '_' . $tahun . '.xls');
    }

    // ═══════════════════════════════════════════════════════
    // PRIVATE HELPERS
    // ═══════════════════════════════════════════════════════

    /**
     * Validasi token QR, cari peserta, dan cek status pembayaran.
     * Mengembalikan [$peserta, null] jika valid, atau [null, JsonResponse] jika gagal.
     */
    private function resolveQrPeserta(string $token): array
    {
        $identifier = QrController::validateToken($token);
        if (!$identifier) {
            return [null, response()->json([
                'success' => false,
                'message' => '⏱ QR tidak valid atau sudah kedaluwarsa. Minta siswa buka ulang halaman QR dan scan segera.',
            ])];
        }

        if (str_starts_with($identifier, 'ID:')) {
            $id = substr($identifier, 3);
            $peserta = PesertaDidik::aktif()->inContext()->find($id);
        } else {
            $peserta = PesertaDidik::aktif()->inContext()->where('nisn', $identifier)->first();
        }

        if (!$peserta) {
            return [null, response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan di cabang ini.'])];
        }

        $statusPembayaran = $peserta->getStatusPembayaran();
        // if ($statusPembayaran['is_locked']) {
        //     return [null, response()->json([
        //         'success' => false,
        //         'message' => '⚠️ Gagal Absen! Siswa ini memiliki tagihan jatuh tempo / durasi bimbingan hampir habis dan belum dilunasi. Hubungi bagian keuangan.',
        //     ])];
        // }

        return [$peserta, null];
    }

    /**
     * Fetch & group absensi berdasarkan bulan/tahun, return Collection grouped by peserta_didik_id.
     */
    private function buildAbsensiMap($pesertaIds, int $bulan, int $tahun)
    {
        return Absensi::whereIn('peserta_didik_id', $pesertaIds)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get()
            ->groupBy('peserta_didik_id');
    }

    /**
     * Inject total_hadir / total_izin / total_sakit / total_alpha ke setiap item koleksi peserta.
     */
    private function buildAbsensiStats($pesertaList, $absensisMap, $bulan, $tahun): void
    {
        $targetDays = 0;
        $daysInMonth = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = \Carbon\Carbon::createFromDate($tahun, $bulan, $i);
            if ($date->isFuture()) break;
            // Hanya hitung Senin - Jumat (asumsi hari kerja efektif)
            if (!$date->isWeekend()) {
                $targetDays++;
            }
        }
        if ($targetDays === 0) $targetDays = 1; // Cegah division by zero

        $pesertaList->each(function ($p) use ($absensisMap, $targetDays) {
            $records = $absensisMap->get($p->id, collect());
            $p->total_hadir = $records->where('status_masuk', 'hadir')->count();
            $p->total_izin  = $records->where('status_masuk', 'izin')->count();
            $p->total_sakit = $records->where('status_masuk', 'sakit')->count();
            $p->total_alpha = $records->where('status_masuk', 'alpha')->count();
            $p->target_days = $targetDays;
        });
    }

    private function kirimWa(PesertaDidik $peserta, Absensi $absensi, string $tipe): void
    {
        $nomor = $peserta->no_telepon_ayah ?? $peserta->no_telepon_ibu ?? $peserta->no_telepon;
        if (!$nomor) return;

        $tanggalFormatted = Carbon::parse($absensi->tanggal)->translatedFormat('d F Y');

        if ($tipe === 'masuk') {
            $pesan = "📚 *Notifikasi Kehadiran*\n\nAssalamu'alaikum Bapak/Ibu,\n\nPutra/putri Anda:\n*{$peserta->nama_lengkap}*\n\n✅ Sudah *MASUK* bimbel pada:\n📅 {$tanggalFormatted}\n🕐 " . substr($absensi->jam_masuk, 0, 5) . "\n\nTerima kasih. 🙏";
        } else {
            $pesan = "📚 *Notifikasi Kehadiran*\n\nAssalamu'alaikum Bapak/Ibu,\n\nPutra/putri Anda:\n*{$peserta->nama_lengkap}*\n\n🏠 Sudah *PULANG* dari bimbel pada:\n📅 {$tanggalFormatted}\n🕐 " . substr($absensi->jam_pulang, 0, 5) . "\n\nTerima kasih. 🙏";
        }

        WhatsAppService::sendAsync($nomor, $pesan);
    }


}




