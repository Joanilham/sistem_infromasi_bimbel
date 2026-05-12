<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtOpsiJawaban extends Model
{
    protected $table = 'cbt_opsi_jawabans';
    protected $guarded = [];

    protected $casts = [
        'is_benar' => 'boolean',
    ];

    public function bankSoal()
    {
        return $this->belongsTo(CbtBankSoal::class, 'cbt_bank_soal_id');
    }
}
