<?php

namespace App\Exports;
use App\Models\Akademik\PaketBimbingan;
use App\Models\Akademik\KelompokBelajar;

use App\Models\Akademik\PesertaDidik;
use App\Traits\ExportsExcel;

class PesertaDidikExport
{
    use ExportsExcel;

    public function exportAktif()
    {
        $rows = PesertaDidik::aktif()
            ->inContext()
            ->with('paketBimbingan', 'kelompokBelajar')
            ->orderBy('paket_bimbingan_id')
            ->orderBy('nama_lengkap')
            ->get();

        $filename = 'Peserta_Didik_Aktif_' . date('d-m-Y') . '.xls';
        $headers = ['No', 'Nama Lengkap', 'NISN', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Agama', 'Alamat', 'Asal Sekolah', 'No. Telepon', 'Paket Bimbingan', 'Kelompok', 'Nama Ayah', 'No. Telp Ayah', 'Nama Ibu', 'No. Telp Ibu'];
        $cols = count($headers);

        $xml = $this->xmlOpen('Peserta Didik Aktif', $cols, '#1E40AF', '#2563EB', '#EFF6FF');
        
        $widths = [30, 140, 80, 80, 100, 85, 70, 160, 130, 100, 110, 80, 100, 100, 100, 100];
        $xml .= '<Worksheet ss:Name="Peserta Didik Aktif"><Table ss:DefaultRowHeight="18">';
        foreach ($widths as $w) {
            $xml .= '<Column ss:Width="' . $w . '"/>' . "\n";
        }

        $xml .= $this->xmlTitleRow('DATA PESERTA DIDIK AKTIF — GENIUS EDUCATION', $cols);
        $xml .= $this->xmlInfoRow('Diekspor: ' . now()->format('d/m/Y H:i') . ' WIB  |  Jumlah: ' . $rows->count() . ' Peserta', $cols);
        $xml .= $this->xmlHeaderRow($headers);

        $xml .= $this->generateXmlRowsAktif($rows);

        $xml .= '</Table></Worksheet></Workbook>';
        return $this->xlsResponse($xml, $filename);
    }

    public function exportKeluar()
    {
        $rows = PesertaDidik::keluar()
            ->inContext()
            ->with('paketBimbingan', 'kelompokBelajar')
            ->orderBy('paket_bimbingan_id')
            ->orderBy('tanggal_keluar', 'desc')
            ->get();

        $filename = 'Peserta_Didik_Keluar_' . date('d-m-Y') . '.xls';
        $headers = ['No', 'Nama Lengkap', 'NISN', 'Jenis Kelamin', 'Asal Sekolah', 'Paket Bimbingan', 'Kelompok', 'No. Telepon', 'Tanggal Keluar', 'Nama Ayah', 'Nama Ibu', 'Keterangan Keluar'];
        $cols    = count($headers);

        $xml = $this->xmlOpen('Peserta Didik Keluar', $cols, '#991B1B', '#DC2626', '#FEF2F2');
        
        $widths = [30, 140, 80, 80, 160, 130, 80, 100, 100, 110, 110, 200];
        $xml .= '<Worksheet ss:Name="Peserta Didik Keluar"><Table ss:DefaultRowHeight="18">';
        foreach ($widths as $w) {
            $xml .= '<Column ss:Width="' . $w . '"/>' . "\n";
        }

        $xml .= $this->xmlTitleRow('DATA PESERTA DIDIK KELUAR — GENIUS EDUCATION', $cols);
        $xml .= $this->xmlInfoRow('Diekspor: ' . now()->format('d/m/Y H:i') . ' WIB  |  Jumlah: ' . $rows->count() . ' Peserta', $cols);
        $xml .= $this->xmlHeaderRow($headers);

        $xml .= $this->generateXmlRowsKeluar($rows);

        $xml .= '</Table></Worksheet></Workbook>';
        return $this->xlsResponse($xml, $filename);
    }

    public function exportLulus()
    {
        $rows = PesertaDidik::lulus()
            ->inContext()
            ->with('paketBimbingan', 'kelompokBelajar')
            ->orderBy('paket_bimbingan_id')
            ->orderBy('tanggal_keluar', 'desc')
            ->get();

        $filename = 'Peserta_Didik_Lulus_' . date('d-m-Y') . '.xls';
        $headers = ['No', 'Nama Lengkap', 'NISN', 'Jenis Kelamin', 'Asal Sekolah', 'Paket Bimbingan', 'Kelompok', 'No. Telepon', 'Tanggal Lulus', 'Nama Ayah', 'Nama Ibu', 'Keterangan Lulus'];
        $cols    = count($headers);

        $xml = $this->xmlOpen('Peserta Didik Lulus', $cols, '#065F46', '#10B981', '#ECFDF5');
        
        $widths = [30, 140, 80, 80, 160, 130, 80, 100, 100, 110, 110, 200];
        $xml .= '<Worksheet ss:Name="Peserta Didik Lulus"><Table ss:DefaultRowHeight="18">';
        foreach ($widths as $w) {
            $xml .= '<Column ss:Width="' . $w . '"/>' . "\n";
        }

        $xml .= $this->xmlTitleRow('DATA PESERTA DIDIK LULUS — GENIUS EDUCATION', $cols);
        $xml .= $this->xmlInfoRow('Diekspor: ' . now()->format('d/m/Y H:i') . ' WIB  |  Jumlah: ' . $rows->count() . ' Peserta', $cols);
        $xml .= $this->xmlHeaderRow($headers);

        $xml .= $this->generateXmlRowsKeluar($rows); // Can reuse the same row generator

        $xml .= '</Table></Worksheet></Workbook>';
        return $this->xlsResponse($xml, $filename);
    }

    private function generateXmlRowsAktif($rows)
    {
        $xml = '';
        foreach ($rows as $i => $p) {
            $even = ($i % 2 === 0);
            $sd   = $even ? 's_data'  : 's_data2';
            $st   = $even ? 's_text'  : 's_text2';

            $xml .= '<Row ss:Height="18">';
            $xml .= $this->xmlNum($i + 1, $sd);
            $xml .= $this->xmlStr($p->nama_lengkap, $sd);
            $xml .= $this->xmlStr($p->nisn, $st);
            $xml .= $this->xmlStr($p->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan', $sd);
            $xml .= $this->xmlStr($p->tempat_lahir ?? '', $sd);
            $xml .= $this->xmlStr($p->tanggal_lahir ?? '', $sd);
            $xml .= $this->xmlStr($p->agama ?? '', $sd);
            $xml .= $this->xmlStr($p->alamat_lengkap ?? '', $sd);
            $xml .= $this->xmlStr($p->asal_sekolah, $sd);
            $xml .= $this->xmlStr($p->no_telepon, $st);
            $xml .= $this->xmlStr(optional($p->paketBimbingan)->nama_paket ?? '', $sd);
            $xml .= $this->xmlStr(optional($p->kelompokBelajar)->nama_kelompok ?? '', $sd);
            $xml .= $this->xmlStr($p->nama_ayah ?? '', $sd);
            $xml .= $this->xmlStr($p->no_telepon_ayah ?? '', $st);
            $xml .= $this->xmlStr($p->nama_ibu ?? '', $sd);
            $xml .= $this->xmlStr($p->no_telepon_ibu ?? '', $st);
            $xml .= '</Row>' . "\n";
        }
        return $xml;
    }

    private function generateXmlRowsKeluar($rows)
    {
        $xml = '';
        foreach ($rows as $i => $p) {
            $even = ($i % 2 === 0);
            $sd   = $even ? 's_data'  : 's_data2';
            $st   = $even ? 's_text'  : 's_text2';

            $xml .= '<Row ss:Height="18">';
            $xml .= $this->xmlNum($i + 1, $sd);
            $xml .= $this->xmlStr($p->nama_lengkap, $sd);
            $xml .= $this->xmlStr($p->nisn, $st);
            $xml .= $this->xmlStr($p->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan', $sd);
            $xml .= $this->xmlStr($p->asal_sekolah, $sd);
            $xml .= $this->xmlStr(optional($p->paketBimbingan)->nama_paket ?? '', $sd);
            $xml .= $this->xmlStr(optional($p->kelompokBelajar)->nama_kelompok ?? '', $sd);
            $xml .= $this->xmlStr($p->no_telepon, $st);
            $xml .= $this->xmlStr($p->tanggal_keluar ?? '', $sd);
            $xml .= $this->xmlStr($p->nama_ayah ?? '', $sd);
            $xml .= $this->xmlStr($p->nama_ibu ?? '', $sd);
            $xml .= $this->xmlStr($p->alasan_keluar ?? '', $sd);
            $xml .= '</Row>' . "\n";
        }
        return $xml;
    }
}


