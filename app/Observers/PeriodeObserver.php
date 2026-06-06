<?php

namespace App\Observers;

use App\Models\MasterData\Periode;
use Illuminate\Support\Facades\Cache;

class PeriodeObserver
{
    private function clearCache()
    {
        Cache::forget('dash_periodes');
        Cache::forget('global_periodes');
    }

    public function created(Periode $periode): void { $this->clearCache(); }
    public function updated(Periode $periode): void { $this->clearCache(); }
    public function deleted(Periode $periode): void { $this->clearCache(); }
    public function restored(Periode $periode): void { $this->clearCache(); }
    public function forceDeleted(Periode $periode): void { $this->clearCache(); }
}

