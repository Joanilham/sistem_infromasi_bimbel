<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketBimbingan extends Model
{
    protected $fillable = ['kantor_id', 'periode_id', 'nama_paket', 'nominal'];

    public function scopeInContext($query)
    {
        $kantorId = session('kantor_id');
        $periodeId = session('periode_id');

        if (!$kantorId || !$periodeId) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('kantor_id', $kantorId)
                     ->where('periode_id', $periodeId);
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
