<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CBT\CbtUjian;
use App\Models\CBT\CbtBankSoal;
use App\Models\CBT\CbtMapel;
use App\Models\Akademik\KelompokBelajar;
use App\Models\User;
use App\Services\CbtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponse;

class GuruUjianController extends Controller
{
    use ApiResponse;

    public function __construct(protected CbtService $cbtService)
    {}

    public function index(Request $request)
    {
        $user = $request->user();

        if (strtolower($user->level) !== 'guru') {
            return $this->errorResponse('Akses ditolak.', 403);
        }

        // Ambil ujian yang dibuat oleh guru ini
        $query = CbtUjian::where('created_by', $user->id)
            ->withCount(['ujianSoals', 'pesertas'])
            ->with(['ujianSoals' => function ($q) {
                // FIX N+1: Eager load mapel sekaligus, bukan join di dalam loop transform()
                $q->with('bankSoal.mapel')->orderBy('urutan')->limit(1);
            }]);

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $ujians = $query->orderBy('created_at', 'desc')->paginate(20);

        // Map mapel dari soal pertama — pure PHP, no extra DB queries
        $ujians->getCollection()->transform(function ($ujian) {
            $firstSoal = $ujian->ujianSoals->first();
            $ujian->mapel = $firstSoal?->bankSoal?->mapel
                ? ['nama' => $firstSoal->bankSoal->mapel->nama]
                : null;
            // Hapus relasi besar sebelum serialize (hemat memori)
            $ujian->unsetRelation('ujianSoals');
            return $ujian;
        });

        return $this->successResponse([
            'data' => $ujians->items(),
            'current_page' => $ujians->currentPage(),
            'last_page' => $ujians->lastPage(),
            'total' => $ujians->total(),
        ], 'Daftar ujian berhasil dimuat');
    }

    public function formData(Request $request)
    {
        $user = $request->user();
        if (strtolower($user->level) !== 'guru') {
            return $this->errorResponse('Akses ditolak.', 403);
        }

        $kelompoks = KelompokBelajar::orderBy('nama_kelompok')->get();
        $siswas = User::where('level', 'siswa')->where('is_active', true)->orderBy('name')->get();

        return $this->successResponse([
            'kelompoks' => $kelompoks,
            'siswas' => $siswas,
        ], 'Data referensi berhasil dimuat');
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if (strtolower($user->level) !== 'guru') {
            return $this->errorResponse('Akses ditolak.', 403);
        }

        $request->validate([
            'judul'          => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'durasi'         => 'required|integer|min:5|max:300',
            'waktu_mulai'    => 'nullable|date',
            'waktu_selesai'  => 'nullable|date|after:waktu_mulai',
            'mode'           => 'required|in:latihan,resmi',
            'acak_soal'      => 'boolean',
            'acak_opsi'      => 'boolean',
            'tampilkan_hasil' => 'boolean',
            'limit_attempt'  => 'required|integer|min:0',
            'token'          => 'nullable|string|max:20',
        ]);

        $ujian = CbtUjian::create([
            'judul'           => $request->judul,
            'deskripsi'       => $request->deskripsi,
            'durasi'          => $request->durasi,
            'waktu_mulai'     => $request->waktu_mulai,
            'waktu_selesai'   => $request->waktu_selesai,
            'mode'            => $request->mode,
            'acak_soal'       => $request->boolean('acak_soal'),
            'acak_opsi'       => $request->boolean('acak_opsi'),
            'tampilkan_hasil' => $request->boolean('tampilkan_hasil', true),
            'limit_attempt'   => $request->limit_attempt,
            'token'           => $request->token,
            'created_by'      => $user->id,
        ]);

        return $this->successResponse($ujian, 'Ujian berhasil dibuat.', 201);
    }

    public function show(Request $request, string $id)
    {
        $user = $request->user();
        if (strtolower($user->level) !== 'guru') {
            return $this->errorResponse('Akses ditolak.', 403);
        }

        $ujian = CbtUjian::where('created_by', $user->id)
            ->withCount(['ujianSoals', 'pesertas'])
            ->with(['ujianSoals.bankSoal', 'assigns'])
            ->findOrFail($id);
            
        // Get available bank soals
        $soalTerpilihIds = $ujian->ujianSoals->pluck('cbt_bank_soal_id')->toArray();
        $bankSoals = CbtBankSoal::where('created_by', $user->id)
            ->whereNotIn('id', $soalTerpilihIds)
            ->with(['mapel', 'opsiJawabans'])
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->successResponse([
            'ujian' => $ujian,
            'available_soals' => $bankSoals
        ], 'Detail ujian berhasil dimuat');
    }

