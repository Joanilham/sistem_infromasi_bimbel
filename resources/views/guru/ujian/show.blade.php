@extends('layouts.guru')

@section('title', $ujian->judul)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
            <div>
                <a href="{{ route('guru.ujian.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 text-sm inline-flex items-center gap-1 mb-3 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali
                </a>
                <h2 class="text-xl font-bold text-slate-800 dark:text-white">{{ $ujian->judul }}</h2>
                @if($ujian->deskripsi)
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $ujian->deskripsi }}</p>
                @endif
            </div>
            <div class="flex gap-2 shrink-0">
                <a href="{{ route('guru.ujian.edit', $ujian->id) }}" class="px-4 py-2 border border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-slate-300 rounded-xl text-sm font-medium hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors">Edit</a>
                @if($ujian->is_aktif)
                <a href="{{ route('guru.ujian.monitoring', $ujian->id) }}" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition-colors shadow-sm flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span> Monitoring
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Info Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-800 p-4 text-center">
            <div class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ $ujian->ujian_soals_count }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Soal</div>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-800 p-4 text-center">
            <div class="text-2xl font-extrabold text-violet-600 dark:text-violet-400">{{ $ujian->pesertas_count }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Peserta</div>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-800 p-4 text-center">
            <div class="text-2xl font-extrabold text-blue-600 dark:text-blue-400">{{ $ujian->durasi }}'</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Durasi</div>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-800 p-4 text-center">
            <div class="text-2xl font-extrabold text-amber-600 dark:text-amber-400">{{ $ujian->mode === 'resmi' ? '🏅' : '📝' }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">{{ ucfirst($ujian->mode) }}</div>
        </div>
    </div>

    {{-- Jadwal & Konfigurasi --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-5">
            <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3">Jadwal</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Mulai</span><span class="text-slate-800 dark:text-white font-medium">{{ $ujian->waktu_mulai ? $ujian->waktu_mulai->format('d M Y, H:i') : '-' }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Selesai</span><span class="text-slate-800 dark:text-white font-medium">{{ $ujian->waktu_selesai ? $ujian->waktu_selesai->format('d M Y, H:i') : '-' }}</span></div>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-5">
            <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3">Konfigurasi</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Acak Soal</span><span class="font-medium {{ $ujian->acak_soal ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }}">{{ $ujian->acak_soal ? '✓ Ya' : '✗ Tidak' }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Acak Opsi</span><span class="font-medium {{ $ujian->acak_opsi ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }}">{{ $ujian->acak_opsi ? '✓ Ya' : '✗ Tidak' }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Tampil Hasil</span><span class="font-medium {{ $ujian->tampilkan_hasil ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }}">{{ $ujian->tampilkan_hasil ? '✓ Ya' : '✗ Tidak' }}</span></div>
                @if($ujian->token)
                <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Token</span><span class="font-mono text-indigo-600 dark:text-indigo-400 font-bold">{{ $ujian->token }}</span></div>
                @endif
            </div>
        </div>
    </div>

    {{-- Daftar Soal Preview --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800">
        <div class="p-5 border-b border-slate-100 dark:border-zinc-800 flex justify-between items-center">
            <h3 class="font-semibold text-slate-800 dark:text-white text-sm">Daftar Soal ({{ $ujian->ujianSoals->count() }})</h3>
            <a href="{{ route('guru.ujian.soal', $ujian->id) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium">Kelola Soal →</a>
        </div>
        @forelse($ujian->ujianSoals as $idx => $us)
        <div class="px-5 py-3 border-b border-slate-50 dark:border-zinc-800/50 text-sm flex gap-3 items-start">
            <span class="w-6 h-6 bg-slate-100 dark:bg-zinc-800 rounded text-xs font-bold text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0">{{ $idx + 1 }}</span>
            <p class="text-slate-700 dark:text-slate-300 line-clamp-1 flex-1">{!! Str::limit(strip_tags($us->bankSoal->pertanyaan), 80) !!}</p>
            <span class="text-xs text-slate-400 shrink-0">{{ $us->bankSoal->tipe_soal_label }}</span>
        </div>
        @empty
        <div class="p-8 text-center text-sm text-slate-400 dark:text-slate-500">Belum ada soal. <a href="{{ route('guru.ujian.soal', $ujian->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Tambah sekarang →</a></div>
        @endforelse
    </div>

    {{-- Quick Actions --}}
    <div class="flex flex-wrap gap-3 pt-2">
        <a href="{{ route('guru.ujian.soal', $ujian->id) }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-colors shadow-sm">Kelola Soal</a>
        <a href="{{ route('guru.ujian.peserta', $ujian->id) }}" class="px-5 py-2.5 bg-violet-600 text-white rounded-xl text-sm font-semibold hover:bg-violet-700 transition-colors shadow-sm">Atur Peserta</a>
        <form method="POST" action="{{ route('guru.ujian.destroy', $ujian->id) }}" onsubmit="return confirm('Yakin hapus ujian ini?')" class="ml-auto">
            @csrf @method('DELETE')
            <button type="submit" class="px-5 py-2.5 border border-red-200 dark:border-red-900 text-red-600 dark:text-red-400 rounded-xl text-sm font-medium hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">Hapus Ujian</button>
        </form>
    </div>
</div>
@endsection
