<?php

namespace App\Models\System;
use App\Models\System\Gallery;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class Gallery extends Model
{
    use Auditable;

    protected $fillable = [
        'kantor_id',
        'judul',
        'foto',
        'kategori',
        'urutan',
    ];

    public function scopeInContext($query)
    {
        $kantorId = session('kantor_id');
        if (!$kantorId) return $query->whereRaw('1 = 0');
        return $query->where('kantor_id', $kantorId);
    }
}


