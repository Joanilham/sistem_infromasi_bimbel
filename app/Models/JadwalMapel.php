<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\Auditable;

class JadwalMapel extends Model
{
    use Auditable;

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