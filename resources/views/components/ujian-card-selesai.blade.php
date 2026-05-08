@props(['ujian', 'sesi'])

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow relative">
    <div class="absolute top-0 left-0 w-1.5 h-full bg-slate-400"></div>
    <div class="p-5 pl-7">
        <div class="flex items-start justify-between gap-3 mb-3">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                        Selesai
                    </span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full border border-slate-200 text-slate-500">
                        {{ ucfirst($ujian->mode) }}
                    </span>
                </div>
                <h3 class="font-bold text-slate-800 text-base leading-snug">{{ $ujian->judul }}</h3>
                @if($ujian->deskripsi)
                    <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $ujian->deskripsi }}</p>
                @endif
            </div>
            @if($sesi && in_array($sesi->status, ['selesai', 'timeout']))
                <div class="flex-shrink-0 w-16 h-16 rounded-2xl flex flex-col items-center justify-center border-2 border-white shadow-sm"
                    style="{{ $sesi->skor >= 75 ? 'background:#D1FAE5; color:#065F46' : ($sesi->skor >= 50 ? 'background:#FEF3C7; color:#92400E' : 'background:#FEE2E2; color:#991B1B') }}">
                    <span class="text-xl font-black leading-none">{{ number_format($sesi->skor ?? 0, 0) }}</span>
                    <span class="text-[10px] font-bold uppercase tracking-wide mt-1 opacity-80">Skor</span>
                </div>
            @else
                <div class="flex-shrink-0 w-16 h-16 rounded-2xl flex flex-col items-center justify-center bg-slate-100 text-slate-400 border-2 border-white shadow-sm">
                    <svg class="w-6 h-6 mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-[10px] font-bold uppercase tracking-wide mt-0.5 opacity-80">Lewat</span>
                </div>
            @endif
        </div>
        
        <div class="flex flex-wrap gap-4 text-sm text-slate-500 mb-5 border-t border-slate-100 pt-3 mt-3">
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span>{{ $ujian->soals_count ?? $ujian->ujianSoals->count() }} Soal</span>
            </div>
            @if($sesi && $sesi->waktu_selesai)
                <div class="flex items-center gap-1.5 ml-auto text-xs">
                    Dikumpulkan: {{ \Carbon\Carbon::parse($sesi->waktu_selesai)->format('d M, H:i') }}
                </div>
            @endif
        </div>
        
        @if($sesi && in_array($sesi->status, ['selesai', 'timeout']))
            <a href="{{ route('siswa.ujian.hasil', $sesi->id) }}"
                class="block w-full text-center py-2.5 rounded-xl text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                Lihat Hasil Lengkap
            </a>
        @else
            <button disabled
                class="block w-full text-center py-2.5 rounded-xl text-sm font-semibold text-slate-400 bg-slate-50 cursor-not-allowed border border-slate-100">
                Waktu Telah Habis
            </button>
        @endif
    </div>
</div>
