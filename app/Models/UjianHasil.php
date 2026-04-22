<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UjianHasil extends Model
{
    protected $fillable = [
        'ujian_id',
        'peserta_didik_id',
        'skor',
        'nilai',
        'jawaban',
        'waktu_mulai',
        'waktu_selesai',
    ];

    protected $casts = [
        'jawaban' => 'array',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(Ujian::class);
    }

    public function pesertaDidik(): BelongsTo
    {
        return $this->belongsTo(User::class, 'peserta_didik_id');
    }
}