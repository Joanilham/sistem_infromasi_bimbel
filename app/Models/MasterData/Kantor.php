<?php

namespace App\Models\MasterData;
use App\Models\MasterData\Kantor;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Auditable;

class Kantor extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = ['nama_kantor', 'alamat'];
}


