    {{-- ─── Header ─── --}}
    @push('head')
    <style>
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(60px, -60px) scale(1.2); }
            66% { transform: translate(-40px, 40px) scale(0.8); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
    </style>
    @endpush
    
    <div class="relative mb-8 rounded-3xl shadow-xl z-20">
        {{-- Animated gradient background layer (separated to prevent clipping dropdown) --}}
        <div class="absolute inset-0 rounded-3xl overflow-hidden pointer-events-none -z-10">
            <div class="absolute inset-0 bg-gradient-to-br from-rose-600 via-orange-600 to-amber-500 animate-gradient-xy"></div>
            {{-- Animated Blobs --}}
            <div class="absolute -top-20 -left-20 w-80 h-80 rounded-full bg-rose-300/40 blur-3xl animate-blob pointer-events-none"></div>
            <div class="absolute top-0 -right-20 w-96 h-96 rounded-full bg-white/30 blur-3xl animate-blob animation-delay-2000 pointer-events-none"></div>
            <div class="absolute -bottom-32 left-1/3 w-80 h-80 rounded-full bg-amber-200/40 blur-3xl animate-blob animation-delay-4000 pointer-events-none"></div>
            {{-- Dot grid overlay --}}
            <div class="absolute inset-0 opacity-[0.04]"
                style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;">
            </div>
        </div>

        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-px bg-gradient-to-r from-transparent via-white/20 to-transparent pointer-events-none"></div>

        <div class="relative z-10 p-6 sm:p-8 lg:p-10 flex flex-col xl:flex-row xl:items-center justify-between gap-6 sm:gap-8">
            {{-- Greeting --}}
            <div class="flex-1 relative z-20">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-4">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-semibold tracking-widest text-white/90 uppercase">Sistem Aktif</span>
                </div>

                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight mb-2 tracking-tight">
                    Selamat Datang,<br>
                    <span>
                        {{ auth()->user()->name ?? 'Administrator' }}!
                    </span>
                </h1>

                {{-- Sleek Active Context Info Capsule --}}
                <div class="flex flex-wrap items-center gap-3 mt-6">
                    @if(strtolower(auth()->user()->level) === 'super admin')
                    <form action="{{ route('session.konteks') }}" method="POST" id="switch-kantor-form" 
                        class="relative inline-flex items-center gap-2.5 px-4 py-2.5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-xs text-white hover:bg-white/20 transition-colors cursor-pointer"
                        :class="open ? 'z-[60]' : 'z-20'"
                        x-data="{ open: false, selected: '{{ session('kantor_id') ?: 'all' }}', submitForm(val) { this.selected = val; $refs.kantorInput.value = val; $refs.form.submit(); } }" 
                        @click="open = !open" 
                        @click.away="open = false"
                        x-ref="form">
                        @csrf
                        <input type="hidden" name="redirect" value="/dashboard">
                        <input type="hidden" name="periode_id" value="{{ session('periode_id') }}">
                        <input type="hidden" name="kantor_id" x-ref="kantorInput" :value="selected">
                        <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-white/15 text-white shrink-0">
                            <svg class="h-4.5 w-4.5 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </span>
                        <div class="flex flex-col text-left pr-6">
                            <span class="text-[9px] uppercase tracking-widest text-white/80 font-bold leading-none mb-1">Kantor Cabang</span>
                            <span class="text-xs font-bold text-white tracking-tight">
                                @if(session('kantor_id') == 'all' || !session('kantor_id'))
                                    Semua Cabang (Global)
                                @else
                                    {{ $kantors->where('id', session('kantor_id'))->first()->nama_kantor ?? 'Semua Cabang (Global)' }}
                                @endif
                            </span>
                        </div>
                        <span class="absolute right-4 text-white/80">
                            <svg class="h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>

                        {{-- Modern Custom Dropdown Menu --}}
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                             class="absolute left-0 top-[calc(100%+8px)] mt-0 w-72 z-[60] rounded-2xl bg-white/95 backdrop-blur-xl p-2 shadow-2xl border border-white/20 ring-1 ring-black/5 dark:bg-slate-900/95 dark:border-slate-700/50" 
                             style="display: none;">
                            <div class="max-h-60 overflow-y-auto custom-scrollbar space-y-1">
                                <div @click.stop="submitForm('all')"
                                     class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl cursor-pointer transition-colors"
                                     :class="selected == 'all' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                     <div class="w-8 h-8 flex shrink-0 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400">
                                         <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                     </div>
                                     <span class="flex-1 truncate">Semua Cabang (Global)</span>
                                     <svg x-show="selected == 'all'" class="h-4 w-4 text-indigo-600 dark:text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                @foreach($kantors as $kantor)
                                <div @click.stop="submitForm('{{ $kantor->id }}')"
                                     class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl cursor-pointer transition-colors"
                                     :class="selected == '{{ $kantor->id }}' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                     <div class="w-8 h-8 flex shrink-0 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                                         <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                     </div>
                                     <span class="flex-1 truncate">{{ $kantor->nama_kantor }}</span>
                                     <svg x-show="selected == '{{ $kantor->id }}'" class="h-4 w-4 text-indigo-600 dark:text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </form>
                    @else
                    <div class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-xs text-white">
                        <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-white/15 text-white shrink-0">
                            <svg class="h-4.5 w-4.5 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </span>
                        <div class="flex flex-col text-left pr-2">
                            <span class="text-[9px] uppercase tracking-widest text-white/80 font-bold leading-none mb-1">Kantor Cabang</span>
                            <span class="text-xs font-bold text-white tracking-tight">{{ $activeKantorName }}</span>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('session.konteks') }}" method="POST" id="switch-periode-form" 
                        class="relative inline-flex items-center gap-2.5 px-4 py-2.5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-xs text-white hover:bg-white/20 transition-colors cursor-pointer"
                        :class="open ? 'z-[50]' : 'z-10'"
                        x-data="{ open: false, selected: '{{ session('periode_id') }}', submitForm(val) { this.selected = val; $refs.periodeInput.value = val; $refs.form.submit(); } }" 
                        @click="open = !open" 
                        @click.away="open = false"
                        x-ref="form">
                        @csrf
                        <input type="hidden" name="redirect" value="/dashboard">
                        <input type="hidden" name="kantor_id" value="{{ session('kantor_id') ?: 'all' }}">
                        <input type="hidden" name="periode_id" x-ref="periodeInput" :value="selected">
                        <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-white/15 text-white shrink-0">
                            <svg class="h-4.5 w-4.5 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <div class="flex flex-col text-left pr-6">
                            <span class="text-[9px] uppercase tracking-widest text-white/80 font-bold leading-none mb-1">Tahun Ajaran</span>
                            <span class="text-xs font-bold text-white tracking-tight">{{ $activePeriodeYear }}</span>
                        </div>
                        <span class="absolute right-4 text-white/80">
                            <svg class="h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>

                        {{-- Modern Custom Dropdown Menu --}}
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                             class="absolute left-0 top-[calc(100%+8px)] mt-0 w-64 z-[60] rounded-2xl bg-white/95 backdrop-blur-xl p-2 shadow-2xl border border-white/20 ring-1 ring-black/5 dark:bg-slate-900/95 dark:border-slate-700/50" 
                             style="display: none;">
                            <div class="max-h-60 overflow-y-auto custom-scrollbar space-y-1">
                                @foreach($allPeriodes as $periode)
                                <div @click.stop="submitForm('{{ $periode->id }}')"
                                     class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl cursor-pointer transition-colors"
                                     :class="selected == '{{ $periode->id }}' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                     <div class="w-8 h-8 flex shrink-0 items-center justify-center rounded-lg"
                                          :class="selected == '{{ $periode->id }}' ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400'">
                                         <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                         </svg>
                                     </div>
                                     <span class="flex-1 truncate">{{ $periode->tahun_periode }}</span>
                                     <svg x-show="selected == '{{ $periode->id }}'" class="h-4 w-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Real-Time Clock --}}
            <div class="shrink-0 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-5 lg:p-6 min-w-[220px] sm:min-w-[250px] shadow-sm hover:bg-white/15 transition-colors duration-300 text-center text-white">
                <div class="flex items-center justify-center gap-1.5 mb-1.5">
                    <svg class="w-4 h-4 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-white/90" x-text="date">Memuat...</span>
                </div>
                <div class="text-4xl sm:text-5xl font-black tabular-nums tracking-tight drop-shadow-sm" x-text="time">00:00:00</div>
                <div class="mt-2.5 text-[11px] font-medium text-white/80 bg-white/10 rounded-lg py-1 px-2.5" x-text="zonaWaktu">Waktu Lokal</div>
            </div>
        </div>
    </div>

    @include('layouts.admin.pesan-panel')
