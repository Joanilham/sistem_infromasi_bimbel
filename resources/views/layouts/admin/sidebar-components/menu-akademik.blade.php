    <!-- MENU AKADEMIK -->
    @if(auth()->user()->hasPermission('manage_paket_bimbingan') || auth()->user()->hasPermission('manage_peserta_didik') || auth()->user()->hasPermission('manage_guru'))
    <div class="mb-6">
        <h3 class="px-3 text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-widest mb-2">MENU AKADEMIK</h3>
        <nav class="space-y-1">

            @if(auth()->user()->hasPermission('manage_paket_bimbingan'))

            <a href="{{ route('paket-bimbingan.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('paket-bimbingan.*') ? 'bg-orange-500 shadow-md shadow-orange-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('paket-bimbingan.*') ? 'bg-white/25' : 'bg-orange-500 shadow-sm shadow-orange-500/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('paket-bimbingan.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Paket Bimbingan</span>
            </a>
            @endif

            @if(auth()->user()->hasPermission('manage_peserta_didik'))
            <a href="{{ route('kelompok-belajar.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('kelompok-belajar.*') ? 'bg-purple-500 shadow-md shadow-purple-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('kelompok-belajar.*') ? 'bg-white/25' : 'bg-purple-500 shadow-sm shadow-purple-500/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('kelompok-belajar.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Kelompok Belajar</span>
            </a>
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
                        class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('manajemen-guru.index') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white' }} transition-all text-sm font-medium">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('manajemen-guru.index') ? 'bg-emerald-500' : 'bg-emerald-100 dark:bg-emerald-900/50' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('manajemen-guru.index') ? 'text-white' : 'text-emerald-700 dark:text-emerald-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                </div>
            </div>
            @endif

        </nav>
    </div>
    @endif
