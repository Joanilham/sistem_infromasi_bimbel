<!-- Hero Section -->
<section class="relative pt-28 pb-20 lg:pt-36 lg:pb-32 overflow-hidden min-h-[90vh] flex flex-col justify-center bg-slate-950 text-white w-full">
    <!-- Dynamic Background or Ambient Lighting -->
    @if($masterData && $masterData->hero_image)
        <div class="absolute inset-0 z-0 w-full h-full">
            <img src="{{ asset('storage/' . $masterData->hero_image) }}" alt="Hero Background" class="w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950/90 via-slate-950/75 to-slate-950" style="opacity: {{ $masterData->hero_overlay_opacity ?? 0.85 }};"></div>
        </div>
    @else
        <!-- Ambient Mesh Background -->
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div class="absolute -top-40 -left-40 w-96 h-96 bg-orange-600/20 rounded-full blur-[128px]"></div>
            <div class="absolute top-1/3 -right-40 w-96 h-96 bg-amber-500/15 rounded-full blur-[128px]"></div>
            <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-rose-600/15 rounded-full blur-[128px]"></div>
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 32px 32px;"></div>
        </div>
    @endif

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            
            <!-- Left Column: Copywriting & CTA -->
            <div class="lg:col-span-7 text-center lg:text-left">
                <!-- Announcement Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 backdrop-blur-md mb-6 shadow-sm" data-aos="fade-up">
                    <span class="flex h-2 w-2 rounded-full bg-orange-400 animate-pulse"></span>
                    <span class="text-xs font-bold tracking-wide text-orange-200 uppercase">
                        Sistem Informasi Pembelajaran Terpadu
                    </span>
                </div>

                <!-- Main Headline -->
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-white leading-[1.15] tracking-tight mb-6" data-aos="fade-up" data-aos-delay="100">
                    {{ $masterData->hero_title ?? 'Wujudkan Impian Akademik Bersama Kami' }}
                </h1>

                <!-- Subtitle -->
                <p class="text-slate-300 text-base sm:text-lg lg:text-xl leading-relaxed max-w-2xl mx-auto lg:mx-0 mb-8 sm:mb-10 font-medium" data-aos="fade-up" data-aos-delay="200">
                    {{ $masterData->hero_subtitle ?? 'Platform bimbingan belajar modern yang mengintegrasikan pendaftaran online, modul adaptif, sistem ujian CBT real-time, dan evaluasi hasil belajar.' }}
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
                       class="w-full sm:w-auto bg-white/10 hover:bg-white/15 border border-white/20 text-white font-bold px-8 py-4 rounded-2xl transition-all duration-300 backdrop-blur-md flex items-center justify-center text-base hover:border-white/30">
                        Tentang Institusi
                    </a>
                </div>

                <!-- Trust Metrics Bar -->
                <div class="pt-8 border-t border-white/10 grid grid-cols-3 gap-4 max-w-lg mx-auto lg:mx-0 text-left" data-aos="fade-up" data-aos-delay="400">
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
                        <div class="text-xs font-semibold text-slate-400 mt-0.5">Rating Siswa</div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Interactive Ecosystem Showcase Card -->
            <div class="lg:col-span-5 relative" data-aos="fade-left" data-aos-delay="200">
                <!-- Outer Glow -->
                <div class="absolute -inset-1 rounded-[2.5rem] bg-gradient-to-tr from-orange-500/30 to-amber-500/20 blur-xl opacity-70"></div>

                <!-- Main Glassmorphic Card -->
                <div class="relative rounded-[2rem] bg-slate-900/80 backdrop-blur-2xl border border-white/15 p-6 sm:p-8 shadow-2xl overflow-hidden">
                    
                    <!-- Card Top Bar -->
                    <div class="flex items-center justify-between pb-5 border-b border-white/10">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full bg-emerald-400 animate-ping"></div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-300">Live Learning Ecosystem</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-[11px] font-bold border border-emerald-500/30">
                            Sistem Aktif
                        </span>
                    </div>

                    <!-- Inner Mockup: Exam / Class Snapshot -->
                    <div class="mt-6 space-y-4">
                        <!-- Module Card -->
                        <div class="rounded-2xl bg-white/5 border border-white/10 p-4 transition-all hover:bg-white/10">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-orange-400">Modul Interaktif</span>
                                    <h4 class="text-sm font-bold text-white mt-0.5">Tryout Akbar & Evaluasi CBT</h4>
                                </div>
                                <span class="text-xs font-bold text-slate-400 bg-white/10 px-2 py-1 rounded-lg">Real-time</span>
                            </div>
                            <!-- Progress Bar -->
                            <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden">
                                <div class="bg-gradient-to-r from-orange-500 to-amber-400 h-2 rounded-full" style="width: 82%"></div>
                            </div>
                            <div class="flex justify-between text-[11px] font-medium text-slate-400 mt-2">
                                <span>82% Materi Terselesaikan</span>
                                <span class="text-emerald-400 font-bold">Skor: 94 / 100</span>
                            </div>
                        </div>

                        <!-- Mini Stats Grid -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-xl bg-white/5 border border-white/10 p-3.5">
                                <div class="text-[10px] uppercase font-bold text-slate-400">Jadwal Kelas</div>
                                <div class="text-sm font-extrabold text-white mt-1">Interaktif & Terjadwal</div>
                                <div class="text-[11px] text-orange-400 font-semibold mt-0.5">Tatap Muka & Daring</div>
                            </div>
                            <div class="rounded-xl bg-white/5 border border-white/10 p-3.5">
                                <div class="text-[10px] uppercase font-bold text-slate-400">CBT Analytics</div>
                                <div class="text-sm font-extrabold text-white mt-1">Analisis Diagnostik</div>
                                <div class="text-[11px] text-emerald-400 font-semibold mt-0.5">Grafik Akurasi Soal</div>
                            </div>
                        </div>

                        <!-- Student Stack Floating Badge -->
                        <div class="rounded-xl bg-gradient-to-r from-orange-500/10 to-amber-500/10 border border-orange-500/20 p-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-orange-500 to-amber-500 flex items-center justify-center text-white font-black text-xs shadow-md">
                                    ✓
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-white">Garansi Kualitas Materi</div>
                                    <div class="text-[10px] text-slate-400">Kurikulum terupdate & teruji</div>
                                </div>
                            </div>
                            <a href="{{ route('daftar.step1') }}" class="text-xs font-extrabold text-orange-400 hover:text-orange-300 transition-colors">
                                Daftar &rarr;
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>
