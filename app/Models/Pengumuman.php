<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'isi',
        'foto',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
