<header class="bg-white dark:bg-slate-900/80 backdrop-blur-sm border-b border-slate-200 dark:border-slate-900/50 sticky top-0 z-20 transition-colors duration-200">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center gap-3 flex-wrap">
                <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-700 focus:outline-none lg:hidden mr-4 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </button>
                <h2 class="text-[22px] font-bold text-slate-800 dark:text-white tracking-tight">@yield('title', 'Dashboard')</h2>

                {{-- Selector Kantor & Periode --}}
                @if(isset($kantors) && $kantors->count() > 0)
                <form action="{{ route('session.konteks') }}" method="POST" class="hidden lg:flex items-center gap-2 ml-4">
                    @csrf
                    {{-- Kantor Selector --}}
                    <div class="relative">
                        <select name="kantor_id" onchange="this.form.submit()"
                            class="appearance-none text-xs font-semibold pl-3 pr-7 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-400 cursor-pointer transition-colors">
                            <option value="">— Pilih Kantor —</option>
                            @foreach($kantors as $k)
                            <option value="{{ $k->id }}" {{ session('kantor_id') == $k->id ? 'selected' : '' }}>
                                🏢 {{ $k->nama_kantor }}
                            </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2 text-slate-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    {{-- Periode Selector --}}
                    @if(isset($periodes) && $periodes->count() > 0)
                    <div class="relative">
                        <select name="periode_id" onchange="this.form.submit()"
                            class="appearance-none text-xs font-semibold pl-3 pr-7 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-400 cursor-pointer transition-colors">
                            <option value="">— Pilih Periode —</option>
                            @foreach($periodes as $p)
                            <option value="{{ $p->id }}" {{ session('periode_id') == $p->id ? 'selected' : '' }}>
                                📅 {{ $p->tahun_periode }}{{ $p->is_active ? ' ✓' : '' }}
                            </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2 text-slate-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @endif
                </form>
                @endif
            </div>

            <div class="flex items-center gap-2 sm:gap-4">
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode" type="button" class="p-2 rounded-full text-slate-400 hover:text-slate-600 dark:text-slate-400 dark:hover:text-slate-200 transition-colors focus:outline-none" aria-label="Toggle Dark Mode">
                    <!-- Moon Icon -->
                    <svg x-show="!darkMode" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <!-- Sun Icon -->
                    <svg x-show="darkMode" x-cloak class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </button>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="hidden sm:flex items-center gap-2.5 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700/80 px-2.5 py-1.5 rounded-full border border-slate-200 dark:border-transparent focus:outline-none transition-colors cursor-pointer">
                        <div class="w-[26px] h-[26px] rounded-full bg-indigo-100 dark:bg-indigo-600 flex items-center justify-center text-indigo-600 dark:text-white font-bold text-xs">
                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-white">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-lg shadow-slate-200/50 dark:shadow-none py-1 border border-slate-100 dark:border-slate-700 ring-1 ring-black ring-opacity-5 focus:outline-none z-50 py-2"
                        x-cloak>

                        <div class="px-4 py-2 border-b border-slate-50 mb-1">
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Akun Saya</p>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-indigo-600 transition-colors">
                            <svg class="mr-3 w-4 h-4 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Edit Profile
                        </a>

                        <div class="border-t border-slate-50 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="flex w-full items-center px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-red-700 dark:hover:text-red-300 transition-colors">
                                <svg class="mr-3 w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>