<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemasukan extends Model
{
    use SoftDeletes;

    protected $table = 'pemasukan';

    protected $fillable = ['tanggal', 'kategori_id', 'nominal', 'keterangan', 'user_id'];


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
