<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - {{ $masterData->nama_lembaga ?? config('app.name') }}</title>
    

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="{{ asset('vendor/alpinejs/alpine.min.js') }}"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .input-animated {
            transition: all 0.2s ease-in-out;
        }
        .input-animated:focus-within {
            transform: translateY(-1px);
        }
        /* Fix Chrome autofill background hiding icons or breaking theme */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-text-fill-color: #0f172a !important;
            transition: background-color 5000s ease-in-out 0s;
        }
        /* Animated Blobs */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .blob-shape {
            position: absolute;
            border-radius: 9999px;
            mix-blend-mode: multiply;
            filter: blur(64px);
            opacity: 0.6;
            width: 24rem;
            height: 24rem;
            animation: blob 7s infinite;
        }
        @media (max-width: 640px) {
            .blob-shape {
                width: 18rem;
                height: 18rem;
            }
        }
        .blob-1 { background-color: #fed7aa; top: -5%; left: 15%; }
        .blob-2 { background-color: #e2e8f0; top: 15%; right: 15%; animation-delay: 2s; }
        .blob-3 { background-color: #fecdd3; bottom: -5%; left: 25%; animation-delay: 4s; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center py-8 sm:py-12 px-4 sm:px-6 lg:px-8 text-slate-900 antialiased selection:bg-orange-100 selection:text-orange-900 overflow-x-hidden relative">

    <!-- Background Animated Blobs (Pure CSS to ensure they always show) -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none flex items-center justify-center z-0">
        <div class="blob-shape blob-1"></div>
        <div class="blob-shape blob-2"></div>
        <div class="blob-shape blob-3"></div>
    </div>
    
    <div class="w-full max-w-md relative z-10 my-auto">
        
        <!-- Brand Header -->
        <div class="flex flex-col items-center mb-6 text-center">
            <a href="{{ url('/') }}" class="group flex flex-col items-center focus:outline-none">
                <div class="h-16 w-16 mb-3 rounded-2xl bg-white shadow-xl shadow-slate-200/70 ring-1 ring-slate-900/5 flex items-center justify-center p-2.5 transition-all duration-300 group-hover:scale-105 group-hover:shadow-2xl group-hover:shadow-orange-500/10">
                    @if(isset($masterData) && $masterData->logo)
                        <img src="{{ Storage::url($masterData->logo) }}" alt="{{ $masterData->nama_lembaga ?? config('app.name') }}" class="h-full w-full object-contain">
                    @else
                        <div class="h-full w-full rounded-xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center text-white shadow-sm">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    @endif
                </div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">
                    {{ $masterData->nama_lembaga ?? config('app.name') }}
                </h1>
                <p class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">
                    Portal Informasi & Manajemen Akademik
                </p>
            </a>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-6 sm:p-10">
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Selamat datang!</h2>
                <p class="mt-2 text-sm text-slate-500 font-medium leading-relaxed">Masuk ke akun Anda untuk mengakses dashboard.</p>
            </div>

            @auth
            <!-- Notifikasi Sesi Aktif -->
            <div class="mb-6 p-3.5 bg-orange-50/90 border border-orange-200/90 rounded-2xl flex items-center justify-between gap-3 text-sm">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-xl bg-orange-500 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="truncate text-left">
                        <p class="text-[11px] text-slate-500 font-medium">Sesi aktif:</p>
                        <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                    </div>
                </div>
                <a href="{{ url('/dashboard') }}" class="px-3 py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors shrink-0 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
            </div>
            @endauth

            {{-- Flash Messages --}}
            @if(session('success') || session('status'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-3 text-sm font-medium">
                <svg class="h-5 w-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') ?? session('status') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl flex items-center gap-3 text-sm font-medium">
                <svg class="h-5 w-5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5" onsubmit="window.showLoading()">
                @csrf

                <!-- Login Input (Email / Username / NISN) -->
                <div>
                    <label for="login" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Email / Username / NISN
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10 text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="text" id="login" name="login" required autofocus autocomplete="username"
                            class="input-animated block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 text-sm font-medium placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all"
                            placeholder="Ketik email, username, atau NISN..."
                            value="{{ old('login', old('email')) }}">
                    </div>
                </div>

                <!-- Password Input -->
                <div x-data="{ showPassword: false }">
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Kata Sandi
                        </label>
                        <a href="{{ route('password.request') }}" class="text-xs font-bold text-orange-600 hover:text-orange-700 transition-colors">
                            Lupa sandi?
                        </a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10 text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" :type="showPassword ? 'text' : 'password'" id="password" name="password" required autocomplete="current-password"
                            class="input-animated block w-full pl-11 pr-11 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 text-sm font-medium placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all"
                            placeholder="••••••••">
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors focus:outline-none cursor-pointer z-10"
                            aria-label="Toggle password visibility">
                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Submit -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-orange-600 focus:ring-orange-500/20">
                        <span class="ml-2 text-xs font-medium text-slate-600">Ingat saya</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-3.5 px-4 bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-orange-500/25 hover:shadow-orange-500/35 active:scale-[0.99] transition-all duration-200 cursor-pointer">
                    Masuk ke Sistem
                </button>

                <!-- Menu Pendaftaran & Navigasi -->
                <div class="mt-6 pt-5 border-t border-slate-100 text-center space-y-3">
                    <p class="text-sm text-slate-600 font-medium">
                        Belum punya akun? 
                        <a href="{{ route('daftar.step1') }}" class="font-bold text-orange-600 hover:text-orange-700 hover:underline transition-all inline-flex items-center gap-1 ml-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                            Daftar Siswa Baru
                        </a>
                    </p>

                    <div class="flex flex-wrap items-center justify-center gap-3 pt-2 text-xs font-semibold text-slate-500">
                        @auth
                        <a href="{{ url('/dashboard') }}" class="hover:text-orange-600 flex items-center gap-1.5 transition-colors">
                            <svg class="h-3.5 w-3.5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Kembali ke Dashboard
                        </a>
                        <span class="text-slate-300">•</span>
                        @endauth

                        <a href="{{ url('/') }}" class="hover:text-slate-800 flex items-center gap-1.5 transition-colors">
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Halaman Utama
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <p class="mt-6 text-center text-xs font-medium text-slate-400 pb-6">
            &copy; {{ date('Y') }} {{ $masterData->nama_lembaga ?? config('app.name') }}. All rights reserved.
        </p>

    </div>
    @include('components.loading-overlay')
</body>
</html>