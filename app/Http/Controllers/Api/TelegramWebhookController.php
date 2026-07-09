<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $update = $request->all();

        // Ensure this is a message
        if (isset($update['message']['text'])) {
            $text = $update['message']['text'];
            $chatId = $update['message']['chat']['id'];

            // Respond only if the command is /status
            if ($text === '/status') {
                $this->sendStatus($chatId);
            }
        }

        // Always return 200 OK so Telegram knows we received it
        return response()->json(['status' => 'ok']);
    }

    private function sendStatus($chatId)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        if (!$token) return;

        $appName = env('APP_NAME', 'Laravel');
        
        // 1. Check Database Status
        $dbStatus = '❌ Terputus';
        try {
            DB::connection()->getPdo();
            $dbStatus = '✅ Terhubung (' . DB::connection()->getDatabaseName() . ')';
        } catch (\Exception $e) {
            $dbStatus = '❌ Gagal: ' . $e->getMessage();
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
            }
        }

        $date = now()->format('Y-m-d H:i:s');
        
        $message = "📊 *Status Server {$appName}*\n\n"
                 . "Waktu: {$date}\n\n"
                 . "💾 *Disk Space:*\n{$diskStatus}\n\n"
                 . "🗄 *Database:*\n{$dbStatus}\n\n"
                 . "⚙️ *CPU Load (1m, 5m, 15m):*\n{$loadStatus}\n\n"
                 . "🚀 Sistem beroperasi secara normal.";

        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown',
        ]);
    }
}
