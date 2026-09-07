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
     * Kirim notifikasi error / exception ke Telegram.
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
        $env = config('app.env', 'production');
        $time = now()->format('Y-m-d H:i:s');
        $exceptionClass = get_class($e);
        $message = htmlspecialchars($e->getMessage() ?: 'No message', ENT_QUOTES, 'UTF-8');
        $file = $this->sanitizePath($e->getFile());
        $line = $e->getLine();

        // Data Request & Pengguna
        $requestInfo = 'CLI / Background Process';
        $userInfo = 'Tidak ada / Guest';
        $clientInfo = '-';

        if ($request && !app()->runningInConsole()) {
            $method = $request->method();
            $url = htmlspecialchars($request->fullUrl(), ENT_QUOTES, 'UTF-8');
            $requestInfo = "<code>{$method}</code> {$url}";

            if ($user = $request->user()) {
                $userName = htmlspecialchars($user->name ?? 'User', ENT_QUOTES, 'UTF-8');
                $userLevel = htmlspecialchars($user->level ?? '-', ENT_QUOTES, 'UTF-8');
                $userInfo = "{$userName} (ID: {$user->id}, Level: {$userLevel})";
            }

            $ip = $request->ip();
            $clientInfo = "IP: <code>{$ip}</code>";
        }

        // Stack trace ringkas (3 baris teratas)
        $traceSnippet = '';
        $traces = explode("\n", $e->getTraceAsString());
        $shortTrace = array_slice($traces, 0, 4);
        if (!empty($shortTrace)) {
            $traceSnippet = "\n\n🔍 <b>Trace:</b>\n<pre>" . htmlspecialchars(implode("\n", $shortTrace), ENT_QUOTES, 'UTF-8') . "</pre>";
        }

        $telegramMessage = "🚨 <b>[ERROR REPORT] {$appName}</b> ({$env})\n\n"
            . "⏰ <b>Waktu:</b> {$time}\n"
            . "⚠️ <b>Tipe:</b> <code>{$exceptionClass}</code>\n"
            . "📝 <b>Pesan:</b>\n<code>{$message}</code>\n\n"
            . "📍 <b>Lokasi:</b>\n<code>{$file}:{$line}</code>\n\n"
            . "🌐 <b>Request:</b> {$requestInfo}\n"
            . "👤 <b>Pengguna:</b> {$userInfo}\n"
            . "💻 <b>Klien:</b> {$clientInfo}"
            . $traceSnippet;

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
     * Bersihkan path absolut server agar lebih ringkas.
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
