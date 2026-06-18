<header class="bg-white dark:bg-zinc-900 backdrop-blur-sm border-b border-slate-200 dark:border-zinc-800 sticky top-0 z-30 transition-colors duration-200">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center gap-3">
            <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                <button type="button" @click="sidebarOpen = !sidebarOpen" class="shrink-0 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 focus:outline-none transition-colors p-2 hover:bg-slate-100 dark:hover:bg-zinc-800 rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="sidebarOpen">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!sidebarOpen" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h2 class="text-lg sm:text-[22px] font-bold text-slate-800 dark:text-white tracking-tight truncate">@yield('title', 'Dashboard Siswa')</h2>
            </div>

            <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                @auth
                    @if(auth()->user()->pesertaDidik)
                        <div class="hidden lg:flex flex-col items-end text-right max-w-[220px]">
                            <span class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500">Status Siswa</span>
                            <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">
                                Aktif • {{ auth()->user()->pesertaDidik->kelompokBelajar->nama_kelompok ?? 'Umum' }}
                            </span>
                        </div>
                    @endif

                    <div class="relative" x-data="{ open: false }">
                        <button type="button"
                            @click="open = !open"
                            @keydown.escape.window="open = false"
                            class="rounded-full focus:outline-none focus-visible:ring-2 focus-visible:ring-[#388782] focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-zinc-900"
                            :aria-expanded="open"
                            aria-haspopup="true"
                            aria-label="Menu akun">
                            @if(auth()->user()->photo && Storage::disk('public')->exists(auth()->user()->photo))
                                <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="" class="w-10 h-10 rounded-full object-cover border-2 border-slate-200 dark:border-zinc-600 shadow-sm">
                            @else
                                <div class="w-10 h-10 rounded-full bg-[#A2D5CB] dark:bg-[#388782] flex items-center justify-center text-[#388782] dark:text-white font-bold text-sm border-2 border-white dark:border-zinc-700 shadow-sm">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 2)) }}
                                </div>
                            @endif
                        </button>

                        <div x-show="open"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                            @click.outside="open = false"
                            class="absolute right-0 mt-2 w-56 bg-white dark:bg-zinc-800 rounded-xl shadow-xl shadow-slate-200/50 dark:shadow-zinc-950/80 border border-slate-100 dark:border-zinc-700 py-1 z-[60]"
                            x-cloak
                            role="menu">

                            <div class="px-4 py-3 border-b border-slate-100 dark:border-zinc-700">
                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email }}</p>
                                <p class="text-sm font-bold text-slate-800 dark:text-white truncate mt-0.5">{{ auth()->user()->name }}</p>
                            </div>

                            <a href="{{ route('siswa.profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-zinc-200 hover:bg-slate-50 dark:hover:bg-zinc-700 hover:text-[#388782] transition-colors" role="menuitem" @click="open = false">
                                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Edit profil
                            </a>

                            <div class="border-t border-slate-100 dark:border-zinc-700 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}" role="none">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors" role="menuitem">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>
