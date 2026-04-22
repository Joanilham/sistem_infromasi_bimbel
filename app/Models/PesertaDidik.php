<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
        if (session('kantor_id')) {
            $query->where('kantor_id', session('kantor_id'));
        }
        if (session('periode_id')) {
            $query->where('periode_id', session('periode_id'));
        }
        return $query;
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
    public function kantor()
    {
        return $this->belongsTo(Kantor::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function paketBimbingan()
    {
        return $this->belongsTo(PaketBimbingan::class, 'paket_bimbingan_id');
    }

    public function kelompokBelajar()
    {
        return $this->belongsTo(KelompokBelajar::class, 'kelompok_belajar_id');
    }
}
