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
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-10">

    {{-- Card: Jadwal Mengajar --}}
    <div class="relative bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 border border-slate-100 dark:border-[#388782]/30 rounded-2xl p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-[#A2D5CB]/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-5">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Jadwal Hari Ini</span>
                <div class="p-2.5 rounded-xl bg-[#A2D5CB] text-[#388782] group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-4xl font-black text-slate-800 dark:text-slate-100 mb-2">{{ $jadwalHariIni }}</p>
            <p class="text-sm font-medium text-slate-400">Jadwal aktif</p>
        </div>
        <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-[#78BBB0] to-[#388782] scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500 rounded-b-2xl"></div>
    </div>

    {{-- Card: Bank Soal --}}
    <div class="relative bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 border border-slate-100 dark:border-[#388782]/30 rounded-2xl p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-[#A2D5CB]/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-5">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Bank Soal</span>
                <div class="p-2.5 rounded-xl bg-[#A2D5CB] text-[#388782] group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <p class="text-4xl font-black text-slate-800 dark:text-slate-100 mb-2">{{ $bankSoalCount }}</p>
            <p class="text-sm font-medium text-slate-400">Total soal tersedia</p>
        </div>
        <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-[#55A199] to-[#206D6C] scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500 rounded-b-2xl"></div>
    </div>

    {{-- Card: Ujian Aktif --}}
    <div class="relative bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 border border-slate-100 dark:border-[#388782]/30 rounded-2xl p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-[#A2D5CB]/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-5">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Ujian Aktif</span>
                <div class="p-2.5 rounded-xl bg-[#A2D5CB] text-[#388782] group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
            <p class="text-4xl font-black text-slate-800 dark:text-slate-100 mb-2">{{ $ujianAktifCount }}</p>
            <p class="text-sm font-medium text-slate-400">Ujian siap dikerjakan</p>
        </div>
        <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-[#55A199] to-[#388782] scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500 rounded-b-2xl"></div>
    </div>

    {{-- Card: Total Siswa --}}
    <div class="relative bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 border border-slate-100 dark:border-[#388782]/30 rounded-2xl p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-[#A2D5CB]/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-5">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Siswa</span>
                <div class="p-2.5 rounded-xl bg-[#A2D5CB] text-[#388782] group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-4xl font-black text-slate-800 dark:text-slate-100 mb-2">{{ $totalSiswa }}</p>
            <p class="text-sm font-medium text-slate-400">Siswa terdaftar</p>
        </div>
        <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-[#78BBB0] to-[#55A199] scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500 rounded-b-2xl"></div>
    </div>

</div>

{{-- ─── Akses Cepat ─────────────────────────────────────────────────────────── --}}
<div class="mb-3 flex items-end justify-between mt-10">
    <div>
        <h2 class="text-xl font-extrabold text-slate-800 dark:text-slate-100 tracking-tight">Akses Cepat</h2>
        <p class="text-sm text-slate-500 mt-0.5">Navigasi ke fitur utama guru</p>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-10">

    {{-- Buat Jadwal --}}
    <a href="{{ route('guru.jadwal.index') }}"
        class="group relative flex flex-col gap-4 bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 border border-slate-100 dark:border-[#388782]/30 rounded-2xl p-6 hover:border-[#388782] hover:shadow-xl hover:shadow-[#388782]/10 transition-all duration-300 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-[#A2D5CB]/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative z-10 p-3 w-fit rounded-xl bg-[#A2D5CB] text-[#388782] group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        <div class="relative z-10">
            <h3 class="font-bold text-slate-800 dark:text-slate-100 text-base mb-1 group-hover:text-[#388782] transition-colors">Buat Jadwal</h3>
            <p class="text-sm text-slate-500 leading-snug">Atur dan kelola jadwal mengajar pelajaran Anda.</p>
        </div>
        <div class="absolute bottom-4 right-4 text-[#388782] opacity-0 group-hover:opacity-100 translate-x-2 group-hover:translate-x-0 transition-all duration-300">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
        </div>
    </a>

    {{-- Bank Soal --}}
    <a href="{{ route('guru.bank-soal.create') }}"
        class="group relative flex flex-col gap-4 bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 border border-slate-100 dark:border-[#388782]/30 rounded-2xl p-6 hover:border-[#388782] hover:shadow-xl hover:shadow-[#388782]/10 transition-all duration-300 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-[#A2D5CB]/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative z-10 p-3 w-fit rounded-xl bg-[#A2D5CB] text-[#388782] group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
        </div>
        <div class="relative z-10">
            <h3 class="font-bold text-slate-800 dark:text-slate-100 text-base mb-1 group-hover:text-[#388782] transition-colors">Tambah Bank Soal</h3>
            <p class="text-sm text-slate-500 leading-snug">Buat kumpulan soal CBT baru untuk siswa.</p>
        </div>
        <div class="absolute bottom-4 right-4 text-[#388782] opacity-0 group-hover:opacity-100 translate-x-2 group-hover:translate-x-0 transition-all duration-300">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
        </div>
    </a>

    {{-- Buat Ujian --}}
    <a href="{{ route('guru.ujian.create') }}"
        class="group relative flex flex-col gap-4 bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 border border-slate-100 dark:border-[#388782]/30 rounded-2xl p-6 hover:border-[#388782] hover:shadow-xl hover:shadow-[#388782]/10 transition-all duration-300 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-[#A2D5CB]/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative z-10 p-3 w-fit rounded-xl bg-[#A2D5CB] text-[#388782] group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
        </div>
        <div class="relative z-10">
            <h3 class="font-bold text-slate-800 dark:text-slate-100 text-base mb-1 group-hover:text-[#388782] transition-colors">Buat Ujian Baru</h3>
            <p class="text-sm text-slate-500 leading-snug">Rancang dan jadwalkan sesi ujian untuk siswa.</p>
        </div>
        <div class="absolute bottom-4 right-4 text-[#388782] opacity-0 group-hover:opacity-100 translate-x-2 group-hover:translate-x-0 transition-all duration-300">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
        </div>
    </a>

</div>

{{-- ─── Data Tables Section ────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <!-- Jadwal Hari Ini -->
    <div class="bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 rounded-2xl shadow-sm border border-slate-100 dark:border-[#388782]/30 overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-[#388782]/20 flex justify-between items-center bg-gradient-to-r from-[#A2D5CB]/40 to-transparent dark:from-[#388782]/20 dark:to-transparent">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Jadwal Hari Ini</h3>
            <span class="text-xs font-bold px-3 py-1 bg-[#A2D5CB] text-[#388782] rounded-full">Hari {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd') }}</span>
        </div>
        <div class="divide-y divide-slate-50 dark:divide-zinc-800/50">
            @forelse($jadwals as $jadwal)
            <div class="p-4 mx-2 my-2 rounded-xl hover:bg-slate-50 dark:hover:bg-zinc-800 border border-transparent hover:border-slate-100 transition-all flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#A2D5CB] to-[#78BBB0] text-[#388782] flex flex-col items-center justify-center font-black shadow-inner">
                        <span class="text-sm">{{ substr($jadwal->jam_mulai, 0, 5) }}</span>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 dark:text-slate-100 text-base">{{ $jadwal->mapel }}</p>
                        <p class="text-sm text-slate-500 mt-0.5">Kelas <span class="font-semibold text-slate-600">{{ $jadwal->kelas }}</span></p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs font-medium text-slate-400 mb-0.5">Selesai</p>
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ substr($jadwal->jam_selesai, 0, 5) }}</p>
                </div>
            </div>
            @empty
            <div class="p-10 text-center flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="font-bold text-slate-700 dark:text-slate-200 text-lg">Waktu Luang!</p>
                <p class="text-sm text-slate-500 mt-1">Anda tidak memiliki jadwal mengajar hari ini.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Ujian Terbaru -->
    <div class="bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 rounded-2xl shadow-sm border border-slate-100 dark:border-[#388782]/30 overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-[#388782]/20 flex justify-between items-center bg-gradient-to-r from-[#A2D5CB]/40 to-transparent dark:from-[#388782]/20 dark:to-transparent">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Ujian Terbaru</h3>
            <a href="{{ route('guru.ujian.index') }}" class="text-sm font-bold text-[#388782] hover:text-[#206D6C] transition-colors">Lihat Semua &rarr;</a>
        </div>
        <div class="divide-y divide-slate-50 dark:divide-zinc-800/50">
            @forelse($recentUjians as $ujian)
            <div class="p-4 mx-2 my-2 rounded-xl hover:bg-slate-50 dark:hover:bg-zinc-800 border border-transparent hover:border-slate-100 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $ujian->mode == 'resmi' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $ujian->mode }}
                        </span>
                        <span class="text-xs font-semibold text-slate-400">&bull; {{ $ujian->durasi }} Menit</span>
                    </div>
                    <p class="font-bold text-slate-800 dark:text-slate-100 text-base truncate" title="{{ $ujian->judul }}">{{ $ujian->judul }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-center px-4 py-1.5 bg-[#A2D5CB]/30 rounded-xl border border-[#78BBB0]">
                        <p class="text-lg font-black text-[#388782] leading-none">{{ $ujian->pesertas_count }}</p>
                        <p class="text-[10px] text-[#388782] uppercase tracking-wider font-bold mt-1">Peserta</p>
                    </div>
                    <a href="{{ route('guru.ujian.monitoring', $ujian->id) }}" class="p-2.5 bg-[#A2D5CB] text-[#388782] hover:bg-[#388782] hover:text-white rounded-xl transition-all shadow-sm" title="Lihat Rekap Nilai">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="p-10 text-center flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <a href="{{ route('guru.ujian.create') }}" class="text-sm text-[#388782] font-bold mt-2 hover:underline">Buat Ujian Pertama &rarr;</a>
                <p class="font-bold text-slate-700 dark:text-slate-200 text-lg">Belum ada ujian.</p>
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