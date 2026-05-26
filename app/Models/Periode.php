<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Auditable;

class Periode extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = ['tahun_periode', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
