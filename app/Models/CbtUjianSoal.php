<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $cbt_ujian_id
 * @property int $cbt_bank_soal_id
 * @property int $bobot
 * @property int|null $urutan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\CbtUjian $ujian
 * @property-read \App\Models\CbtBankSoal $bankSoal
 */
class CbtUjianSoal extends Model
{
    protected $table = 'cbt_ujian_soals';
    protected $guarded = [];

    public function ujian(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CbtUjian::class, 'cbt_ujian_id');
    }

    public function bankSoal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CbtBankSoal::class, 'cbt_bank_soal_id');
    }
}
