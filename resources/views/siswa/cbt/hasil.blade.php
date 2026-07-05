@extends('layouts.siswa')

@section('title', 'Hasil - ' . $sesi->ujian->judul)

@push('head')
<style>
    .hero-gradient {
        background: linear-gradient(135deg, #0F5253 0%, #1a7471 50%, #299c98 100%);
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(60px, -60px) scale(1.2); }
        66% { transform: translate(-40px, 40px) scale(0.8); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
</style>
@endpush

@section('content')
<div class="space-y-6 pb-12">

    {{-- HERO SECTION --}}
    <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl hero-gradient p-8 sm:p-12">
        <!-- Animated Blobs -->
        <div class="absolute -top-20 -left-20 w-80 h-80 rounded-full bg-emerald-300/40 blur-3xl animate-blob pointer-events-none"></div>
        <div class="absolute top-0 -right-20 w-96 h-96 rounded-full bg-white/30 blur-3xl animate-blob animation-delay-2000 pointer-events-none"></div>
        <div class="absolute -bottom-32 left-1/3 w-80 h-80 rounded-full bg-teal-200/40 blur-3xl animate-blob animation-delay-4000 pointer-events-none"></div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle, #fff 2px, transparent 2px); background-size: 32px 32px;"></div>

        <div class="relative z-10 flex flex-col justify-between gap-6">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 mb-4">
                    <span class="text-xs font-bold tracking-widest text-white uppercase">Hasil Ujian</span>
                </div>
                
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight mb-4 leading-tight">
                    {{ $sesi->ujian->judul }}
                </h1>
                
                @php
                    $skor = $sesi->skor;
                    $motivasi = "";
                    if($sesi->belum_dikoreksi_count > 0) {
                        $motivasi = "Ujianmu sedang menunggu koreksi guru. Harap bersabar ya! ⏳";
                    } else {
                        if($skor >= 80) {
                            $motivasi = "Luar biasa! Kerja kerasmu terbayar lunas. Pertahankan prestasimu dan teruslah bersinar! 🌟";
                        } elseif($skor >= 60) {
                            $motivasi = "Kerja bagus! Kamu sudah berusaha dengan baik. Terus tingkatkan belajarmu untuk hasil yang lebih maksimal! 💪";
                        } else {
                            $motivasi = "Jangan menyerah! Nilai hanyalah angka sementara. Jadikan ini sebagai motivasi untuk belajar lebih giat lagi. Kamu pasti bisa! 🔥";
                        }
                    }
                @endphp
                <p class="text-[#cce8e4] text-sm sm:text-base md:text-lg max-w-2xl font-medium leading-relaxed">
                    {{ $motivasi }}
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('siswa.ujian.riwayat') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-white text-slate-900 font-bold shadow-sm hover:bg-slate-50 transition-colors">
                    Lihat Riwayat
                </a>
                <a href="{{ route('siswa.ujian.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-white/20 backdrop-blur-md border border-white/30 text-white font-bold hover:bg-white/30 transition-colors">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- SCORE & STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- MAIN SCORE --}}
        <div class="md:col-span-1 bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 p-8 flex flex-col items-center justify-center text-center shadow-xl shadow-slate-200/40 dark:shadow-none relative overflow-hidden group hover:border-emerald-200 transition-colors">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <h3 class="relative z-10 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Skor Akhir</h3>
            
            @if($sesi->belum_dikoreksi_count > 0)
                <div class="relative z-10 flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-amber-100 to-amber-50 dark:from-amber-900/40 dark:to-amber-900/10 text-amber-500 mb-4 border border-amber-200 dark:border-amber-800/50 shadow-inner">
                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="relative z-10 text-sm font-bold text-slate-600 dark:text-slate-400">Menunggu Koreksi</p>
            @else
                <div class="relative z-10 flex items-center justify-center w-32 h-32 rounded-full bg-gradient-to-br from-slate-50 to-white dark:from-zinc-800 dark:to-zinc-900 mb-4 border-4 border-slate-100 dark:border-zinc-800 shadow-[inset_0_2px_10px_rgba(0,0,0,0.02)]">
                    <div class="inline-flex items-baseline justify-center gap-0.5">
                        <span class="text-5xl font-black tracking-tighter {{ $skor >= 75 ? 'text-emerald-500' : ($skor >= 50 ? 'text-amber-500' : 'text-red-500') }}">
                            {{ number_format($skor, 0) }}
                        </span>
                    </div>
                </div>
                <span class="relative z-10 text-sm font-bold text-slate-400 uppercase tracking-widest">Dari 100</span>
            @endif
        </div>

        {{-- STATS --}}
        <div class="md:col-span-2 bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 p-8 shadow-xl shadow-slate-200/40 dark:shadow-none flex items-center">
            <div class="grid grid-cols-3 w-full gap-4 sm:gap-6">
                <div class="flex flex-col items-center justify-center text-center p-6 rounded-[2rem] bg-emerald-50/50 dark:bg-emerald-900/10 border border-emerald-100/50 dark:border-emerald-900/20 hover:bg-emerald-50 transition-colors">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-[10px] font-black text-emerald-600/70 dark:text-emerald-400 uppercase tracking-widest mb-1">Benar</p>
                    <p class="text-4xl font-black text-emerald-600 dark:text-emerald-400">{{ $sesi->jawabans->where('is_benar', true)->count() }}</p>
                </div>
                <div class="flex flex-col items-center justify-center text-center p-6 rounded-[2rem] bg-red-50/50 dark:bg-red-900/10 border border-red-100/50 dark:border-red-900/20 hover:bg-red-50 transition-colors">
                    <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 text-red-500 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <p class="text-[10px] font-black text-red-600/70 dark:text-red-400 uppercase tracking-widest mb-1">Salah</p>
                    <p class="text-4xl font-black text-red-600 dark:text-red-400">{{ $sesi->jawabans->where('is_benar', false)->whereNotNull('cbt_opsi_jawaban_id')->count() }}</p>
                </div>
                <div class="flex flex-col items-center justify-center text-center p-6 rounded-[2rem] bg-slate-50 dark:bg-zinc-800/50 border border-slate-100 dark:border-zinc-700/50 hover:bg-slate-100 transition-colors">
                    <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-zinc-700 text-slate-500 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"></path></svg>
                    </div>
                    <p class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Kosong</p>
                    <p class="text-4xl font-black text-slate-700 dark:text-slate-300">{{ $sesi->ujian->ujianSoals->count() - $sesi->jawabans->whereNotNull('cbt_opsi_jawaban_id')->whereNotNull('jawaban_essay')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- DETAILED REVIEW --}}
    @if($sesi->ujian->tampilkan_hasil)
            <div class="mt-12">
                <div class="flex items-center gap-4 mb-8 px-4">
                    <div class="h-12 w-3 rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/30"></div>
                    <div>
                        <h3 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Riwayat & Analisis Jawaban</h3>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">Cermati detail jawabanmu di bawah ini untuk bahan evaluasi.</p>
                    </div>
                </div>

                <div class="space-y-6">
                    @foreach($sesi->jawabans as $index => $j)
                    @php
                        if (!$j->bankSoal) continue;
                        $soal = $j->bankSoal;
                        $isCorrect = $j->is_benar;
                        $isKosong = is_null($j->cbt_opsi_jawaban_id) && is_null($j->jawaban_essay);
                    @endphp
                    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-xl shadow-slate-200/20 dark:shadow-none overflow-hidden">
                        
                        {{-- Capture Explanation HTML so we don't repeat code --}}
                        @php ob_start(); @endphp
                            @if($soal->pembahasan)
                            <div class="p-6 sm:p-8 rounded-[1.5rem] bg-[#388782]/5 dark:bg-[#388782]/10 border border-[#388782]/20">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-8 h-8 rounded-full bg-[#388782]/10 flex items-center justify-center text-[#388782]">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <span class="text-xs font-black text-[#388782] uppercase tracking-[0.15em]">Penjelasan Solusi</span>
                                </div>
                                <div class="text-sm font-medium text-slate-700 dark:text-slate-300 leading-relaxed prose prose-sm prose-slate dark:prose-invert">
                                    {!! \App\Helpers\HtmlSanitizer::clean($soal->pembahasan->teks_pembahasan) !!}
                                </div>
                            </div>
                            @else
                            <div class="p-6 sm:p-8 rounded-[1.5rem] bg-slate-50 dark:bg-zinc-800/50 border border-slate-100 dark:border-zinc-800 flex flex-col items-center justify-center text-center">
                                <div class="w-12 h-12 rounded-full bg-slate-200 dark:bg-zinc-700 flex items-center justify-center text-slate-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <span class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Penjelasan Solusi</span>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 max-w-xs mx-auto">Guru tidak melampirkan teks pembahasan untuk soal ini.</p>
                            </div>
                            @endif
                        @php $explanationHtml = ob_get_clean(); @endphp

                        <div class="flex flex-col lg:flex-row p-8 lg:p-10 gap-8 lg:gap-12 relative">
                            
                            {{-- Left Column (Question + Desktop Explanation) --}}
                            <div class="flex-1 lg:w-1/2 flex flex-col gap-8 relative">
                                <div class="absolute -left-10 top-0 bottom-0 w-1.5 bg-slate-100 dark:bg-zinc-800 hidden lg:block rounded-full"></div>
                                
                                {{-- Question Section --}}
                                <div class="mb-4">
                                    <div class="flex flex-wrap items-center gap-3 mb-5">
                                        <span class="px-4 py-1.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl text-xs font-black tracking-widest uppercase shadow-md">Soal {{ $index + 1 }}</span>
                                        @if($isKosong)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-zinc-800 text-[11px] font-black text-slate-500 uppercase tracking-widest"><div class="w-1.5 h-1.5 rounded-full bg-slate-400"></div> Tidak Dijawab</span>
                                        @elseif($isCorrect === true)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-[11px] font-black text-emerald-600 uppercase tracking-widest"><div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> Benar</span>
                                        @elseif($isCorrect === false)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-red-50 dark:bg-red-900/20 text-[11px] font-black text-red-600 uppercase tracking-widest"><div class="w-1.5 h-1.5 rounded-full bg-red-500"></div> Salah</span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 dark:bg-amber-900/20 text-[11px] font-black text-amber-600 uppercase tracking-widest"><div class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></div> Menunggu Koreksi</span>
                                        @endif
                                    </div>
                                    <div class="text-lg font-semibold text-slate-800 dark:text-slate-200 leading-relaxed prose prose-slate dark:prose-invert max-w-none">
                                        {!! \App\Helpers\HtmlSanitizer::clean($soal->pertanyaan) !!}
                                    </div>
                                </div>

                                {{-- Explanation for Desktop (Placed directly under Question) --}}
                                <div class="hidden lg:block h-fit">
                                    {!! $explanationHtml !!}
                                </div>
                            </div>

                            {{-- Answers Section --}}
                            <div class="flex-1 lg:w-1/2">
                                @if($soal->tipe_soal === 'pg')
                                    @php $letters = ['A','B','C','D','E']; @endphp
                                    <div class="grid gap-3">
                                        @foreach($soal->opsiJawabans as $oi => $opsi)
                                        @php
                                            $isYourChoice = $j->cbt_opsi_jawaban_id == $opsi->id;
                                            $isKey = $opsi->is_benar;
                                        @endphp
                                        <div class="flex items-center gap-4 p-4 sm:p-5 rounded-[1.5rem] border-2 transition-all
                                            {{ $isKey ? 'bg-emerald-50/50 dark:bg-emerald-900/10 border-emerald-400' : ($isYourChoice ? 'bg-red-50/50 dark:bg-red-900/10 border-red-400' : 'bg-slate-50/50 dark:bg-zinc-800/30 border-transparent hover:bg-slate-100') }}">
                                            <div class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm shadow-sm
                                                {{ $isKey ? 'bg-emerald-500 text-white shadow-emerald-500/30' : ($isYourChoice ? 'bg-red-500 text-white shadow-red-500/30' : 'bg-white dark:bg-zinc-700 text-slate-500 border border-slate-200 dark:border-zinc-600') }}">
                                                {{ $letters[$oi] ?? $oi+1 }}
                                            </div>
                                            <div class="flex-1 text-sm sm:text-base font-semibold {{ $isKey ? 'text-emerald-800 dark:text-emerald-400' : ($isYourChoice ? 'text-red-800 dark:text-red-400' : 'text-slate-600 dark:text-slate-400') }}">
                                                {!! \App\Helpers\HtmlSanitizer::clean($opsi->teks_opsi) !!}
                                            </div>
                                            @if($isKey)
                                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-6 sm:p-8 rounded-[1.5rem] bg-slate-50 dark:bg-zinc-800/50 border border-slate-100 dark:border-zinc-800">
                                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Jawaban Kamu</p>
                                        <p class="text-base font-semibold text-slate-700 dark:text-slate-300 leading-relaxed">{{ $j->jawaban_essay ?: '(Kosong - Tidak Dijawab)' }}</p>
                                    </div>
                                @endif

                            </div>

                            {{-- Explanation for Mobile (Placed after Answers) --}}
                            <div class="block lg:hidden w-full h-fit">
                                {!! $explanationHtml !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
    @else
    <div class="mt-8 p-12 rounded-[2.5rem] bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 text-center space-y-4 shadow-sm">
        <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-zinc-800 flex items-center justify-center mx-auto text-slate-300 dark:text-zinc-700">
            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
        </div>
        <h4 class="text-lg font-black text-slate-800 dark:text-slate-200 uppercase tracking-widest">Detail Disembunyikan</h4>
        <p class="text-sm text-slate-400 max-w-sm mx-auto font-medium">Guru membatasi tampilan pembahasan jawaban untuk ujian ini demi menjaga kerahasiaan soal.</p>
    </div>
    @endif

</div>
@endsection
