@props(['ujian'])

<div class="group bg-white dark:bg-zinc-900 rounded-[2rem] border border-slate-200 dark:border-zinc-800 p-6 sm:p-8 shadow-sm transition-all duration-300 hover:shadow-2xl hover:shadow-slate-500/5 hover:border-slate-300 dark:hover:border-zinc-600 flex flex-col">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4 mb-4">
        <div class="flex-1">
            <div class="flex flex-wrap items-center gap-2.5 mb-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-50 dark:bg-zinc-800 text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest border border-slate-200 dark:border-zinc-700">
                    Akan Datang
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-50 dark:bg-zinc-800 text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest border border-slate-200 dark:border-zinc-700">
                    {{ ucfirst($ujian->mode) }}
                </span>
            </div>
            <h3 class="font-black text-slate-800 dark:text-slate-100 text-xl leading-tight line-clamp-2">
                {{ $ujian->judul }}
            </h3>
        </div>

        <div class="shrink-0 w-12 h-12 rounded-2xl bg-slate-50 dark:bg-zinc-800 flex items-center justify-center text-slate-400 dark:text-slate-500 border border-slate-100 dark:border-zinc-700/50">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
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

        <div class="col-span-2 flex items-center gap-3 mt-1">
            @php
                $diff = now()->diffForHumans($ujian->waktu_mulai, ['parts' => 2, 'short' => false]);
            @endphp
            <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-400 border border-indigo-100 dark:border-indigo-800/50 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-indigo-400/80">Dimulai</p>
                <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">{{ $ujian->waktu_mulai->format('d M, H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="mt-8 w-full py-4 rounded-2xl text-center text-sm font-black text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700/50 cursor-not-allowed select-none">
        Belum Bisa Dikerjakan
    </div>
</div>
