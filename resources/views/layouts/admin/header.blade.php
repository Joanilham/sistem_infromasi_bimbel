<header class="bg-white dark:bg-zinc-900 backdrop-blur-sm border-b border-slate-200 dark:border-zinc-800 sticky top-0 z-50">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                <!-- Sidebar Toggle -->
                <button @click="sidebarOpen = !sidebarOpen" aria-label="Toggle Sidebar" class="shrink-0 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 focus:outline-none transition-colors p-2 hover:bg-slate-100 dark:hover:bg-zinc-800 rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="sidebarOpen">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!sidebarOpen" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="text-base sm:text-lg lg:text-[22px] font-bold text-slate-800 dark:text-white tracking-tight truncate">@yield('title', 'Dashboard')</h1>
            </div>

            <div class="flex items-center gap-2 sm:gap-4">
                <!-- Notification / Email Link -->
                @php
                    if (!isset($totalPesan)) {
                        $totalPesan = \App\Http\Controllers\System\NotifikasiController::getNotificationCounts()['totalBadge'];
                    }
                @endphp
                <div class="relative shrink-0">
                    <a href="{{ route('notifikasi.index') }}" class="relative inline-flex items-center justify-center p-2.5 rounded-xl text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-zinc-800 transition-all focus:outline-none cursor-pointer" aria-label="Pemberitahuan" title="Pusat Pemberitahuan">
                        <!-- Notification Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if(isset($totalPesan) && $totalPesan > 0)
                        <span class="absolute -top-0.5 -right-0.5 flex min-w-[18px] h-[18px] px-1 items-center justify-center rounded-full bg-red-600 text-white text-[9px] font-black leading-none ring-2 ring-white dark:ring-zinc-900 shadow-xs">
                            {{ $totalPesan > 99 ? '99+' : $totalPesan }}
                        </span>
                        @endif
                    </a>
                </div>

                <!-- User Dropdown -->
                <div class="relative shrink-0" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" aria-label="User Menu" class="flex items-center gap-2 sm:gap-2.5 bg-slate-50 dark:bg-zinc-800 hover:bg-slate-100 dark:hover:bg-zinc-700/80 px-2 sm:px-2.5 py-1 sm:py-1.5 rounded-full border border-slate-200 dark:border-zinc-700 focus:outline-none transition-colors cursor-pointer">
                        @if(auth()->user() && auth()->user()->photo)
                            <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Profile" class="w-[26px] h-[26px] rounded-full object-cover">
                        @else
                            <div class="w-[26px] h-[26px] rounded-full bg-emerald-100 dark:bg-emerald-600 flex items-center justify-center text-emerald-700 dark:text-white font-bold text-xs">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                        @endif
                        <span class="hidden sm:block text-sm font-medium text-slate-700 dark:text-white">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <svg class="hidden sm:block w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        class="absolute right-0 mt-2 w-52 bg-white dark:bg-zinc-800 rounded-xl shadow-lg shadow-slate-200/50 dark:shadow-zinc-900/80 py-1 border border-slate-100 dark:border-zinc-700 ring-1 ring-black ring-opacity-5 focus:outline-none z-50 py-2"
                        x-cloak>

                        <div class="px-4 py-2 border-b border-slate-50 dark:border-zinc-700/60 mb-1">
                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Akun Saya</p>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 text-sm text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-700 hover:text-emerald-600 transition-colors">
                            <svg class="mr-3 w-4 h-4 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Edit Profile
                        </a>

                        <a href="{{ route('notifikasi.index') }}" class="flex items-center justify-between px-4 py-2.5 text-sm text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-700 hover:text-indigo-600 transition-colors">
                            <div class="flex items-center">
                                <svg class="mr-3 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                Pemberitahuan
                            </div>
                            @if(isset($totalPesan) && $totalPesan > 0)
                            <span class="min-w-[18px] h-[18px] px-1 rounded-full bg-red-600 text-white text-[9px] font-black flex items-center justify-center">
                                {{ $totalPesan > 99 ? '99+' : $totalPesan }}
                            </span>
                            @endif
                        </a>

                        @if(auth()->check() && in_array(strtolower(auth()->user()->level), ['super admin', 'admin', 'administrator']))
                        <a href="{{ route('konteks.select') }}" class="flex items-center px-4 py-2.5 text-sm text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-700 hover:text-indigo-600 transition-colors">
                            <svg class="mr-3 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Ganti Cabang / Periode
                        </a>
                        @endif

                        <div class="border-t border-slate-50 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="flex w-full items-center px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-slate-50 dark:hover:bg-zinc-700 hover:text-red-700 dark:hover:text-red-300 transition-colors">
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
