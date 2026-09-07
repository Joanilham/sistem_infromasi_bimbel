<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Throwable;

class TelegramWebhookController extends Controller
{
    protected TelegramService $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    /**
     * Handle incoming webhook updates from Telegram API.
     */
    public function handle(Request $request)
    {
        $update = $request->all();

        // Pastikan update memiliki pesan teks
        if (isset($update['message']['text'])) {
            $text = trim($update['message']['text']);
            $chatId = $update['message']['chat']['id'];
            $fromName = $update['message']['from']['first_name'] ?? 'Admin';

            // Verifikasi otorisasi: Hanya chat_id yang terdaftar yang boleh mengeksekusi
            $authorizedChatId = config('services.telegram.chat_id', env('TELEGRAM_CHAT_ID'));
            if ((string) $chatId !== (string) $authorizedChatId) {
                $this->telegramService->sendMessage(
                    "⛔ <b>Akses Ditolak</b>\nID Telegram Anda (<code>{$chatId}</code>) tidak terdaftar.",
                    $chatId
                );
                return response()->json(['status' => 'unauthorized']);
            }

            // Normalisasi perintah (hilangkan suffix @botname jika ada)
            $command = strtolower(explode(' ', $text)[0]);
            $command = explode('@', $command)[0];

            switch ($command) {
                case '/start':
                case '/help':
                case '/bantuan':
                    $this->sendHelp($chatId, $fromName);
                    break;

                case '/status':
                case '/server':
                case '/health':
                    $this->sendServerStatus($chatId);
                    break;

                case '/ringkasan':
                case '/summary':
                case '/bimbel':
                    $this->sendBimbelSummary($chatId);
                    break;

                case '/db':
                case '/database':
                    $this->sendDatabaseStatus($chatId);
                    break;

                case '/ping':
                    $this->sendPing($chatId);
                    break;

                case '/clearcache':
                case '/clear_cache':
                case '/reload':
                    $this->sendClearCache($chatId);
                    break;

                default:
                    $this->telegramService->sendMessage(
                        "❓ Perintah tidak dikenali: <code>" . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . "</code>\n\nKetik /help untuk melihat menu.",
                        $chatId
                    );
                    break;
            }
        }

        // Return 200 OK ke Telegram Server
        return response()->json(['status' => 'ok']);
    }

    /**
     * Kirim menu bantuan ringkas & rapi untuk layar compact.
     */
    private function sendHelp(int|string $chatId, string $name)
    {
        $appName = config('app.name', 'Genius Education');
        $msg = "🤖 <b>{$appName} BOT</b>\n"
             . "Halo, <b>{$name}</b>!\n\n"
             . "📋 <b>Daftar Perintah:</b>\n"
             . "• <b>/status</b> — Cek kondisi server\n"
             . "• <b>/ringkasan</b> — Ringkasan data bimbel\n"
             . "• <b>/db</b> — Info database MySQL\n"
             . "• <b>/ping</b> — Latensi & respon server\n"
             . "• <b>/clearcache</b> — Bersihkan cache sistem\n"
             . "• <b>/help</b> — Menu bantuan ini\n\n"
             . "<i>💡 Bot otomatis mengirim alert jika web mengalami error.</i>";

        $this->telegramService->sendMessage($msg, $chatId);
    }

    /**
     * Kirim ringkasan data bimbel (siswa, guru, kelas, pendaftaran).
     */
    private function sendBimbelSummary(int|string $chatId)
    {
        $appName = config('app.name', 'Genius Education');
        $time = now()->translatedFormat('d M Y, H:i') . ' WIB';

        try {
            // 1. Siswa Aktif
            $siswaAktif = 0;
            if (class_exists(\App\Models\Akademik\PesertaDidik::class)) {
                $siswaAktif = \App\Models\Akademik\PesertaDidik::where('status', 'aktif')->count();
            }

            // 2. Guru / Tutor
            $totalGuru = 0;
            if (class_exists(\App\Models\User::class)) {
                $totalGuru = \App\Models\User::where('level', 'guru')->count();
            }

            // 3. Kelompok Belajar / Kelas
            $totalKelas = 0;
            if (class_exists(\App\Models\Akademik\KelompokBelajar::class)) {
                $totalKelas = \App\Models\Akademik\KelompokBelajar::count();
            }

            // 4. Pendaftaran Siswa Baru Bulan Ini
            $pendaftaranBulanIni = 0;
            if (class_exists(\App\Models\Pendaftaran\PendaftaranSiswa::class)) {
                $pendaftaranBulanIni = \App\Models\Pendaftaran\PendaftaranSiswa::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count();
            }

            $msg = "📚 <b>RINGKASAN BIMBEL</b>\n"
                 . "🏢 {$appName}\n\n"
                 . "👥 <b>Data Akademik:</b>\n"
                 . "• Siswa Aktif: <b>{$siswaAktif}</b> siswa\n"
                 . "• Guru / Tutor: <b>{$totalGuru}</b> guru\n"
                 . "• Rombel / Kelas: <b>{$totalKelas}</b> kelas\n"
                 . "• Daftar Bulan Ini: <b>{$pendaftaranBulanIni}</b> siswa\n\n"
                 . "⏰ {$time}";

            $this->telegramService->sendMessage($msg, $chatId);
        } catch (Throwable $e) {
            $this->telegramService->sendMessage(
                "❌ <b>Gagal memuat ringkasan</b>\n" . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'),
                $chatId
            );
        }
    }

