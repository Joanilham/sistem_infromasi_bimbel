<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Akademik\PaketBimbingan;
use App\Models\Akademik\PesertaDidik;
use App\Models\Keuangan\Pemasukan;
use App\Models\Keuangan\PembayaranSiswa;
use App\Models\Keuangan\Pengeluaran;
use App\Models\Pendaftaran\PendaftaranSiswa;
use App\Models\User;
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
                    "⛔ <b>Akses Ditolak</b>\nID Telegram Anda (<code>{$chatId}</code>) tidak memiliki izin untuk mengontrol sistem.",
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
                case '/stats':
                case '/dashboard':
                    $this->sendAppStats($chatId);
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
                        "❓ Perintah tidak dikenali: <code>" . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . "</code>\n\nKetik /help untuk melihat daftar perintah yang tersedia.",
                        $chatId
                    );
                    break;
            }
        }

        // Return 200 OK ke Telegram Server
        return response()->json(['status' => 'ok']);
    }

    /**
     * Kirim menu bantuan.
     */
    private function sendHelp(int|string $chatId, string $name)
    {
        $appName = config('app.name', 'Genius Education');
        $msg = "🤖 <b>{$appName} - Assistant Bot</b>\n"
             . "━━━━━━━━━━━━━━━━━━━━\n"
             . "Halo, <b>{$name}</b>! Berikut perintah yang dapat Anda gunakan:\n\n"
             . "📊 <b>/status</b> — Pantau kesehatan server (CPU, RAM, Disk, DB)\n"
             . "📈 <b>/ringkasan</b> — Ringkasan data (Siswa, Guru, PPDB, Keuangan)\n"
             . "🗄 <b>/db</b> — Cek koneksi & ukuran database\n"
             . "⚡ <b>/ping</b> — Cek responsivitas sistem & latensi\n"
             . "🧹 <b>/clearcache</b> — Bersihkan cache aplikasi & view\n"
             . "❓ <b>/help</b> — Tampilkan menu bantuan ini\n\n"
             . "━━━━━━━━━━━━━━━━━━━━\n"
             . "<i>Sistem juga akan otomatis mengirim notifikasi instan jika terjadi error.</i>";

        $this->telegramService->sendMessage($msg, $chatId);
    }

    /**
     * Kirim telemetri kesehatan server.
     */
    private function sendServerStatus(int|string $chatId)
    {
        $appName = config('app.name', 'Genius Education');
        $time = now()->translatedFormat('d M Y, H:i:s') . ' WIB';

        // 1. Database Check
        $dbStatus = '❌ Terputus';
        $dbLatency = 0;
        try {
            $start = microtime(true);
            DB::connection()->getPdo();
            $dbLatency = round((microtime(true) - $start) * 1000, 2);
            $dbName = DB::connection()->getDatabaseName();
            $dbStatus = "🟢 Terhubung (<code>{$dbName}</code> | {$dbLatency}ms)";
        } catch (Throwable $e) {
            $dbStatus = '🔴 Gagal: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }

        // 2. Disk Usage
        $diskInfo = 'Tidak diketahui';
        try {
            $free = disk_free_space('/');
            $total = disk_total_space('/');
            if ($free && $total) {
                $used = $total - $free;
                $usedPercent = round(($used / $total) * 100, 1);
                $freeGb = round($free / (1024 ** 3), 2);
                $totalGb = round($total / (1024 ** 3), 2);
                $bar = $this->renderProgressBar($usedPercent);
                $icon = $usedPercent > 85 ? '🔴' : ($usedPercent > 70 ? '🟡' : '🟢');
                $diskInfo = "{$icon} {$usedPercent}% [{$bar}]\n   Sisa: <b>{$freeGb} GB</b> dari {$totalGb} GB";
            }
        } catch (Throwable $e) {}

        // 3. RAM Usage
        $ramInfo = 'Tidak diketahui';
        try {
            if (is_readable('/proc/meminfo')) {
                $meminfo = file_get_contents('/proc/meminfo');
                preg_match('/MemTotal:\s+(\d+)\s+kB/', $meminfo, $totalMatch);
                preg_match('/MemAvailable:\s+(\d+)\s+kB/', $meminfo, $availMatch);
                if (isset($totalMatch[1], $availMatch[1])) {
                    $memTotal = (int) $totalMatch[1] * 1024;
                    $memAvail = (int) $availMatch[1] * 1024;
                    $memUsed = $memTotal - $memAvail;
                    $ramPercent = round(($memUsed / $memTotal) * 100, 1);
                    $ramUsedGb = round($memUsed / (1024 ** 3), 2);
                    $ramTotalGb = round($memTotal / (1024 ** 3), 2);
                    $bar = $this->renderProgressBar($ramPercent);
                    $icon = $ramPercent > 85 ? '🔴' : ($ramPercent > 70 ? '🟡' : '🟢');
                    $ramInfo = "{$icon} {$ramPercent}% [{$bar}]\n   Terpakai: <b>{$ramUsedGb} GB</b> / {$ramTotalGb} GB";
                }
            } else {
                $phpMem = round(memory_get_usage(true) / 1024 / 1024, 2);
                $ramInfo = "PHP Memory: <b>{$phpMem} MB</b>";
            }
        } catch (Throwable $e) {}

        // 4. CPU Load
        $loadInfo = 'Tidak diketahui';
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            if ($load) {
                $icon = $load[0] > 4 ? '🔴' : ($load[0] > 2 ? '🟡' : '🟢');
                $loadInfo = "{$icon} 1m: <b>{$load[0]}</b> | 5m: <b>{$load[1]}</b> | 15m: <b>{$load[2]}</b>";
            }
        }

        // 5. Environment & Versions
        $phpVersion = PHP_VERSION;
        $laravelVersion = app()->version();
        $env = strtoupper(config('app.env', 'PRODUCTION'));

        $msg = "📊 <b>STATUS SERVER & SISTEM</b>\n"
             . "━━━━━━━━━━━━━━━━━━━━\n"
             . "🏫 <b>Aplikasi:</b> {$appName} [{$env}]\n"
             . "⏰ <b>Waktu Server:</b> {$time}\n\n"
             . "🗄 <b>Database MySQL:</b>\n{$dbStatus}\n\n"
             . "💾 <b>Penyimpanan Disk:</b>\n{$diskInfo}\n\n"
             . "🧠 <b>Penggunaan RAM:</b>\n{$ramInfo}\n\n"
             . "⚙️ <b>CPU Load Average:</b>\n{$loadInfo}\n\n"
             . "📦 <b>Versi Sistem:</b>\n"
             . "• PHP: <code>v{$phpVersion}</code>\n"
             . "• Laravel: <code>v{$laravelVersion}</code>\n"
             . "━━━━━━━━━━━━━━━━━━━━\n"
             . "🚀 <i>Gunakan /ringkasan untuk melihat statistik bimbel.</i>";

        $this->telegramService->sendMessage($msg, $chatId);
    }

    /**
     * Kirim ringkasan data statistik bimbel.
     */
    private function sendAppStats(int|string $chatId)
    {
        $appName = config('app.name', 'Genius Education');
        $time = now()->translatedFormat('d M Y, H:i:s') . ' WIB';

        try {
            $totalSiswaAktif = PesertaDidik::aktif()->count();
            $totalGuru = User::where('level', 'guru')->count();
            $totalPaket = PaketBimbingan::count();
            $pendaftaranMenunggu = PendaftaranSiswa::where('status', 'menunggu')->count();

            // Pemasukan & Pengeluaran Bulan Ini
            $startOfMonth = now()->startOfMonth()->toDateString();
            $endOfMonth = now()->endOfMonth()->toDateString();

            $pemasukanBulanIni = (float) Pemasukan::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->sum('nominal');
            $pengeluaranBulanIni = (float) Pengeluaran::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->sum('nominal');

            // Tagihan jatuh tempo
            $tagihanJatuhTempo = PembayaranSiswa::where('batas_waktu', '<=', now()->addDays(7))
                ->where('kekurangan', '>', 0)
                ->count();

            $formatRupiah = fn($val) => 'Rp ' . number_format($val, 0, ',', '.');

            $msg = "📈 <b>RINGKASAN STATISTIK BIMBEL</b>\n"
                 . "━━━━━━━━━━━━━━━━━━━━\n"
                 . "🏫 <b>Lembaga:</b> {$appName}\n"
                 . "⏰ <b>Per:</b> {$time}\n\n"
                 . "👥 <b>Data Akademik:</b>\n"
                 . "• Peserta Didik Aktif: <b>{$totalSiswaAktif} Siswa</b>\n"
                 . "• Tenaga Pengajar: <b>{$totalGuru} Guru</b>\n"
                 . "• Program Bimbingan: <b>{$totalPaket} Paket</b>\n\n"
                 . "📝 <b>Pendaftaran & Antrean:</b>\n"
                 . "• Pendaftar Menunggu: <b>{$pendaftaranMenunggu} Pendaftar</b> " . ($pendaftaranMenunggu > 0 ? '⚠️' : '✅') . "\n"
                 . "• Tagihan Jatuh Tempo (≤7 Hari): <b>{$tagihanJatuhTempo} Tagihan</b> " . ($tagihanJatuhTempo > 0 ? '⚠️' : '✅') . "\n\n"
                 . "💰 <b>Keuangan Bulan Ini (" . now()->translatedFormat('F Y') . "):</b>\n"
                 . "• Pemasukan: <b>" . $formatRupiah($pemasukanBulanIni) . "</b>\n"
                 . "• Pengeluaran: <b>" . $formatRupiah($pengeluaranBulanIni) . "</b>\n"
                 . "━━━━━━━━━━━━━━━━━━━━\n"
                 . "<i>Gunakan /status untuk mengecek kesehatan server.</i>";

            $this->telegramService->sendMessage($msg, $chatId);
        } catch (Throwable $e) {
            $this->telegramService->sendMessage(
                "❌ Gagal memuat statistik: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'),
                $chatId
            );
        }
    }

    /**
     * Kirim status spesifik database.
     */
    private function sendDatabaseStatus(int|string $chatId)
    {
        try {
            $dbName = DB::connection()->getDatabaseName();
            $driver = DB::connection()->getDriverName();

            // Ukuran database dalam MB
            $sizeResult = DB::select("
                SELECT table_schema AS 'db',
                       ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'size_mb',
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

            $msg = "🗄 <b>STATUS DATABASE MYSQL</b>\n"
                 . "━━━━━━━━━━━━━━━━━━━━\n"
                 . "• <b>Database:</b> <code>{$dbName}</code>\n"
                 . "• <b>Driver:</b> <code>{$driver}</code>\n"
                 . "• <b>Status:</b> 🟢 Terhubung Normal\n"
                 . "• <b>Ukuran Database:</b> <b>{$sizeMb} MB</b>\n"
                 . "• <b>Total Tabel:</b> <b>{$tablesCount} Tabel</b>\n"
                 . "• <b>Koneksi Aktif:</b> <b>{$activeConnections} Thread(s)</b>\n"
                 . "━━━━━━━━━━━━━━━━━━━━";

            $this->telegramService->sendMessage($msg, $chatId);
        } catch (Throwable $e) {
            $this->telegramService->sendMessage(
                "❌ Gagal mengambil status database: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'),
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
            $latency = round((microtime(true) - $start) * 1000, 2);
            $this->telegramService->sendMessage(
                "🏓 <b>Pong!</b>\n• Server Respons: 🟢 Aktif\n• DB Latency: <b>{$latency} ms</b>\n• Waktu: " . now()->format('H:i:s') . " WIB",
                $chatId
            );
        } catch (Throwable $e) {
            $this->telegramService->sendMessage(
                "🏓 <b>Pong!</b>\n• Server: 🟢 Aktif\n• DB: 🔴 Error (" . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . ")",
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
                "🧹 <b>Pembersihan Cache Berhasil!</b>\n"
                . "━━━━━━━━━━━━━━━━━━━━\n"
                . "✅ Application Cache dibersihkan\n"
                . "✅ Compiled Views dibersihkan\n"
                . "✅ Configuration Cache disegarkan\n"
                . "⏰ Waktu: " . now()->format('H:i:s') . " WIB\n"
                . "━━━━━━━━━━━━━━━━━━━━",
                $chatId
            );
        } catch (Throwable $e) {
            $this->telegramService->sendMessage(
                "❌ Gagal membersihkan cache: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'),
                $chatId
            );
        }
    }

    /**
     * Render ASCII progress bar.
     */
    private function renderProgressBar(float|int $percent, int $length = 10): string
    {
        $filled = (int) round(($percent / 100) * $length);
        $filled = max(0, min($length, $filled));
        $empty = $length - $filled;
        return str_repeat('█', $filled) . str_repeat('░', $empty);
    }
}
