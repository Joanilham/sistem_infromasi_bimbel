<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Akademik\LmsCompletion;
use App\Models\Akademik\LmsContent;
use App\Models\Akademik\LmsSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class LmsController extends Controller
{
    /**
     * Tampilkan seluruh materi dan tugas yang tersedia untuk kelompok belajar siswa.
     */
    public function index(Request $request): View
    {
        $pesertaDidik = Auth::user()->pesertaDidik;
        $groupId = $pesertaDidik?->kelompok_belajar_id;

        if (!$groupId) {
            $contents = collect();
            $materials = collect();
            $assignments = collect();
            $stats = [
                'totalMateri'      => 0,
                'materiSelesai'    => 0,
                'totalTugas'       => 0,
                'tugasKumpul'      => 0,
                'tugasDinilai'     => 0,
                'tugasBelumKumpul' => 0,
            ];
            return view('siswa.lms.index', compact('contents', 'materials', 'assignments', 'pesertaDidik', 'stats'));
        }

        $allContents = LmsContent::with(['guru', 'submissions' => function ($q) use ($pesertaDidik) {
                $q->where('peserta_didik_id', $pesertaDidik->id);
            }, 'completions' => function ($q) use ($pesertaDidik) {
                $q->where('peserta_didik_id', $pesertaDidik->id);
            }])
            ->where('kelompok_belajar_id', $groupId)
            ->latest()
            ->get();

        $materials = $allContents->where('type', 'materi');
        $assignments = $allContents->where('type', 'tugas');

        // Kalkulasi statistik tugas & materi siswa
        $totalMateri = $materials->count();
        $materiSelesai = $materials->filter(fn($m) => $m->isCompletedBy($pesertaDidik))->count();

        $totalTugas = $assignments->count();
        $tugasKumpul = $assignments->filter(fn($t) => $t->getSubmissionFor($pesertaDidik) !== null)->count();
        $tugasDinilai = $assignments->filter(fn($t) => $t->getSubmissionFor($pesertaDidik)?->isGraded())->count();
        $tugasBelumKumpul = max(0, $totalTugas - $tugasKumpul);

        $stats = [
            'totalMateri'      => $totalMateri,
            'materiSelesai'    => $materiSelesai,
            'totalTugas'       => $totalTugas,
            'tugasKumpul'      => $tugasKumpul,
            'tugasDinilai'     => $tugasDinilai,
            'tugasBelumKumpul' => $tugasBelumKumpul,
        ];

        // Filter tampilan berdasarkan query pencarian dan status
        $contents = $allContents;

        if ($request->filled('search')) {
            $term = strtolower(trim($request->search));
            $contents = $contents->filter(function ($item) use ($term) {
                return str_contains(strtolower($item->title), $term)
                    || str_contains(strtolower($item->subject_label), $term)
                    || str_contains(strtolower($item->guru->name ?? ''), $term)
                    || str_contains(strtolower($item->description ?? ''), $term);
            });
        }

        if ($request->filled('type')) {
            $contents = $contents->where('type', $request->type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'belum_kumpul') {
                $contents = $contents->filter(function ($item) use ($pesertaDidik) {
                    return $item->isAssignment() && $item->getSubmissionFor($pesertaDidik) === null;
                });
            } elseif ($request->status === 'selesai') {
                $contents = $contents->filter(function ($item) use ($pesertaDidik) {
                    return $item->isCompletedBy($pesertaDidik) || ($item->isAssignment() && $item->getSubmissionFor($pesertaDidik) !== null);
                });
            }
        }

        return view('siswa.lms.index', compact('contents', 'materials', 'assignments', 'pesertaDidik', 'stats'));
    }

    /**
     * Tampilkan detail materi / tugas siswa dalam tampilan terfokus.
     */
    public function show(LmsContent $lms): View
    {
        $pesertaDidik = Auth::user()->pesertaDidik;
        abort_unless($pesertaDidik && $lms->kelompok_belajar_id === $pesertaDidik->kelompok_belajar_id, 403, 'Anda tidak terdaftar di kelas untuk konten ini.');

        if ($lms->isMaterial()) {
            $this->recordRead($lms);
        }

        $lms->load(['guru', 'kelompokBelajar']);
        $submission = $lms->getSubmissionFor($pesertaDidik);
        $isCompleted = $lms->isCompletedBy($pesertaDidik);
        $completion = $lms->getCompletionFor($pesertaDidik);

        return view('siswa.lms.show', compact('lms', 'pesertaDidik', 'submission', 'isCompleted', 'completion'));
    }

    /**
     * Preview interaktif dokumen PDF siswa menggunakan PDF.js in-browser canvas.
     */
    public function viewer(LmsContent $lms): View
    {
        $this->authorizeFileAccess($lms);

        if ($lms->isMaterial()) {
            $this->recordRead($lms);
        }

        $cleanPath = ltrim($lms->file_path, '/');
        $fileName = $lms->file_name ?: basename($cleanPath);
        $fileUrl = route('siswa.lms.open', ['lms' => $lms, 'raw' => 1]);
        $downloadUrl = route('siswa.lms.download', $lms);
        $backUrl = route('siswa.lms.show', $lms);
        $subjectLabel = $lms->subject_label;
        $extension = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));

        $fullPath = Storage::disk('public')->path($cleanPath);
        $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;
        $fileSizeFormatted = $fileSize > 1048576 
            ? round($fileSize / 1048576, 2) . ' MB' 
            : round($fileSize / 1024, 1) . ' KB';

        return view('siswa.lms.viewer', compact('lms', 'fileName', 'fileUrl', 'downloadUrl', 'backUrl', 'subjectLabel', 'extension', 'fileSizeFormatted'));
    }

    /**
     * Buka file lampiran materi guru di tab baru browser.
     * Jika diakses langsung via browser navigasi, dialihkan ke viewer in-browser
     * untuk mencegah ekstensi download manager (IDM) mencegat file PDF.
     */
    public function open(Request $request, LmsContent $lms)
    {
        $this->authorizeFileAccess($lms);

        if ($lms->isMaterial()) {
            $this->recordRead($lms);
        }

        $cleanPath = ltrim($lms->file_path, '/');
        $fullPath = Storage::disk('public')->path($cleanPath);
        $extension = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));

        // Jika file yang didukung viewer (PDF, DOCX, PPTX) dan diakses langsung via browser navigasi (bukan fetch/raw data), alihkan ke viewer
        if (in_array($extension, ['pdf', 'pptx', 'ppt', 'docx', 'doc']) && !$request->boolean('raw') && !$request->ajax() && $request->header('X-Requested-With') !== 'XMLHttpRequest') {
            return redirect()->route('siswa.lms.viewer', $lms);
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
     * Unduh file lampiran materi guru.
     */
    public function download(LmsContent $lms): StreamedResponse
    {
        $this->authorizeFileAccess($lms);
        $this->recordDownload($lms);

        $cleanPath = ltrim($lms->file_path, '/');
        return Storage::disk('public')->download($cleanPath, $lms->file_name ?: basename($cleanPath));
    }

    /**
     * Pengumpulan tugas oleh siswa (upload dokumen/catatan).
     */
    public function submit(Request $request, LmsContent $lms): RedirectResponse
    {
        $pesertaDidik = Auth::user()->pesertaDidik;
        abort_unless($pesertaDidik && $lms->kelompok_belajar_id === $pesertaDidik->kelompok_belajar_id, 403, 'Akses ditolak.');
        abort_unless($lms->isAssignment(), 400, 'Konten ini bukan berupa tugas.');

        $existingSubmission = $lms->getSubmissionFor($pesertaDidik);

        // Jika sudah dinilai, tidak bisa upload ulang kecuali statusnya di-return
        if ($existingSubmission && $existingSubmission->status === 'graded') {
            return redirect()->back()->with('error', 'Tugas ini sudah dinilai oleh guru dan tidak dapat dikumpulkan ulang.');
        }

        $request->validate([
            'file'          => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png|max:15360',
            'catatan_siswa' => 'nullable|string|max:5000',
        ]);

        if (!$existingSubmission && !$request->hasFile('file') && empty($request->catatan_siswa)) {
            return redirect()->back()->with('error', 'Silakan lampirkan file jawaban atau tulis catatan pengumpulan tugas.');
        }

        $filePath = $existingSubmission?->file_path;
        $fileName = $existingSubmission?->file_name;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $storedPath = $file->store("lms/submissions/{$lms->id}", 'public');
            if ($storedPath) {
                // Hapus file jawaban lama jika upload ulang
                if ($filePath && Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
                $filePath = $storedPath;
                $fileName = $file->getClientOriginalName();
            }
        }

        $isLate = $lms->due_at ? now()->greaterThan($lms->due_at) : false;

        LmsSubmission::updateOrCreate(
            [
                'lms_content_id'   => $lms->id,
                'peserta_didik_id' => $pesertaDidik->id,
            ],
            [
                'nisn'           => $pesertaDidik->nisn ?: null,
                'file_path'      => $filePath,
                'file_name'      => $fileName,
                'catatan_siswa'  => $request->catatan_siswa,
                'submitted_at'   => now(),
                'is_late'        => $isLate,
                'status'         => $existingSubmission ? 'resubmitted' : 'submitted',
            ]
        );

        $statusMsg = $isLate ? 'Tugas berhasil dikumpulkan (Terlambat).' : 'Tugas berhasil dikumpulkan tepat waktu.';
        return redirect()->back()->with('success', $statusMsg);
    }

    /**
     * Batalkan pengumpulan tugas (Unsubmit).
     */
    public function unsubmit(LmsContent $lms): RedirectResponse
    {
        $pesertaDidik = Auth::user()->pesertaDidik;
        abort_unless($pesertaDidik && $lms->kelompok_belajar_id === $pesertaDidik->kelompok_belajar_id, 403);

        $submission = $lms->getSubmissionFor($pesertaDidik);
        abort_unless($submission, 404, 'Pengumpulan tugas tidak ditemukan.');

        if ($submission->isGraded()) {
            return redirect()->back()->with('error', 'Tugas sudah dinilai dan tidak dapat dibatalkan.');
        }

        if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
            Storage::disk('public')->delete($submission->file_path);
        }

        $submission->delete();

        return redirect()->back()->with('success', 'Pengumpulan tugas berhasil ditarik/dibatalkan.');
    }

    /**
     * Tandai materi selesai atau belum selesai dipelajari (Toggle completion).
     */
    public function toggleComplete(LmsContent $lms): RedirectResponse
    {
        $pesertaDidik = Auth::user()->pesertaDidik;
        abort_unless($pesertaDidik && $lms->kelompok_belajar_id === $pesertaDidik->kelompok_belajar_id, 403);
        abort_unless($lms->isMaterial(), 400, 'Fitur ini hanya untuk materi pembelajaran.');

        // Jika materi memiliki berkas PDF / lampiran, pencatatan otomatis dilakukan saat siswa mengunduh berkas
        if ($lms->file_path) {
            return redirect()->route('siswa.lms.download', $lms);
        }

        $nisn = !empty($pesertaDidik->nisn) ? trim($pesertaDidik->nisn) : null;

        $completion = LmsCompletion::where('lms_content_id', $lms->id)
            ->where(function ($q) use ($pesertaDidik, $nisn) {
                $q->where('peserta_didik_id', $pesertaDidik->id);
                if ($nisn) {
                    $q->orWhere('nisn', $nisn);
                }
            })
            ->first();

        if ($completion) {
            $completion->delete();
            $msg = 'Materi ditandai belum selesai.';
        } else {
            LmsCompletion::create([
                'lms_content_id'   => $lms->id,
                'peserta_didik_id' => $pesertaDidik->id,
                'nisn'             => $nisn,
                'read_at'          => now(),
                'completed_at'     => now(),
            ]);
            $msg = 'Hebat! Materi berhasil ditandai selesai dipelajari.';
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Catat status membaca materi oleh siswa terdeteksi berdasarkan NISN dan peserta_didik_id.
     */
    protected function recordRead(LmsContent $lms): ?LmsCompletion
    {
        if (!$lms->isMaterial()) {
            return null;
        }

        $user = Auth::user();
        $pesertaDidik = $user?->pesertaDidik;
        if (!$pesertaDidik) {
            return null;
        }

        $nisn = !empty($pesertaDidik->nisn) ? trim($pesertaDidik->nisn) : null;

        $existing = LmsCompletion::where('lms_content_id', $lms->id)
            ->where(function ($q) use ($pesertaDidik, $nisn) {
                $q->where('peserta_didik_id', $pesertaDidik->id);
                if ($nisn) {
                    $q->orWhere('nisn', $nisn);
                }
            })
            ->first();

        if ($existing) {
            $updates = [];
            if (is_null($existing->read_at)) {
                $updates['read_at'] = now();
            }
            if (is_null($existing->completed_at)) {
                $updates['completed_at'] = now();
            }
            if ($nisn && empty($existing->nisn)) {
                $updates['nisn'] = $nisn;
            }
            if (!empty($updates)) {
                $existing->update($updates);
            }
            return $existing;
        }

        return LmsCompletion::create([
            'lms_content_id'   => $lms->id,
            'peserta_didik_id' => $pesertaDidik->id,
            'nisn'             => $nisn,
            'read_at'          => now(),
            'completed_at'     => now(),
        ]);
    }

    /**
     * Catat status mengunduh berkas materi oleh siswa terdeteksi berdasarkan NISN dan peserta_didik_id.
     */
    protected function recordDownload(LmsContent $lms): ?LmsCompletion
    {
        if (!$lms->isMaterial()) {
            return null;
        }

        $user = Auth::user();
        $pesertaDidik = $user?->pesertaDidik;
        if (!$pesertaDidik) {
            return null;
        }

        $nisn = !empty($pesertaDidik->nisn) ? trim($pesertaDidik->nisn) : null;

        $existing = LmsCompletion::where('lms_content_id', $lms->id)
            ->where(function ($q) use ($pesertaDidik, $nisn) {
                $q->where('peserta_didik_id', $pesertaDidik->id);
                if ($nisn) {
                    $q->orWhere('nisn', $nisn);
                }
            })
            ->first();

        if ($existing) {
            $updates = [];
            if (is_null($existing->downloaded_at)) {
                $updates['downloaded_at'] = now();
            }
            if (is_null($existing->completed_at)) {
                $updates['completed_at'] = now();
            }
            if ($nisn && empty($existing->nisn)) {
                $updates['nisn'] = $nisn;
            }
            if (!empty($updates)) {
                $existing->update($updates);
            }
            return $existing;
        }

        return LmsCompletion::create([
            'lms_content_id'   => $lms->id,
            'peserta_didik_id' => $pesertaDidik->id,
            'nisn'             => $nisn,
            'downloaded_at'    => now(),
            'completed_at'     => now(),
        ]);
    }

    /**
     * Catat status membaca/mengunduh materi (backward compatibility).
     */
    protected function recordCompletion(LmsContent $lms): ?LmsCompletion
    {
        return $this->recordDownload($lms);
    }

    /**
     * Unduh file tugas yang sudah dikumpulkan siswa (hanya miliknya sendiri).
     */
    public function downloadSubmission(LmsSubmission $submission): StreamedResponse
    {
        $pesertaDidik = Auth::user()->pesertaDidik;
        abort_unless($pesertaDidik && $submission->peserta_didik_id === $pesertaDidik->id, 403, 'Akses ditolak.');
        abort_unless($submission->file_path && Storage::disk('public')->exists($submission->file_path), 404);

        return Storage::disk('public')->download($submission->file_path, $submission->file_name ?: basename($submission->file_path));
    }

    /**
     * Preview interaktif dokumen PDF jawaban tugas siswa.
     */
    public function viewerSubmission(LmsSubmission $submission): View
    {
        $pesertaDidik = Auth::user()->pesertaDidik;
        abort_unless($pesertaDidik && $submission->peserta_didik_id === $pesertaDidik->id, 403, 'Akses ditolak.');
        abort_unless($submission->file_path && Storage::disk('public')->exists($submission->file_path), 404);

        $lms = $submission->content;
        $cleanPath = ltrim($submission->file_path, '/');
        $fileName = $submission->file_name ?: basename($cleanPath);
        $fileUrl = route('siswa.lms.submission.open', ['submission' => $submission, 'raw' => 1]);
        $downloadUrl = route('siswa.lms.submission.download', $submission);
        $backUrl = route('siswa.lms.show', $lms);
        $subjectLabel = $lms->subject_label;
        $extension = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));

        $fullPath = Storage::disk('public')->path($cleanPath);
        $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;
        $fileSizeFormatted = $fileSize > 1048576 
            ? round($fileSize / 1048576, 2) . ' MB' 
            : round($fileSize / 1024, 1) . ' KB';

        return view('siswa.lms.viewer', compact('lms', 'fileName', 'fileUrl', 'downloadUrl', 'backUrl', 'subjectLabel', 'extension', 'fileSizeFormatted'));
    }

    /**
     * Buka file tugas yang sudah dikumpulkan siswa di browser.
     */
    public function openSubmission(Request $request, LmsSubmission $submission)
    {
        $pesertaDidik = Auth::user()->pesertaDidik;
        abort_unless($pesertaDidik && $submission->peserta_didik_id === $pesertaDidik->id, 403, 'Akses ditolak.');
        abort_unless($submission->file_path && Storage::disk('public')->exists($submission->file_path), 404);

        $cleanPath = ltrim($submission->file_path, '/');
        $fullPath = Storage::disk('public')->path($cleanPath);
        $extension = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));

        // Jika file yang didukung viewer (PDF, DOCX, PPTX) dan diakses langsung via browser navigasi, alihkan ke viewer
        if (in_array($extension, ['pdf', 'pptx', 'ppt', 'docx', 'doc']) && !$request->boolean('raw') && !$request->ajax() && $request->header('X-Requested-With') !== 'XMLHttpRequest') {
            return redirect()->route('siswa.lms.submission.viewer', $submission);
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

    // ─── Private Guards ──────────────────────────────────────────

    private function authorizeFileAccess(LmsContent $lms): void
    {
        $pesertaDidik = Auth::user()->pesertaDidik;
        abort_unless($pesertaDidik && $lms->kelompok_belajar_id === $pesertaDidik->kelompok_belajar_id, 403, 'Anda tidak terdaftar di kelas untuk materi ini.');
        
        $cleanPath = ltrim((string)$lms->file_path, '/');
        abort_unless(!empty($cleanPath) && Storage::disk('public')->exists($cleanPath), 404, 'File lampiran tidak ditemukan di server.');
    }
}