    /**
     * Kirim telemetri kesehatan server dengan format ringkas & rapi untuk HP compact.
     */
    private function sendServerStatus(int|string $chatId)
    {
        $appName = config('app.name', 'Genius Education');
        $time = now()->translatedFormat('d M Y, H:i') . ' WIB';

        // 1. Uptime Server
        $uptimeInfo = 'Aktif';
        try {
            if (is_readable('/proc/uptime')) {
                $uptimeSecs = (int) floatval(explode(' ', file_get_contents('/proc/uptime'))[0]);
                $days = floor($uptimeSecs / 86400);
                $hours = floor(($uptimeSecs % 86400) / 3600);
                $minutes = floor(($uptimeSecs % 3600) / 60);

                $uptimeParts = [];
                if ($days > 0) $uptimeParts[] = "{$days} hari";
                if ($hours > 0) $uptimeParts[] = "{$hours} jam";
                $uptimeParts[] = "{$minutes} menit";
                $uptimeInfo = implode(' ', $uptimeParts);
            }
        } catch (Throwable $e) {}

        // 2. Database Check
        $dbStatus = '❌ Terputus';
        try {
            $start = microtime(true);
            DB::connection()->getPdo();
            $latency = round((microtime(true) - $start) * 1000, 1);
            $dbStatus = "🟢 Terhubung ({$latency} ms)";
        } catch (Throwable $e) {
            $dbStatus = '🔴 Error';
        }

        // 3. Disk Usage
        $diskPercent = '0%';
        $diskDetail = '-';
        try {
            $free = disk_free_space('/');
            $total = disk_total_space('/');
            if ($free && $total) {
                $used = $total - $free;
                $percent = round(($used / $total) * 100, 1);
                $freeGb = round($free / (1024 ** 3), 1);
                $totalGb = round($total / (1024 ** 3), 1);
                $icon = $percent > 85 ? '🔴' : ($percent > 70 ? '🟡' : '🟢');
                $diskPercent = "{$icon} <b>{$percent}%</b>";
                $diskDetail = "Sisa {$freeGb} GB dari {$totalGb} GB";
            }
        } catch (Throwable $e) {}

        // 4. RAM Usage
        $ramPercent = '0%';
        $ramDetail = '-';
        try {
            if (is_readable('/proc/meminfo')) {
                $meminfo = file_get_contents('/proc/meminfo');
                preg_match('/MemTotal:\s+(\d+)\s+kB/', $meminfo, $totalMatch);
                preg_match('/MemAvailable:\s+(\d+)\s+kB/', $meminfo, $availMatch);
                if (isset($totalMatch[1], $availMatch[1])) {
                    $memTotal = (int) $totalMatch[1] * 1024;
                    $memAvail = (int) $availMatch[1] * 1024;
                    $memUsed = $memTotal - $memAvail;
                    $percent = round(($memUsed / $memTotal) * 100, 1);
                    $usedGb = round($memUsed / (1024 ** 3), 2);
                    $totalGb = round($memTotal / (1024 ** 3), 2);
                    $icon = $percent > 85 ? '🔴' : ($percent > 70 ? '🟡' : '🟢');
                    $ramPercent = "{$icon} <b>{$percent}%</b>";
                    $ramDetail = "Pakai {$usedGb} GB / {$totalGb} GB";
                }
            } else {
                $phpMem = round(memory_get_usage(true) / 1024 / 1024, 1);
                $ramPercent = "PHP: <b>{$phpMem} MB</b>";
            }
        } catch (Throwable $e) {}

        // 5. Beban CPU
        $cpuStatus = '🟢 Normal';
        $cpu1 = '0.00';
        $cpu5 = '0.00';
        $cpu15 = '0.00';
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            if ($load) {
                $cpu1 = number_format($load[0], 2);
                $cpu5 = number_format($load[1], 2);
                $cpu15 = number_format($load[2], 2);

                if ($load[0] > 4.0) {
                    $cpuStatus = "🔴 Tinggi";
                } elseif ($load[0] > 2.0) {
                    $cpuStatus = "🟡 Sedang";
                } else {
                    $cpuStatus = "🟢 Normal";
                }
            }
        }

