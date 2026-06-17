<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - {{ $masterData->nama_lembaga ?? 'Genius Education' }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ isset($masterData) && $masterData->logo ? Storage::url($masterData->logo) : asset('favicon.png') }}">

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
        <div class="flex justify-center mb-8">
            <div class="flex items-center gap-3">
                @if(isset($masterData) && $masterData->logo)
                    <img src="{{ Storage::url($masterData->logo) }}" alt="Logo" class="h-12 w-auto object-contain drop-shadow-sm">
                @else
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/20">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                @endif
                <span class="text-2xl font-extrabold tracking-tight text-slate-900">{{ $masterData->nama_lembaga ?? 'Genius Education' }}</span>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8 sm:p-10">
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Selamat datang!</h2>
                <p class="mt-2 text-sm text-slate-500 font-medium leading-relaxed">Masuk ke akun Anda untuk mengakses dashboard.</p>
            </div>

            {{-- Flash Messages --}}
            @if (session('success') || session('status'))
                <div class="mb-6 rounded-xl bg-emerald-50 p-4 border border-emerald-100 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                            <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-emerald-800">{{ session('success') ?? session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 p-4 border border-red-100 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-semibold text-red-800">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div class="input-animated">
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                            class="block w-full rounded-xl border-slate-200 bg-slate-50 pl-11 pr-4 py-3 text-sm text-slate-900 shadow-sm focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition-all duration-200"
                            placeholder="Masukkan email anda">
                    </div>
                </div>

                <div class="input-animated">
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" required
                            class="block w-full rounded-xl border-slate-200 bg-slate-50 pl-11 pr-4 py-3 text-sm text-slate-900 shadow-sm focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition-all duration-200"
                            placeholder="Masukkan kata sandi anda">
                    </div>
                </div>

                <div class="flex items-center pt-1">
                    <input id="show_password" type="checkbox"
                        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-600 transition-colors cursor-pointer"
                        onclick="document.getElementById('password').type = this.checked ? 'text' : 'password'">
                    <label for="show_password" class="ml-2.5 block text-sm font-medium text-slate-600 cursor-pointer select-none">
                        Tampilkan kata sandi
                    </label>
                </div>

                <button type="submit"
                    class="mt-2 flex w-full justify-center rounded-xl bg-slate-900 px-4 py-3.5 text-sm font-bold text-white shadow-lg shadow-slate-900/20 hover:bg-slate-800 hover:shadow-xl hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 transition-all duration-200">
                    Masuk
                </button>
                
                <div class="mt-4 flex justify-center">
                    <a href="{{ route('password.request') }}" class="text-sm font-bold text-blue-600 hover:text-blue-500 transition-colors">
                        Lupa sandi?
                    </a>
                </div>

                <div class="mt-8 text-center text-sm text-slate-600 border-b border-slate-100 pb-6">
                    Belum memiliki akun? 
                    <a href="{{ route('daftar.step1') }}" class="font-bold text-blue-600 hover:text-blue-500 transition-colors ml-1">
                        Daftar di sini
                    </a>
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
            &copy; {{ date('Y') }} Genius Education. All rights reserved.
        </p>

    </div>
    @include('components.loading-overlay')
</body>
</html>