<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtUjianAssign extends Model
{
    protected $table = 'cbt_ujian_assigns';
    protected $guarded = [];

    public function ujian()
    {
        return $this->belongsTo(CbtUjian::class, 'cbt_ujian_id');
    }
}
