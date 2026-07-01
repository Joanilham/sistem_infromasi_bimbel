<?php

namespace App\Models\Keuangan;
use App\Models\User;
use App\Models\Keuangan\Pemasukan;
use App\Models\Keuangan\KategoriPemasukan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Pemasukan extends Model
{
    use SoftDeletes, Auditable;

    protected $table = 'pemasukan';

    protected $fillable = ['kantor_id', 'tanggal', 'kategori_id', 'nominal', 'keterangan', 'user_id'];


    protected $casts = ['tanggal' => 'date', 'nominal' => 'integer'];

    public function kategori()
    {
        return $this->belongsTo(KategoriPemasukan::class, 'kategori_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


