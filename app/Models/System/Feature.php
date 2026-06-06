<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = [
        'title',
        'description',
        'icon',
        'is_active',
        'order_num'
    ];
}
