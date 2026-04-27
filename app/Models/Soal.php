<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Soal extends Model
{
    protected $fillable = [
        'bank_soal_id',
        'pertanyaan',
        'tipe',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'jawaban_benar',
        'pembahasan',
        'poin',
    ];

    protected $casts = [
        'opsi_a' => 'array',
        'opsi_b' => 'array',
        'opsi_c' => 'array',
        'opsi_d' => 'array',
    ];

    public function bankSoal(): BelongsTo
    {
        return $this->belongsTo(BankSoal::class);
    }
}