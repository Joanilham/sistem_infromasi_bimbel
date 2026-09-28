<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Akademik\LmsContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class LmsController extends Controller
{
    public function index(): View
    {
        $groupId = Auth::user()->pesertaDidik?->kelompok_belajar_id;
        $contents = $groupId
            ? LmsContent::with('guru')->where('kelompok_belajar_id', $groupId)->latest()->get()
            : collect();

        return view('siswa.lms.index', compact('contents'));
    }

    public function open(LmsContent $lms): BinaryFileResponse
    {
        $this->authorizeFileAccess($lms);

        return response()->file(Storage::disk('public')->path($lms->file_path), [
            'Content-Disposition' => 'inline; filename="' . addslashes($lms->file_name ?: basename($lms->file_path)) . '"',
        ]);
    }

    public function download(LmsContent $lms): StreamedResponse
    {
        $this->authorizeFileAccess($lms);

        return Storage::disk('public')->download($lms->file_path, $lms->file_name ?: basename($lms->file_path));
    }

    private function authorizeFileAccess(LmsContent $lms): void
    {
        abort_unless($lms->kelompok_belajar_id === Auth::user()->pesertaDidik?->kelompok_belajar_id, 403);
        abort_unless($lms->file_path && Storage::disk('public')->exists($lms->file_path), 404);
    }
}