    public function destroy(Request $request, string $id)
    {
        $user = $request->user();
        if (strtolower($user->level) !== 'guru') {
            return $this->errorResponse('Akses ditolak.', 403);
        }

        $ujian = CbtUjian::where('created_by', $user->id)->findOrFail($id);

        if ($ujian->pesertas()->exists()) {
            return $this->errorResponse('Ujian tidak bisa dihapus karena sudah ada peserta yang mengerjakan.', 400);
        }

        $ujian->delete();
        return $this->successResponse(null, 'Ujian berhasil dihapus.');
    }

    public function tambahSoal(Request $request, string $id)
    {
        $user = $request->user();
        if (strtolower($user->level) !== 'guru') {
            return $this->errorResponse('Akses ditolak.', 403);
        }

        $ujian = CbtUjian::where('created_by', $user->id)->findOrFail($id);

        $request->validate([
            'soal_ids'   => 'required|array',
            'soal_ids.*' => 'exists:cbt_bank_soals,id',
        ]);

        $added = $this->cbtService->addSoals($ujian, $request->soal_ids);

        return $this->successResponse(null, $added . ' soal ditambahkan ke ujian.');
    }

    public function hapusSoal(Request $request, string $id, string $soalId)
    {
        $user = $request->user();
        if (strtolower($user->level) !== 'guru') {
            return $this->errorResponse('Akses ditolak.', 403);
        }

        $ujian = CbtUjian::where('created_by', $user->id)->findOrFail($id);
        $ujian->ujianSoals()->where('cbt_bank_soal_id', $soalId)->delete();

        return $this->successResponse(null, 'Soal dihapus dari ujian.');
    }

    public function setPeserta(Request $request, string $id)
    {
        $user = $request->user();
        if (strtolower($user->level) !== 'guru') {
            return $this->errorResponse('Akses ditolak.', 403);
        }

        $ujian = CbtUjian::where('created_by', $user->id)->findOrFail($id);

        // Hapus assign lama
        $ujian->assigns()->delete();

        $this->cbtService->setAssignees(
            $ujian,
            $request->input('kelompok_ids', []),
            $request->input('siswa_ids', [])
        );

        return $this->successResponse(null, 'Peserta ujian berhasil diatur.');
    }

    public function monitoring(Request $request, string $id)
    {
        $user = $request->user();
        if (strtolower($user->level) !== 'guru') {
            return $this->errorResponse('Akses ditolak.', 403);
        }

        $ujian = CbtUjian::where('created_by', $user->id)
            ->with(['assigns'])
            ->findOrFail($id);

        $assignedKelasIds = $ujian->assigns->where('tipe_assign', 'kelas')->pluck('assign_id');
        $assignedUserIds = $ujian->assigns->where('tipe_assign', 'user')->pluck('assign_id');

        // Jika tidak ada assign sama sekali
        if ($assignedKelasIds->isEmpty() && $assignedUserIds->isEmpty()) {
            return $this->successResponse([
                'selesai' => [],
                'mengerjakan' => [],
                'belum' => [],
            ], 'Data monitoring berhasil dimuat');
        }

        $targetUsers = User::where('level', 'siswa')
            ->where(function ($query) use ($assignedKelasIds, $assignedUserIds) {
                if ($assignedKelasIds->isNotEmpty() && $assignedUserIds->isNotEmpty()) {
                    $query->whereHas('pesertaDidik', function ($q) use ($assignedKelasIds) {
                        $q->whereIn('kelompok_belajar_id', $assignedKelasIds);
                    })->orWhereIn('id', $assignedUserIds);
                } elseif ($assignedKelasIds->isNotEmpty()) {
                    $query->whereHas('pesertaDidik', function ($q) use ($assignedKelasIds) {
                        $q->whereIn('kelompok_belajar_id', $assignedKelasIds);
                    });
                } elseif ($assignedUserIds->isNotEmpty()) {
                    $query->whereIn('id', $assignedUserIds);
                }
            })
            ->with('pesertaDidik.kelompokBelajar')
            ->get();

        $pesertas = \App\Models\CBT\CbtPeserta::where('cbt_ujian_id', $ujian->id)->get()->keyBy('user_id');

        $result = [];
        foreach ($targetUsers as $siswa) {
            $peserta = $pesertas->get($siswa->id);
            $status = 'belum'; 
            $skor = null;

            $peserta_id = null;

            if ($peserta) {
                $status = $peserta->status; 
                $skor = $peserta->skor;
                $peserta_id = $peserta->id;
            }

            $result[] = [
                'user_id' => $siswa->id,
                'peserta_id' => $peserta_id,
                'name' => $siswa->name,
                'kelas' => $siswa->pesertaDidik->kelompokBelajar->nama_kelompok ?? '-',
                'status' => $status,
                'skor' => $skor,
            ];
        }

        $grouped = [
            'selesai' => array_values(array_filter($result, fn($r) => in_array($r['status'], ['selesai', 'timeout']))),
            'mengerjakan' => array_values(array_filter($result, fn($r) => $r['status'] === 'mengerjakan')),
            'belum' => array_values(array_filter($result, fn($r) => $r['status'] === 'belum')),
        ];

        return $this->successResponse($grouped, 'Data monitoring berhasil dimuat');
    }

