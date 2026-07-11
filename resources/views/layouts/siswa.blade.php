@php
    $user = Auth::user();
    $pesertaDidik = $user ? $user->pesertaDidik : null;
    $isPending = $user ? strtolower($user->status) !== 'aktif' : false;
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
    <meta name="app-version" content="v1.0.1">
    <title>@yield('title', 'Dashboard Siswa') - Sistem Akademik</title>
    @if(isset($masterData) && $masterData->logo)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $masterData->logo) }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="{{ asset('js/sidebar-scroll.js') }}?v={{ time() }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        {{-- Halaman profil lama masih memakai var(...) --}}
        :root {
            --text-main: #0f172a;
            --text-muted: #64748b;
            --white: #ffffff;
            --border-color: #e2e8f0;
            --bg-body: #f8fafc;
            --primary: #388782;
            --primary-light: #e6f4f2;
        }
        
        /* TomSelect Styles */
        .ts-control { border-radius: 0.75rem !important; border: 1px solid #e2e8f0 !important; padding: 0.625rem 0.875rem !important; font-size: 0.875rem !important; box-shadow: none !important; }
        .ts-dropdown { border-radius: 0.75rem !important; border: 1px solid #e2e8f0 !important; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important; font-size: 0.875rem !important; }
        .ts-dropdown .active { background-color: #EEF2FF !important; color: #4F46E5 !important; }
        .dark .ts-control { background-color: #18181b !important; border-color: #27272a !important; color: #f4f4f5 !important; }
        .dark .ts-dropdown { background-color: #18181b !important; border-color: #27272a !important; color: #f4f4f5 !important; }
        .dark .ts-dropdown .active { background-color: #27272a !important; color: #818cf8 !important; }
        .dark .ts-control input { color: #f4f4f5 !important; }
    </style>
    <!-- TomSelect CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    @stack('head')
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
</head>
<body class="bg-slate-50 dark:bg-slate-900 flex h-screen overflow-hidden text-slate-800 dark:text-slate-100">

    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-gray-900 bg-opacity-50 lg:hidden" @click="sidebarOpen = false" x-cloak></div>

    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed z-40 inset-y-0 left-0 w-64 transition-transform duration-300 transform bg-white dark:bg-zinc-900 shadow-2xl lg:shadow-none border-r border-slate-200 dark:border-zinc-800 flex flex-col"
        style="will-change: transform;">
        <div class="flex flex-col h-full custom-scrollbar">
            @include('layouts.siswa.sidebar')
        </div>
    </aside>

    <div :class="!sidebarOpen ? 'sidebar-closed' : ''" class="main-content-wrapper flex-1 flex flex-col min-w-0 overflow-hidden">
        @include('layouts.siswa.header')

        <main id="main-scroll-area" class="flex-1 overflow-y-scroll bg-slate-50 dark:bg-slate-950 p-4 sm:p-6 lg:p-8 pb-24 lg:pb-8 custom-scrollbar page-enter transition-colors duration-200">
            <div class="max-w-7xl mx-auto">
                @if(session('success'))
                <div x-data="{ show: true }"
                     x-init="setTimeout(() => show = false, 4000)"
                     x-show="show"
                     x-transition:leave="transition ease-in duration-500"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="mb-6 bg-emerald-50 dark:bg-emerald-950/40 dark:border-emerald-800 border border-emerald-200 text-emerald-700 dark:text-emerald-300 px-4 py-4 rounded-xl flex items-center shadow-sm" role="alert">
                    <svg class="w-5 h-5 mr-3 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                     class="mb-6 bg-red-50 dark:bg-red-950/30 dark:border-red-800 border border-red-300 text-red-700 dark:text-red-300 px-4 py-4 rounded-xl flex items-center shadow-sm" role="alert">
                    <svg class="w-5 h-5 mr-3 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="block sm:inline font-medium">{{ session('error') }}</span>
                </div>
                @endif

                @if(session('info'))
                <div x-data="{ show: true }"
                     x-init="setTimeout(() => show = false, 4000)"
                     x-show="show"
                     class="mb-6 bg-sky-50 dark:bg-sky-950/40 dark:border-sky-800 border border-sky-200 text-sky-800 dark:text-sky-200 px-4 py-4 rounded-xl shadow-sm">
                    {{ session('info') }}
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-4 rounded-xl shadow-sm" role="alert">
                    <ul class="list-disc list-inside text-sm">
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

    {{-- Bottom nav mobile (warna mengikuti tema guru / teal) --}}
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 flex justify-around items-center bg-white dark:bg-zinc-900 border-t border-slate-200 dark:border-zinc-800 pt-2 pb-safe pb-[calc(0.5rem+env(safe-area-inset-bottom))] shadow-[0_-4px_20px_rgba(0,0,0,0.06)]">
        @if($pembayaranOverdue)
            <a href="javascript:void(0)" onclick="alert('⚠️ Akses ditangguhkan! Tagihan paket Anda belum dilunasi dan durasi bimbingan hampir habis / terlampaui. Silakan melunasi tagihan di menu Pembayaran.')" class="flex flex-col items-center gap-0.5 px-2 py-1 text-[10px] font-semibold text-slate-400 dark:text-zinc-600 opacity-50 cursor-not-allowed">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                Home
            </a>
        @else
            <a href="{{ route('siswa.dashboard') }}" class="flex flex-col items-center gap-0.5 px-2 py-1 text-[10px] font-semibold {{ request()->routeIs('siswa.dashboard') ? 'text-[#388782]' : 'text-slate-500 dark:text-slate-400' }}">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Home
            </a>
        @endif
        @if(!$isPending)
            @if($pembayaranOverdue)
                <a href="javascript:void(0)" onclick="alert('⚠️ Akses ditangguhkan! Tagihan paket Anda belum dilunasi dan durasi bimbingan hampir habis / terlampaui. Silakan melunasi tagihan di menu Pembayaran.')" class="flex flex-col items-center gap-0.5 px-2 py-1 text-[10px] font-semibold text-slate-400 dark:text-zinc-600 opacity-50 cursor-not-allowed">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Ujian
                </a>
                <a href="javascript:void(0)" onclick="alert('⚠️ Akses ditangguhkan! Tagihan paket Anda belum dilunasi dan durasi bimbingan hampir habis / terlampaui. Silakan melunasi tagihan di menu Pembayaran.')" class="flex flex-col items-center -mt-5 opacity-50 cursor-not-allowed">
                    <span class="w-12 h-12 rounded-full bg-slate-400 dark:bg-zinc-800 text-white flex items-center justify-center shadow-lg ring-4 ring-slate-50 dark:ring-zinc-950">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </span>
                    <span class="text-[10px] font-semibold mt-1 text-slate-450 dark:text-zinc-600">QR</span>
                </a>
                <a href="javascript:void(0)" onclick="alert('⚠️ Akses ditangguhkan! Tagihan paket Anda belum dilunasi dan durasi bimbingan hampir habis / terlampaui. Silakan melunasi tagihan di menu Pembayaran.')" class="flex flex-col items-center gap-0.5 px-2 py-1 text-[10px] font-semibold text-slate-400 dark:text-zinc-600 opacity-50 cursor-not-allowed">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Nilai
                </a>
            @else
                <a href="{{ route('siswa.ujian.index') }}" class="flex flex-col items-center gap-0.5 px-2 py-1 text-[10px] font-semibold {{ request()->routeIs('siswa.ujian.*') ? 'text-[#388782]' : 'text-slate-500 dark:text-slate-400' }}">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Ujian
                </a>
                <a href="{{ route('siswa.qr.show') }}" class="flex flex-col items-center -mt-5">
                    <span class="w-12 h-12 rounded-full bg-[#388782] text-white flex items-center justify-center shadow-lg shadow-[#388782]/35 ring-4 ring-slate-50 dark:ring-zinc-950">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </span>
                    <span class="text-[10px] font-semibold mt-1 text-slate-500 dark:text-slate-400">QR</span>
                </a>
                <a href="{{ route('siswa.hasil.index') }}" class="flex flex-col items-center gap-0.5 px-2 py-1 text-[10px] font-semibold {{ request()->routeIs('siswa.hasil.*') ? 'text-[#388782]' : 'text-slate-500 dark:text-slate-400' }}">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Nilai
                </a>
            @endif
        @endif
        <a href="{{ route('siswa.profile.edit') }}" class="flex flex-col items-center gap-0.5 px-2 py-1 text-[10px] font-semibold {{ request()->routeIs('siswa.profile.*') ? 'text-[#388782]' : 'text-slate-500 dark:text-slate-400' }}">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Profil
        </a>
    </nav>

    @stack('scripts')
    
    <!-- TomSelect JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Scroll to top of main content area on navigation
            const mainScrollArea = document.getElementById('main-scroll-area');
            if (mainScrollArea) {
                mainScrollArea.scrollTop = 0;
            }

            // TomSelect Init
            document.querySelectorAll('select').forEach((el) => {
                if (el.classList.contains('no-tomselect')) return;
                new TomSelect(el, {
                    create: false,
                    sortField: null,
                    plugins: ['dropdown_input'],
                });
            });

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

            // Scroll to top of main content area on navigation
            const mainScrollArea = document.getElementById('main-scroll-area');
            if (mainScrollArea) {
                mainScrollArea.scrollTop = 0;
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
    @include('components.loading-overlay')

</body>
</html>
<div class="grid-cols-12 xl:flex-row"></div>
