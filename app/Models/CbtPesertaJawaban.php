<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtPesertaJawaban extends Model
{
    protected $table = 'cbt_peserta_jawabans';
    protected $guarded = [];

    protected $casts = [
        'jawaban_multi' => 'array',
        'opsi_order'    => 'array',
        'ragu_ragu'     => 'boolean',
        'is_benar'      => 'boolean',
        'skor'          => 'decimal:2',
    ];

    public function peserta()
    {
        return $this->belongsTo(CbtPeserta::class, 'cbt_peserta_id');
    }

    public function bankSoal()
    {
        return $this->belongsTo(CbtBankSoal::class, 'cbt_bank_soal_id');
    }

    public function opsiJawaban()
    {
        return $this->belongsTo(CbtOpsiJawaban::class, 'cbt_opsi_jawaban_id');
    }
}
