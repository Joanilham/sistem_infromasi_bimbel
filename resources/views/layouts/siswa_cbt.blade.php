@php
    $user = Auth::user();
    $pesertaDidik = $user ? $user->pesertaDidik : null;
    $pembayaranOverdue = false;
    
    if ($pesertaDidik) {
        $statusPembayaran = $pesertaDidik->getStatusPembayaran();
        $pembayaranOverdue = $statusPembayaran['is_locked'];
    }
@endphp
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
    <style>
        :root {
            --primary: #4318FF;
            --primary-light: #F4F7FE;
            --text-main: #2B3674;
            --text-muted: #A3AED0;
            --bg-body: #F4F7FE;
            --white: #FFFFFF;
            --sidebar-bg: #0B1437;
            --sidebar-text: #A3AED0;
            --sidebar-active-bg: #4318FF;
            --danger: #EE5D50;
            --border-color: #E2E8F0;
        }
        
        /* TomSelect Styles */
        .ts-control { border-radius: 0.75rem !important; border: 1px solid #e2e8f0 !important; padding: 0.625rem 0.875rem !important; font-size: 0.875rem !important; box-shadow: none !important; }
        .ts-dropdown { border-radius: 0.75rem !important; border: 1px solid #e2e8f0 !important; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important; font-size: 0.875rem !important; }
        .ts-dropdown .active { background-color: #EEF2FF !important; color: #4318FF !important; }

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
        .sidebar-link.active { background: var(--sidebar-active-bg); color: #FFF; box-shadow: 0 4px 12px rgba(67,24,255,0.3); }
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 50; border-bottom: 1px solid var(--border-color);
        }
        .header-mobile-brand { display: flex; align-items: center; gap: 8px; font-size: 1.1rem; font-weight: 800; color: var(--text-main); text-decoration: none; }
        .header-mobile-brand svg { width: 22px; height: 22px; color: var(--primary); }
        .header-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #8F9BFA);
            color: white; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.8rem;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .header-avatar:focus-visible {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 24, 255, 0.25);
        }
        .profile-popover {
            position: absolute;
            right: 0;
            top: calc(100% + 10px);
            min-width: 220px;
            background: var(--white);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 16px 48px rgba(15, 23, 42, 0.12);
            z-index: 60;
            overflow: hidden;
        }

        .profile-popover-item {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 12px 16px;
            font-size: 0.875rem;
            font-weight: 600;
            font-family: inherit;
            color: var(--text-main);
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
            transition: background 0.15s;
        }
        .profile-popover-item:hover {
            background: var(--primary-light);
        }
        .profile-popover-item.danger {
            color: var(--danger);
        }
        .profile-popover-item.danger:hover {
            background: rgba(238, 93, 80, 0.12);
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
    <!-- TomSelect CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    @stack('head')
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
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
            @if($pembayaranOverdue)
            <li>
                <a href="javascript:void(0)" onclick="alert('⚠️ Akses ditangguhkan! Tagihan paket Anda belum dilunasi dan durasi bimbingan hampir habis / terlampaui. Silakan melunasi tagihan di menu Pembayaran.')" class="sidebar-link" style="opacity: 0.55; cursor: not-allowed;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" onclick="alert('⚠️ Akses ditangguhkan! Tagihan paket Anda belum dilunasi dan durasi bimbingan hampir habis / terlampaui. Silakan melunasi tagihan di menu Pembayaran.')" class="sidebar-link" style="opacity: 0.55; cursor: not-allowed;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Ujian Online
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" onclick="alert('⚠️ Akses ditangguhkan! Tagihan paket Anda belum dilunasi dan durasi bimbingan hampir habis / terlampaui. Silakan melunasi tagihan di menu Pembayaran.')" class="sidebar-link" style="opacity: 0.55; cursor: not-allowed;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Nilai Saya
                </a>
            </li>
            @else
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
            @endif
        </ul>
        <div class="sidebar-footer mt-auto pt-4 px-1">
            <p class="text-[10px] font-bold uppercase tracking-wider mb-2" style="color: var(--sidebar-text); opacity: 0.75;">Akun</p>
            <p class="text-xs leading-snug mb-1" style="color: rgba(255,255,255,0.9);">Profil &amp; logout ada di menu foto pojok kanan atas.</p>
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
            
            <div class="flex items-center gap-3 sm:gap-4 relative" x-data="{ profileOpen: false }">
                <div class="hidden lg:flex flex-col items-end mr-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider" style="color: var(--text-muted);">Status Siswa</span>
                    <span class="text-xs font-bold" style="color: var(--text-main);">Aktif • {{ Auth::user()->pesertaDidik?->kelompokBelajar?->nama_kelompok ?? 'Umum' }}</span>
                </div>
                <button
                    type="button"
                    id="siswa-profile-trigger"
                    @click="profileOpen = !profileOpen"
                    @keydown.escape.window="profileOpen = false"
                    class="shrink-0 rounded-full focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#4318FF] dark:focus-visible:ring-offset-[#080E29]"
                    :aria-expanded="profileOpen"
                    aria-haspopup="true"
                    aria-label="Menu akun"
                >
                    @if(Auth::user() && Auth::user()->photo)
                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="" class="header-avatar" width="40" height="40" style="object-fit: cover;">
                    @else
                        <div class="header-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'S', 0, 2)) }}</div>
                    @endif
                </button>

                {{-- Popup akun (klik foto) --}}
                <div
                    x-show="profileOpen"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                    @click.outside="profileOpen = false"
                    class="profile-popover"
                    x-cloak
                    role="menu"
                    aria-label="Menu akun pengguna"
                >
                    <div class="px-4 py-3 border-b" style="border-color: var(--border-color);">
                        <p class="text-xs font-bold truncate" style="color: var(--text-muted);">{{ Auth::user()->email }}</p>
                        <p class="text-sm font-extrabold truncate mt-0.5" style="color: var(--text-main);">{{ Auth::user()->name }}</p>
                    </div>
                    <a href="{{ route('siswa.profile.edit') }}" class="profile-popover-item" role="menuitem" @click="profileOpen = false">
                        <svg class="w-5 h-5 shrink-0 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Edit profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}" role="none">
                        @csrf
                        <button type="submit" class="profile-popover-item danger w-full" role="menuitem">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
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
        @if($pembayaranOverdue)
            <a href="javascript:void(0)" onclick="alert('⚠️ Akses ditangguhkan! Tagihan paket Anda belum dilunasi dan durasi bimbingan hampir habis / terlampaui. Silakan melunasi tagihan di menu Pembayaran.')" class="bnav-item" style="opacity: 0.5; cursor: not-allowed;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                Home
            </a>
            <a href="javascript:void(0)" onclick="alert('⚠️ Akses ditangguhkan! Tagihan paket Anda belum dilunasi dan durasi bimbingan hampir habis / terlampaui. Silakan melunasi tagihan di menu Pembayaran.')" class="bnav-item" style="opacity: 0.5; cursor: not-allowed;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                Ujian
            </a>
            <a href="javascript:void(0)" onclick="alert('⚠️ Akses ditangguhkan! Tagihan paket Anda belum dilunasi dan durasi bimbingan hampir habis / terlampaui. Silakan melunasi tagihan di menu Pembayaran.')" class="bnav-item bnav-fab" style="opacity: 0.5; cursor: not-allowed; background: var(--text-muted);">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </a>
            <a href="javascript:void(0)" onclick="alert('⚠️ Akses ditangguhkan! Tagihan paket Anda belum dilunasi dan durasi bimbingan hampir habis / terlampaui. Silakan melunasi tagihan di menu Pembayaran.')" class="bnav-item" style="opacity: 0.5; cursor: not-allowed;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                Nilai
            </a>
            <a href="javascript:void(0)" onclick="alert('⚠️ Akses ditangguhkan! Tagihan paket Anda belum dilunasi dan durasi bimbingan hampir habis / terlampaui. Silakan melunasi tagihan di menu Pembayaran.')" class="bnav-item" style="opacity: 0.5; cursor: not-allowed;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                Profil
            </a>
        @else
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
        @endif
    </nav>

</div>

@stack('scripts')
@include('components.autosave-script')

<!-- TomSelect JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('select').forEach((el) => {
            if (el.classList.contains('no-tomselect')) return;
            new TomSelect(el, {
                create: false,
                sortField: null,
                plugins: ['dropdown_input'],
            });
        });
    });
</script>
    @include('components.loading-overlay')
</body>
</html>
