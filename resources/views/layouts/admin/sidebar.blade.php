<!-- Sidebar Header -->
<div class="flex items-center justify-center h-20 border-b border-white/10 px-4 bg-slate-950/50">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
        </div>
        <span class="text-xl font-extrabold text-white tracking-tight">Genius<span class="text-indigo-400">Edu</span></span>
    </div>
</div>

<!-- Sidebar Content -->
<div class="overflow-y-auto flex-1 p-4 custom-scrollbar">
    <nav class="space-y-1.5 mb-8">
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} group">
            <svg class="mr-3 h-5 w-5 transition-colors {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-slate-200' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
        </a>
    </nav>

    <!-- MENU AKADEMIK -->
    <div class="mb-6">
        <h3 class="px-4 text-xs font-bold uppercase text-slate-500 tracking-wider mb-3">MENU AKADEMIK</h3>
        <nav class="space-y-1.5">
            <a href="{{ route('paket-bimbingan.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('paket-bimbingan.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} group">
                <svg class="mr-3 h-5 w-5 transition-colors {{ request()->routeIs('paket-bimbingan.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
                Paket Bimbingan
            </a>
            <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-slate-300 hover:bg-white/5 hover:text-white group">
                <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-200 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354l-8.646 6.484a.5.5 0 00.316.892h16.66a.5.5 0 00.316-.892L12 4.354z" />
                </svg>
                Kelompok Belajar
            </a>

            <div x-data="{ open: {{ request()->routeIs('peserta-didik.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('peserta-didik.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} group focus:outline-none">
                    <div class="flex items-center">
                        <svg class="mr-3 h-5 w-5 transition-colors {{ request()->routeIs('peserta-didik.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Peserta Didik
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('peserta-didik.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="mt-2 space-y-1 pl-4 pr-4" style="display: none;">
                    <a href="{{ route('peserta-didik.index') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl {{ request()->routeIs('peserta-didik.*') ? 'bg-slate-800 text-white border border-slate-700' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-all">
                        <svg class="mr-3 h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Peserta Didik Aktif
                    </a>
                    <a href="#" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition-all">
                        <svg class="mr-3 h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Peserta Didik Keluar
                    </a>
                </div>
            </div>

            <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-slate-300 hover:bg-white/5 hover:text-white group">
                <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-200 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Tenaga Pengajar
            </a>
            <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-slate-300 hover:bg-white/5 hover:text-white group">
                <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-200 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253" />
                </svg>
                Mata Pelajaran
            </a>
            <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-slate-300 hover:bg-white/5 hover:text-white group">
                <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-200 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Jadwal
            </a>
            <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-slate-300 hover:bg-white/5 hover:text-white group">
                <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-200 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Jurnal Mengajar
            </a>
        </nav>
    </div>

    <!-- MENU ADMINISTRASI -->
    <div class="mb-6">
        <h3 class="px-4 text-xs font-bold uppercase text-slate-500 tracking-wider mb-3">MENU ADMINISTRASI</h3>
        <nav class="space-y-1.5">
            <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-slate-300 hover:bg-white/5 hover:text-white group">
                <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-200 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                Pembayaran
            </a>
            <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-slate-300 hover:bg-white/5 hover:text-white group">
                <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-200 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Pemasukan
            </a>
            <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-slate-300 hover:bg-white/5 hover:text-white group">
                <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-200 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
                Pengeluaran
            </a>
            <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-slate-300 hover:bg-white/5 hover:text-white group">
                <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-200 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2h-2z" />
                </svg>
                Tagihan
            </a>
        </nav>
    </div>

    <!-- MENU ABSENSI -->
    <div class="mb-6">
        <h3 class="px-4 text-xs font-bold uppercase text-slate-500 tracking-wider mb-3">MENU ABSENSI</h3>
        <nav class="space-y-1.5">
            <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-slate-300 hover:bg-white/5 hover:text-white group">
                <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-200 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
                Data QR Code
            </a>
            <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-slate-300 hover:bg-white/5 hover:text-white group">
                <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-200 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Absensi
            </a>
            <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-slate-300 hover:bg-white/5 hover:text-white group">
                <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-200 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Laporan
            </a>
        </nav>
    </div>

    <!-- MENU LAPORAN -->
    <div class="mb-6">
        <h3 class="px-4 text-xs font-bold uppercase text-slate-500 tracking-wider mb-3">MENU LAPORAN</h3>
        <nav class="space-y-1.5">
            <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-slate-300 hover:bg-white/5 hover:text-white group">
                <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-200 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                </svg>
                Laporan
            </a>
        </nav>
    </div>

    <!-- MENU PENGATURAN -->
    @if(auth()->check() && auth()->user()->level === 'administrator')
    <div class="mb-6">
        <h3 class="px-4 text-xs font-bold uppercase text-slate-500 tracking-wider mb-3">MENU PENGATURAN</h3>
        <nav class="space-y-1.5">
            <a href="{{ route('kantor.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('kantor.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} group">
                <svg class="mr-3 h-5 w-5 transition-colors {{ request()->routeIs('kantor.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Pengaturan Kantor
            </a>

            <a href="{{ route('periode.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('periode.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} group">
                <svg class="mr-3 h-5 w-5 transition-colors {{ request()->routeIs('periode.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Pengaturan Periode
            </a>

            <a href="{{ route('master.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('master.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} group">
                <svg class="mr-3 h-5 w-5 transition-colors {{ request()->routeIs('master.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                </svg>
                Pengaturan Master
            </a>

            <a href="{{ route('pengguna.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('pengguna.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} group">
                <svg class="mr-3 h-5 w-5 transition-colors {{ request()->routeIs('pengguna.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Pengaturan Pengguna
            </a>
        </nav>
    </div>
    @endif
</div>