    <!-- MENU DATA PENGGUNA -->
    @if(auth()->user()->hasPermission('manage_pendaftaran') || auth()->user()->hasPermission('manage_peserta_didik') || auth()->user()->hasPermission('manage_guru'))
    <div class="mb-6">
        <h3 class="px-3 text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-widest mb-2">DATA PENGGUNA</h3>
        <nav class="space-y-1">

            {{-- Verifikasi Pendaftaran --}}
            @if(auth()->user()->hasPermission('manage_pendaftaran'))
            <a href="{{ route('admin.pendaftaran.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.pendaftaran.*') ? 'bg-cyan-500 shadow-md shadow-cyan-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('admin.pendaftaran.*') ? 'bg-white/25' : 'bg-cyan-500 shadow-sm shadow-cyan-500/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('admin.pendaftaran.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Verifikasi Pendaftaran</span>
            </a>
            @endif

            {{-- Peserta Didik Dropdown --}}
            @if(auth()->user()->hasPermission('manage_peserta_didik'))
            <div x-data="{ open: {{ request()->routeIs('peserta-didik.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('peserta-didik.*') ? 'bg-emerald-500 shadow-md shadow-emerald-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group focus:outline-none">
                    <div class="flex items-center">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('peserta-didik.*') ? 'bg-white/25' : 'bg-emerald-500 shadow-sm shadow-emerald-500/40' }}">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold {{ request()->routeIs('peserta-didik.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Peserta Didik</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('peserta-didik.*') ? 'text-white/80' : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 ml-3 pl-3 border-l-2 border-slate-100 dark:border-zinc-700 space-y-1" style="display: none;">
                    <a href="{{ route('peserta-didik.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('peserta-didik.index') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white' }} transition-all text-sm font-medium">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('peserta-didik.index') ? 'bg-emerald-500' : 'bg-emerald-100 dark:bg-emerald-900/50' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('peserta-didik.index') ? 'text-white' : 'text-emerald-600 dark:text-emerald-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        Peserta Didik Aktif
                    </a>
                    <a href="{{ route('peserta-didik.keluar') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('peserta-didik.keluar') ? 'bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white' }} transition-all text-sm font-medium">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('peserta-didik.keluar') ? 'bg-rose-500' : 'bg-rose-100 dark:bg-rose-900/50' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('peserta-didik.keluar') ? 'text-white' : 'text-rose-600 dark:text-rose-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        Peserta Didik Keluar
                    </a>
                    <a href="{{ route('peserta-didik.lulus') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('peserta-didik.lulus') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white' }} transition-all text-sm font-medium">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('peserta-didik.lulus') ? 'bg-indigo-500' : 'bg-indigo-100 dark:bg-indigo-900/50' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('peserta-didik.lulus') ? 'text-white' : 'text-indigo-600 dark:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                        </div>
                        Peserta Didik Lulus
                    </a>
                </div>
            </div>
            @endif

            {{-- Guru Dropdown --}}
            @if(auth()->user()->hasPermission('manage_guru'))
            <div x-data="{ open: {{ request()->routeIs('manajemen-guru.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('manajemen-guru.*') ? 'bg-amber-500 shadow-md shadow-amber-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group focus:outline-none">
                    <div class="flex items-center">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('manajemen-guru.*') ? 'bg-white/25' : 'bg-amber-500 shadow-sm shadow-amber-500/40' }}">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold {{ request()->routeIs('manajemen-guru.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Manajemen Guru</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('manajemen-guru.*') ? 'text-white/80' : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 ml-3 pl-3 border-l-2 border-slate-100 dark:border-zinc-700 space-y-1" style="display: none;">
                    <a href="{{ route('manajemen-guru.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('manajemen-guru.index') ? 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white' }} transition-all text-sm font-medium">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('manajemen-guru.index') ? 'bg-amber-500' : 'bg-amber-100 dark:bg-amber-900/50' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('manajemen-guru.index') ? 'text-white' : 'text-amber-700 dark:text-amber-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        Guru Aktif
                    </a>
                    <a href="{{ route('manajemen-guru.keluar') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('manajemen-guru.keluar') ? 'bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white' }} transition-all text-sm font-medium">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('manajemen-guru.keluar') ? 'bg-rose-500' : 'bg-rose-100 dark:bg-rose-900/50' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('manajemen-guru.keluar') ? 'text-white' : 'text-rose-700 dark:text-rose-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        Guru Keluar
                    </a>
                </div>
            </div>
            @endif

        </nav>
    </div>
    @endif
