<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\CbtUjian;
use App\Models\CbtUjianSoal;
use App\Models\CbtUjianAssign;
use App\Models\CbtBankSoal;
use App\Models\CbtMapel;
use App\Models\KelompokBelajar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UjianController extends Controller
{
    /**
     * Daftar ujian milik guru.
     */
    public function index(Request $request)
    {
        $query = CbtUjian::where('created_by', Auth::id())
            ->withCount(['ujianSoals', 'pesertas']);

        // Filter status
        if ($request->filled('status')) {
            // Mapping status visual ke logika query
            match ($request->status) {
                'aktif' => $query->aktif(),
                'mendatang' => $query->mendatang(),
                'selesai' => $query->where('waktu_selesai', '<', now()),
                default => null,
            };
        }

        $ujians = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('guru.ujian.index', compact('ujians'));
    }

    /**
     * Form buat ujian baru (Wizard Step 1: Info Dasar).
     */
    public function create()
    {
        $mapels = CbtMapel::orderBy('nama')->get();

        return view('guru.ujian.create', compact('mapels'));
    }

    /**
     * Simpan ujian baru (draft).
     */
    public function store(Request $request)
    {
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
            'token'           => $request->token,
            'created_by'      => Auth::id(),
        ]);

        return redirect()->route('guru.ujian.soal', $ujian->id)
            ->with('success', 'Ujian berhasil dibuat. Sekarang pilih soal.');
    }

    /**
     * Detail ujian.
     */
    public function show(string $id)
    {
        $ujian = CbtUjian::where('created_by', Auth::id())
            ->withCount(['ujianSoals', 'pesertas'])
            ->with(['ujianSoals.bankSoal.opsiJawabans'])
            ->findOrFail($id);

        return view('guru.ujian.show', compact('ujian'));
    }

    /**
     * Form edit ujian.
     */
    public function edit(string $id)
    {
        $ujian = CbtUjian::where('created_by', Auth::id())->findOrFail($id);
        $mapels = CbtMapel::orderBy('nama')->get();

        return view('guru.ujian.edit', compact('ujian', 'mapels'));
    }

    /**
     * Update ujian.
     */
    public function update(Request $request, string $id)
    {
        $ujian = CbtUjian::where('created_by', Auth::id())->findOrFail($id);

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
            'token'          => 'nullable|string|max:20',
        ]);

        $ujian->update([
            'judul'           => $request->judul,
            'deskripsi'       => $request->deskripsi,
            'durasi'          => $request->durasi,
            'waktu_mulai'     => $request->waktu_mulai,
            'waktu_selesai'   => $request->waktu_selesai,
            'mode'            => $request->mode,
            'acak_soal'       => $request->boolean('acak_soal'),
            'acak_opsi'       => $request->boolean('acak_opsi'),
            'tampilkan_hasil' => $request->boolean('tampilkan_hasil', true),
            'token'           => $request->token,
        ]);

        return redirect()->route('guru.ujian.show', $ujian->id)
            ->with('success', 'Ujian berhasil diperbarui.');
    }

    /**
     * Hapus ujian.
     */
    public function destroy(string $id)
    {
        $ujian = CbtUjian::where('created_by', Auth::id())->findOrFail($id);

        if ($ujian->pesertas()->exists()) {
            return back()->with('error', 'Ujian tidak bisa dihapus karena sudah ada peserta yang mengerjakan.');
        }

        $ujian->delete();
        return redirect()->route('guru.ujian.index')->with('success', 'Ujian berhasil dihapus.');
    }

    // ═══════════════════════════════════════════════════════
    // STEP 2: Kelola Soal di Ujian
    // ═══════════════════════════════════════════════════════

    /**
     * Halaman dual-panel: Bank Soal (kiri) ↔ Soal Ujian (kanan).
     */
    public function kelolaSoal(string $id)
    {
        $ujian = CbtUjian::where('created_by', Auth::id())
            ->with(['ujianSoals.bankSoal.opsiJawabans', 'ujianSoals.bankSoal.mapel'])
            ->findOrFail($id);

        // Soal yang sudah dipilih
        $soalTerpilihIds = $ujian->ujianSoals->pluck('cbt_bank_soal_id')->toArray();

        // Bank soal milik guru (yang belum dipilih)
        $bankSoals = CbtBankSoal::where('created_by', Auth::id())
            ->whereNotIn('id', $soalTerpilihIds)
            ->with(['mapel', 'opsiJawabans'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $mapels = CbtMapel::orderBy('nama')->get();

        return view('guru.ujian.pilih-soal', compact('ujian', 'bankSoals', 'mapels'));
    }

    /**
     * Tambah soal ke ujian.
     */
    public function tambahSoal(Request $request, string $id)
    {
        $ujian = CbtUjian::where('created_by', Auth::id())->findOrFail($id);

        $request->validate([
            'soal_ids'   => 'required|array',
            'soal_ids.*' => 'exists:cbt_bank_soals,id',
        ]);

        $lastUrutan = $ujian->ujianSoals()->max('urutan') ?? 0;

        foreach ($request->soal_ids as $soalId) {
            // Cek duplikat
            if (!$ujian->ujianSoals()->where('cbt_bank_soal_id', $soalId)->exists()) {
                $lastUrutan++;
                CbtUjianSoal::create([
                    'cbt_ujian_id'    => $ujian->id,
                    'cbt_bank_soal_id' => $soalId,
                    'bobot'           => 1,
                    'urutan'          => $lastUrutan,
                ]);
            }
        }

        return back()->with('success', count($request->soal_ids) . ' soal ditambahkan ke ujian.');
    }

    /**
     * Hapus soal dari ujian.
     */
    public function hapusSoal(string $id, string $soalId)
    {
        $ujian = CbtUjian::where('created_by', Auth::id())->findOrFail($id);
        $ujian->ujianSoals()->where('cbt_bank_soal_id', $soalId)->delete();

        return back()->with('success', 'Soal dihapus dari ujian.');
    }

    /**
     * Reorder soal (AJAX).
     */
    public function reorderSoal(Request $request, string $id)
    {
        $ujian = CbtUjian::where('created_by', Auth::id())->findOrFail($id);

        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer',
        ]);

        foreach ($request->order as $urutan => $ujianSoalId) {
            CbtUjianSoal::where('id', $ujianSoalId)
                ->where('cbt_ujian_id', $ujian->id)
                ->update(['urutan' => $urutan + 1]);
        }

        return response()->json(['status' => 'ok']);
    }

    // ═══════════════════════════════════════════════════════
    // STEP 3: Kelola Peserta
    // ═══════════════════════════════════════════════════════

    /**
     * Halaman kelola peserta ujian.
     */
    public function kelolaPeserta(string $id)
    {
        $ujian = CbtUjian::where('created_by', Auth::id())
            ->with('assigns')
            ->findOrFail($id);

        $kelompoks = KelompokBelajar::orderBy('nama_kelompok')->get();
        $siswaList = User::where('level', 'siswa')->where('is_active', true)->orderBy('name')->get();

        // Peserta yang sudah di-assign
        $assignedKelompokIds = $ujian->assigns()->where('tipe_assign', 'kelas')->pluck('assign_id')->toArray();
        $assignedUserIds = $ujian->assigns()->where('tipe_assign', 'user')->pluck('assign_id')->toArray();

        return view('guru.ujian.peserta', compact('ujian', 'kelompoks', 'siswaList', 'assignedKelompokIds', 'assignedUserIds'));
    }

    /**
     * Set peserta ujian.
     */
    public function setPeserta(Request $request, string $id)
    {
        $ujian = CbtUjian::where('created_by', Auth::id())->findOrFail($id);

        // Hapus assign lama
        $ujian->assigns()->delete();

        // Assign per kelompok belajar
        if ($request->filled('kelompok_ids')) {
            foreach ($request->kelompok_ids as $kelompokId) {
                CbtUjianAssign::create([
                    'cbt_ujian_id' => $ujian->id,
                    'tipe_assign'  => 'kelas',
                    'assign_id'    => $kelompokId,
                ]);
            }
        }

        // Assign per individu
        if ($request->filled('siswa_ids')) {
            foreach ($request->siswa_ids as $siswaId) {
                CbtUjianAssign::create([
                    'cbt_ujian_id' => $ujian->id,
                    'tipe_assign'  => 'user',
                    'assign_id'    => $siswaId,
                ]);
            }
        }

        return redirect()->route('guru.ujian.show', $ujian->id)
            ->with('success', 'Peserta ujian berhasil diatur.');
    }

    // ═══════════════════════════════════════════════════════
    // PUBLISH & MONITORING
    // ═══════════════════════════════════════════════════════

    /**
     * Publish ujian (ubah dari draft → available).
     */
    public function publish(string $id)
    {
        $ujian = CbtUjian::where('created_by', Auth::id())->findOrFail($id);

        // Validasi sebelum publish
        if ($ujian->ujianSoals()->count() === 0) {
            return back()->with('error', 'Ujian harus memiliki minimal 1 soal sebelum di-publish.');
        }

        if (!$ujian->waktu_mulai || !$ujian->waktu_selesai) {
            return back()->with('error', 'Waktu mulai dan selesai harus diisi sebelum publish.');
        }

        // Cukup set waktu saja, logika aktif ditentukan dari waktu_mulai <= NOW <= waktu_selesai
        return redirect()->route('guru.ujian.show', $ujian->id)
            ->with('success', 'Ujian berhasil di-publish dan siap diakses siswa.');
    }

    /**
     * Arsipkan ujian.
     */
    public function arsipkan(string $id)
    {
        $ujian = CbtUjian::where('created_by', Auth::id())->findOrFail($id);
        $ujian->delete(); // soft delete = arsip

        return redirect()->route('guru.ujian.index')
            ->with('success', 'Ujian berhasil diarsipkan.');
    }

    /**
     * Monitoring live ujian.
     */
    public function monitoring(string $id)
    {
        $ujian = CbtUjian::where('created_by', Auth::id())
            ->withCount('ujianSoals')
            ->findOrFail($id);

        $pesertas = $ujian->pesertas()
            ->with('user')
            ->withCount('jawabans')
            ->orderBy('waktu_mulai', 'desc')
            ->get();

        return view('guru.ujian.monitoring', compact('ujian', 'pesertas'));
    }
}
