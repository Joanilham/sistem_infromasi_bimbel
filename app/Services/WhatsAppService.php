<?php

namespace App\Services;
use App\Models\System\Message;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\MasterData\Master;

class WhatsAppService
{
    protected $master;

    public function __construct()
    {
        $this->master = Master::first();
    }

    public function sendMessage($phone, $message)
    {
        if (!$this->master || !$this->master->wa_token) {
            return ['status' => 'error', 'message' => 'Token WhatsApp belum dikonfigurasi di Data Master.'];
        }

        $url = $this->master->wa_url;
        $token = $this->master->wa_token;
        $instanceId = $this->master->instance_id;

        // Format nomor, pastikan tidak ada karakter aneh dan ubah 08 ke 62 jika diperlukan oleh Fonnte
        // Fonnte bisa handle 08, namun untuk keamanan kita kirim as-is sesuai input.
        
        // Jika wa_url kosong, gunakan default fonnte API
        if (empty($url)) {
            $url = 'https://api.fonnte.com/send';
        }

        try {
            // Default Fonnte Payload format
            if (str_contains($url, 'fonnte.com')) {
                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->post($url, [
                    'target' => $phone,
                    'message' => $message,
                ]);
            } else {
                // Konfigurasi untuk Custom Gateway Standar (seperti Baileys / Evolution API)
                $payload = [
                    'number' => $phone,
                    'message' => $message,
                ];
                
                if ($instanceId) {
                    $payload['instance_id'] = $instanceId;
                }

                // Coba gunakan Authorization: Bearer Token, atau apikey header (disesuaikan dengan kebutuhan)
                // Disini kita mengirim Bearer Token
                $response = Http::withToken($token)
                                // Jika pakai Evolution API biasanya via JSON
                                ->asJson()
                                ->post($url, $payload);
            }

            if ($response->successful()) {
                return ['status' => 'success', 'data' => $response->json()];
            }

            Log::error('WhatsApp API Error: ' . escapeshellarg($response->body()));
            return ['status' => 'error', 'message' => 'API Error: ' . $response->status() . '. Cek log untuk detail.'];

        } catch (\Exception $e) {
            Log::error('WhatsApp API Exception: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()];
        }
    }

    public static function sendAsync($phone, $message)
    {
        if (empty($phone)) return;
        
        dispatch(function () use ($phone, $message) {
            try {
                (new self())->sendMessage($phone, $message);
            } catch (\Exception $e) {
                Log::error("Gagal kirim WA async: " . $e->getMessage());
            }
        })->afterResponse();
    }
}


