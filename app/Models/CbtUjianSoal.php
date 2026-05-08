<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtUjianSoal extends Model
{
    protected $table = 'cbt_ujian_soals';
    protected $guarded = [];

    public function ujian()
    {
        return $this->belongsTo(CbtUjian::class, 'cbt_ujian_id');
    }

    public function bankSoal()
    {
        return $this->belongsTo(CbtBankSoal::class, 'cbt_bank_soal_id');
    }
}
