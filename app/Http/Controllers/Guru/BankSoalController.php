<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\CBT\CbtBab;
use App\Models\CBT\CbtBankSoal;
use App\Models\CBT\CbtMapel;
use App\Services\BankSoalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BankSoalController extends Controller
{
    public function __construct(
        protected BankSoalService $bankSoalService
    ) {}

    /**
     * Daftar semua soal milik guru (dengan filter & search).
     */
    public function index(Request $request): View
    {
        $guruId = Auth::id();
        $soals  = $this->bankSoalService->getFilteredSoal($guruId, $request);
        $stats  = $this->bankSoalService->getGuruStats($guruId);

        $mapels = CbtMapel::orderBy('nama')->get();
        $babs   = CbtBab::when($request->mapel, fn($q) => $q->where('cbt_mapel_id', $request->mapel))
            ->orderBy('nama')->get();

        return view('guru.bank-soal.index', compact('soals', 'mapels', 'babs', 'stats'));
    }

    /**
     * Form buat soal baru.
     */
    public function create(): View
    {
        $formData = $this->bankSoalService->getFormDataForGuru(Auth::user());

        return view('guru.bank-soal.create', $formData);
    }

    /**
     * Simpan soal baru + opsi jawaban + pembahasan.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->tipe_soal === 'benar_salah') {
            $request->merge(['tipe_soal' => 'pg']);
        }

        $validated = $request->validate([
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
            'media.image'         => 'File yang diunggah harus berupa gambar.',
            'media.mimes'         => 'Format gambar hanya diperbolehkan: JPG, JPEG, PNG, WEBP.',
            'media.max'           => 'Ukuran gambar maksimal adalah 5MB.',
            'pertanyaan.required' => 'Teks pertanyaan wajib diisi.',
            'tipe_soal.required'  => 'Tipe soal harus dipilih.',
        ]);

        try {
            $this->bankSoalService->createSoal(Auth::user(), $validated, $request->file('media'));

            return redirect()->route('guru.bank-soal.index')
                ->with('success', 'Soal berhasil ditambahkan.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan soal: ' . $e->getMessage());
        }
    }

    /**
     * Detail soal (preview).
     */
    public function show(string $id): View
    {
        $soal = CbtBankSoal::with(['mapel', 'bab', 'opsiJawabans', 'pembahasan', 'creator'])
            ->byGuru(Auth::id())
            ->findOrFail($id);

        return view('guru.bank-soal.show', compact('soal'));
    }

    /**
     * Form edit soal.
     */
    public function edit(string $id): View
    {
        $soal = CbtBankSoal::with(['opsiJawabans', 'pembahasan'])
            ->byGuru(Auth::id())
            ->findOrFail($id);

        $formData = $this->bankSoalService->getFormDataForGuru(Auth::user(), $soal);

        return view('guru.bank-soal.edit', array_merge(['soal' => $soal], $formData));
    }

    /**
     * Update soal.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $soal = CbtBankSoal::byGuru(Auth::id())->findOrFail($id);

        if ($request->tipe_soal === 'benar_salah') {
            $request->merge(['tipe_soal' => 'pg']);
        }

        $validated = $request->validate([
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
            'pertanyaan.required' => 'Teks pertanyaan wajib diisi.',
            'tipe_soal.required'  => 'Tipe soal harus dipilih.',
        ]);

        try {
            $this->bankSoalService->updateSoal($soal, $validated, $request->file('media'));

            return redirect()->route('guru.bank-soal.index')
                ->with('success', 'Soal berhasil diperbarui.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui soal: ' . $e->getMessage());
        }
    }

    /**
     * Hapus soal.
     */
    public function destroy(string $id): RedirectResponse
    {
        $soal = CbtBankSoal::byGuru(Auth::id())->findOrFail($id);
        $this->bankSoalService->deleteSoal($soal);

        return redirect()->route('guru.bank-soal.index')
            ->with('success', 'Soal berhasil dihapus.');
    }

    /**
     * JSON: list topik/bab berdasarkan mapel.
     */
    public function getBabByMapel(Request $request): JsonResponse
    {
        $babs = CbtBab::where('cbt_mapel_id', $request->mapel_id)
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return response()->json($babs);
    }

    /**
     * Kelola mapel (AJAX store).
     */
    public function storeMapel(Request $request): JsonResponse
    {
        $request->validate(['nama' => 'required|string|max:255|unique:cbt_mapels,nama']);
        $mapel = CbtMapel::create(['nama' => $request->nama]);

        return response()->json($mapel);
    }

    /**
     * Kelola bab/topik (AJAX store).
     */
    public function storeBab(Request $request): JsonResponse
    {
        $request->validate([
            'nama'         => 'required|string|max:255',
            'cbt_mapel_id' => 'required|exists:cbt_mapels,id',
        ]);

        $bab = CbtBab::create([
            'nama'         => $request->nama,
            'cbt_mapel_id' => $request->cbt_mapel_id,
        ]);

        return response()->json($bab);
    }

    /**
     * Download template Excel XML untuk import soal.
     */
    public function template(): Response
    {
        $filename = 'Template_Import_Soal_Genius.xls';
        $xml = $this->bankSoalService->buildXmlTemplate();

        return response($xml, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }

    /**
     * Import soal dari file CSV atau XML Excel.
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xls,xml,txt|max:5120',
        ], [
            'file.required' => 'Silakan pilih file untuk diimport.',
            'file.mimes'    => 'Format file tidak didukung. Gunakan template CSV atau Excel (XLS) yang tersedia.',
            'file.max'      => 'Ukuran file maksimal adalah 5MB.',
        ]);

        $result = $this->bankSoalService->importSoal($request->file('file'), Auth::id());
        $statusKey = $result['success'] ? 'success' : 'error';

        return redirect()->route('guru.bank-soal.index')->with($statusKey, $result['message']);
    }
}
