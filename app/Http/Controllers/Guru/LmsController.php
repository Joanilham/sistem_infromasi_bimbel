<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Akademik\KelompokBelajar;
use App\Models\Akademik\LmsContent;
use App\Models\Akademik\LmsSubmission;
use App\Models\Akademik\PesertaDidik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class LmsController extends Controller
{
    /**
     * Tampilkan daftar arsip materi & tugas guru dengan pencarian, filter, dan paginasi.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $guruId = $user->id;
        $guruMapels = $user->daftar_mapel;

        $selectedSubject = $request->query('subject');
        if ($selectedSubject && !$user->teachesSubject($selectedSubject)) {
            $selectedSubject = null;
        }

        $query = LmsContent::with([
            'guru',
            'kelompokBelajar' => function ($q) {
                $q->withCount(['pesertaDidiks as total_siswa' => fn($sq) => $sq->aktif()]);
            },
        ])
        ->withCount([
            'submissions',
            'completions as readers_count' => function ($q) {
                $q->where(function ($sq) {
                    $sq->whereNotNull('read_at')
                       ->orWhere(fn($ssq) => $ssq->whereNotNull('completed_at')->whereNull('downloaded_at'));
                });
            },
            'completions as downloaders_count' => function ($q) {
                $q->whereNotNull('downloaded_at');
            },
            'completions',
        ])
        ->where('guru_id', $guruId);

        if ($selectedSubject) {
            $query->where('subject', $selectedSubject);
        }

        // Filter konteks kantor dan periode (jika session tersedia)
        $kantorId = session('kantor_id');
        $periodeId = session('periode_id');
        if ($kantorId && $kantorId !== 'all') {
            $query->where(function ($q) use ($kantorId) {
                $q->where('kantor_id', $kantorId)
                  ->orWhereHas('kelompokBelajar', fn($kb) => $kb->where('kantor_id', $kantorId));
            });
        }
        if ($periodeId) {
            $query->where(function ($q) use ($periodeId) {
                $q->where('periode_id', $periodeId)
                  ->orWhereHas('kelompokBelajar', fn($kb) => $kb->where('periode_id', $periodeId));
            });
        }

        // Pencarian judul / deskripsi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter tipe (materi / tugas)
        if ($request->filled('type') && in_array($request->type, ['materi', 'tugas'])) {
            $query->where('type', $request->type);
        }

        // Filter kelompok belajar
        if ($request->filled('kelompok_belajar_id')) {
            $query->where('kelompok_belajar_id', $request->kelompok_belajar_id);
        }

        $contents = $query->latest()->paginate(12)->withQueryString();

        // Kelompok belajar yang dapat diakses oleh guru untuk mata pelajarannya
        $kelompokBelajars = $this->getKelompokBelajarUntukGuru($user);

        // Rekap ringkasan total materi & tugas milik guru ini
        $totalMateri = LmsContent::where('guru_id', $guruId)
            ->when($selectedSubject, fn($q) => $q->where('subject', $selectedSubject))
            ->materi()
            ->count();

        $totalTugas = LmsContent::where('guru_id', $guruId)
            ->when($selectedSubject, fn($q) => $q->where('subject', $selectedSubject))
            ->tugas()
            ->count();

        return view('guru.lms.index', compact(
            'contents',
            'kelompokBelajars',
            'guruMapels',
            'selectedSubject',
            'totalMateri',
            'totalTugas'
        ));
    }

    /**
     * Form buat konten LMS baru.
     */
    public function create(): View
    {
        $user = Auth::user();
        $guruMapels = $user->daftar_mapel;
        $kelompokBelajars = $this->getKelompokBelajarUntukGuru($user);

        return view('guru.lms.create', compact('kelompokBelajars', 'guruMapels'));
    }

    /**
     * Simpan konten LMS baru ke database & upload file.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type'                => 'required|in:materi,tugas',
            'title'               => 'required|string|max:255',
            'subject'             => 'required|string|max:255',
            'description'         => 'nullable|string|max:10000',
            'kelompok_belajar_id' => 'required|integer|exists:kelompok_belajars,id',
            'file'                => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png|max:15360',
            'due_at'              => 'nullable|date',
        ]);

        $user = Auth::user();
        abort_unless($user->teachesSubject($validated['subject']), 403, "Anda tidak mengampu mata pelajaran {$validated['subject']}.");

        $kelompokBelajar = KelompokBelajar::findOrFail($validated['kelompok_belajar_id']);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $storedPath = $file->store('lms/materials', 'public');
            if ($storedPath) {
                $validated['file_path'] = $storedPath;
                $validated['file_name'] = $file->getClientOriginalName();
            }
        }

        $subject = $validated['subject'];

        $validated['guru_id']    = $user->id;
        $validated['kantor_id']  = $kelompokBelajar->kantor_id ?: (session('kantor_id') ?? $user->kantor_id ?? 1);
        $validated['periode_id'] = $kelompokBelajar->periode_id ?: (session('periode_id') ?? $user->periode_id ?? 1);
        $validated['subject']    = $subject;
        unset($validated['file']);

        LmsContent::create($validated);

        $label = $validated['type'] === 'tugas' ? 'Tugas' : 'Materi';
        return redirect()->route('guru.lms.index')->with('success', "{$label} {$subject} berhasil dipublikasikan.");
    }

    /**
     * Form edit konten LMS.
     */
    public function edit(LmsContent $lms): View
    {
        $this->authorizeContentOwner($lms);

        $user = Auth::user();
        $guruMapels = $user->daftar_mapel;
        $kelompokBelajars = $this->getKelompokBelajarUntukGuru($user);

        return view('guru.lms.edit', compact('lms', 'kelompokBelajars', 'guruMapels'));
    }

    /**
     * Update konten LMS yang sudah ada.
     */
    public function update(Request $request, LmsContent $lms): RedirectResponse
    {
        $this->authorizeContentOwner($lms);

        $user = Auth::user();

        $validated = $request->validate([
            'type'                => 'required|in:materi,tugas',
            'title'               => 'required|string|max:255',
            'subject'             => 'required|string|max:255',
            'description'         => 'nullable|string|max:10000',
            'kelompok_belajar_id' => 'required|integer|exists:kelompok_belajars,id',
            'file'                => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png|max:15360',
            'due_at'              => 'nullable|date',
        ]);

        abort_unless($user->teachesSubject($validated['subject']), 403, "Anda tidak mengampu mata pelajaran {$validated['subject']}.");

        $kelompokBelajar = KelompokBelajar::findOrFail($validated['kelompok_belajar_id']);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $storedPath = $file->store('lms/materials', 'public');
            if ($storedPath) {
                // Hapus file lama jika ada
                if ($lms->file_path && Storage::disk('public')->exists($lms->file_path)) {
                    Storage::disk('public')->delete($lms->file_path);
                }
                $validated['file_path'] = $storedPath;
                $validated['file_name'] = $file->getClientOriginalName();
            }
        }

        $subject = $validated['subject'];
        $validated['kantor_id']  = $kelompokBelajar->kantor_id ?: ($lms->kantor_id ?? 1);
        $validated['periode_id'] = $kelompokBelajar->periode_id ?: ($lms->periode_id ?? 1);
        $validated['subject']    = $subject;
        unset($validated['file']);

        $lms->update($validated);

        $label = $validated['type'] === 'tugas' ? 'Tugas' : 'Materi';
        return redirect()->route('guru.lms.index')->with('success', "{$label} {$subject} berhasil diperbarui.");
    }

    /**
     * Hapus konten LMS beserta file fisik dan seluruh submission terkait.
     */
    public function destroy(LmsContent $lms): RedirectResponse
    {
        $this->authorizeContentOwner($lms);

        DB::transaction(function () use ($lms) {
            // Hapus file lampiran materi guru
            if ($lms->file_path && Storage::disk('public')->exists($lms->file_path)) {
                Storage::disk('public')->delete($lms->file_path);
            }

            // Hapus seluruh file submission siswa
            foreach ($lms->submissions as $submission) {
                if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
                    Storage::disk('public')->delete($submission->file_path);
                }
            }

            $lms->delete();
        });

        return redirect()->route('guru.lms.index')->with('success', 'Konten LMS beserta data terkait berhasil dihapus.');
    }

    /**
     * Buka file lampiran materi di tab baru browser.
     * Jika diakses langsung via browser navigasi, dialihkan ke viewer in-browser
     * untuk mencegah ekstensi download manager (IDM) mencegat file PDF.
     */
    public function open(Request $request, LmsContent $lms)
    {
        $this->authorizeFileAccess($lms);

        $cleanPath = ltrim($lms->file_path, '/');
        $fullPath = Storage::disk('public')->path($cleanPath);
        $extension = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));

        // Jika file yang didukung viewer (PDF, DOCX, PPTX) dan diakses langsung via browser navigasi (bukan fetch/raw data), alihkan ke viewer
        if (in_array($extension, ['pdf', 'pptx', 'ppt', 'docx', 'doc']) && !$request->boolean('raw') && !$request->ajax() && $request->header('X-Requested-With') !== 'XMLHttpRequest') {
            return redirect()->route('guru.lms.viewer', $lms);
        }

        // Jika diminta sebagai raw data (untuk PDF.js arrayBuffer), kirimkan sebagai binary stream tanpa Content-Disposition filename
        if ($request->boolean('raw') || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->file($fullPath, [
                'Content-Type'  => 'application/octet-stream',
                'Cache-Control' => 'private, max-age=86400, must-revalidate',
            ]);
        }

        $fileName = $lms->file_name ?: basename($cleanPath);
        $mime = \Illuminate\Support\Facades\File::mimeType($fullPath) ?: 'application/octet-stream';

        $response = response()->file($fullPath, [
            'Content-Type'  => $mime,
            'Cache-Control' => 'private, max-age=86400, must-revalidate',
        ]);

        $response->setContentDisposition(
            \Symfony\Component\HttpFoundation\ResponseHeaderBag::DISPOSITION_INLINE,
            $fileName
        );

        return $response;
    }

    /**
     * Preview interaktif dokumen PDF guru menggunakan PDF.js in-browser canvas.
     */
    public function viewer(LmsContent $lms): View
    {
        $this->authorizeFileAccess($lms);

        $cleanPath = ltrim($lms->file_path, '/');
        $fileName = $lms->file_name ?: basename($cleanPath);
        $fileUrl = route('guru.lms.open', ['lms' => $lms, 'raw' => 1]);
        $downloadUrl = route('guru.lms.download', $lms);
        $backUrl = route('guru.lms.index');
        $subjectLabel = $lms->subject_label;
        $extension = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));

        $fullPath = Storage::disk('public')->path($cleanPath);
        $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;
        $fileSizeFormatted = $fileSize > 1048576 
            ? round($fileSize / 1048576, 2) . ' MB' 
            : round($fileSize / 1024, 1) . ' KB';

        return view('guru.lms.viewer', compact('lms', 'fileName', 'fileUrl', 'downloadUrl', 'backUrl', 'subjectLabel', 'extension', 'fileSizeFormatted'));
    }

    /**
     * Unduh file lampiran materi.
     */
    public function download(LmsContent $lms): StreamedResponse
    {
        $this->authorizeFileAccess($lms);

        return Storage::disk('public')->download(
            $lms->file_path,
            $lms->file_name ?: basename($lms->file_path)
        );
    }

    /**
     * Tampilkan matriks pengumpulan tugas siswa untuk konten tertentu.
     */
    public function submissions(LmsContent $lms): View
    {
        $this->authorizeContentOwner($lms);
        abort_unless($lms->isAssignment(), 404, 'Konten ini bukan berupa tugas.');

        // Ambil seluruh siswa aktif di kelas tersebut
        $students = $lms->kelompokBelajar->pesertaDidiks()
            ->aktif()
            ->orderBy('nama_lengkap')
            ->get();

        // Ambil seluruh submission siswa yang sudah masuk
        $allSubmissions = $lms->submissions()
            ->with(['pesertaDidik', 'penilai'])
            ->get();

        $submissions = $allSubmissions->keyBy('peserta_didik_id');
        $submissionsByNisn = $allSubmissions->whereNotNull('nisn')->keyBy('nisn');

        // Hitung statistik dengan lookup ganda (peserta_didik_id atau NISN)
        $submittedCount = 0;
        $gradedCount = 0;
        $lateCount = 0;

        foreach ($students as $student) {
            $sub = $submissions->get($student->id)
                ?? (!empty($student->nisn) ? $submissionsByNisn->get(trim($student->nisn)) : null);

            if ($sub) {
                $submittedCount++;
                if ($sub->nilai !== null) {
                    $gradedCount++;
                }
                if ($sub->is_late) {
                    $lateCount++;
                }
            }
        }

        $totalSiswa = $students->count();
        $totalKumpul = $submittedCount;
        $totalDinilai = $gradedCount;
        $totalBelumKumpul = max(0, $totalSiswa - $totalKumpul);

        $stats = [
            'totalSiswa'       => $totalSiswa,
            'totalKumpul'      => $totalKumpul,
            'totalDinilai'     => $totalDinilai,
            'totalBelumKumpul' => $totalBelumKumpul,
            'totalTerlambat'   => $lateCount,
        ];

        return view('guru.lms.submissions', compact('lms', 'students', 'submissions', 'submissionsByNisn', 'stats'));
    }

    /**
     * Simpan nilai dan catatan evaluasi/feedback untuk tugas siswa.
     */
    public function gradeSubmission(Request $request, LmsSubmission $submission): RedirectResponse
    {
        $this->authorizeContentOwner($submission->content);

        $validated = $request->validate([
            'nilai'         => 'required|numeric|min:0|max:100',
            'feedback_guru' => 'nullable|string|max:5000',
            'status'        => 'required|in:graded,resubmitted',
        ]);

        $submission->update([
            'nilai'         => $validated['nilai'],
            'feedback_guru' => $validated['feedback_guru'],
            'status'        => $validated['status'],
            'graded_at'     => now(),
            'graded_by'     => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Nilai dan evaluasi tugas berhasil disimpan.');
    }

    /**
     * Unduh file jawaban tugas siswa.
     */
    public function downloadSubmission(LmsSubmission $submission): StreamedResponse
    {
        $this->authorizeContentOwner($submission->content);
        abort_unless($submission->file_path && Storage::disk('public')->exists($submission->file_path), 404);

        return Storage::disk('public')->download(
            $submission->file_path,
            $submission->file_name ?: basename($submission->file_path)
        );
    }

    /**
     * Preview interaktif dokumen PDF jawaban tugas siswa untuk guru.
     */
    public function viewerSubmission(LmsSubmission $submission): View
    {
        $this->authorizeContentOwner($submission->content);
        abort_unless($submission->file_path && Storage::disk('public')->exists($submission->file_path), 404);

        $lms = $submission->content;
        $cleanPath = ltrim($submission->file_path, '/');
        $fileName = $submission->file_name ?: basename($cleanPath);
        $fileUrl = route('guru.lms.submission.open', ['submission' => $submission, 'raw' => 1]);
        $downloadUrl = route('guru.lms.submission.download', $submission);
        $backUrl = route('guru.lms.submissions', $lms);
        $subjectLabel = $lms->subject_label;
        $extension = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));

        $fullPath = Storage::disk('public')->path($cleanPath);
        $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;
        $fileSizeFormatted = $fileSize > 1048576 
            ? round($fileSize / 1048576, 2) . ' MB' 
            : round($fileSize / 1024, 1) . ' KB';

        return view('guru.lms.viewer', compact('lms', 'fileName', 'fileUrl', 'downloadUrl', 'backUrl', 'subjectLabel', 'extension', 'fileSizeFormatted'));
    }

    /**
     * Buka file jawaban tugas siswa di browser.
     */
    public function openSubmission(Request $request, LmsSubmission $submission)
    {
        $this->authorizeContentOwner($submission->content);
        abort_unless($submission->file_path && Storage::disk('public')->exists($submission->file_path), 404);

        $cleanPath = ltrim($submission->file_path, '/');
        $fullPath = Storage::disk('public')->path($cleanPath);
        $extension = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));

        // Jika file yang didukung viewer (PDF, DOCX, PPTX) dan diakses langsung via browser navigasi, alihkan ke viewer
        if (in_array($extension, ['pdf', 'pptx', 'ppt', 'docx', 'doc']) && !$request->boolean('raw') && !$request->ajax() && $request->header('X-Requested-With') !== 'XMLHttpRequest') {
            return redirect()->route('guru.lms.submission.viewer', $submission);
        }

        // Stream raw binary data untuk PDF.js canvas tanpa Content-Disposition filename
        if ($request->boolean('raw') || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->file($fullPath, [
                'Content-Type'  => 'application/octet-stream',
                'Cache-Control' => 'private, max-age=86400, must-revalidate',
            ]);
        }

        $fileName = $submission->file_name ?: basename($cleanPath);
        $mime = \Illuminate\Support\Facades\File::mimeType($fullPath) ?: 'application/octet-stream';

        $response = response()->file($fullPath, [
            'Content-Type'  => $mime,
            'Cache-Control' => 'private, max-age=86400, must-revalidate',
        ]);

        $response->setContentDisposition(
            \Symfony\Component\HttpFoundation\ResponseHeaderBag::DISPOSITION_INLINE,
            $fileName
        );

        return $response;
    }

    // ─── Private Guards & Helpers ──────────────────────────────────────────

    /**
     * Dapatkan daftar kelompok belajar yang dapat diakses oleh guru untuk mata pelajarannya.
     */
    protected function getKelompokBelajarUntukGuru($user)
    {
        $guruId = $user->id;

        // 1. Cek apakah admin sudah menugaskan guru ini ke rombel tertentu di jadwal
        $scheduledRombelIds = \App\Models\Akademik\Jadwal::where('guru_id', $guruId)
            ->pluck('rombel_id')
            ->filter()
            ->unique()
            ->toArray();

        // 2. Cek juga dari jadwal_mapels jika ada
        $legacyKelasNames = \App\Models\Akademik\JadwalMapel::where('guru_id', $guruId)
            ->pluck('kelas')
            ->filter()
            ->unique()
            ->toArray();

        $query = KelompokBelajar::query()->with(['kantor', 'periode']);

        if (!empty($scheduledRombelIds)) {
            $query->whereIn('id', $scheduledRombelIds);
        } elseif (!empty($legacyKelasNames)) {
            $query->whereIn('nama_kelompok', $legacyKelasNames);
        } else {
            // 3. Jika admin belum memetakan jadwal per rombel, filter sesuai kantor & periode aktif guru
            $kantorId = session('kantor_id') ?? $user->kantor_id;
            $periodeId = session('periode_id') ?? $user->periode_id;

            $query->when($kantorId && $kantorId !== 'all', fn($q) => $q->where('kantor_id', $kantorId))
                  ->when($periodeId, fn($q) => $q->where('periode_id', $periodeId));
        }

        $list = $query->orderBy('nama_kelompok')->get();

        if ($list->isEmpty()) {
            $list = KelompokBelajar::with(['kantor', 'periode'])->orderBy('nama_kelompok')->get();
        }

        return $list;
    }

    /**
     * Tampilkan indikator & daftar siswa di rombel yang sudah/belum membaca materi (deteksi NISN).
     */
    public function readers(Request $request, LmsContent $lms)
    {
        $this->authorizeContentOwner($lms);

        $lms->load(['guru', 'kelompokBelajar']);

        // Ambil data siswa aktif di rombel materi ini
        $rombel = $lms->kelompokBelajar;
        if ($rombel) {
            $students = $rombel->pesertaDidiks()
                ->aktif()
                ->orderBy('nama_lengkap', 'asc')
                ->get(['id', 'nama_lengkap', 'nisn', 'nomor_induk', 'jenis_kelamin', 'status']);
        } else {
            $students = PesertaDidik::aktif()
                ->when($lms->kantor_id, fn($q) => $q->where('kantor_id', $lms->kantor_id))
                ->when($lms->periode_id, fn($q) => $q->where('periode_id', $lms->periode_id))
                ->orderBy('nama_lengkap', 'asc')
                ->get(['id', 'nama_lengkap', 'nisn', 'nomor_induk', 'jenis_kelamin', 'status']);
        }

        // Ambil data completion materi ini
        $completions = $lms->completions()->get();
        $completionsByStudentId = $completions->keyBy('peserta_didik_id');
        $completionsByNisn = $completions->whereNotNull('nisn')->keyBy('nisn');

        $studentList = $students->map(function ($s) use ($completionsByStudentId, $completionsByNisn) {
            $comp = $completionsByStudentId->get($s->id) 
                ?? (!empty($s->nisn) ? $completionsByNisn->get(trim($s->nisn)) : null);

            $hasRead = $comp && ($comp->read_at !== null || ($comp->completed_at !== null && $comp->downloaded_at === null));
            $hasDownloaded = $comp && $comp->downloaded_at !== null;
            if ($comp && !$hasRead && !$hasDownloaded && $comp->completed_at) {
                $hasRead = true;
            }

            return [
                'id'             => $s->id,
                'nisn'           => $s->nisn ?: '-',
                'nomor_induk'    => $s->nomor_induk ?: '-',
                'nama_lengkap'   => $s->nama_lengkap,
                'jenis_kelamin'  => $s->jenis_kelamin,
                'has_read'       => (bool) $hasRead,
                'read_at'        => ($comp && $comp->read_at) 
                    ? $comp->read_at->translatedFormat('d M Y, H:i') . ' WIB' 
                    : (($comp && $hasRead && $comp->completed_at) ? $comp->completed_at->translatedFormat('d M Y, H:i') . ' WIB' : null),
                'has_downloaded' => (bool) $hasDownloaded,
                'downloaded_at'  => ($comp && $comp->downloaded_at) 
                    ? $comp->downloaded_at->translatedFormat('d M Y, H:i') . ' WIB' 
                    : null,
            ];
        });

        $totalRombel = $studentList->count();
        $totalRead = $studentList->where('has_read', true)->count();
        $totalDownloaded = $studentList->where('has_downloaded', true)->count();
        $readPercentage = $totalRombel > 0 ? round(($totalRead / $totalRombel) * 100) : 0;
        $downloadPercentage = $totalRombel > 0 ? round(($totalDownloaded / $totalRombel) * 100) : 0;

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'             => true,
                'lms_id'              => $lms->id,
                'title'               => $lms->title,
                'subject'             => $lms->subject_label,
                'has_file'            => !empty($lms->file_path),
                'file_name'           => $lms->file_name,
                'rombel_name'         => $rombel?->nama_kelompok ?? 'Semua Siswa',
                'total_rombel'        => $totalRombel,
                'total_read'          => $totalRead,
                'total_downloaded'    => $totalDownloaded,
                'percentage'          => $readPercentage,
                'read_percentage'     => $readPercentage,
                'download_percentage' => $downloadPercentage,
                'students'            => $studentList->values(),
            ]);
        }

        return view('guru.lms.readers', compact(
            'lms',
            'rombel',
            'studentList',
            'totalRombel',
            'totalRead',
            'totalDownloaded',
            'readPercentage',
            'downloadPercentage'
        ));
    }

    private function authorizeContentOwner(LmsContent $lms): void
    {
        abort_unless($lms->guru_id === Auth::id(), 403, 'Anda tidak memiliki hak akses untuk konten LMS ini.');

        $user = Auth::user();
        if ($lms->subject && !$user->teachesSubject($lms->subject)) {
            abort(403, "Anda hanya berwenang mengelola konten mata pelajaran yang Anda ampu.");
        }
    }

    private function authorizeFileAccess(LmsContent $lms): void
    {
        $this->authorizeContentOwner($lms);
        abort_unless($lms->file_path && Storage::disk('public')->exists($lms->file_path), 404, 'File lampiran tidak ditemukan.');
    }
}
