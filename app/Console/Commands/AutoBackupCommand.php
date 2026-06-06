<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AutoBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'db:auto-backup {--type=daily : Tipe backup: daily, weekly, monthly, manual} {--force : Abaikan status nonaktif di pengaturan}';

    /**
     * The console command description.
     */
    protected $description = 'Jalankan backup database otomatis (Pure PHP)';

    protected string $backupDir = 'backups';

    /**
     * Berkas pengaturan konfigurasi backup otomatis
     */
    protected string $settingsFile = 'backup_settings.json';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        if (!in_array($type, ['daily', 'weekly', 'monthly', 'manual'])) {
            $type = 'daily';
        }

        // Pengecekan status aktif/nonaktif dari pengaturan pengguna (kecuali jika dipaksa/force)
        if (!$this->option('force') && Storage::exists($this->settingsFile)) {
            $settings = json_decode(Storage::get($this->settingsFile), true);
            $enabledFreqs = $settings['backup_frequencies'] ?? ['daily'];
            
            if (!in_array($type, $enabledFreqs)) {
                $this->info("Backup otomatis tipe {$type} dinonaktifkan di pengaturan. Proses dibatalkan.");
                return Command::SUCCESS;
            }
        }

        $this->info("Memulai backup database {$type} otomatis...");
        
        try {
            Storage::makeDirectory($this->backupDir);

            $dbName = config('database.connections.mysql.database');
            $timestamp = now()->format('Y-m-d_H-i-s');
            
            // Nama berkas disanitasi dengan label tipe backup yang jelas
            $fileName = "backup_{$type}_" . preg_replace('/[^a-zA-Z0-9_-]/', '', $dbName) . "_{$timestamp}.sql";

            // Buat SQL Dump menggunakan PHP PDO
            $sqlContent = $this->generateSqlDump($type);
            
            // Simpan ke Storage yang terlindungi
            Storage::put("{$this->backupDir}/{$fileName}", $sqlContent);

            $sizeFormatted = $this->formatFileSize(strlen($sqlContent));
            
            $msg = "Backup DB otomatis ({$type}) berhasil dibuat: {$fileName} ({$sizeFormatted})";
            $this->info($msg);
            Log::info($msg);

            // Batasi jumlah file backup per tipe untuk rotasi penyimpanan mandiri
            $this->pruneOldBackups($type);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $errorMsg = "Gagal melakukan backup otomatis ({$type}): " . $e->getMessage();
            $this->error($errorMsg);
            Log::error($errorMsg);
            return Command::FAILURE;
        }
    }

    /**
     * Menghapus backup lama berdasarkan tipe untuk menghemat ruang penyimpanan
     */
    protected function pruneOldBackups(string $type)
    {
        if (!Storage::exists($this->backupDir)) {
            return;
        }

        $files = Storage::files($this->backupDir);
        $backups = [];

        foreach ($files as $file) {
            $filename = basename($file);
            // Hanya cocokkan file backup dengan tipe tertentu, contoh: backup_daily_...
            if (str_starts_with($filename, "backup_{$type}_") && str_ends_with(strtolower($filename), '.sql') && Storage::exists($file)) {
                $backups[] = [
                    'path' => $file,
                    'mtime' => Storage::lastModified($file),
                ];
            }
        }

        // Urutkan: terlama ke terbaru
        usort($backups, fn($a, $b) => $a['mtime'] - $b['mtime']);

        // Konfigurasi retensi per tipe backup
        $retentionLimits = [
            'daily'   => 7,  // Simpan 7 file harian terakhir
            'weekly'  => 4,  // Simpan 4 file mingguan terakhir
            'monthly' => 3,  // Simpan 3 file bulanan terakhir
            'manual'  => 5,  // Simpan 5 file manual terakhir
        ];

        $limit = $retentionLimits[$type] ?? 5;

        if (count($backups) > $limit) {
            $toDelete = count($backups) - $limit;
            for ($i = 0; $i < $toDelete; $i++) {
                Storage::delete($backups[$i]['path']);
                Log::info("Rotasi otomatis menghapus backup {$type} lama: " . basename($backups[$i]['path']));
            }
        }
    }

    /**
     * Generate SQL dump menggunakan pure PHP PDO
     */
    protected function generateSqlDump(string $type): string
    {
        $tables = [];
        $result = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $keyName = "Tables_in_{$dbName}";

        foreach ($result as $row) {
            $tables[] = $row->{$keyName};
        }

        $sql = "-- GeniusEdu Database Backup (" . strtoupper($type) . ")\n";
        $sql .= "-- Generated: " . now()->format('Y-m-d H:i:s') . "\n";
        $sql .= "-- Database: {$dbName}\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            // Drop table if exists
            $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";

            // Show Create Table
            $createTableResult = DB::select("SHOW CREATE TABLE `{$table}`");
            $createTableSql = $createTableResult[0]->{'Create Table'};
            $sql .= $createTableSql . ";\n\n";

            // Ambil data baris dengan chunking agar tidak habiskan memori untuk tabel besar
            $hasRows = false;
            DB::table($table)->orderBy(
                DB::getSchemaBuilder()->hasColumn($table, 'id') ? 'id' : DB::raw('1')
            )->chunk(500, function ($rows) use (&$sql, $table, &$hasRows) {
                if (!$hasRows) {
                    $sql .= "INSERT INTO `{$table}` VALUES \n";
                    $hasRows = true;
                }
                $inserts = [];
                foreach ($rows as $row) {
                    $values = [];
                    foreach ((array)$row as $column => $value) {
                        if (is_null($value)) {
                            $values[] = 'NULL';
                        } else {
                            $escaped = str_replace(
                                ["\\", "'", "\n", "\r", "\x1a"],
                                ["\\\\", "\\'", "\\n", "\\r", "\\Z"],
                                $value
                            );
                            $values[] = "'{$escaped}'";
                        }
                    }
                    $inserts[] = "(" . implode(', ', $values) . ")";
                }
                $sql .= implode(",\n", $inserts) . ";\n";
            });

            if ($hasRows) {
                $sql .= "\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        return $sql;
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
}
