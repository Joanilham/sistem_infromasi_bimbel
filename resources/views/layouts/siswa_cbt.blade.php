<!DOCTYPE html>
<html lang="id" x-data="{ 
    sidebarOpen: window.innerWidth >= 1024
}" @resize.window="if(window.innerWidth < 1024) { sidebarOpen = false; }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ujian') - GeniusEdu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        :root {
            --primary: #388782;
            --primary-light: #F0F7F6;
            --text-main: #2B3674;
            --text-muted: #A3AED0;
            --bg-body: #F4F7FE;
            --white: #FFFFFF;
            --sidebar-bg: #0B1437;
            --sidebar-text: #A3AED0;
            --sidebar-active-bg: #388782;
            --danger: #EE5D50;
            --border-color: #E2E8F0;
        }
        @media (prefers-color-scheme: dark) {
            :root {
                --primary-light: rgba(56, 135, 130, 0.15);
                --text-main: #FFFFFF;
                --text-muted: #A3AED0;
                --bg-body: #080E29;
                --white: #111C44;
                --sidebar-bg: #111C44;
                --danger: #EE5D50;
                --border-color: rgba(255, 255, 255, 0.1);
            }
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { overflow-x: hidden; max-width: 100vw; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
        }

        .app-layout { display: flex; min-height: 100vh; }

        /* Sidebar Desktop */
        .sidebar {
            width: 260px; background: var(--sidebar-bg); flex-shrink: 0;
            display: flex; flex-direction: column; padding: 28px 18px;
            border-right: 1px solid var(--border-color); position: fixed; top: 0; left: 0; height: 100vh;
            z-index: 40; transition: transform 0.3s; overflow-y: auto;
        }
        .sidebar.closed { transform: translateX(-100%); }
        .sidebar-brand { display: flex; align-items: center; gap: 10px; font-size: 1.3rem; font-weight: 800; color: #FFF; margin-bottom: 40px; padding: 0 8px; text-decoration: none; }
        .sidebar-brand svg { width: 28px; height: 28px; color: var(--primary); }
        .sidebar-menu { list-style: none; display: flex; flex-direction: column; gap: 6px; flex: 1; }
        .sidebar-link {
            display: flex; align-items: center; gap: 12px; padding: 12px 14px;
            border-radius: 12px; color: var(--sidebar-text); font-weight: 600; text-decoration: none; font-size: 0.9rem;
            transition: all 0.2s;
        }
        .sidebar-link svg { width: 20px; height: 20px; opacity: 0.8; }
        .sidebar-link.active { background: var(--sidebar-active-bg); color: #FFF; box-shadow: 0 4px 12px rgba(56, 135, 130, 0.3); }
        .sidebar-link.active svg { opacity: 1; }
        .sidebar-link:hover:not(.active) { background: rgba(255,255,255,0.05); color: #FFF; }
        .sidebar-footer { margin-top: auto; padding-top: 16px; }

        /* Main Content */
        .main-content { flex: 1; display: flex; flex-direction: column; min-width: 0; max-width: 100vw; padding-bottom: 70px; transition: margin-left 0.3s; margin-left: 0; }
        .main-content.sidebar-open { margin-left: 260px; }

        /* Top Header */
        .top-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 16px; background: var(--white);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 30; border-bottom: 1px solid var(--border-color);
        }
        .header-mobile-brand { display: flex; align-items: center; gap: 8px; font-size: 1.1rem; font-weight: 800; color: var(--text-main); text-decoration: none; }
        .header-mobile-brand svg { width: 22px; height: 22px; color: var(--primary); }
        .header-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, #388782, #206D6C);
            color: white; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.85rem;
        }

        /* Content wrap */
        .content-wrap { padding: 20px 16px; max-width: 1100px; margin: 0 auto; width: 100%; }

        /* Bottom Nav Mobile */
        .bottom-nav {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: var(--white); padding: 8px 20px;
            display: flex; justify-content: space-around; align-items: center;
            border-top: 1px solid var(--border-color); z-index: 100;
            padding-bottom: calc(8px + env(safe-area-inset-bottom));
        }
        .bnav-item { display: flex; flex-direction: column; align-items: center; gap: 3px; text-decoration: none; color: var(--text-muted); font-size: 0.65rem; font-weight: 600; }
        .bnav-item svg { width: 22px; height: 22px; }
        .bnav-item.active { color: var(--primary); }
        .bnav-fab {
            width: 46px; height: 46px; background: var(--primary); color: white;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            transform: translateY(-14px); box-shadow: 0 6px 16px rgba(67,24,255,0.3);
            border: 3px solid var(--bg-body);
        }

        @media (min-width: 1024px) {
            .bottom-nav { display: none; }
            .main-content { padding-bottom: 0; }
        }
        @media (max-width: 1023px) {
            .main-content.sidebar-open { margin-left: 0; }
        }
    </style>
    @stack('head')
</head>
<body>

<div class="app-layout">

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-30 bg-gray-900 bg-opacity-50 lg:hidden" @click="sidebarOpen = false" x-cloak></div>

    {{-- SIDEBAR DESKTOP --}}
    <aside class="sidebar" :class="!sidebarOpen ? 'closed' : ''">
        <a href="{{ route('siswa.dashboard') }}" class="sidebar-brand">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            GeniusEdu
        </a>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('siswa.dashboard') }}" class="sidebar-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('siswa.ujian.index') }}" class="sidebar-link {{ request()->routeIs('siswa.ujian.*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Ujian Online
                </a>
            </li>
            <li>
                <a href="{{ route('siswa.hasil.index') }}" class="sidebar-link {{ request()->routeIs('siswa.hasil.*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Nilai Saya
                </a>
            </li>
            <li>
                <a href="{{ route('siswa.profile.edit') }}" class="sidebar-link {{ request()->routeIs('siswa.profile.*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profil Saya
                </a>
            </li>
        </ul>
        <div class="sidebar-footer">
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="main-content" :class="sidebarOpen ? 'sidebar-open' : ''">
        {{-- Header --}}
        <header class="top-header">
            <div style="display: flex; align-items: center; gap: 16px;">
                <button @click="sidebarOpen = !sidebarOpen" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px;">
                    <svg x-show="sidebarOpen" style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <svg x-show="!sidebarOpen" x-cloak style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <a href="{{ route('siswa.dashboard') }}" class="header-mobile-brand">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span class="lg:hidden">GeniusEdu</span>
                </a>
            </div>
            
            <div style="display: flex; align-items: center; gap: 16px;">
                <div class="hidden lg:flex flex-col items-end mr-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Status Siswa</span>
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-200">Aktif • {{ Auth::user()->pesertaDidik->kelompokBelajar->nama ?? 'Umum' }}</span>
                </div>
                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 sm:gap-2.5 bg-slate-50 dark:bg-zinc-800 hover:bg-slate-100 dark:hover:bg-zinc-700/80 px-2.5 py-1.5 rounded-full border border-slate-200 dark:border-zinc-700 focus:outline-none transition-colors cursor-pointer">
                        @if(Auth::user() && Auth::user()->photo)
                            <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Profile" class="w-[26px] h-[26px] rounded-full object-cover">
                        @else
                            <div class="w-[26px] h-[26px] rounded-full bg-[#388782] dark:bg-[#388782] flex items-center justify-center text-white font-bold text-xs">
                                {{ strtoupper(substr(Auth::user()->name ?? 'S', 0, 1)) }}
                            </div>
                        @endif
                        <span class="hidden sm:block text-sm font-medium text-slate-700 dark:text-white">{{ explode(' ', Auth::user()->name)[0] ?? 'Siswa' }}</span>
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
                        class="absolute right-0 mt-2 w-48 bg-white dark:bg-zinc-800 rounded-xl shadow-lg shadow-slate-200/50 dark:shadow-zinc-900/80 py-1 border border-slate-100 dark:border-zinc-700 ring-1 ring-black ring-opacity-5 focus:outline-none z-50 py-2"
                        x-cloak>

                        <div class="px-4 py-2 border-b border-slate-50 mb-1">
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Akun Saya</p>
                        </div>

                        <a href="{{ route('siswa.profile.edit') }}" class="flex items-center px-4 py-2.5 text-sm text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-700 hover:text-[#388782] transition-colors">
                            <svg class="mr-3 w-4 h-4 text-slate-400 group-hover:text-[#388782]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Edit Profile
                        </a>

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
                @if(Auth::user() && Auth::user()->photo)
                    <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Avatar" class="header-avatar lg:hidden" style="object-fit: cover;">
                @else
                    <div class="header-avatar lg:hidden">{{ strtoupper(substr(Auth::user()->name ?? 'S', 0, 2)) }}</div>
                @endif
            </div>
        </header>

        <div class="content-wrap">
            @if(session('success'))
                <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
            @endif
            @if(session('info'))
                <div class="mb-5 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl text-sm">{{ session('info') }}</div>
            @endif

            @yield('content')
        </div>
    </main>

    {{-- BOTTOM NAV MOBILE --}}
    <nav class="bottom-nav">
        <a href="{{ route('siswa.dashboard') }}" class="bnav-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Home
        </a>
        <a href="{{ route('siswa.ujian.index') }}" class="bnav-item {{ request()->routeIs('siswa.ujian.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            Ujian
        </a>
        <a href="{{ route('siswa.qr.show') }}" class="bnav-item bnav-fab">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
        </a>
        <a href="{{ route('siswa.hasil.index') }}" class="bnav-item {{ request()->routeIs('siswa.hasil.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Nilai
        </a>
        <a href="{{ route('siswa.profile.edit') }}" class="bnav-item {{ request()->routeIs('siswa.profile.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Profil
        </a>
    </nav>

</div>

@stack('scripts')
</body>
</html>
