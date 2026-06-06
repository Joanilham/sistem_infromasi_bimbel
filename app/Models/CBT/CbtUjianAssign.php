<?php

namespace App\Models\CBT;
use App\Models\CBT\CbtUjian;
use App\Models\CBT\CbtUjianAssign;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class CbtUjianAssign extends Model
{
    use Auditable;

    protected $table = 'cbt_ujian_assigns';
    protected $guarded = [];

    public function ujian()
    {
        return $this->belongsTo(CbtUjian::class, 'cbt_ujian_id');
    }
}


