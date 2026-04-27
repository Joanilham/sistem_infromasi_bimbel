<?php

namespace App\Observers;

use App\Models\PesertaDidik;
use Illuminate\Support\Facades\Cache;

class PesertaDidikObserver
{
    private function clearCache()
    {
        Cache::forget('dash_total_peserta');
        Cache::forget('dash_peserta_baru');
        Cache::forget('data_peserta_didiks_aktif');
        Cache::forget('data_peserta_didiks_keluar');
    }

    public function created(PesertaDidik $pesertaDidik): void 
    { 
        $this->clearCache(); 
        
        // Kirim WhatsApp Notification jika nomor handphone tersedia
        if (!empty($pesertaDidik->nomor_handphone)) {
            // Gunakan Queue jika memungkinkan agar tidak memperlambat response
            // Disini kita jalankan secara asynchronous menggunakan dispatch closure
            dispatch(function () use ($pesertaDidik) {
                try {
                    $pesan = "Halo {$pesertaDidik->nama_lengkap},\n\nSelamat datang di Bimbingan Belajar kami! Data pendaftaran Anda telah berhasil disimpan dalam sistem kami.\n\nTerima kasih.";
                    $waService = new \App\Services\WhatsAppService();
                    $waService->sendMessage($pesertaDidik->nomor_handphone, $pesan);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Gagal mengirim WA otomatis: ' . $e->getMessage());
                }
            })->afterResponse();
        }
    }
    public function updated(PesertaDidik $pesertaDidik): void { $this->clearCache(); }
    public function deleted(PesertaDidik $pesertaDidik): void { $this->clearCache(); }
    public function restored(PesertaDidik $pesertaDidik): void { $this->clearCache(); }
    public function forceDeleted(PesertaDidik $pesertaDidik): void { $this->clearCache(); }
}
