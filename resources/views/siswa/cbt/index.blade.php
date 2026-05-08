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
        <div class="flex items-center gap-2 mb-4">
            <h2 class="text-lg font-bold text-slate-800">Ujian Aktif</h2>
            <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $ujianAktif->count() }}</span>
        </div>
        
        @if($ujianAktif->count() > 0)
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach($ujianAktif as $ujian)
                    <x-ujian-card-aktif :ujian="$ujian" :sesi="$sesis[$ujian->id] ?? null" />
                @endforeach
            </div>
        @else
            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-8 text-center text-slate-500 text-sm">
                Tidak ada ujian yang sedang berlangsung saat ini.
            </div>
        @endif
    </div>

    {{-- SECTION: UJIAN MENDATANG --}}
    <div x-show="filterStatus === 'semua' || filterStatus === 'mendatang'" x-transition.opacity.duration.300ms>
        <div class="flex items-center gap-2 mb-4">
            <h2 class="text-lg font-bold text-slate-800">Akan Datang</h2>
            <span class="bg-slate-200 text-slate-600 text-xs font-bold px-2 py-0.5 rounded-full">{{ $ujianMendatang->count() }}</span>
        </div>
        
        @if($ujianMendatang->count() > 0)
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach($ujianMendatang as $ujian)
                    <x-ujian-card-mendatang :ujian="$ujian" />
                @endforeach
            </div>
        @else
            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-8 text-center text-slate-500 text-sm">
                Tidak ada jadwal ujian mendatang.
            </div>
        @endif
    </div>

    {{-- SECTION: UJIAN SELESAI --}}
    <div x-show="filterStatus === 'semua' || filterStatus === 'selesai'" x-transition.opacity.duration.300ms>
        <div class="flex items-center gap-2 mb-4">
            <h2 class="text-lg font-bold text-slate-800">Selesai</h2>
            <span class="bg-slate-200 text-slate-600 text-xs font-bold px-2 py-0.5 rounded-full">{{ $ujianSelesai->count() }}</span>
        </div>
        
        @if($ujianSelesai->count() > 0)
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach($ujianSelesai as $ujian)
                    <x-ujian-card-selesai :ujian="$ujian" :sesi="$sesis[$ujian->id] ?? null" />
                @endforeach
            </div>
        @else
            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-8 text-center text-slate-500 text-sm">
                Belum ada ujian yang kamu selesaikan.
            </div>
        @endif
    </div>

    @if($ujianAktif->isEmpty() && $ujianMendatang->isEmpty() && $ujianSelesai->isEmpty())
        <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center shadow-sm">
            <div class="w-20 h-20 rounded-full mx-auto mb-5 flex items-center justify-center bg-indigo-50">
                <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <h3 class="font-bold text-slate-800 text-xl">Belum Ada Ujian</h3>
            <p class="text-slate-500 mt-2 max-w-sm mx-auto">Ujian yang ditugaskan oleh guru atau lembaga akan muncul di halaman ini.</p>
        </div>
    @endif

</div>
@endsection
