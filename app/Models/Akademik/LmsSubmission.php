<?php

namespace App\Models\Akademik;

use App\Models\User;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LmsSubmission extends Model
{
    use Auditable;

    protected $fillable = [
        'lms_content_id',
        'peserta_didik_id',
        'nisn',
        'file_path',
        'file_name',
        'catatan_siswa',
        'submitted_at',
        'is_late',
        'status',
        'nilai',
        'feedback_guru',
        'graded_at',
        'graded_by',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'graded_at'    => 'datetime',
            'is_late'      => 'boolean',
            'nilai'        => 'float',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────────

    public function content(): BelongsTo
    {
        return $this->belongsTo(LmsContent::class, 'lms_content_id');
    }

    public function pesertaDidik(): BelongsTo
    {
        return $this->belongsTo(PesertaDidik::class, 'peserta_didik_id');
    }

    public function penilai(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    // ─── Helpers & Accessors ───────────────────────────────────────

    public function isGraded(): bool
    {
        return $this->status === 'graded' || $this->nilai !== null;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'graded'      => 'Sudah Dinilai',
            'resubmitted' => 'Terkumpul Ulang',
            default       => 'Menunggu Dinilai',
        };
    }
}
