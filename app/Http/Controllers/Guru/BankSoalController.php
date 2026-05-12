<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\CbtBankSoal;
use App\Models\CbtOpsiJawaban;
use App\Models\CbtPembahasan;
use App\Models\CbtMapel;
use App\Models\CbtBab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BankSoalController extends Controller
{
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
        $mapels = CbtMapel::orderBy('nama')->get();
        $babs = CbtBab::orderBy('nama')->get();

        return view('guru.bank-soal.create', compact('mapels', 'babs'));
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
            'media'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pembahasan'        => 'nullable|string',
            // Opsi PG
            'opsi'              => 'required_if:tipe_soal,pg|array',
            'opsi.*'            => 'required_if:tipe_soal,pg|string',
            'kunci'             => 'required_if:tipe_soal,pg|integer',
        ]);

        DB::beginTransaction();
        try {
            // Upload media jika ada
            $mediaPath = null;
            $mediaType = null;
            if ($request->hasFile('media')) {
                $mediaPath = $request->file('media')->store('bank-soal/media', 'public');
                $mediaType = 'image';
            }

            // Simpan soal
            $soal = CbtBankSoal::create([
                'pertanyaan'        => $request->pertanyaan,
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
                        'teks_opsi'        => $teks,
                        'is_benar'         => $idx == $request->kunci,
                    ]);
                }
            }

            // Simpan pembahasan
            if ($request->filled('pembahasan')) {
                CbtPembahasan::create([
                    'cbt_bank_soal_id'  => $soal->id,
                    'teks_pembahasan'   => $request->pembahasan,
                ]);
            }

            DB::commit();
            return redirect()->route('guru.bank-soal.index')
                ->with('success', 'Soal berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan soal: ' . $e->getMessage());
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

        $mapels = CbtMapel::orderBy('nama')->get();
        $babs = CbtBab::when($soal->cbt_mapel_id, fn($q) => $q->where('cbt_mapel_id', $soal->cbt_mapel_id))
            ->orderBy('nama')->get();

        return view('guru.bank-soal.edit', compact('soal', 'mapels', 'babs'));
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
            'media'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pembahasan'        => 'nullable|string',
            'opsi'              => 'required_if:tipe_soal,pg|array',
            'opsi.*'            => 'required_if:tipe_soal,pg|string',
            'kunci'             => 'required_if:tipe_soal,pg|integer',
        ]);

        DB::beginTransaction();
        try {
            // Upload media jika ada file baru
            $mediaData = [];
            if ($request->hasFile('media')) {
                // Hapus media lama
                if ($soal->file_media) {
                    Storage::disk('public')->delete($soal->file_media);
                }
                $mediaData['file_media'] = $request->file('media')->store('bank-soal/media', 'public');
                $mediaData['tipe_media'] = 'image';
            }

            // Update soal
            $soal->update(array_merge([
                'pertanyaan'        => $request->pertanyaan,
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
                        'teks_opsi'        => $teks,
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
                    ['teks_pembahasan' => $request->pembahasan]
                );
            } else {
                $soal->pembahasan()->delete();
            }

            DB::commit();
            return redirect()->route('guru.bank-soal.index')
                ->with('success', 'Soal berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui soal: ' . $e->getMessage());
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
    public function template()
    {
        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_soal.csv"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];

        $columns = ['Mapel_ID', 'Bab_ID', 'Tipe_Soal(pg/essay)', 'Kesulitan(easy/medium/hard)', 'Pertanyaan', 'Opsi_A', 'Opsi_B', 'Opsi_C', 'Opsi_D', 'Opsi_E', 'Kunci_Jawaban(A/B/C/D/E)', 'Pembahasan'];

        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Contoh data
            fputcsv($file, ['1', '', 'pg', 'medium', 'Berapa 1+1?', '1', '2', '3', '4', '5', 'B', 'Karena 1+1=2']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import soal dari file CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), "r");
        $header = true;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 10000, ",")) !== FALSE) {
                if ($header) {
                    $header = false;
                    continue;
                }

                // Pastikan baris memiliki cukup kolom
                if (count($row) < 5) continue;

                $mapel_id = $row[0];
                $bab_id = $row[1] ?: null;
                $tipe_soal = strtolower($row[2]);
                $kesulitan = strtolower($row[3]);
                $pertanyaan = $row[4];
                $opsi = [$row[5] ?? '', $row[6] ?? '', $row[7] ?? '', $row[8] ?? '', $row[9] ?? ''];
                $kunci = strtoupper($row[10] ?? 'A');
                $pembahasan = $row[11] ?? null;

                if (empty(trim($pertanyaan))) continue;

                $soal = CbtBankSoal::create([
                    'cbt_mapel_id' => $mapel_id ?: null,
                    'cbt_bab_id' => $bab_id ?: null,
                    'tipe_soal' => in_array($tipe_soal, ['pg', 'essay']) ? $tipe_soal : 'pg',
                    'tingkat_kesulitan' => in_array($kesulitan, ['easy', 'medium', 'hard']) ? $kesulitan : 'medium',
                    'pertanyaan' => $pertanyaan,
                    'status' => 'published',
                    'created_by' => Auth::id()
                ]);

                if ($soal->tipe_soal === 'pg') {
                    $kunciIdx = array_search($kunci, ['A','B','C','D','E']);
                    if ($kunciIdx === false) $kunciIdx = 0;

                    foreach ($opsi as $idx => $teks_opsi) {
                        if (empty(trim($teks_opsi))) continue;
                        CbtOpsiJawaban::create([
                            'cbt_bank_soal_id' => $soal->id,
                            'teks_opsi' => $teks_opsi,
                            'is_benar' => ($idx === $kunciIdx)
                        ]);
                    }
                }

                if (!empty(trim($pembahasan))) {
                    CbtPembahasan::create([
                        'cbt_bank_soal_id' => $soal->id,
                        'teks_pembahasan' => $pembahasan
                    ]);
                }
            }
            fclose($handle);
            DB::commit();

            return redirect()->route('guru.bank-soal.index')->with('success', 'Soal berhasil diimport.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('guru.bank-soal.index')->with('error', 'Gagal mengimport soal: ' . $e->getMessage());
        }
    }
}
