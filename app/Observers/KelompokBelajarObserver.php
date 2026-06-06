<?php

namespace App\Observers;

use App\Models\Akademik\KelompokBelajar;
use Illuminate\Support\Facades\Cache;

class KelompokBelajarObserver
{
    private function clearCache()
    {
        Cache::forget('data_kelompok_belajars');
    }

    public function created(KelompokBelajar $kelompokBelajar): void { $this->clearCache(); }
    public function updated(KelompokBelajar $kelompokBelajar): void { $this->clearCache(); }
    public function deleted(KelompokBelajar $kelompokBelajar): void { $this->clearCache(); }
    public function restored(KelompokBelajar $kelompokBelajar): void { $this->clearCache(); }
    public function forceDeleted(KelompokBelajar $kelompokBelajar): void { $this->clearCache(); }
}

