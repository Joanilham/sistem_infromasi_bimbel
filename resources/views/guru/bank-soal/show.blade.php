@extends('layouts.guru')

@section('title', 'Detail Soal')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
            <div>
                <a href="{{ route('guru.bank-soal.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 text-sm inline-flex items-center gap-1 mb-3 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke Bank Soal
                </a>
                <h2 class="text-xl font-bold text-slate-800 dark:text-white">Preview Soal</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Lihat tampilan soal seperti yang akan dilihat siswa</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('guru.bank-soal.edit', $soal->id) }}"
                    class="px-4 py-2 border border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-slate-300 rounded-xl text-sm font-medium hover:bg-slate-50 dark:hover:bg-zinc-800 flex items-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                <form action="{{ route('guru.bank-soal.destroy', $soal->id) }}" method="POST" onsubmit="event.preventDefault(); confirmDelete('Hapus Soal?', 'Soal ini akan dihapus permanen!', this)">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 border border-red-200 dark:border-red-900 text-red-600 dark:text-red-400 rounded-xl text-sm font-medium hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Metadata Badges --}}
    <div class="flex flex-wrap gap-2">
        @if($soal->mapel)
        <span class="text-xs font-medium text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-zinc-800 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-zinc-700">
            📚 {{ $soal->mapel->nama }}
        </span>
        @endif
        @if($soal->bab)
        <span class="text-xs font-medium text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-zinc-800 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-zinc-700">
            📖 {{ $soal->bab->nama }}
        </span>
        @endif
        @if($soal->tipe_soal === 'pg')
        <span class="text-xs font-semibold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-3 py-1.5 rounded-lg">Pilihan Ganda</span>
        @else
        <span class="text-xs font-semibold text-purple-700 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/30 px-3 py-1.5 rounded-lg">Essay</span>
        @endif
        @php
            $kColors = ['easy' => 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/30', 'medium' => 'text-yellow-700 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/30', 'hard' => 'text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/30'];
        @endphp
        <span class="text-xs font-semibold {{ $kColors[$soal->tingkat_kesulitan] ?? '' }} px-3 py-1.5 rounded-lg">{{ $soal->tingkat_kesulitan_label }}</span>
        @if($soal->status === 'published')
        <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-3 py-1.5 rounded-lg flex items-center gap-1">
            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Aktif
        </span>
        @else
        <span class="text-xs font-semibold text-slate-500 bg-slate-50 dark:bg-zinc-800 px-3 py-1.5 rounded-lg">Draft</span>
        @endif
    </div>

    {{-- Pertanyaan --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
        <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-4">Pertanyaan</h3>
        <div class="prose prose-sm dark:prose-invert max-w-none text-slate-800 dark:text-slate-100 leading-relaxed">
            {!! nl2br(e($soal->pertanyaan)) !!}
        </div>

        @if($soal->file_media)
        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-zinc-800">
            <img src="{{ asset('storage/' . $soal->file_media) }}" alt="Media Soal" class="max-h-64 rounded-xl border border-slate-200 dark:border-zinc-700 shadow-sm">
        </div>
        @endif
    </div>

    {{-- Opsi Jawaban --}}
    @if($soal->tipe_soal === 'pg' && $soal->opsiJawabans->count())
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
        <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-4">Opsi Jawaban</h3>
        <div class="space-y-2">
            @foreach($soal->opsiJawabans as $idx => $opsi)
            <div class="flex items-start gap-3 p-3 rounded-xl transition-colors {{ $opsi->is_benar ? 'bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800' : 'bg-slate-50 dark:bg-zinc-800/50 border border-transparent' }}">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold shrink-0 {{ $opsi->is_benar ? 'bg-emerald-500 text-white shadow-sm shadow-emerald-500/30' : 'bg-slate-200 dark:bg-zinc-700 text-slate-600 dark:text-slate-300' }}">
                    {{ chr(65 + $idx) }}
                </span>
                <span class="text-sm pt-1 flex-1 {{ $opsi->is_benar ? 'text-emerald-800 dark:text-emerald-200 font-semibold' : 'text-slate-700 dark:text-slate-300' }}">
                    {{ $opsi->teks_opsi }}
                </span>
                @if($opsi->is_benar)
                <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Pembahasan --}}
    @if($soal->pembahasan)
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6" x-data="{ open: false }">
        <button @click="open = !open" class="flex items-center justify-between w-full text-left">
            <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                Pembahasan
            </h3>
            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="open" x-transition class="mt-4 pt-4 border-t border-slate-100 dark:border-zinc-800">
            <div class="prose prose-sm dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 bg-amber-50/50 dark:bg-amber-900/10 p-4 rounded-xl">
                {!! nl2br(e($soal->pembahasan->pembahasanBersih)) !!}
            </div>
        </div>
    </div>
    @endif

    {{-- Info --}}
    <div class="bg-slate-50 dark:bg-zinc-800/30 rounded-xl border border-slate-100 dark:border-zinc-800 p-4 text-xs text-slate-400 dark:text-slate-500 flex flex-wrap gap-x-6 gap-y-1">
        <span>Dibuat oleh: <strong class="text-slate-600 dark:text-slate-300">{{ $soal->creator?->name ?? '-' }}</strong></span>
        <span>Dibuat: <strong class="text-slate-600 dark:text-slate-300">{{ $soal->created_at->format('d M Y H:i') }}</strong></span>
        <span>Terakhir diubah: <strong class="text-slate-600 dark:text-slate-300">{{ $soal->updated_at->diffForHumans() }}</strong></span>
    </div>
</div>
@endsection