<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Master extends Model
{
    protected $fillable = [
        'nama_lembaga',
        'alamat_lembaga',
        'instance_id',
        'wa_token',
        'logo',
    ];
}
