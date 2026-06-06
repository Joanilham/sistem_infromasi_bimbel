<?php

namespace App\Models\CBT;
use App\Models\CBT\CbtUjianSoal;
use App\Models\CBT\CbtBankSoal;
use App\Models\CBT\CbtUjian;

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
 * @property-read \App\Models\CBT\CbtUjian $ujian
 * @property-read \App\Models\CBT\CbtBankSoal $bankSoal
 */
use App\Traits\Auditable;

class CbtUjianSoal extends Model
{
    use Auditable;

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