        // 6. Output Message (Sangat rapi, vertikal, anti-wrapping di layar compact)
        $phpVer = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
        $laravelVer = app()->version();

        $msg = "📊 <b>STATUS SERVER</b>\n"
             . "🏢 {$appName}\n\n"
             . "⏱ <b>Uptime:</b> 🟢 {$uptimeInfo}\n"
             . "📦 <b>Sistem:</b> PHP {$phpVer} • Laravel {$laravelVer}\n\n"
             . "🗄 <b>Database MySQL:</b>\n"
             . "• Status: {$dbStatus}\n\n"
             . "💾 <b>Penyimpanan Disk:</b>\n"
             . "• Penggunaan: {$diskPercent}\n"
             . "• {$diskDetail}\n\n"
             . "🧠 <b>Memori RAM:</b>\n"
             . "• Penggunaan: {$ramPercent}\n"
             . "• {$ramDetail}\n\n"
             . "⚙️ <b>Beban CPU:</b>\n"
             . "• Status: {$cpuStatus}\n"
             . "• 1 mnt: <b>{$cpu1}</b>\n"
             . "• 5 mnt: <b>{$cpu5}</b>\n"
             . "• 15 mnt: <b>{$cpu15}</b>\n\n"
             . "⏰ {$time}";

        $this->telegramService->sendMessage($msg, $chatId);
    }

    /**
     * Kirim status spesifik database.
     */
    private function sendDatabaseStatus(int|string $chatId)
    {
        try {
            $dbName = DB::connection()->getDatabaseName();

            // Ukuran database dalam MB
            $sizeResult = DB::select("
                SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'size_mb',
                       COUNT(table_name) AS 'total_tables'
                FROM information_schema.TABLES
                WHERE table_schema = ?
                GROUP BY table_schema
            ", [$dbName]);

            $sizeMb = $sizeResult[0]->size_mb ?? '0';
            $tablesCount = $sizeResult[0]->total_tables ?? '0';

            // Active Connections
            $threads = DB::select("SHOW STATUS WHERE Variable_name = 'Threads_connected'");
            $activeConnections = $threads[0]->Value ?? '1';

            $msg = "🗄 <b>DATABASE MYSQL</b>\n\n"
                 . "• Database: <code>{$dbName}</code>\n"
                 . "• Status: 🟢 Terhubung\n"
                 . "• Ukuran: <b>{$sizeMb} MB</b>\n"
                 . "• Total Tabel: <b>{$tablesCount}</b>\n"
                 . "• Koneksi Aktif: <b>{$activeConnections} Thread(s)</b>";

            $this->telegramService->sendMessage($msg, $chatId);
        } catch (Throwable $e) {
            $this->telegramService->sendMessage(
                "❌ Gagal membaca database: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'),
                $chatId
            );
        }
    }

    /**
     * Kirim respons ping sederhana.
     */
    private function sendPing(int|string $chatId)
    {
        $start = microtime(true);
        try {
            DB::connection()->getPdo();
            $latency = round((microtime(true) - $start) * 1000, 1);
            $this->telegramService->sendMessage(
                "🏓 <b>PONG!</b>\n\n• Server: 🟢 Aktif\n• Latensi DB: <b>{$latency} ms</b>\n• Jam: " . now()->format('H:i:s') . " WIB",
                $chatId
            );
        } catch (Throwable $e) {
            $this->telegramService->sendMessage(
                "🏓 <b>PONG!</b>\n\n• Server: 🟢 Aktif\n• DB: 🔴 Error",
                $chatId
            );
        }
    }

    /**
     * Bersihkan cache sistem via Telegram command.
     */
    private function sendClearCache(int|string $chatId)
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('config:clear');

            $this->telegramService->sendMessage(
                "🧹 <b>CACHE DIBERSIHKAN!</b>\n\n"
                . "✅ Cache Aplikasi\n"
                . "✅ Cache Views\n"
                . "✅ Cache Konfigurasi\n\n"
                . "⏰ " . now()->format('H:i:s') . " WIB",
                $chatId
            );
        } catch (Throwable $e) {
            $this->telegramService->sendMessage(
                "❌ Gagal membersihkan cache: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'),
                $chatId
            );
        }
    }
}
