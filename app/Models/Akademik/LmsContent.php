<?php

namespace App\Models\Akademik;

use App\Models\MasterData\Kantor;
use App\Models\MasterData\Periode;
use App\Models\User;
use App\Traits\Auditable;
use App\Traits\HasContextScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class LmsContent extends Model
{
    use HasContextScope, Auditable;

    protected $fillable = [
        'kantor_id',
        'periode_id',
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
        return [
            'due_at' => 'datetime',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────────

    public function kantor(): BelongsTo
    {
        return $this->belongsTo(Kantor::class);
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function kelompokBelajar(): BelongsTo
    {
        return $this->belongsTo(KelompokBelajar::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(LmsSubmission::class, 'lms_content_id');
    }

    public function completions(): HasMany
    {
        return $this->hasMany(LmsCompletion::class, 'lms_content_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeMateri(Builder $query): Builder
    {
        return $query->where('type', 'materi');
    }

    public function scopeTugas(Builder $query): Builder
    {
        return $query->where('type', 'tugas');
    }

    // ─── Accessors & Helpers ──────────────────────────────────────

    public function isAssignment(): bool
    {
        return $this->type === 'tugas';
    }

    public function isMaterial(): bool
    {
        return $this->type === 'materi';
    }

    public function isOverdue(): bool
    {
        return $this->due_at && $this->due_at->isPast();
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'tugas' ? 'Tugas' : 'Materi';
    }

    public function getSubjectLabelAttribute(): string
    {
        return $this->subject ?: ($this->guru?->matapelajaran ?: 'Mata Pelajaran Umum');
    }

    public function getSubmissionFor($pesertaDidik): ?LmsSubmission
    {
        if (!Schema::hasTable('lms_submissions')) {
            return null;
        }

        $id = $pesertaDidik instanceof PesertaDidik ? $pesertaDidik->id : $pesertaDidik;
        if (!$id) return null;

        $nisn = ($pesertaDidik instanceof PesertaDidik && !empty($pesertaDidik->nisn)) ? trim($pesertaDidik->nisn) : null;

        if ($this->relationLoaded('submissions')) {
            return $this->submissions->first(function ($sub) use ($id, $nisn) {
                return $sub->peserta_didik_id === $id || ($nisn && !empty($sub->nisn) && $sub->nisn === $nisn);
            });
        }

        return $this->submissions()
            ->where(function ($q) use ($id, $nisn) {
                $q->where('peserta_didik_id', $id);
                if ($nisn) {
                    $q->orWhere('nisn', $nisn);
                }
            })
            ->first();
    }

    public function getCompletionFor($pesertaDidik): ?LmsCompletion
    {
        if (!Schema::hasTable('lms_completions')) {
            return null;
        }

        $id = $pesertaDidik instanceof PesertaDidik ? $pesertaDidik->id : $pesertaDidik;
        if (!$id) return null;

        $nisn = ($pesertaDidik instanceof PesertaDidik && !empty($pesertaDidik->nisn)) ? trim($pesertaDidik->nisn) : null;

        if ($this->relationLoaded('completions')) {
            return $this->completions->first(function ($comp) use ($id, $nisn) {
                return $comp->peserta_didik_id === $id || ($nisn && $comp->nisn === $nisn);
            });
        }

        return $this->completions()
            ->where(function ($q) use ($id, $nisn) {
                $q->where('peserta_didik_id', $id);
                if ($nisn) {
                    $q->orWhere('nisn', $nisn);
                }
            })
            ->first();
    }

    public function isCompletedBy($pesertaDidik): bool
    {
        $id = $pesertaDidik instanceof PesertaDidik ? $pesertaDidik->id : $pesertaDidik;
        if (!$id) return false;

        $comp = $this->getCompletionFor($pesertaDidik);
        return $comp !== null && ($comp->completed_at !== null || $comp->read_at !== null || $comp->downloaded_at !== null);
    }

    public function isReadBy($pesertaDidik): bool
    {
        $comp = $this->getCompletionFor($pesertaDidik);
        return $comp !== null && $comp->hasRead();
    }

    public function isDownloadedBy($pesertaDidik): bool
    {
        $comp = $this->getCompletionFor($pesertaDidik);
        return $comp !== null && $comp->hasDownloaded();
    }
}
