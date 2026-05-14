@props(['ujian', 'sesi'])

@php
    // Hitung sisa waktu dalam detik dari sekarang ke waktu berakhir ujian
    // Jika tidak ada waktu_selesai, gunakan waktu_mulai + durasi menit
    $waktuSelesai = $ujian->waktu_selesai ? $ujian->waktu_selesai : $ujian->waktu_mulai->copy()->addMinutes($ujian->durasi);
    $detikSisa = max(0, $waktuSelesai->diffInSeconds(now()));
@endphp

<div class="bg-emerald-50 dark:bg-emerald-950/20 rounded-2xl border border-emerald-200 dark:border-emerald-800 overflow-hidden shadow-sm hover:shadow-md transition-shadow relative">
    <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-500"></div>
    <div class="p-5 pl-7">
        <div class="flex items-start justify-between gap-3 mb-3">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 animate-pulse flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Sedang Berlangsung
                    </span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-white dark:bg-zinc-900 border border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400">
                        {{ ucfirst($ujian->mode) }}
                    </span>
                </div>
                <h3 class="font-bold text-slate-800 dark:text-slate-100 text-base leading-snug">{{ $ujian->judul }}</h3>
                @if($ujian->deskripsi)
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">{{ $ujian->deskripsi }}</p>
                @endif
            </div>
        </div>
        
        <div class="flex flex-wrap gap-4 text-sm text-slate-600 dark:text-slate-300 mb-5 bg-white dark:bg-zinc-900 bg-opacity-60 dark:bg-opacity-40 p-3 rounded-xl border border-emerald-100 dark:border-emerald-900/50">
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span class="font-medium">{{ $ujian->soals_count ?? $ujian->ujianSoals->count() }} Soal</span>
            </div>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-medium">{{ $ujian->durasi }} Menit</span>
            </div>
            
            <div class="flex items-center gap-1.5 ml-auto text-emerald-700 dark:text-emerald-400 font-semibold" 
                 x-data="{ 
                    sisa: {{ (int)$detikSisa }},
                    formatWaktu(detik) {
                        if(detik <= 0) return 'Waktu Habis';
                        let h = Math.floor(detik / 3600);
                        let m = Math.floor((detik % 3600) / 60);
                        let s = detik % 60;
                        if(h > 0) return `${h}j ${m}m ${s}s`;
                        return `${m}m ${s}s`;
                    }
                 }" 
                 x-init="setInterval(() => { if(sisa > 0) sisa-- }, 1000)">
                <svg class="w-4 h-4 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span x-text="formatWaktu(sisa)">Menghitung...</span>
            </div>
        </div>
        
        <a href="{{ route('siswa.ujian.show', $ujian->id) }}"
            class="block w-full text-center py-2.5 rounded-xl text-sm font-bold text-white transition-all shadow-sm hover:shadow-emerald-200/50 hover:-translate-y-0.5 bg-emerald-600 hover:bg-emerald-700">
            @if($sesi && $sesi->status == 'mengerjakan')
                Lanjutkan Ujian &rarr;
            @else
                Mulai Ujian &rarr;
            @endif
        </a>
    </div>
</div>
