@props(['ujian', 'sesi'])

@php
    $isSelesai = $sesi && in_array($sesi->status, ['selesai', 'timeout']);
    $hasScore = $isSelesai && $sesi->skor !== null;
    $skor = $hasScore ? (float)$sesi->skor : null;
    $skorColor = 'text-slate-500';
    $skorBg = 'bg-slate-100 dark:bg-zinc-800';
    if ($skor !== null) {
        if ($skor >= 75) { $skorColor = 'text-emerald-600 dark:text-emerald-400'; $skorBg = 'bg-emerald-50 dark:bg-emerald-900/20'; }
        elseif ($skor >= 50) { $skorColor = 'text-amber-600 dark:text-amber-400'; $skorBg = 'bg-amber-50 dark:bg-amber-900/20'; }
        else { $skorColor = 'text-red-600 dark:text-red-400'; $skorBg = 'bg-red-50 dark:bg-red-900/20'; }
    }
    $belumDikoreksi = false;
    if ($sesi) {
        $belumDikoreksi = $sesi->jawabans()
            ->whereHas('bankSoal', fn($q) => $q->where('tipe_soal', 'essay'))
            ->whereNull('is_benar')
            ->exists();
    }
@endphp

<div class="group bg-white dark:bg-zinc-900 rounded-[2rem] border border-slate-200 dark:border-zinc-800 p-6 sm:p-8 shadow-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-500/5 flex flex-col">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4 mb-4">
        <div class="flex-1">
            <div class="flex flex-wrap items-center gap-2.5 mb-4">
                @if($isSelesai)
                    @if($belumDikoreksi)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 text-[10px] font-black uppercase tracking-widest border border-amber-100 dark:border-amber-800/50">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>
                        Menunggu Koreksi
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-50 dark:bg-zinc-800 text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest border border-slate-200 dark:border-zinc-700">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Selesai
                    </span>
                    @endif
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-500 dark:text-red-400 text-[10px] font-black uppercase tracking-widest border border-red-100 dark:border-red-800/50">
                        Waktu Habis
                    </span>
                @endif
                <span class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-50 dark:bg-zinc-800 text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest border border-slate-200 dark:border-zinc-700">
                    {{ ucfirst($ujian->mode) }}
                </span>
            </div>
            <h3 class="font-black text-slate-800 dark:text-slate-100 text-xl leading-tight line-clamp-2">
                {{ $ujian->judul }}
            </h3>
        </div>

        {{-- Score Badge --}}
        <div class="shrink-0 text-center min-w-[56px]">
            @if($belumDikoreksi)
                <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/50 flex flex-col items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span class="text-[9px] font-black text-amber-500 mt-0.5 leading-none">Pending</span>
                </div>
            @elseif($skor !== null)
                <div class="w-14 h-14 rounded-2xl {{ $skorBg }} border border-slate-100 dark:border-zinc-700/50 flex flex-col items-center justify-center">
                    <span class="text-xl font-black leading-none {{ $skorColor }}">{{ number_format($skor, 0) }}</span>
                    <span class="text-[9px] font-black text-slate-400 mt-0.5 leading-none uppercase tracking-widest">Skor</span>
                </div>
            @else
                <div class="w-14 h-14 rounded-2xl bg-slate-50 dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700/50 flex flex-col items-center justify-center text-slate-300 dark:text-zinc-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-[9px] font-black mt-0.5 leading-none uppercase tracking-widest">Lewat</span>
                </div>
            @endif
        </div>
    </div>

    @if($ujian->deskripsi)
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 line-clamp-2 leading-relaxed font-medium">
            {{ $ujian->deskripsi }}
        </p>
    @else
        <div class="mb-8"></div>
    @endif

    {{-- Meta Data --}}
    <div class="mt-auto pt-6 border-t border-slate-100 dark:border-zinc-800/50 grid grid-cols-2 gap-y-5 gap-x-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-slate-50 dark:bg-zinc-800 flex items-center justify-center text-slate-400 border border-slate-100 dark:border-zinc-700/50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Soal</p>
                <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">{{ $ujian->soals_count ?? $ujian->ujianSoals->count() }} Soal</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-slate-50 dark:bg-zinc-800 flex items-center justify-center text-slate-400 border border-slate-100 dark:border-zinc-700/50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Durasi</p>
                <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">{{ $ujian->durasi }} Menit</p>
            </div>
        </div>

        @if($sesi && $sesi->waktu_selesai)
        <div class="col-span-2 flex items-center gap-3 mt-1">
            <div class="w-8 h-8 rounded-xl bg-slate-50 dark:bg-zinc-800 flex items-center justify-center text-slate-400 border border-slate-100 dark:border-zinc-700/50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Dikumpulkan</p>
                <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">{{ \Carbon\Carbon::parse($sesi->waktu_selesai)->format('d M Y, H:i') }}</p>
            </div>
        </div>
        @endif
    </div>

    {{-- CTA --}}
    @if($isSelesai)
        @if($belumDikoreksi)
        <div class="mt-8 w-full py-4 rounded-2xl text-sm font-black text-center text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/50">
            Hasil Belum Keluar
        </div>
        @else
        <a href="{{ route('siswa.ujian.hasil', $sesi->id) }}"
            class="mt-8 flex items-center justify-center gap-2 w-full py-4 rounded-2xl text-sm font-black text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-zinc-800 hover:bg-slate-800 hover:text-white dark:hover:bg-white dark:hover:text-slate-900 border border-slate-100 dark:border-zinc-700/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-500/10">
            Lihat Hasil Lengkap
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
        </a>
        @endif
    @else
        <div class="mt-8 w-full py-4 rounded-2xl text-center text-sm font-black text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-zinc-800/50 border border-slate-100 dark:border-zinc-700/50 cursor-not-allowed select-none">
            Waktu Telah Habis
        </div>
    @endif
</div>
