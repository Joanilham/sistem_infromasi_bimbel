<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Guru') - Genius Education</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <!-- Top Navigation -->
    <header class="bg-white shadow-sm border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-slate-800">Dashboard Guru</h1>
                        <p class="text-xs text-slate-500">Genius Education</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-medium text-slate-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500">{{ Auth::user()->matapelajaran }}</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                        <span class="text-indigo-600 font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-500 hover:text-red-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation Menu -->
    <nav class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-8 h-12 items-center">
                <a href="{{ route('guru.dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 h-12 flex items-center {{ request()->routeIs('guru.dashboard') ? 'text-indigo-600 border-b-2 border-indigo-600' : '' }}">Dashboard</a>
                <a href="{{ route('guru.jadwal.index') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 h-12 flex items-center {{ request()->routeIs('guru.jadwal.*') ? 'text-indigo-600 border-b-2 border-indigo-600' : '' }}">Jadwal Mata Pelajaran</a>
                <a href="{{ route('guru.bank-soal.index') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 h-12 flex items-center {{ request()->routeIs('guru.bank-soal.*') ? 'text-indigo-600 border-b-2 border-indigo-600' : '' }}">Bank Soal</a>
                <a href="{{ route('guru.ujian.index') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 h-12 flex items-center {{ request()->routeIs('guru.ujian.*') ? 'text-indigo-600 border-b-2 border-indigo-600' : '' }}">Ujian</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>
</body>
</html>