@extends('layouts.guru')

@section('title', 'Pengumpulan Tugas - ' . $lms->title)

@section('content')
<div class="space-y-6 pb-16" x-data="{
    filter: 'all',
    search: '',
    gradeModalOpen: false,
    selectedStudent: null,
    submissionId: null,
    currentScore: '',
    currentFeedback: '',
    currentStatus: 'graded',
    openGradeModal(subId, studentName, score, feedback, status) {
        this.submissionId = subId;
        this.selectedStudent = studentName;
        this.currentScore = score || '';
        this.currentFeedback = feedback || '';
        this.currentStatus = status || 'graded';
        this.gradeModalOpen = true;
    },
    matches(name, nisn, status) {
        const q = this.search.trim().toLowerCase();
        const matchesSearch = !q || (name && name.toLowerCase().includes(q)) || (nisn && nisn.toLowerCase().includes(q));
        if (!matchesSearch) return false;
        if (this.filter === 'all') return true;
        if (this.filter === 'submitted') return status !== 'unsubmitted';
        if (this.filter === 'unsubmitted') return status === 'unsubmitted';
        if (this.filter === 'graded') return status === 'graded';
        if (this.filter === 'late') return status === 'late';
        return true;
    }
}">
    <!-- Header & Navigation -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ route('guru.lms.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Arsip LMS
            </a>
            <div class="mt-2 flex items-center gap-3">
                <span class="rounded-xl bg-amber-100 px-3 py-1 text-xs font-black uppercase text-amber-700 dark:bg-amber-950/60 dark:text-amber-300">Penugasan</span>
                <span class="text-xs font-semibold text-slate-500">{{ $lms->kelompokBelajar?->nama_kelompok }}</span>
            </div>
            <h1 class="mt-1 text-2xl font-black text-slate-900 dark:text-white">{{ $lms->title }}</h1>
            @if($lms->due_at)
                <p class="mt-1 text-xs font-bold text-slate-500 dark:text-slate-400">
                    Batas Pengumpulan: <span class="{{ $lms->isOverdue() ? 'text-red-600 font-black' : 'text-amber-600 font-bold' }}">{{ $lms->due_at->translatedFormat('l, d F Y - H:i') }} WIB</span>
                </p>
            @endif
        </div>
        <div class="flex items-center gap-2">
            @if($lms->file_path)
                <a href="{{ route('guru.lms.download', $lms) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">
                    <svg class="h-4 w-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Lampiran Soal
                </a>
            @endif
            <a href="{{ route('guru.lms.edit', $lms) }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                Edit Tugas
            </a>
        </div>
    </div>

    <!-- Statistik Pengumpulan Matriks -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Siswa</p>
            <p class="mt-2 text-3xl font-black text-slate-900 dark:text-white">{{ $stats['totalSiswa'] }}</p>
        </div>
        <div class="rounded-3xl border border-emerald-100 bg-emerald-50/40 p-5 shadow-sm dark:border-emerald-950/40 dark:bg-emerald-950/20">
            <p class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Sudah Mengumpulkan</p>
            <p class="mt-2 text-3xl font-black text-emerald-700 dark:text-emerald-300">{{ $stats['totalKumpul'] }}</p>
        </div>
        <div class="rounded-3xl border border-indigo-100 bg-indigo-50/40 p-5 shadow-sm dark:border-indigo-950/40 dark:bg-indigo-950/20">
            <p class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Sudah Dinilai</p>
            <p class="mt-2 text-3xl font-black text-indigo-700 dark:text-indigo-300">{{ $stats['totalDinilai'] }}</p>
        </div>
        <div class="rounded-3xl border border-rose-100 bg-rose-50/40 p-5 shadow-sm dark:border-rose-950/40 dark:bg-rose-950/20">
            <p class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Belum Mengumpulkan</p>
            <p class="mt-2 text-3xl font-black text-rose-700 dark:text-rose-300">{{ $stats['totalBelumKumpul'] }}</p>
        </div>
    </div>

    <!-- Filter Tabs & Pencarian Siswa (NISN / Nama) -->
    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-xs dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Filter Tabs -->
            <div class="flex items-center gap-1.5 p-1 rounded-2xl bg-slate-100 dark:bg-slate-800 overflow-x-auto shrink-0">
                <button type="button" @click="filter = 'all'" :class="filter === 'all' ? 'bg-white text-slate-900 shadow-xs dark:bg-slate-900 dark:text-white' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400'" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition whitespace-nowrap">
                    Semua ({{ $stats['totalSiswa'] }})
                </button>
                <button type="button" @click="filter = 'submitted'" :class="filter === 'submitted' ? 'bg-white text-emerald-700 shadow-xs dark:bg-slate-900 dark:text-emerald-400' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400'" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition whitespace-nowrap">
                    Sudah Kumpul ({{ $stats['totalKumpul'] }})
                </button>
                <button type="button" @click="filter = 'unsubmitted'" :class="filter === 'unsubmitted' ? 'bg-white text-rose-700 shadow-xs dark:bg-slate-900 dark:text-rose-400' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400'" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition whitespace-nowrap">
                    Belum Kumpul ({{ $stats['totalBelumKumpul'] }})
                </button>
                <button type="button" @click="filter = 'graded'" :class="filter === 'graded' ? 'bg-white text-indigo-700 shadow-xs dark:bg-slate-900 dark:text-indigo-400' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400'" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition whitespace-nowrap">
                    Sudah Dinilai ({{ $stats['totalDinilai'] }})
                </button>
                @if(($stats['totalTerlambat'] ?? 0) > 0)
                    <button type="button" @click="filter = 'late'" :class="filter === 'late' ? 'bg-white text-amber-700 shadow-xs dark:bg-slate-900 dark:text-amber-400' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400'" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition whitespace-nowrap">
                        Terlambat ({{ $stats['totalTerlambat'] }})
                    </button>
                @endif
            </div>

            <!-- Search Input -->
            <div class="relative flex-1 max-w-md">
                <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" x-model="search" placeholder="Cari nama siswa atau NISN..." class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 py-2 pl-10 pr-4 text-xs text-slate-800 placeholder-slate-400 outline-none transition focus:border-indigo-500 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Pengumpulan Siswa -->
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="border-b border-slate-100 p-5 dark:border-slate-800">
            <h2 class="text-base font-black text-slate-900 dark:text-white">Matriks Pengumpulan Siswa Kelas {{ $lms->kelompokBelajar?->nama_kelompok }}</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:bg-slate-800/60 dark:text-slate-400">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Siswa</th>
                        <th class="px-6 py-4">Status Pengumpulan</th>
                        <th class="px-6 py-4">Waktu Kumpul</th>
                        <th class="px-6 py-4">Jawaban / Berkas</th>
                        <th class="px-6 py-4">Nilai</th>
                        <th class="px-6 py-4">Feedback Guru</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($students as $index => $student)
                        @php 
                            $submission = $submissions->get($student->id) 
                                ?? (!empty($student->nisn) ? ($submissionsByNisn->get(trim($student->nisn)) ?? null) : null);

                            $studentStatus = 'unsubmitted';
                            if ($submission) {
                                if ($submission->nilai !== null) {
                                    $studentStatus = 'graded';
                                } elseif ($submission->is_late) {
                                    $studentStatus = 'late';
                                } else {
                                    $studentStatus = 'submitted';
                                }
                            }
                        @endphp
                        <tr x-show="matches('{{ addslashes($student->nama_lengkap) }}', '{{ addslashes($student->nisn ?? '') }}', '{{ $studentStatus }}')" class="transition hover:bg-slate-50/70 dark:hover:bg-slate-800/40">
                            <td class="px-6 py-4 font-bold text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <span class="block font-bold text-slate-900 dark:text-white">{{ $student->nama_lengkap }}</span>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 font-mono text-[11px] font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                        NISN: {{ $student->nisn ?: '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if(!$submission)
                                    <span class="inline-flex items-center rounded-lg bg-rose-50 px-2.5 py-1 text-[11px] font-bold text-rose-600 dark:bg-rose-950/40 dark:text-rose-400">
                                        Belum Mengumpulkan
                                    </span>
                                @elseif($submission->is_late)
                                    <span class="inline-flex items-center rounded-lg bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-700 dark:bg-amber-950/40 dark:text-amber-400">
                                        Terlambat
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-lg bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400">
                                        Tepat Waktu
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                @if($submission)
                                    <span>{{ $submission->submitted_at->format('d M Y, H:i') }}</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($submission)
                                    <div class="space-y-1">
                                        @if($submission->file_path)
                                            @php
                                                $isSubPdf = strtolower(pathinfo($submission->file_path, PATHINFO_EXTENSION)) === 'pdf';
                                            @endphp
                                            <div class="flex items-center gap-2">
                                                <a href="{{ $isSubPdf ? route('guru.lms.submission.viewer', $submission) : route('guru.lms.submission.open', $submission) }}" target="_blank" class="font-bold text-indigo-600 hover:underline">
                                                    Lihat Berkas
                                                </a>
                                                <span class="text-slate-300">&bull;</span>
                                                <a href="{{ route('guru.lms.submission.download', $submission) }}" class="text-slate-500 hover:underline">
                                                    Unduh
                                                </a>
                                            </div>
                                        @endif
                                        @if($submission->catatan_siswa)
                                            <p class="max-w-xs truncate text-[11px] text-slate-500 italic" title="{{ $submission->catatan_siswa }}">
                                                "{{ $submission->catatan_siswa }}"
                                            </p>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($submission && $submission->nilai !== null)
                                    <span class="text-sm font-black text-indigo-600 dark:text-indigo-400">{{ number_format($submission->nilai, 1) }}</span>
                                @elseif($submission)
                                    <span class="text-[11px] font-bold text-amber-600">Belum dinilai</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($submission && $submission->feedback_guru)
                                    <p class="max-w-xs truncate text-[11px] text-slate-600 dark:text-slate-400" title="{{ $submission->feedback_guru }}">
                                        {{ $submission->feedback_guru }}
                                    </p>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($submission)
                                    <button type="button" 
                                            @click="openGradeModal('{{ $submission->id }}', '{{ addslashes($student->nama_lengkap) }}', '{{ $submission->nilai }}', '{{ addslashes($submission->feedback_guru ?? '') }}', '{{ $submission->status }}')"
                                            class="inline-flex items-center gap-1 rounded-xl bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 transition hover:bg-indigo-100 dark:bg-indigo-950/60 dark:text-indigo-300">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        {{ $submission->isGraded() ? 'Edit Nilai' : 'Beri Nilai' }}
                                    </button>
                                @else
                                    <span class="text-[11px] text-slate-400 italic">Menunggu</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-sm text-slate-400">
                                Tidak ada siswa terdaftar dalam kelompok belajar ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Beri Nilai & Evaluasi Guru -->
    <div x-show="gradeModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" x-cloak>
        <div class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl dark:bg-slate-900 dark:border dark:border-slate-800"
             @click.away="gradeModalOpen = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
                <div>
                    <h3 class="text-base font-black text-slate-900 dark:text-white">Beri Nilai & Evaluasi Tugas</h3>
                    <p class="text-xs text-slate-500">Siswa: <span class="font-bold text-slate-700 dark:text-slate-300" x-text="selectedStudent"></span></p>
                </div>
                <button type="button" @click="gradeModalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form :action="'{{ url('/guru/lms/submissions') }}/' + submissionId + '/grade'" method="POST" class="mt-5 space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Nilai (Skala 0 - 100) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.1" min="0" max="100" name="nilai" x-model="currentScore" required placeholder="Contoh: 85.5"
                           class="mt-2 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Status Tugas</label>
                    <select name="status" x-model="currentStatus" class="mt-2 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                        <option value="graded">Sudah Dinilai (Selesai)</option>
                        <option value="resubmitted">Perlu Revisi (Siswa diminta kumpul ulang)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Catatan / Feedback untuk Siswa</label>
                    <textarea name="feedback_guru" rows="4" maxlength="5000" x-model="currentFeedback" placeholder="Tuliskan catatan perbaikan atau apresiasi terhadap hasil pengerjaan siswa..."
                              class="mt-2 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="gradeModalOpen = false" class="rounded-xl px-4 py-2.5 text-xs font-bold text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Batal</button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-200 transition hover:bg-indigo-700 dark:shadow-none">
                        Simpan Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
