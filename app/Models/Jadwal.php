<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Jadwal extends Model
{
    protected $fillable = [
        'guru_id',
        'rombel_id',
        'mata_pelajaran_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'kantor_id',
        'periode_id',
    ];

    // ── Daftar hari yang valid ──────────────────────────────────
    const HARI_LIST = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    // ── Scopes ──────────────────────────────────────────────────
    public function scopeInContext(Builder $query): Builder
    {
        if (session('kantor_id')) {
            $query->where('kantor_id', session('kantor_id'));
        }
        if (session('periode_id')) {
            $query->where('periode_id', session('periode_id'));
        }
        return $query;
    }

    // ── Relationships ───────────────────────────────────────────
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function rombel(): BelongsTo
    {
        return $this->belongsTo(KelompokBelajar::class, 'rombel_id');
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(CbtMapel::class, 'mata_pelajaran_id');
    }

    public function kantor(): BelongsTo
    {
        return $this->belongsTo(Kantor::class);
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }
}
