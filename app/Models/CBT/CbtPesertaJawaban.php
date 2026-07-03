<?php

namespace App\Models\CBT;
use App\Models\CBT\CbtOpsiJawaban;
use App\Models\CBT\CbtPeserta;
use App\Models\CBT\CbtBankSoal;
use App\Models\CBT\CbtPesertaJawaban;

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
 * @property-read \App\Models\CBT\CbtPeserta $peserta
 * @property-read \App\Models\CBT\CbtBankSoal $bankSoal
 * @property-read \App\Models\CBT\CbtOpsiJawaban|null $opsiJawaban
 */
use App\Traits\Auditable;

class CbtPesertaJawaban extends Model
{
    use Auditable;

    protected $table = 'cbt_peserta_jawabans';
    protected $fillable = [
        'cbt_peserta_id', 'cbt_bank_soal_id', 'jawaban_essay',
        'cbt_opsi_jawaban_id', 'jawaban_multi', 'opsi_order',
        'ragu_ragu', 'is_benar', 'skor'
    ];

    protected $casts = [
        'jawaban_multi' => 'array',
        'opsi_order'    => 'array',
        'ragu_ragu'     => 'boolean',
        'is_benar'      => 'boolean',
        'skor'          => 'float',
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

    public function getJawabanTeksAttribute()
    {
        return $this->attributes['jawaban_essay'] ?? null;
    }

    public function setJawabanTeksAttribute($value)
    {
        $this->attributes['jawaban_essay'] = $value;
    }
}



