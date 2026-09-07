<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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

        $appName = env('APP_NAME', 'Laravel');
        $hasWarning = false;
        
        // 1. Check Database Status
        $dbStatus = '❌ Terputus';
        try {
            DB::connection()->getPdo();
            $dbStatus = '✅ Terhubung (' . DB::connection()->getDatabaseName() . ')';
        } catch (\Exception $e) {
            $dbStatus = '❌ Gagal: ' . $e->getMessage();
            $hasWarning = true;
        }

        // 2. Check Disk Space
        $diskStatus = 'Tidak diketahui';
        try {
            $free = disk_free_space('/');
            $total = disk_total_space('/');
            if ($free && $total) {
                $freeGb = round($free / 1024 / 1024 / 1024, 2);
                $totalGb = round($total / 1024 / 1024 / 1024, 2);
                $percent = round(($total - $free) / $total * 100, 1);
                
                $icon = $percent > 85 ? '⚠️' : '✅';
                if ($percent > 85) $hasWarning = true;
                
                $diskStatus = "{$icon} {$freeGb}GB tersedia dari {$totalGb}GB (Terpakai: {$percent}%)";
            }
        } catch (\Exception $e) {
            // Ignore
        }

        // 3. System Load (if available)
        $loadStatus = 'Tidak diketahui';
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            if ($load) {
                $loadStatus = implode(', ', array_map(function($v) { return round($v, 2); }, $load));
                if ($load[0] > 5) $hasWarning = true; // CPU Load > 5
            }
        }

        // Jika mode alert-only aktif dan tidak ada warning, jangan kirim pesan
        if ($this->option('alert-only') && !$hasWarning) {
            $this->info('Semua sistem normal. Tidak ada notifikasi yang dikirim (Mode Alert-Only).');
            return;
        }

        $date = now()->format('Y-m-d H:i:s');
        $header = $hasWarning ? "🚨 <b>PERINGATAN SERVER {$appName}</b> 🚨" : "📊 <b>Laporan Harian Server {$appName}</b>";
        
        $message = "{$header}\n\n"
                 . "Waktu: {$date}\n\n"
                 . "💾 <b>Disk Space:</b>\n{$diskStatus}\n\n"
                 . "🗄 <b>Database:</b>\n{$dbStatus}\n\n"
                 . "⚙️ <b>CPU Load (1m, 5m, 15m):</b>\n{$loadStatus}\n\n";

        if (!$hasWarning) {
            $message .= "🚀 Sistem beroperasi secara normal.";
        } else {
            $message .= "⚠️ <b>Mohon segera periksa server Anda!</b>";
        }

        $res = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
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
