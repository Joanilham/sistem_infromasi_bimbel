{{--
    Panel Pesan: ditampilkan di footer atas area konten utama (hanya di dashboard admin)
    Menampilkan: (1) pendaftaran menunggu verifikasi, (2) pembayaran belum dikonfirmasi, (3) tagihan jatuh tempo
--}}
@php
    $itemsCount = 0;
    if (($pendaftaranMenunggu ?? 0) > 0) $itemsCount++;
    if (($pembayaranBelumDikonfirmasi ?? 0) > 0) $itemsCount++;
    if (($tagihanJatuhTempoCount ?? 0) > 0) $itemsCount++;
    $totalPesan = ($pendaftaranMenunggu ?? 0) + ($pembayaranBelumDikonfirmasi ?? 0) + ($tagihanJatuhTempoCount ?? 0);
@endphp

@if($itemsCount > 0)
<div class="w-full mb-8" id="panel-pesan">
    {{-- Header panel --}}
    <div class="flex items-center justify-between px-1 mb-3.5">
        <div class="flex items-center gap-2.5">
            <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
            </span>
            <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                {{ $totalPesan }} Tindakan Perlu Perhatian
            </span>
        </div>
        <button onclick="document.getElementById('panel-pesan').style.display='none'"
            class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors p-1 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg" title="Tutup Panel">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Item pesan dalam Grid Card --}}
    <div class="grid grid-cols-1 {{ $itemsCount >= 3 ? 'md:grid-cols-3' : ($itemsCount === 2 ? 'md:grid-cols-2' : '') }} gap-4">

        {{-- Item 1: Verifikasi pendaftaran --}}
        @if(($pendaftaranMenunggu ?? 0) > 0)
        <a href="{{ route('admin.pendaftaran.index') }}"
           class="group relative flex items-center gap-4 p-4.5 rounded-2xl border border-amber-200/80 dark:border-amber-800/40 bg-amber-50/70 hover:bg-amber-100/80 dark:bg-amber-950/20 dark:hover:bg-amber-900/30 transition-all duration-200 shadow-xs hover:shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-amber-100 dark:bg-amber-800/60 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                <svg class="w-5 h-5 text-amber-700 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-amber-950 dark:text-amber-100 truncate">Pendaftaran Baru</p>
                <p class="text-xs text-amber-700 dark:text-amber-300/90 mt-0.5 leading-snug">
                    <span class="font-black text-amber-900 dark:text-amber-200">{{ $pendaftaranMenunggu }}</span> pendaftar menunggu verifikasi
                </p>
            </div>
            <svg class="w-4.5 h-4.5 text-amber-500 group-hover:text-amber-700 transition-transform group-hover:translate-x-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endif

        {{-- Item 2: Konfirmasi Pembayaran --}}
        @if(($pembayaranBelumDikonfirmasi ?? 0) > 0)
        <a href="{{ route('admin.pembayaran.index') }}"
           class="group relative flex items-center gap-4 p-4.5 rounded-2xl border border-orange-200/80 dark:border-orange-800/40 bg-orange-50/70 hover:bg-orange-100/80 dark:bg-orange-950/20 dark:hover:bg-orange-900/30 transition-all duration-200 shadow-xs hover:shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-orange-100 dark:bg-orange-800/60 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                <svg class="w-5 h-5 text-orange-700 dark:text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-orange-950 dark:text-orange-100 truncate">Konfirmasi Pembayaran</p>
                <p class="text-xs text-orange-700 dark:text-orange-300/90 mt-0.5 leading-snug">
                    <span class="font-black text-orange-900 dark:text-orange-200">{{ $pembayaranBelumDikonfirmasi }}</span> bukti pembayaran baru
                </p>
            </div>
            <svg class="w-4.5 h-4.5 text-orange-500 group-hover:text-orange-700 transition-transform group-hover:translate-x-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endif

        {{-- Item 3: Tagihan Jatuh Tempo --}}
        @if(($tagihanJatuhTempoCount ?? 0) > 0)
        <a href="{{ route('notifikasi.index') }}?tab=tagihan"
           class="group relative flex items-center gap-4 p-4.5 rounded-2xl border border-yellow-200/80 dark:border-yellow-800/40 bg-yellow-50/70 hover:bg-yellow-100/80 dark:bg-yellow-950/20 dark:hover:bg-yellow-900/30 transition-all duration-200 shadow-xs hover:shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-yellow-100 dark:bg-yellow-800/60 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                <svg class="w-5 h-5 text-yellow-700 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-yellow-950 dark:text-yellow-100 truncate">Tagihan Jatuh Tempo</p>
                <p class="text-xs text-yellow-700 dark:text-yellow-300/90 mt-0.5 leading-snug">
                    <span class="font-black text-yellow-900 dark:text-yellow-200">{{ $tagihanJatuhTempoCount }}</span> tagihan melewati atau mendekati jatuh tempo
                </p>
            </div>
            <svg class="w-4.5 h-4.5 text-yellow-500 group-hover:text-yellow-700 transition-transform group-hover:translate-x-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endif

    </div>
</div>
@endif
