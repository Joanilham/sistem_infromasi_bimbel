<!-- About Section -->
<section id="tentang" class="py-20 sm:py-28 bg-white overflow-hidden w-full relative">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <!-- Left Column: Visi & Misi Card -->
            <div class="lg:col-span-6 order-2 lg:order-1 relative" data-aos="fade-right">
                <div class="bg-slate-50/80 p-8 sm:p-12 rounded-[2.5rem] border border-slate-200/80 shadow-xl shadow-slate-200/40 relative z-10">
                    
                    <!-- Visi Section -->
                    <div class="mb-10">
                        <div class="flex items-center gap-3.5 mb-4">
                            <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-amber-500 text-white flex items-center justify-center font-black text-sm shadow-md shadow-orange-500/20">
                                V
                            </span>
                            <h3 class="text-xl font-black text-slate-900">Visi Lembaga</h3>
                        </div>
                        <p class="text-slate-700 italic text-base sm:text-lg leading-relaxed border-l-4 border-orange-500 pl-5 py-1">
                            "Menjadi lembaga pendidikan terdepan yang mengintegrasikan keunggulan materi akademik dengan teknologi pembelajaran mutakhir untuk melahirkan generasi berprestasi."
                        </p>
                    </div>

                    <!-- Misi Section -->
                    <div>
                        <div class="flex items-center gap-3.5 mb-5">
                            <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-amber-500 text-white flex items-center justify-center font-black text-sm shadow-md shadow-orange-500/20">
                                M
                            </span>
                            <h3 class="text-xl font-black text-slate-900">Misi Kami</h3>
                        </div>
                        <ul class="space-y-4 text-slate-700 font-semibold">
                            @foreach([
                                'Penyediaan modul adaptif dan kurikulum terstruktur berbasis target ujian',
                                'Sistem evaluasi berkala dan simulasi CBT dengan akurasi penilaian tinggi',
                                'Pendampingan belajar intensif oleh tim pengajar profesional berdedikasi'
                            ] as $index => $misi)
                                <li class="flex items-start gap-3.5">
                                    <div class="bg-white text-orange-600 font-black rounded-lg w-7 h-7 flex items-center justify-center flex-shrink-0 text-xs shadow-sm border border-slate-200 mt-0.5">
                                        {{ $index + 1 }}
                                    </div>
                                    <span class="text-xs sm:text-sm leading-relaxed text-slate-600 font-medium">
                                        {{ $misi }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>

            <!-- Right Column: Institutional Identity -->
            <div class="lg:col-span-6 order-1 lg:order-2 text-center lg:text-left" data-aos="fade-left">
                <span class="bg-orange-50 border border-orange-200/60 text-orange-600 text-xs font-bold px-3.5 py-1.5 rounded-full mb-6 inline-block uppercase tracking-wider">
                    Tentang Institusi
                </span>
                
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 mb-6 leading-tight tracking-tight">
                    Ekosistem Belajar <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-amber-600">Modern & Terpadu</span>.
                </h2>
                
                <p class="text-slate-600 text-base sm:text-lg leading-relaxed mb-8 font-normal">
                    {{ $masterData->tentang_kami ?? 'Kami adalah institusi pendidikan yang berdedikasi tinggi dalam menyediakan bimbingan belajar terpercaya, menggabungkan kurikulum adaptif dengan teknologi manajemen akademik yang transparan bagi siswa dan orang tua.' }}
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                    <a href="{{ route('daftar.step1') }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-3 bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold px-8 py-4 rounded-2xl transition-all shadow-lg shadow-orange-500/25 active:scale-95 text-base">
                        <span>Daftar Sekarang</span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
