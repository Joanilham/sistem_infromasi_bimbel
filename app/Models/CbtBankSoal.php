<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $cbt_mapel_id
 * @property int|null $cbt_bab_id
 * @property string $tipe_soal
 * @property string $tingkat_kesulitan
 * @property array|null $tags
 * @property string $pertanyaan
 * @property string|null $file_media
 * @property string|null $tipe_media
 * @property string $status
 * @property int $versi
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * 
 * @property-read \App\Models\CbtMapel|null $mapel
 * @property-read \App\Models\CbtBab|null $bab
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CbtOpsiJawaban[] $opsiJawabans
 */
class CbtBankSoal extends Model
{
    use SoftDeletes;

    protected $table = 'cbt_bank_soals';
    protected $guarded = [];

    protected $casts = [
        'tags' => 'array',
    ];

    // ─── Relationships ─────────────────────────────────────────────
    public function mapel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CbtMapel::class, 'cbt_mapel_id');
    }

    public function bab(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CbtBab::class, 'cbt_bab_id');
    }

    public function opsiJawabans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CbtOpsiJawaban::class, 'cbt_bank_soal_id');
    }

    public function pembahasan(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CbtPembahasan::class, 'cbt_bank_soal_id');
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ujianSoals(): \Illuminate\Database\Eloquent\Relations\HasMany
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
