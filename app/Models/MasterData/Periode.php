<?php

namespace App\Models\MasterData;
use App\Models\MasterData\Periode;

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


