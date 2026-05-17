<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $nama_lengkap
 * @property string $nisn
 * @property string $jenis_kelamin
 * @property string|null $tempat_lahir
 * @property \Illuminate\Support\Carbon|null $tanggal_lahir
 * @property string|null $agama
 * @property string|null $alamat_lengkap
 * @property string $asal_sekolah
 * @property string|null $no_telepon
 * @property string|null $nama_ayah
 * @property string|null $nama_ibu
 * @property string|null $pekerjaan_ayah
 * @property string|null $pekerjaan_ibu
 * @property string|null $no_telepon_ayah
 * @property string|null $no_telepon_ibu
 * @property string|null $informasi_dari
 * @property int $paket_bimbingan_id
 * @property int|null $kelompok_belajar_id
 * @property string $status
 * @property int|null $total_hadir
 * @property int|null $total_izin
 * @property int|null $total_sakit
 * @property int|null $total_alpha
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\KelompokBelajar|null $kelompokBelajar
 */
class PesertaDidik extends Model
{
    protected $fillable = [
        'kantor_id',
        'periode_id',
        'nama_lengkap',
        'nisn',
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
        'kelompok_belajar_id',
        'status',
        'tanggal_keluar',
        'alasan_keluar',
    ];

    protected $casts = [
        'tanggal_lahir'  => 'date',
        'tanggal_keluar' => 'date',
    ];

    // ─── Scopes ────────────────────────────────────────────────────
    /**
     * Filter berdasarkan sessi Konteks (Kantor dan Periode).
     */
    public function scopeInContext(Builder $query): Builder
    {
        $kantorId = session('kantor_id');
        $periodeId = session('periode_id');

        // Jika tidak ada konteks di session, jangan tampilkan data apapun untuk keamanan
        // Kecuali jika memang sistem didesain untuk melihat data global (biasanya Super Admin)
        // Namun sesuai permintaan User, kita harus isolasi ketat.
        if (!$kantorId || !$periodeId) {
            return $query->whereRaw('1 = 0'); 
        }

        return $query->where('kantor_id', $kantorId)
                     ->where('periode_id', $periodeId);
    }

    /**
     * Hanya peserta dengan status Aktif.
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'Aktif');
    }

    /**
     * Hanya peserta yang sudah Keluar.
     */
    public function scopeKeluar(Builder $query): Builder
    {
        return $query->where('status', 'Keluar');
    }

    // ─── Relationships ─────────────────────────────────────────────
    public function kantor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Kantor::class);
    }

    public function periode(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }

    public function paketBimbingan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PaketBimbingan::class, 'paket_bimbingan_id');
    }

    public function kelompokBelajar(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(KelompokBelajar::class, 'kelompok_belajar_id');
    }

    public function pembayaran(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PembayaranSiswa::class, 'peserta_didik_id');
    }
}
