    {{-- MENU KEUANGAN --}}
    @if(in_array(strtolower(auth()->user()->level), ['super admin', 'administrator']) || auth()->user()->hasPermission('manage_keuangan') || auth()->user()->hasPermission('manage_pembayaran_siswa') || auth()->user()->hasPermission('manage_pemasukan') || auth()->user()->hasPermission('manage_pengeluaran') || auth()->user()->hasPermission('manage_tagihan') || auth()->user()->hasPermission('manage_bank'))
    <div class="mb-6">
        <h3 class="px-3 text-[10px] font-bold uppercase text-slate-400 dark:text-zinc-500 tracking-widest mb-2">MENU KEUANGAN</h3>
        <nav class="space-y-1">

            {{-- Pembayaran --}}
            @if(auth()->user()->hasPermission('manage_keuangan') || auth()->user()->hasPermission('manage_pembayaran_siswa'))
            <a href="{{ route('keuangan.pembayaran.index') }}"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('keuangan.pembayaran.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('keuangan.pembayaran.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="text-sm">Pembayaran Siswa</span>
            </a>
            @endif

            {{-- Pemasukan Dropdown --}}
            @if(auth()->user()->hasPermission('manage_keuangan') || auth()->user()->hasPermission('manage_pemasukan'))
            <div x-data="{ open: {{ request()->routeIs('keuangan.pemasukan.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('keuangan.pemasukan.*') ? 'bg-indigo-50/70 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }} focus:outline-none">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('keuangan.pemasukan.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="text-sm">Pemasukan</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 ml-4 pl-3.5 border-l border-slate-200 dark:border-zinc-800 space-y-1" style="{{ request()->routeIs('keuangan.pemasukan.*') ? '' : 'display: none;' }}">
                    <a href="{{ route('keuangan.pemasukan.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('keuangan.pemasukan.index') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/80 dark:bg-indigo-950/50 font-bold' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white' }} transition-all text-xs font-medium">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('keuangan.pemasukan.index') ? 'bg-indigo-600 dark:bg-indigo-400' : 'bg-slate-300 dark:bg-zinc-600' }}"></span>
                        Daftar Pemasukan
                    </a>
                    <a href="{{ route('keuangan.pemasukan.kategori.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('keuangan.pemasukan.kategori.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/80 dark:bg-indigo-950/50 font-bold' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white' }} transition-all text-xs font-medium">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('keuangan.pemasukan.kategori.*') ? 'bg-indigo-600 dark:bg-indigo-400' : 'bg-slate-300 dark:bg-zinc-600' }}"></span>
                        Kategori Pemasukan
                    </a>
                </div>
            </div>
            @endif

            {{-- Pengeluaran Dropdown --}}
            @if(auth()->user()->hasPermission('manage_keuangan') || auth()->user()->hasPermission('manage_pengeluaran'))
            <div x-data="{ open: {{ request()->routeIs('keuangan.pengeluaran.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('keuangan.pengeluaran.*') ? 'bg-indigo-50/70 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }} focus:outline-none">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('keuangan.pengeluaran.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                        <span class="text-sm">Pengeluaran</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 ml-4 pl-3.5 border-l border-slate-200 dark:border-zinc-800 space-y-1" style="{{ request()->routeIs('keuangan.pengeluaran.*') ? '' : 'display: none;' }}">
                    <a href="{{ route('keuangan.pengeluaran.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('keuangan.pengeluaran.index') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/80 dark:bg-indigo-950/50 font-bold' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white' }} transition-all text-xs font-medium">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('keuangan.pengeluaran.index') ? 'bg-indigo-600 dark:bg-indigo-400' : 'bg-slate-300 dark:bg-zinc-600' }}"></span>
                        Daftar Pengeluaran
                    </a>
                    <a href="{{ route('keuangan.pengeluaran.kategori.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('keuangan.pengeluaran.kategori.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/80 dark:bg-indigo-950/50 font-bold' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white' }} transition-all text-xs font-medium">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('keuangan.pengeluaran.kategori.*') ? 'bg-indigo-600 dark:bg-indigo-400' : 'bg-slate-300 dark:bg-zinc-600' }}"></span>
                        Kategori Pengeluaran
                    </a>
                </div>
            </div>
            @endif

            {{-- Tagihan --}}
            @if(auth()->user()->hasPermission('manage_keuangan') || auth()->user()->hasPermission('manage_tagihan'))
            <a href="{{ route('keuangan.tagihan.index') }}"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('keuangan.tagihan.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('keuangan.tagihan.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span class="text-sm">Tagihan Siswa</span>
            </a>
            @endif

            {{-- Rekening Bank --}}
            @if(in_array(strtolower(auth()->user()->level), ['super admin', 'administrator']) || auth()->user()->hasPermission('manage_bank'))
            <a href="{{ route('bank.index') }}"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('bank.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('bank.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span class="text-sm">Rekening Bank</span>
            </a>
            @endif

        </nav>
    </div>
    @endif