    public function koreksi(Request $request, string $id, string $peserta_id)
    {
        $user = $request->user();
        if (strtolower($user->level) !== 'guru') {
            return $this->errorResponse('Akses ditolak.', 403);
        }

        $ujian = CbtUjian::where('created_by', $user->id)->findOrFail($id);
        $sesi = \App\Models\CBT\CbtPeserta::with(['user', 'jawabans.bankSoal.opsiJawabans', 'jawabans.opsiJawaban'])
            ->where('cbt_ujian_id', $ujian->id)
            ->findOrFail($peserta_id);

        $ujianSoalsMap = \App\Models\CBT\CbtUjianSoal::where('cbt_ujian_id', $ujian->id)
            ->get()
            ->keyBy('cbt_bank_soal_id');

        $detailJawabans = $sesi->jawabans->map(function($jawaban) use ($ujianSoalsMap) {
            $bobot = $ujianSoalsMap->get($jawaban->cbt_bank_soal_id)?->bobot ?? 1;
            $soal = $jawaban->bankSoal;
            
            $jawaban_siswa = null;
            $kunci_jawaban = null;

            if ($soal->tipe_soal === 'pilihan_ganda') {
                $jawaban_siswa = $jawaban->opsiJawaban?->teks_opsi;
                $kunci_jawaban = $soal->opsiJawabans->where('is_jawaban', true)->first()?->teks_opsi;
            } else {
                $jawaban_siswa = $jawaban->jawaban_text;
                $kunci_jawaban = $soal->jawaban_benar_text; // asumsi ada text jawaban benar untuk panduan essay
            }

            return [
                'id' => $jawaban->id,
                'cbt_bank_soal_id' => $soal->id,
                'tipe_soal' => $soal->tipe_soal,
                'pertanyaan' => $soal->pertanyaan,
                'jawaban_siswa' => $jawaban_siswa,
                'kunci_jawaban' => $kunci_jawaban,
                'is_benar' => $jawaban->is_benar,
                'skor' => $jawaban->skor,
                'bobot' => $bobot,
            ];
        });

        return $this->successResponse([
            'ujian' => [
                'id' => $ujian->id,
                'judul' => $ujian->judul,
            ],
            'peserta' => [
                'id' => $sesi->id,
                'nama' => $sesi->user->name,
                'status' => $sesi->status,
                'skor_akhir' => $sesi->skor,
            ],
            'jawabans' => $detailJawabans
        ], 'Data koreksi berhasil dimuat');
    }

    public function simpanKoreksi(Request $request, string $id, string $peserta_id)
    {
        $user = $request->user();
        if (strtolower($user->level) !== 'guru') {
            return $this->errorResponse('Akses ditolak.', 403);
        }

        $ujian = CbtUjian::where('created_by', $user->id)->findOrFail($id);
        $sesi = \App\Models\CBT\CbtPeserta::where('cbt_ujian_id', $ujian->id)->findOrFail($peserta_id);

        $request->validate([
            'skor' => 'required|array',
            'skor.*' => 'numeric|min:0'
        ]);

        $skorData = $request->input('skor', []);
        
        $ujianSoalsMap = \App\Models\CBT\CbtUjianSoal::where('cbt_ujian_id', $ujian->id)
            ->get()
            ->keyBy('cbt_bank_soal_id');

        foreach ($skorData as $jawaban_id => $inputSkor) {
            $jawaban = \App\Models\CBT\CbtPesertaJawaban::where('cbt_peserta_id', $sesi->id)->find($jawaban_id);
            if ($jawaban && $jawaban->bankSoal->tipe_soal === 'essay') {
                $bobotMaksimal = $ujianSoalsMap->get($jawaban->cbt_bank_soal_id)?->bobot ?? 1;
                
                // Validasi skor tidak melebihi bobot
                $skorDiinput = min(max(0, (float) $inputSkor), $bobotMaksimal);
                
                $jawaban->skor = $skorDiinput;
                $jawaban->is_benar = ($skorDiinput > 0);
                $jawaban->save();
            }
        }

        // Kalkulasi Ulang Skor Akhir
        app(\App\Services\CbtService::class)->recalculateSkor($sesi);
        $sesi->refresh();

        return $this->successResponse([
            'skor_baru' => $sesi->skor
        ], 'Nilai koreksi berhasil disimpan.');
    }
}
