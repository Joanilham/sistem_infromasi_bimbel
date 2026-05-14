@props(['ujian'])

<div class="bg-slate-50 dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800 overflow-hidden shadow-sm hover:shadow relative">
    <div class="absolute top-0 left-0 w-1.5 h-full bg-slate-300 dark:bg-zinc-700"></div>
    <div class="p-5 pl-7 opacity-80 hover:opacity-100 transition-opacity">
        <div class="flex items-start justify-between gap-3 mb-3">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-200 dark:bg-zinc-800 text-slate-700 dark:text-slate-300">
                        Akan Datang
                    </span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-500 dark:text-slate-400">
                        {{ ucfirst($ujian->mode) }}
                    </span>
                </div>
                <h3 class="font-bold text-slate-700 dark:text-slate-100 text-base leading-snug">{{ $ujian->judul }}</h3>
                @if($ujian->deskripsi)
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">{{ $ujian->deskripsi }}</p>
                @endif
            </div>
        </div>
        
        <div class="flex flex-wrap gap-4 text-sm text-slate-500 dark:text-slate-400 mb-5 bg-white dark:bg-zinc-800 p-3 rounded-xl border border-slate-100 dark:border-zinc-700">
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span>{{ $ujian->soals_count ?? $ujian->ujianSoals->count() }} Soal</span>
            </div>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ $ujian->durasi }} Menit</span>
            </div>
        </div>
        
        <div class="flex items-center justify-center gap-2 p-3 bg-slate-100 dark:bg-zinc-800 rounded-xl text-slate-600 dark:text-slate-300 text-sm font-medium">
            <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            @php
                $diff = now()->diffForHumans($ujian->waktu_mulai, ['parts' => 2, 'short' => true]);
            @endphp
            Mulai dalam {{ str_replace('dari sekarang', '', $diff) }}
            <span class="text-xs font-normal text-slate-400 dark:text-slate-500 ml-1">({{ $ujian->waktu_mulai->format('d M, H:i') }})</span>
        </div>
    </div>
</div>
