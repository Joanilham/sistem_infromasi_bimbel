<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    protected $casts = [
        'tanggal_lahir'  => 'date',
        'tanggal_keluar' => 'date',
    ];

    // ─── Scopes ────────────────────────────────────────────────────
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
    public function paketBimbingan()
    {
        return $this->belongsTo(PaketBimbingan::class, 'paket_bimbingan_id');
    }
}
