<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BackupController extends Controller
{
    /**
     * Proteksi akses: hanya Super Admin yang diizinkan.
     * Middleware ini berlaku untuk SEMUA method di controller ini.
     */
    public function __construct()
    {
        $this->middleware('ensure_role:Super Admin');
    }

    /**
     * Direktori penyimpanan backup di storage/app/backups
     * (Direktori ini berada di LUAR folder publik, tidak bisa diakses langsung via browser)
     */
    protected string $backupDir = 'backups';

    /**
     * Berkas pengaturan konfigurasi backup otomatis
     */
    protected string $settingsFile = 'backup_settings.json';


    /**
     * Mengambil daftar frekuensi backup otomatis saat ini
     */
    protected function getBackupFrequencies(): array
    {
        if (Storage::exists($this->settingsFile)) {
            $settings = json_decode(Storage::get($this->settingsFile), true);
            return $settings['backup_frequencies'] ?? ['daily'];
        }
        return ['daily']; // Default harian saja yang aktif
    }

    /**
     * Mengambil jam pencadangan otomatis kustom
     */
    protected function getBackupTime(): string
    {
        if (Storage::exists($this->settingsFile)) {
            $settings = json_decode(Storage::get($this->settingsFile), true);
            return $settings['backup_time'] ?? '01:00';
        }
        return '01:00'; // Default pukul 01:00 dini hari
    }

    /**
     * Menyimpan preferensi backup otomatis (mendukung multiple choices & jam kustom)
     */
    public function toggle(Request $request)
    {

        $request->validate([
            'backup_frequencies' => 'nullable|array',
            'backup_frequencies.*' => 'in:daily,weekly,monthly',
            'backup_time' => 'required|date_format:H:i',
        ]);

        $frequencies = $request->input('backup_frequencies', []);
        $backupTime = $request->input('backup_time', '01:00');

        Storage::put($this->settingsFile, json_encode([
            'backup_frequencies' => $frequencies,
            'backup_time' => $backupTime,
            'updated_at' => now()->format('Y-m-d H:i:s')
        ]));

        $freqString = count($frequencies) > 0 ? implode(', ', array_map('strtoupper', $frequencies)) : 'NONAKTIF';
        $logMsg = "Pengaturan frekuensi backup otomatis diubah menjadi: {$freqString} (Waktu: {$backupTime})";
        Log::info($logMsg . " oleh Super Admin ID " . auth()->id());
        \App\Models\System\AuditLog::logSystemEvent('Ubah Pengaturan Backup', 'SystemSettings', null, ['frekuensi' => $freqString, 'waktu' => $backupTime]);

        return back()->with('success', 'Pengaturan frekuensi backup otomatis berhasil diperbarui!');
    }

    /**
     * Halaman utama backup
     */
    public function index()
    {

        $allBackups = $this->getBackupList();
        
        // Batasi render data menjadi 10 (Pagination)
        $perPage = 10;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $currentItems = array_slice($allBackups, ($currentPage - 1) * $perPage, $perPage);
        $backups = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, count($allBackups), $perPage, $currentPage, [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
        ]);
        
        $backupFrequencies = $this->getBackupFrequencies();
        $backupTime = $this->getBackupTime();
        
        return view('admin.backup.index', compact('backups', 'backupFrequencies', 'backupTime', 'allBackups'));
    }

    /**
     * Jalankan backup database (Pure PHP - Bebas Dependensi Server)
     */
    public function create(Request $request)
    {

        try {
            // Jalankan command Artisan secara langsung dengan paksa (force) dan tipe manual
            \Illuminate\Support\Facades\Artisan::call('db:auto-backup', [
                '--force' => true,
                '--type'  => 'manual'
            ]);

            // Ambil berkas terbaru untuk pesan sukses
            $backups = $this->getBackupList();
            $latestFile = count($backups) > 0 ? $backups[0]->filename : 'baru';

            \App\Models\System\AuditLog::logSystemEvent('Buat Backup Database', 'SystemBackup', null, ['filename' => $latestFile]);
            return back()->with('success', "Backup berhasil dibuat: {$latestFile}");

        } catch (\Exception $e) {
            Log::error('Manual Backup Exception: ' . $e->getMessage());
            return back()->with('error', 'Gagal mem-backup database: ' . $e->getMessage());
        }
    }

    /**
     * Upload file backup
     */
    public function upload(Request $request)
    {

        $request->validate([
            'backup_file' => 'required|file|max:102400', // max 100MB
        ]);

        try {
            $file = $request->file('backup_file');
            $ext = strtolower($file->getClientOriginalExtension());

            if ($ext !== 'sql') {
                return back()->with('error', 'Hanya berkas berformat .sql yang diperbolehkan.');
            }

            Storage::makeDirectory($this->backupDir);
            
            // Sanitasi Nama File secara ketat untuk mencegah Directory Traversal & Injeksi Karakter
            $filename = basename($file->getClientOriginalName());
            $filename = preg_replace('/[^a-zA-Z0-9_\.-]/', '', $filename);

            // Garansi paksa ekstensi adalah .sql
            if (!str_ends_with(strtolower($filename), '.sql')) {
                $filename .= '.sql';
            }
            
            // Simpan file ke direktori backup privat
            $file->storeAs($this->backupDir, $filename);

            Log::info("File backup di-upload secara sah oleh Super Admin ID " . auth()->id() . ": {$filename}");
            \App\Models\System\AuditLog::logSystemEvent('Upload Backup Database', 'SystemBackup', null, ['filename' => $filename]);
            return back()->with('success', "File backup '{$filename}' berhasil di-upload!");

        } catch (\Exception $e) {
            Log::error('Upload Backup Exception: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat meng-upload file: ' . $e->getMessage());
        }
    }

    /**
     * Jalankan restore database dari file sql (Pure PHP - Bebas Dependensi Server)
     */
    public function restore(string $filename)
    {
        // Bypass WAF: Tambahkan .sql jika tidak ada di URL
        if (!str_ends_with(strtolower($filename), '.sql')) {
            $filename .= '.sql';
        }

        // Proteksi Directory Traversal & Batasan Ekstensi
        if (str_contains($filename, '/') || str_contains($filename, '\\') || !str_ends_with(strtolower($filename), '.sql')) {
            Log::warning("Percobaan serangan Directory Traversal pada Restore DB: {$filename} oleh User ID " . auth()->id());
            abort(403, 'Akses tidak sah.');
        }

        try {
            $path = "{$this->backupDir}/{$filename}";

            if (!Storage::exists($path)) {
                return back()->with('error', 'File backup tidak ditemukan.');
            }

            $sqlContent = Storage::get($path);

            if (empty($sqlContent)) {
                return back()->with('error', 'File backup kosong.');
            }

            // Validasi SQL injection / perintah berbahaya
            $dangerousCommands = ['DROP DATABASE', 'GRANT ', 'CREATE USER', 'ALTER USER', 'FLUSH PRIVILEGES'];
            $upperSql = strtoupper($sqlContent);
            foreach ($dangerousCommands as $cmd) {
                if (str_contains($upperSql, $cmd)) {
                    Log::warning("Restore DB Ditolak: Ditemukan perintah berbahaya ({$cmd}) oleh User ID " . auth()->id());
                    return back()->with('error', "Gagal memulihkan database: File backup mengandung perintah SQL yang tidak diizinkan ({$cmd}).");
                }
            }

            // Jalankan impor SQL secara aman (tanpa DB::transaction karena DDL MySQL menyebabkan implicit commit)
            \Illuminate\Support\Facades\DB::unprepared("SET FOREIGN_KEY_CHECKS=0;");
            \Illuminate\Support\Facades\DB::unprepared($sqlContent);
            \Illuminate\Support\Facades\DB::unprepared("SET FOREIGN_KEY_CHECKS=1;");

            // Hapus cache agar data yang di-restore langsung terbaca
            \Illuminate\Support\Facades\Artisan::call('optimize:clear');

            Log::info("Restore DB sukses dilaksanakan oleh Super Admin ID " . auth()->id() . ": {$filename}");
            \App\Models\System\AuditLog::logSystemEvent('Restore Database', 'SystemBackup', ['status' => 'sebelum_restore'], ['filename' => $filename, 'status' => 'sukses_restore']);
            return back()->with('success', "Database berhasil di-restore dari file: {$filename}");

        } catch (\Exception $e) {
            Log::error('Restore Exception (Pure PHP): ' . $e->getMessage());
            return back()->with('error', 'Gagal memulihkan database: ' . $e->getMessage());
        }
    }

    /**
     * Download file backup
     */
    public function download(string $filename)
    {
        // Bypass WAF: Tambahkan .sql jika tidak ada di URL
        if (!str_ends_with(strtolower($filename), '.sql')) {
            $filename .= '.sql';
        }

        // Proteksi Directory Traversal & Batasan Ekstensi
        if (str_contains($filename, '/') || str_contains($filename, '\\') || !str_ends_with(strtolower($filename), '.sql')) {
            Log::warning("Percobaan serangan Directory Traversal pada Download DB: {$filename} oleh User ID " . auth()->id());
            abort(403, 'Akses tidak sah.');
        }

        $relativePath = "{$this->backupDir}/{$filename}";

        if (!\Illuminate\Support\Facades\Storage::exists($relativePath)) {
            return back()->with('error', 'File backup tidak ditemukan.');
        }

        Log::info("File backup diunduh oleh Super Admin ID " . auth()->id() . ": {$filename}");
        
        // Simpan log aktivitas langsung ke database tanpa menunggu app()->terminating
        // agar terhindar dari isu koneksi terputus saat streaming file besar.
        try {
            \App\Models\System\AuditLog::create([
                'user_id'        => auth()->id(),
                'event'          => 'Download Backup Database',
                'auditable_type' => 'SystemBackup',
                'auditable_id'   => 0,
                'url'            => request()->fullUrl(),
                'ip_address'     => request()->ip(),
                'user_agent'     => request()->userAgent(),
                'new_values'     => ['filename' => $filename]
            ]);
        } catch (\Throwable $e) {
            Log::error('Audit log write failed: ' . $e->getMessage());
        }

        return \Illuminate\Support\Facades\Storage::download($relativePath);
    }

    /**
     * Hapus file backup
     */
    public function destroy(string $filename)
    {
        Log::info("DEBUG: Entering destroy with filename: {$filename}");
        // Bypass WAF: Tambahkan .sql jika tidak ada di URL
        if (!str_ends_with(strtolower($filename), '.sql')) {
            $filename .= '.sql';
        }
        Log::info("DEBUG: After adding .sql: {$filename}");

        // Proteksi Directory Traversal & Batasan Ekstensi
        if (str_contains($filename, '/') || str_contains($filename, '\\') || !str_ends_with(strtolower($filename), '.sql')) {
            Log::warning("Percobaan serangan Directory Traversal pada Hapus DB: {$filename} oleh User ID " . auth()->id());
            if (request()->wantsJson() || request()->ajax()) return response()->json(['success' => false, 'message' => 'Akses tidak sah.'], 403);
            abort(403, 'Akses tidak sah.');
        }

        $path = "{$this->backupDir}/{$filename}";
        Log::info("DEBUG: Path to check: {$path}");

        if (!Storage::exists($path)) {
            Log::error("DEBUG: Storage::exists returned false for {$path}");
            if (request()->wantsJson() || request()->ajax()) return response()->json(['success' => false, 'message' => 'File backup tidak ditemukan.'], 404);
            return back()->with('error', 'File backup tidak ditemukan.');
        }

        Log::info("DEBUG: Storage::exists returned true. Deleting {$path}");
        Storage::delete($path);
        Log::info("Backup dihapus secara sah oleh Super Admin ID " . auth()->id() . ": {$filename}");
        \App\Models\System\AuditLog::logSystemEvent('Hapus Backup Database', 'SystemBackup', ['filename' => $filename], null);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => "Backup {$filename} berhasil dihapus."]);
        }
        return back()->with('success', "Backup {$filename} berhasil dihapus.");
    }

    /**
     * Hapus banyak file backup sekaligus
     */
    public function bulkDestroy(Request $request)
    {
        $filenames = $request->input('filenames', []);
        Log::info("DEBUG: Entering bulkDestroy with filenames:", $filenames);
        
        if (empty($filenames) || !is_array($filenames)) {
            Log::warning("DEBUG: bulkDestroy received empty or invalid filenames");
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Tidak ada file yang dipilih.']);
            }
            return back()->with('error', 'Tidak ada file yang dipilih.');
        }

        $deletedCount = 0;
        foreach ($filenames as $filename) {
            Log::info("DEBUG: bulkDestroy checking filename: {$filename}");
            
            // Bypass WAF: Tambahkan .sql jika tidak ada
            if (!str_ends_with(strtolower($filename), '.sql')) {
                $filename .= '.sql';
            }

            if (str_contains($filename, '/') || str_contains($filename, '\\') || !str_ends_with(strtolower($filename), '.sql')) {
                Log::warning("DEBUG: bulkDestroy skipped {$filename} due to validation");
                continue;
            }

            $path = "{$this->backupDir}/{$filename}";
            Log::info("DEBUG: bulkDestroy Path to check: {$path}");
            if (Storage::exists($path)) {
                Log::info("DEBUG: bulkDestroy Storage::exists true, deleting {$path}");
                Storage::delete($path);
                $deletedCount++;
                Log::info("Backup dihapus secara sah (Bulk) oleh Super Admin ID " . auth()->id() . ": {$filename}");
            }
        }
        
        if ($deletedCount > 0) {
            \App\Models\System\AuditLog::logSystemEvent('Hapus Massal Backup Database', 'SystemBackup', ['count' => $deletedCount], null);
        }

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => "Berhasil menghapus {$deletedCount} file backup."
            ]);
        }
        
        return back()->with('success', "Berhasil menghapus {$deletedCount} file backup.");
    }

    /**
     * Ambil daftar file backup dengan klasifikasi tipe
     */
    protected function getBackupList(): array
    {
        if (!Storage::exists($this->backupDir)) {
            return [];
        }

        $files = Storage::files($this->backupDir);
        $backups = [];

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'sql' && Storage::exists($file)) {
                $filename = basename($file);
                
                $backups[] = (object) [
                    'filename'   => $filename,
                    'type'       => $this->classifyBackupType($filename),
                    'size'       => $this->formatFileSize(Storage::size($file)),
                    'size_bytes' => Storage::size($file),
                    'created_at' => date('Y-m-d H:i:s', Storage::lastModified($file)),
                ];
            }
        }

        // Urutkan: terbaru di atas
        usort($backups, fn($a, $b) => strcmp($b->created_at, $a->created_at));

        return $backups;
    }

    /**
     * Format ukuran file ke human readable
     */
    protected function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }

    /**
     * Determine the type of backup based on the filename prefix.
     */
    private function classifyBackupType(string $filename): string
    {
        if (str_starts_with($filename, 'backup_daily_')) return 'daily';
        if (str_starts_with($filename, 'backup_weekly_')) return 'weekly';
        if (str_starts_with($filename, 'backup_monthly_')) return 'monthly';
        return 'manual';
    }
}

