<?php

namespace App\Models\Akademik;
use App\Models\User;
use App\Models\Akademik\PesertaDidik;
use App\Models\Keuangan\PembayaranSiswa;
use App\Models\MasterData\Periode;
use App\Models\Akademik\PaketBimbingan;
use App\Models\Akademik\KelompokBelajar;
use App\Models\Akademik\Absensi;
use App\Models\MasterData\Kantor;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\HasContextScope;
use App\Traits\Auditable;

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
 * @property-read \App\Models\Akademik\KelompokBelajar|null $kelompokBelajar
 */
class PesertaDidik extends Model
{
    use HasContextScope, Auditable;

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

    protected static function booted()
    {
        static::created(function ($peserta) {
            // Automatically create default billing profile
            $peserta->pembayaran()->create([
                'total_harus_dibayar' => $peserta->paketBimbingan?->nominal ?? 0,
                'biaya_pendaftaran'   => 0,
                'diskon_persen'       => 0,
                'diskon_nominal'      => 0,
            ]);
        });
    }

    // ─── Scopes ────────────────────────────────────────────────────
    // scopeInContext() disediakan oleh HasContextScope trait

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

    /**
     * Hanya peserta yang sudah Lulus.
     */
    public function scopeLulus(Builder $query): Builder
    {
        return $query->where('status', 'Lulus');
    }

    // ─── Relationships ─────────────────────────────────────────────
    public function kantor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Kantor::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'peserta_didik_id');
    }

    public function periode(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }

    public function paketBimbingan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PaketBimbingan::class, 'paket_bimbingan_id')->withTrashed();
    }

    public function kelompokBelajar(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(KelompokBelajar::class, 'kelompok_belajar_id');
    }

    public function pembayaran(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PembayaranSiswa::class, 'peserta_didik_id');
    }

    public function absensi(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Absensi::class, 'peserta_didik_id');
    }

    /**
     * Get the payment and expiry status for the student.
     * Centralized logic to prevent duplication across controllers, middleware, and layouts.
     */
    public function getStatusPembayaran(): array
    {
        $pembayaran = $this->pembayaran()->first();
        
        $status = [
            'is_overdue' => false,
            'is_expiring' => false,
            'sisa_hari' => 0,
            'is_locked' => false,
            'kekurangan' => 0,
        ];

        if (!$pembayaran) {
            return $status;
        }

        $status['kekurangan'] = $pembayaran->kekurangan;

        // Admin dispensasi: if true, bypass all restrictions
        if ($pembayaran->dispensasi) {
            return $status;
        }

        if ($pembayaran->lunas) {
            return $status;
        }

        // 1. Overdue check
        if ($pembayaran->batas_waktu && $pembayaran->batas_waktu->isPast()) {
            $status['is_overdue'] = true;
            $status['is_locked'] = true;
        }

        // 2. Expiring package check (<= 30 days active duration remaining)
        if ($this->paketBimbingan) {
            $paket = $this->paketBimbingan;
            $durasiJumlah = $paket->durasi_jumlah;
            $durasiSatuan = strtolower($paket->durasi_satuan);
            $startDate = $this->created_at;
            $expiredDate = null;

            if ($durasiJumlah && $durasiSatuan) {
                if ($durasiSatuan === 'bulan') {
                    $expiredDate = $startDate->copy()->addMonths($durasiJumlah);
                } elseif ($durasiSatuan === 'tahun') {
                    $expiredDate = $startDate->copy()->addYears($durasiJumlah);
                }
            }

            if ($expiredDate) {
                $sisaHari = (int) now()->diffInDays($expiredDate, false);
                $status['sisa_hari'] = $sisaHari;
                if ($sisaHari <= 30) {
                    $status['is_expiring'] = true;
                    $status['is_locked'] = true;
                }
            }
        }

        return $status;
    }
}



