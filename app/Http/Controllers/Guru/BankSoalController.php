<?php

namespace App\Http\Controllers\Guru;
use App\Models\MasterData\Bank;

use App\Http\Controllers\Controller;
use App\Models\CBT\CbtBankSoal;
use App\Models\CBT\CbtOpsiJawaban;
use App\Models\CBT\CbtPembahasan;
use App\Models\CBT\CbtMapel;
use App\Models\CBT\CbtBab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Traits\HandlesImageUpload;
use App\Helpers\HtmlSanitizer;

class BankSoalController extends Controller
{
    use HandlesImageUpload;
    /**
     * Daftar semua soal milik guru (dengan filter & search).
     */
    public function index(Request $request)
    {
        $query = CbtBankSoal::with(['mapel', 'bab', 'opsiJawabans'])
            ->byGuru(Auth::id());

        // Search by teks soal
        if ($request->filled('search')) {
            $query->where('pertanyaan', 'like', '%' . $request->search . '%');
        }

        // Filter: mapel
        if ($request->filled('mapel')) {
            $query->where('cbt_mapel_id', $request->mapel);
        }

        // Filter: bab/topik
        if ($request->filled('bab')) {
            $query->where('cbt_bab_id', $request->bab);
        }

        // Filter: tipe soal
        if ($request->filled('tipe')) {
            $query->where('tipe_soal', $request->tipe);
        }

        // Filter: tingkat kesulitan
        if ($request->filled('kesulitan')) {
            $query->where('tingkat_kesulitan', $request->kesulitan);
        }

        $soals = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $mapels = CbtMapel::orderBy('nama')->get();
        $babs = CbtBab::when($request->mapel, fn($q) => $q->where('cbt_mapel_id', $request->mapel))
            ->orderBy('nama')->get();

        // Statistik ringkas
        $stats = [
            'total'   => CbtBankSoal::byGuru(Auth::id())->count(),
            'pg'      => CbtBankSoal::byGuru(Auth::id())->where('tipe_soal', 'pg')->count(),
            'essay'   => CbtBankSoal::byGuru(Auth::id())->where('tipe_soal', 'essay')->count(),
            'mudah'   => CbtBankSoal::byGuru(Auth::id())->where('tingkat_kesulitan', 'easy')->count(),
            'sedang'  => CbtBankSoal::byGuru(Auth::id())->where('tingkat_kesulitan', 'medium')->count(),
            'sulit'   => CbtBankSoal::byGuru(Auth::id())->where('tingkat_kesulitan', 'hard')->count(),
        ];

        return view('guru.bank-soal.index', compact('soals', 'mapels', 'babs', 'stats'));
    }

    /**
     * Form buat soal baru.
     */
    public function create()
    {
        $guru = Auth::user();
        if (!empty($guru->matapelajaran)) {
            $mapel = CbtMapel::firstOrCreate(['nama' => $guru->matapelajaran]);
            $mapels = collect([$mapel]);
            $babs = CbtBab::where('cbt_mapel_id', $mapel->id)->orderBy('nama')->get();
        } else {
            $mapel = null;
            $mapels = CbtMapel::orderBy('nama')->get();
            $babs = CbtBab::orderBy('nama')->get();
        }

        return view('guru.bank-soal.create', compact('mapels', 'babs', 'mapel'));
    }

