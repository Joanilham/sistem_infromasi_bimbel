<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketBimbingan extends Model
{
    protected $fillable = ['kantor_id', 'periode_id', 'nama_paket', 'nominal'];

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

    public function kantor()
    {
        return $this->belongsTo(Kantor::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
}
