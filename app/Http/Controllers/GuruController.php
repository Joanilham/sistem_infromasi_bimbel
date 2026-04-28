<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    /**
     * Display a listing of active guru.
     */
    public function index()
    {
        $kantorId = session('kantor_id');
        $periodeId = session('periode_id');

        $gurus = \Illuminate\Support\Facades\Cache::rememberForever("data_gurus_aktif_{$kantorId}_{$periodeId}", function () {
            return User::where('level', 'guru')
                ->where('status', 'Aktif')
                ->latest()
                ->get();
        });

        return view('admin.guru.aktif', compact('gurus'));
    }

    /**
     * Display a listing of guru who have left.
     */
    public function keluar()
    {
        $kantorId = session('kantor_id');
        $periodeId = session('periode_id');

        $gurus = \Illuminate\Support\Facades\Cache::rememberForever("data_gurus_keluar_{$kantorId}_{$periodeId}", function () {
            return User::where('level', 'guru')
                ->where('status', 'Keluar')
                ->orderBy('tanggal_keluar', 'desc')
                ->get();
        });

        return view('admin.guru.keluar', compact('gurus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.guru.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'nip'             => 'nullable|string|max:20',
            'alamat'          => 'nullable|string|max:500',
            'matapelajaran'   => 'required|string|max:255',
            'no_telp'         => ['nullable', 'regex:/^[0-9]{8,15}$/', 'max:15'],
            'email'           => 'required|string|email|max:255|unique:users',
            'password'        => 'required|string|min:8|confirmed',
        ], [
            'no_telp.regex' => 'Nomor telepon hanya boleh berisi angka (8-15 digit).',
        ]);

        $validated['password']  = Hash::make($validated['password']);
        $validated['level']     = 'guru';
        $validated['status']    = 'Aktif';
        $validated['is_active'] = true;
        $validated['username']  = $request->email;

        unset($validated['password_confirmation']);
        User::create($validated);

        $this->clearGuruCache();

        return redirect()->route('guru.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $guru = User::where('level', 'guru')->findOrFail($id);
        return view('admin.guru.edit', compact('guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $guru = User::where('level', 'guru')->findOrFail($id);

        // Debug: log request data
        \Illuminate\Support\Facades\Log::info('Guru update request:', $request->all());

        // Fallback: jika status tidak ada, gunakan status saat ini dari database
        if (!$request->has('status') || empty($request->status)) {
            $request->merge(['status' => $guru->status ?? 'Aktif']);
        }

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'nip'           => 'nullable|string|max:20',
            'alamat'        => 'nullable|string|max:500',
            'matapelajaran' => 'required|string|max:255',
            'no_telp'       => ['nullable', 'regex:/^[0-9]{8,15}$/', 'max:15'],
            'email'         => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($guru->id)],
            'password'      => 'nullable|string|min:8|confirmed',
            'status'        => 'required|in:Aktif,Keluar',
            'tanggal_keluar' => 'nullable|date',
            'alasan_keluar' => 'nullable|string',
        ], [
            'no_telp.regex' => 'Nomor telepon hanya boleh berisi angka (8-15 digit).',
        ]);

        // Hapus field keluar jika status bukan Keluar
        if ($validated['status'] !== 'Keluar') {
            $validated['tanggal_keluar'] = null;
            $validated['alasan_keluar']  = null;
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['username'] = $validated['email'];
        unset($validated['password_confirmation']);
        $guru->update($validated);

        // Debug: log after update
        \Illuminate\Support\Facades\Log::info('Guru updated successfully:', ['id' => $guru->id, 'status' => $guru->fresh()->status]);

        $this->clearGuruCache();

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $guru = User::where('level', 'guru')->findOrFail($id);
        $guru->delete();

        $this->clearGuruCache();

        return redirect()->back()->with('success', 'Guru berhasil dihapus.');
    }

    /**
     * Export active guru to Excel (XML Spreadsheet 2003).
     */
    public function export()
    {
        $gurus = User::where('level', 'guru')
            ->where('status', 'Aktif')
            ->latest()
            ->get();

        $filename = 'Guru_Aktif_' . date('d-m-Y') . '.xls';

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
    <Interior ss:Color="#059669" ss:Pattern="Solid"/>
    <Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#6EE7B7"/></Borders>
  </Style>
  <Style ss:ID="s_info">
    <Alignment ss:Vertical="Center"/>
    <Font ss:FontName="Calibri" ss:Size="10" ss:Italic="1" ss:Color="#374151"/>
    <Interior ss:Color="#ECFDF5" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s_head">
    <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
    <Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1" ss:Color="#FFFFFF"/>
    <Interior ss:Color="#059669" ss:Pattern="Solid"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#047857"/>
      <Border ss:Position="Right"  ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#047857"/>
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
    <Interior ss:Color="#ECFDF5" ss:Pattern="Solid"/>
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
    <Interior ss:Color="#ECFDF5" ss:Pattern="Solid"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Right"  ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
    </Borders>
  </Style>
</Styles>' . "\n";

        $headers = ['No', 'Nama Lengkap', 'Alamat', 'NIP', 'Mata Pelajaran', 'No. Telp', 'Email'];
        $cols    = count($headers);

        $xml .= '<Worksheet ss:Name="Guru Aktif">' . "\n";
        $xml .= '<Table ss:DefaultRowHeight="18">' . "\n";

        $widths = [30, 150, 180, 80, 150, 80, 180];
        foreach ($widths as $w) {
            $xml .= '<Column ss:Width="' . $w . '"/>' . "\n";
        }

        // Title row
        $xml .= '<Row ss:Height="28">'
            . '<Cell ss:StyleID="s_title" ss:MergeAcross="' . ($cols - 1) . '">'
            . '<Data ss:Type="String">DATA GURU AKTIF — GENIUS EDUCATION</Data>'
            . '</Cell></Row>' . "\n";

        // Info row
        $xml .= '<Row ss:Height="20">'
            . '<Cell ss:StyleID="s_info" ss:MergeAcross="' . ($cols - 1) . '">'
            . '<Data ss:Type="String">Tanggal Export: ' . date('d-m-Y H:i:s') . '</Data>'
            . '</Cell></Row>' . "\n";

        // Header row
        $xml .= '<Row ss:Height="22">';
        foreach ($headers as $header) {
            $xml .= $bold($header);
        }
        $xml .= '</Row>' . "\n";

        // Data rows
        foreach ($gurus as $index => $guru) {
            $style = $index % 2 === 0 ? 's_data' : 's_data2';
            $textStyle = $index % 2 === 0 ? 's_text' : 's_text2';

            $xml .= '<Row ss:Height="18">';
            $xml .= '<Cell ss:StyleID="' . $style . '"><Data ss:Type="Number">' . ($index + 1) . '</Data></Cell>';
            $xml .= '<Cell ss:StyleID="' . $textStyle . '"><Data ss:Type="String">' . $x($guru->name) . '</Data></Cell>';
            $xml .= '<Cell ss:StyleID="' . $textStyle . '"><Data ss:Type="String">' . $x($guru->alamat) . '</Data></Cell>';
            $xml .= '<Cell ss:StyleID="' . $textStyle . '"><Data ss:Type="String">' . $x($guru->nip) . '</Data></Cell>';
            $xml .= '<Cell ss:StyleID="' . $textStyle . '"><Data ss:Type="String">' . $x($guru->matapelajaran) . '</Data></Cell>';
            $xml .= '<Cell ss:StyleID="' . $textStyle . '"><Data ss:Type="String">' . $x($guru->no_telp) . '</Data></Cell>';
            $xml .= '<Cell ss:StyleID="' . $textStyle . '"><Data ss:Type="String">' . $x($guru->email) . '</Data></Cell>';
            $xml .= '</Row>' . "\n";
        }

        $xml .= '</Table>' . "\n";
        $xml .= '</Worksheet>' . "\n";
        $xml .= '</Workbook>' . "\n";

        return response($xml, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export guru who have left to Excel (XML Spreadsheet 2003).
     */
    public function exportKeluar()
    {
        $gurus = User::where('level', 'guru')
            ->where('status', 'Keluar')
            ->orderBy('tanggal_keluar', 'desc')
            ->get();

        $filename = 'Guru_Keluar_' . date('d-m-Y') . '.xls';

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

        $headers = ['No', 'Nama Lengkap', 'Alamat', 'NIP', 'Mata Pelajaran', 'No. Telp', 'Email', 'Tanggal Keluar', 'Alasan Keluar'];
        $cols    = count($headers);

        $xml .= '<Worksheet ss:Name="Guru Keluar">' . "\n";
        $xml .= '<Table ss:DefaultRowHeight="18">' . "\n";

        $widths = [30, 140, 150, 80, 130, 80, 150, 100, 180];
        foreach ($widths as $w) {
            $xml .= '<Column ss:Width="' . $w . '"/>' . "\n";
        }

        // Title row
        $xml .= '<Row ss:Height="28">'
            . '<Cell ss:StyleID="s_title" ss:MergeAcross="' . ($cols - 1) . '">'
            . '<Data ss:Type="String">DATA GURU KELUAR — GENIUS EDUCATION</Data>'
            . '</Cell></Row>' . "\n";

        // Info row
        $xml .= '<Row ss:Height="20">'
            . '<Cell ss:StyleID="s_info" ss:MergeAcross="' . ($cols - 1) . '">'
            . '<Data ss:Type="String">Tanggal Export: ' . date('d-m-Y H:i:s') . '</Data>'
            . '</Cell></Row>' . "\n";

        // Header row
        $xml .= '<Row ss:Height="22">';
        foreach ($headers as $header) {
            $xml .= $bold($header);
        }
        $xml .= '</Row>' . "\n";

        // Data rows
        foreach ($gurus as $index => $guru) {
            $style = $index % 2 === 0 ? 's_data' : 's_data2';
            $textStyle = $index % 2 === 0 ? 's_text' : 's_text2';

            $xml .= '<Row ss:Height="18">';
            $xml .= '<Cell ss:StyleID="' . $style . '"><Data ss:Type="Number">' . ($index + 1) . '</Data></Cell>';
            $xml .= '<Cell ss:StyleID="' . $textStyle . '"><Data ss:Type="String">' . $x($guru->name) . '</Data></Cell>';
            $xml .= '<Cell ss:StyleID="' . $textStyle . '"><Data ss:Type="String">' . $x($guru->alamat) . '</Data></Cell>';
            $xml .= '<Cell ss:StyleID="' . $textStyle . '"><Data ss:Type="String">' . $x($guru->nip) . '</Data></Cell>';
            $xml .= '<Cell ss:StyleID="' . $textStyle . '"><Data ss:Type="String">' . $x($guru->matapelajaran) . '</Data></Cell>';
            $xml .= '<Cell ss:StyleID="' . $textStyle . '"><Data ss:Type="String">' . $x($guru->no_telp) . '</Data></Cell>';
            $xml .= '<Cell ss:StyleID="' . $textStyle . '"><Data ss:Type="String">' . $x($guru->email) . '</Data></Cell>';
            $xml .= '<Cell ss:StyleID="' . $textStyle . '"><Data ss:Type="String">' . $x($guru->tanggal_keluar) . '</Data></Cell>';
            $xml .= '<Cell ss:StyleID="' . $textStyle . '"><Data ss:Type="String">' . $x($guru->alasan_keluar) . '</Data></Cell>';
            $xml .= '</Row>' . "\n";
        }

        $xml .= '</Table>' . "\n";
        $xml .= '</Worksheet>' . "\n";
        $xml .= '</Workbook>' . "\n";

        return response($xml, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Clear cache for guru data.
     */
    private function clearGuruCache(): void
    {
        $kantorId  = session('kantor_id');
        $periodeId = session('periode_id');

        \Illuminate\Support\Facades\Cache::forget("data_gurus_aktif_{$kantorId}_{$periodeId}");
        \Illuminate\Support\Facades\Cache::forget("data_gurus_keluar_{$kantorId}_{$periodeId}");
    }
}