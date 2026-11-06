<?php

namespace App\Notifications;

use App\Models\Akademik\LmsSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TugasDinilaiNotification extends Notification
{
    use Queueable;

    public function __construct(
        public LmsSubmission $submission
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $this->submission->loadMissing(['content.guru', 'pesertaDidik']);
        $content = $this->submission->content;
        $title = $content?->title ?? 'Tugas';
        $guruName = $content?->guru?->name ?? 'Guru Pengajar';
        $nilai = $this->submission->nilai !== null ? (int) $this->submission->nilai : '-';

        return [
            'type'          => 'tugas_dinilai',
            'title'         => "Tugas Dinilai: {$title}",
            'message'       => "Guru {$guruName} telah menilai tugas '{$title}' Anda dengan nilai {$nilai}/100.",
            'content_id'    => $content?->id,
            'submission_id' => $this->submission->id,
            'nilai'         => $nilai,
            'feedback'      => $this->submission->feedback_guru,
            'url'           => $content ? route('siswa.lms.show', $content->id, false) : '#',
            'icon'          => 'check-badge',
            'accent'        => 'emerald',
        ];
    }
}
