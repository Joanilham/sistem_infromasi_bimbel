<?php

namespace App\Services;

use App\Models\Akademik\KelompokBelajar;
use App\Models\Akademik\LmsCompletion;
use App\Models\Akademik\LmsContent;
use App\Models\Akademik\LmsSubmission;
use App\Models\Akademik\PesertaDidik;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class LmsService
{
    public function getContentsForGuru(User $guru, Request $request, ?string $subject = null)
    {
        $query = LmsContent::query()
            ->with(['kelompokBelajar'])
            ->where('guru_id', $guru->id);

        if ($this->hasLmsSubmissionTable() && $this->hasLmsCompletionTable()) {
            $query->withCount(['submissions', 'completions']);
        }

        if ($subject) {
            $query->where('subject', $subject);
        }

        if ($request->filled('kelompok_belajar_id')) {
            $query->where('kelompok_belajar_id', $request->integer('kelompok_belajar_id'));
        }

        if ($request->filled('type') && in_array($request->input('type'), ['materi', 'tugas'], true)) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function (Builder $query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate(12)->withQueryString();
    }

    public function getKelompokBelajarForGuru(User $guru): Collection
    {
        return KelompokBelajar::query()
            ->withCount(['pesertaDidiks as siswa_aktif_count' => fn (Builder $query) => $query->where('status', 'Aktif')])
            ->where(function (Builder $query) use ($guru) {
                // Kelompok belajar yang dibuat melalui halaman admin bersifat global.
                $query->where(function (Builder $query) {
                    $query->whereNull('kantor_id')
                        ->whereNull('periode_id');
                });

                // Tetap tampilkan kelompok yang memang khusus untuk kantor dan periode guru.
                if ($guru->kantor_id && $guru->periode_id) {
                    $query->orWhere(function (Builder $query) use ($guru) {
                        $query->where('kantor_id', $guru->kantor_id)
                            ->where('periode_id', $guru->periode_id);
                    });
                }
            })
            ->orderBy('nama_kelompok')
            ->get();
    }

    public function getGuruStats(User $guru, ?string $subject = null): array
    {
        $query = LmsContent::where('guru_id', $guru->id);
        if ($subject) {
            $query->where('subject', $subject);
        }

        return [
            'totalMateri' => (clone $query)->where('type', 'materi')->count(),
            'totalTugas' => (clone $query)->where('type', 'tugas')->count(),
        ];
    }

    public function createContent(User $guru, array $data, ?UploadedFile $file = null): LmsContent
    {
        $group = KelompokBelajar::findOrFail($data['kelompok_belajar_id']);
        $storedPath = $file?->store('lms/materials', 'public');

        return LmsContent::create([
            'guru_id' => $guru->id,
            'kantor_id' => $this->contextId(session('kantor_id'), $group->kantor_id),
            'periode_id' => $this->contextId(session('periode_id'), $group->periode_id),
            'subject' => $data['subject'] ?? null,
            'kelompok_belajar_id' => $group->id,
            'type' => $data['type'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'file_path' => $storedPath,
            'file_name' => $file?->getClientOriginalName(),
            'due_at' => $data['due_at'] ?? null,
        ]);
    }

    public function updateContent(LmsContent $content, array $data, ?UploadedFile $file = null): void
    {
        $content->fill([
            'subject' => $data['subject'] ?? null,
            'kelompok_belajar_id' => $data['kelompok_belajar_id'],
            'type' => $data['type'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'due_at' => $data['due_at'] ?? null,
        ]);

        if ($file) {
            $oldPath = $content->file_path;
            $content->file_path = $file->store('lms/materials', 'public');
            $content->file_name = $file->getClientOriginalName();
            $content->save();

            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }

            return;
        }

        $content->save();
    }

    public function deleteContent(LmsContent $content): void
    {
        $submissionPaths = Schema::hasTable('lms_submissions')
            ? $content->submissions()->pluck('file_path')->all()
            : [];

        $filePaths = array_filter([
            $content->file_path,
            ...$submissionPaths,
        ]);

        $content->delete();
        Storage::disk('public')->delete($filePaths);
    }

    public function getStudentLmsDashboard(?PesertaDidik $student, Request $request): array
    {
        $query = LmsContent::query()
            ->with(['guru', 'kelompokBelajar']);

        if ($this->hasLmsSubmissionTable() && $this->hasLmsCompletionTable()) {
            $query->with(['submissions', 'completions']);
        }

        if ($student?->kelompok_belajar_id) {
            $query->where('kelompok_belajar_id', $student->kelompok_belajar_id);
        } else {
            $query->whereRaw('1 = 0');
        }

        if ($request->filled('type') && in_array($request->input('type'), ['materi', 'tugas'], true)) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function (Builder $query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($request->input('status') === 'belum_kumpul') {
            if ($this->hasLmsSubmissionTable()) {
                $query->where('type', 'tugas')
                    ->whereDoesntHave('submissions', function (Builder $query) use ($student) {
                        $query->where('peserta_didik_id', $student?->id);
                    });
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($request->input('status') === 'selesai') {
            if ($this->hasLmsSubmissionTable() || $this->hasLmsCompletionTable()) {
                $query->where(function (Builder $query) use ($student) {
                    if ($this->hasLmsCompletionTable()) {
                        $query->whereHas('completions', function (Builder $query) use ($student) {
                            $query->where('peserta_didik_id', $student?->id)
                                ->whereNotNull('completed_at');
                        });
                    }

                    if ($this->hasLmsSubmissionTable()) {
                        $query->orWhereHas('submissions', function (Builder $query) use ($student) {
                            $query->where('peserta_didik_id', $student?->id);
                        });
                    }
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $contents = $query->latest()->get();
        $materials = $contents->where('type', 'materi');
        $assignments = $contents->where('type', 'tugas');
        $materiSelesai = $materials->filter(fn (LmsContent $item) => $student && $item->isCompletedBy($student))->count();
        $tugasKumpul = $assignments->filter(fn (LmsContent $item) => $student && $item->getSubmissionFor($student))->count();

        return [
            'pesertaDidik' => $student,
            'contents' => $contents,
            'materials' => $materials,
            'assignments' => $assignments,
            'stats' => [
                'materiSelesai' => $materiSelesai,
                'totalMateri' => $materials->count(),
                'tugasKumpul' => $tugasKumpul,
                'totalTugas' => $assignments->count(),
                'tugasBelumKumpul' => max(0, $assignments->count() - $tugasKumpul),
            ],
        ];
    }

    public function getStudentDetailData(LmsContent $content, ?PesertaDidik $student): array
    {
        $content->loadMissing(['guru', 'kelompokBelajar']);

        return [
            'lms' => $content,
            'completion' => $student && $this->hasLmsCompletionTable()
                ? LmsCompletion::where('lms_content_id', $content->id)->where('peserta_didik_id', $student->id)->first()
                : null,
            'submission' => $student && $this->hasLmsSubmissionTable() ? $content->getSubmissionFor($student) : null,
            'isCompleted' => $student && $this->hasLmsCompletionTable() && $content->isCompletedBy($student),
        ];
    }

    public function submitAssignment(
        LmsContent $content,
        PesertaDidik $student,
        UploadedFile $file,
        ?string $note
    ): array {
        if (!$this->hasLmsSubmissionTable()) {
            return ['success' => false, 'message' => 'Fitur pengumpulan tugas belum siap karena tabel LMS belum dibuat. Jalankan migrasi database terlebih dahulu.'];
        }

        $submission = $content->getSubmissionFor($student);
        $oldPath = $submission?->file_path;
        $newPath = $file->store('lms/submissions', 'public');

        if (!$newPath) {
            throw new \RuntimeException('File jawaban gagal disimpan.');
        }

        $isLate = $content->due_at !== null && now()->greaterThan($content->due_at);
        $submission ??= new LmsSubmission();
        $submission->fill([
            'lms_content_id' => $content->id,
            'peserta_didik_id' => $student->id,
            'nisn' => $student->nisn,
            'file_path' => $newPath,
            'file_name' => $file->getClientOriginalName(),
            'catatan_siswa' => $note,
            'submitted_at' => now(),
            'is_late' => $isLate,
            'status' => $submission->exists ? 'resubmitted' : 'submitted',
            'nilai' => null,
            'feedback_guru' => null,
            'graded_at' => null,
            'graded_by' => null,
        ]);
        $submission->save();

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return ['success' => true, 'message' => 'Tugas berhasil dikumpulkan.'];
    }

    public function unsubmitAssignment(LmsContent $content, PesertaDidik $student): array
    {
        if (!$this->hasLmsSubmissionTable()) {
            return ['success' => false, 'message' => 'Fitur pengumpulan tugas belum siap karena tabel LMS belum dibuat. Jalankan migrasi database terlebih dahulu.'];
        }

        $submission = $content->getSubmissionFor($student);
        if (!$submission) {
            return ['success' => false, 'message' => 'Pengumpulan tugas tidak ditemukan.'];
        }
        if ($submission->isGraded()) {
            return ['success' => false, 'message' => 'Tugas yang sudah dinilai tidak dapat dibatalkan.'];
        }

        $path = $submission->file_path;
        $submission->delete();
        if ($path) {
            Storage::disk('public')->delete($path);
        }

        return ['success' => true, 'message' => 'Pengumpulan tugas berhasil dibatalkan.'];
    }

    public function toggleMaterialComplete(LmsContent $content, PesertaDidik $student): array
    {
        if (!$this->hasLmsCompletionTable()) {
            return ['message' => 'Fitur progress materi belum siap karena tabel LMS belum dibuat. Jalankan migrasi database terlebih dahulu.'];
        }

        $completion = LmsCompletion::where('lms_content_id', $content->id)
            ->where('peserta_didik_id', $student->id)
            ->first();

        if ($completion?->completed_at) {
            $completion->delete();
            return ['message' => 'Status selesai belajar dibatalkan.'];
        }

        if ($completion) {
            $completion->update(['completed_at' => now()]);
        } else {
            LmsCompletion::create([
                'lms_content_id' => $content->id,
                'peserta_didik_id' => $student->id,
                'nisn' => $student->nisn,
                'completed_at' => now(),
            ]);
        }

        return ['message' => 'Materi berhasil ditandai selesai.'];
    }

    public function recordMaterialRead(LmsContent $content, PesertaDidik $student): void
    {
        if (!$this->hasLmsCompletionTable()) {
            return;
        }

        $completion = LmsCompletion::firstOrNew([
            'lms_content_id' => $content->id,
            'peserta_didik_id' => $student->id,
        ]);
        $completion->nisn = $student->nisn;
        $completion->read_at ??= now();
        $completion->save();
    }

    public function recordMaterialDownload(LmsContent $content, PesertaDidik $student): void
    {
        if (!$this->hasLmsCompletionTable()) {
            return;
        }

        $completion = LmsCompletion::firstOrNew([
            'lms_content_id' => $content->id,
            'peserta_didik_id' => $student->id,
        ]);
        $completion->nisn = $student->nisn;
        $completion->downloaded_at = now();
        $completion->save();
    }

    public function getSubmissionsData(LmsContent $content): array
    {
        if (!$this->hasLmsSubmissionTable()) {
            return [
                'students' => collect(),
                'submissions' => collect(),
                'submissionsByNisn' => collect(),
                'stats' => [
                    'totalSiswa' => 0,
                    'totalKumpul' => 0,
                    'totalDinilai' => 0,
                    'totalBelumKumpul' => 0,
                    'totalTerlambat' => 0,
                ],
            ];
        }

        $students = PesertaDidik::query()
            ->where('kelompok_belajar_id', $content->kelompok_belajar_id)
            ->where('status', 'Aktif')
            ->orderBy('nama_lengkap')
            ->get();
        $submissions = $content->submissions()->with('penilai')->get();
        $byStudent = $submissions->keyBy('peserta_didik_id');
        $byNisn = $submissions->filter(fn (LmsSubmission $submission) => $submission->nisn)
            ->keyBy(fn (LmsSubmission $submission) => trim($submission->nisn));
        $total = $students->count();
        $submitted = $submissions->count();
        $graded = $submissions->filter(fn (LmsSubmission $submission) => $submission->isGraded())->count();

        return [
            'students' => $students,
            'submissions' => $byStudent,
            'submissionsByNisn' => $byNisn,
            'stats' => [
                'totalSiswa' => $total,
                'totalKumpul' => $submitted,
                'totalDinilai' => $graded,
                'totalBelumKumpul' => max(0, $total - $submitted),
                'totalTerlambat' => $submissions->where('is_late', true)->count(),
            ],
        ];
    }

    public function gradeSubmission(LmsSubmission $submission, array $data, ?int $graderId): void
    {
        if (!$this->hasLmsSubmissionTable()) {
            return;
        }

        $submission->fill([
            'nilai' => $data['nilai'],
            'feedback_guru' => $data['feedback_guru'] ?? null,
            'status' => $data['status'] ?? 'graded',
            'graded_at' => now(),
            'graded_by' => $graderId,
        ])->save();
    }

    public function getReadersData(LmsContent $content): array
    {
        $rombel = $content->kelompokBelajar;
        $students = $rombel
            ? PesertaDidik::query()->where('kelompok_belajar_id', $rombel->id)->where('status', 'Aktif')->orderBy('nama_lengkap')->get()
            : collect();

        if (!$this->hasLmsCompletionTable()) {
            return [
                'rombel' => $rombel,
                'studentList' => $students->map(fn (PesertaDidik $student) => [
                    'id' => $student->id,
                    'nisn' => $student->nisn ?: '-',
                    'nama_lengkap' => $student->nama_lengkap,
                    'jenis_kelamin' => $student->jenis_kelamin,
                    'has_read' => false,
                    'read_at' => null,
                    'has_downloaded' => false,
                    'downloaded_at' => null,
                ])->values(),
                'totalRombel' => $students->count(),
                'totalRead' => 0,
                'totalDownloaded' => 0,
                'readPercentage' => 0,
                'downloadPercentage' => 0,
            ];
        }

        $completions = $content->completions()->get()->keyBy('peserta_didik_id');
        $byNisn = $content->completions()->whereNotNull('nisn')->get()->keyBy(fn (LmsCompletion $item) => trim($item->nisn));

        $studentList = $students->map(function (PesertaDidik $student) use ($completions, $byNisn) {
            $activity = $completions->get($student->id)
                ?? (!empty($student->nisn) ? $byNisn->get(trim($student->nisn)) : null);

            return [
                'id' => $student->id,
                'nisn' => $student->nisn ?: '-',
                'nama_lengkap' => $student->nama_lengkap,
                'jenis_kelamin' => $student->jenis_kelamin,
                'has_read' => (bool) ($activity?->hasRead()),
                'read_at' => $activity?->read_at?->format('d M Y, H:i'),
                'has_downloaded' => (bool) ($activity?->hasDownloaded()),
                'downloaded_at' => $activity?->downloaded_at?->format('d M Y, H:i'),
            ];
        })->values();

        $totalRombel = $students->count();
        $totalRead = $studentList->where('has_read', true)->count();
        $totalDownloaded = $studentList->where('has_downloaded', true)->count();

        return [
            'rombel' => $rombel,
            'studentList' => $studentList,
            'totalRombel' => $totalRombel,
            'totalRead' => $totalRead,
            'totalDownloaded' => $totalDownloaded,
            'readPercentage' => $totalRombel ? (int) round($totalRead / $totalRombel * 100) : 0,
            'downloadPercentage' => $totalRombel ? (int) round($totalDownloaded / $totalRombel * 100) : 0,
        ];
    }

    public function formatReadersJsonResponse(LmsContent $content, array $data): array
    {
        return [
            'success' => true,
            'lms_id' => $content->id,
            'title' => $content->title,
            'total_rombel' => $data['totalRombel'],
            'total_read' => $data['totalRead'],
            'students' => $data['studentList'],
        ];
    }

    private function hasLmsSubmissionTable(): bool
    {
        return Schema::hasTable('lms_submissions');
    }

    private function hasLmsCompletionTable(): bool
    {
        return Schema::hasTable('lms_completions');
    }

    private function contextId(mixed $value, ?int $fallback): ?int
    {
        return is_numeric($value) ? (int) $value : $fallback;
    }
}
