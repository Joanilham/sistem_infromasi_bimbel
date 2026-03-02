<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaDidik extends Model
{
    protected $fillable = [
        'nama_lengkap',
        'nomor_induk',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat_lengkap',
        'asal_sekolah',
        'no_telepon',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'no_telepon_ayah',
        'no_telepon_ibu',
        'informasi_dari',
        'paket_bimbingan_id',
        'kelompok_belajar',
        'status',
        'tanggal_keluar',
        'alasan_keluar',
    ];

    public function paketBimbingan()
    {
        return $this->belongsTo(PaketBimbingan::class, 'paket_bimbingan_id');
    }
}
