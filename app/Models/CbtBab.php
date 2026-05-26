<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $cbt_mapel_id
 * @property string $nama
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\CbtMapel $mapel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CbtBankSoal[] $bankSoals
 */
use App\Traits\Auditable;

class CbtBab extends Model
{
    use Auditable;

    protected $table = 'cbt_babs';
    protected $guarded = [];

    public function mapel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CbtMapel::class, 'cbt_mapel_id');
    }

    public function bankSoals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CbtBankSoal::class, 'cbt_bab_id');
    }
}
