<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtPembahasan extends Model
{
    protected $table = 'cbt_pembahasans';
    protected $guarded = [];

    public function bankSoal()
    {
        return $this->belongsTo(CbtBankSoal::class, 'cbt_bank_soal_id');
    }
}
