<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtMapel extends Model
{
    protected $table = 'cbt_mapels';
    protected $guarded = [];

    public function babs()
    {
        return $this->hasMany(CbtBab::class, 'cbt_mapel_id');
    }

    public function bankSoals()
    {
        return $this->hasMany(CbtBankSoal::class, 'cbt_mapel_id');
    }
}
