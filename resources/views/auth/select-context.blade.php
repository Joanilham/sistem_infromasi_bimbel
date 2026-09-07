<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Ruang Kerja - {{ $masterData->nama_lembaga ?? config('app.name') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
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
        .blob-1 { background-color: #fed7aa; top: -5%; left: 15%; }
        .blob-2 { background-color: #e2e8f0; top: 15%; right: 15%; animation-delay: 2s; }
        .blob-3 { background-color: #fecdd3; bottom: -5%; left: 25%; animation-delay: 4s; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
        
        .dropdown-glass {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 text-slate-900 antialiased selection:bg-orange-100 selection:text-orange-900 overflow-hidden relative">
    
    <!-- Background Animated Blobs (Warm Palette identical to login) -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none flex items-center justify-center z-0">
        <div class="blob-shape blob-1"></div>
        <div class="blob-shape blob-2"></div>
        <div class="blob-shape blob-3"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        
        <!-- Brand Header -->
        <div class="flex flex-col items-center mb-8 text-center">
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
                    Pilihan Ruang Kerja
                </p>
            </a>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8 sm:p-10">
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Pilih Konteks</h2>
                <p class="mt-2 text-sm text-slate-500 font-medium leading-relaxed">
                    Masuk sebagai <span class="font-bold text-slate-800">{{ Auth::user()->name ?? 'User' }}</span>. Silakan pilih ruang kerja.
                </p>
            </div>

            {{-- Flash Messages --}}
            @if (session('error'))
                <div class="mb-6 rounded-2xl bg-red-50 p-4 border border-red-200 shadow-sm flex items-start gap-3">
                    <svg class="h-5 w-5 text-red-500 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            @php
                $currentKantorId = session('kantor_id') ?: (auth()->user()->kantor_id ?: (\App\Models\MasterData\Kantor::first()->id ?? ''));
                $currentKantor = \App\Models\MasterData\Kantor::find($currentKantorId);
                $currentKantorLabel = $currentKantor ? $currentKantor->nama_kantor : 'Pilih kantor cabang';

                $activePeriode = \App\Models\MasterData\Periode::where('is_active', true)->first();
                $currentPeriodeId = session('periode_id') ?: ($activePeriode ? $activePeriode->id : (\App\Models\MasterData\Periode::first()->id ?? ''));
                $currentPeriode = \App\Models\MasterData\Periode::find($currentPeriodeId);
                $currentPeriodeLabel = $currentPeriode ? ($currentPeriode->tahun_periode . ' - ' . ucfirst($currentPeriode->semester ?? '')) : 'Pilih tahun ajaran';
            @endphp

            <form action="{{ route('session.konteks') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="redirect" value="/dashboard">

                @if(in_array(strtolower(auth()->user()->level), ['super admin', 'admin']))
                    <div class="space-y-1.5" x-data="{ open: false, selected: '{{ $currentKantorId }}', selectedLabel: '{{ $currentKantorLabel }}' }">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Kantor Cabang</label>
                        <div class="relative">
                            <button type="button" @click="open = !open" @click.away="open = false"
                                class="relative w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-10 py-3.5 text-left text-sm text-slate-900 shadow-xs focus:border-orange-500 focus:bg-white focus:ring-2 focus:ring-orange-500/20 transition-all duration-200 cursor-pointer">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </span>
                                <span x-text="selectedLabel" :class="selected === '' ? 'text-slate-400' : 'text-slate-900 font-semibold'">Pilih kantor cabang</span>
                                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                    <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </span>
                            </button>

                            <input type="hidden" name="kantor_id" :value="selected" required>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute z-50 mt-2 w-full rounded-2xl dropdown-glass p-2 shadow-2xl border border-slate-100 ring-1 ring-black/5 overflow-hidden focus:outline-none" style="display: none;">
                                <div class="max-h-60 overflow-y-auto custom-scrollbar space-y-1">
                                    <div @click="selected = 'all'; selectedLabel = 'Semua Cabang (Akses Global)'; open = false"
                                         class="group flex items-center gap-3.5 px-3.5 py-3 text-sm rounded-xl cursor-pointer transition-all duration-200"
                                         :class="selected == 'all' ? 'bg-gradient-to-r from-orange-500 to-amber-600 text-white shadow-md shadow-orange-500/20' : 'text-slate-600 hover:bg-orange-50/70 hover:text-slate-900'">
                                         <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-slate-100 bg-white/50 shadow-xs transition-all duration-200"
                                              :class="selected == 'all' ? 'bg-white/20 border-white/20 text-white' : 'group-hover:border-orange-200 group-hover:bg-white text-slate-500'">
                                             <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                             </svg>
                                         </div>
                                         <div class="flex flex-col">
                                             <span class="font-bold text-xs sm:text-sm">Semua Cabang (Akses Global)</span>
                                             <span class="text-[10px] opacity-75" :class="selected == 'all' ? 'text-white' : 'text-slate-400'">Akses semua data secara menyeluruh</span>
                                         </div>
                                         <span x-show="selected == 'all'" class="ml-auto" x-transition>
                                             <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                 <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                             </svg>
                                         </span>
                                    </div>

                                    @foreach($kantors as $kantor)
                                        <div @click="selected = '{{ $kantor->id }}'; selectedLabel = '{{ $kantor->nama_kantor }}'; open = false"
                                             class="group flex items-center gap-3.5 px-3.5 py-3 text-sm rounded-xl cursor-pointer transition-all duration-200"
                                             :class="selected == '{{ $kantor->id }}' ? 'bg-gradient-to-r from-orange-500 to-amber-600 text-white shadow-md shadow-orange-500/20' : 'text-slate-600 hover:bg-orange-50/70 hover:text-slate-900'">
                                             <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-slate-100 bg-white/50 shadow-xs transition-all duration-200"
                                                  :class="selected == '{{ $kantor->id }}' ? 'bg-white/20 border-white/20 text-white' : 'group-hover:border-orange-200 group-hover:bg-white text-slate-500'">
                                                 <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                 </svg>
                                             </div>
                                             <div class="flex flex-col">
                                                 <span class="font-bold text-xs sm:text-sm">{{ $kantor->nama_kantor }}</span>
                                                 <span class="text-[10px] opacity-75" :class="selected == '{{ $kantor->id }}' ? 'text-white' : 'text-slate-400'">Klik untuk memilih unit cabang ini</span>
                                             </div>
                                             <span x-show="selected == '{{ $kantor->id }}'" class="ml-auto" x-transition>
                                                 <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                     <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                 </svg>
                                             </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Kantor Cabang</label>
                        <div class="relative flex items-center w-full rounded-2xl border border-slate-200 bg-slate-100/70 px-4 py-3.5 text-sm text-slate-500 shadow-xs cursor-not-allowed">
                            <span class="mr-3">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </span>
                            <span class="font-bold text-slate-700">{{ $kantors->first()->nama_kantor ?? 'Tidak terasosiasi' }}</span>
                            <span class="ml-auto text-[10px] bg-slate-200 text-slate-600 px-2 py-0.5 rounded-md font-bold uppercase tracking-widest">Locked</span>
                        </div>
                        <input type="hidden" name="kantor_id" value="{{ $kantors->first()->id ?? '' }}">
                    </div>
                @endif

                <div class="space-y-1.5" x-data="{ open: false, selected: '{{ $currentPeriodeId }}', selectedLabel: '{{ $currentPeriodeLabel }}' }">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Tahun Ajaran</label>
                    <div class="relative">
                        <button type="button" @click="open = !open" @click.away="open = false"
                            class="relative w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-10 py-3.5 text-left text-sm text-slate-900 shadow-xs focus:border-orange-500 focus:bg-white focus:ring-2 focus:ring-orange-500/20 transition-all duration-200 cursor-pointer">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <span x-text="selectedLabel" :class="selected === '' ? 'text-slate-400' : 'text-slate-900 font-semibold'">Pilih tahun ajaran</span>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </span>
                        </button>

                        <input type="hidden" name="periode_id" :value="selected" required>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute z-50 mt-2 w-full rounded-2xl dropdown-glass p-2 shadow-2xl border border-slate-100 ring-1 ring-black/5 overflow-hidden focus:outline-none" style="display: none;">
                            <div class="max-h-60 overflow-y-auto custom-scrollbar space-y-1">
                                @foreach($periodes as $periode)
                                    <div @click="selected = '{{ $periode->id }}'; selectedLabel = '{{ $periode->tahun_periode }} - {{ ucfirst($periode->semester ?? '') }}'; open = false"
                                         class="group flex items-center gap-3.5 px-3.5 py-3 text-sm rounded-xl cursor-pointer transition-all duration-200"
                                         :class="selected == '{{ $periode->id }}' ? 'bg-gradient-to-r from-orange-500 to-amber-600 text-white shadow-md shadow-orange-500/20' : 'text-slate-600 hover:bg-orange-50/70 hover:text-slate-900'">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-slate-100 bg-white/50 shadow-xs transition-all duration-200"
                                             :class="selected == '{{ $periode->id }}' ? 'bg-white/20 border-white/20 text-white' : 'group-hover:border-orange-200 group-hover:bg-white text-slate-500'">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-xs sm:text-sm">{{ $periode->tahun_periode }}</span>
                                            <span class="text-[10px] opacity-75" :class="selected == '{{ $periode->id }}' ? 'text-white' : 'text-slate-400'">Semester {{ ucfirst($periode->semester ?? '') }}</span>
                                        </div>
                                        <span x-show="selected == '{{ $periode->id }}'" class="ml-auto" x-transition>
                                            <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-3.5 px-4 bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-orange-500/25 hover:shadow-orange-500/35 active:scale-[0.99] transition-all duration-200 mt-2 cursor-pointer">
                    Lanjutkan ke Dashboard
                </button>
            </form>
            
            <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-center">
                <form method="POST" action="{{ route('logout', ['redirect' => 'login']) }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-orange-600 transition-colors cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Masuk dengan akun lain
                    </button>
                </form>
            </div>
        </div>

        <p class="mt-8 text-center text-sm font-medium text-slate-500">
            &copy; {{ date('Y') }} {{ $masterData->nama_lembaga ?? config('app.name') }}. All rights reserved.
        </p>

    </div>
    @include('components.loading-overlay')
</body>
</html>
