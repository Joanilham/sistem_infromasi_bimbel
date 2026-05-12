<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ujianSoals()
    {
        return $this->hasMany(CbtUjianSoal::class, 'cbt_ujian_id')->orderBy('urutan');
    }

    public function assigns()
    {
        return $this->hasMany(CbtUjianAssign::class, 'cbt_ujian_id');
    }

    public function pesertas()
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
}
