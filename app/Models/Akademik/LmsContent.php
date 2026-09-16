<?php

namespace App\Models\Akademik;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LmsContent extends Model
{
    protected $fillable = [
        'guru_id',
        'subject',
        'kelompok_belajar_id',
        'type',
        'title',
        'description',
        'file_path',
        'file_name',
        'due_at',
    ];

    protected function casts(): array
    {
        return ['due_at' => 'datetime'];
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function kelompokBelajar(): BelongsTo
    {
        return $this->belongsTo(KelompokBelajar::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'tugas' ? 'Tugas' : 'Materi';
    }

    public function getSubjectLabelAttribute(): string
    {
        return $this->subject ?: ($this->guru?->matapelajaran ?: 'Mata Pelajaran Umum');
    }
}
