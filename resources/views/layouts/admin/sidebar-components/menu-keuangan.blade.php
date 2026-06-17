    {{-- MENU KEUANGAN --}}
    @if(in_array(strtolower(auth()->user()->level), ['super admin', 'administrator']) || auth()->user()->hasPermission('manage_keuangan') || auth()->user()->hasPermission('manage_pembayaran_siswa') || auth()->user()->hasPermission('manage_pemasukan') || auth()->user()->hasPermission('manage_pengeluaran') || auth()->user()->hasPermission('manage_tagihan') || auth()->user()->hasPermission('manage_bank'))
    <div class="mb-6">
        <h3 class="px-3 text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-widest mb-2">MENU KEUANGAN</h3>
        <nav class="space-y-1">

            {{-- Pembayaran --}}
            @if(auth()->user()->hasPermission('manage_keuangan') || auth()->user()->hasPermission('manage_pembayaran_siswa'))
            <a href="{{ route('keuangan.pembayaran.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('keuangan.pembayaran.*') ? 'bg-emerald-500 shadow-md shadow-emerald-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('keuangan.pembayaran.*') ? 'bg-white/25' : 'bg-emerald-500 shadow-sm shadow-emerald-500/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('keuangan.pembayaran.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Pembayaran Siswa</span>
            </a>
            @endif

            {{-- Pemasukan Dropdown --}}
            @if(auth()->user()->hasPermission('manage_keuangan') || auth()->user()->hasPermission('manage_pemasukan'))
            <div x-data="{ open: {{ request()->routeIs('keuangan.pemasukan.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('keuangan.pemasukan.*') ? 'bg-green-500 shadow-md shadow-green-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group focus:outline-none">
                    <div class="flex items-center">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('keuangan.pemasukan.*') ? 'bg-white/25' : 'bg-green-500 shadow-sm shadow-green-500/40' }}">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold {{ request()->routeIs('keuangan.pemasukan.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Pemasukan</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('keuangan.pemasukan.*') ? 'text-white/80' : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 ml-3 pl-3 border-l-2 border-slate-100 dark:border-zinc-700 space-y-1" style="display: none;">
                    <a href="{{ route('keuangan.pemasukan.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('keuangan.pemasukan.index') ? 'bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white' }} transition-all text-sm font-medium">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('keuangan.pemasukan.index') ? 'bg-green-500' : 'bg-green-100 dark:bg-green-900/50' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('keuangan.pemasukan.index') ? 'text-white' : 'text-green-600 dark:text-green-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        Daftar Pemasukan
                    </a>
                    <a href="{{ route('keuangan.pemasukan.kategori.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('keuangan.pemasukan.kategori.*') ? 'bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white' }} transition-all text-sm font-medium">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('keuangan.pemasukan.kategori.*') ? 'bg-green-500' : 'bg-green-100 dark:bg-green-900/50' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('keuangan.pemasukan.kategori.*') ? 'text-white' : 'text-green-600 dark:text-green-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        Kategori Pemasukan
                    </a>
                </div>
            </div>
            @endif

            {{-- Pengeluaran Dropdown --}}
            @if(auth()->user()->hasPermission('manage_keuangan') || auth()->user()->hasPermission('manage_pengeluaran'))
            <div x-data="{ open: {{ request()->routeIs('keuangan.pengeluaran.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('keuangan.pengeluaran.*') ? 'bg-red-500 shadow-md shadow-red-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group focus:outline-none">
                    <div class="flex items-center">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('keuangan.pengeluaran.*') ? 'bg-white/25' : 'bg-red-500 shadow-sm shadow-red-500/40' }}">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold {{ request()->routeIs('keuangan.pengeluaran.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Pengeluaran</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('keuangan.pengeluaran.*') ? 'text-white/80' : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 ml-3 pl-3 border-l-2 border-slate-100 dark:border-zinc-700 space-y-1" style="display: none;">
                    <a href="{{ route('keuangan.pengeluaran.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('keuangan.pengeluaran.index') ? 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white' }} transition-all text-sm font-medium">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('keuangan.pengeluaran.index') ? 'bg-red-500' : 'bg-red-100 dark:bg-red-900/50' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('keuangan.pengeluaran.index') ? 'text-white' : 'text-red-600 dark:text-red-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        Daftar Pengeluaran
                    </a>
                    <a href="{{ route('keuangan.pengeluaran.kategori.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('keuangan.pengeluaran.kategori.*') ? 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white' }} transition-all text-sm font-medium">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('keuangan.pengeluaran.kategori.*') ? 'bg-red-500' : 'bg-red-100 dark:bg-red-900/50' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('keuangan.pengeluaran.kategori.*') ? 'text-white' : 'text-red-600 dark:text-red-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        Kategori Pengeluaran
                    </a>
                </div>
            </div>
            @endif

            {{-- Tagihan --}}
            @if(auth()->user()->hasPermission('manage_keuangan') || auth()->user()->hasPermission('manage_tagihan'))
            <a href="{{ route('keuangan.tagihan.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('keuangan.tagihan.*') ? 'bg-amber-500 shadow-md shadow-amber-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('keuangan.tagihan.*') ? 'bg-white/25' : 'bg-amber-500 shadow-sm shadow-amber-500/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('keuangan.tagihan.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Tagihan Siswa</span>
            </a>
            @endif

            {{-- Rekening Bank --}}
            @if(in_array(strtolower(auth()->user()->level), ['super admin', 'administrator']) || auth()->user()->hasPermission('manage_bank'))
            <a href="{{ route('bank.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('bank.*') ? 'bg-indigo-500 shadow-md shadow-indigo-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('bank.*') ? 'bg-white/25' : 'bg-indigo-500 shadow-sm shadow-indigo-500/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('bank.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Rekening Bank</span>
            </a>
            @endif

        </nav>
    </div>
    @endif
