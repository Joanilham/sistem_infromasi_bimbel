<?php

namespace App\Notifications;

use App\Models\Akademik\LmsContent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TugasBaruNotification extends Notification
{
    use Queueable;

    public function __construct(
        public LmsContent $content
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $this->content->loadMissing('guru');
        $guruName = $this->content->guru?->name ?? 'Guru Pengajar';
        $subject = $this->content->subject ?: 'Umum';
        $dueAt = $this->content->due_at 
            ? $this->content->due_at->translatedFormat('d M Y, H:i') . ' WIB'
            : 'Tidak ada tenggat';

        return [
            'type'        => 'tugas_baru',
            'title'       => 'Tugas Baru: ' . $this->content->title,
            'message'     => "Guru {$guruName} telah mempublikasikan tugas baru untuk mata pelajaran {$subject}.",
            'content_id'  => $this->content->id,
            'subject'     => $subject,
            'due_at'      => $dueAt,
            'url'         => route('siswa.lms.show', $this->content->id, false),
            'icon'        => 'clipboard',
            'accent'      => 'indigo',
        ];
    }
}
