@extends('layouts.siswa')

@section('title', $lms->title . ' - LMS Siswa')

@section('content')
<div class="mx-auto max-w-6xl space-y-6 pb-20">
    <!-- Navigasi Kembali & Header Halaman -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ route('siswa.lms.index') }}" class="group inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-indigo-600 transition hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                <svg class="h-4 w-4 transition group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Ruang Belajar
            </a>
            <div class="mt-2.5 flex flex-wrap items-center gap-2">
                <span class="rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-black text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300">
                    {{ $lms->subject_label }}
                </span>
                <span class="rounded-lg {{ $lms->isMaterial() ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300' }} px-2.5 py-1 text-xs font-black">
                    {{ $lms->type_label }}
                </span>
                <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                    Kelas: {{ $lms->kelompokBelajar?->nama_kelompok ?? 'Umum' }}
                </span>
            </div>
            <h1 class="mt-2 text-2xl font-black text-slate-900 sm:text-3xl dark:text-white">{{ $lms->title }}</h1>
            <p class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <span>Diajarkan oleh <strong>{{ $lms->guru->name }}</strong></span>
                <span>&bull;</span>
                <span>Dipublikasikan {{ $lms->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
            </p>
        </div>

        @if($lms->isMaterial())
            <div class="shrink-0">
                @if($completion?->hasDownloaded())
                    <div class="inline-flex items-center gap-2 rounded-2xl border border-indigo-200 bg-indigo-50 px-4 py-2.5 text-xs font-bold text-indigo-700 shadow-2xs dark:border-indigo-900/60 dark:bg-indigo-950/40 dark:text-indigo-300">
                        <svg class="h-4 w-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>Materi Telah Dibaca & Diunduh</span>
                    </div>
                @elseif($isCompleted || $completion?->hasRead())
                    <div class="inline-flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-xs font-bold text-emerald-700 shadow-2xs dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-300">
                        <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>Materi Telah Dibaca</span>
                    </div>
                @elseif($lms->file_path)
                    <a href="{{ route('siswa.lms.download', $lms) }}" class="inline-flex items-center gap-2 rounded-2xl bg-indigo-600 px-5 py-3 text-xs font-bold text-white shadow-md shadow-indigo-200 transition hover:bg-indigo-700 dark:shadow-none">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Unduh Berkas Materi</span>
                    </a>
                @endif
            </div>
        @endif
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-bold text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-950/40 dark:text-emerald-300">
            <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs font-bold text-rose-800 dark:border-rose-900/50 dark:bg-rose-950/40 dark:text-rose-300">
            <svg class="h-5 w-5 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 gap-6 {{ $lms->isAssignment() ? 'lg:grid-cols-12' : '' }}">
        
        <!-- Kolom Kiri / Utama: Petunjuk Pengerjaan & Berkas Lampiran Guru -->
        <div class="space-y-6 {{ $lms->isAssignment() ? 'lg:col-span-7 xl:col-span-8' : '' }}">
            
            <!-- Card Petunjuk & Panduan Belajar -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="flex items-center gap-2.5 border-b border-slate-100 pb-4 dark:border-slate-800">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-black uppercase tracking-wider text-slate-900 dark:text-white">
                            {{ $lms->isAssignment() ? 'Petunjuk Pengerjaan Tugas' : 'Petunjuk & Modul Pembelajaran' }}
                        </h2>
                        <p class="text-xs text-slate-400">Baca dan pahami arahan dari guru pengajar di bawah ini</p>
                    </div>
                </div>

                <div class="prose max-w-none pt-5 text-sm leading-relaxed text-slate-700 dark:text-slate-300 whitespace-pre-line">
                    {{ $lms->description ?: 'Guru tidak menyertakan catatan petunjuk teks tambahan untuk konten ini.' }}
                </div>
            </div>

            <!-- Card Dokumen Lampiran Soal / Materi (Tanpa Frame Iframe yang Membebani Layar) -->
            @if($lms->file_path)
                @php
                    $cleanFile = ltrim($lms->file_path, '/');
                    $fileExt = strtoupper(pathinfo($cleanFile, PATHINFO_EXTENSION));
                    $isPdf = $fileExt === 'PDF';
                @endphp
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs transition hover:border-slate-300 dark:border-slate-800 dark:bg-slate-900 sm:p-7">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="relative flex h-13 w-13 shrink-0 items-center justify-center rounded-2xl {{ $isPdf ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400' : 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400' }}">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="absolute -bottom-1 -right-1 rounded-md {{ $isPdf ? 'bg-rose-600 text-white' : 'bg-indigo-600 text-white' }} px-1.5 py-0.5 text-[9px] font-black uppercase tracking-wider">
                                    {{ $fileExt ?: 'FILE' }}
                                </span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="truncate text-sm font-bold text-slate-900 dark:text-white" title="{{ $lms->file_name ?: basename($cleanFile) }}">
                                    {{ $lms->file_name ?: basename($cleanFile) }}
                                </h3>
                                <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-400 dark:text-slate-500">
                                    <span>Lampiran resmi {{ $lms->isAssignment() ? 'soal tugas' : 'materi belajar' }}</span>
                                    <span>&bull;</span>
                                    <span class="font-medium text-emerald-600 dark:text-emerald-400">Dapat dibuka & dibaca di tab baru</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5 shrink-0">
                            <a href="{{ route('siswa.lms.viewer', $lms) }}" target="_blank"
                               class="inline-flex items-center gap-2 rounded-2xl bg-indigo-600 px-5 py-3 text-xs font-bold text-white shadow-md shadow-indigo-200 transition hover:bg-indigo-700 hover:shadow-indigo-300 dark:shadow-none">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                Buka Dokumen
                            </a>
                            <a href="{{ route('siswa.lms.download', $lms) }}"
                               class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-xs font-bold text-slate-700 shadow-2xs transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Unduh
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if($lms->isAssignment())
            <!-- Kolom Kanan / Sidebar: Batas Waktu, Hasil Evaluasi, & Form Pengumpulan -->
            <div class="space-y-6 lg:col-span-5 xl:col-span-4">
                
                <!-- Card Batas Waktu (Deadline) -->
                @php
                    $isOverdue = $lms->isOverdue();
                    $isGraded = $submission && $submission->isGraded();
                @endphp
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Batas Waktu Pengumpulan</span>
                    <div class="mt-3 flex items-center gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $isOverdue && !$submission ? 'bg-rose-100 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400' : 'bg-amber-100 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-black text-slate-900 dark:text-white">
                                {{ $lms->due_at ? $lms->due_at->translatedFormat('d M Y, H:i') . ' WIB' : 'Tidak ditentukan' }}
                            </p>
                            @if($lms->due_at)
                                <p class="mt-0.5 text-xs font-bold {{ $isOverdue ? 'text-rose-500' : 'text-amber-600 dark:text-amber-400' }}">
                                    {{ $isOverdue ? 'Batas waktu telah berakhir' : 'Tersisa ' . $lms->due_at->diffForHumans(null, true) . ' lagi' }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Hasil Penilaian Guru (Jika Sudah Dinilai) -->
                @if($isGraded)
                    <div class="rounded-3xl border border-indigo-200 bg-indigo-50/70 p-6 shadow-xs dark:border-indigo-900/50 dark:bg-indigo-950/40">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-indigo-900 dark:text-indigo-200">Hasil Evaluasi Guru</span>
                            <span class="rounded-xl bg-indigo-600 px-3 py-1 text-base font-black text-white">
                                {{ number_format($submission->nilai, 1) }} / 100
                            </span>
                        </div>
                        @if($submission->feedback_guru)
                            <div class="mt-3 rounded-2xl bg-white/80 p-3.5 text-xs text-slate-700 italic dark:bg-slate-900/80 dark:text-slate-200">
                                "{{ $submission->feedback_guru }}"
                            </div>
                        @endif
                        <p class="mt-2.5 text-[11px] text-indigo-700/80 dark:text-indigo-300/80">
                            Dinilai oleh <strong>{{ $submission->penilai?->name ?? 'Guru Pengampu' }}</strong> pada {{ $submission->graded_at?->translatedFormat('d M Y, H:i') }} WIB
                        </p>
                    </div>
                @endif

                <!-- Form & Status Pengumpulan Jawaban -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-sm font-black text-slate-900 dark:text-white">Status Pengumpulan Anda</h3>

                    @if($submission)
                        <!-- Kartu Jawaban yang Sudah Dikumpulkan -->
                        <div class="mt-3 rounded-2xl border border-emerald-100 bg-emerald-50/60 p-4 dark:border-emerald-950/50 dark:bg-emerald-950/30">
                            <div class="flex items-center gap-2">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500 text-white">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span class="text-xs font-bold text-emerald-800 dark:text-emerald-200">
                                    {{ $submission->is_late ? 'Terkumpul (Melewati Batas)' : 'Terkumpul Tepat Waktu' }}
                                </span>
                            </div>
                            <p class="mt-1 text-[11px] text-emerald-700 dark:text-emerald-300">
                                Waktu: {{ $submission->submitted_at->translatedFormat('d M Y, H:i') }} WIB
                            </p>

                            @if($submission->file_path)
                                @php
                                    $isSubPdf = strtolower(pathinfo($submission->file_path, PATHINFO_EXTENSION)) === 'pdf';
                                @endphp
                                <div class="mt-3 flex items-center justify-between rounded-xl bg-white p-2.5 text-xs shadow-2xs dark:bg-slate-800">
                                    <span class="truncate max-w-[170px] font-semibold text-slate-700 dark:text-slate-200">
                                        📎 {{ $submission->file_name ?: 'Berkas Jawaban' }}
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ $isSubPdf ? route('siswa.lms.submission.viewer', $submission) : route('siswa.lms.submission.open', $submission) }}" target="_blank" class="font-bold text-indigo-600 hover:underline">
                                            Lihat
                                        </a>
                                        <span class="text-slate-300">&bull;</span>
                                        <a href="{{ route('siswa.lms.submission.download', $submission) }}" class="text-slate-500 hover:underline">
                                            Unduh
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if($submission->catatan_siswa)
                                <div class="mt-2 text-xs italic text-slate-600 dark:text-slate-300">
                                    "{{ $submission->catatan_siswa }}"
                                </div>
                            @endif
                        </div>

                        @if(!$isGraded)
                            <!-- Tombol Batalkan Pengumpulan (Jika belum dinilai) -->
                            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                                <form method="POST" action="{{ route('siswa.lms.unsubmit', $lms) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengumpulan tugas ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full rounded-2xl bg-rose-50 py-2.5 text-xs font-bold text-rose-600 transition hover:bg-rose-100 dark:bg-rose-950/40 dark:text-rose-400">
                                        Batalkan Pengumpulan
                                    </button>
                                </form>
                            </div>
                        @else
                            <p class="mt-4 text-center text-xs font-semibold text-slate-400">Tugas telah dinilai dan dikunci.</p>
                        @endif
                    @endif

                    @if(!$submission || (!$isGraded && request()->has('edit')))
                        <!-- Form Pengunggahan Jawaban Tugas dengan Modern Dropzone -->
                        <form method="POST" action="{{ route('siswa.lms.submit', $lms) }}" enctype="multipart/form-data" class="mt-4 space-y-4"
                              x-data="{
                                  fileName: '',
                                  fileSize: '',
                                  isDragging: false,
                                  handleFile(e) {
                                      const file = e.target.files?.[0];
                                      if (file) {
                                          this.fileName = file.name;
                                          const size = file.size;
                                          this.fileSize = size > 1048576 
                                              ? (size / 1048576).toFixed(2) + ' MB' 
                                              : (size / 1024).toFixed(1) + ' KB';
                                      }
                                  },
                                  removeFile() {
                                      this.fileName = '';
                                      this.fileSize = '';
                                      this.$refs.fileInput.value = '';
                                  }
                              }">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                                    File Jawaban <span class="font-normal text-slate-400">(Maks 15 MB)</span>
                                </label>
                                
                                <!-- Hidden File Input -->
                                <input type="file" name="file" x-ref="fileInput" @change="handleFile($event)" class="hidden" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,.jpg,.jpeg,.png">

                                <!-- Empty Dropzone State -->
                                <div x-show="!fileName"
                                     @click="$refs.fileInput.click()"
                                     @dragover.prevent="isDragging = true"
                                     @dragleave.prevent="isDragging = false"
                                     @drop.prevent="isDragging = false; if($event.dataTransfer.files[0]) { $refs.fileInput.files = $event.dataTransfer.files; handleFile({ target: $refs.fileInput }); }"
                                     :class="isDragging ? 'border-indigo-500 bg-indigo-50/50 dark:bg-indigo-950/40 ring-2 ring-indigo-500/30' : 'border-slate-300 hover:border-indigo-400 hover:bg-slate-50/60 dark:border-slate-700 dark:hover:border-slate-600 dark:hover:bg-slate-800/40'"
                                     class="mt-2 flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed p-6 text-center transition-all">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    </div>
                                    <p class="mt-2.5 text-xs font-bold text-slate-800 dark:text-white">
                                        <span class="text-indigo-600 hover:underline dark:text-indigo-400">Pilih berkas jawaban</span> atau tarik ke sini
                                    </p>
                                    <p class="mt-1 text-[11px] text-slate-400 dark:text-slate-500">PDF, Word, Excel, PPT, Gambar, atau ZIP</p>
                                </div>

                                <!-- Selected File Preview Card -->
                                <div x-show="fileName" x-cloak
                                     class="mt-2 flex items-center justify-between rounded-2xl border border-indigo-200 bg-indigo-50/40 p-3.5 dark:border-indigo-900/40 dark:bg-indigo-950/20">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-2xs">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate text-xs font-bold text-slate-900 dark:text-white" x-text="fileName"></p>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="text-[11px] font-semibold text-indigo-600 dark:text-indigo-400" x-text="fileSize"></span>
                                                <span class="text-[10px] rounded-md bg-emerald-100 px-1.5 py-0.5 font-bold text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">Siap dikirim</span>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" @click="removeFile()" class="rounded-lg p-1.5 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/40 dark:hover:text-rose-400" title="Hapus Berkas">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                                    Catatan Tambahan <span class="font-normal text-slate-400">(Opsional)</span>
                                </label>
                                <textarea name="catatan_siswa" rows="3" maxlength="5000" placeholder="Tuliskan catatan tambahan kepada guru pengajar..."
                                          class="mt-2 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-xs text-slate-800 shadow-2xs focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100"></textarea>
                            </div>

                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-indigo-600 py-3 text-xs font-bold text-white shadow-md shadow-indigo-200 transition hover:bg-indigo-700 dark:shadow-none">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                {{ $submission ? 'Perbarui Jawaban Tugas' : 'Kirim Jawaban Tugas' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

