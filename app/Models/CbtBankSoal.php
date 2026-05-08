<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CbtBankSoal extends Model
{
    use SoftDeletes;

    protected $table = 'cbt_bank_soals';
    protected $guarded = [];

    protected $casts = [
        'tags' => 'array',
    ];

    // ─── Relationships ─────────────────────────────────────────────
    public function mapel()
    {
        return $this->belongsTo(CbtMapel::class, 'cbt_mapel_id');
    }

    public function bab()
    {
        return $this->belongsTo(CbtBab::class, 'cbt_bab_id');
    }

    public function opsiJawabans()
    {
        return $this->hasMany(CbtOpsiJawaban::class, 'cbt_bank_soal_id');
    }

    public function pembahasan()
    {
        return $this->hasOne(CbtPembahasan::class, 'cbt_bank_soal_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ujianSoals()
    {
        return $this->hasMany(CbtUjianSoal::class, 'cbt_bank_soal_id');
    }

    // ─── Scopes ────────────────────────────────────────────────────
    public function scopeByGuru($query, $guruId)
    {
        return $query->where('created_by', $guruId);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // ─── Accessors ─────────────────────────────────────────────────
    public function getTipeSoalLabelAttribute()
    {
        return match ($this->tipe_soal) {
            'pg' => 'Pilihan Ganda',
            'essay' => 'Essay',
            'multi_correct' => 'Multi Jawaban',
            default => ucfirst($this->tipe_soal),
        };
    }

    public function getTingkatKesulitanLabelAttribute()
    {
        return match ($this->tingkat_kesulitan) {
            'easy' => 'Mudah',
            'medium' => 'Sedang',
            'hard' => 'Sulit',
            default => ucfirst($this->tingkat_kesulitan),
        };
    }

    public function getKunciJawabanAttribute()
    {
        return $this->opsiJawabans()->where('is_benar', true)->first();
    }
}
