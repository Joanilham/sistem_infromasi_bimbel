<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class MitraLogo extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'link',
        'is_active',
        'order_num'
    ];
}
