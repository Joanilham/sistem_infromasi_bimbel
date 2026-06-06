<?php

namespace App\Models\CBT;
use App\Models\User;
use App\Models\CBT\CbtPeserta;
use App\Models\CBT\CbtUjian;
use App\Models\CBT\CbtPesertaJawaban;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $cbt_ujian_id
 * @property int $user_id
 * @property string $status
 * @property \Illuminate\Support\Carbon $waktu_mulai
 * @property \Illuminate\Support\Carbon|null $waktu_selesai
 * @property float|null $skor
 * @property int $attempt_ke
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\CBT\CbtUjian $ujian
 * @property-read \App\Models\User $user
 */
use App\Traits\Auditable;

class CbtPeserta extends Model
{
    use Auditable;

    protected $table = 'cbt_pesertas';
    protected $guarded = [];

    protected $casts = [
        'waktu_mulai'   => 'datetime',
        'waktu_selesai' => 'datetime',
        'skor'          => 'float',
    ];

    public function ujian(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CbtUjian::class, 'cbt_ujian_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jawabans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CbtPesertaJawaban::class, 'cbt_peserta_id');
    }

    // ─── Scopes ────────────────────────────────────────────────────
    public function scopeSelesai($query)
    {
        return $query->whereIn('status', ['selesai', 'timeout']);
    }

    public function scopeSedangMengerjakan($query)
    {
        return $query->where('status', 'mengerjakan');
    }
}



