<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $pendaftaran_siswa_id
 * @property string $metode_pembayaran
 * @property float $jumlah
 * @property string|null $bukti_pembayaran
 * @property string $status
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\PendaftaranSiswa $pendaftaran
 * @property-read \App\Models\PendaftaranSiswa $pendaftaranSiswa
 */
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

    public function pendaftaran(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PendaftaranSiswa::class, 'pendaftaran_siswa_id');
    }

    // Alias untuk whereHas('pendaftaranSiswa', ...)
    public function pendaftaranSiswa(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PendaftaranSiswa::class, 'pendaftaran_siswa_id');
    }
}
