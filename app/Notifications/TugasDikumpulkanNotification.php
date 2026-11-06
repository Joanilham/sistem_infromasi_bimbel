<?php

namespace App\Notifications;

use App\Models\Akademik\LmsContent;
use App\Models\Akademik\LmsSubmission;
use App\Models\Akademik\PesertaDidik;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TugasDikumpulkanNotification extends Notification
{
    use Queueable;

    public function __construct(
        public LmsContent $content,
        public PesertaDidik $student,
        public LmsSubmission $submission
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $this->student->loadMissing('kelompokBelajar');
        $studentName = $this->student->nama_lengkap ?: 'Siswa';
        $groupName = $this->student->kelompokBelajar?->nama_kelompok ?? 'Kelas';
        $action = $this->submission->status === 'resubmitted' ? 'mengunggah revisi' : 'mengumpulkan';
        $lateText = $this->submission->is_late ? ' (Terlambat)' : '';

        return [
            'type'          => 'tugas_dikumpulkan',
            'title'         => "Tugas {$action}: {$studentName}",
            'message'       => "{$studentName} ({$groupName}) telah {$action} tugas '{$this->content->title}'{$lateText}.",
            'content_id'    => $this->content->id,
            'submission_id' => $this->submission->id,
            'student_id'    => $this->student->id,
            'student_name'  => $studentName,
            'is_late'       => (bool) $this->submission->is_late,
            'status'        => $this->submission->status,
            'url'           => route('guru.lms.submissions', $this->content->id, false),
            'icon'          => 'arrow-up-tray',
            'accent'        => $this->submission->is_late ? 'amber' : 'emerald',
        ];
    }
}
