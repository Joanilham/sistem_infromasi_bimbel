<?php

namespace App\Models\CBT;
use App\Models\CBT\CbtBab;
use App\Models\CBT\CbtBankSoal;
use App\Models\CBT\CbtMapel;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nama
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CBT\CbtBab[] $babs
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CBT\CbtBankSoal[] $bankSoals
 */
use App\Traits\Auditable;

class CbtMapel extends Model
{
    use Auditable;

    protected $table = 'cbt_mapels';
    protected $fillable = [
        'nama'
    ];

    public function babs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CbtBab::class, 'cbt_mapel_id');
    }

    public function bankSoals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CbtBankSoal::class, 'cbt_mapel_id');
    }
}



