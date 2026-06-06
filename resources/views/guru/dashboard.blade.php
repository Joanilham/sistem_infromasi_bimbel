@extends('layouts.guru')

@section('title', 'Dashboard Guru')

@section('content')

<div class="relative mb-8 rounded-[1.75rem] overflow-hidden shadow-2xl" x-data="dashboardClock">

    {{-- Animated gradient background --}}
    <div class="absolute inset-0 bg-gradient-to-br from-[#388782] via-[#206D6C] to-[#0F5253] animate-gradient-xy"></div>

    {{-- Decorative circles --}}
    <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -left-16 w-80 h-80 rounded-full bg-[#388782]/20 blur-3xl pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>

    {{-- Dot grid overlay --}}
    <div class="absolute inset-0 opacity-[0.04]"
        style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;">
    </div>

    <div class="relative z-10 p-5 md:p-10 flex flex-col xl:flex-row xl:items-center justify-between gap-8">

        {{-- Greeting --}}
        <div class="flex-1">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-5">
                <span class="w-2 h-2 rounded-full bg-[#A2D5CB] animate-pulse"></span>
                <span class="text-xs font-semibold tracking-widest text-[#A2D5CB] uppercase">Dashboard Guru</span>
            </div>

            <h1 class="text-2xl md:text-5xl font-extrabold text-white leading-tight mb-3 tracking-tight">
                Selamat Datang,<br>
                <span class="">
                    {{ explode(' ', auth()->user()->name)[0] ?? 'Guru' }}! 👋
                </span>
            </h1>
            <p class="text-[#A2D5CB] text-base md:text-lg max-w-xl leading-relaxed">
                Anda mengajar mata pelajaran <span class="font-bold text-white bg-white/20 px-2 py-0.5 rounded-lg">{{ $guru->matapelajaran ?? 'Umum' }}</span>.
            </p>
        </div>

        {{-- Real-Time Clock --}}
        <div class="shrink-0 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/15 p-5 lg:p-8 min-w-0 sm:min-w-[260px] shadow-[inset_0_1px_1px_rgba(255,255,255,0.12)] hover:bg-white/15 transition-colors duration-300 text-center">
            <div class="flex items-center justify-center gap-2 mb-2">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-[10px] sm:text-xs font-semibold text-white uppercase tracking-widest" x-text="date">Memuat...</span>
            </div>
            <div class="text-4xl lg:text-6xl font-black tabular-nums tracking-tight text-white drop-shadow" x-text="time">00:00:00</div>
            <div class="mt-3 text-[10px] sm:text-xs font-medium text-white/80 bg-white/10 rounded-lg py-1.5 px-3" x-text="zonaWaktu">Waktu Lokal</div>
        </div>
    </div>
</div>

{{-- ─── Stat Cards ──────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">

    {{-- Card: Jadwal Mengajar --}}
    <div class="relative bg-white dark:bg-zinc-900 rounded-2xl p-5 border border-slate-100 dark:border-zinc-800 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
        <div class="absolute right-0 bottom-0 translate-x-4 translate-y-4 opacity-5 group-hover:opacity-10 group-hover:scale-110 group-hover:rotate-12 transition-all duration-500 pointer-events-none">
            <svg class="w-24 h-24 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="relative z-10 flex flex-col h-full justify-between gap-4">
            <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-900/20 text-sky-600 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-3xl font-black text-slate-800 dark:text-white mb-0.5 tracking-tight">{{ $jadwalHariIni }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jadwal Hari Ini</p>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1.5 bg-sky-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500"></div>
    </div>

    {{-- Card: Bank Soal --}}
    <div class="relative bg-white dark:bg-zinc-900 rounded-2xl p-5 border border-slate-100 dark:border-zinc-800 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
        <div class="absolute right-0 bottom-0 translate-x-4 translate-y-4 opacity-5 group-hover:opacity-10 group-hover:scale-110 group-hover:-rotate-12 transition-all duration-500 pointer-events-none">
            <svg class="w-24 h-24 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <div class="relative z-10 flex flex-col h-full justify-between gap-4">
            <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-900/20 text-teal-600 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <p class="text-3xl font-black text-slate-800 dark:text-white mb-0.5 tracking-tight">{{ $bankSoalCount }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bank Soal Tersedia</p>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1.5 bg-teal-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500"></div>
    </div>

    {{-- Card: Ujian Aktif --}}
    <div class="relative bg-white dark:bg-zinc-900 rounded-2xl p-5 border border-slate-100 dark:border-zinc-800 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
        <div class="absolute right-0 bottom-0 translate-x-4 translate-y-4 opacity-5 group-hover:opacity-10 group-hover:scale-110 group-hover:rotate-12 transition-all duration-500 pointer-events-none">
            <svg class="w-24 h-24 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
        </div>
        <div class="relative z-10 flex flex-col h-full justify-between gap-4">
            <div class="w-10 h-10 rounded-xl bg-violet-50 dark:bg-violet-900/20 text-violet-600 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <div>
                <p class="text-3xl font-black text-slate-800 dark:text-white mb-0.5 tracking-tight">{{ $ujianAktifCount }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ujian Aktif</p>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1.5 bg-violet-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500"></div>
    </div>

    {{-- Card: Total Siswa --}}
    <div class="relative bg-white dark:bg-zinc-900 rounded-2xl p-5 border border-slate-100 dark:border-zinc-800 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
        <div class="absolute right-0 bottom-0 translate-x-4 translate-y-4 opacity-5 group-hover:opacity-10 group-hover:scale-110 group-hover:-rotate-12 transition-all duration-500 pointer-events-none">
            <svg class="w-24 h-24 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </div>
        <div class="relative z-10 flex flex-col h-full justify-between gap-4">
            <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <p class="text-3xl font-black text-slate-800 dark:text-white mb-0.5 tracking-tight">{{ $totalSiswa }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Siswa Terdaftar</p>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1.5 bg-rose-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500"></div>
    </div>

</div>

{{-- ─── Akses Cepat ─────────────────────────────────────────────────────────── --}}
<div class="mb-3 flex items-end justify-between mt-10">
    <div>
        <h2 class="text-xl font-extrabold text-slate-800 dark:text-slate-100 tracking-tight">Akses Cepat</h2>
        <p class="text-sm text-slate-500 mt-0.5">Navigasi ke fitur utama guru</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

    {{-- Buat Jadwal --}}
    <a href="{{ route('guru.jadwal.index') }}"
        class="group relative bg-white dark:bg-zinc-900 rounded-2xl p-4 border border-slate-100 dark:border-zinc-800 shadow-sm hover:border-sky-300 dark:hover:border-sky-700 hover:shadow-md hover:shadow-sky-500/10 transition-all overflow-hidden flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-900/20 text-sky-600 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:rotate-3 transition-transform">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-slate-800 dark:text-white text-[15px] group-hover:text-sky-600 transition-colors">Buat Jadwal</h3>
            <p class="text-[11px] font-medium text-slate-500 mt-0.5 line-clamp-1">Atur jadwal mengajar hari ini.</p>
        </div>
        <div class="w-7 h-7 rounded-full bg-slate-50 dark:bg-zinc-800 flex items-center justify-center text-slate-400 group-hover:bg-sky-100 group-hover:text-sky-600 transition-colors shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
        </div>
    </a>

    {{-- Bank Soal --}}
    <a href="{{ route('guru.bank-soal.create') }}"
        class="group relative bg-white dark:bg-zinc-900 rounded-2xl p-4 border border-slate-100 dark:border-zinc-800 shadow-sm hover:border-teal-300 dark:hover:border-teal-700 hover:shadow-md hover:shadow-teal-500/10 transition-all overflow-hidden flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-900/20 text-teal-600 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:-rotate-3 transition-transform">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-slate-800 dark:text-white text-[15px] group-hover:text-teal-600 transition-colors">Tambah Soal</h3>
            <p class="text-[11px] font-medium text-slate-500 mt-0.5 line-clamp-1">Buat soal CBT baru untuk siswa.</p>
        </div>
        <div class="w-7 h-7 rounded-full bg-slate-50 dark:bg-zinc-800 flex items-center justify-center text-slate-400 group-hover:bg-teal-100 group-hover:text-teal-600 transition-colors shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
        </div>
    </a>

    {{-- Buat Ujian --}}
    <a href="{{ route('guru.ujian.create') }}"
        class="group relative bg-white dark:bg-zinc-900 rounded-2xl p-4 border border-slate-100 dark:border-zinc-800 shadow-sm hover:border-violet-300 dark:hover:border-violet-700 hover:shadow-md hover:shadow-violet-500/10 transition-all overflow-hidden flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-violet-50 dark:bg-violet-900/20 text-violet-600 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:rotate-3 transition-transform">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-slate-800 dark:text-white text-[15px] group-hover:text-violet-600 transition-colors">Buat Ujian Baru</h3>
            <p class="text-[11px] font-medium text-slate-500 mt-0.5 line-clamp-1">Rancang ujian untuk siswa.</p>
        </div>
        <div class="w-7 h-7 rounded-full bg-slate-50 dark:bg-zinc-800 flex items-center justify-center text-slate-400 group-hover:bg-violet-100 group-hover:text-violet-600 transition-colors shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
        </div>
    </a>

</div>

{{-- ─── Data Tables Section ────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <!-- Jadwal Hari Ini -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-slate-100 dark:border-zinc-800 overflow-hidden flex flex-col h-full">
        <div class="p-5 border-b border-slate-100 dark:border-zinc-800 flex justify-between items-center bg-slate-50/50 dark:bg-zinc-800/50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-sky-100 dark:bg-sky-900/30 text-sky-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-[15px] font-bold text-slate-800 dark:text-white">Jadwal Hari Ini</h3>
            </div>
            <span class="text-[10px] font-bold px-3 py-1 bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-slate-300 rounded-full">Hari {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd') }}</span>
        </div>
        <div class="flex-1 p-3 flex flex-col gap-2 bg-slate-50/30 dark:bg-zinc-900/30">
            @forelse($jadwals as $jadwal)
            <div class="p-3.5 bg-white dark:bg-zinc-800 rounded-xl border border-slate-100 dark:border-zinc-700 shadow-sm hover:border-sky-200 dark:hover:border-sky-800 hover:shadow-md transition-all flex items-center justify-between group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-sky-50 dark:bg-sky-900/20 text-sky-600 flex flex-col items-center justify-center font-black group-hover:scale-105 transition-transform">
                        <span class="text-xs">{{ substr($jadwal->jam_mulai, 0, 5) }}</span>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 dark:text-white text-[15px] leading-tight">{{ $jadwal->mapel }}</p>
                        <p class="text-[11px] font-medium text-slate-500 mt-1">Kelas <span class="text-sky-600 dark:text-sky-400 font-bold px-1.5 py-0.5 bg-sky-50 dark:bg-sky-900/30 rounded-md">{{ $jadwal->kelas }}</span></p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Selesai</p>
                    <p class="text-sm font-black text-slate-700 dark:text-slate-300">{{ substr($jadwal->jam_selesai, 0, 5) }}</p>
                </div>
            </div>
            @empty
            <div class="flex-1 flex flex-col items-center justify-center text-center p-8">
                <div class="w-16 h-16 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="font-bold text-slate-700 dark:text-slate-200 text-base">Waktu Luang!</p>
                <p class="text-xs font-medium text-slate-500 mt-1">Anda tidak memiliki jadwal mengajar hari ini.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Ujian Terbaru -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-slate-100 dark:border-zinc-800 overflow-hidden flex flex-col h-full">
        <div class="p-5 border-b border-slate-100 dark:border-zinc-800 flex justify-between items-center bg-slate-50/50 dark:bg-zinc-800/50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-900/30 text-violet-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="text-[15px] font-bold text-slate-800 dark:text-white">Ujian Terbaru</h3>
            </div>
            <a href="{{ route('guru.ujian.index') }}" class="text-[11px] font-bold text-violet-600 hover:text-violet-700 hover:underline transition-colors">Lihat Semua &rarr;</a>
        </div>
        <div class="flex-1 p-3 flex flex-col gap-2 bg-slate-50/30 dark:bg-zinc-900/30">
            @forelse($recentUjians as $ujian)
            <div class="p-3.5 bg-white dark:bg-zinc-800 rounded-xl border border-slate-100 dark:border-zinc-700 shadow-sm hover:border-violet-200 dark:hover:border-violet-800 hover:shadow-md transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4 group">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-widest {{ $ujian->mode == 'resmi' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-400' }}">
                            {{ $ujian->mode }}
                        </span>
                        <span class="text-[11px] font-bold text-slate-400">&bull; {{ $ujian->durasi }} Menit</span>
                    </div>
                    <p class="font-bold text-slate-800 dark:text-white text-[15px] truncate group-hover:text-violet-600 transition-colors" title="{{ $ujian->judul }}">{{ $ujian->judul }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="flex flex-col items-center justify-center px-3 py-1.5 bg-slate-50 dark:bg-zinc-900 rounded-lg border border-slate-100 dark:border-zinc-700 min-w-[4rem]">
                        <p class="text-sm font-black text-slate-700 dark:text-slate-300 leading-none">{{ $ujian->pesertas_count }}</p>
                        <p class="text-[9px] text-slate-400 uppercase tracking-widest font-bold mt-1">Peserta</p>
                    </div>
                    <a href="{{ route('guru.ujian.monitoring', $ujian->id) }}" class="p-2.5 bg-violet-50 text-violet-600 hover:bg-violet-600 hover:text-white dark:bg-violet-900/30 dark:text-violet-400 dark:hover:bg-violet-600 dark:hover:text-white rounded-lg transition-all shadow-sm group-hover:scale-105" title="Lihat Rekap Nilai">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="flex-1 flex flex-col items-center justify-center text-center p-8">
                <div class="w-16 h-16 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <p class="font-bold text-slate-700 dark:text-slate-200 text-base">Belum ada ujian.</p>
                <a href="{{ route('guru.ujian.create') }}" class="text-[11px] text-violet-600 font-bold mt-1.5 hover:underline">Buat Ujian Pertama &rarr;</a>
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