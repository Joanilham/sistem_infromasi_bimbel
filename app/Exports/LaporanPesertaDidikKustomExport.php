<?php
namespace App\Exports;

use App\Models\Akademik\PesertaDidik;
use App\Traits\ExportsExcel;

class LaporanPesertaDidikKustomExport
{
    use ExportsExcel;

    public function export($startDate, $endDate)
    {
        $rows = PesertaDidik::inContext()
            ->with(['paketBimbingan', 'pembayaran'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('paket_bimbingan_id')
            ->orderBy('created_at')
            ->get();

        $filename = 'Laporan_Peserta_Didik_' . date('d-m-Y') . '.xls';
        $headers = ['No', 'No. Induk', 'Nama Lengkap', 'Paket Bimbel', 'Total Harus Dibayar', 'Total Terbayar', 'Kekurangan'];
        $cols = count($headers);

        $xml = $this->xmlOpen('Laporan Peserta Didik', $cols, '#1E40AF', '#2563EB', '#EFF6FF');
        
        $widths = [30, 100, 150, 130, 120, 120, 120];
        $xml .= '<Worksheet ss:Name="Laporan Peserta Didik"><Table ss:DefaultRowHeight="18">';
        foreach ($widths as $w) {
            $xml .= '<Column ss:Width="' . $w . '"/>' . "
";
        }

        $titleDate = $startDate === '1970-01-01' ? 'Semua Waktu' : $startDate.' s/d '.$endDate;
        $xml .= $this->xmlTitleRow('LAPORAN PESERTA DIDIK ('.$titleDate.')', $cols);
        $xml .= $this->xmlInfoRow('Diekspor: ' . now()->format('d/m/Y H:i') . ' WIB  |  Jumlah: ' . $rows->count() . ' Siswa', $cols);
        $xml .= $this->xmlHeaderRow($headers);

        foreach ($rows as $i => $p) {
            $even = ($i % 2 === 0);
            $sd   = $even ? 's_data'  : 's_data2';
            $st   = $even ? 's_text'  : 's_text2';

            $totalHarusDibayar = $p->pembayaran ? $p->pembayaran->total_harus_dibayar : 0;
            $totalTerbayar = $p->pembayaran ? $p->pembayaran->total_terbayar : 0;
            $kekurangan = $p->pembayaran ? $p->pembayaran->kekurangan : 0;

            $xml .= '<Row ss:Height="18">';
            $xml .= $this->xmlNum($i + 1, $sd);
            $xml .= $this->xmlStr($p->nisn ?? $p->nomor_induk ?? '', $st);
            $xml .= $this->xmlStr($p->nama_lengkap, $sd);
            $xml .= $this->xmlStr(optional($p->paketBimbingan)->nama_paket ?? '-', $sd);
            $xml .= $this->xmlNum($totalHarusDibayar, $sd);
            $xml .= $this->xmlNum($totalTerbayar, $sd);
            $xml .= $this->xmlNum($kekurangan, $sd);
            $xml .= '</Row>' . "
";
        }

        $xml .= '</Table></Worksheet></Workbook>';
        return $this->xlsResponse($xml, $filename);
    }
}
