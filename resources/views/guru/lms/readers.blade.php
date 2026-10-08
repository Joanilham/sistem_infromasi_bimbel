@extends('layouts.guru')

@section('title', 'Indikator Aktivitas Siswa - ' . $lms->title)

@section('content')
<div class="w-full space-y-6 pb-20" x-data="readersListApp()">
    <!-- Navigasi Kembali & Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ route('guru.lms.index') }}" class="group inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-indigo-600 transition hover:text-indigo-800 dark:text-indigo-400">
                <svg class="h-4 w-4 transition group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Ruang Belajar
            </a>
            <div class="mt-2.5 flex flex-wrap items-center gap-2">
                <span class="rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-black text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300">
                    {{ $lms->subject_label }}
                </span>
                <span class="rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-black text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                    Indikator Aktivitas Siswa
                </span>
                <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                    Rombel: {{ $rombel?->nama_kelompok ?? 'Semua Siswa' }}
                </span>
                @if($lms->file_path)
                    <span class="rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700 dark:bg-sky-950/60 dark:text-sky-300">
                        📎 Lampiran Berkas Tersedia
                    </span>
                @endif
            </div>
            <h1 class="mt-2 text-2xl font-black text-slate-900 sm:text-3xl dark:text-white">{{ $lms->title }}</h1>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                Melacak siapa yang telah membaca modul online dan siapa yang telah mengunduh berkas materi (deteksi otomatis by NISN).
            </p>
        </div>

        <div class="flex items-center gap-2">
            @if($lms->file_path)
                <a href="{{ route('guru.lms.viewer', $lms) }}" target="_blank" class="inline-flex items-center gap-2 rounded-2xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-200 transition hover:bg-indigo-700 dark:shadow-none">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Buka Dokumen
                </a>
            @endif
        </div>
    </div>

    <!-- Statistik Indikator Rombel (4 Cards) -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-xs dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Siswa di Rombel</p>
            <p class="mt-2 text-3xl font-black text-slate-900 dark:text-white">{{ $totalRombel }}</p>
            <p class="mt-1 text-[11px] text-slate-400">Kelas {{ $rombel?->nama_kelompok ?? '-' }}</p>
        </div>
        <div class="rounded-3xl border border-emerald-100 bg-emerald-50/60 p-5 shadow-xs dark:border-emerald-950/40 dark:bg-emerald-950/20">
            <div class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Sudah Membaca
            </div>
            <p class="mt-2 text-3xl font-black text-emerald-700 dark:text-emerald-300">{{ $totalRead }}</p>
            <p class="mt-1 text-[11px] font-semibold text-emerald-600 dark:text-emerald-400">{{ $readPercentage }}% dari total rombel</p>
        </div>
        <div class="rounded-3xl border border-indigo-100 bg-indigo-50/60 p-5 shadow-xs dark:border-indigo-950/40 dark:bg-indigo-950/20">
            <div class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Sudah Mengunduh
            </div>
            <p class="mt-2 text-3xl font-black text-indigo-700 dark:text-indigo-300">{{ $lms->file_path ? $totalDownloaded : '-' }}</p>
            <p class="mt-1 text-[11px] font-semibold text-indigo-600 dark:text-indigo-400">
                {{ $lms->file_path ? ($downloadPercentage . '% telah mengunduh berkas') : 'Tanpa berkas unduhan' }}
            </p>
        </div>
        <div class="rounded-3xl border border-amber-100 bg-amber-50/60 p-5 shadow-xs dark:border-amber-950/40 dark:bg-amber-950/20">
            <div class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Belum Membaca
            </div>
            <p class="mt-2 text-3xl font-black text-amber-700 dark:text-amber-300">{{ max(0, $totalRombel - $totalRead) }}</p>
            <p class="mt-1 text-[11px] font-semibold text-amber-600 dark:text-amber-400">{{ max(0, 100 - $readPercentage) }}% siswa belum akses</p>
        </div>
    </div>

    <!-- Dual Progress Bars Section -->
    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-xs dark:border-slate-800 dark:bg-slate-900">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                    <span class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                        Persentase Keterbacaan Rombel
                    </span>
                    <span class="text-emerald-600 dark:text-emerald-400">{{ $readPercentage }}% ({{ $totalRead }} / {{ $totalRombel }})</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden dark:bg-slate-800">
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-3 rounded-full transition-all duration-500" style="width: {{ $readPercentage }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                    <span class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-indigo-500"></span>
                        Tingkat Unduhan Berkas Modul
                    </span>
                    <span class="text-indigo-600 dark:text-indigo-400">
                        @if($lms->file_path)
                            {{ $downloadPercentage }}% ({{ $totalDownloaded }} / {{ $totalRombel }})
                        @else
                            Tidak Ada Lampiran
                        @endif
                    </span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden dark:bg-slate-800">
                    <div class="bg-gradient-to-r from-indigo-500 to-blue-500 h-3 rounded-full transition-all duration-500" style="width: {{ $lms->file_path ? $downloadPercentage : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian Siswa -->
    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-xs dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Filter Tabs -->
            <div class="flex items-center gap-1.5 p-1 rounded-2xl bg-slate-100 dark:bg-slate-800 overflow-x-auto shrink-0">
                <button type="button" @click="filter = 'all'" :class="filter === 'all' ? 'bg-white text-slate-900 shadow-xs dark:bg-slate-900 dark:text-white' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400'" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition whitespace-nowrap">
                    Semua ({{ $totalRombel }})
                </button>
                <button type="button" @click="filter = 'read'" :class="filter === 'read' ? 'bg-white text-emerald-700 shadow-xs dark:bg-slate-900 dark:text-emerald-400' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400'" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition whitespace-nowrap">
                    Sudah Membaca ({{ $totalRead }})
                </button>
                @if($lms->file_path)
                    <button type="button" @click="filter = 'downloaded'" :class="filter === 'downloaded' ? 'bg-white text-indigo-700 shadow-xs dark:bg-slate-900 dark:text-indigo-400' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400'" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition whitespace-nowrap">
                        Sudah Unduh ({{ $totalDownloaded }})
                    </button>
                @endif
                <button type="button" @click="filter = 'unread'" :class="filter === 'unread' ? 'bg-white text-amber-700 shadow-xs dark:bg-slate-900 dark:text-amber-400' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400'" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition whitespace-nowrap">
                    Belum Membaca ({{ max(0, $totalRombel - $totalRead) }})
                </button>
                @if($lms->file_path)
                    <button type="button" @click="filter = 'undownloaded'" :class="filter === 'undownloaded' ? 'bg-white text-rose-700 shadow-xs dark:bg-slate-900 dark:text-rose-400' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400'" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition whitespace-nowrap">
                        Belum Unduh ({{ max(0, $totalRombel - $totalDownloaded) }})
                    </button>
                @endif
            </div>

            <!-- Search Input -->
            <div class="relative flex-1 max-w-md">
                <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" x-model="search" placeholder="Cari nama siswa atau NISN..." class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 py-2 pl-10 pr-4 text-xs text-slate-800 placeholder-slate-400 outline-none transition focus:border-indigo-500 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
            </div>
        </div>

        <!-- Tabel Siswa di Rombel -->
        <div class="mt-5 overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-black uppercase tracking-wider text-slate-400 dark:border-slate-800">
                        <th class="py-3 px-3 w-12 text-center">No</th>
                        <th class="py-3 px-4">NISN</th>
                        <th class="py-3 px-4">Nama Lengkap Siswa</th>
                        <th class="py-3 px-4">L/P</th>
                        <th class="py-3 px-4">Indikator Membaca</th>
                        <th class="py-3 px-4">Indikator Download</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <template x-for="(s, index) in filteredStudents" :key="s.id">
                        <tr class="transition hover:bg-slate-50/60 dark:hover:bg-slate-800/40">
                            <td class="py-3.5 px-3 font-bold text-slate-400 text-center" x-text="index + 1"></td>
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-700 dark:text-slate-300">
                                <span class="rounded-lg bg-slate-100 px-2 py-0.5 text-[11px] dark:bg-slate-800" x-text="s.nisn"></span>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white" x-text="s.nama_lengkap"></td>
                            <td class="py-3.5 px-4 text-slate-500 font-semibold" x-text="s.jenis_kelamin || '-'"></td>
                            
                            <!-- Indikator 1: Membaca -->
                            <td class="py-3.5 px-4">
                                <template x-if="s.has_read">
                                    <div>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-black text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            Sudah Membaca
                                        </span>
                                        <div class="mt-1 text-[10px] text-slate-400 font-medium" x-text="s.read_at"></div>
                                    </div>
                                </template>
                                <template x-if="!s.has_read">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Belum Membaca
                                    </span>
                                </template>
                            </td>

                            <!-- Indikator 2: Download -->
                            <td class="py-3.5 px-4">
                                @if($lms->file_path)
                                    <template x-if="s.has_downloaded">
                                        <div>
                                            <span class="inline-flex items-center gap-1 rounded-full bg-indigo-100 px-2.5 py-1 text-[11px] font-black text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                Sudah Mengunduh
                                            </span>
                                            <div class="mt-1 text-[10px] text-slate-400 font-medium" x-text="s.downloaded_at"></div>
                                        </div>
                                    </template>
                                    <template x-if="!s.has_downloaded">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                                            Belum Mengunduh
                                        </span>
                                    </template>
                                @else
                                    <span class="text-[11px] italic text-slate-400 dark:text-slate-500">
                                        Tanpa Berkas Unduhan
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredStudents.length === 0">
                        <td colspan="6" class="py-8 text-center text-slate-400 font-semibold">
                            Tidak ada siswa yang sesuai dengan filter atau pencarian.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function readersListApp() {
        return {
            filter: 'all',
            search: '',
            students: @json($studentList),
            get filteredStudents() {
                return this.students.filter(s => {
                    // Filter Status
                    if (this.filter === 'read' && !s.has_read) return false;
                    if (this.filter === 'downloaded' && !s.has_downloaded) return false;
                    if (this.filter === 'unread' && s.has_read) return false;
                    if (this.filter === 'undownloaded' && s.has_downloaded) return false;
                    
                    // Filter Search (Nama / NISN)
                    if (this.search.trim()) {
                        const q = this.search.toLowerCase();
                        const matchName = (s.nama_lengkap || '').toLowerCase().includes(q);
                        const matchNisn = (s.nisn || '').toLowerCase().includes(q);
                        return matchName || matchNisn;
                    }
                    return true;
                });
            }
        };
    }
</script>
@endsection
