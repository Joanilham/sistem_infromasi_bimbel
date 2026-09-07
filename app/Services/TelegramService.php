<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class TelegramService
{
    protected ?string $token;
    protected ?string $chatId;

    public function __construct()
    {
        $this->token = config('services.telegram.bot_token', env('TELEGRAM_BOT_TOKEN'));
        $this->chatId = config('services.telegram.chat_id', env('TELEGRAM_CHAT_ID'));
    }

    /**
     * Kirim pesan teks ke Telegram.
     */
    public function sendMessage(string $message, ?string $chatId = null, string $parseMode = 'HTML'): bool
    {
        $targetChatId = $chatId ?: $this->chatId;

        if (!$this->token || !$targetChatId) {
            return false;
        }

        try {
            $response = Http::timeout(4)->post("https://api.telegram.org/bot{$this->token}/sendMessage", [
                'chat_id'                  => $targetChatId,
                'text'                     => $message,
                'parse_mode'               => $parseMode,
                'disable_web_page_preview' => true,
            ]);

            return $response->successful();
        } catch (Throwable $e) {
            Log::warning('Gagal mengirim pesan ke Telegram: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim notifikasi error / exception ke Telegram dengan format terstruktur dan rapi.
     */
    public function sendExceptionNotification(Throwable $e, ?Request $request = null): bool
    {
        if ($this->shouldIgnoreException($e)) {
            return false;
        }

        // Throttle / Debounce: Cegah spam jika error yang sama terjadi berulang kali dalam 60 detik
        $errorHash = 'tg_err_' . md5(get_class($e) . $e->getFile() . $e->getLine() . $e->getMessage());
        if (!Cache::add($errorHash, true, 60)) {
            return false;
        }

        $appName = config('app.name', 'Genius Education');
        $env = strtoupper(config('app.env', 'PRODUCTION'));
        $time = now()->translatedFormat('d M Y, H:i:s') . ' WIB';
        $exceptionClass = get_class($e);
        $message = htmlspecialchars($e->getMessage() ?: 'No exception message provided', ENT_QUOTES, 'UTF-8');
        $file = $this->sanitizePath($e->getFile());
        $line = $e->getLine();

        // Data Request & Pengguna
        $requestInfo = 'CLI / Background Process';
        $userInfo = 'Guest (Belum Login)';
        $clientInfo = '-';

        if ($request && !app()->runningInConsole()) {
            $method = $request->method();
            $url = htmlspecialchars($request->fullUrl(), ENT_QUOTES, 'UTF-8');
            $requestInfo = "<code>{$method}</code> {$url}";

            if ($user = $request->user()) {
                $userName = htmlspecialchars($user->name ?? 'User', ENT_QUOTES, 'UTF-8');
                $userLevel = htmlspecialchars($user->level ?? '-', ENT_QUOTES, 'UTF-8');
                $userInfo = "<b>{$userName}</b> (ID: <code>{$user->id}</code> | Level: <code>{$userLevel}</code>)";
            }

            $ip = $request->ip();
            $clientInfo = "IP: <code>{$ip}</code>";
        }

        // Stack trace ringkas (3 baris teratas yang relevan)
        $traceSnippet = '';
        $traces = explode("\n", $e->getTraceAsString());
        $shortTrace = array_slice($traces, 0, 3);
        if (!empty($shortTrace)) {
            $cleanedTrace = array_map(fn($t) => $this->sanitizePath($t), $shortTrace);
            $traceSnippet = "\n\n <b>Stack Trace (Top 3):</b>\n<pre>" . htmlspecialchars(implode("\n", $cleanedTrace), ENT_QUOTES, 'UTF-8') . "</pre>";
        }

        $telegramMessage = "<b>LAPORAN INSIDEN SISTEM</b>\n"
            . "━━━━━━━━━━━━━━━━━━━━\n"
            . " <b>Lembaga:</b> {$appName} [{$env}]\n"
            . " <b>Waktu:</b> {$time}\n\n"
            . " <b>Detail Kesalahan:</b>\n"
            . "• <b>Tipe:</b> <code>{$exceptionClass}</code>\n"
            . "• <b>Pesan:</b>\n<blockquote>{$message}</blockquote>\n"
            . "• <b>Lokasi:</b> <code>{$file}:{$line}</code>\n\n"
            . "  <b>Konteks Request:</b>\n"
            . "• <b>Endpoint:</b> {$requestInfo}\n"
            . "• <b>Pengguna:</b> {$userInfo}\n"
            . "• <b>Klien:</b> {$clientInfo}"
            . $traceSnippet
            . "\n━━━━━━━━━━━━━━━━━━━━\n"
            . "<i>🤖 Notifikasi otomatis dari Sistem Informasi {$appName}</i>";

        return $this->sendMessage($telegramMessage);
    }

    /**
     * Filter exception yang tidak perlu dikirim ke Telegram agar tidak menjadi spam.
     */
    protected function shouldIgnoreException(Throwable $e): bool
    {
        $ignoredExceptions = [
            \Illuminate\Validation\ValidationException::class,
            \Illuminate\Auth\AuthenticationException::class,
            \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
            \Illuminate\Session\TokenMismatchException::class,
            \Illuminate\Http\Exceptions\HttpResponseException::class,
            \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class,
            \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        ];

        foreach ($ignoredExceptions as $ignored) {
            if ($e instanceof $ignored) {
                return true;
            }
        }

        return false;
    }

    /**
     * Bersihkan path absolut server agar lebih ringkas dan rapi.
     */
    protected function sanitizePath(string $path): string
    {
        $base = base_path();
        if (str_starts_with($path, $base)) {
            return substr($path, strlen($base));
        }
        return $path;
    }
}
