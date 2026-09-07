<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Throwable;

class MonitorServerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:monitor {--alert-only : Hanya kirim pesan jika ada error/peringatan}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pantau kesehatan server dan laporkan ke Telegram';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        if (!$token || !$chatId) {
            $this->error('Telegram Token atau Chat ID belum dikonfigurasi.');
            return;
        }

        $appName = config('app.name', env('APP_NAME', 'Genius Education'));
        $hasWarning = false;

        // 1. Uptime Server
        $uptimeInfo = 'Tidak diketahui';
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
        
        // 2. Check Database Status
        $dbStatus = '❌ Terputus';
        try {
            DB::connection()->getPdo();
            $dbStatus = '🟢 Terhubung (' . DB::connection()->getDatabaseName() . ')';
        } catch (\Exception $e) {
            $dbStatus = '🔴 Gagal: ' . $e->getMessage();
            $hasWarning = true;
        }

        // 3. Check Disk Space
        $diskStatus = 'Tidak diketahui';
        try {
            $free = disk_free_space('/');
            $total = disk_total_space('/');
            if ($free && $total) {
                $used = $total - $free;
                $percent = round(($used / $total) * 100, 1);
                $freeGb = round($free / (1024 ** 3), 2);
                $totalGb = round($total / (1024 ** 3), 2);
                
                $icon = $percent > 85 ? '🔴' : ($percent > 70 ? '🟡' : '🟢');
                if ($percent > 85) $hasWarning = true;
                
                $diskStatus = "{$icon} {$percent}% (Sisa: {$freeGb} GB dari {$totalGb} GB)";
            }
        } catch (\Exception $e) {}

        // 4. System Load (Dibulatkan 2 desimal)
        $loadStatus = 'Tidak diketahui';
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            if ($load) {
                $l1 = number_format($load[0], 2);
                $l5 = number_format($load[1], 2);
                $l15 = number_format($load[2], 2);

                if ($load[0] > 4.0) {
                    $statusText = "🔴 Tinggi";
                    $hasWarning = true;
                } elseif ($load[0] > 2.0) {
                    $statusText = "🟡 Sedang";
                } else {
                    $statusText = "🟢 Normal";
                }

                $loadStatus = "{$statusText} (1m: {$l1} | 5m: {$l5} | 15m: {$l15})";
            }
        }

        // Jika mode alert-only aktif dan tidak ada warning, jangan kirim pesan
        if ($this->option('alert-only') && !$hasWarning) {
            $this->info('Semua sistem normal. Tidak ada notifikasi yang dikirim (Mode Alert-Only).');
            return;
        }

        $date = now()->translatedFormat('d M Y, H:i:s') . ' WIB';
        $header = $hasWarning ? "🚨 <b>PERINGATAN KESEHATAN SERVER {$appName}</b> 🚨" : "📊 <b>Laporan Status Server {$appName}</b>";
        
        $message = "{$header}\n"
                 . "━━━━━━━━━━━━━━━━━━━━\n"
                 . "⏰ <b>Waktu:</b> {$date}\n"
                 . "⏱ <b>Uptime:</b> 🟢 Aktif {$uptimeInfo}\n\n"
                 . "💾 <b>Penyimpanan Disk:</b>\n{$diskStatus}\n\n"
                 . "🗄 <b>Database MySQL:</b>\n{$dbStatus}\n\n"
                 . "⚙️ <b>Rata-rata Beban CPU:</b>\n{$loadStatus}\n"
                 . "━━━━━━━━━━━━━━━━━━━━\n";

        if (!$hasWarning) {
            $message .= "🚀 <i>Semua sistem beroperasi secara normal.</i>";
        } else {
            $message .= "⚠️ <b>Mohon segera periksa server Anda!</b>";
        }

        $res = Http::timeout(5)->post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML',
        ]);

        if (!$res->successful()) {
            $this->error('Gagal mengirim ke Telegram: ' . $res->body());
        } else {
            $this->info('Laporan berhasil dikirim ke Telegram.');
        }
    }
}
