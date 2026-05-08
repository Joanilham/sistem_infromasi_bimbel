<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtBab extends Model
{
    protected $table = 'cbt_babs';
    protected $guarded = [];

    public function mapel()
    {
        return $this->belongsTo(CbtMapel::class, 'cbt_mapel_id');
    }

    public function bankSoals()
    {
        return $this->hasMany(CbtBankSoal::class, 'cbt_bab_id');
    }
}
