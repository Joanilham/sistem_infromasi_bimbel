@extends('layouts.siswa_cbt')

@section('title', 'Ujian Online')

@section('content')
<div class="space-y-8 pb-10" x-data="{ filterStatus: 'semua' }">

    {{-- Page Header & Filter --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-2xl font-bold" style="color:var(--text-main)">Ujian Online</h1>
            <p class="text-sm mt-1" style="color:var(--text-muted)">Daftar ujian yang ditugaskan untukmu</p>
        </div>
        
        <div class="flex gap-2">
            <select x-model="filterStatus" class="text-sm border-slate-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 pl-3 pr-10">
                <option value="semua">Semua Status</option>
                <option value="aktif">Sedang Berlangsung</option>
                <option value="mendatang">Akan Datang</option>
                <option value="selesai">Selesai</option>
            </select>
        </div>
    </div>

    {{-- SECTION: UJIAN AKTIF --}}
    <div x-show="filterStatus === 'semua' || filterStatus === 'aktif'" x-transition.opacity.duration.300ms>
        <div class="flex items-center gap-3 mb-6">
            <h2 class="text-xl font-black" style="color:var(--text-main)">Ujian Aktif</h2>
            <div class="w-7 h-7 flex items-center justify-center rounded-full bg-emerald-500 text-white text-xs font-black">{{ $ujianAktif->count() }}</div>
        </div>
        
        @if($ujianAktif->count() > 0)
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($ujianAktif as $ujian)
                    <x-ujian-card-aktif :ujian="$ujian" :sesi="$sesis[$ujian->id] ?? null" />
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 text-center text-slate-400 font-bold text-sm shadow-sm mb-8">
                Tidak ada ujian yang sedang berlangsung saat ini.
            </div>
        @endif
    </div>

    {{-- SECTION: UJIAN MENDATANG --}}
    <div x-show="filterStatus === 'semua' || filterStatus === 'mendatang'" x-transition.opacity.duration.300ms>
        <div class="flex items-center gap-3 mb-6">
            <h2 class="text-xl font-black" style="color:var(--text-main)">Akan Datang</h2>
            <div class="w-7 h-7 flex items-center justify-center rounded-full bg-slate-300 dark:bg-slate-700 text-white text-xs font-black">{{ $ujianMendatang->count() }}</div>
        </div>
        
        @if($ujianMendatang->count() > 0)
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($ujianMendatang as $ujian)
                    <x-ujian-card-mendatang :ujian="$ujian" />
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 text-center text-slate-400 font-bold text-sm shadow-sm mb-8">
                Tidak ada jadwal ujian mendatang.
            </div>
        @endif
    </div>

    {{-- SECTION: UJIAN SELESAI --}}
    <div x-show="filterStatus === 'semua' || filterStatus === 'selesai'" x-transition.opacity.duration.300ms>
        <div class="flex items-center gap-3 mb-6">
            <h2 class="text-xl font-black" style="color:var(--text-main)">Selesai</h2>
            <div class="w-7 h-7 flex items-center justify-center rounded-full bg-slate-300 dark:bg-slate-700 text-white text-xs font-black">{{ $ujianSelesai->count() }}</div>
        </div>
        
        @if($ujianSelesai->count() > 0)
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($ujianSelesai as $ujian)
                    <x-ujian-card-selesai :ujian="$ujian" :sesi="$sesis[$ujian->id] ?? null" />
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 text-center text-slate-400 font-bold text-sm shadow-sm">
                Belum ada ujian yang kamu selesaikan.
            </div>
        @endif
    </div>

    @if($ujianAktif->isEmpty() && $ujianMendatang->isEmpty() && $ujianSelesai->isEmpty())
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 p-16 text-center shadow-sm relative overflow-hidden">
            <div class="absolute -top-24 -left-24 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <div class="w-24 h-24 rounded-3xl mx-auto mb-6 flex items-center justify-center bg-indigo-50 dark:bg-indigo-900/20 text-indigo-500 shadow-inner">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h3 class="font-black text-2xl" style="color:var(--text-main)">Belum Ada Ujian</h3>
                <p class="text-slate-400 mt-2 max-w-sm mx-auto font-medium">Ujian yang ditugaskan oleh guru atau lembaga akan muncul di halaman ini.</p>
            </div>
        </div>
    @endif

</div>
@endsection
