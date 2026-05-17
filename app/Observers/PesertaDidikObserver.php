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
    }
    public function updated(PesertaDidik $pesertaDidik): void { $this->clearCache(); }
    public function deleted(PesertaDidik $pesertaDidik): void { $this->clearCache(); }
    public function restored(PesertaDidik $pesertaDidik): void { $this->clearCache(); }
    public function forceDeleted(PesertaDidik $pesertaDidik): void { $this->clearCache(); }
}
