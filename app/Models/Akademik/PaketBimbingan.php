<?php

namespace App\Models\Akademik;
use App\Models\MasterData\Periode;
use App\Models\Akademik\PaketBimbingan;
use App\Models\MasterData\Kantor;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class PaketBimbingan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kantor_id', 
        'periode_id', 
        'nama_paket', 
        'nominal', 
        'harga_coret',
        'dp_persen_minimal',
        'bisa_dicicil',
        'max_cicilan',
        'durasi_jumlah',
        'durasi_satuan',
        'deskripsi',
        'benefits',
        'target_peserta',
        'fasilitas',
        'gambar_paket', 
        'is_featured', 
        'label_populer',
        'urutan'
    ];

    // HasContextScope sudah dihapus agar paket benar-benar jadi milik Pusat (Global)
    use Auditable;

    public function kantor()
    {
        return $this->belongsTo(Kantor::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
}

