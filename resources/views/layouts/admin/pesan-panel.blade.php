{{--
    Panel Pesan: ditampilkan di footer atas area konten utama (hanya di dashboard admin)
    Menampilkan: (1) pendaftaran menunggu verifikasi, (2) pembayaran belum dikonfirmasi
    Variabel: $pendaftaranMenunggu (int), $pembayaranBelumDikonfirmasi (int)
--}}
@if(isset($pendaftaranMenunggu) || isset($pembayaranBelumDikonfirmasi) || isset($tagihanJatuhTempoCount))
@php
    $totalPesan = ($pendaftaranMenunggu ?? 0) + ($pembayaranBelumDikonfirmasi ?? 0) + ($tagihanJatuhTempoCount ?? 0);
@endphp

@if($totalPesan > 0)
<div class="mx-auto max-w-7xl mb-6" id="panel-pesan">
    <div class="rounded-2xl border border-amber-200 dark:border-amber-700/50 bg-amber-50 dark:bg-amber-900/20 overflow-hidden shadow-sm">
        {{-- Header panel --}}
        <div class="flex items-center justify-between px-5 py-3 border-b border-amber-200 dark:border-amber-700/40">
            <div class="flex items-center gap-2">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                </span>
                <span class="text-sm font-bold text-amber-800 dark:text-amber-300">
                    {{ $totalPesan }} Tindakan Perlu Perhatian
                </span>
            </div>
            <button onclick="document.getElementById('panel-pesan').style.display='none'"
                class="text-amber-400 hover:text-amber-600 dark:hover:text-amber-300 transition-colors p-1 rounded-lg">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Item pesan --}}
        <div class="flex flex-col sm:flex-row divide-y sm:divide-y-0 sm:divide-x divide-amber-200 dark:divide-amber-700/40">

            {{-- Item 1: Verifikasi pendaftaran --}}
            @if(($pendaftaranMenunggu ?? 0) > 0)
            <a href="{{ route('admin.pendaftaran.index') }}"
               class="flex items-center gap-4 px-5 py-4 hover:bg-amber-100 dark:hover:bg-amber-900/30 transition-colors flex-1 group">
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-800/50 flex items-center justify-center flex-shrink-0 group-hover:bg-amber-200 dark:group-hover:bg-amber-700/50 transition-colors">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-amber-900 dark:text-amber-200 leading-tight">
                        Verifikasi Pendaftaran Siswa
                    </p>
                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-0.5">
                        <span class="font-bold text-amber-800 dark:text-amber-300">{{ $pendaftaranMenunggu }}</span>
                        pendaftaran baru menunggu verifikasi
                    </p>
                </div>
                <svg class="w-4 h-4 text-amber-400 group-hover:text-amber-600 dark:group-hover:text-amber-300 transition-colors flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @endif

            {{-- Item 2: Konfirmasi pembayaran --}}
            @if(($pembayaranBelumDikonfirmasi ?? 0) > 0)
            <a href="{{ route('admin.pendaftaran.index') }}"
               class="flex items-center gap-4 px-5 py-4 hover:bg-amber-100 dark:hover:bg-amber-900/30 transition-colors flex-1 group">
                <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-800/50 flex items-center justify-center flex-shrink-0 group-hover:bg-rose-200 dark:group-hover:bg-rose-700/50 transition-colors">
                    <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-amber-900 dark:text-amber-200 leading-tight">
                        Konfirmasi Pembayaran
                    </p>
                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-0.5">
                        <span class="font-bold text-rose-700 dark:text-rose-400">{{ $pembayaranBelumDikonfirmasi }}</span>
                        pembayaran belum dikonfirmasi
                    </p>
                </div>
                <svg class="w-4 h-4 text-amber-400 group-hover:text-amber-600 dark:group-hover:text-amber-300 transition-colors flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @endif

            {{-- Item 3: Tagihan Jatuh Tempo --}}
            @if(($tagihanJatuhTempoCount ?? 0) > 0)
            <a href="{{ route('notifikasi.index') }}?tab=tagihan"
               class="flex items-center gap-4 px-5 py-4 hover:bg-amber-100 dark:hover:bg-amber-900/30 transition-colors flex-1 group">
                <div class="w-10 h-10 rounded-xl bg-yellow-100 dark:bg-yellow-800/50 flex items-center justify-center flex-shrink-0 group-hover:bg-yellow-200 dark:group-hover:bg-yellow-700/50 transition-colors">
                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-amber-900 dark:text-amber-200 leading-tight">
                        Tagihan Jatuh Tempo
                    </p>
                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-0.5">
                        <span class="font-bold text-yellow-700 dark:text-yellow-400">{{ $tagihanJatuhTempoCount }}</span>
                        tagihan melewati/mendekati jatuh tempo
                    </p>
                </div>
                <svg class="w-4 h-4 text-amber-400 group-hover:text-amber-600 dark:group-hover:text-amber-300 transition-colors flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @endif

        </div>
    </div>
</div>
@endif
@endif
