<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait HandlesLmsFiles
{
    protected function sanitizeStoragePath(?string $path): ?string
    {
        if ($path === null || trim($path) === '' || str_contains($path, "\0")) {
            return null;
        }

        $path = str_replace('\\', '/', trim($path));
        if (str_starts_with($path, '/') || preg_match('/^[A-Za-z]:\//', $path)) {
            return null;
        }

        $segments = explode('/', $path);
        if (in_array('..', $segments, true) || in_array('.', $segments, true)) {
            return null;
        }

        return $path;
    }

    protected function streamFileResponse(
        ?string $filePath,
        ?string $fileName,
        Request $request,
        string $viewerRoute,
        Model $model
    ): BinaryFileResponse|RedirectResponse {
        $path = $this->sanitizeStoragePath($filePath);
        abort_unless($path && Storage::disk('public')->exists($path), 404, 'File lampiran tidak ditemukan.');

        if (!$request->boolean('raw')) {
            return redirect()->route($viewerRoute, $model);
        }

        $name = basename($fileName ?: $path);

        return response()->file(Storage::disk('public')->path($path), [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => HeaderUtils::makeDisposition('inline', $name, Str::ascii($name)),
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    protected function downloadFileResponse(?string $filePath, ?string $fileName): StreamedResponse
    {
        $path = $this->sanitizeStoragePath($filePath);
        abort_unless($path && Storage::disk('public')->exists($path), 404, 'File lampiran tidak ditemukan.');

        return Storage::disk('public')->download($path, basename($fileName ?: $path));
    }

    protected function renderViewer(
        Model $model,
        ?string $filePath,
        ?string $fileName,
        string $fileUrl,
        string $downloadUrl,
        string $backUrl,
        ?string $viewerRoute = null
    ): View {
        $path = $this->sanitizeStoragePath($filePath);
        abort_unless($path && Storage::disk('public')->exists($path), 404, 'File lampiran tidak ditemukan.');

        $bytes = Storage::disk('public')->size($path);
        $size = match (true) {
            $bytes >= 1024 * 1024 * 1024 => number_format($bytes / (1024 * 1024 * 1024), 2) . ' GB',
            $bytes >= 1024 * 1024 => number_format($bytes / (1024 * 1024), 2) . ' MB',
            $bytes >= 1024 => number_format($bytes / 1024, 2) . ' KB',
            default => $bytes . ' B',
        };

        $routeName = $viewerRoute ?: request()->route()?->getName();
        $view = str_starts_with((string) $routeName, 'guru.')
            ? 'guru.lms.viewer'
            : 'siswa.lms.viewer';

        return view($view, [
            'lms' => $model,
            'fileName' => basename($fileName ?: $path),
            'extension' => pathinfo($path, PATHINFO_EXTENSION),
            'fileSizeFormatted' => $size,
            'fileUrl' => $fileUrl,
            'downloadUrl' => $downloadUrl,
            'backUrl' => $backUrl,
        ]);
    }
}
