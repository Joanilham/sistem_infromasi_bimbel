<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelompokBelajar extends Model
{
    protected $fillable = ['kantor_id', 'periode_id', 'nama_kelompok'];

    public function scopeInContext($query)
    {
        if (session('kantor_id')) {
            $query->where('kantor_id', session('kantor_id'));
        }
        if (session('periode_id')) {
            $query->where('periode_id', session('periode_id'));
        }
        return $query;
    }

    // ─── Relationships ─────────────────────────────────────────────
    public function pesertaDidiks()
    {
        return $this->hasMany(PesertaDidik::class, 'kelompok_belajar_id');
    }

    public function kantor()
    {
        return $this->belongsTo(Kantor::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
}
