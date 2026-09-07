    <!-- MENU AKADEMIK & OPERASIONAL -->
    @if(in_array(strtolower(auth()->user()->level), ['super admin', 'administrator']) || auth()->user()->hasPermission('manage_paket_bimbingan') || auth()->user()->hasPermission('manage_jadwal') || auth()->user()->hasPermission('manage_absensi') || auth()->user()->hasPermission('manage_pengumuman'))
    <div class="mb-6">
        <h3 class="px-3 text-[10px] font-bold uppercase text-slate-400 dark:text-zinc-500 tracking-widest mb-2">AKADEMIK & OPERASIONAL</h3>
        <nav class="space-y-1">

            @if(auth()->user()->hasPermission('manage_paket_bimbingan'))
            <a href="{{ route('paket-bimbingan.index') }}"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('paket-bimbingan.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('paket-bimbingan.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
                <span class="text-sm">Paket Bimbingan</span>
            </a>
            @endif

            @if(auth()->user()->hasPermission('manage_paket_bimbingan'))
            <a href="{{ route('kelompok-belajar.index') }}"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('kelompok-belajar.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('kelompok-belajar.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="text-sm">Kelompok Belajar</span>
            </a>
            @endif

            {{-- Manajemen Jadwal --}}
            @if(auth()->user()->hasPermission('manage_jadwal'))
            <a href="{{ route('admin.jadwal.index') }}"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('admin.jadwal.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('admin.jadwal.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-sm">Manajemen Jadwal</span>
            </a>
            @endif

            {{-- Absensi Dropdown --}}
            @if(auth()->user()->hasPermission('manage_absensi'))
            <div x-data="{ open: {{ request()->routeIs('absensi.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('absensi.*') ? 'bg-indigo-50/70 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }} focus:outline-none">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('absensi.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm">Absensi</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 ml-4 pl-3.5 border-l border-slate-200 dark:border-zinc-800 space-y-1" style="{{ request()->routeIs('absensi.*') ? '' : 'display: none;' }}">
                    <a href="{{ route('absensi.scan.masuk.page') }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('absensi.scan.masuk.page') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/80 dark:bg-indigo-950/50 font-bold' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white' }} transition-all text-xs font-medium">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('absensi.scan.masuk.page') ? 'bg-indigo-600 dark:bg-indigo-400' : 'bg-slate-300 dark:bg-zinc-600' }}"></span>
                        Absen Masuk
                    </a>
                    <a href="{{ route('absensi.scan.pulang.page') }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('absensi.scan.pulang.page') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/80 dark:bg-indigo-950/50 font-bold' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white' }} transition-all text-xs font-medium">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('absensi.scan.pulang.page') ? 'bg-indigo-600 dark:bg-indigo-400' : 'bg-slate-300 dark:bg-zinc-600' }}"></span>
                        Absen Pulang
                    </a>
                    <a href="{{ route('absensi.rekap') }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('absensi.rekap') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/80 dark:bg-indigo-950/50 font-bold' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white' }} transition-all text-xs font-medium">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('absensi.rekap') ? 'bg-indigo-600 dark:bg-indigo-400' : 'bg-slate-300 dark:bg-zinc-600' }}"></span>
                        Rekap Absensi
                    </a>
                </div>
            </div>
            @endif

            {{-- Berita & Informasi --}}
            @if(in_array(strtolower(auth()->user()->level), ['super admin', 'administrator']) || auth()->user()->hasPermission('manage_pengumuman'))
            <a href="{{ route('admin.pengumuman.index') }}"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('admin.pengumuman.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('admin.pengumuman.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 8M6 12h.01M6 16h.01M12 12h.01M12 16h.01M18 12h.01M18 16h.01" />
                </svg>
                <span class="text-sm">Berita & Informasi</span>
            </a>
            @endif

        </nav>
    </div>
    @endif
