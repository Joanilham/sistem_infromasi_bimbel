<?php

namespace App\Observers;

use App\Models\MasterData\Kantor;
use App\Models\MasterData\Periode;
use App\Models\Akademik\PaketBimbingan;
use Illuminate\Support\Facades\Cache;

class PaketBimbinganObserver
{
    private function clearCache(): void
    {
        // Cache landing page & daftar paket
        Cache::forget('data_paket_bimbingans');

        // Hapus cache dashboard untuk semua kombinasi kantor & periode yang ada
        // Karena PaketBimbingan bersifat Global (tidak terikat kantor/periode tertentu)
        $kantorIds = Kantor::pluck('id')->toArray();
        $periodeIds = Periode::pluck('id')->toArray();

        // Cache fallback tanpa konteks
        Cache::forget('dash_total_paket');

        // Cache per-konteks (format yang digunakan DashboardController)
        foreach ($kantorIds as $kId) {
            foreach ($periodeIds as $pId) {
                Cache::forget("dash_total_paket_{$kId}_{$pId}");
            }
        }
    }

    public function created(PaketBimbingan $paketBimbingan): void { $this->clearCache(); }
    public function updated(PaketBimbingan $paketBimbingan): void { $this->clearCache(); }
    public function deleted(PaketBimbingan $paketBimbingan): void { $this->clearCache(); }
    public function restored(PaketBimbingan $paketBimbingan): void { $this->clearCache(); }
    public function forceDeleted(PaketBimbingan $paketBimbingan): void { $this->clearCache(); }
}

