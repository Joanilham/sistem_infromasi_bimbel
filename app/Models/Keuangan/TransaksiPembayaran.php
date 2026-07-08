<?php

namespace App\Models\Keuangan;
use App\Models\User;
use App\Models\Keuangan\PembayaranSiswa;
use App\Models\MasterData\Bank;
use App\Models\Keuangan\TransaksiPembayaran;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Auditable;

class TransaksiPembayaran extends Model
{
    use SoftDeletes, Auditable;

    protected $table = 'transaksi_pembayaran';

    protected $fillable = [
        'pembayaran_siswa_id',
        'nominal',
        'tanggal',
        'tipe_pembayaran',
        'no_kwitansi',
        'penerima',
        'user_id',
        'status',
        'bukti_pembayaran',
        'catatan_siswa',
        'bank_tujuan_id',
        'alasan_penolakan',
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

    public function bankTujuan()
    {
        return $this->belongsTo(Bank::class, 'bank_tujuan_id');
    }

    /**
     * Generate no kwitansi: YYMMDD{urutan_harian}/{kode_user}
     */
    public static function generateNoKwitansi(string $kodeUser): string
    {
        $prefix = \Carbon\Carbon::now()->format('ymd');
        $count  = static::withTrashed()
                        ->where('no_kwitansi', 'like', $prefix . '%')
                        ->count() + 1;
        
        $noKwitansi = $prefix . str_pad($count, 3, '0', STR_PAD_LEFT) . '/' . strtolower($kodeUser);
        while(static::withTrashed()->where('no_kwitansi', $noKwitansi)->exists()) {
            $count++;
            $noKwitansi = $prefix . str_pad($count, 3, '0', STR_PAD_LEFT) . '/' . strtolower($kodeUser);
        }
        return $noKwitansi;
    }
}


