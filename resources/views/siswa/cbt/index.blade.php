@extends('layouts.siswa')

@section('title', 'Ujian Online')

@section('content')
<div class="space-y-8 pb-20" x-data="{ filterStatus: 'semua' }">

    {{-- Hero Section --}}
    <div class="relative overflow-hidden rounded-[2rem] sm:rounded-[2.5rem] bg-gradient-to-br from-[#388782] via-[#206D6C] to-[#0F5253] p-6 sm:p-8 md:p-12 shadow-2xl shadow-teal-900/20">
        {{-- Decorative elements --}}
        <div class="absolute -right-20 -top-20 h-96 w-96 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-20 h-80 w-80 rounded-full bg-emerald-400/10 blur-3xl pointer-events-none"></div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 32px 32px;"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6 md:gap-10">
            <div class="max-w-xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-[#A2D5CB] text-[9px] sm:text-[10px] font-bold uppercase tracking-[0.15em] mb-4 sm:mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    CBT Assessment Center
                </div>
                <h1 class="text-2xl sm:text-4xl md:text-5xl font-black text-white leading-tight mb-3 sm:mb-5 tracking-tight">
                    Pusat Ujian <span class="text-[#A2D5CB]">Online</span>
                </h1>
                <p class="text-[#A2D5CB] text-xs sm:text-sm md:text-lg font-medium opacity-90 leading-relaxed max-w-lg mb-0">
                    Selesaikan ujianmu tepat waktu untuk mendapatkan hasil terbaik. Pantau jadwal dan riwayat nilaimu secara real-time.
                </p>
            </div>

            <div class="grid grid-cols-3 gap-2 sm:gap-4 shrink-0 w-full lg:w-auto">
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl sm:rounded-[2rem] p-3 sm:p-6 text-center min-w-[70px] sm:min-w-[130px] transition-transform hover:-translate-y-1">
                    <div class="text-xl sm:text-4xl font-black text-white mb-0.5 sm:mb-1 leading-none">{{ $ujianAktif->count() }}</div>
                    <div class="text-[8px] sm:text-[10px] font-black uppercase tracking-widest text-[#A2D5CB] opacity-80">Aktif</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl sm:rounded-[2rem] p-3 sm:p-6 text-center min-w-[70px] sm:min-w-[130px] transition-transform hover:-translate-y-1">
                    <div class="text-xl sm:text-4xl font-black text-white mb-0.5 sm:mb-1 leading-none">{{ $ujianMendatang->count() }}</div>
                    <div class="text-[8px] sm:text-[10px] font-black uppercase tracking-widest text-[#A2D5CB] opacity-80">Jadwal</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl sm:rounded-[2rem] p-3 sm:p-6 text-center min-w-[70px] sm:min-w-[130px] transition-transform hover:-translate-y-1">
                    <div class="text-xl sm:text-4xl font-black text-white mb-0.5 sm:mb-1 leading-none">{{ $ujianSelesai->count() }}</div>
                    <div class="text-[8px] sm:text-[10px] font-black uppercase tracking-widest text-[#A2D5CB] opacity-80">Selesai</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom Tabs / Filter --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-200 dark:border-zinc-800 pb-2">
        <div class="flex p-1.5 bg-slate-100 dark:bg-zinc-900 rounded-2xl w-full md:w-fit overflow-x-auto no-scrollbar">
            <button @click="filterStatus = 'semua'" 
                :class="filterStatus === 'semua' ? 'bg-white dark:bg-zinc-800 text-[#388782] shadow-sm scale-100' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 scale-95'" 
                class="flex-1 md:flex-none whitespace-nowrap px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300">Semua</button>
            <button @click="filterStatus = 'aktif'" 
                :class="filterStatus === 'aktif' ? 'bg-white dark:bg-zinc-800 text-[#388782] shadow-sm scale-100' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 scale-95'" 
                class="flex-1 md:flex-none whitespace-nowrap px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300">Sedang Berlangsung</button>
            <button @click="filterStatus = 'mendatang'" 
                :class="filterStatus === 'mendatang' ? 'bg-white dark:bg-zinc-800 text-[#388782] shadow-sm scale-100' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 scale-95'" 
                class="flex-1 md:flex-none whitespace-nowrap px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300">Mendatang</button>
            <button @click="filterStatus = 'selesai'" 
                :class="filterStatus === 'selesai' ? 'bg-white dark:bg-zinc-800 text-[#388782] shadow-sm scale-100' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 scale-95'" 
                class="flex-1 md:flex-none whitespace-nowrap px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300">Selesai</button>
        </div>
        
        <div class="hidden md:flex text-[10px] font-black uppercase tracking-widest text-slate-400 items-center gap-2">
            <svg class="w-4 h-4 text-[#388782]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ $ujianAktif->count() + $ujianMendatang->count() + $ujianSelesai->count() }} Total Penugasan
        </div>
    </div>

    {{-- SECTION: UJIAN AKTIF --}}
    <div x-show="filterStatus === 'semua' || filterStatus === 'aktif'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
        <div class="flex items-center gap-3 mb-6">
            <h2 class="text-xl font-black text-slate-800 dark:text-slate-100">Ujian Aktif</h2>
            <span class="px-2.5 py-0.5 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 text-xs font-black">{{ $ujianAktif->count() }}</span>
        </div>
        
        @if($ujianAktif->count() > 0)
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($ujianAktif as $ujian)
                    <x-ujian-card-aktif :ujian="$ujian" :sesi="$sesis[$ujian->id] ?? null" />
                @endforeach
            </div>
        @else
            <div class="bg-slate-50 dark:bg-zinc-900/50 border border-dashed border-slate-200 dark:border-zinc-800 rounded-3xl p-10 text-center text-slate-400 font-bold text-sm">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Tidak ada ujian yang sedang berlangsung saat ini.
            </div>
        @endif
    </div>

    {{-- SECTION: UJIAN MENDATANG --}}
    <div x-show="filterStatus === 'semua' || filterStatus === 'mendatang'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
        <div class="flex items-center gap-3 mb-6 mt-4">
            <h2 class="text-xl font-black text-slate-800 dark:text-slate-100">Jadwal Mendatang</h2>
            <span class="px-2.5 py-0.5 rounded-lg bg-slate-200 dark:bg-zinc-800 text-slate-600 dark:text-slate-400 text-xs font-black">{{ $ujianMendatang->count() }}</span>
        </div>
        
        @if($ujianMendatang->count() > 0)
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($ujianMendatang as $ujian)
                    <x-ujian-card-mendatang :ujian="$ujian" />
                @endforeach
            </div>
        @else
            <div class="bg-slate-50 dark:bg-zinc-900/50 border border-dashed border-slate-200 dark:border-zinc-800 rounded-3xl p-10 text-center text-slate-400 font-bold text-sm">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                Tidak ada jadwal ujian mendatang.
            </div>
        @endif
    </div>

    {{-- SECTION: UJIAN SELESAI --}}
    <div x-show="filterStatus === 'semua' || filterStatus === 'selesai'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
        <div class="flex items-center gap-3 mb-6 mt-4">
            <h2 class="text-xl font-black text-slate-800 dark:text-slate-100">Telah Diselesaikan</h2>
            <span class="px-2.5 py-0.5 rounded-lg bg-slate-200 dark:bg-zinc-800 text-slate-600 dark:text-slate-400 text-xs font-black">{{ $ujianSelesai->count() }}</span>
        </div>
        
        @if($ujianSelesai->count() > 0)
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($ujianSelesai as $ujian)
                    <x-ujian-card-selesai :ujian="$ujian" :sesi="$sesis[$ujian->id] ?? null" />
                @endforeach
            </div>
        @else
            <div class="bg-slate-50 dark:bg-zinc-900/50 border border-dashed border-slate-200 dark:border-zinc-800 rounded-3xl p-10 text-center text-slate-400 font-bold text-sm">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                Belum ada ujian yang kamu selesaikan.
            </div>
        @endif
    </div>

    @if($ujianAktif->isEmpty() && $ujianMendatang->isEmpty() && $ujianSelesai->isEmpty())
        <div class="bg-white dark:bg-zinc-900 rounded-[2rem] sm:rounded-[3rem] border border-slate-200 dark:border-zinc-800 p-8 sm:p-16 text-center shadow-sm relative overflow-hidden">
            <div class="absolute -top-24 -left-24 w-80 h-80 bg-teal-500/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="w-16 h-16 sm:w-24 sm:h-24 rounded-2xl sm:rounded-[2rem] mx-auto mb-6 sm:mb-8 flex items-center justify-center bg-slate-50 dark:bg-zinc-800 text-[#388782] shadow-inner">
                    <svg class="w-8 h-8 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="font-black text-xl sm:text-3xl text-slate-800 dark:text-slate-100 mb-2 sm:mb-3 tracking-tight">Belum Ada Ujian</h3>
                <p class="text-slate-400 dark:text-slate-500 text-xs sm:text-base max-w-sm mx-auto font-medium leading-relaxed">Ujian yang ditugaskan oleh guru atau lembaga akan muncul di halaman ini secara otomatis.</p>
                <div class="mt-6 sm:mt-8 flex justify-center">
                    <a href="{{ route('siswa.dashboard') }}" class="px-6 py-2.5 sm:px-8 sm:py-3 rounded-xl sm:rounded-2xl bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-slate-300 font-bold text-xs sm:text-sm hover:bg-slate-200 dark:hover:bg-zinc-700 transition-colors">Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
