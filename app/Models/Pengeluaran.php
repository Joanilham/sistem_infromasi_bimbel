<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran';

    protected $fillable = ['tanggal', 'kategori_id', 'nominal', 'keterangan', 'user_id'];


    protected $casts = ['tanggal' => 'date', 'nominal' => 'integer'];

    public function kategori()
    {
        return $this->belongsTo(KategoriPengeluaran::class, 'kategori_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
