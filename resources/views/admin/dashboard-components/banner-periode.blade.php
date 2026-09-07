    {{-- ─── Banner Pengingat Periode Hampir Habis ─── --}}
    @php
        $activePeriode = \App\Models\MasterData\Periode::where('id', session('periode_id'))->first();
        $periodeWarning = false;
        $bulanSisa = null;
        if ($activePeriode) {
            // Parse tahun akhir dari format "2024/2025" atau "2025"
            preg_match('/(\d{4})(?:\/(\d{4}))?/', $activePeriode->tahun_periode, $periodeMatch);
            $tahunAkhir = isset($periodeMatch[2]) ? (int)$periodeMatch[2] : (int)($periodeMatch[1] ?? 0);
            if ($tahunAkhir > 0) {
                // Periode dianggap berakhir di bulan Juni tahun akhir (akhir tahun ajaran)
                $periodeEnd = \Carbon\Carbon::create($tahunAkhir, 6, 30);
                $now = \Carbon\Carbon::now();
                $bulanSisa = (int) $now->diffInMonths($periodeEnd, false);
                // Tampilkan warning jika sisa <= 3 bulan dan belum lewat
                $periodeWarning = $bulanSisa >= 0 && $bulanSisa <= 3;
            }
        }
        // Cek juga apakah periode berikutnya sudah dibuat
        $nextPeriodeExists = $activePeriode
            ? \App\Models\MasterData\Periode::where('id', '!=', $activePeriode->id)
                ->where('created_at', '>', $activePeriode->created_at)
                ->exists()
            : false;
    @endphp

    @if($periodeWarning && !$nextPeriodeExists)
    <div x-data="{ show: true }" x-show="show"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="mb-6">
        <div class="relative flex flex-col sm:flex-row items-start sm:items-center gap-4 px-5 py-4 pr-12 rounded-2xl border overflow-hidden
            {{ $bulanSisa == 0 ? 'bg-red-50 dark:bg-red-950/40 border-red-200 dark:border-red-800' : 'bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800' }}">
            
            {{-- Animated left accent bar --}}
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-2xl
                {{ $bulanSisa == 0 ? 'bg-red-500' : 'bg-amber-500' }}"></div>

            <div class="flex items-start gap-4 flex-1 min-w-0">
                {{-- Icon --}}
                <div class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl
                    {{ $bulanSisa == 0 ? 'bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400' : 'bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400' }}">
                    @if($bulanSisa == 0)
                    <svg class="w-5 h-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    @else
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold {{ $bulanSisa == 0 ? 'text-red-800 dark:text-red-300' : 'text-amber-800 dark:text-amber-300' }}">
                        @if($bulanSisa == 0)
                            Periode <strong>{{ $activePeriode->tahun_periode }}</strong> akan berakhir bulan ini!
                        @else
                            Periode <strong>{{ $activePeriode->tahun_periode }}</strong> akan berakhir dalam <strong>{{ $bulanSisa }} bulan</strong> lagi.
                        @endif
                    </p>
                    <p class="text-xs mt-0.5 {{ $bulanSisa == 0 ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400' }}">
                        Segera buat periode tahun ajaran baru agar data siswa, guru, dan laporan tetap terorganisir.
                    </p>
                </div>
            </div>

            {{-- Action Button --}}
            <div class="shrink-0 mt-2 sm:mt-0 w-full sm:w-auto">
                <a href="{{ route('periode.index') }}" aria-label="Buat Periode Baru"
                   class="flex sm:inline-flex justify-center items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold transition-all w-full sm:w-auto
                    {{ $bulanSisa == 0
                        ? 'bg-red-600 hover:bg-red-700 text-white shadow-sm shadow-red-200 dark:shadow-red-900'
                        : 'bg-amber-500 hover:bg-amber-600 text-white shadow-sm shadow-amber-200 dark:shadow-amber-900' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Periode Baru
                </a>
            </div>

            {{-- Close Button --}}
            <button @click="show = false"
                    class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors"
                    title="Tutup pengingat">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
    @endif

