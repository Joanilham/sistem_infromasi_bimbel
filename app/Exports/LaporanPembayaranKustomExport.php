<?php
namespace App\Exports;

use App\Models\Keuangan\TransaksiPembayaran;
use App\Traits\ExportsExcel;

class LaporanPembayaranKustomExport
{
    use ExportsExcel;

    public function export($startDate, $endDate)
    {
        $rows = TransaksiPembayaran::whereHas('pembayaranSiswa.pesertaDidik', function($q) {
                $q->inContext();
            })
            ->with(['pembayaranSiswa.pesertaDidik', 'user'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal')
            ->get();

        $filename = 'Laporan_Pembayaran_' . date('d-m-Y') . '.xls';
        $headers = ['Tanggal', 'No. Induk', 'Nama Peserta', 'Nominal Pembayaran', 'Tipe Pembayaran', 'Penerima'];
        $cols = count($headers);

        $xml = $this->xmlOpen('Laporan Pembayaran', $cols, '#065F46', '#10B981', '#ECFDF5');
        
        $widths = [100, 100, 150, 120, 120, 150];
        $xml .= '<Worksheet ss:Name="Laporan Pembayaran"><Table ss:DefaultRowHeight="18">';
        foreach ($widths as $w) {
            $xml .= '<Column ss:Width="' . $w . '"/>' . "
";
        }

        $titleDate = $startDate === '1970-01-01' ? 'Semua Waktu' : $startDate.' s/d '.$endDate;
        $xml .= $this->xmlTitleRow('LAPORAN PEMBAYARAN ('.$titleDate.')', $cols);
        $xml .= $this->xmlInfoRow('Diekspor: ' . now()->format('d/m/Y H:i') . ' WIB  |  Total Transaksi: ' . $rows->count(), $cols);
        $xml .= $this->xmlHeaderRow($headers);

        foreach ($rows as $i => $p) {
            $even = ($i % 2 === 0);
            $sd   = $even ? 's_data'  : 's_data2';
            $st   = $even ? 's_text'  : 's_text2';
            
            $peserta = $p->pembayaranSiswa ? $p->pembayaranSiswa->pesertaDidik : null;

            $xml .= '<Row ss:Height="18">';
            $xml .= $this->xmlStr($p->tanggal ? $p->tanggal->format('d/m/Y') : '', $sd);
            $xml .= $this->xmlStr($peserta ? ($peserta->nisn ?? $peserta->nomor_induk ?? '') : '', $st);
            $xml .= $this->xmlStr($peserta ? $peserta->nama_lengkap : '', $sd);
            $xml .= $this->xmlNum($p->nominal, $sd);
            $xml .= $this->xmlStr($p->tipe_pembayaran ?? '', $sd);
            $xml .= $this->xmlStr($p->penerima ?? ($p->user ? $p->user->name : ''), $sd);
            $xml .= '</Row>' . "
";
        }

        $xml .= '</Table></Worksheet></Workbook>';
        return $this->xlsResponse($xml, $filename);
    }
}
