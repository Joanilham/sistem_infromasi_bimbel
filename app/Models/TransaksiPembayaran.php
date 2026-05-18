<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiPembayaran extends Model
{
    use SoftDeletes;

    protected $table = 'transaksi_pembayaran';

    protected $fillable = [
        'pembayaran_siswa_id',
        'nominal',
        'tanggal',
        'tipe_pembayaran',
        'no_kwitansi',
        'penerima',
        'user_id',
    ];

    protected $casts = [
        'tanggal'  => 'date',
        'nominal'  => 'integer',
        'tipe_pembayaran' => 'string',
    ];

    public function pembayaranSiswa()
    {
        return $this->belongsTo(PembayaranSiswa::class, 'pembayaran_siswa_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate no kwitansi: YYMMDD{urutan_harian}/{kode_user}
     */
    public static function generateNoKwitansi(string $kodeUser): string
    {
        $prefix = Carbon::now()->format('ymd');
        $count  = static::whereDate('created_at', today())
                        ->count() + 1;
        return $prefix . str_pad($count, 3, '0', STR_PAD_LEFT) . '/' . strtolower($kodeUser);
    }
}
