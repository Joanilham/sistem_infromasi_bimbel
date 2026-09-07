<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Exception;
use Illuminate\Console\Command;

class TestTelegramErrorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:test-error';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim simulasi error ke Telegram Bot untuk pengujian notifikasi';

    /**
     * Execute the console command.
     */
    public function handle(TelegramService $telegramService)
    {
        $this->info('Mengirim simulasi exception ke Telegram...');

        try {
            // Sengaja lemparkan test exception
            throw new Exception('Ini adalah simulasi error pengujian notifikasi Telegram Bot Genius Education!');
        } catch (Exception $e) {
            $sent = $telegramService->sendExceptionNotification($e);

            if ($sent) {
                $this->info('✅ Berhasil! Notifikasi error telah dikirim ke Telegram.');
            } else {
                $this->error('❌ Gagal mengirim notifikasi error ke Telegram. Periksa TELEGRAM_BOT_TOKEN dan TELEGRAM_CHAT_ID di file .env');
            }
        }
    }
}
