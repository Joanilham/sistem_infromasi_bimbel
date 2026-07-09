<?php

namespace App\Exports;
use App\Models\Keuangan\PembayaranSiswa;
use App\Models\MasterData\Periode;
use App\Models\Akademik\PaketBimbingan;
use App\Models\Akademik\KelompokBelajar;
use App\Models\MasterData\Kantor;

use Illuminate\Http\Request;
use App\Models\Akademik\PesertaDidik;
use App\Models\User;
use App\Models\Keuangan\Pemasukan;
use App\Models\Keuangan\Pengeluaran;
use App\Models\Keuangan\TransaksiPembayaran;
use App\Models\Akademik\Absensi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Traits\ExportsExcel;

class RekapitulasiExport
{
    use ExportsExcel;

    public function generate(Request $request)
    {
        $tab = $request->query('tab', 'siswa');
        $kantorId = session('kantor_id');
        $periodeId = session('periode_id');

        $dark = '#4F46E5';
        $mid = '#6366F1';
        $light = '#EEF2FF';

        $xml = $this->xmlOpen('Rekapitulasi ' . ucfirst($tab), 7, $dark, $mid, $light);
        
        $info = 'Kantor: ' . ($kantorId ? \App\Models\MasterData\Kantor::find($kantorId)->nama_kantor : 'Semua Kantor');
        $info .= ' | Periode: ' . ($periodeId ? \App\Models\MasterData\Periode::find($periodeId)->nama_periode : 'Semua Periode');

        if ($tab === 'siswa') {
            $xml .= $this->generateSiswaXml($request, $info);
        } elseif ($tab === 'guru') {
            $xml .= $this->generateGuruXml($request, $info);
        } elseif ($tab === 'keuangan') {
            $xml .= $this->generateKeuanganXml($request, $info, $kantorId, $periodeId);
        } elseif ($tab === 'absensi') {
            $xml .= $this->generateAbsensiXml($request, $info, $kantorId, $periodeId);
        }

        $xml .= '</Workbook>';
        $filename = 'Rekapitulasi_' . ucfirst($tab) . '_' . date('Ymd_His') . '.xls';

        return $this->xlsResponse($xml, $filename);
    }