    /**
     * Simpan soal baru + opsi jawaban + pembahasan.
     */
    public function store(Request $request)
    {
        $isBenarSalah = $request->tipe_soal === 'benar_salah';
        if ($isBenarSalah) {
            $request->merge(['tipe_soal' => 'pg']);
        }

        $request->validate([
            'pertanyaan'        => 'required|string',
            'tipe_soal'         => 'required|in:pg,essay',
            'tingkat_kesulitan' => 'required|in:easy,medium,hard',
            'cbt_mapel_id'      => 'nullable|exists:cbt_mapels,id',
            'cbt_bab_id'        => 'nullable|exists:cbt_babs,id',
            'media'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120', // Dinaikkan ke 5MB karena ada kompresi
            'pembahasan'        => 'nullable|string',
            'opsi'              => 'required_if:tipe_soal,pg|array',
            'opsi.*'            => 'required_if:tipe_soal,pg|string',
            'kunci'             => 'required_if:tipe_soal,pg|integer',
        ], [
            'media.image' => 'File yang diunggah harus berupa gambar.',
            'media.mimes' => 'Format gambar hanya diperbolehkan: JPG, JPEG, PNG, WEBP.',
            'media.max'   => 'Ukuran gambar maksimal adalah 5MB.',
            'pertanyaan.required' => 'Teks pertanyaan wajib diisi.',
            'tipe_soal.required' => 'Tipe soal harus dipilih.',
        ]);

        DB::beginTransaction();
        try {
            // Upload media jika ada
            $mediaPath = null;
            $mediaType = null;
            if ($request->hasFile('media')) {
                try {
                    $mediaPath = $this->compressAndStore($request->file('media'), 'bank-soal/media');
                    $mediaType = 'image';
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Error upload bank soal: ' . $e->getMessage());
                    return back()->withInput()->with('error', 'Gagal memproses file gambar. Pastikan format valid.');
                }
            }

            // Simpan soal
            $soal = CbtBankSoal::create([
                'pertanyaan'        => HtmlSanitizer::clean($request->pertanyaan),
                'tipe_soal'         => $request->tipe_soal,
                'tingkat_kesulitan' => $request->tingkat_kesulitan,
                'cbt_mapel_id'      => $request->cbt_mapel_id,
                'cbt_bab_id'        => $request->cbt_bab_id,
                'file_media'        => $mediaPath,
                'tipe_media'        => $mediaType,
                'status'            => 'published',
                'created_by'        => Auth::id(),
            ]);

            // Simpan opsi jawaban (PG)
            if ($request->tipe_soal === 'pg' && $request->has('opsi')) {
                $labels = ['A', 'B', 'C', 'D', 'E'];
                foreach ($request->opsi as $idx => $teks) {
                    if (empty(trim($teks))) continue;
                    CbtOpsiJawaban::create([
                        'cbt_bank_soal_id' => $soal->id,
                        'teks_opsi'        => HtmlSanitizer::clean($teks),
                        'is_benar'         => $idx == $request->kunci,
                    ]);
                }
            }

            // Simpan pembahasan
            if ($request->filled('pembahasan')) {
                CbtPembahasan::create([
                    'cbt_bank_soal_id'  => $soal->id,
                    'teks_pembahasan'   => HtmlSanitizer::clean($request->pembahasan),
                ]);
            }

            DB::commit();
            return redirect()->route('guru.bank-soal.index')
                ->with('success', 'Soal berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Store Soal Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan soal. Silakan coba lagi nanti.');
        }
    }

    /**
     * Detail soal (preview).
     */
    public function show(string $id)
    {
        $soal = CbtBankSoal::with(['mapel', 'bab', 'opsiJawabans', 'pembahasan', 'creator'])
            ->byGuru(Auth::id())
            ->findOrFail($id);

        return view('guru.bank-soal.show', compact('soal'));
    }

    /**
     * Form edit soal.
     */
    public function edit(string $id)
    {
        $soal = CbtBankSoal::with(['opsiJawabans', 'pembahasan'])
            ->byGuru(Auth::id())
            ->findOrFail($id);

        $guru = Auth::user();
        if (!empty($guru->matapelajaran)) {
            $mapel = CbtMapel::firstOrCreate(['nama' => $guru->matapelajaran]);
            $mapels = collect([$mapel]);
            $babs = CbtBab::where('cbt_mapel_id', $mapel->id)->orderBy('nama')->get();
        } else {
            $mapel = null;
            $mapels = CbtMapel::orderBy('nama')->get();
            $babs = CbtBab::when($soal->cbt_mapel_id, fn($q) => $q->where('cbt_mapel_id', $soal->cbt_mapel_id))
                ->orderBy('nama')->get();
        }

        return view('guru.bank-soal.edit', compact('soal', 'mapels', 'babs', 'mapel'));
    }

