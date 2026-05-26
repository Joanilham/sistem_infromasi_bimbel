<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Bank extends Model
{
    use Auditable;

    protected $fillable = [
        'nama_bank',
        'nomor_rekening',
        'atas_nama',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}
