<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalMapel extends Model
{
    protected $fillable = [
        'guru_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kelas',
        'mapel',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}