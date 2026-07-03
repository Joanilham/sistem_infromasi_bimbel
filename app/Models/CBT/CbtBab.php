<?php

namespace App\Models\CBT;
use App\Models\CBT\CbtBab;
use App\Models\CBT\CbtBankSoal;
use App\Models\CBT\CbtMapel;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $cbt_mapel_id
 * @property string $nama
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\CBT\CbtMapel $mapel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CBT\CbtBankSoal[] $bankSoals
 */
use App\Traits\Auditable;

class CbtBab extends Model
{
    use Auditable;

    protected $table = 'cbt_babs';
    protected $fillable = [
        'cbt_mapel_id', 'nama'
    ];

    public function mapel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CbtMapel::class, 'cbt_mapel_id');
    }

    public function bankSoals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CbtBankSoal::class, 'cbt_bab_id');
    }
}



