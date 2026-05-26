<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $cbt_bank_soal_id
 * @property string $teks_opsi
 * @property string|null $file_media
 * @property bool $is_benar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\CbtBankSoal $bankSoal
 */
use App\Traits\Auditable;

class CbtOpsiJawaban extends Model
{
    use Auditable;

    protected $table = 'cbt_opsi_jawabans';
    protected $guarded = [];

    protected $casts = [
        'is_benar' => 'boolean',
    ];

    public function bankSoal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CbtBankSoal::class, 'cbt_bank_soal_id');
    }
}
