<?php

namespace App\Models\Akademik;
use App\Models\Akademik\PesertaDidik;
use App\Models\Akademik\Absensi;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class Absensi extends Model
{
    use Auditable;

    protected $fillable = [
        'peserta_didik_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status_masuk',
        'wa_masuk_sent',
        'wa_pulang_sent',
        'keterangan',
    ];

    protected $casts = [
        'tanggal'         => 'date',
        'wa_masuk_sent'   => 'boolean',
        'wa_pulang_sent'  => 'boolean',
    ];

    public function pesertaDidik()
    {
        return $this->belongsTo(PesertaDidik::class, 'peserta_didik_id');
    }
}


