<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $fillable = ['tahun_periode', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
