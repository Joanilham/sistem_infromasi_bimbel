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
        $siswaList = (clone $siswaQuery)->with(['kelompokBelajar', 'paketBimbingan'])->get();
        
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
        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->query('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $info .= ' | Tanggal: ' . Carbon::parse($startDate)->translatedFormat('d M Y') . ' - ' . Carbon::parse($endDate)->translatedFormat('d M Y');

        $xml = '<Worksheet ss:Name="Rekap Keuangan"><Table>';
        $xml .= '<Column ss:Width="250"/><Column ss:Width="150"/>';
        $xml .= $this->xmlTitleRow('REKAPITULASI KEUANGAN', 2);
        $xml .= $this->xmlInfoRow($info, 2);

        $pemasukanLain = Pemasukan::whereBetween('tanggal', [$startDate, $endDate])->sum('nominal');
        $pemasukanSpp = TransaksiPembayaran::whereBetween('tanggal', [$startDate, $endDate])
            ->whereHas('pembayaranSiswa.pesertaDidik', function($q) use ($kantorId, $periodeId) {
                if ($kantorId) $q->where('kantor_id', $kantorId);
                if ($periodeId) $q->where('periode_id', $periodeId);
            })->sum('nominal');
        $total_pemasukan = $pemasukanLain + $pemasukanSpp;
        $total_pengeluaran = Pengeluaran::whereBetween('tanggal', [$startDate, $endDate])->sum('nominal');

        $xml .= $this->xmlHeaderRow(['Keterangan', 'Total (Rp)']);
        $xml .= '<Row ss:Height="20">';
        $xml .= $this->xmlStr('Total Pemasukan', 's_data2');
        $xml .= $this->xmlNum($total_pemasukan, 's_data2');
        $xml .= '</Row>';
        $xml .= '<Row ss:Height="20">';
        $xml .= $this->xmlStr('Total Pengeluaran', 's_data');
        $xml .= $this->xmlNum($total_pengeluaran, 's_data');
        $xml .= '</Row>';

        $xml .= '<Row ss:Height="15"></Row>';

        $xml .= $this->xmlHeaderRow(['Detail Pemasukan', 'Total (Rp)']);
        $xml .= '<Row ss:Height="20">';
        $xml .= $this->xmlStr('Pembayaran SPP/Bimbingan', 's_data2');
        $xml .= $this->xmlNum($pemasukanSpp, 's_data2');
        $xml .= '</Row>';
        
        $rekap_pemasukan_lain = Pemasukan::whereBetween('tanggal', [$startDate, $endDate])->select('kategori_id', DB::raw('SUM(nominal) as total'))->with('kategori')->groupBy('kategori_id')->get();
        $i = 1;
        foreach ($rekap_pemasukan_lain as $rpl) {
            $style = $i % 2 === 0 ? 's_data2' : 's_data';
            $xml .= '<Row ss:Height="20">';
            $xml .= $this->xmlStr($rpl->kategori->nama_kategori ?? 'Lainnya', $style);
            $xml .= $this->xmlNum($rpl->total, $style);
            $xml .= '</Row>';
            $i++;
        }

        $xml .= '<Row ss:Height="15"></Row>';

        $xml .= $this->xmlHeaderRow(['Detail Pengeluaran', 'Total (Rp)']);
        $rekap_pengeluaran = Pengeluaran::whereBetween('tanggal', [$startDate, $endDate])->select('kategori_id', DB::raw('SUM(nominal) as total'))->with('kategori')->groupBy('kategori_id')->get();
        $i = 0;
        foreach ($rekap_pengeluaran as $rp) {
            $style = $i % 2 === 0 ? 's_data2' : 's_data';
            $xml .= '<Row ss:Height="20">';
            $xml .= $this->xmlStr($rp->kategori->nama_kategori ?? 'Lainnya', $style);
            $xml .= $this->xmlNum($rp->total, $style);
            $xml .= '</Row>';
            $i++;
        }

        $xml .= '</Table></Worksheet>';
        return $xml;
    }

    private function generateAbsensiXml(Request $request, string $info, $kantorId, $periodeId): string
    {
        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->query('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $info .= ' | Tanggal: ' . Carbon::parse($startDate)->translatedFormat('d M Y') . ' - ' . Carbon::parse($endDate)->translatedFormat('d M Y');

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


