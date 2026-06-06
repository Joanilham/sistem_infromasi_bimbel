    {{-- ─── Grafik Statistik ─── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        {{-- Chart: Peserta Didik --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-800 dark:text-white tracking-tight">Statistik Peserta Didik</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Peserta masuk dan keluar periode {{ $activePeriodeYear }}</p>
                    </div>
                    <div class="px-3.5 py-1 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest border border-indigo-100/50 dark:border-indigo-900/50">
                        Siswa
                    </div>
                </div>
                
                {{-- Summary --}}
                <div class="flex items-center gap-6 mb-2 px-2">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Masuk</span>
                        <span class="text-2xl font-black text-indigo-600 dark:text-indigo-400">{{ array_sum($chartPesertaMasuk) }} <span class="text-xs font-semibold text-slate-500">Siswa</span></span>
                    </div>
                    <div class="w-px h-8 bg-slate-200 dark:bg-slate-700"></div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Keluar</span>
                        <span class="text-2xl font-black text-rose-500 dark:text-rose-400">{{ array_sum($chartPesertaKeluar) }} <span class="text-xs font-semibold text-slate-500">Siswa</span></span>
                    </div>
                </div>

                <div id="chart-peserta-didik" class="w-full min-h-[320px]"></div>
            </div>
        </div>

        {{-- Chart: Keuangan --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-800 dark:text-white tracking-tight">Statistik Keuangan</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Arus kas masuk dan keluar periode {{ $activePeriodeYear }}</p>
                    </div>
                    <div class="px-3.5 py-1 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest border border-emerald-100/50 dark:border-emerald-900/50">
                        Keuangan
                    </div>
                </div>

                {{-- Summary --}}
                <div class="flex flex-wrap items-center gap-6 mb-2 px-2">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Pemasukan</span>
                        <span class="text-2xl font-black text-emerald-500 dark:text-emerald-400">Rp {{ number_format(array_sum($chartUangMasuk), 0, ',', '.') }}</span>
                    </div>
                    <div class="w-px h-8 bg-slate-200 dark:bg-slate-700 hidden sm:block"></div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Pengeluaran</span>
                        <span class="text-2xl font-black text-rose-500 dark:text-rose-400">Rp {{ number_format(array_sum($chartUangKeluar), 0, ',', '.') }}</span>
                    </div>
                </div>

                <div id="chart-keuangan" class="w-full min-h-[320px]"></div>
            </div>
        </div>
    </div>

