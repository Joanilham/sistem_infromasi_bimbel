<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ujian extends Model
{
    protected $fillable = [
        'guru_id',
        'bank_soal_id',
        'judul',
        'deskripsi',
        'status',
        'waktu_mulai',
        'waktu_selesai',
        'durasi',
        'acak_soal',
        'tampilkan_hasil',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'acak_soal' => 'boolean',
        'tampilkan_hasil' => 'boolean',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bankSoal(): BelongsTo
    {
        return $this->belongsTo(BankSoal::class);
    }

    public function hasil(): HasMany
    {
        return $this->hasMany(UjianHasil::class);
    }
}