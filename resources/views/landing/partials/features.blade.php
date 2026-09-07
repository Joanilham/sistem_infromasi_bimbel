<!-- Academic Advantages (Keunggulan): Asymmetric Editorial Grid -->
<section id="keunggulan" class="py-20 sm:py-28 bg-[#FAF8F5] border-b border-[#E7E2D9] relative w-full">
    <div class="w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
        
        <!-- Section Header -->
        <div class="max-w-3xl mb-14 sm:mb-20" data-aos="fade-up">
            <span class="text-xs font-mono uppercase tracking-widest text-[#78716C] font-semibold block mb-3">
                [ KEUNGGULAN AKADEMIK ]
            </span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#141413] tracking-tight leading-tight mb-4">
                Lebih dari Sekadar Bimbingan Belajar
            </h2>
            <p class="text-[#57534E] text-base sm:text-lg leading-relaxed font-normal">
                Pendekatan terpadu yang memadukan pemahaman konsep mendalam, teknologi simulasi ujian, dan analitik diagnostik untuk memastikan pertumbuhan akademik siswa.
            </p>
        </div>

        @if(isset($features) && $features->count() > 0)
            <!-- Dynamic Asymmetric Grid based on DB -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                @foreach($features as $index => $feature)
                    @if($index === 0)
                        <!-- Large Dominant Feature Block -->
                        <div class="lg:col-span-7 bg-white rounded-2xl p-8 sm:p-10 border border-[#E7E2D9] flex flex-col justify-between shadow-xs" data-aos="fade-up">
                            <div>
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded bg-[#FDF0EB] text-[#E14D2A] text-xs font-mono uppercase tracking-wider mb-6 font-bold">
                                    Pilar Utama 01
                                </div>
                                <h3 class="text-2xl sm:text-3xl font-extrabold text-[#141413] mb-4 leading-snug">
                                    {{ $feature->title }}
                                </h3>
                                <p class="text-[#57534E] text-base sm:text-lg leading-relaxed mb-8">
                                    {{ $feature->description }}
                                </p>
                            </div>
                            
                            <div class="pt-6 border-t border-[#F4EFEA] flex items-center justify-between text-xs font-mono text-[#78716C]">
                                <span>Metodologi Teruji</span>
                                <span class="text-[#E14D2A] font-semibold">Terkalibrasi Standar SNBT</span>
                            </div>
                        </div>
                    @else
                        <!-- Supporting Features Grid -->
                        <div class="lg:col-span-5 bg-white rounded-2xl p-7 sm:p-8 border border-[#E7E2D9] flex flex-col justify-between shadow-xs" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                            <div>
                                <div class="text-[11px] font-mono text-[#78716C] uppercase tracking-wider mb-3">
                                    Keunggulan 0{{ $index + 1 }}
                                </div>
                                <h3 class="text-lg sm:text-xl font-bold text-[#141413] mb-3">
                                    {{ $feature->title }}
                                </h3>
                                <p class="text-sm text-[#57534E] leading-relaxed">
                                    {{ $feature->description }}
                                </p>
                            </div>
                            <div class="pt-4 mt-6 border-t border-[#F4EFEA] flex items-center gap-2 text-xs text-[#78716C]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#E14D2A]"></span>
                                <span>Fasilitas Standar Resmi</span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <!-- Baseline Editorial Asymmetric Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                <!-- Large Dominant Feature Block -->
                <div class="lg:col-span-7 bg-white rounded-2xl p-8 sm:p-10 border border-[#E7E2D9] flex flex-col justify-between shadow-xs" data-aos="fade-up">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded bg-[#FDF0EB] text-[#E14D2A] text-xs font-mono uppercase tracking-wider mb-6 font-bold">
                            Pilar Utama 01
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-[#141413] mb-4 leading-snug">
                            Belajar Berbasis Data & Pemetaan Diagnostik
                        </h3>
                        <p class="text-[#57534E] text-base sm:text-lg leading-relaxed mb-6">
                            Setiap siswa memiliki pola pemahaman yang unik. Sistem kami menganalisis hasil tryout butir per butir menggunakan teori respons butir (IRT), memetakan kelemahan sub-topik secara objektif, dan menyusun rekomendasi latihan terarah.
                        </p>
                        
                        <div class="grid grid-cols-2 gap-4 py-4 mb-6 border-y border-[#F4EFEA]">
                            <div>
                                <div class="font-mono text-xl font-bold text-[#141413]">Item Response</div>
                                <div class="text-xs text-[#78716C] mt-0.5">Penilaian akurat setara SNBT</div>
                            </div>
                            <div>
                                <div class="font-mono text-xl font-bold text-[#141413]">Rekomendasi</div>
                                <div class="text-xs text-[#78716C] mt-0.5">Soal remedial otomatis</div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs font-mono text-[#78716C]">
                        <span>Analitik Terukur</span>
                        <span class="text-[#E14D2A] font-semibold">Tersedia di Portal Siswa</span>
                    </div>
                </div>

                <!-- Supporting Feature 2 -->
                <div class="lg:col-span-5 flex flex-col gap-6">
                    <div class="bg-white rounded-2xl p-7 border border-[#E7E2D9] flex-1 flex flex-col justify-between shadow-xs" data-aos="fade-up" data-aos-delay="100">
                        <div>
                            <div class="text-[11px] font-mono text-[#78716C] uppercase tracking-wider mb-2">Keunggulan 02</div>
                            <h3 class="text-lg font-bold text-[#141413] mb-2">Modul Adaptif & Bank Soal Terstandarisasi</h3>
                            <p class="text-sm text-[#57534E] leading-relaxed">
                                Ribuan butir soal latihan yang terus diperbarui mengikuti perubahan kisi-kisi BSNP, UTBK, dan tes kedinasan tahun berjalan.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-[#F4EFEA] text-xs text-[#78716C] font-mono">
                            Modul Teruji BSNP
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-7 border border-[#E7E2D9] flex-1 flex flex-col justify-between shadow-xs" data-aos="fade-up" data-aos-delay="150">
                        <div>
                            <div class="text-[11px] font-mono text-[#78716C] uppercase tracking-wider mb-2">Keunggulan 03</div>
                            <h3 class="text-lg font-bold text-[#141413] mb-2">Mentor Berpengalaman Lulusan PTN Ternama</h3>
                            <p class="text-sm text-[#57534E] leading-relaxed">
                                Didampingi oleh tenaga pengajar yang menguasai konsep dasar secara mendalam serta strategi pemecahan soal cepat dan logis.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-[#F4EFEA] text-xs text-[#78716C] font-mono">
                            Pendampingan 1-on-1
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</section>
