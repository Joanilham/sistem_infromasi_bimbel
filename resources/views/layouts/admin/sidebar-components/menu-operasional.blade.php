    {{-- MENU OPERASIONAL --}}
    @if(in_array(strtolower(auth()->user()->level), ['super admin', 'administrator']) || auth()->user()->hasPermission('manage_peserta_didik') || auth()->user()->hasPermission('manage_jadwal') || auth()->user()->hasPermission('manage_absensi') || auth()->user()->hasPermission('manage_rekapitulasi') || auth()->user()->hasPermission('manage_pendaftaran') || auth()->user()->hasPermission('manage_pengumuman'))
    <div class="mb-6">
        <h3 class="px-3 text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-widest mb-2">MENU OPERASIONAL</h3>
        <nav class="space-y-1">

            {{-- Rekapitulasi --}}
            @if(auth()->user()->hasPermission('manage_rekapitulasi'))
            <a href="{{ route('admin.rekapitulasi.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.rekapitulasi.*') ? 'bg-indigo-500 shadow-md shadow-indigo-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('admin.rekapitulasi.*') ? 'bg-white/25' : 'bg-indigo-500 shadow-sm shadow-indigo-500/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('admin.rekapitulasi.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Rekapitulasi Data</span>
            </a>
            @endif

            {{-- Verifikasi Pendaftaran --}}
            @if(auth()->user()->hasPermission('manage_pendaftaran'))
            <a href="{{ route('admin.pendaftaran.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.pendaftaran.*') ? 'bg-emerald-500 shadow-md shadow-emerald-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('admin.pendaftaran.*') ? 'bg-white/25' : 'bg-emerald-500 shadow-sm shadow-emerald-500/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('admin.pendaftaran.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Verifikasi Pendaftaran</span>
            </a>
            @endif

            {{-- Manajemen Jadwal --}}
            @if(auth()->user()->hasPermission('manage_jadwal'))
            <a href="{{ route('admin.jadwal.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.jadwal.*') ? 'bg-teal-500 shadow-md shadow-teal-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('admin.jadwal.*') ? 'bg-white/25' : 'bg-teal-500 shadow-sm shadow-teal-500/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('admin.jadwal.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Manajemen Jadwal</span>
            </a>
            @endif

            {{-- Absensi Dropdown --}}
            @if(auth()->user()->hasPermission('manage_absensi'))
            <div x-data="{ open: {{ request()->routeIs('absensi.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('absensi.*') ? 'bg-sky-500 shadow-md shadow-sky-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group focus:outline-none">
                    <div class="flex items-center">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('absensi.*') ? 'bg-white/25' : 'bg-sky-500 shadow-sm shadow-sky-500/40' }}">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold {{ request()->routeIs('absensi.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Absensi</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('absensi.*') ? 'text-white/80' : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 ml-3 pl-3 border-l-2 border-slate-100 dark:border-zinc-700 space-y-1" style="display: none;">
                    <a href="{{ route('absensi.scan.masuk.page') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('absensi.scan.masuk.page') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white' }} transition-all text-sm font-medium">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('absensi.scan.masuk.page') ? 'bg-emerald-500' : 'bg-emerald-100 dark:bg-emerald-900/50' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('absensi.scan.masuk.page') ? 'text-white' : 'text-emerald-600 dark:text-emerald-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        Absen Masuk
                    </a>
                    <a href="{{ route('absensi.scan.pulang.page') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('absensi.scan.pulang.page') ? 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white' }} transition-all text-sm font-medium">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('absensi.scan.pulang.page') ? 'bg-amber-500' : 'bg-amber-100 dark:bg-amber-900/50' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('absensi.scan.pulang.page') ? 'text-white' : 'text-amber-600 dark:text-amber-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        Absen Pulang
                    </a>
                    <a href="{{ route('absensi.rekap') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('absensi.rekap') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white' }} transition-all text-sm font-medium">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('absensi.rekap') ? 'bg-purple-500' : 'bg-purple-100 dark:bg-purple-900/50' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('absensi.rekap') ? 'text-white' : 'text-purple-600 dark:text-purple-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        Rekap Absensi
                    </a>
                </div>
            </div>
            @endif

            {{-- Berita & Informasi --}}
            @if(in_array(strtolower(auth()->user()->level), ['super admin', 'administrator']) || auth()->user()->hasPermission('manage_pengumuman'))
            <a href="{{ route('admin.pengumuman.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.pengumuman.*') ? 'bg-pink-500 shadow-md shadow-pink-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('admin.pengumuman.*') ? 'bg-white/25' : 'bg-pink-500 shadow-sm shadow-pink-500/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 8M6 12h.01M6 16h.01M12 12h.01M12 16h.01M18 12h.01M18 16h.01" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('admin.pengumuman.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Berita & Informasi</span>
            </a>
            @endif

        </nav>
    </div>
    @endif
