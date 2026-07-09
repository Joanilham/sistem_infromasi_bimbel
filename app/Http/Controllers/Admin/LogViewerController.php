<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Pagination\LengthAwarePaginator;

class LogViewerController extends Controller
{
    /**
     * Regex pattern to match log entries.
     * Pattern matches [YYYY-MM-DD HH:MM:SS] Environment.Level: Message
     */
    private $logPattern = '/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.*)/m';

    public function index(Request $request)
    {
        $logPath = storage_path('logs');
        $files = collect(File::files($logPath))
            ->filter(function ($file) {
                return $file->getExtension() === 'log';
            })
            ->map(function ($file) {
                return [
                    'name' => $file->getFilename(),
                    'path' => $file->getPathname(),
                    'size' => $this->formatBytes($file->getSize()),
                    'mtime' => $file->getMTime(),
                ];
            })
            ->sortByDesc('mtime')
            ->values();

        $selectedFile = $request->query('file');
        if (!$selectedFile && $files->isNotEmpty()) {
            $selectedFile = $files->first()['name'];
        }

        $logs = [];
        $fileSize = '0 B';
        if ($selectedFile && File::exists($logPath . '/' . $selectedFile)) {
            $filePath = $logPath . '/' . $selectedFile;
            $fileSize = $this->formatBytes(File::size($filePath));
            
            // Read file efficiently (tail)
            // Read the last 2000 lines max to prevent memory exhaustion
            $content = $this->tailFile($filePath, 2000);
            
            // Reverse content so newest is first
            $lines = explode("\n", $content);
            $lines = array_reverse($lines);

            $logsCollection = [];
            $currentStack = [];
            
            foreach ($lines as $line) {
                if (preg_match($this->logPattern, $line, $matches)) {
                    $logsCollection[] = [
                        'date' => $matches[1],
                        'env' => $matches[2],
                        'level' => strtoupper($matches[3]),
                        'message' => trim($matches[4]),
                        'stack' => implode("\n", $currentStack),
                    ];
                    $currentStack = [];
                } else {
                    if (trim($line) !== '') {
                        array_unshift($currentStack, $line);
                    }
                }
            }
            
            // Filter by level
            $levelFilter = $request->query('level');
            if ($levelFilter) {
                $logsCollection = array_filter($logsCollection, function($log) use ($levelFilter) {
                    return strcasecmp($log['level'], $levelFilter) === 0;
                });
            }

            // Filter by search
            $search = $request->query('search');
            if ($search) {
                $logsCollection = array_filter($logsCollection, function($log) use ($search) {
                    return stripos($log['message'], $search) !== false || stripos($log['stack'], $search) !== false;
                });
            }

            // Paginate
            $perPage = 25;
            $page = $request->query('page', 1);
            $offset = ($page - 1) * $perPage;
            
            $logs = new LengthAwarePaginator(
                array_slice($logsCollection, $offset, $perPage),
                count($logsCollection),
                $perPage,
                $page,
                ['path' => route('admin.log-viewer'), 'query' => $request->query()]
            );
        }

        // Summary counts for badges
        $summary = [];
        if (isset($logsCollection)) {
            $summary = collect($logsCollection)->groupBy('level')->map->count()->toArray();
        }

        return view('admin.log-viewer.index', compact('files', 'selectedFile', 'fileSize', 'logs', 'summary'));
    }

    public function clear(Request $request)
    {
        $file = $request->input('file');
        $logPath = storage_path('logs/' . $file);
        
        if (File::exists($logPath) && str_ends_with($file, '.log')) {
            File::put($logPath, '');
            return back()->with('success', 'File log berhasil dikosongkan.');
        }
        
        return back()->with('error', 'File log tidak ditemukan.');
    }

    public function destroy(Request $request)
    {
        $file = $request->input('file');
        $logPath = storage_path('logs/' . $file);
        
        if (File::exists($logPath) && str_ends_with($file, '.log')) {
            File::delete($logPath);
            return redirect()->route('admin.log-viewer')->with('success', 'File log berhasil dihapus.');
        }
        
        return back()->with('error', 'File log tidak ditemukan.');
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    private function tailFile($filepath, $lines = 1000)
    {
        $f = @fopen($filepath, "rb");
        if ($f === false) return false;
        
        fseek($f, -1, SEEK_END);
        if (fread($f, 1) != "\n") $lines -= 1;
        
        $output = '';
        $chunklen = 4096;
        while (ftell($f) > 0 && $lines >= 0) {
            $seek = min(ftell($f), $chunklen);
            fseek($f, -$seek, SEEK_CUR);
            $output = ($chunk = fread($f, $seek)) . $output;
            fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
            $lines -= substr_count($chunk, "\n");
        }
        
        while ($lines++ < 0) {
            $output = substr($output, strpos($output, "\n") + 1);
        }
        
        fclose($f);
        return trim($output);
    }
}
