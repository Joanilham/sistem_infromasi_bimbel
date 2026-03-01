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

        /* Custom Scrollbar minimalis (Hover only / hidden outline) */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.2);
            border-radius: 10px;
        }

        .custom-scrollbar:hover::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.4);
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(51, 65, 85, 0.4);
        }

        .dark .custom-scrollbar:hover::-webkit-scrollbar-thumb {
            background: rgba(71, 85, 105, 0.6);
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
        @include('layouts.admin.sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top Navigation -->
        @include('layouts.admin.header')

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

    @include('layouts.admin.scripts')
</body>

</html>