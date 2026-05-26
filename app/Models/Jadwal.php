<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\HasContextScope;

use App\Traits\Auditable;

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
    const HARI_LIST = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    use HasContextScope, Auditable;

    // ── Scopes ──────────────────────────────────────────────────
    // scopeInContext() disediakan oleh HasContextScope trait

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
