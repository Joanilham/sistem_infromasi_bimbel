<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class PendaftaranSiswa extends Model
{
    use Auditable;

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


    public function paketBimbingan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PaketBimbingan::class, 'paket_bimbingan_id')->withTrashed();
    }

    public function kelompokBelajar(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(KelompokBelajar::class, 'kelompok_belajar_id');
    }

    public function kantor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Kantor::class);
    }

    public function periode(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }

    public function pembayaran(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PembayaranPendaftaran::class);
    }
}
