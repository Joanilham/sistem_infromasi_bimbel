<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtPeserta extends Model
{
    protected $table = 'cbt_pesertas';
    protected $guarded = [];

    protected $casts = [
        'waktu_mulai'   => 'datetime',
        'waktu_selesai' => 'datetime',
        'skor'          => 'decimal:2',
    ];

    public function ujian()
    {
        return $this->belongsTo(CbtUjian::class, 'cbt_ujian_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jawabans()
    {
        return $this->hasMany(CbtPesertaJawaban::class, 'cbt_peserta_id');
    }

    // ─── Scopes ────────────────────────────────────────────────────
    public function scopeSelesai($query)
    {
        return $query->whereIn('status', ['selesai', 'timeout']);
    }

    public function scopeSedangMengerjakan($query)
    {
        return $query->where('status', 'mengerjakan');
    }
}