    /**
     * Update soal.
     */
    public function update(Request $request, string $id)
    {
        $soal = CbtBankSoal::byGuru(Auth::id())->findOrFail($id);

        $isBenarSalah = $request->tipe_soal === 'benar_salah';
        if ($isBenarSalah) {
            $request->merge(['tipe_soal' => 'pg']);
        }

        $request->validate([
            'pertanyaan'        => 'required|string',
            'tipe_soal'         => 'required|in:pg,essay',
            'tingkat_kesulitan' => 'required|in:easy,medium,hard',
            'cbt_mapel_id'      => 'nullable|exists:cbt_mapels,id',
            'cbt_bab_id'        => 'nullable|exists:cbt_babs,id',
            'media'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'pembahasan'        => 'nullable|string',
            'opsi'              => 'required_if:tipe_soal,pg|array',
            'opsi.*'            => 'required_if:tipe_soal,pg|string',
            'kunci'             => 'required_if:tipe_soal,pg|integer',
        ], [
            'media.image' => 'File yang diunggah harus berupa gambar.',
            'media.mimes' => 'Format gambar hanya diperbolehkan: JPG, JPEG, PNG, WEBP.',
            'media.max'   => 'Ukuran gambar maksimal adalah 5MB.',
        ]);

        DB::beginTransaction();
        try {
            // Upload media jika ada file baru
            $mediaData = [];
            if ($request->hasFile('media')) {
                try {
                    // Hapus media lama
                    if ($soal->file_media) {
                        Storage::disk('public')->delete($soal->file_media);
                    }
                    $mediaData['file_media'] = $this->compressAndStore($request->file('media'), 'bank-soal/media');
                    $mediaData['tipe_media'] = 'image';
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Error update bank soal: ' . $e->getMessage());
                    return back()->withInput()->with('error', 'Gagal memproses file gambar baru.');
                }
            }

            // Update soal
            $soal->update(array_merge([
                'pertanyaan'        => HtmlSanitizer::clean($request->pertanyaan),
                'tipe_soal'         => $request->tipe_soal,
                'tingkat_kesulitan' => $request->tingkat_kesulitan,
                'cbt_mapel_id'      => $request->cbt_mapel_id,
                'cbt_bab_id'        => $request->cbt_bab_id,
            ], $mediaData));

            // Update opsi jawaban (PG): hapus lama, buat baru
            if ($request->tipe_soal === 'pg' && $request->has('opsi')) {
                $soal->opsiJawabans()->delete();
                foreach ($request->opsi as $idx => $teks) {
                    if (empty(trim($teks))) continue;
                    CbtOpsiJawaban::create([
                        'cbt_bank_soal_id' => $soal->id,
                        'teks_opsi'        => HtmlSanitizer::clean($teks),
                        'is_benar'         => $idx == $request->kunci,
                    ]);
                }
            } elseif ($request->tipe_soal === 'essay') {
                $soal->opsiJawabans()->delete();
            }

            // Update pembahasan
            if ($request->filled('pembahasan')) {
                CbtPembahasan::updateOrCreate(
                    ['cbt_bank_soal_id' => $soal->id],
                    ['teks_pembahasan' => HtmlSanitizer::clean($request->pembahasan)]
                );
            } else {
                $soal->pembahasan()->delete();
            }

            DB::commit();
            return redirect()->route('guru.bank-soal.index')
                ->with('success', 'Soal berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Update Soal Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui soal.');
        }
    }

    /**
     * Hapus soal (soft delete).
     */
    public function destroy(string $id)
    {
        $soal = CbtBankSoal::byGuru(Auth::id())->findOrFail($id);
        $soal->delete();

        return redirect()->route('guru.bank-soal.index')
            ->with('success', 'Soal berhasil dihapus.');
    }

    /**
     * JSON: list topik/bab berdasarkan mapel (dependent dropdown).
     */
    public function getBabByMapel(Request $request)
    {
        $babs = CbtBab::where('cbt_mapel_id', $request->mapel_id)
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return response()->json($babs);
    }

