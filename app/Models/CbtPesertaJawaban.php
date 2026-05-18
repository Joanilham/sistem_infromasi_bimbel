<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $cbt_peserta_id
 * @property int $cbt_bank_soal_id
 * @property string|null $jawaban_essay
 * @property int|null $cbt_opsi_jawaban_id
 * @property array|null $jawaban_multi
 * @property bool $ragu_ragu
 * @property bool|null $is_benar
 * @property float|null $skor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\CbtPeserta $peserta
 * @property-read \App\Models\CbtBankSoal $bankSoal
 * @property-read \App\Models\CbtOpsiJawaban|null $opsiJawaban
 */
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

    public function peserta(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CbtPeserta::class, 'cbt_peserta_id');
    }

    public function bankSoal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CbtBankSoal::class, 'cbt_bank_soal_id');
    }

    public function opsiJawaban(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CbtOpsiJawaban::class, 'cbt_opsi_jawaban_id');
    }
}
