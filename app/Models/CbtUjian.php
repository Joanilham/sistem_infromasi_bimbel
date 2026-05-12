<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $judul
 * @property string|null $deskripsi
 * @property int $durasi
 * @property \Illuminate\Support\Carbon|null $waktu_mulai
 * @property \Illuminate\Support\Carbon|null $waktu_selesai
 * @property string $mode
 * @property bool $acak_soal
 * @property bool $acak_opsi
 * @property int $limit_attempt
 * @property string|null $token
 * @property bool $tampilkan_hasil
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CbtBankSoal[] $bankSoals
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CbtUjianSoal[] $ujianSoals
 */
class CbtUjian extends Model
{
    use SoftDeletes;

    protected $table = 'cbt_ujians';
    protected $guarded = [];

    protected $casts = [
        'acak_soal'         => 'boolean',
        'acak_opsi'         => 'boolean',
        'tampilkan_hasil'   => 'boolean',
        'waktu_mulai'       => 'datetime',
        'waktu_selesai'     => 'datetime',
    ];

    // ─── Relationships ─────────────────────────────────────────────
    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ujianSoals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CbtUjianSoal::class, 'cbt_ujian_id')->orderBy('urutan');
    }

    public function assigns(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CbtUjianAssign::class, 'cbt_ujian_id');
    }

    public function pesertas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CbtPeserta::class, 'cbt_ujian_id');
    }

    // ─── Scopes ────────────────────────────────────────────────────
    public function scopeAktif($query)
    {
        return $query->where('waktu_mulai', '<=', now())
                     ->where(function ($q) {
                         $q->whereNull('waktu_selesai')
                           ->orWhere('waktu_selesai', '>=', now());
                     });
    }

    public function scopeMendatang($query)
    {
        return $query->where('waktu_mulai', '>', now());
    }

    // ─── Accessors ─────────────────────────────────────────────────
    public function getIsAktifAttribute()
    {
        $now = now();
        return $this->waktu_mulai <= $now
            && ($this->waktu_selesai === null || $this->waktu_selesai >= $now);
    }

    public function getTotalSoalAttribute()
    {
        return $this->ujianSoals()->count();
    }

    public function canAttempt($userId)
    {
        $activeSession = $this->pesertas()
            ->where('user_id', $userId)
            ->where('status', 'mengerjakan')
            ->first();

        if ($activeSession) {
            return true;
        }

        if ($this->limit_attempt == 0) {
            return true;
        }

        $totalAttempts = $this->pesertas()
            ->where('user_id', $userId)
            ->count();

        return $totalAttempts < $this->limit_attempt;
    }
}
