<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Master extends Model
{
    protected $fillable = [
        'nama_lembaga',
        'alamat_lembaga',
        'wa_url',
        'instance_id',
        'wa_token',
        'logo',
    ];
}
