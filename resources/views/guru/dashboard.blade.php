@extends('layouts.guru')

@section('title', 'Dashboard Guru')

@section('content')

@php
    $rawName = $guru->name ?? 'Guru';
    $cleanName = str_contains($rawName, '@') ? ucwords(str_replace(['.', '_', '-'], ' ', explode('@', $rawName)[0])) : $rawName;
    $mapel = $guru->matapelajaran ?: 'Mata Pelajaran Umum';
@endphp

@push('head')
<style>
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(60px, -60px) scale(1.2); }
        66% { transform: translate(-40px, 40px) scale(0.8); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
</style>
@endpush

{{-- ─── Executive Welcome Hero Banner ─── --}}
<div class="relative mb-8 rounded-3xl shadow-xl z-20 overflow-hidden" x-data="dashboardClock">
    {{-- Animated gradient background layer --}}
    <div class="absolute inset-0 rounded-3xl overflow-hidden pointer-events-none -z-10"
         style="background: linear-gradient(135deg, #0d9488 0%, #115e59 45%, #042f2e 100%);">
        {{-- Animated Blobs --}}
        <div class="absolute -top-20 -left-20 w-80 h-80 rounded-full bg-emerald-400/30 blur-3xl animate-blob pointer-events-none"></div>
        <div class="absolute top-0 -right-20 w-96 h-96 rounded-full bg-white/20 blur-3xl animate-blob animation-delay-2000 pointer-events-none"></div>
        <div class="absolute -bottom-32 left-1/3 w-80 h-80 rounded-full bg-teal-300/30 blur-3xl animate-blob animation-delay-4000 pointer-events-none"></div>
        {{-- Dot grid overlay --}}
        <div class="absolute inset-0 opacity-[0.05]"
            style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;">
        </div>
    </div>

    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-px bg-gradient-to-r from-transparent via-white/20 to-transparent pointer-events-none"></div>

    <div class="relative z-10 p-6 sm:p-8 lg:p-10 flex flex-col xl:flex-row xl:items-center justify-between gap-6 sm:gap-8">
        {{-- Left: Greeting & Status --}}
        <div class="flex-1 min-w-0">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/25 mb-4 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                <span class="text-xs font-bold tracking-widest text-emerald-100 uppercase">Status Pengajar Aktif</span>
            </div>

            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight mb-3 tracking-tight">
                Selamat Datang,<br>
                <span class="text-emerald-200">{{ $cleanName }}</span>
            </h1>

            <p class="text-emerald-50/95 text-sm sm:text-base leading-relaxed max-w-2xl mb-6">
                Kelola jadwal pembelajaran, bank butir soal, dan pantau hasil evaluasi ujian CBT peserta didik dalam satu portal terpadu.
            </p>

            {{-- Info Capsules --}}
            <div class="flex flex-wrap items-center gap-2.5 sm:gap-3 text-xs font-semibold">
                <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white/15 text-white border border-white/20 backdrop-blur-md shadow-xs">
                    <svg class="w-4 h-4 text-emerald-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>Bidang: <strong class="text-white">{{ $mapel }}</strong></span>
                </div>

                <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white/15 text-white border border-white/20 backdrop-blur-md shadow-xs">
                    <svg class="w-4 h-4 text-emerald-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Right: Compact Real-Time Clock Widget --}}
        <div class="shrink-0 bg-white/15 backdrop-blur-xl rounded-2xl border border-white/25 p-5 sm:p-6 min-w-0 sm:min-w-[240px] text-center shadow-lg text-white">
            <div class="flex items-center justify-center gap-1.5 text-emerald-200 mb-1.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-100" x-text="date">Memuat...</span>
            </div>
            <div class="text-3xl sm:text-4xl font-black tabular-nums tracking-tight text-white my-1" x-text="time">00:00:00</div>
            <div class="inline-block mt-1 text-[10px] font-bold text-white bg-white/20 border border-white/30 rounded-lg py-1 px-2.5 shadow-xs" x-text="zonaWaktu">
                Waktu Indonesia Barat (WIB)
            </div>
        </div>
    </div>
</div>

{{-- ─── Stat Cards (Consistent with Super Admin Role Design) ─── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    {{-- Card 1: Jadwal Mengajar Hari Ini --}}
    <a href="{{ route('guru.jadwal.index') }}" class="group relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 sm:p-6 shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 overflow-hidden block">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jadwal Hari Ini</span>
            <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
        <p class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-1.5">{{ $jadwalHariIni }}</p>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full {{ $jadwalHariIni > 0 ? 'bg-sky-500' : 'bg-slate-300' }}"></span>
            <span>{{ $jadwalHariIni > 0 ? $jadwalHariIni . ' sesi mengajar aktif' : 'Tidak ada sesi mengajar hari ini' }}</span>
        </p>
        <div class="absolute bottom-0 left-0 h-1 w-full bg-sky-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
    </a>

    {{-- Card 2: Bank Soal Tersedia --}}
    <a href="{{ route('guru.bank-soal.index') }}" class="group relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 sm:p-6 shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 overflow-hidden block">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Bank Soal Tersedia</span>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
        <p class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-1.5">{{ $bankSoalCount }}</p>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full {{ $bankSoalCount > 0 ? 'bg-indigo-500' : 'bg-slate-300' }}"></span>
            <span>{{ $bankSoalCount > 0 ? 'Koleksi soal siap diujikan' : 'Belum ada bank soal' }}</span>
        </p>
        <div class="absolute bottom-0 left-0 h-1 w-full bg-indigo-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
    </a>

    {{-- Card 3: Ujian Aktif --}}
    <a href="{{ route('guru.ujian.index') }}" class="group relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 sm:p-6 shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 overflow-hidden block">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ujian Aktif</span>
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
        </div>
        <p class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-1.5">{{ $ujianAktifCount }}</p>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full {{ $ujianAktifCount > 0 ? 'bg-amber-500' : 'bg-slate-300' }}"></span>
            <span>{{ $ujianAktifCount > 0 ? 'Sedang dapat diakses siswa' : 'Tidak ada ujian aktif' }}</span>
        </p>
        <div class="absolute bottom-0 left-0 h-1 w-full bg-amber-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
    </a>

    {{-- Card 4: Total Siswa Terdaftar --}}
    <div class="group relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 sm:p-6 shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 overflow-hidden block">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Siswa</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
        <p class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-1.5">{{ $totalSiswa }}</p>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            <span>Peserta didik terdaftar di bimbel</span>
        </p>
        <div class="absolute bottom-0 left-0 h-1 w-full bg-emerald-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
    </div>

</div>

{{-- ─── Akses Cepat (Quick Actions) ─── --}}
<div class="mb-4">
    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight">Akses Cepat</h2>
    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Pintasan navigasi untuk aktivitas pengajaran harian</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    {{-- Action LMS --}}
    <a href="{{ route('guru.lms.create') }}" class="group bg-indigo-600 rounded-2xl p-4 sm:p-5 shadow-xs hover:bg-indigo-700 hover:shadow-md transition-all flex items-center gap-4 text-white">
        <div class="w-11 h-11 rounded-xl bg-white/15 flex items-center justify-center shrink-0"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332 0.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18 18.247 18 16.5 18c-1.746 0-3.332 0.477-4.5 1.253" /></svg></div>
        <div class="flex-1 min-w-0"><h3 class="font-bold text-sm">Buat Konten LMS</h3><p class="text-xs text-indigo-100 mt-0.5 truncate">Upload materi atau buat tugas</p></div>
    </a>
    {{-- Action 1: Jadwal Mengajar --}}
    <a href="{{ route('guru.jadwal.index') }}"
        class="group bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-5 border border-slate-100 dark:border-slate-800 shadow-xs hover:border-indigo-300 dark:hover:border-indigo-700 hover:shadow-md transition-all flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="font-bold text-slate-900 dark:text-white text-sm group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Lihat Jadwal Mengajar</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate">Pantau dan kelola jadwal sesi mengajar</p>
        </div>
        <div class="w-7 h-7 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        </div>
    </a>

    {{-- Action 2: Tambah Soal CBT --}}
    <a href="{{ route('guru.bank-soal.create') }}"
        class="group bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-5 border border-slate-100 dark:border-slate-800 shadow-xs hover:border-emerald-300 dark:hover:border-emerald-700 hover:shadow-md transition-all flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="font-bold text-slate-900 dark:text-white text-sm group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Tambah Bank Soal</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate">Buat butir soal baru untuk ujian CBT</p>
        </div>
        <div class="w-7 h-7 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-400 group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-colors shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        </div>
    </a>

    {{-- Action 3: Rancang Ujian Baru --}}
    <a href="{{ route('guru.ujian.create') }}"
        class="group bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-5 border border-slate-100 dark:border-slate-800 shadow-xs hover:border-amber-300 dark:hover:border-amber-700 hover:shadow-md transition-all flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="font-bold text-slate-900 dark:text-white text-sm group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Rancang Ujian Baru</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate">Konfigurasi jadwal, token, dan peserta ujian</p>
        </div>
        <div class="w-7 h-7 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-400 group-hover:bg-amber-50 group-hover:text-amber-600 transition-colors shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        </div>
    </a>
</div>

{{-- ─── LMS & Pengumpulan Tugas Siswa Section ─── --}}
<div class="mb-8" x-data="{
    tab: 'all',
    search: '',
    matchSearch(text) {
        if (!this.search.trim()) return true;
        return (text || '').toLowerCase().includes(this.search.toLowerCase().trim());
    }
}">
    {{-- Section Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div>
            <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 animate-pulse"></div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight">Pengumpulan Tugas Siswa & Aktivitas LMS</h2>
                @if(($unreadNotifCount ?? 0) > 0)
                    <span class="px-2 py-0.5 text-[11px] font-black bg-rose-500 text-white rounded-full">
                        {{ $unreadNotifCount }} baru
                    </span>
                @endif
            </div>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                Monitoring pengumpulan tugas dari siswa secara real-time dan evaluasi hasil belajar
            </p>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-auto">
            <a href="{{ route('guru.lms.index') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition-all inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                <span>Kelola LMS</span>
            </a>
            <a href="{{ route('notifikasi.lms.index') }}" class="px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-xs transition-all inline-flex items-center gap-1.5">
                <span>Pusat Notifikasi</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    {{-- Main Container Grid (Submissions Stream on Left, Active Assignments on Right) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Left Column: Submissions & Notifications Stream (8 Cols) --}}
        <div class="lg:col-span-8 flex flex-col gap-4">

            {{-- Filter & Search Toolbar --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-3.5 sm:p-4 border border-slate-100 dark:border-slate-800 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                {{-- Filter Tabs --}}
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0 text-xs font-bold scrollbar-none">
                    <button type="button" @click="tab = 'all'"
                            class="px-3 py-1.5 rounded-xl transition-all cursor-pointer shrink-0"
                            :class="tab === 'all' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'">
                        Semua
                    </button>
                    <button type="button" @click="tab = 'perlu_dinilai'"
                            class="px-3 py-1.5 rounded-xl transition-all cursor-pointer shrink-0 inline-flex items-center gap-1.5"
                            :class="tab === 'perlu_dinilai' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'">
                        <span>Perlu Dinilai</span>
                        @if(($pendingReviewCount ?? 0) > 0)
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black" :class="tab === 'perlu_dinilai' ? 'bg-white text-indigo-700' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-200'">{{ $pendingReviewCount }}</span>
                        @endif
                    </button>
                    <button type="button" @click="tab = 'terlambat'"
                            class="px-3 py-1.5 rounded-xl transition-all cursor-pointer shrink-0"
                            :class="tab === 'terlambat' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'">
                        Terlambat
                    </button>
                    <button type="button" @click="tab = 'unread'"
                            class="px-3 py-1.5 rounded-xl transition-all cursor-pointer shrink-0 inline-flex items-center gap-1.5"
                            :class="tab === 'unread' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'">
                        <span>Belum Dibaca</span>
                        @if(($unreadNotifCount ?? 0) > 0)
                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        @endif
                    </button>
                </div>

                {{-- Search Box --}}
                <div class="relative shrink-0 sm:w-56">
                    <input type="text"
                           x-model="search"
                           placeholder="Cari siswa atau tugas..."
                           class="w-full text-xs pl-8 pr-7 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-hidden dark:text-white placeholder:text-slate-400">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <button type="button" x-show="search.length > 0" @click="search = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        &times;
                    </button>
                </div>
            </div>

            {{-- Submissions & Notifications Stream Cards --}}
            <div class="space-y-3">
                @php
                    $hasItems = ($recentSubmissions && $recentSubmissions->isNotEmpty()) || ($notifikasiGuru && $notifikasiGuru->isNotEmpty());
                @endphp

                @if(!$hasItems)
                    {{-- Empty State --}}
                    <div class="bg-white dark:bg-slate-900 rounded-2xl p-8 border border-slate-100 dark:border-slate-800 text-center">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-3 shadow-xs">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white">Belum Ada Pengumpulan Tugas</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-sm mx-auto">
                            Saat siswa mulai mengumpulkan tugas, daftar submission dan notifikasi masuk akan langsung tampil di sini untuk diperiksa.
                        </p>
                        <a href="{{ route('guru.lms.create') }}" class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-xs transition-colors">
                            <span>Buat Tugas Baru</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </a>
                    </div>
                @else
                    {{-- 1. Display Actual Submissions from LmsSubmission --}}
                    @foreach($recentSubmissions as $sub)
                        @php
                            $subStudentName = $sub->pesertaDidik?->nama_lengkap ?? 'Siswa';
                            $subRombel = $sub->pesertaDidik?->kelompokBelajar?->nama_kelompok ?? ($sub->content?->kelompokBelajar?->nama_kelompok ?? 'Kelas');
                            $subTitle = $sub->content?->title ?? 'Tugas';
                            $isGraded = $sub->isGraded();
                            $isLate = (bool) $sub->is_late;
                            $score = $sub->nilai;
                            $checkUrl = route('guru.lms.submissions', $sub->lms_content_id, false);
                            $searchHaystack = strtolower($subStudentName . ' ' . $subRombel . ' ' . $subTitle);
                        @endphp
                        <div x-show="(tab === 'all' || (tab === 'perlu_dinilai' && {{ $isGraded ? 'false' : 'true' }}) || (tab === 'terlambat' && {{ $isLate ? 'true' : 'false' }})) && matchSearch('{{ addslashes($searchHaystack) }}')"
                             x-transition
                             class="group bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-100 dark:border-slate-800 shadow-xs hover:border-indigo-200 dark:hover:border-indigo-800 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            
                            <div class="flex items-start gap-3.5 min-w-0">
                                <div class="w-10 h-10 rounded-xl {{ $isLate ? 'bg-rose-50 dark:bg-rose-950/50 text-rose-600' : 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600' }} flex items-center justify-center font-black text-sm shrink-0 shadow-xs">
                                    {{ strtoupper(substr($subStudentName, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <span class="font-bold text-slate-900 dark:text-white text-sm truncate">
                                            {{ $subStudentName }}
                                        </span>
                                        <span class="text-[11px] font-semibold text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md">
                                            {{ $subRombel }}
                                        </span>
                                        @if($isLate)
                                            <span class="text-[10px] font-black text-rose-700 bg-rose-100 dark:bg-rose-950/80 dark:text-rose-300 px-2 py-0.5 rounded-md">
                                                Terlambat
                                            </span>
                                        @else
                                            <span class="text-[10px] font-black text-emerald-700 bg-emerald-100 dark:bg-emerald-950/80 dark:text-emerald-300 px-2 py-0.5 rounded-md">
                                                Tepat Waktu
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 font-medium truncate flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Tugas: <strong class="text-slate-800 dark:text-slate-200">{{ $subTitle }}</strong></span>
                                    </p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">
                                        Dikumpulkan {{ $sub->submitted_at?->diffForHumans() ?? 'baru saja' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100 dark:border-slate-800">
                                @if($isGraded)
                                    <div class="text-right">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Nilai</span>
                                        <span class="text-xs font-black text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-md">
                                            {{ $score }}/100
                                        </span>
                                    </div>
                                @else
                                    <span class="text-[11px] font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/60 px-2.5 py-1 rounded-lg">
                                        Menunggu Dinilai
                                    </span>
                                @endif

                                <a href="{{ $checkUrl }}" class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-600 text-indigo-600 hover:text-white dark:bg-indigo-950/60 dark:text-indigo-400 dark:hover:bg-indigo-600 dark:hover:text-white text-xs font-bold transition-all inline-flex items-center gap-1.5 shadow-xs">
                                    <span>Periksa</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach

                    {{-- 2. Display Notification Items (For unread tab or if submissions table hasn't populated this record yet) --}}
                    @foreach($notifikasiGuru as $notif)
                        @php
                            $nData = $notif->data ?? [];
                            $nType = $nData['type'] ?? 'info';
                            $nStudentName = $nData['student_name'] ?? '';
                            $nAssignmentTitle = $nData['assignment_title'] ?? ($nData['title'] ?? 'Notifikasi');
                            $nIsRead = $notif->read_at !== null;
                            $nIsLate = (bool) ($nData['is_late'] ?? false);
                            $nUrl = route('notifikasi.lms.baca', $notif->id, false);
                            $searchHaystack = strtolower(($nData['title'] ?? '') . ' ' . ($nData['message'] ?? '') . ' ' . $nStudentName);
                        @endphp
                        {{-- Only show in 'all' if recentSubmissions is empty, OR always show in 'unread' tab --}}
                        <div x-show="(tab === 'unread' && {{ $nIsRead ? 'false' : 'true' }}) || (tab === 'all' && {{ $recentSubmissions->isEmpty() ? 'true' : 'false' }}) || (tab === 'terlambat' && {{ $nIsLate ? 'true' : 'false' }})"
                             x-cloak
                             x-transition
                             class="group bg-indigo-50/40 dark:bg-indigo-950/20 rounded-2xl p-4 border border-indigo-100 dark:border-indigo-900/60 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            
                            <div class="flex items-start gap-3.5 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 shadow-xs">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[10px] font-black uppercase tracking-wider text-indigo-700 dark:text-indigo-300 bg-indigo-100 dark:bg-indigo-900/60 px-2 py-0.5 rounded-md">
                                            Notifikasi Masuk
                                        </span>
                                        @if(!$nIsRead)
                                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                                        @endif
                                        <span class="text-[11px] text-slate-400">{{ $notif->created_at?->diffForHumans() }}</span>
                                    </div>
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white line-clamp-1">
                                        {{ $nData['title'] ?? 'Pemberitahuan Tugas' }}
                                    </h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1">
                                        {{ $nData['message'] ?? '' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-2 shrink-0">
                                <a href="{{ $nUrl }}" class="px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all inline-flex items-center gap-1.5 shadow-xs">
                                    <span>Buka & Verifikasi</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Right Column: Active Assignments & Progress Tracker (4 Cols) --}}
        <div class="lg:col-span-4 flex flex-col gap-4">
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-100 dark:border-slate-800 shadow-xs flex flex-col h-full">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Daftar Tugas Aktif</h3>
                    </div>
                    <span class="text-xs font-black text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md">
                        {{ $totalTugasLms }} Tugas
                    </span>
                </div>

                <div class="space-y-3 flex-1">
                    @forelse($recentTugas as $tugas)
                        @php
                            $isOverdue = $tugas->isOverdue();
                            $subsCount = $tugas->submissions_count ?? 0;
                        @endphp
                        <div class="p-3 bg-slate-50/70 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-700/60 hover:border-indigo-200 dark:hover:border-indigo-700 transition-all flex flex-col gap-2">
                            <div class="flex items-start justify-between gap-2">
                                <h4 class="text-xs font-bold text-slate-900 dark:text-white line-clamp-1" title="{{ $tugas->title }}">
                                    {{ $tugas->title }}
                                </h4>
                                <span class="text-[10px] font-bold text-slate-500 bg-white dark:bg-slate-700 px-1.5 py-0.5 rounded shrink-0">
                                    {{ $tugas->kelompokBelajar?->nama_kelompok ?? 'Umum' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-slate-500 dark:text-slate-400">
                                    <strong class="text-slate-800 dark:text-slate-200">{{ $subsCount }}</strong> siswa mengumpulkan
                                </span>
                                @if($tugas->due_at)
                                    <span class="text-[10px] font-semibold {{ $isOverdue ? 'text-rose-600 dark:text-rose-400' : 'text-slate-500' }}">
                                        {{ $isOverdue ? 'Berakhir' : 'Tenggat' }}: {{ $tugas->due_at->isoFormat('D MMM, HH:mm') }}
                                    </span>
                                @endif
                            </div>

                            <div class="pt-2 border-t border-slate-200/60 dark:border-slate-700/60 flex items-center justify-between">
                                <a href="{{ route('guru.lms.submissions', $tugas->id) }}" class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline inline-flex items-center gap-1">
                                    <span>Lihat Semua Pengumpulan</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center">
                            <p class="text-xs text-slate-400">Belum ada tugas yang dibuat.</p>
                            <a href="{{ route('guru.lms.create') }}" class="mt-2 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline inline-block">
                                + Buat Tugas Sekarang
                            </a>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 text-center">
                    <a href="{{ route('guru.lms.index') }}" class="text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 inline-flex items-center gap-1">
                        <span>Buka Manajemen LMS Lengkap</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ─── Operational Panels (Jadwal Mengajar & Ujian CBT) ─── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Panel Kiri: Jadwal Mengajar Hari Ini --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-100 dark:border-slate-800 overflow-hidden flex flex-col h-full">
        <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-sky-50 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">Jadwal Mengajar Hari Ini</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Hari {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd') }}</p>
                </div>
            </div>
            <a href="{{ route('guru.jadwal.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 transition-colors">
                Kelola Jadwal &rarr;
            </a>
        </div>

        <div class="flex-1 p-4 flex flex-col gap-2.5">
            @forelse($jadwals as $jadwal)
            <div class="p-3.5 bg-slate-50/70 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-700/60 hover:border-sky-200 dark:hover:border-sky-700 transition-all flex items-center justify-between group">
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-12 h-12 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sky-600 dark:text-sky-400 flex flex-col items-center justify-center font-black shadow-xs shrink-0">
                        <span class="text-xs">{{ substr($jadwal->jam_mulai, 0, 5) }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-slate-900 dark:text-white text-sm truncate leading-tight">{{ $jadwal->mapel }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1.5">
                            <span>Kelas:</span>
                            <span class="font-bold text-sky-700 dark:text-sky-300 bg-sky-100 dark:bg-sky-950/60 px-2 py-0.5 rounded-md text-[11px]">{{ $jadwal->kelas }}</span>
                        </p>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Selesai</span>
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ substr($jadwal->jam_selesai, 0, 5) }} WIB</span>
                </div>
            </div>
            @empty
            <div class="flex-1 flex flex-col items-center justify-center text-center p-8">
                <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-3 text-slate-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm">Tidak Ada Jadwal Mengajar Hari Ini</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-xs">
                    Agenda Anda untuk hari ini kosong. Anda dapat menyusun materi atau menyiapkan bank soal untuk evaluasi siswa.
                </p>
                <a href="{{ route('guru.jadwal.index') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                    <span>Lihat Jadwal Mingguan</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Panel Kanan: Ujian CBT Terbaru --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-100 dark:border-slate-800 overflow-hidden flex flex-col h-full">
        <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">Ujian CBT Terbaru</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Daftar evaluasi dan monitoring nilai</p>
                </div>
            </div>
            <a href="{{ route('guru.ujian.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 transition-colors">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="flex-1 p-4 flex flex-col gap-2.5">
            @forelse($recentUjians as $ujian)
            <div class="p-3.5 bg-slate-50/70 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-700/60 hover:border-amber-200 dark:hover:border-amber-700 transition-all flex items-center justify-between gap-3 group">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $ujian->mode == 'resmi' ? 'bg-red-100 text-red-700 dark:bg-red-950/60 dark:text-red-300' : 'bg-sky-100 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300' }}">
                            {{ $ujian->mode }}
                        </span>
                        <span class="text-xs font-medium text-slate-400">&bull; {{ $ujian->durasi }} Menit</span>
                    </div>
                    <p class="font-bold text-slate-900 dark:text-white text-sm truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors" title="{{ $ujian->judul }}">
                        {{ $ujian->judul }}
                    </p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <div class="text-center px-2.5 py-1 bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                        <span class="text-xs font-black text-slate-800 dark:text-slate-200 leading-none block">{{ $ujian->pesertas_count }}</span>
                        <span class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">Peserta</span>
                    </div>
                    <a href="{{ route('guru.ujian.monitoring', $ujian->id) }}" class="p-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white dark:bg-indigo-950/60 dark:text-indigo-400 dark:hover:bg-indigo-600 dark:hover:text-white rounded-lg transition-all shadow-xs" title="Monitoring Nilai">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="flex-1 flex flex-col items-center justify-center text-center p-8">
                <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-3 text-slate-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm">Belum Ada Ujian Terjadwal</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-xs">
                    Rancang dan publikasikan ujian CBT untuk menguji capaian belajar siswa.
                </p>
                <a href="{{ route('guru.ujian.create') }}" class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-xs transition-colors">
                    <span>Buat Ujian Baru</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </a>
            </div>
            @endforelse
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dashboardClock', () => ({
            time: '00:00:00',
            date: 'Memuat...',
            zonaWaktu: 'WIB',

            init() {
                this.updateClock();
                setInterval(() => this.updateClock(), 1000);
            },

            updateClock() {
                const now = new Date();

                this.time = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                }).replace(/\./g, ':');

                this.date = now.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                const offset = -now.getTimezoneOffset() / 60;
                if (offset === 7) this.zonaWaktu = 'Waktu Indonesia Barat (WIB)';
                else if (offset === 8) this.zonaWaktu = 'Waktu Indonesia Tengah (WITA)';
                else if (offset === 9) this.zonaWaktu = 'Waktu Indonesia Timur (WIT)';
                else this.zonaWaktu = 'Waktu Lokal: GMT' + (offset > 0 ? '+' : '') + offset;
            }
        }));
    });
</script>
@endpush