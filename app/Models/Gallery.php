<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
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
