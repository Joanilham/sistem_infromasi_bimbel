<?php

namespace App\Observers;

use App\Models\Kantor;
use Illuminate\Support\Facades\Cache;

class KantorObserver
{
    private function clearCache()
    {
        Cache::forget('dash_kantors');
        Cache::forget('global_kantors');
    }

    public function created(Kantor $kantor): void { $this->clearCache(); }
    public function updated(Kantor $kantor): void { $this->clearCache(); }
    public function deleted(Kantor $kantor): void { $this->clearCache(); }
    public function restored(Kantor $kantor): void { $this->clearCache(); }
    public function forceDeleted(Kantor $kantor): void { $this->clearCache(); }
}