    /**
     * Kelola mapel (AJAX store).
     */
    public function storeMapel(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:cbt_mapels,nama']);
        $mapel = CbtMapel::create(['nama' => $request->nama]);

        return response()->json($mapel);
    }

    /**
     * Kelola bab/topik (AJAX store).
     */
    public function storeBab(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'cbt_mapel_id'  => 'required|exists:cbt_mapels,id',
        ]);
        $bab = CbtBab::create([
            'nama'          => $request->nama,
            'cbt_mapel_id'  => $request->cbt_mapel_id,
        ]);

        return response()->json($bab);
    }

    /**
     * Download template CSV untuk import soal
     */
    /**
     * Download template Excel untuk import soal
     */
    public function template()
    {
        $filename = 'Template_Import_Soal_Genius.xls';
        $xml = $this->buildXmlTemplate();

        return response($xml, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }

    /**
     * Import soal dari file CSV
     */
    /**
     * Import soal dari file CSV atau XML Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xls,xml,txt|max:5120'
        ], [
            'file.required' => 'Silakan pilih file untuk diimport.',
            'file.mimes'    => 'Format file tidak didukung. Gunakan template CSV atau Excel (XLS) yang tersedia.',
            'file.max'      => 'Ukuran file maksimal adalah 5MB.',
        ]);

        $file = $request->file('file');
        $content = file_get_contents($file->getRealPath());
        $dataRows = [];

        // Deteksi file binary (seperti .xlsx atau .xls binary asli) yang bisa memicu Error Laravel
        $isZip = str_starts_with($content, "PK\x03\x04");
        $isOle2 = str_starts_with($content, "\xD0\xCF\x11\xE0");

        if ($isZip || $isOle2) {
            return back()->with('error', 'Format file tidak didukung! Pastikan Anda menyimpan (Save) template tanpa mengubah jenis formatnya. Jika Anda tidak sengaja menyimpannya sebagai "Excel Workbook (*.xlsx)", silakan Save As kembali ke format "XML Spreadsheet 2003" atau "CSV" agar bisa diupload.');
        }

        // Deteksi apakah ini XML (Template Baru) atau CSV (Lama)
        if (str_contains($content, '<?xml') && str_contains($content, 'Workbook')) {
            try {
                $dataRows = $this->parseXmlFile($content);
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal membaca format XML: ' . $e->getMessage());
            }
        } else {
            $dataRows = $this->parseCsvFile($file->getRealPath());
        }

        if (empty($dataRows)) {
            return back()->with('error', 'Tidak ada data valid yang ditemukan di dalam file.');
        }

        DB::beginTransaction();
        try {
            // Pre-fetch mapel & bab IDs to prevent N+1 queries during validation
            $mapelSet = \Illuminate\Support\Facades\DB::table('cbt_mapels')->pluck('id')->flip()->toArray();
            $babSet = \Illuminate\Support\Facades\DB::table('cbt_babs')->pluck('id')->flip()->toArray();

            foreach ($dataRows as $row) {
                $mapel_id   = $row[0] ?? null;
                $bab_id     = $row[1] ?? null;
                $tipe_soal  = strtolower($row[2] ?? 'pg');
                $kesulitan  = strtolower($row[3] ?? 'medium');
                $pertanyaan = $row[4] ?? '';
                $opsi       = [
                    $row[5] ?? '', 
                    $row[6] ?? '', 
                    $row[7] ?? '', 
                    $row[8] ?? '', 
                    $row[9] ?? ''
                ];
                $kunci      = strtoupper($row[10] ?? 'A');
                $pembahasan = $row[11] ?? null;

                if (empty(trim($pertanyaan))) continue;

                // Validasi Mapel_ID dan Bab_ID
                $finalMapelId = null;
                if (!empty($mapel_id) && is_numeric($mapel_id) && isset($mapelSet[$mapel_id])) {
                    $finalMapelId = $mapel_id;
                }

                $finalBabId = null;
                if (!empty($bab_id) && is_numeric($bab_id) && isset($babSet[$bab_id])) {
                    $finalBabId = $bab_id;
                }

                $soal = CbtBankSoal::create([
                    'cbt_mapel_id'      => $finalMapelId,
                    'cbt_bab_id'        => $finalBabId,
                    'tipe_soal'         => in_array($tipe_soal, ['pg', 'essay']) ? $tipe_soal : 'pg',
                    'tingkat_kesulitan' => in_array($kesulitan, ['easy', 'medium', 'hard']) ? $kesulitan : 'medium',
                    'pertanyaan'        => $pertanyaan,
                    'status'            => 'published',
                    'created_by'        => Auth::id()
                ]);

                if ($soal->tipe_soal === 'pg') {
                    $kunciIdx = array_search($kunci, ['A','B','C','D','E']);
                    if ($kunciIdx === false) $kunciIdx = 0;

                    foreach ($opsi as $idx => $teks_opsi) {
                        if (empty(trim($teks_opsi))) continue;
                        CbtOpsiJawaban::create([
                            'cbt_bank_soal_id' => $soal->id,
                            'teks_opsi'        => $teks_opsi,
                            'is_benar'         => ($idx === $kunciIdx)
                        ]);
                    }
                }

                if (!empty(trim($pembahasan))) {
                    CbtPembahasan::create([
                        'cbt_bank_soal_id' => $soal->id,
                        'teks_pembahasan'  => $pembahasan
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('guru.bank-soal.index')->with('success', count($dataRows) . ' Soal berhasil diimport.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('guru.bank-soal.index')->with('error', 'Gagal mengimport soal: ' . $e->getMessage());
        }
    }

    // ═══════════════════════════════════════════════════════
    // PRIVATE HELPERS
    // ═══════════════════════════════════════════════════════

    private function buildXmlTemplate(): string
    {
        $columns = ['Mapel_ID', 'Bab_ID', 'Tipe_Soal', 'Kesulitan', 'Pertanyaan', 'Opsi_A', 'Opsi_B', 'Opsi_C', 'Opsi_D', 'Opsi_E', 'Kunci_Jawaban', 'Pembahasan'];
        $x = fn($v) => htmlspecialchars((string) ($v ?? ''), ENT_XML1, 'UTF-8');
        
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
            <Style ss:ID="s_head">
                <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
                <Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1" ss:Color="#FFFFFF"/>
                <Interior ss:Color="#4F46E5" ss:Pattern="Solid"/>
                <Borders>
                    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#3730A3"/>
                    <Border ss:Position="Right"  ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#3730A3"/>
                </Borders>
            </Style>
            <Style ss:ID="s_info">
                <Font ss:FontName="Calibri" ss:Size="10" ss:Italic="1" ss:Color="#4B5563"/>
                <Interior ss:Color="#F3F4F6" ss:Pattern="Solid"/>
            </Style>
            <Style ss:ID="s_data">
                <Alignment ss:Vertical="Top" ss:WrapText="1"/>
                <Font ss:FontName="Calibri" ss:Size="10"/>
                <Borders>
                    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
                    <Border ss:Position="Right"  ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
                </Borders>
            </Style>
        </Styles>' . "\n";

        $xml .= '<Worksheet ss:Name="Template Import">' . "\n";
        $xml .= '<Table ss:DefaultRowHeight="18">' . "\n";
        
        $widths = [60, 60, 80, 80, 250, 120, 120, 120, 120, 120, 100, 200];
        foreach ($widths as $w) {
            $xml .= '<Column ss:Width="' . $w . '"/>' . "\n";
        }

        $xml .= '<Row ss:Height="25">';
        foreach ($columns as $h) {
            $xml .= '<Cell ss:StyleID="s_head"><Data ss:Type="String">' . $x($h) . '</Data></Cell>';
        }
        $xml .= '</Row>' . "\n";

        $hints = [
            'Isi ID Mapel', 'Isi ID Bab', 'pg / essay', 'easy/medium/hard', 
            'Tulis soal di sini', 'Opsi A', 'Opsi B', 'Opsi C', 'Opsi D', 'Opsi E', 
            'A/B/C/D/E', 'Tulis pembahasan'
        ];
        $xml .= '<Row ss:Height="18">';
        foreach ($hints as $h) {
            $xml .= '<Cell ss:StyleID="s_info"><Data ss:Type="String">' . $x($h) . '</Data></Cell>';
        }
        $xml .= '</Row>' . "\n";

        $xml .= '<Row ss:Height="40">';
        $xml .= '<Cell ss:StyleID="s_data"><Data ss:Type="String"></Data></Cell>';
        $xml .= '<Cell ss:StyleID="s_data"><Data ss:Type="String"></Data></Cell>';
        $xml .= '<Cell ss:StyleID="s_data"><Data ss:Type="String">pg</Data></Cell>';
        $xml .= '<Cell ss:StyleID="s_data"><Data ss:Type="String">medium</Data></Cell>';
        $xml .= '<Cell ss:StyleID="s_data"><Data ss:Type="String">Berapa hasil dari 1 + 1?</Data></Cell>';
        $xml .= '<Cell ss:StyleID="s_data"><Data ss:Type="String">1</Data></Cell>';
        $xml .= '<Cell ss:StyleID="s_data"><Data ss:Type="String">2</Data></Cell>';
        $xml .= '<Cell ss:StyleID="s_data"><Data ss:Type="String">3</Data></Cell>';
        $xml .= '<Cell ss:StyleID="s_data"><Data ss:Type="String">4</Data></Cell>';
        $xml .= '<Cell ss:StyleID="s_data"><Data ss:Type="String">5</Data></Cell>';
        $xml .= '<Cell ss:StyleID="s_data"><Data ss:Type="String">B</Data></Cell>';
        $xml .= '<Cell ss:StyleID="s_data"><Data ss:Type="String">Karena penjumlahan 1 dengan 1 hasilnya adalah 2.</Data></Cell>';
        $xml .= '</Row>' . "\n";

        $xml .= '</Table></Worksheet></Workbook>';
        return $xml;
    }

    private function parseXmlFile(string $content): array
    {
        $dataRows = [];
        $dom = new \DOMDocument();
        $dom->loadXML($content);
        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('ss', 'urn:schemas-microsoft-com:office:spreadsheet');
        $xpath->registerNamespace('main', 'urn:schemas-microsoft-com:office:spreadsheet');

        $rows = $xpath->query('//ss:Row | //main:Row | //Row');
        
        foreach ($rows as $index => $row) {
            if ($index < 2) continue;

            $cells = $xpath->query('.//ss:Cell | .//main:Cell | .//Cell', $row);
            $rowData = array_fill(0, 12, '');
            $currentIdx = 0;

            foreach ($cells as $cell) {
                if ($cell->hasAttributeNS('urn:schemas-microsoft-com:office:spreadsheet', 'Index')) {
                    $currentIdx = (int)$cell->getAttributeNS('urn:schemas-microsoft-com:office:spreadsheet', 'Index') - 1;
                } elseif ($cell->hasAttribute('ss:Index')) {
                    $currentIdx = (int)$cell->getAttribute('ss:Index') - 1;
                }

                $dataNodes = $xpath->query('.//ss:Data | .//main:Data | .//Data', $cell);
                if ($dataNodes->length > 0) {
                    $rowData[$currentIdx] = $dataNodes->item(0)->nodeValue;
                }
                $currentIdx++;
            }

            if (!empty(trim($rowData[4] ?? ''))) {
                $dataRows[] = $rowData;
            }
        }
        return $dataRows;
    }

    private function parseCsvFile(string $path): array
    {
        $dataRows = [];
        $handle = fopen($path, "r");
        $header = true;
        while (($row = fgetcsv($handle, 10000, ",")) !== FALSE) {
            if ($header) {
                $header = false;
                continue;
            }
            if (count($row) >= 5) {
                $dataRows[] = $row;
            }
        }
        fclose($handle);
        return $dataRows;
    }
}


