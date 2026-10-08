<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LmsCompletion extends Model
{
    protected $fillable = [
        'lms_content_id',
        'peserta_didik_id',
        'nisn',
        'read_at',
        'downloaded_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at'       => 'datetime',
            'downloaded_at' => 'datetime',
            'completed_at'  => 'datetime',
        ];
    }

    public function hasRead(): bool
    {
        return !is_null($this->read_at) || (!is_null($this->completed_at) && is_null($this->downloaded_at));
    }

    public function hasDownloaded(): bool
    {
        return !is_null($this->downloaded_at);
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(LmsContent::class, 'lms_content_id');
    }

    public function pesertaDidik(): BelongsTo
    {
        return $this->belongsTo(PesertaDidik::class, 'peserta_didik_id');
    }
}
