<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Akademik\KelompokBelajar;
use App\Models\Akademik\LmsContent;
use Illuminate\Http\RedirectResponse;
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
        $contents = LmsContent::with(['kelompokBelajar', 'guru'])
            ->where('guru_id', Auth::id())
            ->latest()
            ->get();

        $materials = $contents->where('type', 'materi');
        $assignments = $contents->where('type', 'tugas');
        $subject = Auth::user()->matapelajaran ?: 'Mata Pelajaran Umum';

        return view('guru.lms.index', compact('contents', 'materials', 'assignments', 'subject'));
    }

    public function create(): View
    {
        $kelompokBelajars = KelompokBelajar::with(['kantor', 'periode'])
            ->orderBy('nama_kelompok')
            ->get();

        return view('guru.lms.create', compact('kelompokBelajars'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:materi,tugas',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:10000',
            'kelompok_belajar_id' => 'required|integer|exists:kelompok_belajars,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,jpg,jpeg,png|max:10240',
            'due_at' => 'nullable|date',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('lms', 'public');
            $validated['file_name'] = $request->file('file')->getClientOriginalName();
        }

        $validated['guru_id'] = Auth::id();
        $validated['subject'] = Auth::user()->matapelajaran ?: null;
        unset($validated['file']);
        LmsContent::create($validated);

        return redirect()->route('guru.lms.index')->with('success', 'Konten LMS berhasil dibuat.');
    }

    public function destroy(LmsContent $lms): RedirectResponse
    {
        abort_unless($lms->guru_id === Auth::id(), 403);

        if ($lms->file_path) {
            Storage::disk('public')->delete($lms->file_path);
        }
        $lms->delete();

        return redirect()->route('guru.lms.index')->with('success', 'Konten LMS berhasil dihapus.');
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

        return Storage::disk('public')->download(
            $lms->file_path,
            $lms->file_name ?: basename($lms->file_path)
        );
    }

    private function authorizeFileAccess(LmsContent $lms): void
    {
        abort_unless($lms->guru_id === Auth::id(), 403);
        abort_unless($lms->file_path && Storage::disk('public')->exists($lms->file_path), 404);
    }

}
