<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PesertaDidik;
use App\Models\PaketBimbingan;
use App\Models\KelompokBelajar;

class PesertaDidikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesertaDidiks   = PesertaDidik::with('paketBimbingan')->where('status', 'Aktif')->latest()->get();
        $paketBimbingans = PaketBimbingan::all();
        $kelompokBelajars = KelompokBelajar::orderBy('nama_kelompok')->get();
        return view('admin.peserta_didik.aktif', compact('pesertaDidiks', 'paketBimbingans', 'kelompokBelajars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $paketBimbingans  = PaketBimbingan::all();
        $kelompokBelajars = KelompokBelajar::orderBy('nama_kelompok')->get();
        return view('admin.peserta_didik.create', compact('paketBimbingans', 'kelompokBelajars'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap'      => 'required|string|max:255',
            'nomor_induk'       => 'required|string|max:50|unique:peserta_didiks,nomor_induk',
            'jenis_kelamin'     => 'required|in:L,P',
            'tempat_lahir'      => 'nullable|string|max:255',
            'tanggal_lahir'     => 'nullable|date',
            'agama'             => 'nullable|string|max:50',
            'alamat_lengkap'    => 'nullable|string',
            'asal_sekolah'      => 'required|string|max:255',
            'no_telepon'        => 'nullable|string|max:20',
            'nama_ayah'         => 'nullable|string|max:255',
            'nama_ibu'          => 'nullable|string|max:255',
            'pekerjaan_ayah'    => 'nullable|string|max:255',
            'pekerjaan_ibu'     => 'nullable|string|max:255',
            'no_telepon_ayah'   => 'nullable|string|max:20',
            'no_telepon_ibu'    => 'nullable|string|max:20',
            'informasi_dari'    => 'nullable|string|max:255',
            'paket_bimbingan_id' => 'required|exists:paket_bimbingans,id',
            'kelompok_belajar'  => 'required|string|max:255',
        ]);

        PesertaDidik::create($validated + ['status' => 'Aktif']);

        return redirect()->route('peserta-didik.index')->with('success', 'Data Peserta Didik berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pesertaDidik = PesertaDidik::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap'      => 'required|string|max:255',
            'nomor_induk'       => 'required|string|max:50|unique:peserta_didiks,nomor_induk,' . $id,
            'jenis_kelamin'     => 'required|in:L,P',
            'tempat_lahir'      => 'nullable|string|max:255',
            'tanggal_lahir'     => 'nullable|date',
            'agama'             => 'nullable|string|max:50',
            'alamat_lengkap'    => 'nullable|string',
            'asal_sekolah'      => 'required|string|max:255',
            'no_telepon'        => 'nullable|string|max:20',
            'nama_ayah'         => 'nullable|string|max:255',
            'nama_ibu'          => 'nullable|string|max:255',
            'pekerjaan_ayah'    => 'nullable|string|max:255',
            'pekerjaan_ibu'     => 'nullable|string|max:255',
            'no_telepon_ayah'   => 'nullable|string|max:20',
            'no_telepon_ibu'    => 'nullable|string|max:20',
            'informasi_dari'    => 'nullable|string|max:255',
            'paket_bimbingan_id' => 'required|exists:paket_bimbingans,id',
            'kelompok_belajar'  => 'required|string|max:255',
            'status'            => 'required|string|max:20',
        ]);

        $pesertaDidik->update($validated);

        return redirect()->back()->with('success', 'Data Peserta Didik berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pesertaDidik = PesertaDidik::findOrFail($id);
        $pesertaDidik->delete();

        return redirect()->back()->with('success', 'Data Peserta Didik berhasil dihapus!');
    }

    /**
     * Export active students to Excel (XML Spreadsheet 2003).
     */
    public function export()
    {
        $rows = PesertaDidik::with('paketBimbingan')
            ->where('status', 'Aktif')
            ->orderBy('nama_lengkap')
            ->get();

        $filename = 'Peserta_Didik_Aktif_' . date('d-m-Y') . '.xls';

        // Helper: escape XML special chars
        $x = fn($v) => htmlspecialchars((string) ($v ?? ''), ENT_XML1, 'UTF-8');

        // Wrap a cell value
        $str  = fn($v) => '<Cell ss:StyleID="s_text"><Data ss:Type="String">' . $x($v) . '</Data></Cell>';
        $num  = fn($v) => '<Cell ss:StyleID="s_data"><Data ss:Type="Number">' . $x($v) . '</Data></Cell>';
        $bold = fn($v) => '<Cell ss:StyleID="s_head"><Data ss:Type="String">' . $x($v) . '</Data></Cell>';

        // ── Build XML ──────────────────────────────────────────────────────────
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"'
            . ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"'
            . ' xmlns:o="urn:schemas-microsoft-com:office:office">'  . "\n";

        // ── Styles ─────────────────────────────────────────────────────────────
        $xml .= '<Styles>
  <Style ss:ID="Default">
    <Alignment ss:Vertical="Center"/>
    <Font ss:FontName="Calibri" ss:Size="11"/>
  </Style>
  <Style ss:ID="s_title">
    <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
    <Font ss:FontName="Calibri" ss:Size="14" ss:Bold="1" ss:Color="#FFFFFF"/>
    <Interior ss:Color="#1E40AF" ss:Pattern="Solid"/>
    <Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#93C5FD"/></Borders>
  </Style>
  <Style ss:ID="s_info">
    <Alignment ss:Vertical="Center"/>
    <Font ss:FontName="Calibri" ss:Size="10" ss:Italic="1" ss:Color="#374151"/>
    <Interior ss:Color="#EFF6FF" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s_head">
    <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
    <Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1" ss:Color="#FFFFFF"/>
    <Interior ss:Color="#2563EB" ss:Pattern="Solid"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1D4ED8"/>
      <Border ss:Position="Right"  ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1D4ED8"/>
    </Borders>
  </Style>
  <Style ss:ID="s_data">
    <Alignment ss:Vertical="Center"/>
    <Font ss:FontName="Calibri" ss:Size="10"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Right"  ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
    </Borders>
  </Style>
  <Style ss:ID="s_data2">
    <Alignment ss:Vertical="Center"/>
    <Font ss:FontName="Calibri" ss:Size="10"/>
    <Interior ss:Color="#EFF6FF" ss:Pattern="Solid"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Right"  ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
    </Borders>
  </Style>
  <Style ss:ID="s_text">
    <Alignment ss:Vertical="Center"/>
    <Font ss:FontName="Calibri" ss:Size="10"/>
    <NumberFormat ss:Format="@"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Right"  ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
    </Borders>
  </Style>
  <Style ss:ID="s_text2">
    <Alignment ss:Vertical="Center"/>
    <Font ss:FontName="Calibri" ss:Size="10"/>
    <NumberFormat ss:Format="@"/>
    <Interior ss:Color="#EFF6FF" ss:Pattern="Solid"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Right"  ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
    </Borders>
  </Style>
</Styles>' . "\n";

        $cols = 16;
        $xml .= '<Worksheet ss:Name="Peserta Didik Aktif">' . "\n";
        $xml .= '<Table ss:DefaultRowHeight="18">' . "\n";

        // Column widths
        $widths = [30, 140, 80, 80, 100, 85, 70, 160, 130, 100, 110, 80, 100, 100, 100, 100, 100, 100, 110, 70];
        foreach ($widths as $w) {
            $xml .= '<Column ss:Width="' . $w . '"/>' . "\n";
        }

        // Row 1: Title
        $xml .= '<Row ss:Height="28">'
            . '<Cell ss:StyleID="s_title" ss:MergeAcross="' . ($cols - 1) . '">'
            . '<Data ss:Type="String">DATA PESERTA DIDIK AKTIF — GENIUS EDUCATION</Data>'
            . '</Cell></Row>' . "\n";

        // Row 2: Info
        $dateInfo = 'Diekspor: ' . now()->format('d/m/Y H:i') . ' WIB  |  Jumlah: ' . $rows->count() . ' Peserta';
        $xml .= '<Row ss:Height="18">'
            . '<Cell ss:StyleID="s_info" ss:MergeAcross="' . ($cols - 1) . '">'
            . '<Data ss:Type="String">' . $x($dateInfo) . '</Data>'
            . '</Cell></Row>' . "\n";

        // Row 3: Headers
        $headers = ['No', 'Nama Lengkap', 'No. Induk', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Agama', 'Alamat', 'Asal Sekolah', 'No. Telepon', 'Paket Bimbingan', 'Kelompok', 'Nama Ayah', 'No. Telp Ayah', 'Nama Ibu', 'No. Telp Ibu'];
        $xml .= '<Row ss:Height="24">';
        foreach ($headers as $h) {
            $xml .= $bold($h);
        }
        $xml .= '</Row>' . "\n";

        // Data rows
        foreach ($rows as $i => $p) {
            $even = ($i % 2 === 0);
            $sd   = $even ? 's_data'  : 's_data2';
            $st   = $even ? 's_text'  : 's_text2';

            $cell = fn($v) => '<Cell ss:StyleID="' . $sd . '"><Data ss:Type="String">' . $x($v) . '</Data></Cell>';
            $telp = fn($v) => '<Cell ss:StyleID="' . $st . '"><Data ss:Type="String">' . $x($v ?? '') . '</Data></Cell>';
            $no   = fn($v) => '<Cell ss:StyleID="' . $sd . '"><Data ss:Type="Number">' . $x($v) . '</Data></Cell>';

            $xml .= '<Row ss:Height="18">';
            $xml .= $no($i + 1);
            $xml .= $cell($p->nama_lengkap);
            $xml .= $telp($p->nomor_induk);
            $xml .= $cell($p->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan');
            $xml .= $cell($p->tempat_lahir ?? '');
            $xml .= $cell($p->tanggal_lahir ?? '');
            $xml .= $cell($p->agama ?? '');
            $xml .= $cell($p->alamat_lengkap ?? '');
            $xml .= $cell($p->asal_sekolah);
            $xml .= $telp($p->no_telepon);
            $xml .= $cell(optional($p->paketBimbingan)->nama_paket ?? '');
            $xml .= $cell($p->kelompok_belajar);
            $xml .= $cell($p->nama_ayah ?? '');
            $xml .= $telp($p->no_telepon_ayah);
            $xml .= $cell($p->nama_ibu ?? '');
            $xml .= $telp($p->no_telepon_ibu);
            $xml .= '</Row>' . "\n";
        }

        $xml .= '</Table></Worksheet></Workbook>';

        return response($xml, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }
}
