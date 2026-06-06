<?php

namespace App\Models\Keuangan;
use App\Models\Keuangan\Pemasukan;
use App\Models\Keuangan\KategoriPemasukan;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class KategoriPemasukan extends Model
{
    use Auditable;

    protected $table = 'kategori_pemasukan';
    protected $fillable = ['nama'];

    public function pemasukan()
    {
        return $this->hasMany(Pemasukan::class, 'kategori_id');
    }
}


