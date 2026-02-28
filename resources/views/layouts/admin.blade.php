<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }" x-init="$watch('darkMode', val => { localStorage.setItem('theme', val ? 'dark' : 'light'); if(val) { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); }}); if(darkMode){ document.documentElement.classList.add('dark'); }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Genius Education</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Custom DataTables Tailwind Styling */
        div.dataTables_wrapper div.dataTables_length label,
        div.dataTables_wrapper div.dataTables_filter label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #475569;
            margin-bottom: 0;
        }

        div.dataTables_wrapper div.dataTables_length select {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.375rem 2rem 0.375rem 0.75rem;
            background-color: #f8fafc;
            outline: none;
        }

        div.dataTables_wrapper div.dataTables_filter input {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.375rem 0.75rem;
            background-color: #f8fafc;
            outline: none;
            width: 100%;
            max-width: 200px;
        }

        div.dataTables_length select:focus,
        div.dataTables_filter input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
        }

        div.dataTables_wrapper div.dataTables_info {
            padding-top: 1rem;
            font-size: 0.875rem;
            color: #64748b;
        }

        div.dataTables_wrapper div.dataTables_paginate {
            padding-top: 1rem;
            display: flex;
            gap: 0.25rem;
            justify-content: flex-end;
            align-items: center;
        }

        /* DataTables Empty State Styling */
        table.dataTable tbody td.dataTables_empty {
            padding: 3rem 1.5rem;
            text-align: center;
            color: #64748b;
            font-size: 0.875rem;
        }

        /* DataTables Pagination Styling */
        div.dataTables_wrapper div.dataTables_paginate .paginate_button {
            padding: 0.375rem 0.75rem;
            margin-left: 0.25rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            color: #475569 !important;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.875rem;
        }

        div.dataTables_wrapper div.dataTables_paginate .paginate_button:hover {
            background-color: #f1f5f9;
            color: #1e293b !important;
            border-color: #cbd5e1;
        }

        div.dataTables_wrapper div.dataTables_paginate .paginate_button.current,
        div.dataTables_wrapper div.dataTables_paginate .paginate_button.current:hover {
            background-color: #4f46e5;
            color: #ffffff !important;
            border-color: #4f46e5;
        }

        div.dataTables_wrapper div.dataTables_paginate .paginate_button.disabled,
        div.dataTables_wrapper div.dataTables_paginate .paginate_button.disabled:hover {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: #ffffff;
            color: #475569 !important;
            border-color: #e2e8f0;
        }

        /* Dark Mode Extensions for DataTables */
        .dark div.dataTables_wrapper div.dataTables_length label,
        .dark div.dataTables_wrapper div.dataTables_filter label {
            color: #94a3b8;
        }

        .dark div.dataTables_wrapper div.dataTables_length select,
        .dark div.dataTables_wrapper div.dataTables_filter input {
            border-color: #334155;
            background-color: #0f172a;
            color: #f8fafc;
            color-scheme: dark;
        }

        .dark div.dataTables_length select:focus,
        .dark div.dataTables_filter input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.4);
        }

        .dark div.dataTables_wrapper div.dataTables_info,
        .dark table.dataTable tbody td.dataTables_empty {
            color: #94a3b8;
        }

        .dark div.dataTables_wrapper div.dataTables_paginate .paginate_button {
            border-color: #334155;
            background-color: transparent;
            color: #94a3b8 !important;
        }

        .dark div.dataTables_wrapper div.dataTables_paginate .paginate_button:hover {
            background-color: #1e293b;
            color: #f8fafc !important;
            border-color: #475569;
        }

        .dark div.dataTables_wrapper div.dataTables_paginate .paginate_button.current,
        .dark div.dataTables_wrapper div.dataTables_paginate .paginate_button.current:hover {
            background-color: #4f46e5;
            color: #ffffff !important;
            border-color: #4f46e5;
        }

        .dark div.dataTables_wrapper div.dataTables_paginate .paginate_button.disabled,
        .dark div.dataTables_wrapper div.dataTables_paginate .paginate_button.disabled:hover {
            background-color: transparent;
            color: #475569 !important;
            border-color: #334155;
        }
    </style>
    <!-- DataTables TailwindCSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-slate-50 dark:bg-slate-900 flex h-screen overflow-hidden text-slate-800 dark:text-slate-100 transition-colors duration-200" x-data="{ sidebarOpen: false }">

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-gray-900 bg-opacity-50 lg:hidden" @click="sidebarOpen = false" x-cloak></div>

    <!-- Sidebar -->
    <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-slate-900 shadow-2xl lg:translate-x-0 lg:static lg:inset-auto flex flex-col">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-center h-20 border-b border-white/10 px-4 bg-slate-950/50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <span class="text-xl font-extrabold text-white tracking-tight">Genius<span class="text-indigo-400">Edu</span></span>
            </div>
        </div>

        <!-- Sidebar Content -->
        <div class="overflow-y-auto flex-1 p-4 custom-scrollbar">
            <nav class="space-y-1.5 mb-8">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} group">
                    <svg class="mr-3 h-5 w-5 transition-colors {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-slate-200' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
            </nav>

            <!-- Menu Group: Pengaturan -->
            <div class="mb-6">
                <h3 class="px-4 text-xs font-bold uppercase text-slate-500 tracking-wider mb-3">Pengaturan</h3>
                <nav class="space-y-1.5">
                    <a href="{{ route('kantor.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('kantor.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} group">
                        <svg class="mr-3 h-5 w-5 transition-colors {{ request()->routeIs('kantor.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-200' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Kantor
                    </a>

                    <a href="{{ route('periode.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('periode.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} group">
                        <svg class="mr-3 h-5 w-5 transition-colors {{ request()->routeIs('periode.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-200' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Periode
                    </a>

                    <a href="{{ route('master.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('master.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} group">
                        <svg class="mr-3 h-5 w-5 transition-colors {{ request()->routeIs('master.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-200' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                        </svg>
                        Data Master
                    </a>

                    <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-slate-300 hover:bg-white/5 hover:text-white group">
                        <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Pengguna
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top Navigation -->
        <header class="bg-white dark:bg-slate-900/80 backdrop-blur-sm border-b border-slate-200 dark:border-slate-900/50 sticky top-0 z-20 transition-colors duration-200">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-700 focus:outline-none lg:hidden mr-4 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </button>
                        <h2 class="text-[22px] font-bold text-slate-800 dark:text-white tracking-tight">@yield('title', 'Dashboard')</h2>
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

        <!-- Main Body Area -->
        <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-slate-950 p-4 sm:p-6 lg:p-8 custom-scrollbar transition-colors duration-200">
            <div class="max-w-7xl mx-auto">
                @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-xl flex items-center shadow-sm" role="alert">
                    <svg class="w-5 h-5 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-4 rounded-xl shadow-sm" role="alert">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-semibold">Mohon perbaiki kesalahan berikut:</span>
                    </div>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>

    @yield('scripts')
</body>

</html>