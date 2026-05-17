<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPemasukan extends Model
{
    protected $table = 'kategori_pemasukan';
    protected $fillable = ['nama'];

    public function pemasukan()
    {
        return $this->hasMany(Pemasukan::class, 'kategori_id');
    }
}
