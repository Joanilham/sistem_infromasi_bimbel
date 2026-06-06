<?php

namespace App\Models\Keuangan;
use App\Models\Keuangan\KategoriPengeluaran;
use App\Models\Keuangan\Pengeluaran;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class KategoriPengeluaran extends Model
{
    use Auditable;

    protected $table = 'kategori_pengeluaran';
    protected $fillable = ['nama'];

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'kategori_id');
    }
}


