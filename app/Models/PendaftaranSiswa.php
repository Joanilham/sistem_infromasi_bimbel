<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranSiswa extends Model
{
    protected $fillable = [
        'email', 'password',
        'email_verification_token', 'email_verified_at',
        'nama_lengkap', 'nisn', 'jenis_kelamin', 'tempat_lahir',
        'tanggal_lahir', 'agama', 'alamat_lengkap', 'no_telepon',
        'asal_sekolah', 'paket_bimbingan_id', 'kelompok_belajar_id', 'informasi_dari',
        'nama_ayah', 'pekerjaan_ayah', 'no_telepon_ayah',
        'nama_ibu', 'pekerjaan_ibu', 'no_telepon_ibu',
        'status', 'catatan_admin', 'kantor_id', 'periode_id',
    ];

    protected $casts = [
        'tanggal_lahir'     => 'date',
        'email_verified_at' => 'datetime',
    ];

    protected $hidden = ['password'];

    /** Apakah email sudah diverifikasi */
    public function isEmailVerified(): bool
    {
        return !is_null($this->email_verified_at);
    }


    public function paketBimbingan()
    {
        return $this->belongsTo(PaketBimbingan::class, 'paket_bimbingan_id');
    }

    public function kelompokBelajar()
    {
        return $this->belongsTo(KelompokBelajar::class, 'kelompok_belajar_id');
    }

    public function kantor()
    {
        return $this->belongsTo(Kantor::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(PembayaranPendaftaran::class);
    }
}
