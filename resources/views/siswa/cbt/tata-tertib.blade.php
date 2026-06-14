@extends('layouts.siswa')

@section('title', $ujian->judul)

@section('content')
@php
    $sudahSelesai = $sudahSelesai ?? false;
    $sedangMengerjakan = $sedangMengerjakan ?? false;
    $attempt = $attempt ?? null;
@endphp

<div class="max-w-4xl mx-auto space-y-8 pb-20">
    {{-- Breadcrumb --}}
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('siswa.ujian.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-[#388782] dark:text-slate-400 dark:hover:text-slate-200 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                    Ujian Online
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-sm font-bold text-[#388782] md:ml-2">Instruksi</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-200 dark:border-zinc-800 shadow-2xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
        {{-- Banner Section --}}
        <div class="relative p-8 md:p-12 bg-gradient-to-br from-[#388782] to-[#206D6C] text-white overflow-hidden">
            <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-10 -left-10 h-40 w-40 rounded-full bg-emerald-400/10 blur-2xl pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-6">
                    <span class="px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-[#A2D5CB] text-[10px] font-black uppercase tracking-widest">
                        {{ $ujian->mode === 'resmi' ? '📋 Ujian Resmi' : '📝 Latihan Mandiri' }}
                    </span>
                    @if($sudahSelesai)
                        <span class="px-3.5 py-1.5 rounded-full bg-emerald-500/20 backdrop-blur-md border border-emerald-500/30 text-emerald-300 text-[10px] font-black uppercase tracking-widest">
                            ✓ Telah Selesai
                        </span>
                    @endif
                </div>
                <h1 class="text-3xl md:text-4xl font-black mb-4 tracking-tight leading-tight">{{ $ujian->judul }}</h1>
                @if($ujian->deskripsi)
                    <p class="text-[#A2D5CB] text-lg font-medium opacity-90 max-w-2xl leading-relaxed">{{ $ujian->deskripsi }}</p>
                @endif
            </div>
        </div>

        <div class="p-8 md:p-12 space-y-12">
            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                <div class="bg-slate-50 dark:bg-zinc-800/50 rounded-[2rem] p-6 text-center transition-transform hover:-translate-y-1">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Total Soal</p>
                    <p class="text-3xl font-black text-[#388782]">{{ $ujian->soals_count }}</p>
                    <p class="text-[10px] text-slate-500 font-bold mt-1">Butir Pertanyaan</p>
                </div>
                <div class="bg-slate-50 dark:bg-zinc-800/50 rounded-[2rem] p-6 text-center transition-transform hover:-translate-y-1">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Durasi</p>
                    <p class="text-3xl font-black text-amber-600">{{ $ujian->durasi }}</p>
                    <p class="text-[10px] text-slate-500 font-bold mt-1">Menit Pengerjaan</p>
                </div>
                <div class="bg-slate-50 dark:bg-zinc-800/50 rounded-[2rem] p-6 text-center transition-transform hover:-translate-y-1 col-span-2 md:col-span-1">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Percobaan</p>
                    <p class="text-3xl font-black text-emerald-600">{{ $ujian->limit_attempt == 0 ? '∞' : $ujian->limit_attempt }}</p>
                    <p class="text-[10px] text-slate-500 font-bold mt-1">Batas Maksimal</p>
                </div>
            </div>

            {{-- Rules and Instructions --}}
            <div class="grid md:grid-cols-2 gap-10">
                <div class="space-y-6">
                    <h3 class="text-sm font-black uppercase tracking-widest text-slate-800 dark:text-slate-100 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-[#388782] text-white flex items-center justify-center text-[10px]">1</span>
                        Tata Tertib Penting
                    </h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-4 p-4 rounded-2xl bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/20">
                            <span class="shrink-0 w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </span>
                            <div>
                                <p class="text-sm font-bold text-amber-900 dark:text-amber-400">Koneksi & Timer</p>
                                <p class="text-xs text-amber-800 dark:text-amber-500/80 mt-0.5 leading-relaxed">Timer tetap berjalan meskipun tab ditutup. Pastikan internet Anda stabil sebelum mulai.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4 p-4 rounded-2xl bg-indigo-50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-900/20">
                            <span class="shrink-0 w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </span>
                            <div>
                                <p class="text-sm font-bold text-indigo-900 dark:text-indigo-400">Navigasi Bebas</p>
                                <p class="text-xs text-indigo-800 dark:text-indigo-500/80 mt-0.5 leading-relaxed">Anda dapat melompati soal dan kembali lagi sebelum waktu berakhir atau menekan tombol selesai.</p>
                            </div>
                        </li>
                        @if($ujian->token)
                        <li class="flex items-start gap-4 p-4 rounded-2xl bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/20">
                            <span class="shrink-0 w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </span>
                            <div>
                                <p class="text-sm font-bold text-red-900 dark:text-red-400">Memerlukan Token</p>
                                <p class="text-xs text-red-800 dark:text-red-500/80 mt-0.5 leading-relaxed">Mintalah token pengerjaan kepada pengawas atau guru yang bersangkutan.</p>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>

                <div class="space-y-6">
                    <h3 class="text-sm font-black uppercase tracking-widest text-slate-800 dark:text-slate-100 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-[#388782] text-white flex items-center justify-center text-[10px]">2</span>
                        Status & Riwayat
                    </h3>
                    
                    @if($sudahSelesai && $attempt)
                    <div class="relative overflow-hidden p-6 rounded-[2rem] bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700">
                        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-[#388782]/5 rounded-full blur-2xl"></div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Hasil Percobaan Terakhir</p>
                        <div class="flex items-end gap-3 mb-6">
                            <span class="text-6xl font-black leading-none {{ $attempt->skor >= 75 ? 'text-emerald-500' : ($attempt->skor >= 50 ? 'text-amber-500' : 'text-red-500') }}">
                                {{ number_format($attempt->skor, 0) }}
                            </span>
                            <span class="text-sm font-bold text-slate-400 mb-2">/ 100</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-slate-500">Selesai pada {{ $attempt->waktu_selesai?->format('d M Y, H:i') }}</span>
                            <a href="{{ route('siswa.ujian.hasil', $attempt->id) }}" class="text-xs font-black text-[#388782] hover:underline uppercase tracking-widest">Detail Hasil &rarr;</a>
                        </div>
                    </div>
                    @else
                    <div class="p-8 rounded-[2rem] border-2 border-dashed border-slate-200 dark:border-zinc-800 flex flex-col items-center justify-center text-center">
                        <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-zinc-800 text-slate-300 dark:text-zinc-700 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-400">Belum ada riwayat pengerjaan</p>
                        <p class="text-xs text-slate-400/70 mt-1 max-w-[200px]">Data nilai akan muncul setelah Anda menyelesaikan sesi ujian ini.</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Action Area --}}
            <div class="pt-10 border-t border-slate-100 dark:border-zinc-800">
                @if($sedangMengerjakan)
                    <div class="bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-900/30 p-8 rounded-[2rem] text-center">
                        <h4 class="text-xl font-black text-amber-800 dark:text-amber-500 mb-2">Lanjutkan Pengerjaan?</h4>
                        <p class="text-sm text-amber-700 dark:text-amber-600/80 mb-8 max-w-md mx-auto">Sesi Anda masih aktif. Pastikan untuk segera menyelesaikannya sebelum batas waktu berakhir.</p>
                        <a href="{{ route('siswa.ujian.soal', [$attempt->id, 1]) }}"
                            class="inline-flex items-center justify-center gap-3 px-10 py-4 rounded-2xl text-base font-black text-white transition-all hover:scale-105 active:scale-95 shadow-xl shadow-amber-500/20"
                            style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Lanjutkan Sesi Ujian
                        </a>
                    </div>
                @elseif($sudahSelesai && $ujian->limit_attempt > 0 && $attemptKe > $ujian->limit_attempt)
                    <div class="bg-slate-50 dark:bg-zinc-800 p-8 rounded-[2rem] text-center border border-slate-200 dark:border-zinc-700">
                        <svg class="w-12 h-12 text-slate-300 dark:text-zinc-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        <p class="text-slate-500 dark:text-slate-400 font-black">Batas Percobaan Habis</p>
                        <p class="text-xs text-slate-400 mt-1">Anda sudah mencapai batas pengerjaan maksimal untuk ujian ini.</p>
                    </div>
                @else
                    <div x-data="{ setuju: false, token: '' }" class="max-w-xl mx-auto space-y-8">
                        <label class="group relative flex items-center gap-4 p-5 rounded-3xl bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-800 cursor-pointer transition-all hover:bg-white dark:hover:bg-zinc-800 hover:border-[#388782] hover:shadow-xl hover:shadow-[#388782]/5">
                            <div class="relative flex items-center">
                                <input type="checkbox" x-model="setuju" class="w-6 h-6 text-[#388782] rounded-lg border-slate-300 dark:border-zinc-700 focus:ring-[#388782] focus:ring-offset-0 bg-white dark:bg-zinc-900 transition-all cursor-pointer">
                            </div>
                            <span class="text-sm font-bold text-slate-600 dark:text-slate-300 group-hover:text-[#388782] transition-colors leading-relaxed">
                                Saya telah memahami seluruh tata tertib dan siap menanggung konsekuensi atas pelanggaran yang dilakukan.
                            </span>
                        </label>

                        <div x-show="setuju" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-6">
                            @if($ujian->token)
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-4">Otentikasi Ujian</label>
                                <input type="text" name="token" required x-model="token" placeholder="Ketik Token Ujian di Sini..."
                                    class="w-full bg-white dark:bg-zinc-900 border-2 border-slate-100 dark:border-zinc-800 rounded-3xl px-8 py-5 text-lg font-black tracking-[0.3em] text-[#388782] placeholder:text-slate-300 dark:placeholder:text-zinc-700 focus:border-[#388782] focus:ring-0 outline-none transition-all text-center uppercase">
                            </div>
                            @endif

                            <form action="{{ route('siswa.ujian.mulai', $ujian->id) }}" method="POST">
                                @csrf
                                @if($ujian->token) <input type="hidden" name="token" x-bind:value="token"> @endif
                                <button type="submit"
                                    :disabled="!setuju {{ $ujian->token ? '|| token.trim() === \'\'' : '' }}"
                                    :class="!setuju {{ $ujian->token ? '|| token.trim() === \'\'' : '' }} ? 'opacity-30 cursor-not-allowed grayscale' : 'hover:scale-[1.02] active:scale-95 shadow-2xl shadow-[#388782]/30'"
                                    class="w-full py-5 rounded-3xl text-lg font-black text-white transition-all bg-gradient-to-r from-[#388782] to-[#206D6C]">
                                    🚀 {{ $sudahSelesai ? 'Ulangi Ujian Sekarang' : 'Siap, Mulai Ujian!' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
