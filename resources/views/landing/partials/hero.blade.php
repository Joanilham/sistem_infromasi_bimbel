<!-- Hero Section -->
<section class="relative pt-28 pb-20 lg:pt-36 lg:pb-28 overflow-hidden min-h-[85vh] flex flex-col justify-center bg-slate-50 text-slate-900 w-full">
    @if($masterData && $masterData->hero_image)
        <!-- Custom Background Image -->
        <div class="absolute inset-0 z-0 w-full h-full">
            <img src="{{ asset('storage/' . $masterData->hero_image) }}" alt="Hero Background" class="w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-slate-950" style="opacity: {{ $masterData->hero_overlay_opacity ?? 0.85 }};"></div>
        </div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full text-white">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                <!-- Left Column (Dark overlay variant) -->
                <div class="lg:col-span-7 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 backdrop-blur-md mb-6 shadow-sm">
                        <span class="flex h-2 w-2 rounded-full bg-orange-400 animate-pulse"></span>
                        <span class="text-xs font-bold tracking-wide text-orange-200 uppercase">
                            Sistem Informasi Akademik Terpadu
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-white leading-[1.15] tracking-tight mb-6">
                        {{ $masterData->hero_title ?? 'Bimbingan Belajar Modern & Terpercaya' }}
                    </h1>

                    <p class="text-slate-200 text-base sm:text-lg lg:text-xl leading-relaxed max-w-2xl mx-auto lg:mx-0 mb-8 sm:mb-10 font-normal">
                        {{ $masterData->hero_subtitle ?? 'Platform bimbingan belajar dan manajemen pendidikan terpadu untuk meraih prestasi terbaik.' }}
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 mb-10">
                        <a href="{{ $masterData->hero_cta_link ?? '#program' }}" 
                           class="w-full sm:w-auto bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold px-8 py-4 rounded-2xl shadow-xl shadow-orange-500/25 transition-all duration-300 active:scale-95 flex items-center justify-center gap-3 text-base">
                            <span>{{ $masterData->hero_cta_text ?? 'Jelajahi Program' }}</span>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </a>
                        <a href="#tentang" 
                           class="w-full sm:w-auto bg-white/10 hover:bg-white/15 border border-white/20 text-white font-bold px-8 py-4 rounded-2xl transition-all duration-300 backdrop-blur-md flex items-center justify-center text-base hover:border-white/30">
                            Tentang Institusi
                        </a>
                    </div>

                    <div class="pt-8 border-t border-white/10 grid grid-cols-3 gap-4 max-w-lg mx-auto lg:mx-0 text-left">
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-white">1,500+</div>
                            <div class="text-xs font-semibold text-slate-400 mt-0.5">Siswa Terdaftar</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-white">98.4%</div>
                            <div class="text-xs font-semibold text-slate-400 mt-0.5">Tingkat Kelulusan</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-amber-400 flex items-center gap-1">
                                <span>4.9</span>
                                <svg class="w-4 h-4 fill-current text-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <div class="text-xs font-semibold text-slate-400 mt-0.5">Rating Kepuasan</div>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Dark overlay variant) -->
                <div class="lg:col-span-5 relative" data-aos="fade-left" data-aos-delay="200">
                    <div class="rounded-[2rem] bg-slate-900/90 backdrop-blur-2xl border border-white/15 p-6 sm:p-8 shadow-2xl">
                        <div class="flex items-center justify-between pb-4 border-b border-white/10">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-300">Live Learning Portal</span>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-[11px] font-bold border border-emerald-500/30">Sistem Aktif</span>
                        </div>
                        <div class="mt-5 space-y-4">
                            <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                                <div class="flex justify-between items-start mb-2.5">
                                    <div>
                                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-orange-400">Simulasi Ujian CBT</span>
                                        <h4 class="text-sm font-bold text-white mt-0.5">Tryout Akbar & Evaluasi</h4>
                                    </div>
                                    <span class="text-xs font-bold text-slate-400 bg-white/10 px-2 py-1 rounded-lg">Real-time</span>
                                </div>
                                <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden">
                                    <div class="bg-gradient-to-r from-orange-500 to-amber-400 h-2 rounded-full" style="width: 82%"></div>
                                </div>
                                <div class="flex justify-between text-[11px] font-medium text-slate-400 mt-2">
                                    <span>82% Materi Teruji</span>
                                    <span class="text-emerald-400 font-bold">Skor: 94 / 100</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- High-Contrast Light Ambient Mesh Hero (Default) -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-orange-200/40 rounded-full blur-[100px]"></div>
            <div class="absolute top-1/4 -right-32 w-96 h-96 bg-amber-200/40 rounded-full blur-[100px]"></div>
            <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-rose-100/50 rounded-full blur-[100px]"></div>
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#0f172a 1px, transparent 1px); background-size: 28px 28px;"></div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                
                <!-- Left Column: Copywriting & CTA -->
                <div class="lg:col-span-7 text-center lg:text-left">
                    <!-- Announcement Badge -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-orange-50 border border-orange-200/80 text-orange-600 text-xs font-bold uppercase tracking-wider mb-6 shadow-sm" data-aos="fade-up">
                        <span class="flex h-2 w-2 rounded-full bg-orange-500 animate-pulse"></span>
                        <span>Sistem Informasi Akademik Terpadu</span>
                    </div>

                    <!-- Main Headline -->
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-slate-900 leading-[1.15] tracking-tight mb-6" data-aos="fade-up" data-aos-delay="100">
                        {{ $masterData->hero_title ?? 'Bimbingan Belajar Modern & Terpercaya' }}
                    </h1>

                    <!-- Subtitle -->
                    <p class="text-slate-600 text-base sm:text-lg lg:text-xl leading-relaxed max-w-2xl mx-auto lg:mx-0 mb-8 sm:mb-10 font-normal" data-aos="fade-up" data-aos-delay="200">
                        {{ $masterData->hero_subtitle ?? 'Platform bimbingan belajar dan manajemen pendidikan terpadu untuk meraih prestasi terbaik.' }}
                    </p>

                    <!-- Dual Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 mb-10" data-aos="fade-up" data-aos-delay="300">
                        <a href="{{ $masterData->hero_cta_link ?? '#program' }}" 
                           class="w-full sm:w-auto bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold px-8 py-4 rounded-2xl shadow-xl shadow-orange-500/25 hover:shadow-orange-500/35 transition-all duration-300 active:scale-95 flex items-center justify-center gap-3 text-base">
                            <span>{{ $masterData->hero_cta_text ?? 'Jelajahi Program' }}</span>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                        <a href="#tentang" 
                           class="w-full sm:w-auto bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold px-8 py-4 rounded-2xl transition-all duration-300 shadow-sm flex items-center justify-center text-base hover:border-slate-300">
                            Tentang Institusi
                        </a>
                    </div>

                    <!-- Trust Metrics Bar -->
                    <div class="pt-8 border-t border-slate-200/80 grid grid-cols-3 gap-4 max-w-lg mx-auto lg:mx-0 text-left" data-aos="fade-up" data-aos-delay="400">
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-900">1,500+</div>
                            <div class="text-xs font-semibold text-slate-500 mt-0.5">Siswa Terdaftar</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-900">98.4%</div>
                            <div class="text-xs font-semibold text-slate-500 mt-0.5">Tingkat Kelulusan</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-amber-500 flex items-center gap-1">
                                <span>4.9</span>
                                <svg class="w-4 h-4 fill-current text-amber-500" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <div class="text-xs font-semibold text-slate-500 mt-0.5">Rating Kepuasan</div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Interactive Ecosystem Showcase Card -->
                <div class="lg:col-span-5 relative" data-aos="fade-left" data-aos-delay="200">
                    <!-- Subtle Glow -->
                    <div class="absolute -inset-1 rounded-[2.5rem] bg-gradient-to-tr from-orange-400/20 to-amber-300/20 blur-xl opacity-75"></div>

                    <!-- Elevated White Card -->
                    <div class="relative rounded-[2rem] bg-white border border-slate-200/80 p-6 sm:p-8 shadow-2xl shadow-slate-200/70 overflow-hidden">
                        
                        <!-- Card Top Bar -->
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Live Learning Portal</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-200/60">
                                Sistem Aktif
                            </span>
                        </div>

                        <!-- Inner Mockup -->
                        <div class="mt-5 space-y-4">
                            <!-- Module Card -->
                            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4 transition-all hover:bg-slate-100/60">
                                <div class="flex justify-between items-start mb-2.5">
                                    <div>
                                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-orange-600">Simulasi Ujian CBT</span>
                                        <h4 class="text-sm font-bold text-slate-900 mt-0.5">Tryout Akbar & Evaluasi</h4>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-500 bg-white border border-slate-200 px-2 py-0.5 rounded-md">Real-time</span>
                                </div>
                                <!-- Progress Bar -->
                                <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                    <div class="bg-gradient-to-r from-orange-500 to-amber-500 h-2 rounded-full" style="width: 82%"></div>
                                </div>
                                <div class="flex justify-between text-[11px] font-semibold text-slate-500 mt-2">
                                    <span>82% Materi Teruji</span>
                                    <span class="text-emerald-600 font-bold">Skor: 94 / 100</span>
                                </div>
                            </div>

                            <!-- Mini Stats Grid -->
                            <div class="grid grid-cols-2 gap-3">
                                <div class="rounded-xl bg-slate-50 border border-slate-100 p-3.5">
                                    <div class="text-[10px] uppercase font-bold text-slate-400">Jadwal Kelas</div>
                                    <div class="text-sm font-extrabold text-slate-900 mt-0.5">Terstruktur</div>
                                    <div class="text-[11px] text-orange-600 font-semibold mt-0.5">Tatap Muka & Daring</div>
                                </div>
                                <div class="rounded-xl bg-slate-50 border border-slate-100 p-3.5">
                                    <div class="text-[10px] uppercase font-bold text-slate-400">Analitik Siswa</div>
                                    <div class="text-sm font-extrabold text-slate-900 mt-0.5">Akurasi Soal</div>
                                    <div class="text-[11px] text-emerald-600 font-semibold mt-0.5">Grafik Kemajuan</div>
                                </div>
                            </div>

                            <!-- Guarantee Badge -->
                            <div class="rounded-xl bg-orange-50/70 border border-orange-200/70 p-3 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-orange-500 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                        ✓
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-900">Kurikulum Terupdate</div>
                                        <div class="text-[10px] text-slate-500">Materi resmi dan terstandarisasi</div>
                                    </div>
                                </div>
                                <a href="{{ route('daftar.step1') }}" class="text-xs font-extrabold text-orange-600 hover:text-orange-700 transition-colors">
                                    Daftar &rarr;
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    @endif
</section>