    private function generateSiswaXml(Request $request, string $info): string
    {
        $selectedKelas = $request->query('kelompok_belajar_id');
        $selectedGender = $request->query('jenis_kelamin');
        
        $info .= ' | Kelas: ' . ($selectedKelas ? (\App\Models\Akademik\KelompokBelajar::find($selectedKelas)->nama_kelompok ?? '-') : 'Semua Kelas');
        $info .= ' | Gender: ' . ($selectedGender ? ($selectedGender == 'L' ? 'Laki-Laki' : 'Perempuan') : 'Semua Jenis Kelamin');

        $xml = '<Worksheet ss:Name="Rekap Siswa"><Table>';
        $xml .= '<Column ss:Width="250"/><Column ss:Width="150"/><Column ss:Width="150"/><Column ss:Width="150"/><Column ss:Width="100"/>';
        $xml .= $this->xmlTitleRow('REKAPITULASI SISWA', 5);
        $xml .= $this->xmlInfoRow($info, 5);
        
        $siswaQuery = PesertaDidik::inContext();
        if ($selectedKelas) $siswaQuery->where('kelompok_belajar_id', $selectedKelas);
        if ($selectedGender) $siswaQuery->where('jenis_kelamin', $selectedGender);
        if ($request->query('status')) $siswaQuery->where('status', $request->query('status'));
        if ($request->query('search')) {
            $siswaQuery->where(function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->query('search') . '%')
                  ->orWhere('nisn', 'like', '%' . $request->query('search') . '%');
            });
        }

        $total_aktif = (clone $siswaQuery)->aktif()->count();
        $total_keluar = (clone $siswaQuery)->keluar()->count();
        
        $xml .= $this->xmlHeaderRow(['Keterangan', 'Total Aktif', 'Total Keluar', '', '']);
        $xml .= '<Row ss:Height="20">';
        $xml .= $this->xmlStr('Keseluruhan Siswa', 's_data');
        $xml .= $this->xmlNum($total_aktif, 's_data');
        $xml .= $this->xmlNum($total_keluar, 's_data');
        $xml .= $this->xmlStr('', 's_data');
        $xml .= $this->xmlStr('', 's_data');
        $xml .= '</Row>';

        $xml .= '<Row ss:Height="15"></Row>';

        $xml .= $this->xmlHeaderRow(['Nama Siswa', 'Jenis Kelamin', 'Kelas', 'Paket', 'Status']);
        $siswaList = (clone $siswaQuery)->with(['kelompokBelajar', 'paketBimbingan'])
            ->orderBy('paket_bimbingan_id')
            ->orderBy('nama_lengkap')
            ->get();
        
        $i = 0;
        foreach ($siswaList as $siswa) {
            $style = $i % 2 === 0 ? 's_data2' : 's_data';
            $xml .= '<Row ss:Height="20">';
            $xml .= $this->xmlStr($siswa->nama_lengkap, $style);
            $xml .= $this->xmlStr($siswa->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan', $style);
            $xml .= $this->xmlStr($siswa->kelompokBelajar->nama_kelompok ?? '-', $style);
            $xml .= $this->xmlStr($siswa->paketBimbingan->nama_paket ?? '-', $style);
            $xml .= $this->xmlStr($siswa->status, $style);
            $xml .= '</Row>';
            $i++;
        }
        
        $xml .= '</Table></Worksheet>';
        return $xml;
    }

    private function generateGuruXml(Request $request, string $info): string
    {
        $selectedMapel = $request->query('matapelajaran');
        $selectedGender = $request->query('jenis_kelamin');
        
        $info .= ' | Mapel: ' . ($selectedMapel ?: 'Semua Mata Pelajaran');
        $info .= ' | Gender: ' . ($selectedGender ?: 'Semua Jenis Kelamin');

        $xml = '<Worksheet ss:Name="Rekap Guru"><Table>';
        $xml .= '<Column ss:Width="250"/><Column ss:Width="150"/><Column ss:Width="200"/><Column ss:Width="100"/>';
        $xml .= $this->xmlTitleRow('REKAPITULASI GURU', 4);
        $xml .= $this->xmlInfoRow($info, 4);
        
        $guruQuery = User::inContext()->where('level', 'guru');
        if ($selectedMapel) $guruQuery->where('matapelajaran', $selectedMapel);
        if ($selectedGender) $guruQuery->where('jenis_kelamin', $selectedGender);

        $total_aktif = (clone $guruQuery)->where('status', 'aktif')->count();
        $total_keluar = (clone $guruQuery)->where('status', 'keluar')->count();
        
        $xml .= $this->xmlHeaderRow(['Keterangan', 'Total Aktif', 'Total Keluar', '']);
        $xml .= '<Row ss:Height="20">';
        $xml .= $this->xmlStr('Keseluruhan Guru', 's_data');
        $xml .= $this->xmlNum($total_aktif, 's_data');
        $xml .= $this->xmlNum($total_keluar, 's_data');
        $xml .= $this->xmlStr('', 's_data');
        $xml .= '</Row>';

        $xml .= '<Row ss:Height="15"></Row>';

        $xml .= $this->xmlHeaderRow(['Nama Guru', 'Jenis Kelamin', 'Mata Pelajaran', 'Status']);
        
        $guruList = (clone $guruQuery)->get();
        $i = 0;
        foreach ($guruList as $guru) {
            $style = $i % 2 === 0 ? 's_data2' : 's_data';
            $xml .= '<Row ss:Height="20">';
            $xml .= $this->xmlStr($guru->name, $style);
            $xml .= $this->xmlStr($guru->jenis_kelamin ?? '-', $style);
            $xml .= $this->xmlStr($guru->matapelajaran ?: '-', $style);
            $xml .= $this->xmlStr(ucfirst($guru->status ?? 'Aktif'), $style);
            $xml .= '</Row>';
            $i++;
        }
        $xml .= '</Table></Worksheet>';
        return $xml;
    }

    private function generateKeuanganXml(Request $request, string $info, $kantorId, $periodeId): string
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $dateInfo = $startDate === '1970-01-01' ? 'Semua Waktu' : Carbon::parse($startDate)->translatedFormat('d M Y') . ' - ' . Carbon::parse($endDate)->translatedFormat('d M Y');
        $info .= ' | Tanggal: ' . $dateInfo;

        $jenisKeuangan = $request->input('jenis_keuangan', 'keuangan_semua');

        $pemasukanLainList = collect();
        $pengeluaranList = collect();
        $pemasukanSppList = collect();

        if (in_array($jenisKeuangan, ['keuangan_semua', 'keuangan_operasional'])) {
            $pemasukanLainList = Pemasukan::whereBetween('tanggal', [$startDate, $endDate])->with('kategori')->get()->map(function($item) {
                return [
                    'tanggal' => $item->tanggal,
                    'jenis' => 'Pemasukan Lainnya',
                    'kategori' => $item->kategori ? $item->kategori->nama : '-',
                    'keterangan' => $item->keterangan ?? '-',
                    'nominal' => $item->nominal,
                    'tipe' => 'pemasukan'
                ];
            });

            $pengeluaranList = Pengeluaran::whereBetween('tanggal', [$startDate, $endDate])->with('kategori')->get()->map(function($item) {
                return [
                    'tanggal' => $item->tanggal,
                    'jenis' => 'Pengeluaran',
                    'kategori' => $item->kategori ? $item->kategori->nama : '-',
                    'keterangan' => $item->keterangan ?? '-',
                    'nominal' => $item->nominal,
                    'tipe' => 'pengeluaran'
                ];
            });
        }

        if ($jenisKeuangan === 'keuangan_semua') {
            $pemasukanSppList = TransaksiPembayaran::whereBetween('tanggal', [$startDate, $endDate])
                ->with(['pembayaranSiswa.pesertaDidik'])
                ->whereHas('pembayaranSiswa.pesertaDidik', function($q) use ($kantorId, $periodeId) {
                    if ($kantorId) $q->where('kantor_id', $kantorId);
                    if ($periodeId) $q->where('periode_id', $periodeId);
                })->get()->map(function($item) {
                return [
                    'tanggal' => $item->tanggal,
                    'jenis' => 'Pembayaran SPP',
                    'kategori' => 'SPP/Bimbingan',
                    'keterangan' => 'Pembayaran SPP an. ' . ($item->pembayaranSiswa->pesertaDidik->nama_lengkap ?? ''),
                    'nominal' => $item->nominal,
                    'tipe' => 'pemasukan'
                ];
            });
        }

        $allPemasukan = collect([])->concat($pemasukanLainList)->concat($pemasukanSppList)->sortByDesc('tanggal')->values();
        $allPengeluaran = collect([])->concat($pengeluaranList)->sortByDesc('tanggal')->values();

        $xml = '';

        // Sheet Pemasukan
        $xml .= '<Worksheet ss:Name="Rekap Pemasukan"><Table>';
        $xml .= '<Column ss:Width="100"/><Column ss:Width="150"/><Column ss:Width="150"/><Column ss:Width="250"/><Column ss:Width="120"/>';
        $xml .= $this->xmlTitleRow('REKAPITULASI PEMASUKAN', 5);
        $xml .= $this->xmlInfoRow($info, 5);
        $xml .= $this->xmlHeaderRow(['Tanggal', 'Kategori', 'Jenis', 'Keterangan', 'Nominal (Rp)']);

        $i = 0;
        foreach ($allPemasukan as $trx) {
            $style = $i % 2 === 0 ? 's_data2' : 's_data';
            $xml .= '<Row ss:Height="20">';
            $xml .= $this->xmlStr(Carbon::parse($trx['tanggal'])->format('d/m/Y'), $style);
            $xml .= $this->xmlStr($trx['kategori'], $style);
            $xml .= $this->xmlStr($trx['jenis'], $style);
            $xml .= $this->xmlStr($trx['keterangan'], $style);
            $xml .= $this->xmlNum($trx['nominal'], $style);
            $xml .= '</Row>';
            $i++;
        }
        $xml .= '</Table></Worksheet>';

        // Sheet Pengeluaran
        $xml .= '<Worksheet ss:Name="Rekap Pengeluaran"><Table>';
        $xml .= '<Column ss:Width="100"/><Column ss:Width="150"/><Column ss:Width="150"/><Column ss:Width="250"/><Column ss:Width="120"/>';
        $xml .= $this->xmlTitleRow('REKAPITULASI PENGELUARAN', 5);
        $xml .= $this->xmlInfoRow($info, 5);
        $xml .= $this->xmlHeaderRow(['Tanggal', 'Kategori', 'Jenis', 'Keterangan', 'Nominal (Rp)']);

        $i = 0;
        foreach ($allPengeluaran as $trx) {
            $style = $i % 2 === 0 ? 's_data2' : 's_data';
            $xml .= '<Row ss:Height="20">';
            $xml .= $this->xmlStr(Carbon::parse($trx['tanggal'])->format('d/m/Y'), $style);
            $xml .= $this->xmlStr($trx['kategori'], $style);
            $xml .= $this->xmlStr($trx['jenis'], $style);
            $xml .= $this->xmlStr($trx['keterangan'], $style);
            $xml .= $this->xmlNum($trx['nominal'], $style);
            $xml .= '</Row>';
            $i++;
        }
        $xml .= '</Table></Worksheet>';

        return $xml;
    }

    private function generateAbsensiXml(Request $request, string $info, $kantorId, $periodeId): string
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $dateInfo = $startDate === '1970-01-01' ? 'Semua Waktu' : Carbon::parse($startDate)->translatedFormat('d M Y') . ' - ' . Carbon::parse($endDate)->translatedFormat('d M Y');
        $info .= ' | Tanggal: ' . $dateInfo;

        $xml = '<Worksheet ss:Name="Rekap Absensi"><Table>';
        $xml .= '<Column ss:Width="180"/><Column ss:Width="120"/><Column ss:Width="150"/><Column ss:Width="80"/><Column ss:Width="80"/><Column ss:Width="80"/><Column ss:Width="80"/>';
        $xml .= $this->xmlTitleRow('REKAPITULASI ABSENSI SISWA', 7);
        $xml .= $this->xmlInfoRow($info, 7);

        $absensiQuery = Absensi::whereBetween('tanggal', [$startDate, $endDate])
            ->whereHas('pesertaDidik', function($q) use ($kantorId, $periodeId) {
                if ($kantorId) $q->where('kantor_id', $kantorId);
                if ($periodeId) $q->where('periode_id', $periodeId);
            });

        $total_hadir = (clone $absensiQuery)->where('status_masuk', 'Hadir')->count();
        $total_izin = (clone $absensiQuery)->where('status_masuk', 'Izin')->count();
        $total_sakit = (clone $absensiQuery)->where('status_masuk', 'Sakit')->count();
        $total_alpha = (clone $absensiQuery)->where('status_masuk', 'Alpha')->count();

        $xml .= $this->xmlHeaderRow(['Total Hadir', 'Total Sakit', 'Total Izin', 'Total Alpha', '', '', '']);
        $xml .= '<Row ss:Height="20">';
        $xml .= $this->xmlNum($total_hadir, 's_data2');
        $xml .= $this->xmlNum($total_sakit, 's_data2');
        $xml .= $this->xmlNum($total_izin, 's_data2');
        $xml .= $this->xmlNum($total_alpha, 's_data2');
        $xml .= $this->xmlStr('', 's_data2');
        $xml .= $this->xmlStr('', 's_data2');
        $xml .= $this->xmlStr('', 's_data2');
        $xml .= '</Row>';

        $xml .= '<Row ss:Height="15"></Row>';

        $xml .= $this->xmlHeaderRow(['Nama Siswa', 'Kelas', 'Paket', 'Hadir', 'Sakit', 'Izin', 'Alpha']);
        
        $rekap_siswa = PesertaDidik::inContext()
            ->with(['kelompokBelajar', 'paketBimbingan'])
            ->whereHas('absensi', function($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal', [$startDate, $endDate]);
            })
            ->withCount([
                'absensi as hadir_count' => function($q) use ($startDate, $endDate) { $q->whereBetween('tanggal', [$startDate, $endDate])->where('status_masuk', 'Hadir'); },
                'absensi as sakit_count' => function($q) use ($startDate, $endDate) { $q->whereBetween('tanggal', [$startDate, $endDate])->where('status_masuk', 'Sakit'); },
                'absensi as izin_count' => function($q) use ($startDate, $endDate) { $q->whereBetween('tanggal', [$startDate, $endDate])->where('status_masuk', 'Izin'); },
                'absensi as alpha_count' => function($q) use ($startDate, $endDate) { $q->whereBetween('tanggal', [$startDate, $endDate])->where('status_masuk', 'Alpha'); }
            ])
            ->get();
            
        $i = 0;
        foreach ($rekap_siswa as $siswa) {
            $style = $i % 2 === 0 ? 's_data2' : 's_data';
            $xml .= '<Row ss:Height="20">';
            $xml .= $this->xmlStr($siswa->nama_lengkap, $style);
            $xml .= $this->xmlStr($siswa->kelompokBelajar->nama_kelompok ?? '-', $style);
            $xml .= $this->xmlStr($siswa->paketBimbingan->nama_paket ?? '-', $style);
            $xml .= $this->xmlNum($siswa->hadir_count, $style);
            $xml .= $this->xmlNum($siswa->sakit_count, $style);
            $xml .= $this->xmlNum($siswa->izin_count, $style);
            $xml .= $this->xmlNum($siswa->alpha_count, $style);
            $xml .= '</Row>';
            $i++;
        }

        $xml .= '</Table></Worksheet>';
        return $xml;
    }
}


