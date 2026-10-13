@extends('layouts.guru')

@section('title', 'Buat Konten LMS')

@section('content')
<div class="mx-auto max-w-4xl space-y-6 pb-12">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('guru.lms.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Arsip LMS
            </a>
            <h1 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">Buat Materi atau Tugas Baru</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Bagikan modul pembelajaran atau tugaskan pekerjaan ke siswa di kelompok belajar Anda.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="rounded-2xl border border-red-200 bg-red-50 p-4 dark:border-red-900/40 dark:bg-red-950/30">
        <div class="flex items-center gap-2 font-bold text-red-700 dark:text-red-400 text-sm">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            Mohon perbaiki kesalahan berikut:
        </div>
        <ul class="mt-2 list-inside list-disc text-xs text-red-600 dark:text-red-400 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('guru.lms.store') }}" enctype="multipart/form-data" 
          x-data="{ 
              type: '{{ old('type', 'materi') }}',
              fileName: '',
              fileSize: '',
              isDragging: false,
              handleFileChange(e) {
                  const file = e.target.files[0];
                  this.processFile(file);
              },
              handleDrop(e) {
                  this.isDragging = false;
                  if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                      const file = e.dataTransfer.files[0];
                      this.$refs.fileInput.files = e.dataTransfer.files;
                      this.processFile(file);
                  }
              },
              processFile(file) {
                  if (!file) return;
                  if (file.size > 15 * 1024 * 1024) {
                      Swal.fire({
                          icon: 'error',
                          title: 'Berkas Terlalu Besar',
                          text: 'Ukuran berkas maksimal adalah 15 MB. Ukuran berkas Anda: ' + (file.size / (1024 * 1024)).toFixed(2) + ' MB',
                          confirmButtonColor: '#4f46e5'
                      });
                      this.removeFile();
                      return;
                  }
                  this.fileName = file.name;
                  this.fileSize = file.size < 1024 * 1024 
                      ? (file.size / 1024).toFixed(1) + ' KB' 
                      : (file.size / (1024 * 1024)).toFixed(2) + ' MB';
              },
              removeFile() {
                  this.fileName = '';
                  this.fileSize = '';
                  this.$refs.fileInput.value = '';
              }
          }"
          class="space-y-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
        @csrf

        <!-- Tipe Konten -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Tipe Konten <span class="text-red-500">*</span></label>
            <div class="mt-2.5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label :class="type === 'materi' ? 'border-indigo-600 bg-indigo-50/60 ring-2 ring-indigo-500/20 text-indigo-900 dark:border-indigo-500 dark:bg-indigo-950/40 dark:text-indigo-200' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300'"
                       class="relative flex cursor-pointer rounded-2xl border-2 p-4 transition-all focus:outline-none">
                    <input type="radio" name="type" value="materi" x-model="type" class="sr-only">
                    <div class="flex items-center gap-3.5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div>
                            <span class="block text-sm font-black">Materi Pembelajaran</span>
                            <span class="block text-xs text-slate-500 dark:text-slate-400 mt-0.5">Modul, artikel bacaan, bahan ajar PDF/PPT.</span>
                        </div>
                    </div>
                </label>

                <label :class="type === 'tugas' ? 'border-amber-500 bg-amber-50/60 ring-2 ring-amber-500/20 text-amber-900 dark:border-amber-500 dark:bg-amber-950/40 dark:text-amber-200' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300'"
                       class="relative flex cursor-pointer rounded-2xl border-2 p-4 transition-all focus:outline-none">
                    <input type="radio" name="type" value="tugas" x-model="type" class="sr-only">
                    <div class="flex items-center gap-3.5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-900/50 dark:text-amber-400">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <div>
                            <span class="block text-sm font-black">Penugasan Siswa</span>
                            <span class="block text-xs text-slate-500 dark:text-slate-400 mt-0.5">Instruksi tugas, batas pengumpulan, nilai & feedback.</span>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Grid: Kelompok Belajar & Mata Pelajaran Terkunci -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Target Kelompok Belajar -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Target Kelompok Belajar <span class="text-red-500">*</span></label>
                <div class="relative mt-2">
                    <select name="kelompok_belajar_id" required 
                            class="no-tomselect block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-800 shadow-xs transition hover:border-slate-300 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                        <option value="">-- Pilih Kelompok Belajar --</option>
                        @foreach($kelompokBelajars as $kelompok)
                            <option value="{{ $kelompok->id }}" @selected(old('kelompok_belajar_id') == $kelompok->id)>
                                {{ $kelompok->nama_kelompok }} ({{ $kelompok->siswa_aktif_count }} siswa aktif)
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($kelompokBelajars->isEmpty())
                    <p class="mt-2 text-xs font-medium text-amber-600 dark:text-amber-400">Belum ada kelompok belajar yang tersedia.</p>
                @endif
            </div>

            <!-- Mata Pelajaran Pengampu -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                    Mata Pelajaran <span class="text-red-500">*</span>
                </label>
                <div class="mt-2">
                    @if(count($guruMapels) === 1)
                        <input type="hidden" name="subject" value="{{ $guruMapels[0] }}">
                        <div class="flex items-center justify-between rounded-2xl border border-indigo-100 bg-indigo-50/60 px-4 py-3 dark:border-indigo-900/40 dark:bg-indigo-950/30">
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-600 text-white shadow-2xs">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                <span class="text-sm font-black text-indigo-900 dark:text-indigo-200">{{ $guruMapels[0] }}</span>
                            </div>
                            <span class="inline-flex items-center gap-1 rounded-md bg-white px-2 py-0.5 text-[10px] font-bold text-slate-600 shadow-2xs dark:bg-slate-800 dark:text-slate-300">
                                1 Mapel Aktif
                            </span>
                        </div>
                    @else
                        <select name="subject" required 
                                class="no-tomselect block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-800 shadow-xs transition hover:border-slate-300 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($guruMapels as $mapelItem)
                                <option value="{{ $mapelItem }}" @selected(old('subject') == $mapelItem)>
                                    {{ $mapelItem }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <p class="mt-1 text-[11px] text-slate-400 dark:text-slate-500">Materi & tugas akan terhubung ke mata pelajaran yang dipilih.</p>
            </div>
        </div>

        <!-- Judul Konten -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Judul Materi / Tugas <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title') }}" required maxlength="255" placeholder="Contoh: Modul 01 - Pengenalan Aljabar Linear"
                   class="mt-2 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-800 shadow-xs transition hover:border-slate-300 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
        </div>

        <!-- Batas Waktu Pengumpulan (Hanya untuk Tugas) -->
        <div x-show="type === 'tugas'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Batas Waktu Pengumpulan (Deadline) <span class="text-amber-500">*</span></label>
            <input type="datetime-local" name="due_at" value="{{ old('due_at') }}"
                   class="mt-2 block w-full max-w-md rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-800 shadow-xs transition hover:border-slate-300 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
            <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Pengumpulan siswa yang melewati batas ini akan ditandai terlambat secara otomatis.</p>
        </div>

        <!-- Deskripsi / Petunjuk Belajar -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Deskripsi / Petunjuk Belajar</label>
            <textarea name="description" rows="4" maxlength="10000" placeholder="Tuliskan petunjuk belajar, panduan pengerjaan tugas, atau catatan penting untuk siswa..."
                      class="mt-2 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-xs transition hover:border-slate-300 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">{{ old('description') }}</textarea>
        </div>

        <!-- Lampiran Dokumen Interaktif -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Lampiran Dokumen / Berkas <span class="font-normal text-slate-400">(Opsional, Maks. 15 MB)</span></label>
            
            <!-- Hidden native file input -->
            <input type="file" name="file" x-ref="fileInput" @change="handleFileChange($event)" class="hidden" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,.jpg,.jpeg,.png">

            <!-- Empty Dropzone State -->
            <div x-show="!fileName"
                 @click="$refs.fileInput.click()"
                 @dragover.prevent="isDragging = true"
                 @dragleave.prevent="isDragging = false"
                 @drop.prevent="handleDrop($event)"
                 :class="isDragging ? 'border-indigo-500 bg-indigo-50/50 dark:bg-indigo-950/30 ring-2 ring-indigo-500/30' : 'border-slate-300 hover:border-indigo-400 hover:bg-slate-50/50 dark:border-slate-700 dark:hover:border-slate-600 dark:hover:bg-slate-800/40'"
                 class="mt-2 flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed p-7 text-center transition-all duration-200">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 transition group-hover:scale-105 dark:bg-indigo-950/50 dark:text-indigo-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                </div>
                <div class="mt-3">
                    <p class="text-sm font-bold text-slate-800 dark:text-white">
                        <span class="text-indigo-600 hover:underline dark:text-indigo-400">Klik untuk memilih berkas</span> atau seret berkas ke sini
                    </p>
                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Format didukung: PDF, Word, Excel, PPT, Gambar, atau ZIP (Maksimal 15 MB)</p>
                </div>
            </div>

            <!-- Selected File Card State -->
            <div x-show="fileName" x-cloak
                 class="mt-2 flex items-center justify-between rounded-2xl border border-indigo-200 bg-indigo-50/40 p-4 dark:border-indigo-900/40 dark:bg-indigo-950/20">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-xs">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-bold text-slate-900 dark:text-white" x-text="fileName"></p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400" x-text="fileSize"></span>
                            <span class="text-[10px] rounded-md bg-emerald-100 px-1.5 py-0.5 font-bold text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">Siap diunggah</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0 ml-4">
                    <button type="button" @click="$refs.fileInput.click()" 
                            class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 shadow-2xs transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        Ganti
                    </button>
                    <button type="button" @click="removeFile()" 
                            class="rounded-xl bg-red-50 p-1.5 text-red-600 transition hover:bg-red-100 dark:bg-red-950/50 dark:text-red-400" title="Hapus Berkas">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi Simpan -->
        <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-100 dark:border-slate-800">
            <a href="{{ route('guru.lms.index') }}" class="rounded-xl px-5 py-2.5 text-xs font-bold text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Batal</a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-200 transition hover:bg-indigo-700 dark:shadow-none">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Publikasikan Konten
            </button>
        </div>
    </form>
</div>
@endsection
