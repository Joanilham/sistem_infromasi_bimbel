{{--
    Panel Pesan: ditampilkan di footer atas area konten utama (hanya di dashboard admin)
    Menampilkan: (1) pendaftaran menunggu verifikasi, (2) pembayaran belum dikonfirmasi
    Variabel: $pendaftaranMenunggu (int), $pembayaranBelumDikonfirmasi (int)
--}}
@if(isset($pendaftaranMenunggu) || isset($pembayaranBelumDikonfirmasi) || isset($tagihanJatuhTempoCount))
@php
    $totalPesan = ($pendaftaranMenunggu ?? 0) + ($tagihanJatuhTempoCount ?? 0);
@endphp

@if($totalPesan > 0)
<div class="mx-auto max-w-7xl mb-8" id="panel-pesan">
    {{-- Header panel --}}
    <div class="flex items-center justify-between px-2 mb-4">
        <div class="flex items-center gap-3">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500 shadow-sm shadow-amber-500/50"></span>
            </span>
            <span class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wide">
                {{ $totalPesan }} Tindakan Perlu Perhatian
            </span>
        </div>
        <button onclick="document.getElementById('panel-pesan').style.display='none'"
            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full" title="Tutup Panel">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Item pesan dalam Grid Card --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Item 1: Verifikasi pendaftaran --}}
        @if(($pendaftaranMenunggu ?? 0) > 0)
        <a href="{{ route('admin.pendaftaran.index') }}"
           class="group relative flex items-center gap-4 p-5 rounded-2xl border border-amber-200 dark:border-amber-800/50 bg-gradient-to-br from-amber-50 to-amber-100/50 dark:from-amber-900/20 dark:to-amber-900/10 hover:from-amber-100 hover:to-amber-200/50 dark:hover:from-amber-900/40 dark:hover:to-amber-900/20 transition-all duration-300 shadow-sm hover:shadow-md">
            <div class="w-12 h-12 rounded-full bg-amber-200 dark:bg-amber-800/60 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300 shadow-inner">
                <svg class="w-6 h-6 text-amber-700 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-amber-900 dark:text-amber-100 truncate">
                    Pendaftaran Baru
                </p>
                <p class="text-xs text-amber-700 dark:text-amber-400/80 mt-1 leading-snug line-clamp-2">
                    <span class="font-extrabold text-amber-900 dark:text-amber-300">{{ $pendaftaranMenunggu }}</span>
                    pendaftar menunggu verifikasi
                </p>
            </div>
            <svg class="w-5 h-5 text-amber-400 group-hover:text-amber-600 dark:group-hover:text-amber-300 transition-all transform group-hover:translate-x-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endif



        {{-- Item 3: Tagihan Jatuh Tempo --}}
        @if(($tagihanJatuhTempoCount ?? 0) > 0)
        <a href="{{ route('notifikasi.index') }}?tab=tagihan"
           class="group relative flex items-center gap-4 p-5 rounded-2xl border border-yellow-200 dark:border-yellow-800/50 bg-gradient-to-br from-yellow-50 to-yellow-100/50 dark:from-yellow-900/20 dark:to-yellow-900/10 hover:from-yellow-100 hover:to-yellow-200/50 dark:hover:from-yellow-900/40 dark:hover:to-yellow-900/20 transition-all duration-300 shadow-sm hover:shadow-md">
            <div class="w-12 h-12 rounded-full bg-yellow-200 dark:bg-yellow-800/60 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300 shadow-inner">
                <svg class="w-6 h-6 text-yellow-700 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-yellow-900 dark:text-yellow-100 truncate">
                    Tagihan Jatuh Tempo
                </p>
                <p class="text-xs text-yellow-700 dark:text-yellow-400/80 mt-1 leading-snug line-clamp-2">
                    <span class="font-extrabold text-yellow-900 dark:text-yellow-300">{{ $tagihanJatuhTempoCount }}</span>
                    tagihan melewati/mendekati jatuh tempo
                </p>
            </div>
            <svg class="w-5 h-5 text-yellow-400 group-hover:text-yellow-600 dark:group-hover:text-yellow-300 transition-all transform group-hover:translate-x-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endif

    </div>
</div>
@endif
@endif
