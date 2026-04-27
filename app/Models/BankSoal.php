<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankSoal extends Model
{
    protected $fillable = [
        'guru_id',
        'judul',
        'deskripsi',
        'jumlah_soal',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function soals(): HasMany
    {
        return $this->hasMany(Soal::class);
    }
}