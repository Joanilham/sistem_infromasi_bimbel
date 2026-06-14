@props(['ujian', 'sesi'])

@php
    $showCountdown = false;
    $detikSisa = 0;
    $deadlineText = '';
    $detikHinggaDeadline = 0;

    if ($sesi && $sesi->status === 'mengerjakan') {
        $showCountdown = true;
        $waktuHabis = $sesi->waktu_mulai->copy()->addMinutes($ujian->durasi);
        $detikSisa = $waktuHabis->isFuture() ? (int)($waktuHabis->timestamp - now()->timestamp) : 0;
    } else {
        if ($ujian->waktu_selesai) {
            $deadline = \Carbon\Carbon::parse($ujian->waktu_selesai);
            $deadlineText = $deadline->format('d M, H:i');
            // timestamp deadline - timestamp sekarang = detik tersisa (positif jika masih di masa depan)
            $detikHinggaDeadline = max(0, (int)($deadline->timestamp - now()->timestamp));
        } else {
            $deadlineText = 'Tanpa tenggat';
        }
    }
@endphp

<div class="group relative bg-white dark:bg-zinc-900 rounded-[2rem] border border-slate-200 dark:border-zinc-800 p-6 sm:p-8 shadow-sm transition-all duration-300 hover:shadow-2xl hover:shadow-emerald-500/5 hover:border-emerald-500/30 flex flex-col">
    
    {{-- Header Section --}}
    <div class="flex items-start justify-between gap-4 mb-4">
        <div class="flex-1">
            <div class="flex flex-wrap items-center gap-2.5 mb-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest border border-emerald-100 dark:border-emerald-800/50">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 {{ $showCountdown ? 'animate-pulse' : '' }}"></span>
                    {{ $showCountdown ? 'Sedang Dikerjakan' : 'Berlangsung' }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-50 dark:bg-zinc-800 text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest border border-slate-200 dark:border-zinc-700">
                    {{ ucfirst($ujian->mode) }}
                </span>
            </div>
            <h3 class="font-black text-slate-800 dark:text-slate-100 text-xl leading-tight group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors line-clamp-2">
                {{ $ujian->judul }}
            </h3>
        </div>
        
        {{-- Icon Box --}}
        <div class="shrink-0 w-12 h-12 rounded-2xl bg-slate-50 dark:bg-zinc-800 flex items-center justify-center text-slate-400 dark:text-slate-500 border border-slate-100 dark:border-zinc-700/50 group-hover:bg-emerald-600 group-hover:text-white group-hover:border-transparent transition-all duration-300 group-hover:-rotate-6 group-hover:scale-110">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
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
            <div class="w-8 h-8 rounded-xl bg-slate-50 dark:bg-zinc-800 flex items-center justify-center text-slate-400 border border-slate-100 dark:border-zinc-700/50 group-hover:bg-white dark:group-hover:bg-zinc-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Soal</p>
                <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">{{ $ujian->soals_count ?? $ujian->ujianSoals->count() }} Soal</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-slate-50 dark:bg-zinc-800 flex items-center justify-center text-slate-400 border border-slate-100 dark:border-zinc-700/50 group-hover:bg-white dark:group-hover:bg-zinc-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Durasi</p>
                <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">{{ $ujian->durasi }} Menit</p>
            </div>
        </div>

        <div class="col-span-2 flex items-center gap-3 mt-1">
            <div class="w-8 h-8 rounded-xl {{ $showCountdown ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-500 border-amber-100 dark:border-amber-800/50' : 'bg-slate-50 dark:bg-zinc-800 text-slate-400 border-slate-100 dark:border-zinc-700/50' }} flex items-center justify-center border">
                @if($showCountdown)
                    <svg class="w-4 h-4 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                @endif
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest {{ $showCountdown ? 'text-amber-500/80' : 'text-slate-400' }}">{{ $showCountdown ? 'Sisa Waktu Sesi' : 'Tenggat Waktu' }}</p>
                @if($showCountdown)
                    <p class="font-bold text-amber-600 dark:text-amber-500 text-sm" x-data="{ 
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
                     x-init="setInterval(() => { if(sisa > 0) sisa-- }, 1000)"
                     x-text="formatWaktu(sisa)">Menghitung...</p>
                @elseif($ujian->waktu_selesai)
                    @php
                        $deadlineSecs = $detikHinggaDeadline;
                        $cardId = 'deadline-' . $ujian->id;
                    @endphp
                    <p class="font-bold text-slate-700 dark:text-slate-300 text-sm flex items-center gap-2 flex-wrap">
                        <span>{{ $deadlineText }}</span>
                        <span id="{{ $cardId }}"
                            class="ml-auto text-xs font-black px-3 py-1 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/50 tabular-nums tracking-wide"></span>
                    </p>
                    <script>
                    (function() {
                        var sisa = {{ $deadlineSecs }};
                        var el = document.getElementById('{{ $cardId }}');
                        function fmt(s) {
                            if (s <= 0) { el.style.display='none'; return; }
                            var d = Math.floor(s / 86400);
                            var h = Math.floor((s % 86400) / 3600);
                            var m = Math.floor((s % 3600) / 60);
                            var label = d > 0 ? (d+'h '+h+'j lagi') : (h > 0 ? (h+'j '+m+'m lagi') : (m+'m lagi'));
                            el.textContent = label;
                            el.style.display = '';
                        }
                        fmt(sisa);
                        setInterval(function(){ sisa--; fmt(sisa); }, 1000);
                    })();
                    </script>
                @else
                    <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">{{ $deadlineText }}</p>
                @endif
            </div>
        </div>
    </div>
    
    <a href="{{ route('siswa.ujian.show', $ujian->id) }}"
        class="mt-8 flex items-center justify-center w-full py-4 rounded-2xl text-sm font-black transition-all duration-300
        {{ $sesi && $sesi->status == 'mengerjakan' 
            ? 'bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white shadow-xl shadow-amber-500/20 hover:-translate-y-1' 
            : 'bg-slate-50 hover:bg-emerald-600 dark:bg-zinc-800 dark:hover:bg-emerald-600 text-slate-600 hover:text-white dark:text-slate-300 hover:shadow-xl hover:shadow-emerald-500/20 hover:-translate-y-1' }}">
        @if($sesi && $sesi->status == 'mengerjakan')
            Lanjutkan Mengerjakan
            <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
        @else
            Mulai Ujian Sekarang
            <svg class="w-4 h-4 ml-2 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
        @endif
    </a>
</div>
