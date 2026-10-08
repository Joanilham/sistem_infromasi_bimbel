@extends('layouts.guru')

@section('title', 'LMS Guru')

@section('content')
<div class="w-full space-y-6 pb-16">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2">
                <p class="text-xs font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Learning Management System</p>
                @if($selectedSubject)
                    <span class="rounded-lg bg-indigo-50 px-2.5 py-0.5 text-[11px] font-bold text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300">{{ $selectedSubject }}</span>
                @else
                    <span class="rounded-lg bg-indigo-50 px-2.5 py-0.5 text-[11px] font-bold text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300">{{ count($guruMapels) > 1 ? count($guruMapels) . ' Mata Pelajaran' : ($guruMapels[0] ?? 'Umum') }}</span>
                @endif
            </div>
            <h1 class="mt-1 text-2xl font-black text-slate-900 dark:text-white">Materi & Penugasan Siswa</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Kelola modul pembelajaran digital, tugas, dan evaluasi hasil belajar siswa.</p>
        </div>
        <div class="flex items-center sm:justify-end sm:ml-auto shrink-0">
            <a href="{{ route('guru.lms.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-indigo-600 px-5 py-3 text-xs font-bold text-white shadow-md shadow-indigo-200 transition hover:bg-indigo-700 dark:shadow-none whitespace-nowrap">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Materi / Tugas
            </a>
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Modul</p>
            <p class="mt-2 text-3xl font-black text-slate-900 dark:text-white">{{ $contents->total() }}</p>
        </div>
        <div class="rounded-3xl border border-indigo-100 bg-indigo-50/50 p-5 shadow-sm dark:border-indigo-950/40 dark:bg-indigo-950/20">
            <p class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Materi Bacaan</p>
            <p class="mt-2 text-3xl font-black text-indigo-700 dark:text-indigo-300">{{ $totalMateri }}</p>
        </div>
        <div class="rounded-3xl border border-amber-100 bg-amber-50/50 p-5 shadow-sm dark:border-amber-950/40 dark:bg-amber-950/20">
            <p class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">Tugas Terjadwal</p>
            <p class="mt-2 text-3xl font-black text-amber-700 dark:text-amber-300">{{ $totalTugas }}</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Mata Pelajaran</p>
            <p class="mt-2 text-base font-black truncate text-slate-800 dark:text-slate-200">
                {{ $selectedSubject ?: (count($guruMapels) > 1 ? count($guruMapels) . ' Mapel Diampu' : ($guruMapels[0] ?? 'Umum')) }}
            </p>
        </div>
    </div>

    <!-- Filter & Pencarian Bar -->
    <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <form method="GET" action="{{ route('guru.lms.index') }}" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center">
                <!-- Search Box -->
                <div class="relative flex-1">
                    <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau topik materi/tugas..."
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 py-2.5 pl-10 pr-4 text-xs text-slate-800 placeholder-slate-400 outline-none transition focus:border-indigo-500 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                </div>

                <!-- Dropdown Mata Pelajaran (Jika mengajar > 1 Mapel) -->
                @if(count($guruMapels) > 1)
                    <select name="subject" onchange="this.form.submit()" class="no-tomselect rounded-2xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs font-semibold text-slate-700 outline-none transition hover:border-slate-300 focus:border-indigo-500 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        <option value="">Semua Mata Pelajaran</option>
                        @foreach($guruMapels as $mapelItem)
                            <option value="{{ $mapelItem }}" @selected(request('subject') == $mapelItem)>
                                {{ $mapelItem }}
                            </option>
                        @endforeach
                    </select>
                @endif

                <!-- Dropdown Kelompok Belajar -->
                <select name="kelompok_belajar_id" onchange="this.form.submit()" class="no-tomselect rounded-2xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs font-semibold text-slate-700 outline-none transition hover:border-slate-300 focus:border-indigo-500 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                    <option value="">Semua Kelompok Belajar</option>
                    @foreach($kelompokBelajars as $kb)
                        <option value="{{ $kb->id }}" @selected(request('kelompok_belajar_id') == $kb->id)>
                            {{ $kb->nama_kelompok }}
                        </option>
                    @endforeach
                </select>

                <!-- Filter Tipe -->
                <select name="type" onchange="this.form.submit()" class="no-tomselect rounded-2xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs font-semibold text-slate-700 outline-none transition hover:border-slate-300 focus:border-indigo-500 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                    <option value="">Semua Tipe</option>
                    <option value="materi" @selected(request('type') === 'materi')>Hanya Materi</option>
                    <option value="tugas" @selected(request('type') === 'tugas')>Hanya Tugas</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                @if(request()->anyFilled(['search', 'kelompok_belajar_id', 'type', 'subject']))
                    <a href="{{ route('guru.lms.index') }}" class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300">
                        Reset Filter
                    </a>
                @endif
                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700">
                    Terapkan
                </button>
            </div>
        </form>
    </div>

    <!-- Grid Konten LMS -->
    @if($contents->isEmpty())
        <div class="rounded-3xl border border-dashed border-slate-300 p-12 text-center dark:border-slate-800">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <h3 class="mt-4 text-base font-black text-slate-800 dark:text-white">Belum Ada Materi atau Tugas</h3>
            <p class="mt-1 text-xs text-slate-500">Mulai buat modul atau tugas pertama Anda untuk kelompok belajar aktif.</p>
            <div class="mt-5">
                <a href="{{ route('guru.lms.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-indigo-700">
                    Buat Sekarang
                </a>
            </div>
        </div>
    @else
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($contents as $item)
                @php
                    $isTugas = $item->isAssignment();
                    $totalRombel = $item->kelompokBelajar?->total_siswa ?? 0;
                    $readCount = $item->readers_count ?? $item->completions_count ?? 0;
                    $downloadCount = $item->downloaders_count ?? 0;
                @endphp
                <article onclick="if (!event.target.closest('a, button, form, input')) { window.location.href = '{{ $isTugas ? route('guru.lms.submissions', $item) : route('guru.lms.readers', $item) }}'; }"
                         class="group relative flex flex-col justify-between rounded-3xl border border-slate-200 bg-white p-6 shadow-xs transition-all duration-200 hover:-translate-y-1 hover:shadow-xl hover:border-indigo-300 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-indigo-600/50 cursor-pointer">
                    <div>
                        <!-- Header Card -->
                        <div class="flex items-center justify-between gap-2">
                            <span class="rounded-xl px-2.5 py-1 text-[11px] font-black uppercase {{ $isTugas ? 'bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300' : 'bg-indigo-100 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300' }}">
                                {{ $item->type_label }}
                            </span>
                            <span class="rounded-lg bg-slate-100 px-2 py-0.5 text-[11px] font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                {{ $item->kelompokBelajar?->nama_kelompok ?? 'Umum' }}
                            </span>
                        </div>

                        <!-- Judul & Tanggal -->
                        <h2 class="mt-4 text-base font-black leading-snug text-slate-900 dark:text-white line-clamp-2 transition group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                            {{ $item->title }}
                        </h2>
                        <p class="mt-1 text-[11px] text-slate-400">
                            Dibuat: {{ $item->created_at->translatedFormat('d M Y, H:i') }}
                        </p>

                        @if($isTugas)
                            <div class="mt-3 flex items-center gap-1.5 rounded-xl bg-amber-50 p-2.5 text-xs text-amber-800 dark:bg-amber-950/30 dark:text-amber-300">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Batas: <strong>{{ $item->due_at ? $item->due_at->translatedFormat('d M Y, H:i') : 'Tidak ditentukan' }}</strong></span>
                            </div>
                        @endif

                        @if($item->description)
                            <p class="mt-3 text-xs leading-5 text-slate-600 line-clamp-3 dark:text-slate-300">
                                {{ $item->description }}
                            </p>
                        @endif
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 space-y-3">
                        <!-- Lampiran jika ada -->
                        @if($item->file_path)
                            <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2 text-xs dark:bg-slate-800/60">
                                <span class="truncate max-w-[170px] text-[11px] font-semibold text-slate-600 dark:text-slate-300" title="{{ $item->file_name }}">
                                    📎 {{ $item->file_name }}
                                </span>
                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="{{ route('guru.lms.viewer', $item) }}" onclick="event.stopPropagation();" target="_blank" class="font-bold text-indigo-600 hover:underline">Buka</a>
                                    <span class="text-slate-300">&bull;</span>
                                    <a href="{{ route('guru.lms.download', $item) }}" onclick="event.stopPropagation();" class="text-slate-500 hover:underline">Unduh</a>
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between gap-2">
                            @if($isTugas)
                                <a href="{{ route('guru.lms.submissions', $item) }}" onclick="event.stopPropagation();" class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 transition hover:bg-indigo-100 dark:bg-indigo-950/60 dark:text-indigo-300">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    Jawaban ({{ $item->submissions_count }})
                                </a>
                            @else
                                <a href="{{ route('guru.lms.readers', $item) }}"
                                   onclick="event.stopPropagation();"
                                   class="group inline-flex items-center gap-2 rounded-xl border border-emerald-200/70 bg-emerald-50/80 px-2.5 py-1.5 text-[11px] font-bold text-emerald-800 transition hover:bg-emerald-100 hover:border-emerald-300 hover:shadow-xs dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-300 dark:hover:bg-emerald-900/60 cursor-pointer"
                                   title="Klik untuk memonitor indikator siswa membaca & mengunduh modul">
                                    <span class="inline-flex items-center gap-1 text-emerald-700 dark:text-emerald-300">
                                        <svg class="h-3.5 w-3.5 text-emerald-600 transition group-hover:scale-110 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        {{ $readCount }} / {{ $totalRombel }} Baca
                                    </span>
                                    @if($item->file_path)
                                        <span class="text-slate-300 dark:text-slate-600">&bull;</span>
                                        <span class="inline-flex items-center gap-1 text-indigo-700 dark:text-indigo-300">
                                            <svg class="h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            {{ $downloadCount }} Unduh
                                        </span>
                                    @endif
                                </a>
                            @endif

                            <div class="flex items-center gap-1">
                                <a href="{{ route('guru.lms.edit', $item) }}" onclick="event.stopPropagation();" class="rounded-lg p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800" title="Edit Konten">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>

                                <form method="POST" action="{{ route('guru.lms.destroy', $item) }}" onclick="event.stopPropagation();" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi/tugas ini beserta seluruh pengumpulan terkait?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded-lg p-1.5 text-slate-400 transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-950/40" title="Hapus Konten">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $contents->links() }}
        </div>
    @endif
</div>
@endsection
