<?php

namespace App\Observers;

use App\Models\PesertaDidik;
use Illuminate\Support\Facades\Cache;

class PesertaDidikObserver
{
    private function clearCache(PesertaDidik $pesertaDidik)
    {
        \App\Services\CacheService::clearPesertaCache($pesertaDidik->kantor_id, $pesertaDidik->periode_id);
    }

    public function created(PesertaDidik $pesertaDidik): void 
    { 
        $this->clearCache($pesertaDidik); 
    }

    public function updated(PesertaDidik $pesertaDidik): void 
    { 
        $this->clearCache($pesertaDidik); 

        // Sinkronisasi data user jika ada
        $user = \App\Models\User::where('peserta_didik_id', $pesertaDidik->id)->first();
        if ($user) {
            $user->update([
                'status'    => $pesertaDidik->status,
                'is_active' => ($pesertaDidik->status === 'Aktif'),
            ]);
        }
    }

    public function deleted(PesertaDidik $pesertaDidik): void 
    { 
        $this->clearCache($pesertaDidik); 

        // Nonaktifkan user jika peserta didik dihapus
        $user = \App\Models\User::where('peserta_didik_id', $pesertaDidik->id)->first();
        if ($user) {
            $user->update([
                'is_active' => false,
            ]);
        }
    }

    public function restored(PesertaDidik $pesertaDidik): void 
    { 
        $this->clearCache($pesertaDidik); 

        // Aktifkan kembali user sesuai status peserta didik saat direstore
        $user = \App\Models\User::where('peserta_didik_id', $pesertaDidik->id)->first();
        if ($user) {
            $user->update([
                'status'    => $pesertaDidik->status,
                'is_active' => ($pesertaDidik->status === 'Aktif'),
            ]);
        }
    }

    public function forceDeleted(PesertaDidik $pesertaDidik): void 
    { 
        $this->clearCache($pesertaDidik); 
        
        // Hapus permanen user jika peserta didik dihapus permanen
        $user = \App\Models\User::where('peserta_didik_id', $pesertaDidik->id)->first();
        if ($user) {
            $user->delete();
        }
    }
}
