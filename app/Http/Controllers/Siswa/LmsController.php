<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lms\SubmitAssignmentRequest;
use App\Models\Akademik\LmsContent;
use App\Models\Akademik\LmsSubmission;
use App\Models\Akademik\PesertaDidik;
use App\Services\LmsService;
use App\Traits\HandlesLmsFiles;
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

    // ─── 1. Ruang Belajar & Penjelajahan Siswa ───────────────────────────────

    public function index(Request $request): View
    {
        $pesertaDidik = Auth::user()->pesertaDidik;
        $data = $this->lmsService->getStudentLmsDashboard($pesertaDidik, $request);

        return view('siswa.lms.index', $data);
    }

    public function show(LmsContent $lms): View
    {
        $pesertaDidik = $this->authorizeClassAccess($lms);
        $this->trackMaterialReadIfApplicable($lms, $pesertaDidik);

        $viewData = $this->lmsService->getStudentDetailData($lms, $pesertaDidik);

        return view('siswa.lms.show', $viewData);
    }

    // ─── 2. Berkas Materi Guru ───────────────────────────────────────────────

    public function open(Request $request, LmsContent $lms): BinaryFileResponse|RedirectResponse
    {
        $this->authorizeFileAccess($lms);
        $this->trackMaterialReadIfApplicable($lms);

        return $this->streamFileResponse($lms->file_path, $lms->file_name, $request, 'siswa.lms.viewer', $lms);
    }

    public function viewer(LmsContent $lms): View
    {
        $this->authorizeFileAccess($lms);
        $this->trackMaterialReadIfApplicable($lms);

        return $this->renderViewer(
            $lms,
            $lms->file_path,
            $lms->file_name,
            route('siswa.lms.open', ['lms' => $lms, 'raw' => 1]),
            route('siswa.lms.download', $lms),
            route('siswa.lms.show', $lms),
            'siswa.lms.viewer'
        );
    }

    public function download(LmsContent $lms): StreamedResponse
    {
        $this->authorizeFileAccess($lms);

        $pesertaDidik = Auth::user()->pesertaDidik;
        if ($pesertaDidik) {
            $this->lmsService->recordMaterialDownload($lms, $pesertaDidik);
        }

        return $this->downloadFileResponse($lms->file_path, $lms->file_name);
    }

    // ─── 3. Pengumpulan Tugas & Status Progres ────────────────────────────────

    public function submit(SubmitAssignmentRequest $request, LmsContent $lms): RedirectResponse
    {
        $pesertaDidik = $this->authorizeClassAccess($lms);
        abort_unless($lms->isAssignment(), 400, 'Konten ini bukan berupa tugas.');

        $result = $this->lmsService->submitAssignment(
            $lms,
            $pesertaDidik,
            $request->file('file'),
            $request->input('catatan_siswa')
        );

        $key = $result['success'] ? 'success' : 'error';
        return redirect()->back()->with($key, $result['message']);
    }

    public function unsubmit(LmsContent $lms): RedirectResponse
    {
        $pesertaDidik = $this->authorizeClassAccess($lms);
        $result = $this->lmsService->unsubmitAssignment($lms, $pesertaDidik);

        $key = $result['success'] ? 'success' : 'error';
        return redirect()->back()->with($key, $result['message']);
    }

    public function toggleComplete(LmsContent $lms): RedirectResponse
    {
        $pesertaDidik = $this->authorizeClassAccess($lms);
        abort_unless($lms->isMaterial(), 400, 'Fitur ini hanya untuk materi pembelajaran.');

        $result = $this->lmsService->toggleMaterialComplete($lms, $pesertaDidik);

        return redirect()->back()->with('success', $result['message']);
    }

    // ─── 4. Berkas Jawaban Tugas Siswa ───────────────────────────────────────

    public function openSubmission(Request $request, LmsSubmission $submission): BinaryFileResponse|RedirectResponse
    {
        $this->authorizeSubmissionOwner($submission);

        return $this->streamFileResponse(
            $submission->file_path,
            $submission->file_name,
            $request,
            'siswa.lms.submission.viewer',
            $submission
        );
    }

    public function viewerSubmission(LmsSubmission $submission): View
    {
        $this->authorizeSubmissionOwner($submission);

        return $this->renderViewer(
            $submission->content,
            $submission->file_path,
            $submission->file_name,
            route('siswa.lms.submission.open', ['submission' => $submission, 'raw' => 1]),
            route('siswa.lms.submission.download', $submission),
            route('siswa.lms.show', $submission->content),
            'siswa.lms.viewer'
        );
    }

    public function downloadSubmission(LmsSubmission $submission): StreamedResponse
    {
        $this->authorizeSubmissionOwner($submission);

        return $this->downloadFileResponse($submission->file_path, $submission->file_name);
    }

    // ─── 5. Otorisasi Akses Siswa ────────────────────────────────────────────

    private function authorizeClassAccess(LmsContent $lms): PesertaDidik
    {
        $pesertaDidik = Auth::user()->pesertaDidik;

        abort_unless(
            $pesertaDidik && $lms->kelompok_belajar_id === $pesertaDidik->kelompok_belajar_id,
            403,
            'Anda tidak terdaftar di kelas untuk konten ini.'
        );

        return $pesertaDidik;
    }

    private function authorizeFileAccess(LmsContent $lms): void
    {
        $this->authorizeClassAccess($lms);

        $cleanPath = $this->sanitizeStoragePath($lms->file_path);
        abort_unless(!empty($cleanPath) && Storage::disk('public')->exists($cleanPath), 404, 'File lampiran tidak ditemukan di server.');
    }

    private function authorizeSubmissionOwner(LmsSubmission $submission): void
    {
        $pesertaDidik = Auth::user()->pesertaDidik;

        abort_unless(
            $pesertaDidik && $submission->peserta_didik_id === $pesertaDidik->id,
            403,
            'Akses ditolak.'
        );

        $cleanPath = $this->sanitizeStoragePath($submission->file_path);
        abort_unless(!empty($cleanPath) && Storage::disk('public')->exists($cleanPath), 404, 'File lampiran tidak ditemukan di server.');
    }

    private function trackMaterialReadIfApplicable(LmsContent $lms, ?PesertaDidik $pesertaDidik = null): void
    {
        if (!$lms->isMaterial()) {
            return;
        }

        $pesertaDidik = $pesertaDidik ?: Auth::user()->pesertaDidik;
        if ($pesertaDidik) {
            $this->lmsService->recordMaterialRead($lms, $pesertaDidik);
        }
    }
}
