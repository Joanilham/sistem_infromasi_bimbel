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
            <div class="hidden md:flex items-center gap-4 shrink-0">
                <!-- Navigation Arrows -->
                <div class="flex items-center gap-2">
                    <button type="button" 
                            id="prevProgramBtn" 
                            aria-label="Geser ke kiri"
                            title="Geser ke kiri"
                            class="w-10 h-10 rounded-xl border border-[#E7E2D9] bg-white hover:bg-[#F4EFEA] text-[#141413] flex items-center justify-center transition-all shadow-xs disabled:opacity-35 disabled:cursor-not-allowed cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button type="button" 
                            id="nextProgramBtn" 
                            aria-label="Geser ke kanan"
                            title="Geser ke kanan"
                            class="w-10 h-10 rounded-xl border border-[#E7E2D9] bg-white hover:bg-[#F4EFEA] text-[#141413] flex items-center justify-center transition-all shadow-xs disabled:opacity-35 disabled:cursor-not-allowed cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <a href="{{ route('paket.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#E14D2A] hover:text-[#C93B1A] transition-colors ml-1 group">
                    <span>Lihat Semua Program</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>

        <style>
            .program-card { 
                width: 310px; 
                min-width: 310px; 
                max-width: 330px; 
            }
            @media (min-width: 640px) { 
                .program-card { 
                    width: 320px; 
                    min-width: 320px; 
                    max-width: 340px; 
                } 
            }
            @media (max-width: 480px) { 
                .program-card { 
                    width: 80vw; 
                    min-width: 80vw; 
                    max-width: 310px; 
                } 
            }
            .program-slider.active { cursor: grabbing; cursor: -webkit-grabbing; user-select: none; }
            .program-slider:not(.active) { cursor: grab; cursor: -webkit-grab; }
            .program-slider {
                scroll-behavior: auto;
                -webkit-overflow-scrolling: touch;
            }
        </style>

        <!-- Horizontal Swipe Carousel -->
        <div id="programSlider" class="program-slider flex overflow-x-auto gap-5 sm:gap-6 pb-6 custom-scrollbar">
            @forelse($pakets as $paket)
                @php
                    $isPopular = !empty($paket->label_populer);
                @endphp
                <div class="program-card flex-shrink-0 bg-white rounded-2xl overflow-hidden border {{ $isPopular ? 'border-[#E14D2A] ring-1 ring-[#E14D2A]/20' : 'border-[#E7E2D9]' }} shadow-xs hover:border-[#D5CEC4] hover:shadow-lg transition-all duration-300 flex flex-col justify-between" 
                     data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 50 }}">

                    <div>
                        <!-- Cover Image -->
                        <div class="relative h-36 sm:h-40 overflow-hidden bg-[#141413]">
                            @if($paket->gambar_paket)
                                <img src="{{ asset('storage/' . $paket->gambar_paket) }}" alt="{{ $paket->nama_paket }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-[#262422] via-[#1C1A18] to-[#141413] flex items-center justify-center text-[#A8A29E]/60 relative">
                                    <div class="w-11 h-11 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center shadow-inner">
                                        <svg class="w-5 h-5 text-[#E14D2A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-[#141413]/80 via-transparent to-transparent"></div>
                            
                            <!-- Badges -->
                            <div class="absolute top-3 left-3 right-3 flex items-center justify-between">
                                <span class="px-2 py-0.5 rounded bg-[#141413]/85 backdrop-blur-sm text-white text-[10px] font-mono uppercase tracking-wider">
                                    {{ $paket->target_peserta ?? 'Semua Jenjang' }}
                                </span>
                                @if($isPopular)
                                    <span class="px-2 py-0.5 rounded bg-[#E14D2A] text-white text-[10px] font-mono uppercase tracking-wider font-bold shadow-xs">
                                        ✨ {{ $paket->label_populer }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-5 sm:p-5.5 flex flex-col flex-grow">
                            <h3 class="text-base sm:text-lg font-bold text-[#141413] mb-1.5 leading-snug line-clamp-2">
                                {{ $paket->nama_paket }}
                            </h3>

                            <p class="text-xs text-[#57534E] leading-relaxed mb-4 font-normal line-clamp-2">
                                {{ $paket->deskripsi ?? 'Pendampingan belajar intensif dengan kurikulum adaptif, simulasi ujian, dan bimbingan mentor berpengalaman.' }}
                            </p>

                            <!-- Facility / Benefits Checklist -->
                            <div class="space-y-1.5 pt-3 border-t border-[#F4EFEA] mb-4">
                                <div class="text-[10px] font-mono uppercase tracking-wider text-[#78716C] mb-1.5 font-semibold">Fasilitas Utama:</div>
                                @php 
                                    $benefits = explode("\n", str_replace("\r", "", $paket->benefits ?? ''));
                                    $topBenefits = array_slice(array_filter($benefits), 0, 3);
                                @endphp
                                @forelse($topBenefits as $benefit)
                                    <div class="flex items-start gap-2 text-[11px] sm:text-xs text-[#44403C]">
                                        <svg class="w-3.5 h-3.5 text-[#E14D2A] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="leading-relaxed line-clamp-1">{{ trim($benefit) }}</span>
                                    </div>
                                @empty
                                    <div class="flex items-start gap-2 text-[11px] sm:text-xs text-[#44403C]">
                                        <svg class="w-3.5 h-3.5 text-[#E14D2A] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        <span class="line-clamp-1">Modul materi adaptif & bank soal</span>
                                    </div>
                                    <div class="flex items-start gap-2 text-[11px] sm:text-xs text-[#44403C]">
                                        <svg class="w-3.5 h-3.5 text-[#E14D2A] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        <span class="line-clamp-1">Simulasi CBT berkala & evaluasi</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Price & Action Footer -->
                    <div class="p-5 pt-0 border-t border-[#F4EFEA] mt-auto">
                        <div class="flex items-baseline justify-between mb-3 pt-3">
                            <div>
                                <span class="text-[10px] font-mono text-[#78716C] uppercase tracking-wider block">Biaya Investasi</span>
                                <span class="text-xl sm:text-2xl font-extrabold text-[#141413] tracking-tight">
                                    Rp {{ number_format($paket->nominal, 0, ',', '.') }}
                                </span>
                            </div>
                            <span class="text-[10px] text-[#78716C] font-mono">Per Program</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('paket.detail', $paket->id) }}" 
                               class="w-full bg-[#141413] hover:bg-[#292524] text-white font-semibold py-2.5 rounded-lg text-xs transition-colors text-center">
                                Detail
                            </a>
                            <a href="{{ route('daftar.step1', ['paket_id' => $paket->id]) }}" 
                               class="w-full bg-[#E14D2A] hover:bg-[#C93B1A] text-white font-semibold py-2.5 rounded-lg text-xs transition-colors text-center shadow-xs">
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

