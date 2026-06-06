<?php

namespace App\Models\Akademik;
use App\Models\Akademik\PesertaDidik;
use App\Models\MasterData\Periode;
use App\Models\Akademik\KelompokBelajar;
use App\Models\MasterData\Kantor;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasContextScope;

use App\Traits\Auditable;

class KelompokBelajar extends Model
{
    protected $fillable = ['kantor_id', 'periode_id', 'nama_kelompok'];

    use HasContextScope, Auditable;

    // scopeInContext() disediakan oleh HasContextScope trait

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


