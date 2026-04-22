<?php

namespace App\Observers;

use App\Models\PaketBimbingan;
use Illuminate\Support\Facades\Cache;

class PaketBimbinganObserver
{
    private function clearCache()
    {
        Cache::forget('dash_total_paket');
        Cache::forget('data_paket_bimbingans');
    }

    public function created(PaketBimbingan $paketBimbingan): void { $this->clearCache(); }
    public function updated(PaketBimbingan $paketBimbingan): void { $this->clearCache(); }
    public function deleted(PaketBimbingan $paketBimbingan): void { $this->clearCache(); }
    public function restored(PaketBimbingan $paketBimbingan): void { $this->clearCache(); }
    public function forceDeleted(PaketBimbingan $paketBimbingan): void { $this->clearCache(); }
}
