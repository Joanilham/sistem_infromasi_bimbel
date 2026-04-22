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
    $kantorId = session('kantor_id');
    $periodeId = session('periode_id');

    $pesertaDidiks = \Illuminate\Support\Facades\Cache::rememberForever("data_peserta_didiks_aktif_{$kantorId}_{$periodeId}", function () {
      return PesertaDidik::aktif()->inContext()->with('paketBimbingan', 'kelompokBelajar')->latest()->get();
    });
    $paketBimbingans = \Illuminate\Support\Facades\Cache::rememberForever("data_paket_bimbingans_{$kantorId}_{$periodeId}", function () {
      return PaketBimbingan::inContext()->get();
    });
    $kelompokBelajars = \Illuminate\Support\Facades\Cache::rememberForever("data_kelompok_belajars_{$kantorId}_{$periodeId}", function () {
      return KelompokBelajar::inContext()->orderBy('nama_kelompok')->get();
    });
    return view('admin.peserta_didik.aktif', compact('pesertaDidiks', 'paketBimbingans', 'kelompokBelajars'));
  }

  /**
   * Daftar peserta didik yang sudah keluar.
   */
  public function keluar()
  {
    $kantorId = session('kantor_id');
    $periodeId = session('periode_id');

    $pesertaDidiks = \Illuminate\Support\Facades\Cache::rememberForever("data_peserta_didiks_keluar_{$kantorId}_{$periodeId}", function() {
      return PesertaDidik::keluar()
        ->inContext()
        ->with('paketBimbingan')
        ->orderBy('tanggal_keluar', 'desc')
        ->get();
    });
    return view('admin.peserta_didik.keluar', compact('pesertaDidiks'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $paketBimbingans  = PaketBimbingan::inContext()->get();
    $kelompokBelajars = KelompokBelajar::inContext()->orderBy('nama_kelompok')->get();
    return view('admin.peserta_didik.create', compact('paketBimbingans', 'kelompokBelajars'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'nama_lengkap'      => 'required|string|max:255',
      'nisn'              => 'required|digits:10|unique:peserta_didiks,nisn',
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
      'kelompok_belajar_id' => 'nullable|exists:kelompok_belajars,id',
    ]);

    PesertaDidik::create($validated + [
        'status' => 'Aktif',
        'kantor_id' => session('kantor_id'),
        'periode_id' => session('periode_id')
    ]);

    $this->clearPesertaCache();

    return redirect()->route('peserta-didik.index')->with('success', 'Data Peserta Didik berhasil ditambahkan!');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $pesertaDidik     = PesertaDidik::findOrFail($id);
    $paketBimbingans  = PaketBimbingan::inContext()->get();
    $kelompokBelajars = KelompokBelajar::inContext()->orderBy('nama_kelompok')->get();
    return view('admin.peserta_didik.edit', compact('pesertaDidik', 'paketBimbingans', 'kelompokBelajars'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $pesertaDidik = PesertaDidik::findOrFail($id);

    $validated = $request->validate([
      'nama_lengkap'      => 'required|string|max:255',
      'nisn'              => 'required|digits:10|unique:peserta_didiks,nisn,' . $id,
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
      'kelompok_belajar_id' => 'nullable|exists:kelompok_belajars,id',
      'status'            => 'required|in:Aktif,Keluar',
      'tanggal_keluar'    => 'required_if:status,Keluar|nullable|date',
      'alasan_keluar'     => 'required_if:status,Keluar|nullable|string',
    ]);

    // Hapus field keluar jika status bukan Keluar
    if ($validated['status'] !== 'Keluar') {
      $validated['tanggal_keluar'] = null;
      $validated['alasan_keluar']  = null;
    }

    $pesertaDidik->update($validated);

    $this->clearPesertaCache();

    return redirect()->route('peserta-didik.index')->with('success', 'Data Peserta Didik berhasil diperbarui!');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $pesertaDidik = PesertaDidik::findOrFail($id);
    $pesertaDidik->delete();

    $this->clearPesertaCache();

    return redirect()->back()->with('success', 'Data Peserta Didik berhasil dihapus!');
  }

  /**
   * Hapus semua cache terkait data peserta didik untuk context aktif.
   */
  private function clearPesertaCache(): void
  {
    $kantorId  = session('kantor_id');
    $periodeId = session('periode_id');

    \Illuminate\Support\Facades\Cache::forget("data_peserta_didiks_aktif_{$kantorId}_{$periodeId}");
    \Illuminate\Support\Facades\Cache::forget("data_peserta_didiks_keluar_{$kantorId}_{$periodeId}");
    \Illuminate\Support\Facades\Cache::forget("dash_total_peserta_{$kantorId}_{$periodeId}");
    \Illuminate\Support\Facades\Cache::forget("dash_peserta_baru_{$kantorId}_{$periodeId}");
    \Illuminate\Support\Facades\Cache::forget("dash_peserta_keluar_{$kantorId}_{$periodeId}");
  }

  /**
   * Export keluar students to Excel (XML Spreadsheet 2003).
   */
  public function exportKeluar()
  {
    $rows = PesertaDidik::keluar()
      ->with('paketBimbingan', 'kelompokBelajar')
      ->orderBy('tanggal_keluar', 'desc')
      ->get();

    $filename = 'Peserta_Didik_Keluar_' . date('d-m-Y') . '.xls';

    $x    = fn($v) => htmlspecialchars((string) ($v ?? ''), ENT_XML1, 'UTF-8');
    $bold = fn($v) => '<Cell ss:StyleID="s_head"><Data ss:Type="String">' . $x($v) . '</Data></Cell>';

    $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
    $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"'
      . ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"'
      . ' xmlns:o="urn:schemas-microsoft-com:office:office">'  . "\n";

    $xml .= '<Styles>
  <Style ss:ID="Default">
    <Alignment ss:Vertical="Center"/>
    <Font ss:FontName="Calibri" ss:Size="11"/>
  </Style>
  <Style ss:ID="s_title">
    <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
    <Font ss:FontName="Calibri" ss:Size="14" ss:Bold="1" ss:Color="#FFFFFF"/>
    <Interior ss:Color="#991B1B" ss:Pattern="Solid"/>
    <Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#FCA5A5"/></Borders>
  </Style>
  <Style ss:ID="s_info">
    <Alignment ss:Vertical="Center"/>
    <Font ss:FontName="Calibri" ss:Size="10" ss:Italic="1" ss:Color="#374151"/>
    <Interior ss:Color="#FEF2F2" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s_head">
    <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
    <Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1" ss:Color="#FFFFFF"/>
    <Interior ss:Color="#DC2626" ss:Pattern="Solid"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#B91C1C"/>
      <Border ss:Position="Right"  ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#B91C1C"/>
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
    <Interior ss:Color="#FEF2F2" ss:Pattern="Solid"/>
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
    <Interior ss:Color="#FEF2F2" ss:Pattern="Solid"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Right"  ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
    </Borders>
  </Style>
</Styles>' . "\n";

    $headers = ['No', 'Nama Lengkap', 'NISN', 'Jenis Kelamin', 'Asal Sekolah', 'Paket Bimbingan', 'Kelompok', 'No. Telepon', 'Tanggal Keluar', 'Nama Ayah', 'Nama Ibu', 'Keterangan Keluar'];
    $cols    = count($headers);

    $xml .= '<Worksheet ss:Name="Peserta Didik Keluar">' . "\n";
    $xml .= '<Table ss:DefaultRowHeight="18">' . "\n";

    $widths = [30, 140, 80, 80, 160, 130, 80, 100, 100, 110, 110, 200];
    foreach ($widths as $w) {
      $xml .= '<Column ss:Width="' . $w . '"/>' . "\n";
    }

    // Title row
    $xml .= '<Row ss:Height="28">'
      . '<Cell ss:StyleID="s_title" ss:MergeAcross="' . ($cols - 1) . '">'
      . '<Data ss:Type="String">DATA PESERTA DIDIK KELUAR — GENIUS EDUCATION</Data>'
      . '</Cell></Row>' . "\n";

    // Info row
    $dateInfo = 'Diekspor: ' . now()->format('d/m/Y H:i') . ' WIB  |  Jumlah: ' . $rows->count() . ' Peserta';
    $xml .= '<Row ss:Height="18">'
      . '<Cell ss:StyleID="s_info" ss:MergeAcross="' . ($cols - 1) . '">'
      . '<Data ss:Type="String">' . $x($dateInfo) . '</Data>'
      . '</Cell></Row>' . "\n";

    // Header row
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
      $xml .= $telp($p->nisn);
      $xml .= $cell($p->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan');
      $xml .= $cell($p->asal_sekolah);
      $xml .= $cell(optional($p->paketBimbingan)->nama_paket ?? '');
      $xml .= $cell(optional($p->kelompokBelajar)->nama_kelompok ?? '');
      $xml .= $telp($p->no_telepon);
      $xml .= $cell($p->tanggal_keluar ?? '');
      $xml .= $cell($p->nama_ayah ?? '');
      $xml .= $cell($p->nama_ibu ?? '');
      $xml .= $cell($p->alasan_keluar ?? '');
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

  /**
   * Export active students to Excel (XML Spreadsheet 2003).
   */
  public function export()
  {
    $rows = PesertaDidik::aktif()
      ->with('paketBimbingan', 'kelompokBelajar')
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
    $headers = ['No', 'Nama Lengkap', 'NISN', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Agama', 'Alamat', 'Asal Sekolah', 'No. Telepon', 'Paket Bimbingan', 'Kelompok', 'Nama Ayah', 'No. Telp Ayah', 'Nama Ibu', 'No. Telp Ibu'];
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
      $xml .= $telp($p->nisn);
      $xml .= $cell($p->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan');
      $xml .= $cell($p->tempat_lahir ?? '');
      $xml .= $cell($p->tanggal_lahir ?? '');
      $xml .= $cell($p->agama ?? '');
      $xml .= $cell($p->alamat_lengkap ?? '');
      $xml .= $cell($p->asal_sekolah);
      $xml .= $telp($p->no_telepon);
      $xml .= $cell(optional($p->paketBimbingan)->nama_paket ?? '');
      $xml .= $cell(optional($p->kelompokBelajar)->nama_kelompok ?? '');
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
