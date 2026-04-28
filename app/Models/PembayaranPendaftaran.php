<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranPendaftaran extends Model
{
    protected $fillable = [
        'pendaftaran_siswa_id',
        'metode_pembayaran',
        'jumlah',
        'bukti_pembayaran',
        'status',
        'catatan',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranSiswa::class, 'pendaftaran_siswa_id');
    }

    // Alias untuk whereHas('pendaftaranSiswa', ...)
    public function pendaftaranSiswa()
    {
        return $this->belongsTo(PendaftaranSiswa::class, 'pendaftaran_siswa_id');
    }
}
