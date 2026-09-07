    <!-- MENU DATA PENGGUNA -->
    @if(auth()->user()->hasPermission('manage_pendaftaran') || auth()->user()->hasPermission('manage_peserta_didik') || auth()->user()->hasPermission('manage_guru'))
    <div class="mb-6">
        <h3 class="px-3 text-[10px] font-bold uppercase text-slate-400 dark:text-zinc-500 tracking-widest mb-2">DATA PENGGUNA</h3>
        <nav class="space-y-1">

            {{-- Verifikasi Pendaftaran --}}
            @if(auth()->user()->hasPermission('manage_pendaftaran'))
            <a href="{{ route('admin.pendaftaran.index') }}"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('admin.pendaftaran.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('admin.pendaftaran.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span class="text-sm">Verifikasi Pendaftaran</span>
            </a>
            @endif

            {{-- Peserta Didik Dropdown --}}
            @if(auth()->user()->hasPermission('manage_peserta_didik'))
            <div x-data="{ open: {{ request()->routeIs('peserta-didik.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('peserta-didik.*') ? 'bg-indigo-50/70 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }} focus:outline-none">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('peserta-didik.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                        <span class="text-sm">Peserta Didik</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 ml-4 pl-3.5 border-l border-slate-200 dark:border-zinc-800 space-y-1" style="{{ request()->routeIs('peserta-didik.*') ? '' : 'display: none;' }}">
                    <a href="{{ route('peserta-didik.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('peserta-didik.index') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/80 dark:bg-indigo-950/50 font-bold' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white' }} transition-all text-xs font-medium">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('peserta-didik.index') ? 'bg-indigo-600 dark:bg-indigo-400' : 'bg-slate-300 dark:bg-zinc-600' }}"></span>
                        Peserta Didik Aktif
                    </a>
                    <a href="{{ route('peserta-didik.keluar') }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('peserta-didik.keluar') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/80 dark:bg-indigo-950/50 font-bold' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white' }} transition-all text-xs font-medium">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('peserta-didik.keluar') ? 'bg-indigo-600 dark:bg-indigo-400' : 'bg-slate-300 dark:bg-zinc-600' }}"></span>
                        Peserta Didik Keluar
                    </a>
                    <a href="{{ route('peserta-didik.lulus') }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('peserta-didik.lulus') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/80 dark:bg-indigo-950/50 font-bold' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white' }} transition-all text-xs font-medium">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('peserta-didik.lulus') ? 'bg-indigo-600 dark:bg-indigo-400' : 'bg-slate-300 dark:bg-zinc-600' }}"></span>
                        Peserta Didik Lulus
                    </a>
                </div>
            </div>
            @endif

            {{-- Guru Dropdown --}}
            @if(auth()->user()->hasPermission('manage_guru'))
            <div x-data="{ open: {{ request()->routeIs('manajemen-guru.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('manajemen-guru.*') ? 'bg-indigo-50/70 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }} focus:outline-none">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('manajemen-guru.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="text-sm">Manajemen Guru</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 ml-4 pl-3.5 border-l border-slate-200 dark:border-zinc-800 space-y-1" style="{{ request()->routeIs('manajemen-guru.*') ? '' : 'display: none;' }}">
                    <a href="{{ route('manajemen-guru.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('manajemen-guru.index') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/80 dark:bg-indigo-950/50 font-bold' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white' }} transition-all text-xs font-medium">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('manajemen-guru.index') ? 'bg-indigo-600 dark:bg-indigo-400' : 'bg-slate-300 dark:bg-zinc-600' }}"></span>
                        Guru Aktif
                    </a>
                    <a href="{{ route('manajemen-guru.keluar') }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('manajemen-guru.keluar') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/80 dark:bg-indigo-950/50 font-bold' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white' }} transition-all text-xs font-medium">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('manajemen-guru.keluar') ? 'bg-indigo-600 dark:bg-indigo-400' : 'bg-slate-300 dark:bg-zinc-600' }}"></span>
                        Guru Keluar
                    </a>
                </div>
            </div>
            @endif

        </nav>
    </div>
    @endif
