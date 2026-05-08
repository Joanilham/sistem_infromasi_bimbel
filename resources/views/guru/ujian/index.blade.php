@extends('layouts.guru')

@section('title', 'Manajemen Ujian')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800 dark:text-white">Manajemen Ujian</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Buat dan kelola paket ujian CBT dari bank soal Anda</p>
            </div>
            <a href="{{ route('guru.ujian.create') }}"
                class="bg-gradient-to-r from-violet-500 to-purple-600 text-white px-5 py-2.5 rounded-xl hover:from-violet-600 hover:to-purple-700 flex items-center gap-2 text-sm font-semibold shadow-md shadow-violet-500/25 transition-all duration-200 hover:shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Ujian Baru
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="flex gap-2 overflow-x-auto pb-1">
        @php $s = request('status', ''); @endphp
        <a href="{{ route('guru.ujian.index') }}" class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition-colors {{ !$s ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white dark:bg-zinc-900 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800' }}">Semua</a>
        <a href="{{ route('guru.ujian.index', ['status' => 'aktif']) }}" class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition-colors {{ $s === 'aktif' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white dark:bg-zinc-900 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800' }}">🟢 Aktif</a>
        <a href="{{ route('guru.ujian.index', ['status' => 'mendatang']) }}" class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition-colors {{ $s === 'mendatang' ? 'bg-blue-600 text-white shadow-sm' : 'bg-white dark:bg-zinc-900 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800' }}">🔵 Mendatang</a>
        <a href="{{ route('guru.ujian.index', ['status' => 'selesai']) }}" class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition-colors {{ $s === 'selesai' ? 'bg-slate-600 text-white shadow-sm' : 'bg-white dark:bg-zinc-900 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800' }}">⚪ Selesai</a>
    </div>

    {{-- Daftar Ujian --}}
    @if($ujians->isEmpty())
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-16 text-center">
        <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-violet-100 to-purple-50 dark:from-violet-900/30 dark:to-purple-900/20 flex items-center justify-center">
            <svg class="w-10 h-10 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </div>
        <p class="text-slate-600 dark:text-slate-300 font-semibold text-lg">Belum ada ujian</p>
        <p class="text-slate-400 text-sm mt-2">Buat ujian pertama Anda dari bank soal yang sudah ada</p>
        <a href="{{ route('guru.ujian.create') }}" class="mt-6 inline-flex items-center gap-2 bg-gradient-to-r from-violet-500 to-purple-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Ujian Pertama
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($ujians as $ujian)
        @php
            $now = now();
            $isAktif = $ujian->waktu_mulai && $ujian->waktu_mulai <= $now && ($ujian->waktu_selesai === null || $ujian->waktu_selesai >= $now);
            $isMendatang = $ujian->waktu_mulai && $ujian->waktu_mulai > $now;
            $isSelesai = $ujian->waktu_selesai && $ujian->waktu_selesai < $now;
        @endphp
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-5 hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
            <div>
                {{-- Status Badge --}}
                <div class="flex items-center justify-between mb-3">
                    @if($isAktif)
                    <span class="text-[10px] font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2.5 py-1 rounded-full flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> SEDANG BERLANGSUNG
                    </span>
                    @elseif($isMendatang)
                    <span class="text-[10px] font-bold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2.5 py-1 rounded-full">AKAN DATANG</span>
                    @elseif($isSelesai)
                    <span class="text-[10px] font-bold text-slate-500 bg-slate-100 dark:bg-zinc-800 px-2.5 py-1 rounded-full">SELESAI</span>
                    @else
                    <span class="text-[10px] font-bold text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 px-2.5 py-1 rounded-full">DRAFT</span>
                    @endif

                    <span class="text-xs text-slate-400 dark:text-slate-500">{{ $ujian->mode === 'resmi' ? '🏅 Resmi' : '📝 Latihan' }}</span>
                </div>

                {{-- Title --}}
                <h3 class="font-semibold text-slate-800 dark:text-white leading-snug text-base">{{ $ujian->judul }}</h3>
                @if($ujian->deskripsi)
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">{{ $ujian->deskripsi }}</p>
                @endif

                {{-- Stats --}}
                <div class="flex gap-4 mt-3 text-xs text-slate-500 dark:text-slate-400">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        {{ $ujian->ujian_soals_count }} soal
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $ujian->durasi }} menit
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $ujian->pesertas_count }} peserta
                    </span>
                </div>

                {{-- Waktu --}}
                @if($ujian->waktu_mulai)
                <div class="mt-3 text-[10px] text-slate-400 dark:text-slate-500">
                    {{ $ujian->waktu_mulai->format('d M Y, H:i') }}
                    @if($ujian->waktu_selesai) — {{ $ujian->waktu_selesai->format('d M Y, H:i') }} @endif
                </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100 dark:border-zinc-800">
                <a href="{{ route('guru.ujian.show', $ujian->id) }}"
                    class="bg-indigo-600 text-white text-xs px-3.5 py-1.5 rounded-lg hover:bg-indigo-700 font-medium transition-colors shadow-sm">
                    Detail
                </a>
                <div class="flex gap-2">
                    <a href="{{ route('guru.ujian.soal', $ujian->id) }}" class="text-xs text-slate-500 dark:text-slate-400 hover:text-indigo-600 font-medium" title="Kelola Soal">Soal</a>
                    @if($isAktif)
                    <a href="{{ route('guru.ujian.monitoring', $ujian->id) }}" class="text-xs text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 font-medium">Monitor</a>
                    @endif
                    <a href="{{ route('guru.ujian.edit', $ujian->id) }}" class="text-xs text-slate-500 dark:text-slate-400 hover:text-amber-600 font-medium">Edit</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="flex justify-center">{{ $ujians->links() }}</div>
    @endif
</div>
@endsection
