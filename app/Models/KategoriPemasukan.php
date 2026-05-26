<?php

namespace App\Models;

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
