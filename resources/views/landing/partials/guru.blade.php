<!-- Mentor / Teacher Section: Large Editorial Portraits -->
@if(isset($featuredGurus) && $featuredGurus->count() > 0)
<section id="pengajar" class="py-20 sm:py-28 bg-[#FAF8F5] border-b border-[#E7E2D9] relative w-full">
    <div class="w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
        
        <!-- Section Header -->
        <div class="max-w-3xl mb-14 sm:mb-20" data-aos="fade-up">
            <span class="text-xs font-mono uppercase tracking-widest text-[#78716C] font-semibold block mb-3">
                [ TIM PENGAJAR & MENTOR ]
            </span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#141413] tracking-tight leading-tight mb-4">
                Belajar Bersama Mentor yang Memahami Cara Mengajar
            </h2>
            <p class="text-[#57534E] text-base sm:text-lg leading-relaxed font-normal">
                Tenaga pendidik profesional lulusan perguruan tinggi terkemuka dengan rekam jejak dedikasi tinggi dalam membimbing siswa meraih prestasi puncak.
            </p>
        </div>

        <!-- Editorial Portrait Composition Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            @foreach($featuredGurus as $guru)
                <div class="bg-white rounded-2xl overflow-hidden border border-[#E7E2D9] shadow-xs hover:border-[#D5CEC4] transition-all flex flex-col justify-between group" 
                     data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 75 }}">
                    
                    <!-- Portrait Photography Container (Rectangular Aspect Ratio) -->
                    <div class="relative h-72 sm:h-80 overflow-hidden bg-[#1A1918]">
                        @if($guru->foto)
                            <img src="{{ asset('storage/' . $guru->foto) }}" alt="{{ $guru->name }}" class="w-full h-full object-cover object-top group-hover:scale-102 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-[#292524] flex items-center justify-center text-stone-500">
                                <span class="font-mono text-4xl font-bold">{{ substr($guru->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-[#141413]/70 via-transparent to-transparent"></div>
                        
                        <!-- Subject Pill Overlay -->
                        <div class="absolute bottom-4 left-4">
                            <span class="px-2.5 py-1 rounded bg-white/90 backdrop-blur-sm text-[#141413] text-xs font-mono uppercase tracking-wider font-semibold">
                                {{ $guru->matapelajaran ?? 'Pengajar Utama' }}
                            </span>
                        </div>
                    </div>

                    <!-- Details & Identity -->
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="text-lg font-bold text-[#141413] leading-snug">
                                {{ $guru->name }}
                            </h3>
                            <span class="w-4 h-4 rounded-full bg-[#059669] text-white flex items-center justify-center text-[10px] shrink-0" title="Terverifikasi">
                                ✓
                            </span>
                        </div>

                        <p class="text-xs text-[#78716C] leading-relaxed mb-4">
                            {{ $guru->deskripsi ?? 'Spesialis pendampingan materi konsep & pembedahan trik soal ujian mandiri dan SNBT.' }}
                        </p>

                        <div class="pt-3 border-t border-[#F4EFEA] flex items-center justify-between text-[11px] font-mono text-[#78716C]">
                            <span>Status: Aktif Mengajar</span>
                            <span class="text-[#E14D2A] font-semibold">Konsultasi 1-on-1</span>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</section>
@endif
