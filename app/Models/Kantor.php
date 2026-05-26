<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Auditable;

class Kantor extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = ['nama_kantor', 'alamat'];
}
