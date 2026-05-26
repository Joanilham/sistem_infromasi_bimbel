<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait HasContextScope
 * 
 * Menyediakan Eloquent scope `inContext()` yang memfilter data 
 * berdasarkan `kantor_id` dan `periode_id` yang disimpan di session.
 * 
 * Digunakan oleh model yang memiliki kolom kantor_id dan periode_id.
 */
trait HasContextScope
{
    /**
     * Filter berdasarkan sessi Konteks (Kantor dan Periode).
     * Jika konteks belum di-set di session, mengembalikan query kosong (whereRaw 1=0).
     */
    public function scopeInContext(Builder $query): Builder
    {
        $kantorId = session('kantor_id');
        $periodeId = session('periode_id');

        if (!$periodeId) {
            return $query->whereRaw('1 = 0');
        }

        if ($kantorId !== 'all') {
            if (!$kantorId) {
                return $query->whereRaw('1 = 0');
            }
            $query->where('kantor_id', $kantorId);
        }

        return $query->where('periode_id', $periodeId);
    }
}
