<?php

namespace App\Models\Keuangan;
use App\Models\Akademik\PesertaDidik;
use App\Models\Keuangan\PembayaranSiswa;
use App\Models\Keuangan\TransaksiPembayaran;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Auditable;

class PembayaranSiswa extends Model
{
    use SoftDeletes, Auditable;

    protected $table = 'pembayaran_siswa';

    protected $fillable = [
        'peserta_didik_id',
        'diskon_persen',
        'diskon_nominal',
        'keterangan_diskon',
        'biaya_pendaftaran',
        'total_harus_dibayar',
        'batas_waktu',
        'dispensasi',
    ];

    protected $casts = [
        'batas_waktu'         => 'date',
        'diskon_persen'       => 'float',
        'diskon_nominal'      => 'integer',
        'biaya_pendaftaran'   => 'integer',
        'total_harus_dibayar' => 'integer',
        'dispensasi'          => 'boolean',
    ];

    public function pesertaDidik()
    {
        return $this->belongsTo(PesertaDidik::class, 'peserta_didik_id');
    }

    public function transaksi()
    {
        return $this->hasMany(TransaksiPembayaran::class, 'pembayaran_siswa_id');
    }

    /**
     * Total sudah dibayar (sum transaksi).
     */
    public function getTotalTerbayarAttribute(): int
    {
        return (int) $this->transaksi->where('status', 'SUKSES')->sum('nominal');
    }

    /**
     * Sisa tagihan. Positif = kurang bayar, Negatif = lebih bayar.
     */
    public function getKekuranganAttribute(): int
    {
        return $this->total_harus_dibayar - $this->total_terbayar;
    }

    public function getLunasAttribute(): bool
    {
        return $this->kekurangan <= 0;
    }
}


