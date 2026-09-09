<!DOCTYPE html>
<html lang="id" x-data="{ 
    sidebarOpen: window.innerWidth >= 1024
}" @resize.window="if(window.innerWidth < 1024) { sidebarOpen = false; }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard Sistem Informasi Manajemen Pendidikan {{ $masterData->nama_lembaga ?? config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-version" content="v1.0.1">
    <meta name="author" content="{{ $masterData->nama_lembaga ?? config('app.name') }}">
    <meta name="robots" content="index, follow">
    <title>@yield('title', 'Dashboard') - {{ $masterData->nama_lembaga ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"></noscript>
    


    <script defer src="{{ asset('vendor/alpinejs/alpine.min.js') }}"></script>
    <script defer src="{{ asset('js/sidebar-scroll.js') }}?v={{ time() }}"></script>
    
    <!-- TomSelect CSS -->
    <link href="{{ asset('vendor/tom-select/tom-select.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="{{ asset('vendor/tom-select/tom-select.css') }}" rel="stylesheet"></noscript>
    
    <!-- TomSelect JS -->
    <script defer src="{{ asset('vendor/tom-select/tom-select.complete.min.js') }}"></script>
    
    <style>
        .ts-control { border-radius: 0.75rem !important; border: 1px solid #e2e8f0 !important; padding: 0.625rem 0.875rem !important; font-size: 0.875rem !important; box-shadow: none !important; }
        .ts-dropdown { border-radius: 0.75rem !important; border: 1px solid #e2e8f0 !important; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important; font-size: 0.875rem !important; }
        .ts-dropdown .active { background-color: #EEF2FF !important; color: #4F46E5 !important; }
        .dark .ts-control { background-color: #18181b !important; border-color: #27272a !important; color: #f4f4f5 !important; }
        .dark .ts-dropdown { background-color: #18181b !important; border-color: #27272a !important; color: #f4f4f5 !important; }
        .dark .ts-dropdown .active { background-color: #27272a !important; color: #818cf8 !important; }
        .dark .ts-control input { color: #f4f4f5 !important; }
        
        /* ApexCharts Tooltip Contrast Fix */
        .apexcharts-tooltip { background: #ffffff !important; border: 1px solid #e2e8f0 !important; color: #1e293b !important; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1) !important; }
        .apexcharts-tooltip-title { background: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; color: #0f172a !important; font-weight: 700 !important; }
        .dark .apexcharts-tooltip { background: #1e293b !important; border: 1px solid #334155 !important; color: #f8fafc !important; }
        .dark .apexcharts-tooltip-title { background: #0f172a !important; border-bottom: 1px solid #334155 !important; color: #ffffff !important; }
        
        /* TomSelect Dropdown Input Fixes */
        .ts-dropdown .dropdown-input-wrap { padding: 0.5rem !important; border-bottom: 1px solid #e2e8f0; }
        .dark .ts-dropdown .dropdown-input-wrap { border-bottom-color: #27272a; }
        .ts-dropdown .dropdown-input { border: 1px solid #e2e8f0 !important; border-radius: 0.5rem !important; padding: 0.375rem 0.75rem !important; font-size: 0.875rem !important; }
        .dark .ts-dropdown .dropdown-input { border-color: #27272a !important; background-color: #27272a !important; color: #f4f4f5 !important; }
        .dark .ts-dropdown .dropdown-input::placeholder { color: #a1a1aa !important; }
    </style>

    @stack('head')
    <style>
        {!! file_get_contents(public_path('css/loading.css')) !!}
    </style>
</head>

<body class="bg-slate-50 dark:bg-slate-900 flex h-screen overflow-hidden text-slate-800 dark:text-slate-100">
    @php
        $notifCounts                 = \App\Http\Controllers\System\NotifikasiController::getNotificationCounts();
        $pendaftaranMenunggu         = $pendaftaranMenunggu ?? $notifCounts['pendaftaranMenunggu'];
        $transferSppCount            = $transferSppCount ?? $notifCounts['transferSppCount'];
        $tagihanJatuhTempoCount      = $tagihanJatuhTempoCount ?? $notifCounts['tagihanJatuhTempoCount'];
        $totalPesan                  = $notifCounts['totalBadge'];
    @endphp

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-gray-900 bg-opacity-50 lg:hidden" @click="sidebarOpen = false" x-cloak></div>

    <!-- Sidebar -->
    <aside 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
        class="fixed z-40 inset-y-0 left-0 w-64 transition-transform duration-300 transform bg-white dark:bg-zinc-900 shadow-2xl lg:shadow-none border-r border-slate-200 dark:border-zinc-800 flex flex-col"
        style="will-change: transform;">
        <div class="flex flex-col h-full custom-scrollbar">
            @include('layouts.admin.sidebar')
        </div>
    </aside>

    <!-- Main Content -->
    <div :class="!sidebarOpen ? 'sidebar-closed' : ''" class="main-content-wrapper flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top Navigation -->
        @include('layouts.admin.header')

        <!-- Main Body Area -->
        <main id="main-scroll-area" class="flex-1 overflow-y-scroll bg-slate-50 dark:bg-slate-950 p-4 pb-24 sm:p-6 sm:pb-8 lg:p-8 custom-scrollbar page-enter">
            <div class="w-full">
                @if(session('success'))
                <div x-data="{ show: true }" 
                     x-init="setTimeout(() => show = false, 4000)" 
                     x-show="show" 
                     x-transition:leave="transition ease-in duration-500" 
                     x-transition:leave-start="opacity-100 transform scale-100" 
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-xl flex items-center shadow-sm" role="alert">
                    <svg class="w-5 h-5 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }" 
                     x-init="setTimeout(() => show = false, 5000)" 
                     x-show="show" 
                     x-transition:leave="transition ease-in duration-500" 
                     x-transition:leave-start="opacity-100 transform scale-100" 
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="mb-6 bg-red-50 border border-red-300 text-red-700 px-4 py-4 rounded-xl flex items-center shadow-sm" role="alert">
                    <svg class="w-5 h-5 mr-3 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="block sm:inline font-medium">{{ session('error') }}</span>
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
    @stack('modals')
    @include('components.autosave-script')

    {{-- Auto Logout setelah 30 menit tidak aktif --}}
    @auth
        @include('components.idle-timer')
    @endauth

    @stack('scripts')
    
    <!-- TomSelect JS Initialization -->
    <script>
        // Init pada saat load awal atau setelah pergantian halaman via Turbo
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('select').forEach((el) => {
                // Jangan inisialisasi ulang jika sudah memiliki tomselect (Turbo membiarkan node lama atau mengembalikan node dari cache)
                if (el.classList.contains('no-tomselect') || el.tomselect) return;
                
                new TomSelect(el, {
                    create: false,
                    sortField: [{field: '$order'}],
                    allowEmptyOption: true,
                    plugins: ['dropdown_input'],
                    onInitialize: function() {
                        const input = this.dropdown_content.parentNode.querySelector('.dropdown-input');
                        if (input) {
                            input.setAttribute('autocomplete', 'off');
                            input.setAttribute('role', 'presentation');
                        }
                    }
                });
            });
        });
    </script>
    
    @include('components.loading-overlay')

    <script>
        // Global Format Rupiah
        function formatRupiah(value) {
            if (!value) return '';
            let number_string = value.toString().replace(/[^,\d]/g, '').toString(),
                split         = number_string.split(','),
                sisa          = split[0].length % 3,
                rupiah        = split[0].substr(0, sisa),
                ribuan        = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

        // Event delegation untuk format rupiah saat input
        document.addEventListener('input', function(e) {
            if (e.target && e.target.matches('input.nominal-format, input.nominal-input, input[name="nominal"], input[name="biaya_pendaftaran"]')) {
                if (e.target.type === 'number') {
                    e.target.type = 'text';
                    e.target.setAttribute('inputmode', 'numeric');
                }
                e.target.value = formatRupiah(e.target.value);
            }
        });

        // Hapus titik sebelum form disubmit
        document.addEventListener('submit', function(e) {
            if (e.target && e.target.tagName === 'FORM') {
                e.target.querySelectorAll('input.nominal-format, input.nominal-input, input[name="nominal"], input[name="biaya_pendaftaran"]').forEach(input => {
                    input.value = input.value.replace(/\./g, '');
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const mainScrollArea = document.getElementById('main-scroll-area');
            if (mainScrollArea) {
                const scrollKey = 'scrollPosition_' + window.location.pathname + window.location.search;
                const savedScroll = sessionStorage.getItem(scrollKey);
                
                if (savedScroll !== null) {
                    mainScrollArea.scrollTop = parseInt(savedScroll, 10);
                    sessionStorage.removeItem(scrollKey);
                } else {
                    mainScrollArea.scrollTop = 0;
                }

                // Simpan posisi scroll sebelum pindah halaman/reload
                window.addEventListener('beforeunload', function() {
                    sessionStorage.setItem(scrollKey, mainScrollArea.scrollTop);
                });
            }

            // Format nilai awal saat halaman dimuat
            document.querySelectorAll('input.nominal-format, input.nominal-input, input[name="nominal"], input[name="biaya_pendaftaran"]').forEach(input => {
                if(input.type === 'number') {
                    input.type = 'text';
                    input.setAttribute('inputmode', 'numeric');
                }
                if(input.value) {
                    input.value = formatRupiah(input.value);
                }
            });
        });
    </script>
    @include('components.prevent-back-history')
</body>

</html>