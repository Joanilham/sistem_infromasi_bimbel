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
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .input-animated {
            transition: all 0.2s ease-in-out;
        }
        .input-animated:focus-within {
            transform: translateY(-1px);
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
        .blob-1 { background-color: #d8b4fe; top: -5%; left: 15%; }
        .blob-2 { background-color: #93c5fd; top: 15%; right: 15%; animation-delay: 2s; }
        .blob-3 { background-color: #a5b4fc; bottom: -5%; left: 25%; animation-delay: 4s; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 text-slate-900 antialiased selection:bg-blue-200 selection:text-blue-900 overflow-hidden relative">

    <!-- Background Animated Blobs (Pure CSS to ensure they always show) -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none flex items-center justify-center z-0">
        <div class="blob-shape blob-1"></div>
        <div class="blob-shape blob-2"></div>
        <div class="blob-shape blob-3"></div>
    </div>
    
    <div class="w-full max-w-md relative z-10">
        
        <!-- Logo -->
        <div class="flex justify-center mb-8 text-center">
            <div class="flex flex-col items-center gap-4">
                @if(isset($masterData) && $masterData->logo)
                    <img src="{{ Storage::url($masterData->logo) }}" alt="Logo" class="h-20 w-auto object-contain drop-shadow-sm">
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/20">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                @endif
                <span class="text-2xl font-extrabold tracking-tight text-slate-900">{{ $masterData->nama_lembaga ?? config('app.name') }}</span>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8 sm:p-10">
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Selamat datang!</h2>
                <p class="mt-2 text-sm text-slate-500 font-medium leading-relaxed">Masuk ke akun Anda untuk mengakses dashboard.</p>
            </div>

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
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input type="text" id="login" name="login" required autofocus
                            class="input-animated block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 text-sm font-medium placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600"
                            placeholder="Ketik email, username, atau NISN..."
                            value="{{ old('login') }}">
                    </div>
                </div>

                <!-- Password Input -->
                <div x-data="{ showPassword: false }">
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Kata Sandi
                        </label>
                        <a href="{{ route('password.request') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">
                            Lupa sandi?
                        </a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                            class="input-animated block w-full pl-11 pr-11 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 text-sm font-medium placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600"
                            placeholder="••••••••">
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors focus:outline-none">
                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Submit -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-600/20">
                        <span class="ml-2 text-xs font-medium text-slate-600">Ingat saya</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-blue-600/30 hover:shadow-blue-600/40 active:scale-[0.99] transition-all duration-200">
                    Masuk ke Sistem
                </button>

                <div class="mt-6 text-center">
                    <p class="text-sm text-slate-500 font-medium">
                        Belum punya akun? 
                        <a href="{{ route('daftar.step1') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline transition-all">
                            Daftar Siswa Baru
                        </a>
                    </p>
                </div>
                
                <div class="mt-6 flex justify-center">
                    <a href="{{ url('/') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800 flex items-center gap-2 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Halaman Utama
                    </a>
                </div>
            </form>
        </div>

        <p class="mt-8 text-center text-sm font-medium text-slate-500">
            &copy; {{ date('Y') }} {{ $masterData->nama_lembaga ?? config('app.name') }}. All rights reserved.
        </p>

    </div>
    @include('components.loading-overlay')
</body>
</html>