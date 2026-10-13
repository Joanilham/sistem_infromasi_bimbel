@extends('layouts.siswa')

@section('title', 'LMS Siswa - Ruang Belajar')

@section('content')
<div class="w-full space-y-6 pb-16" x-data="{
    // Modal Pengumpulan Tugas
    submitModalOpen: false,
    selectedTask: null,
    submitActionUrl: '',
    selectedFileName: '',
    selectedFileSize: '',
    openSubmitModal(taskTitle, actionUrl) {
        this.selectedTask = taskTitle;
        this.submitActionUrl = actionUrl;
        this.selectedFileName = '';
        this.selectedFileSize = '';
        this.submitModalOpen = true;
    },
    closeSubmitModal() {
        this.submitModalOpen = false;
        this.selectedTask = null;
        this.submitActionUrl = '';
        this.selectedFileName = '';
        this.selectedFileSize = '';
    },
    handleFileSelect(e) {
        const file = e.target.files[0];
        if (file) {
            this.selectedFileName = file.name;
            this.selectedFileSize = file.size < 1024 * 1024 
                ? (file.size / 1024).toFixed(1) + ' KB' 
                : (file.size / (1024 * 1024)).toFixed(2) + ' MB';
        }
    },
    removeSelectedFile() {
        this.selectedFileName = '';
        this.selectedFileSize = '';
        if (this.$refs.taskFileInput) {
            this.$refs.taskFileInput.value = '';
        }
    }
}">
    <!-- Header Page (Konsisten dengan Guru LMS) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2">
                <p class="text-xs font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Learning Management System</p>
                <span class="rounded-lg bg-indigo-50 px-2.5 py-0.5 text-[11px] font-bold text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300">
                    Kelompok: {{ $pesertaDidik?->kelompokBelajar?->nama_kelompok ?? 'Belum diatur' }}
                </span>
            </div>
            <h1 class="mt-1 text-2xl font-black text-slate-900 dark:text-white">Ruang Belajar & Modul Siswa</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Akses modul pembelajaran digital, materi bahan ajar, dan kumpulkan tugas sekolah Anda.</p>
        </div>
    </div>

    <!-- Ringkasan Statistik (Format 4 Kartu Konsisten dengan Guru) -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Konten</p>
            <p class="mt-2 text-3xl font-black text-slate-900 dark:text-white">{{ $contents->count() }}</p>
        </div>
        <div class="rounded-3xl border border-indigo-100 bg-indigo-50/50 p-5 shadow-sm dark:border-indigo-950/40 dark:bg-indigo-950/20">
            <p class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Materi Selesai</p>
            <p class="mt-2 text-3xl font-black text-indigo-700 dark:text-indigo-300">
                {{ $stats['materiSelesai'] }} <span class="text-base font-semibold text-slate-400">/ {{ $stats['totalMateri'] }}</span>
            </p>
        </div>
        <div class="rounded-3xl border border-amber-100 bg-amber-50/50 p-5 shadow-sm dark:border-amber-950/40 dark:bg-amber-950/20">
            <p class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">Tugas Terkumpul</p>
            <p class="mt-2 text-3xl font-black text-amber-700 dark:text-amber-300">
                {{ $stats['tugasKumpul'] }} <span class="text-base font-semibold text-slate-400">/ {{ $stats['totalTugas'] }}</span>
            </p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Belum Kumpul</p>
            <p class="mt-2 text-3xl font-black {{ $stats['tugasBelumKumpul'] > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-800 dark:text-slate-200' }}">
                {{ $stats['tugasBelumKumpul'] }}
            </p>
        </div>
    </div>

    <!-- Alert Flash Message -->
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-bold text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-950/40 dark:text-emerald-300 shadow-xs">
            <svg class="h-5 w-5 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs font-bold text-rose-800 dark:border-rose-900/50 dark:bg-rose-950/40 dark:text-rose-300 shadow-xs">
            <svg class="h-5 w-5 shrink-0 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filter & Pencarian Bar (Sama Persis dengan Guru) -->
    <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <form method="GET" action="{{ route('siswa.lms.index') }}" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center">
                <!-- Search Box -->
                <div class="relative flex-1">
                    <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul materi, tugas, atau topik..."
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 py-2.5 pl-10 pr-4 text-xs text-slate-800 placeholder-slate-400 outline-none transition focus:border-indigo-500 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                </div>

                <!-- Filter Tipe -->
                <select name="type" onchange="this.form.submit()" class="no-tomselect rounded-2xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs font-semibold text-slate-700 outline-none transition hover:border-slate-300 focus:border-indigo-500 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                    <option value="">Semua Tipe</option>
                    <option value="materi" @selected(request('type') === 'materi')>Hanya Materi</option>
                    <option value="tugas" @selected(request('type') === 'tugas')>Hanya Tugas</option>
                </select>

                <!-- Filter Status -->
                <select name="status" onchange="this.form.submit()" class="no-tomselect rounded-2xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs font-semibold text-slate-700 outline-none transition hover:border-slate-300 focus:border-indigo-500 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                    <option value="">Semua Status</option>
                    <option value="belum_kumpul" @selected(request('status') === 'belum_kumpul')>Tugas Belum Kumpul</option>
                    <option value="selesai" @selected(request('status') === 'selesai')>Sudah Selesai</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                @if(request()->anyFilled(['search', 'type', 'status']))
                    <a href="{{ route('siswa.lms.index') }}" class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300">
                        Reset Filter
                    </a>
                @endif
                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700">
                    Terapkan
                </button>
            </div>
        </form>
    </div>

    <!-- Grid Konten LMS (Struktur Kartu Sama Persis dengan Guru) -->
    @if($contents->isEmpty())
        <div class="rounded-3xl border border-dashed border-slate-300 p-12 text-center dark:border-slate-800">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <h3 class="mt-4 text-base font-black text-slate-800 dark:text-white">Belum Ada Materi atau Tugas</h3>
            @if(!$pesertaDidik?->kelompok_belajar_id)
                <p class="mt-1 text-xs text-slate-500">Akun Anda belum terhubung ke kelompok belajar. Hubungi admin untuk memperbarui data kelompok siswa.</p>
            @else
                <p class="mt-1 text-xs text-slate-500">Belum ada materi atau tugas untuk kelompok {{ $pesertaDidik->kelompokBelajar?->nama_kelompok ?? 'Anda' }}. Pastikan guru memilih kelompok belajar yang sama.</p>
            @endif
        </div>
    @else
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($contents as $item)
                @php
                    $isTugas = $item->isAssignment();
                    $sub = $pesertaDidik ? $item->getSubmissionFor($pesertaDidik) : null;
                    $isDone = $pesertaDidik ? $item->isCompletedBy($pesertaDidik) : false;
                    $cleanFile = $item->file_path ? ltrim($item->file_path, '/') : null;
                @endphp
                <article onclick="if (!event.target.closest('a, button, form, input')) { window.location.href = '{{ route('siswa.lms.show', $item) }}'; }"
                         class="group relative flex flex-col justify-between rounded-3xl border border-slate-200 bg-white p-6 shadow-xs transition-all duration-200 hover:-translate-y-1 hover:shadow-xl hover:border-indigo-300 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-indigo-600/50 cursor-pointer">
                    <div>
                        <!-- Header Card -->
                        <div class="flex items-center justify-between gap-2">
                            <span class="rounded-xl px-2.5 py-1 text-[11px] font-black uppercase {{ $isTugas ? 'bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300' : 'bg-indigo-100 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300' }}">
                                {{ $item->type_label }}
                            </span>
                            <span class="rounded-lg bg-slate-100 px-2 py-0.5 text-[11px] font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                {{ $item->subject_label }}
                            </span>
                        </div>

                        <!-- Judul & Pembuat -->
                        <h2 class="mt-4 text-base font-black leading-snug text-slate-900 dark:text-white line-clamp-2 transition group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                            <a href="{{ route('siswa.lms.show', $item) }}">
                                {{ $item->title }}
                            </a>
                        </h2>
                        <p class="mt-1 text-[11px] text-slate-400">
                            Oleh {{ $item->guru->name }} &bull; {{ $item->created_at->translatedFormat('d M Y') }}
                        </p>

                        @if($isTugas)
                            <div class="mt-3 flex items-center gap-1.5 rounded-xl bg-amber-50 p-2.5 text-xs text-amber-800 dark:bg-amber-950/30 dark:text-amber-300">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Batas: <strong>{{ $item->due_at ? $item->due_at->translatedFormat('d M Y, H:i') : 'Tidak ditentukan' }}</strong></span>
                            </div>

                            <!-- Status Pengumpulan Siswa -->
                            <div class="mt-2.5 flex items-center gap-2">
                                @if(!$sub)
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-rose-600 dark:text-rose-400">
                                        <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                                        Belum Dikumpulkan
                                    </span>
                                @elseif($sub->status === 'resubmitted' && $sub->nilai !== null)
                                    <span class="inline-flex items-center gap-1 rounded-md bg-amber-50 px-2 py-0.5 text-xs font-black text-amber-700 dark:bg-amber-950/60 dark:text-amber-300">
                                        Perlu Revisi
                                    </span>
                                @elseif($sub->isGraded())
                                    <span class="inline-flex items-center gap-1 rounded-md bg-indigo-50 px-2 py-0.5 text-xs font-black text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300">
                                        Nilai: {{ number_format($sub->nilai, 1) }} / 100
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        {{ $sub->is_late ? 'Terkumpul (Terlambat)' : 'Terkumpul' }}
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="mt-2.5 flex items-center gap-1.5">
                                @if($isDone)
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Selesai Dipelajari
                                    </span>
                                @else
                                    <span class="text-xs font-semibold text-slate-400">
                                        Materi Aktif
                                    </span>
                                @endif
                            </div>
                        @endif

                        @if($item->description)
                            <p class="mt-3 text-xs leading-5 text-slate-600 line-clamp-3 dark:text-slate-300">
                                {{ $item->description }}
                            </p>
                        @endif
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 space-y-3">
                        <!-- Lampiran Berkas Guru -->
                        @if($cleanFile && Storage::disk('public')->exists($cleanFile))
                            <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2 text-xs dark:bg-slate-800/60">
                                <span class="truncate max-w-[170px] text-[11px] font-semibold text-slate-600 dark:text-slate-300" title="{{ $item->file_name }}">
                                    📎 {{ $item->file_name ?: 'Berkas Lampiran' }}
                                </span>
                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="{{ route('siswa.lms.viewer', $item) }}" @click.stop target="_blank" rel="noopener noreferrer" class="font-bold text-indigo-600 hover:underline">Buka</a>
                                    <span class="text-slate-300">&bull;</span>
                                    <a href="{{ route('siswa.lms.download', $item) }}" @click.stop class="text-slate-500 hover:underline">Unduh</a>
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between gap-2">
                            <a href="{{ route('siswa.lms.show', $item) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 group-hover:border-indigo-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-300">
                                Baca Detail
                            </a>

                            @if($isTugas)
                                @if(!$sub)
                                    <button type="button" 
                                            @click.stop="openSubmitModal('{{ addslashes($item->title) }}', '{{ route('siswa.lms.submit', $item) }}')"
                                            class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-3.5 py-1.5 text-xs font-bold text-white shadow-xs transition hover:bg-indigo-700">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        Kumpulkan
                                    </button>
                                @elseif(!$sub->isGraded())
                                    <button type="button" 
                                            @click.stop="openSubmitModal('{{ addslashes($item->title) }}', '{{ route('siswa.lms.submit', $item) }}')"
                                            class="inline-flex items-center gap-1 rounded-xl bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300">
                                        Ganti Berkas
                                    </button>
                                @else
                                    <span class="text-xs font-semibold text-slate-400">Terkunci</span>
                                @endif
                            @else
                                <form method="POST" action="{{ route('siswa.lms.complete', $item) }}" @click.stop>
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1 rounded-xl px-3 py-1.5 text-xs font-bold transition {{ $isDone ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300' }}">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $isDone ? 'Selesai' : 'Tandai Selesai' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif

    <!-- Modal Pengumpulan Tugas Modern -->
    <div x-show="submitModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" x-cloak>
        <div class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl dark:bg-slate-900 dark:border dark:border-slate-800"
             @click.away="closeSubmitModal()">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
                <div>
                    <h3 class="text-base font-black text-slate-900 dark:text-white">Kumpulkan Jawaban Tugas</h3>
                    <p class="text-xs text-slate-500 line-clamp-1 mt-0.5" x-text="selectedTask"></p>
                </div>
                <button type="button" @click="closeSubmitModal()" class="rounded-xl p-1 text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">&times;</button>
            </div>

            <form :action="submitActionUrl" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
                @csrf

                <!-- Dropzone Interaktif -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                        Berkas Jawaban <span class="font-normal text-slate-400">(Maks 15 MB)</span>
                    </label>

                    <input type="file" name="file" x-ref="taskFileInput" @change="handleFileSelect($event)" class="hidden"
                           accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,.jpg,.jpeg,.png">

                    <!-- Dropzone Kosong -->
                    <div x-show="!selectedFileName"
                         @click="$refs.taskFileInput.click()"
                         class="mt-2 flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 p-6 text-center transition hover:border-indigo-400 hover:bg-slate-50/50 dark:border-slate-700 dark:hover:border-slate-600 dark:hover:bg-slate-800/40">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        </div>
                        <p class="mt-2.5 text-xs font-bold text-slate-800 dark:text-white">
                            <span class="text-indigo-600 hover:underline dark:text-indigo-400">Pilih berkas jawaban</span> atau seret ke sini
                        </p>
                        <p class="mt-1 text-[11px] text-slate-400">PDF, Word, Excel, PPT, Gambar, atau ZIP</p>
                    </div>

                    <!-- File Dipilih -->
                    <div x-show="selectedFileName" x-cloak
                         class="mt-2 flex items-center justify-between rounded-2xl border border-indigo-200 bg-indigo-50/40 p-3.5 dark:border-indigo-900/40 dark:bg-indigo-950/20">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-xs font-bold text-slate-900 dark:text-white" x-text="selectedFileName"></p>
                                <span class="text-[10px] text-indigo-600 dark:text-indigo-400 font-semibold" x-text="selectedFileSize"></span>
                            </div>
                        </div>
                        <button type="button" @click="removeSelectedFile()" class="rounded-xl p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/50">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Catatan Jawaban Teks -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                        Catatan / Jawaban Teks <span class="font-normal text-slate-400">(Opsional)</span>
                    </label>
                    <textarea name="catatan_siswa" rows="3" maxlength="5000" placeholder="Tuliskan catatan tambahan kepada guru pengajar..."
                              class="mt-2 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-xs text-slate-800 shadow-2xs focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100"></textarea>
                </div>

                <!-- Tombol Submit -->
                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="closeSubmitModal()" 
                            class="rounded-xl px-4 py-2 text-xs font-bold text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                        Batal
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-5 py-2 text-xs font-bold text-white shadow-md shadow-indigo-200 transition hover:bg-indigo-700 dark:shadow-none">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Kirim Jawaban
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
