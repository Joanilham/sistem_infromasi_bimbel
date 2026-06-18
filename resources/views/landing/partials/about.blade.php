<!-- About Section -->
    <section id="tentang" class="py-16 sm:py-24 bg-white overflow-hidden w-full">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <div class="order-2 lg:order-1 relative" data-aos="fade-right">
                    <div class="bg-slate-50 p-6 sm:p-10 lg:p-14 rounded-3xl sm:rounded-[3rem] border border-slate-100 relative z-10 shadow-xl sm:shadow-2xl shadow-slate-100/50">
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 mb-6 sm:mb-8 flex items-center gap-3 sm:gap-4">
                            <span class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xs sm:text-sm shadow-lg shadow-indigo-100">V</span>
                            Visi Kami
                        </h3>
                        <p class="text-slate-600 italic text-base sm:text-xl leading-relaxed mb-8 sm:mb-12 border-l-4 border-indigo-600 pl-4 sm:pl-8">
                            "Menjadi lembaga pendidikan terdepan yang mengintegrasikan teknologi modern dengan metode pembelajaran efektif."
                        </p>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 mb-6 sm:mb-8 flex items-center gap-3 sm:gap-4">
                            <span class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xs sm:text-sm shadow-lg shadow-indigo-100">M</span>
                            Misi Institusi
                        </h3>
                        <ul class="space-y-4 sm:space-y-6 text-slate-600 font-bold">
                            @foreach(['Fasilitas Pembelajaran Digital','Kurikulum Adaptif Standar Tinggi','Evaluasi Sistem CBT Akurat'] as $index => $misi)
                                <li class="flex items-center gap-4 sm:gap-5">
                                    <div class="bg-white text-indigo-600 font-black rounded-lg sm:rounded-xl w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center flex-shrink-0 text-[10px] sm:text-xs shadow-md border border-slate-100">{{ $index + 1 }}</div>
                                    <span class="text-xs sm:text-sm uppercase tracking-wider">{{ $misi }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="order-1 lg:order-2 text-center lg:text-left" data-aos="fade-left">
                    <span class="bg-indigo-50 text-indigo-700 text-[9px] sm:text-[10px] font-black px-3 py-1.5 sm:px-4 sm:py-2 rounded-full mb-4 sm:mb-6 inline-block uppercase tracking-[0.2em] border border-indigo-100">Profil Institusi</span>
                    <h2 class="text-3xl sm:text-4xl lg:text-6xl font-black text-slate-900 mb-6 sm:mb-8 leading-[1.2] sm:leading-[1.1]">Eksosistem Belajar <span class="text-indigo-600 underline decoration-indigo-200 underline-offset-4 sm:underline-offset-8">Modern</span>.</h2>
                    <p class="text-slate-500 text-sm sm:text-lg leading-relaxed mb-8 sm:mb-12 font-medium max-w-2xl mx-auto lg:mx-0">
                        {{ $masterData->tentang_kami ?? 'Kami adalah institusi pendidikan yang berdedikasi tinggi dalam menyediakan bimbingan belajar berkualitas dengan teknologi informasi terkini.' }}
                    </p>
                    <a href="{{ route('daftar.step1') }}" class="inline-flex items-center justify-center gap-3 sm:gap-5 bg-indigo-600 text-white font-black px-6 py-3.5 sm:px-10 sm:py-5 rounded-xl sm:rounded-2xl hover:bg-indigo-700 transition-all shadow-xl sm:shadow-2xl shadow-indigo-200 active:scale-95 group text-sm sm:text-base">
                        Mulai Bergabung
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 group-hover:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
