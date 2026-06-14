<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CBT\CbtBankSoal;
use App\Models\CBT\CbtOpsiJawaban;
use App\Models\CBT\CbtMapel;
use App\Models\CBT\CbtBab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponse;

class GuruBankSoalController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $user = $request->user();

        if (strtolower($user->level) !== 'guru') {
            return $this->errorResponse('Akses ditolak.', 403);
        }

        $query = CbtBankSoal::with(['mapel', 'bab'])->byGuru($user->id);

        if ($request->filled('search')) {
            $query->where('pertanyaan', 'like', '%' . $request->search . '%');
        }

        $soals = $query->orderBy('created_at', 'desc')->paginate(20);

        return $this->successResponse([
            'data' => $soals->items(),
            'current_page' => $soals->currentPage(),
            'last_page' => $soals->lastPage(),
            'total' => $soals->total(),
        ], 'Daftar bank soal berhasil dimuat');
    }

    public function formData(Request $request)
    {
        $user = $request->user();

        // Ambil data Mata Pelajaran dan Bab untuk dropdown di aplikasi
        $mapels = CbtMapel::orderBy('nama')->get(['id', 'nama']);
        $babs = CbtBab::orderBy('nama')->get(['id', 'nama', 'cbt_mapel_id']);

        return $this->successResponse([
            'mapels' => $mapels,
            'babs' => $babs,
        ], 'Data form berhasil dimuat');
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (strtolower($user->level) !== 'guru') {
            return $this->errorResponse('Akses ditolak.', 403);
        }

        $request->validate([
            'pertanyaan'        => 'required|string',
            'tipe_soal'         => 'required|in:pg,essay',
            'tingkat_kesulitan' => 'required|in:easy,medium,hard',
            'cbt_mapel_id'      => 'nullable|exists:cbt_mapels,id',
            'cbt_bab_id'        => 'nullable|exists:cbt_babs,id',
            'opsi'              => 'required_if:tipe_soal,pg|array',
            'opsi.*'            => 'required_if:tipe_soal,pg|string',
            'kunci'             => 'required_if:tipe_soal,pg|integer',
        ]);

        DB::beginTransaction();
        try {
            $soal = CbtBankSoal::create([
                'pertanyaan'        => strip_tags($request->pertanyaan), // Sederhana untuk mobile
                'tipe_soal'         => $request->tipe_soal,
                'tingkat_kesulitan' => $request->tingkat_kesulitan,
                'cbt_mapel_id'      => $request->cbt_mapel_id,
                'cbt_bab_id'        => $request->cbt_bab_id,
                'status'            => 'published',
                'created_by'        => $user->id,
            ]);

            if ($request->tipe_soal === 'pg' && $request->has('opsi')) {
                foreach ($request->opsi as $idx => $teks) {
                    if (empty(trim($teks))) continue;
                    CbtOpsiJawaban::create([
                        'cbt_bank_soal_id' => $soal->id,
                        'teks_opsi'        => strip_tags($teks),
                        'is_benar'         => $idx == $request->kunci,
                    ]);
                }
            }

            DB::commit();
            return $this->successResponse($soal, 'Soal berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Terjadi kesalahan saat menyimpan soal.', 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        if (strtolower($user->level) !== 'guru') {
            return $this->errorResponse('Akses ditolak.', 403);
        }

        $soal = CbtBankSoal::byGuru($user->id)->find($id);

        if (!$soal) {
            return $this->errorResponse('Soal tidak ditemukan.', 404);
        }

        $soal->delete();

        return $this->successResponse(null, 'Soal berhasil dihapus.');
    }
}
