@extends('layouts.siswa')

@section('title', 'Hasil - ' . $sesi->ujian->judul)

@section('content')
<div class="max-w-4xl mx-auto space-y-10 pb-20">
    
    {{-- Breadcrumb --}}
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('siswa.ujian.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-[#388782] dark:text-slate-400 dark:hover:text-slate-200 transition-colors">
                    Ujian Online
                </a>
            </li>
            <li class="inline-flex items-center">
                <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                <a href="{{ route('siswa.ujian.riwayat') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-[#388782] dark:text-slate-400 dark:hover:text-slate-200 transition-colors">
                    Riwayat
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-sm font-bold text-[#388782] md:ml-2">Hasil Akhir</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- Score Hero Section --}}
    <div class="relative group">
        <div class="absolute -inset-1 bg-gradient-to-r from-[#388782] to-[#206D6C] rounded-[2.5rem] blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
        <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800">
            {{-- Decoration --}}
            <div class="absolute top-0 right-0 p-10 opacity-10 pointer-events-none">
                <svg class="w-48 h-48 text-[#388782]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-7.53 6.47a1 1 0 011.415 0 3 3 0 004.242 0 1 1 0 011.415 1.415 5 5 0 01-7.072 0 1 1 0 010-1.415z" clip-rule="evenodd"></path></svg>
            </div>
            
            <div class="p-10 md:p-14 text-center">
                @if($sesi->skor >= 80)
                    <div class="mb-6 inline-flex px-4 py-1.5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-[0.2em] animate-bounce">
                        🎉 Luar Biasa!
                    </div>
                @elseif($sesi->skor >= 60)
                    <div class="mb-6 inline-flex px-4 py-1.5 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-[10px] font-black uppercase tracking-[0.2em]">
                        👍 Kerja Bagus!
                    </div>
                @endif

                <h2 class="text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-[0.3em] mb-4">Skor Pencapaian</h2>
                <div class="flex items-center justify-center gap-2 mb-2">
                    <span class="text-8xl md:text-9xl font-black leading-none tracking-tighter {{ $sesi->skor >= 75 ? 'text-emerald-500' : ($sesi->skor >= 50 ? 'text-amber-500' : 'text-red-500') }}">
                        {{ number_format($sesi->skor, 0) }}
                    </span>
                    <span class="text-2xl font-black text-slate-300 dark:text-slate-700 mt-10">/ 100</span>
                </div>
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-10">{{ $sesi->ujian->judul }}</p>

                <div class="grid grid-cols-3 gap-4 max-w-2xl mx-auto pt-10 border-t border-slate-100 dark:border-zinc-800">
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Benar</p>
                        <p class="text-xl font-black text-emerald-500">{{ $sesi->jawabans->where('is_benar', true)->count() }}</p>
                    </div>
                    <div class="space-y-1 border-x border-slate-100 dark:border-zinc-800 px-4">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Salah</p>
                        <p class="text-xl font-black text-red-400">{{ $sesi->jawabans->where('is_benar', false)->whereNotNull('cbt_opsi_jawaban_id')->count() }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kosong</p>
                        <p class="text-xl font-black text-slate-400">{{ $sesi->ujian->ujianSoals->count() - $sesi->jawabans->whereNotNull('cbt_opsi_jawaban_id')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 dark:bg-zinc-800/50 px-10 py-5 flex flex-col md:flex-row items-center justify-between gap-4 border-t border-slate-100 dark:border-zinc-800">
                <div class="flex items-center gap-6 text-xs font-bold text-slate-500 dark:text-slate-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#388782]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        {{ $sesi->waktu_selesai?->format('d M Y, H:i') }}
                    </div>
                    @if($sesi->waktu_mulai && $sesi->waktu_selesai)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#388782]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Durasi: {{ $sesi->waktu_mulai->diff($sesi->waktu_selesai)->format('%H:%I:%S') }}
                    </div>
                    @endif
                </div>
                <div class="flex gap-3 w-full md:w-auto">
                    <a href="{{ route('siswa.ujian.index') }}" class="flex-1 md:flex-none px-6 py-2.5 rounded-2xl text-xs font-black text-slate-600 dark:text-slate-300 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 transition-all text-center">Daftar Ujian</a>
                    <a href="{{ route('siswa.ujian.riwayat') }}" class="flex-1 md:flex-none px-6 py-2.5 rounded-2xl text-xs font-black text-white bg-[#388782] hover:bg-[#2D6A66] transition-all text-center shadow-lg shadow-[#388782]/20">Lihat Riwayat</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Detailed Review --}}
    @if($sesi->ujian->tampilkan_hasil)
    <div class="space-y-6">
        <div class="flex items-center justify-between px-4">
            <h3 class="text-sm font-black uppercase tracking-widest text-slate-800 dark:text-slate-100">Pembahasan & Analisis</h3>
            <span class="text-[10px] font-bold text-slate-400">{{ $sesi->ujian->ujianSoals->count() }} Pertanyaan</span>
        </div>

        <div class="space-y-4">
            @foreach($sesi->jawabans->sortBy('urutan') as $i => $j)
            @php
                $soal = $j->bankSoal;
                $isCorrect = $j->is_benar;
                $isEssay = $soal->tipe_soal === 'essay';
                $isKosong = !$j->cbt_opsi_jawaban_id && !$j->jawaban_essay;
            @endphp
            <div class="bg-white dark:bg-zinc-900 rounded-[2rem] border border-slate-100 dark:border-zinc-800 overflow-hidden shadow-sm hover:shadow-md transition-all">
                <div class="p-6 md:p-8">
                    {{-- Question Header --}}
                    <div class="flex items-start gap-4 mb-6">
                        <div class="shrink-0 w-10 h-10 rounded-2xl flex items-center justify-center font-black text-sm
                            {{ $isKosong ? 'bg-slate-50 text-slate-400 dark:bg-zinc-800 dark:text-zinc-600' : ($isCorrect ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400' : 'bg-red-50 text-red-500 dark:bg-red-900/20 dark:text-red-400') }}">
                            {{ $i + 1 }}
                        </div>
                        <div class="flex-1 space-y-2">
                            <div class="flex flex-wrap items-center gap-2">
                                @if($soal->mapel)
                                    <span class="px-2.5 py-0.5 rounded-lg bg-slate-50 dark:bg-zinc-800 text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                                        {{ $soal->mapel->nama }}
                                    </span>
                                @endif
                                @if($isKosong)
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">• Tidak Dijawab</span>
                                @elseif($isCorrect)
                                    <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">• Benar</span>
                                @else
                                    <span class="text-[10px] font-black text-red-500 uppercase tracking-widest">• Salah</span>
                                @endif
                            </div>
                            <div class="text-base font-bold text-slate-800 dark:text-slate-200 leading-relaxed">
                                {!! nl2br(e($soal->pertanyaan)) !!}
                            </div>
                            @if($soal->file_media)
                                <img src="{{ asset('storage/' . $soal->file_media) }}" class="mt-4 rounded-2xl border border-slate-100 dark:border-zinc-800 max-h-64" alt="Media">
                            @endif
                        </div>
                    </div>

                    {{-- Answers Section --}}
                    <div class="ml-14 space-y-3">
                        @if($soal->tipe_soal === 'pg')
                            @php $letters = ['A','B','C','D','E']; @endphp
                            <div class="grid gap-2">
                                @foreach($soal->opsiJawabans as $oi => $opsi)
                                @php
                                    $isYourChoice = $j->cbt_opsi_jawaban_id == $opsi->id;
                                    $isKey = $opsi->is_benar;
                                @endphp
                                <div class="flex items-center gap-4 p-4 rounded-2xl border-2 transition-all
                                    {{ $isKey ? 'bg-emerald-50/50 dark:bg-emerald-900/10 border-emerald-500/30' : ($isYourChoice ? 'bg-red-50/50 dark:bg-red-900/10 border-red-500/30' : 'bg-slate-50/30 dark:bg-zinc-800/20 border-transparent') }}">
                                    <div class="shrink-0 w-8 h-8 rounded-lg flex items-center justify-center font-black text-xs
                                        {{ $isKey ? 'bg-emerald-500 text-white' : ($isYourChoice ? 'bg-red-500 text-white' : 'bg-slate-100 dark:bg-zinc-800 text-slate-400') }}">
                                        {{ $letters[$oi] ?? $oi+1 }}
                                    </div>
                                    <div class="flex-1 text-sm font-bold {{ $isKey ? 'text-emerald-700 dark:text-emerald-400' : ($isYourChoice ? 'text-red-700 dark:text-red-400' : 'text-slate-500 dark:text-slate-500') }}">
                                        {!! nl2br(e($opsi->teks_opsi)) !!}
                                    </div>
                                    @if($isKey)
                                        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-6 rounded-2xl bg-slate-50 dark:bg-zinc-800/50 border border-slate-100 dark:border-zinc-800">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Jawaban Kamu:</p>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-300 leading-relaxed">{{ $j->jawaban_essay ?: '(Kosong)' }}</p>
                            </div>
                        @endif

                        {{-- Explanation --}}
                        @if($soal->pembahasan)
                            <div class="mt-6 p-6 rounded-2xl bg-[#388782]/5 dark:bg-[#388782]/10 border border-[#388782]/20">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-[#388782]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
                                    <span class="text-[10px] font-black text-[#388782] uppercase tracking-widest">Penjelasan Solusi</span>
                                </div>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400 leading-relaxed">{!! nl2br(e($soal->pembahasan->pembahasanBersih)) !!}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="p-12 rounded-[2.5rem] bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 text-center space-y-4">
        <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-zinc-800 flex items-center justify-center mx-auto text-slate-300 dark:text-zinc-700">
            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
        </div>
        <h4 class="text-lg font-black text-slate-800 dark:text-slate-200 uppercase tracking-widest">Detail Disembunyikan</h4>
        <p class="text-sm text-slate-400 max-w-sm mx-auto font-medium">Guru membatasi tampilan pembahasan jawaban untuk ujian ini demi menjaga kerahasiaan soal.</p>
    </div>
    @endif

</div>
@endsection