<!-- Highly Responsive, Zero-Delay Horizontal Slider Controller -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const slider = document.getElementById('programSlider');
        if (!slider) return;

        const prevBtn = document.getElementById('prevProgramBtn');
        const nextBtn = document.getElementById('nextProgramBtn');

        function getScrollStep() {
            const firstCard = slider.querySelector('.program-card');
            if (!firstCard) return 330;
            const gap = window.innerWidth >= 640 ? 24 : 20;
            return firstCard.offsetWidth + gap;
        }

        function updateArrowButtons() {
            if (!prevBtn || !nextBtn) return;
            const maxScroll = slider.scrollWidth - slider.clientWidth;
            prevBtn.disabled = slider.scrollLeft <= 3;
            nextBtn.disabled = slider.scrollLeft >= maxScroll - 3;
        }

        // Arrow button smooth scrolling with native hardware compositor
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                slider.scrollBy({ left: -getScrollStep(), behavior: 'smooth' });
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                slider.scrollBy({ left: getScrollStep(), behavior: 'smooth' });
            });
        }

        // Normalize delta across all browsers and operating systems (e.g. Firefox Linux DOM_DELTA_LINE)
        function normalizeWheelDelta(e) {
            let delta = e.deltaY;
            if (e.deltaMode === 1) {
                // Firefox Linux line mode: typically 3 lines per notch
                delta *= 36;
            } else if (e.deltaMode === 2) {
                // DOM_DELTA_PAGE
                delta *= 280;
            }
            return delta;
        }

        // Instantaneous, zero-delay wheel scroll handler
        function handleWheel(e) {
            if (e.ctrlKey) return; // Allow browser pinch-to-zoom

            const maxScroll = slider.scrollWidth - slider.clientWidth;
            if (maxScroll <= 5) return; // Content not overflowing

            // Process dominant vertical scroll
            if (Math.abs(e.deltaY) >= Math.abs(e.deltaX)) {
                const delta = normalizeWheelDelta(e);
                const isScrollingRight = delta > 0;
                const isScrollingLeft = delta < 0;

                const canScrollRight = isScrollingRight && slider.scrollLeft < (maxScroll - 4);
                const canScrollLeft = isScrollingLeft && slider.scrollLeft > 4;

                if (canScrollRight || canScrollLeft) {
                    e.preventDefault();
                    // Direct instantaneous scroll assignment (0ms latency, zero delay!)
                    slider.scrollLeft += delta;
                    updateArrowButtons();
                }
                // When edge is reached, preventDefault is NOT called, so the vertical page scroll continues smoothly!
            }
        }

        // Listen on the slider itself with passive: false to allow preventDefault when horizontal sliding
        slider.addEventListener('wheel', handleWheel, { passive: false });

        // Mouse Drag / Swipe Interaction
        let isDown = false;
        let startX = 0;
        let startScrollLeft = 0;
        let hasMoved = false;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            hasMoved = false;
            slider.classList.add('active');
            startX = e.pageX - slider.offsetLeft;
            startScrollLeft = slider.scrollLeft;
        });

        const stopDragging = () => {
            if (!isDown) return;
            isDown = false;
            slider.classList.remove('active');
        };

        slider.addEventListener('mouseleave', stopDragging);
        slider.addEventListener('mouseup', stopDragging);

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 1.3;
            if (Math.abs(walk) > 4) {
                hasMoved = true;
            }
            e.preventDefault();
            slider.scrollLeft = startScrollLeft - walk;
            updateArrowButtons();
        });

        // Prevent unintentional clicks when dragging
        slider.addEventListener('click', (e) => {
            if (hasMoved) {
                e.preventDefault();
                e.stopPropagation();
                hasMoved = false;
            }
        }, true);

        slider.addEventListener('scroll', updateArrowButtons, { passive: true });
        window.addEventListener('resize', updateArrowButtons, { passive: true });
        updateArrowButtons();
    });
</script>
