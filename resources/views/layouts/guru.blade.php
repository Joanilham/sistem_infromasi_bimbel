<!DOCTYPE html>
<html lang="id" x-data="{ 
    sidebarOpen: window.innerWidth >= 1024
}" @resize.window="if(window.innerWidth < 1024) { sidebarOpen = false; }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-version" content="v1.0.1">
    <title>@yield('title', 'Guru Dashboard') - {{ $masterData->nama_lembaga ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script defer src="{{ asset('vendor/alpinejs/alpine.min.js') }}"></script>
    <script defer src="{{ asset('js/sidebar-scroll.js') }}?v={{ time() }}"></script>
    

    
    <!-- TomSelect CSS -->
    <link href="{{ asset('vendor/tom-select/tom-select.css') }}" rel="stylesheet">
    <style>
        .ts-control { border-radius: 0.75rem !important; border: 1px solid #e2e8f0 !important; padding: 0.625rem 0.875rem !important; font-size: 0.875rem !important; box-shadow: none !important; }
        .ts-dropdown { border-radius: 0.75rem !important; border: 1px solid #e2e8f0 !important; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important; font-size: 0.875rem !important; }
        .ts-dropdown .active { background-color: #EEF2FF !important; color: #4F46E5 !important; }
        .dark .ts-control { background-color: #18181b !important; border-color: #27272a !important; color: #f4f4f5 !important; }
        .dark .ts-dropdown { background-color: #18181b !important; border-color: #27272a !important; color: #f4f4f5 !important; }
        .dark .ts-dropdown .active { background-color: #27272a !important; color: #818cf8 !important; }
        .dark .ts-control input { color: #f4f4f5 !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
</head>
<body class="bg-slate-50 dark:bg-slate-900 flex h-screen overflow-hidden text-slate-800 dark:text-slate-100">

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-gray-900 bg-opacity-50 lg:hidden" @click="sidebarOpen = false" x-cloak></div>

    <!-- Sidebar -->
    <aside 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
        class="fixed z-40 inset-y-0 left-0 w-64 transition-transform duration-300 transform bg-white dark:bg-zinc-900 shadow-2xl lg:shadow-none border-r border-slate-200 dark:border-zinc-800 flex flex-col"
        style="will-change: transform;">
        <div class="flex flex-col h-full custom-scrollbar">
            @include('layouts.guru.sidebar')
        </div>
    </aside>

    <!-- Main Content -->
    <div :class="!sidebarOpen ? 'sidebar-closed' : ''" class="main-content-wrapper flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top Navigation -->
        @include('layouts.guru.header')

        <!-- Main Body Area -->
        <main id="main-scroll-area" class="flex-1 overflow-y-auto bg-slate-50 dark:bg-slate-950 p-4 pb-24 sm:p-6 sm:pb-8 lg:p-8 custom-scrollbar">
            <div class="w-full">
                @if(session('success'))
                <div x-data="{ show: true }" 
                     x-init="setTimeout(() => show = false, 3000)" 
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
                     x-init="setTimeout(() => show = false, 3000)" 
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
                 <div x-data="{ show: true }"
                     x-init="setTimeout(() => show = false, 3000)"
                     x-show="show"
                     x-transition:leave="transition ease-in duration-500"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-4 rounded-xl shadow-sm" role="alert">
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

    <!-- SweetAlert2 -->
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Global Delete Confirmation Script -->
    <script>
        function confirmDelete(title, text, formElement) {
            Swal.fire({
                title: title || 'Apakah Anda yakin?',
                text: text || "Data yang dihapus tidak dapat direstore!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5', // Indigo 600
                cancelButtonColor: '#ef4444', // Red 500
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
                customClass: {
                    popup: 'rounded-2xl border border-slate-100 dark:border-slate-700',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    formElement.submit();
                }
            });
        }
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('ajaxTable', () => ({
                isLoading: false,
                _safetyTimer: null,
                
                fetchData(e) {
                    let form = null;
                    if (e && e.target && e.target.tagName === 'FORM') {
                        form = e.target;
                    } else if (e && e.target && e.target.form) {
                        form = e.target.form;
                    } else {
                        form = this.$el.querySelector('form');
                    }

                    if (!form) return;

                    let url = new URL(form.action || window.location.href);
                    let formData = new FormData(form);
                    
                    url.search = '';
                    for (let [key, value] of formData.entries()) {
                        if (value) url.searchParams.append(key, value);
                    }

                    this.doFetch(url.toString());
                },
                
                navigate(e, urlStr) {
                    if (e) e.preventDefault();
                    this.doFetch(urlStr);
                },

                doFetch(urlStr) {
                    if (this.isLoading) return;
                    this.isLoading = true;

                    clearTimeout(this._safetyTimer);
                    this._safetyTimer = setTimeout(() => {
                        if (this.isLoading) {
                            this.isLoading = false;
                            if (typeof App !== 'undefined' && App.Progress) {
                                App.Progress.fail();
                            }
                        }
                    }, 15000);
                    
                    fetch(urlStr, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('HTTP ' + res.status);
                        return res.text();
                    })
                    .then(html => {
                        let parser = new DOMParser();
                        let doc = parser.parseFromString(html, 'text/html');
                        
                        const updateElement = (id) => {
                            let newEl = doc.getElementById(id);
                            let oldEl = document.getElementById(id);
                            if (newEl && oldEl) {
                                const loadingOverlay = oldEl.querySelector('[x-show="isLoading"]');
                                oldEl.innerHTML = newEl.innerHTML;
                                if (loadingOverlay) {
                                    const staleOverlay = oldEl.querySelector('[x-show="isLoading"]');
                                    if (staleOverlay) staleOverlay.remove();
                                    oldEl.insertBefore(loadingOverlay, oldEl.firstChild);
                                }
                            }
                        };

                        updateElement('ajax-summary-cards');
                        updateElement('ajax-table-body');
                        updateElement('ajax-pagination');
                        
                        window.history.pushState({}, '', urlStr);
                    })
                    .catch(error => {
                        console.error('AJAX Error:', error);
                        if (typeof App !== 'undefined' && App.Toast) {
                            App.Toast.error('Gagal Memuat', 'Terjadi kesalahan saat memuat data. Silakan coba lagi.');
                        }
                    })
                    .finally(() => {
                        clearTimeout(this._safetyTimer);
                        this.isLoading = false;
                        if (typeof App !== 'undefined' && App.Progress) {
                            App.Progress.done();
                        }
                    });
                }
            }));
        });
    </script>

    @stack('scripts')
    
    <!-- TomSelect JS -->
    <script src="{{ asset('vendor/tom-select/tom-select.complete.min.js') }}"></script>
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
            // Scroll to top of main content area on navigation
            const mainScrollArea = document.getElementById('main-scroll-area');
            if (mainScrollArea) {
                mainScrollArea.scrollTop = 0;
            }

            document.querySelectorAll('select').forEach((el) => {
                if (el.classList.contains('no-tomselect') || el.tomselect || el.closest('.ql-toolbar')) return;
                new TomSelect(el, {
                    create: false,
                    sortField: [{field: '$order'}],
                    allowEmptyOption: true,
                    plugins: ['dropdown_input'],
                });
            });

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
    @include('components.prevent-back-history')
</body>
</html>
