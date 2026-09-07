    {{-- ─── Stat Cards ─── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        {{-- Card 1: Peserta Didik Aktif --}}
        <a href="{{ route('peserta-didik.index') }}" class="group relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 sm:p-6 shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 overflow-hidden block">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Peserta Aktif</span>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-1.5">{{ $totalPesertaAktif }}</p>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                <span>{{ ($listPesertaBaru ?? collect())->count() }} siswa baru minggu ini</span>
            </p>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-indigo-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
        </a>

        {{-- Card 2: Tenaga Pengajar --}}
        <a href="{{ route('manajemen-guru.index') }}" class="group relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 sm:p-6 shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 overflow-hidden block">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tenaga Pengajar</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-1.5">{{ $totalTenagaPengajar }}</p>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full {{ $totalTenagaPengajar > 0 ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                <span>{{ $totalTenagaPengajar > 0 ? 'Semua aktif bertugas' : 'Belum ada data pengajar' }}</span>
            </p>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-amber-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
        </a>

        {{-- Card 3: Paket Bimbingan --}}
        <a href="{{ route('paket-bimbingan.index') }}" class="group relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 sm:p-6 shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 overflow-hidden block">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Paket Bimbingan</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-1.5">{{ $totalPaketAktif }}</p>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>Program aktif tersinkron</span>
            </p>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-emerald-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
        </a>

        {{-- Card 4: Pendaftaran Baru --}}
        <a href="{{ route('admin.pendaftaran.index') }}" class="group relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 sm:p-6 shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 overflow-hidden block">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pendaftaran Baru</span>
                <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-1.5">{{ $pendaftaranMenunggu ?? 0 }}</p>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full {{ ($pendaftaranMenunggu ?? 0) > 0 ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
                <span>{{ ($pendaftaranMenunggu ?? 0) > 0 ? 'Menunggu verifikasi' : 'Semua terverifikasi' }}</span>
            </p>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-sky-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
        </a>

    </div>
