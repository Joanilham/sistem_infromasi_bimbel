<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lms\GradeSubmissionRequest;
use App\Http\Requests\Lms\StoreLmsContentRequest;
use App\Http\Requests\Lms\UpdateLmsContentRequest;
use App\Models\Akademik\LmsContent;
use App\Models\Akademik\LmsSubmission;
use App\Services\LmsService;
use App\Traits\HandlesLmsFiles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LmsController extends Controller
{
    use HandlesLmsFiles;

    public function __construct(
        protected LmsService $lmsService
    ) {}

    // ─── 1. Manajemen Konten Materi & Tugas (CRUD) ───────────────────────────

    public function index(Request $request): View
    {
        $user = Auth::user();
        $selectedSubject = $request->query('subject');
        if ($selectedSubject && !$user->teachesSubject($selectedSubject)) {
            $selectedSubject = null;
        }

        $contents = $this->lmsService->getContentsForGuru($user, $request, $selectedSubject);
        $kelompokBelajars = $this->lmsService->getKelompokBelajarForGuru($user);
        $stats = $this->lmsService->getGuruStats($user, $selectedSubject);

        return view('guru.lms.index', [
            'contents'         => $contents,
            'kelompokBelajars' => $kelompokBelajars,
            'guruMapels'       => $user->daftar_mapel,
            'selectedSubject'  => $selectedSubject,
            'totalMateri'      => $stats['totalMateri'],
            'totalTugas'       => $stats['totalTugas'],
        ]);
    }

    public function create(): View
    {
        $user = Auth::user();

        return view('guru.lms.create', [
            'guruMapels'       => $user->daftar_mapel,
            'kelompokBelajars' => $this->lmsService->getKelompokBelajarForGuru($user),
        ]);
    }

    public function store(StoreLmsContentRequest $request): RedirectResponse
    {
        $content = $this->lmsService->createContent(
            Auth::user(),
            $request->validated(),
            $request->file('file')
        );

        return redirect()->route('guru.lms.index')
            ->with('success', "{$content->type_label} {$content->subject} berhasil dipublikasikan.");
    }

    public function edit(LmsContent $lms): View
    {
        $this->authorizeContentOwner($lms);
        $user = Auth::user();

        return view('guru.lms.edit', [
            'lms'              => $lms,
            'guruMapels'       => $user->daftar_mapel,
            'kelompokBelajars' => $this->lmsService->getKelompokBelajarForGuru($user),
        ]);
    }

    public function update(UpdateLmsContentRequest $request, LmsContent $lms): RedirectResponse
    {
        $this->authorizeContentOwner($lms);
        $this->lmsService->updateContent($lms, $request->validated(), $request->file('file'));

        return redirect()->route('guru.lms.index')
            ->with('success', "{$lms->type_label} {$lms->subject} berhasil diperbarui.");
    }

    public function destroy(LmsContent $lms): RedirectResponse
    {
        $this->authorizeContentOwner($lms);
        $this->lmsService->deleteContent($lms);

        return redirect()->route('guru.lms.index')
            ->with('success', 'Konten LMS beserta data terkait berhasil dihapus.');
    }

    // ─── 2. Berkas Materi Guru ───────────────────────────────────────────────

    public function open(Request $request, LmsContent $lms): BinaryFileResponse|RedirectResponse
    {
        $this->authorizeFileAccess($lms);

        return $this->streamFileResponse($lms->file_path, $lms->file_name, $request, 'guru.lms.viewer', $lms);
    }

    public function viewer(LmsContent $lms): View
    {
        $this->authorizeFileAccess($lms);

        return $this->renderViewer(
            $lms,
            $lms->file_path,
            $lms->file_name,
            route('guru.lms.open', ['lms' => $lms, 'raw' => 1]),
            route('guru.lms.download', $lms),
            route('guru.lms.index')
        );
    }

    public function download(LmsContent $lms): StreamedResponse
    {
        $this->authorizeFileAccess($lms);

        return $this->downloadFileResponse($lms->file_path, $lms->file_name);
    }

    // ─── 3. Pengelolaan & Penilaian Tugas Siswa ──────────────────────────────

    public function submissions(LmsContent $lms): View
    {
        $this->authorizeContentOwner($lms);
        abort_unless($lms->isAssignment(), 404, 'Konten ini bukan berupa tugas.');

        return view('guru.lms.submissions', array_merge(['lms' => $lms], $this->lmsService->getSubmissionsData($lms)));
    }

    public function gradeSubmission(GradeSubmissionRequest $request, LmsSubmission $submission): RedirectResponse
    {
        $this->authorizeContentOwner($submission->content);
        $this->lmsService->gradeSubmission($submission, $request->validated(), Auth::id());

        return redirect()->back()->with('success', 'Nilai dan evaluasi tugas berhasil disimpan.');
    }

    public function openSubmission(Request $request, LmsSubmission $submission): BinaryFileResponse|RedirectResponse
    {
        $this->authorizeContentOwner($submission->content);

        return $this->streamFileResponse(
            $submission->file_path,
            $submission->file_name,
            $request,
            'guru.lms.submission.viewer',
            $submission
        );
    }

    public function viewerSubmission(LmsSubmission $submission): View
    {
        $this->authorizeContentOwner($submission->content);

        return $this->renderViewer(
            $submission->content,
            $submission->file_path,
            $submission->file_name,
            route('guru.lms.submission.open', ['submission' => $submission, 'raw' => 1]),
            route('guru.lms.submission.download', $submission),
            route('guru.lms.submissions', $submission->content)
        );
    }

    public function downloadSubmission(LmsSubmission $submission): StreamedResponse
    {
        $this->authorizeContentOwner($submission->content);

        return $this->downloadFileResponse($submission->file_path, $submission->file_name);
    }

    // ─── 4. Analitik Keterbacaan Materi ──────────────────────────────────────

    public function readers(Request $request, LmsContent $lms): View|JsonResponse
    {
        $this->authorizeContentOwner($lms);
        $data = $this->lmsService->getReadersData($lms);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($this->lmsService->formatReadersJsonResponse($lms, $data));
        }

        return view('guru.lms.readers', array_merge(['lms' => $lms], $data));
    }

    // ─── 5. Otorisasi Akses ──────────────────────────────────────────────────

    private function authorizeContentOwner(LmsContent $lms): void
    {
        abort_unless($lms->guru_id === Auth::id(), 403, 'Anda tidak memiliki hak akses untuk konten LMS ini.');

        $user = Auth::user();
        if ($lms->subject && !$user->teachesSubject($lms->subject)) {
            abort(403, 'Anda hanya berwenang mengelola konten mata pelajaran yang Anda ampu.');
        }
    }

    private function authorizeFileAccess(LmsContent $lms): void
    {
        $this->authorizeContentOwner($lms);

        $cleanPath = $this->sanitizeStoragePath($lms->file_path);
        abort_unless(!empty($cleanPath) && Storage::disk('public')->exists($cleanPath), 404, 'File lampiran tidak ditemukan.');
    }
}
