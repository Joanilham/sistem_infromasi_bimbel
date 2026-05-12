<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nama
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CbtBab[] $babs
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CbtBankSoal[] $bankSoals
 */
class CbtMapel extends Model
{
    protected $table = 'cbt_mapels';
    protected $guarded = [];

    public function babs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CbtBab::class, 'cbt_mapel_id');
    }

    public function bankSoals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CbtBankSoal::class, 'cbt_mapel_id');
    }
}
