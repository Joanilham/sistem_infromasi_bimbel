<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Clear dashboard & peserta didik related caches
     */
    public static function clearPesertaCache(?int $kantorId = null, ?int $periodeId = null): void
    {
        $kantorId  ??= session('kantor_id');
        $periodeId ??= session('periode_id');

        // Selalu bersihkan cache fallback (tanpa suffix) untuk keamanan ganda
        Cache::forget('dash_total_peserta');
        Cache::forget('dash_peserta_baru');
        Cache::forget('data_peserta_didiks_aktif');
        Cache::forget('data_peserta_didiks_keluar');

        if (!$kantorId || !$periodeId) return;

        // Bersihkan cache dengan spesifik kantor_id dan periode_id
        Cache::forget("data_peserta_didiks_aktif_{$kantorId}_{$periodeId}");
        Cache::forget("data_peserta_didiks_keluar_{$kantorId}_{$periodeId}");
        Cache::forget("dash_total_peserta_{$kantorId}_{$periodeId}");
        Cache::forget("dash_peserta_baru_{$kantorId}_{$periodeId}");
        Cache::forget("dash_list_peserta_baru_{$kantorId}_{$periodeId}");
        Cache::forget("dash_peserta_keluar_{$kantorId}_{$periodeId}");
        Cache::forget("dash_list_peserta_keluar_{$kantorId}_{$periodeId}");
    }

    /**
     * Clear guru related caches
     */
    public static function clearGuruCache(?int $kantorId = null, ?int $periodeId = null): void
    {
        $kantorId  ??= session('kantor_id');
        $periodeId ??= session('periode_id');

        Cache::forget('data_gurus_aktif');
        Cache::forget('data_gurus_keluar');

        if (!$kantorId || !$periodeId) return;

        Cache::forget("data_gurus_aktif_{$kantorId}_{$periodeId}");
        Cache::forget("data_gurus_keluar_{$kantorId}_{$periodeId}");
    }
}

