<!-- About Section: Split Editorial Institutional Narrative -->
<section id="tentang" class="py-20 sm:py-28 bg-[#FAF8F5] border-b border-[#E7E2D9] relative w-full">
    <div class="w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
            
            <!-- Left Column: Large Editorial Statement & Vision -->
            <div class="lg:col-span-6" data-aos="fade-up">
                <span class="text-xs font-mono uppercase tracking-widest text-[#78716C] font-semibold block mb-4">
                    [ TENTANG {{ strtoupper($masterData->nama_lembaga ?? 'NIVORA') }} ]
                </span>

                <h2 class="text-3xl sm:text-5xl lg:text-[3.5rem] font-extrabold text-[#141413] tracking-tight leading-[1.12] mb-8">
                    Setiap siswa memiliki cara untuk berkembang.
                </h2>

                <!-- Vision Quote -->
                <div class="p-6 sm:p-8 rounded-2xl bg-white border border-[#E7E2D9] shadow-xs mb-8">
                    <div class="text-[11px] font-mono text-[#E14D2A] uppercase tracking-wider font-bold mb-3">
                        Visi Institusi
                    </div>
                    <blockquote class="text-base sm:text-lg text-[#141413] font-medium leading-relaxed border-l-2 border-[#E14D2A] pl-4 italic">
                        "Menjadi lembaga pendidikan terdepan yang mengintegrasikan keunggulan materi akademik dengan teknologi pembelajaran mutakhir untuk melahirkan generasi berprestasi."
                    </blockquote>
                </div>

                <div class="pt-6 border-t border-[#E7E2D9] flex items-center justify-between text-xs font-mono text-[#78716C]">
                    <span>Fokus: SNBT • Kedinasan • Olimpiade</span>
                    <span class="text-[#141413] font-bold">Terakreditasi Akademik</span>
                </div>
            </div>

            <!-- Right Column: Institutional Story & Numbered Missions -->
            <div class="lg:col-span-6 space-y-8" data-aos="fade-up" data-aos-delay="150">
                <div>
                    <span class="text-xs font-mono uppercase tracking-widest text-[#78716C] font-semibold block mb-3">
                        FILOSOFI PEMBELAJARAN
                    </span>
                    <p class="text-[#57534E] text-base sm:text-lg leading-relaxed mb-6 font-normal">
                        {{ $masterData->tentang_kami ?? 'Nivora berdedikasi menyediakan bimbingan belajar terpercaya yang menggabungkan kedalaman materi konseptual, kurikulum adaptif, dan ekosistem evaluasi transparan. Kami percaya bahwa persiapan akademik bukan sekadar menghafal rumus, melainkan mengasah logika berpikir kritis.' }}
                    </p>
                    <p class="text-[#78716C] text-sm sm:text-base leading-relaxed font-normal">
                        Melalui pendampingan intensif dari mentor berdedikasi serta simulasi ujian berkala berbasis Computer Based Test (CBT), siswa dilatih memiliki ketahanan mental, akurasi pengerjaan, dan rasa percaya diri yang kokoh saat menghadapi seleksi target mereka.
                    </p>
                </div>

                <!-- Numbered Mission Points -->
                <div class="pt-6 border-t border-[#E7E2D9]">
                    <div class="text-xs font-mono uppercase tracking-widest text-[#141413] font-bold mb-6">
                        MISI STRATEGIS LEMBAGA
                    </div>

                    <div class="space-y-4">
                        @foreach([
                            'Penyediaan modul adaptif dan kurikulum terstruktur berbasis target ujian nasional dan kedinasan.',
                            'Pelaksanaan evaluasi berkala dan simulasi CBT dengan akurasi penilaian respons butir (IRT).',
                            'Pendampingan belajar intensif dan konsultasi strategi pemilihan jurusan oleh tim pengajar profesional.',
                            'Membangun ekosistem akademik yang transparan, disiplin, dan berorientasi pada integritas siswa.'
                        ] as $index => $misi)
                            <div class="flex items-start gap-4 p-4 rounded-xl bg-white border border-[#E7E2D9]">
                                <span class="font-mono text-xs font-bold text-[#E14D2A] bg-[#FDF0EB] px-2.5 py-1 rounded border border-[#FCD8CC]">
                                    0{{ $index + 1 }}
                                </span>
                                <p class="text-sm text-[#44403C] leading-relaxed font-medium pt-0.5">
                                    {{ $misi }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>
