<!-- Programs Section: Educational Tracks & Packages -->
<section id="program" class="py-20 sm:py-28 bg-[#FAF8F5] border-b border-[#E7E2D9] relative w-full">
    <div class="w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
        
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 sm:mb-16 gap-6" data-aos="fade-up">
            <div class="max-w-2xl">
                <span class="text-xs font-mono uppercase tracking-widest text-[#78716C] font-semibold block mb-3">
                    [ PILIHAN PROGRAM BELAJAR ]
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#141413] tracking-tight leading-tight mb-4">
                    Program yang Dirancang untuk Setiap Tahap Belajar
                </h2>
                <p class="text-[#57534E] text-base sm:text-lg leading-relaxed font-normal">
                    Pilih paket bimbingan yang disesuaikan dengan target akademik, jenjang pendidikan, dan strategi persiapan ujian Anda.
                </p>
            </div>
            <div class="hidden md:block shrink-0">
                <a href="{{ route('paket.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#E14D2A] hover:text-[#C93B1A] transition-colors group">
                    <span>Lihat Semua Program</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>

        <style>
            .program-card { min-width: 86vw; }
            @media (min-width: 768px) { .program-card { min-width: 45vw; } }
            @media (min-width: 1024px) { .program-card { min-width: 32%; } }
            .program-slider.active { cursor: grabbing; cursor: -webkit-grabbing; }
            .program-slider:not(.active) { cursor: grab; cursor: -webkit-grab; }
        </style>

        <!-- Horizontal Swipe Carousel -->
        <div id="programSlider" class="program-slider flex overflow-x-auto gap-6 sm:gap-8 snap-x snap-mandatory pb-8 custom-scrollbar">
            @forelse($pakets as $paket)
                @php
                    $isPopular = !empty($paket->label_populer);
                @endphp
                <div class="program-card flex-shrink-0 snap-center bg-white rounded-2xl overflow-hidden border {{ $isPopular ? 'border-[#E14D2A] ring-1 ring-[#E14D2A]/20' : 'border-[#E7E2D9]' }} shadow-xs hover:border-[#D5CEC4] transition-all duration-300 flex flex-col justify-between" 
                     data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 75 }}">

                    <div>
                        <!-- Cover Image -->
                        <div class="relative h-48 sm:h-52 overflow-hidden bg-[#141413]">
                            @if($paket->gambar_paket)
                                <img src="{{ asset('storage/' . $paket->gambar_paket) }}" alt="{{ $paket->nama_paket }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-[#1A1918] flex items-center justify-center text-[#78716C]">
                                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-[#141413]/80 via-transparent to-transparent"></div>
                            
                            <!-- Badges -->
                            <div class="absolute top-4 left-4 right-4 flex items-center justify-between">
                                <span class="px-2.5 py-1 rounded bg-[#141413]/80 backdrop-blur-sm text-white text-[11px] font-mono uppercase tracking-wider">
                                    {{ $paket->target_peserta ?? 'Semua Jenjang' }}
                                </span>
                                @if($isPopular)
                                    <span class="px-2.5 py-1 rounded bg-[#E14D2A] text-white text-[11px] font-mono uppercase tracking-wider font-bold shadow-xs">
                                        {{ $paket->label_populer }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-6 sm:p-7">
                            <h3 class="text-xl font-bold text-[#141413] mb-3 leading-snug">
                                {{ $paket->nama_paket }}
                            </h3>

                            <p class="text-sm text-[#57534E] leading-relaxed mb-6 font-normal">
                                {{ $paket->deskripsi ?? 'Pendampingan belajar intensif dengan kurikulum adaptif, simulasi ujian, dan bimbingan mentor berpengalaman.' }}
                            </p>

                            <!-- Facility / Benefits Checklist -->
                            <div class="space-y-2.5 pt-4 border-t border-[#F4EFEA] mb-6">
                                <div class="text-[11px] font-mono uppercase tracking-wider text-[#78716C] mb-2 font-semibold">Fasilitas Utama:</div>
                                @php 
                                    $benefits = explode("\n", str_replace("\r", "", $paket->benefits ?? ''));
                                    $topBenefits = array_slice(array_filter($benefits), 0, 4);
                                @endphp
                                @forelse($topBenefits as $benefit)
                                    <div class="flex items-start gap-2.5 text-xs text-[#44403C]">
                                        <svg class="w-4 h-4 text-[#E14D2A] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="leading-relaxed">{{ trim($benefit) }}</span>
                                    </div>
                                @empty
                                    <div class="flex items-start gap-2.5 text-xs text-[#44403C]">
                                        <svg class="w-4 h-4 text-[#E14D2A] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        <span>Modul materi adaptif & bank soal terstandarisasi</span>
                                    </div>
                                    <div class="flex items-start gap-2.5 text-xs text-[#44403C]">
                                        <svg class="w-4 h-4 text-[#E14D2A] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        <span>Simulasi CBT berkala & pembahasan evaluasi</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Price & Action Footer -->
                    <div class="p-6 sm:p-7 pt-0 border-t border-[#F4EFEA] mt-auto">
                        <div class="flex items-baseline justify-between mb-4 pt-4">
                            <div>
                                <span class="text-[11px] font-mono text-[#78716C] uppercase tracking-wider block">Biaya Investasi</span>
                                <span class="text-2xl font-extrabold text-[#141413] tracking-tight">
                                    Rp {{ number_format($paket->nominal, 0, ',', '.') }}
                                </span>
                            </div>
                            <span class="text-xs text-[#78716C] font-mono">Per Program</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('paket.detail', $paket->id) }}" 
                               class="w-full bg-[#141413] hover:bg-[#292524] text-white font-semibold py-3 rounded-lg text-sm transition-colors text-center">
                                Detail & Silabus
                            </a>
                            <a href="{{ route('daftar.step1', ['paket_id' => $paket->id]) }}" 
                               class="w-full bg-[#E14D2A] hover:bg-[#C93B1A] text-white font-semibold py-3 rounded-lg text-sm transition-colors text-center shadow-xs">
                                Daftar Paket
                            </a>
                        </div>
                    </div>

                </div>
            @empty
                <div class="w-full text-center py-16 bg-white rounded-2xl border border-dashed border-[#E7E2D9]">
                    <p class="text-sm font-medium text-[#78716C]">Belum ada paket bimbingan yang aktif untuk saat ini.</p>
                </div>
            @endforelse
        </div>

        <!-- Mobile Secondary Link -->
        <div class="mt-8 text-center md:hidden">
            <a href="{{ route('paket.index') }}" class="inline-flex items-center justify-center w-full py-3.5 px-6 rounded-lg bg-white border border-[#E7E2D9] text-[#141413] font-semibold text-sm">
                Lihat Semua Program &rarr;
            </a>
        </div>

    </div>
</section>

<!-- Drag and Scroll Logic for Horizontal Slider -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const slider = document.getElementById('programSlider');
        if(!slider) return;
        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('active');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('active');
        });

        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('active');
        });

        slider.addEventListener('mousemove', (e) => {
            if(!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 1.5;
            slider.scrollLeft = scrollLeft - walk;
        });
    });
</script>
