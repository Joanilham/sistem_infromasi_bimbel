<?php

namespace App\Services;

use App\Helpers\HtmlSanitizer;
use App\Models\CBT\CbtBab;
use App\Models\CBT\CbtBankSoal;
use App\Models\CBT\CbtMapel;
use App\Models\CBT\CbtOpsiJawaban;
use App\Models\CBT\CbtPembahasan;
use App\Models\User;
use App\Traits\HandlesImageUpload;
use DOMDocument;
use DOMXPath;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BankSoalService
{
    use HandlesImageUpload;

    public function getFilteredSoal(int $guruId, Request $request): LengthAwarePaginator
    {
        $query = CbtBankSoal::with(['mapel', 'bab', 'opsiJawabans'])
            ->byGuru($guruId);

        if ($request->filled('search')) {
            $query->where('pertanyaan', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('mapel')) {
            $query->where('cbt_mapel_id', $request->input('mapel'));
        }

        if ($request->filled('bab')) {
            $query->where('cbt_bab_id', $request->input('bab'));
        }

        if ($request->filled('tipe')) {
            $query->where('tipe_soal', $request->input('tipe'));
        }

        if ($request->filled('kesulitan')) {
            $query->where('tingkat_kesulitan', $request->input('kesulitan'));
        }

        return $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
    }

    public function getGuruStats(int $guruId): array
    {
        return [
            'total'   => CbtBankSoal::byGuru($guruId)->count(),
            'pg'      => CbtBankSoal::byGuru($guruId)->where('tipe_soal', 'pg')->count(),
            'essay'   => CbtBankSoal::byGuru($guruId)->where('tipe_soal', 'essay')->count(),
            'mudah'   => CbtBankSoal::byGuru($guruId)->where('tingkat_kesulitan', 'easy')->count(),
            'sedang'  => CbtBankSoal::byGuru($guruId)->where('tingkat_kesulitan', 'medium')->count(),
            'sulit'   => CbtBankSoal::byGuru($guruId)->where('tingkat_kesulitan', 'hard')->count(),
        ];
    }

    public function getFormDataForGuru(User $user, ?CbtBankSoal $soal = null): array
    {
        if (!empty($user->matapelajaran)) {
            $mapel = CbtMapel::firstOrCreate(['nama' => $user->matapelajaran]);
            $mapels = collect([$mapel]);
            $babs = CbtBab::where('cbt_mapel_id', $mapel->id)->orderBy('nama')->get();
        } else {
            $mapel = null;
            $mapels = CbtMapel::orderBy('nama')->get();
            if ($soal && $soal->cbt_mapel_id) {
                $babs = CbtBab::where('cbt_mapel_id', $soal->cbt_mapel_id)->orderBy('nama')->get();
            } else {
                $babs = CbtBab::orderBy('nama')->get();
            }
        }

        return compact('mapels', 'babs', 'mapel');
    }

    public function createSoal(User $user, array $validated, ?UploadedFile $media = null): CbtBankSoal
    {
        return DB::transaction(function () use ($user, $validated, $media) {
            $mediaPath = null;
            $mediaType = null;
            if ($media) {
                $mediaPath = $this->compressAndStore($media, 'bank-soal/media');
                $mediaType = 'image';
            }

            $soal = CbtBankSoal::create([
                'pertanyaan'        => HtmlSanitizer::clean($validated['pertanyaan']),
                'tipe_soal'         => $validated['tipe_soal'],
                'tingkat_kesulitan' => $validated['tingkat_kesulitan'],
                'cbt_mapel_id'      => $validated['cbt_mapel_id'] ?? null,
                'cbt_bab_id'        => $validated['cbt_bab_id'] ?? null,
                'file_media'        => $mediaPath,
                'tipe_media'        => $mediaType,
                'status'            => 'published',
                'created_by'        => $user->id,
            ]);

            if ($validated['tipe_soal'] === 'pg' && !empty($validated['opsi'])) {
                foreach ($validated['opsi'] as $idx => $teks) {
                    if (empty(trim((string) $teks))) {
                        continue;
                    }
                    CbtOpsiJawaban::create([
                        'cbt_bank_soal_id' => $soal->id,
                        'teks_opsi'        => HtmlSanitizer::clean($teks),
                        'is_benar'         => (int) $idx === (int) ($validated['kunci'] ?? 0),
                    ]);
                }
            }

            if (!empty($validated['pembahasan'])) {
                CbtPembahasan::create([
                    'cbt_bank_soal_id' => $soal->id,
                    'teks_pembahasan'  => HtmlSanitizer::clean($validated['pembahasan']),
                ]);
            }

            return $soal;
        });
    }

    public function updateSoal(CbtBankSoal $soal, array $validated, ?UploadedFile $media = null): CbtBankSoal
    {
        return DB::transaction(function () use ($soal, $validated, $media) {
            $mediaData = [];
            if ($media) {
                if ($soal->file_media) {
                    Storage::disk('public')->delete($soal->file_media);
                }
                $mediaData['file_media'] = $this->compressAndStore($media, 'bank-soal/media');
                $mediaData['tipe_media'] = 'image';
            }

            $soal->update(array_merge([
                'pertanyaan'        => HtmlSanitizer::clean($validated['pertanyaan']),
                'tipe_soal'         => $validated['tipe_soal'],
                'tingkat_kesulitan' => $validated['tingkat_kesulitan'],
                'cbt_mapel_id'      => $validated['cbt_mapel_id'] ?? null,
                'cbt_bab_id'        => $validated['cbt_bab_id'] ?? null,
            ], $mediaData));

            if ($validated['tipe_soal'] === 'pg' && !empty($validated['opsi'])) {
                $soal->opsiJawabans()->delete();
                foreach ($validated['opsi'] as $idx => $teks) {
                    if (empty(trim((string) $teks))) {
                        continue;
                    }
                    CbtOpsiJawaban::create([
                        'cbt_bank_soal_id' => $soal->id,
                        'teks_opsi'        => HtmlSanitizer::clean($teks),
                        'is_benar'         => (int) $idx === (int) ($validated['kunci'] ?? 0),
                    ]);
                }
            } elseif ($validated['tipe_soal'] === 'essay') {
                $soal->opsiJawabans()->delete();
            }

            if (!empty($validated['pembahasan'])) {
                CbtPembahasan::updateOrCreate(
                    ['cbt_bank_soal_id' => $soal->id],
                    ['teks_pembahasan' => HtmlSanitizer::clean($validated['pembahasan'])]
                );
            } else {
                $soal->pembahasan()->delete();
            }

            return $soal;
        });
    }

    public function deleteSoal(CbtBankSoal $soal): bool
    {
        return (bool) $soal->delete();
    }

    public function buildXmlTemplate(): string
    {
        $columns = ['Mapel_ID', 'Bab_ID', 'Tipe_Soal', 'Kesulitan', 'Pertanyaan', 'Opsi_A', 'Opsi_B', 'Opsi_C', 'Opsi_D', 'Opsi_E', 'Kunci_Jawaban', 'Pembahasan'];
        $x = fn($v) => htmlspecialchars((string) ($v ?? ''), ENT_XML1, 'UTF-8');

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"'
              . ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"'
              . ' xmlns:o="urn:schemas-microsoft-com:office:office">' . "\n";

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

    public function importSoal(UploadedFile $file, int $guruId): array
    {
        $content = file_get_contents($file->getRealPath());
        $dataRows = [];

        if (str_contains($content, '<?xml') && str_contains($content, 'Workbook')) {
            try {
                $dataRows = $this->parseXmlFile($content);
            } catch (\Throwable $e) {
                return ['success' => false, 'message' => 'Gagal membaca format XML: ' . $e->getMessage()];
            }
        } else {
            $dataRows = $this->parseCsvFile($file->getRealPath());
        }

        if (empty($dataRows)) {
            return ['success' => false, 'message' => 'Tidak ada data valid yang ditemukan di dalam file.'];
        }

        DB::beginTransaction();
        try {
            $mapelSet = DB::table('cbt_mapels')->pluck('id')->flip()->toArray();
            $babSet = DB::table('cbt_babs')->pluck('id')->flip()->toArray();

            foreach ($dataRows as $row) {
                $mapel_id   = $row[0] ?? null;
                $bab_id     = $row[1] ?? null;
                $tipe_soal  = strtolower((string) ($row[2] ?? 'pg'));
                $kesulitan  = strtolower((string) ($row[3] ?? 'medium'));
                $pertanyaan = $row[4] ?? '';
                $opsi       = [
                    $row[5] ?? '',
                    $row[6] ?? '',
                    $row[7] ?? '',
                    $row[8] ?? '',
                    $row[9] ?? ''
                ];
                $kunci      = strtoupper((string) ($row[10] ?? 'A'));
                $pembahasan = $row[11] ?? null;

                if (empty(trim((string) $pertanyaan))) {
                    continue;
                }

                $finalMapelId = null;
                if (!empty($mapel_id) && is_numeric($mapel_id) && isset($mapelSet[(int) $mapel_id])) {
                    $finalMapelId = (int) $mapel_id;
                }

                $finalBabId = null;
                if (!empty($bab_id) && is_numeric($bab_id) && isset($babSet[(int) $bab_id])) {
                    $finalBabId = (int) $bab_id;
                }

                $soal = CbtBankSoal::create([
                    'pertanyaan'        => HtmlSanitizer::clean($pertanyaan),
                    'tipe_soal'         => in_array($tipe_soal, ['pg', 'essay'], true) ? $tipe_soal : 'pg',
                    'tingkat_kesulitan' => in_array($kesulitan, ['easy', 'medium', 'hard'], true) ? $kesulitan : 'medium',
                    'cbt_mapel_id'      => $finalMapelId,
                    'cbt_bab_id'        => $finalBabId,
                    'status'            => 'published',
                    'created_by'        => $guruId,
                ]);

                if ($soal->tipe_soal === 'pg') {
                    $kunciIdx = array_search($kunci, ['A', 'B', 'C', 'D', 'E'], true);
                    if ($kunciIdx === false) {
                        $kunciIdx = 0;
                    }

                    foreach ($opsi as $idx => $teks_opsi) {
                        if (empty(trim((string) $teks_opsi))) {
                            continue;
                        }
                        CbtOpsiJawaban::create([
                            'cbt_bank_soal_id' => $soal->id,
                            'teks_opsi'        => HtmlSanitizer::clean($teks_opsi),
                            'is_benar'         => ($idx === $kunciIdx),
                        ]);
                    }
                }

                if (!empty(trim((string) ($pembahasan ?? '')))) {
                    CbtPembahasan::create([
                        'cbt_bank_soal_id' => $soal->id,
                        'teks_pembahasan'  => HtmlSanitizer::clean($pembahasan),
                    ]);
                }
            }
            DB::commit();
            return ['success' => true, 'message' => count($dataRows) . ' Soal berhasil diimport.'];
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Gagal mengimport soal: ' . $e->getMessage()];
        }
    }

    private function parseXmlFile(string $content): array
    {
        $dataRows = [];
        $dom = new DOMDocument();
        $dom->loadXML($content);
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('ss', 'urn:schemas-microsoft-com:office:spreadsheet');
        $xpath->registerNamespace('main', 'urn:schemas-microsoft-com:office:spreadsheet');

        $rows = $xpath->query('//ss:Row | //main:Row | //Row');

        foreach ($rows as $index => $row) {
            if ($index < 2) {
                continue;
            }

            $cells = $xpath->query('.//ss:Cell | .//main:Cell | .//Cell', $row);
            $rowData = array_fill(0, 12, '');
            $currentIdx = 0;

            foreach ($cells as $cell) {
                if ($cell->hasAttributeNS('urn:schemas-microsoft-com:office:spreadsheet', 'Index')) {
                    $currentIdx = (int) $cell->getAttributeNS('urn:schemas-microsoft-com:office:spreadsheet', 'Index') - 1;
                } elseif ($cell->hasAttribute('ss:Index')) {
                    $currentIdx = (int) $cell->getAttribute('ss:Index') - 1;
                }

                $dataNodes = $xpath->query('.//ss:Data | .//main:Data | .//Data', $cell);
                if ($dataNodes->length > 0) {
                    $rowData[$currentIdx] = $dataNodes->item(0)->nodeValue;
                }
                $currentIdx++;
            }

            if (!empty(trim((string) ($rowData[4] ?? '')))) {
                $dataRows[] = $rowData;
            }
        }
        return $dataRows;
    }

    private function parseCsvFile(string $path): array
    {
        $dataRows = [];
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return $dataRows;
        }

        $header = true;
        while (($row = fgetcsv($handle, 10000, ',')) !== false) {
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
