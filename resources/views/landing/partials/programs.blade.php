<!-- Programs Section -->
<section id="program" class="py-20 sm:py-28 bg-slate-50/80 relative">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 sm:mb-16 gap-6 text-center md:text-left" data-aos="fade-up">
            <div>
                <span class="px-3.5 py-1.5 rounded-full bg-orange-100/60 border border-orange-200/70 text-orange-600 text-xs font-bold uppercase tracking-wider mb-4 inline-block">
                    Pilihan Paket Belajar
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight mb-3">
                    Program Bimbingan Unggulan
                </h2>
                <p class="text-slate-500 font-medium max-w-xl text-base sm:text-lg">
                    Dirancang dengan kurikulum komprehensif untuk mendongkrak prestasi dan kesuksesan ujian Anda.
                </p>
            </div>
            <div class="hidden md:block">
                <a href="{{ route('paket.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-orange-600 hover:text-orange-700 transition-colors group">
                    <span>Jelajahi Semua Program</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>

        <style>
            .program-card { min-width: 85vw; }
            @media (min-width: 768px) { .program-card { min-width: 44vw; } }
            @media (min-width: 1024px) { .program-card { min-width: 31.5%; } }
            .program-slider.active { cursor: grabbing; cursor: -webkit-grabbing; }
            .program-slider:not(.active) { cursor: grab; cursor: -webkit-grab; }
        </style>

        <div id="programSlider" class="program-slider flex overflow-x-auto gap-6 sm:gap-8 snap-x snap-mandatory pb-8 custom-scrollbar">
            @forelse($pakets as $paket)
                <div class="program-card flex-shrink-0 snap-center group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-orange-500/10 transition-all duration-300 border border-slate-200/70 hover:border-orange-200 flex flex-col h-full relative" 
                     data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">

                    <!-- Card Header Image -->
                    <div class="relative h-44 sm:h-52 overflow-hidden bg-slate-900">
                        @if($paket->gambar_paket)
                            <img src="{{ asset('storage/' . $paket->gambar_paket) }}" alt="{{ $paket->nama_paket }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                                <svg class="w-14 h-14 text-slate-700" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                        
                        <!-- Popular Badge -->
                        @if($paket->label_populer)
                            <div class="absolute top-4 left-4">
                                <span class="bg-gradient-to-r from-orange-500 to-amber-500 text-white text-[11px] font-black px-3.5 py-1 rounded-full shadow-lg flex items-center gap-1.5">
                                    <span>✨</span> {{ $paket->label_populer }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 sm:p-7 flex-grow flex flex-col justify-between">
                        <div>
                            <!-- Target Peserta Tag -->
                            <span class="text-orange-600 text-[11px] font-bold uppercase tracking-wider block mb-2">
                                {{ $paket->target_peserta ?? 'Semua Jenjang' }}
                            </span>

                            <!-- Title -->
                            <h3 class="text-xl font-black text-slate-900 mb-4 group-hover:text-orange-600 transition-colors leading-snug">
                                {{ $paket->nama_paket }}
                            </h3>
                            
                            <!-- Benefits List -->
                            <div class="space-y-3 mb-6">
                                @php 
                                    $benefits = explode("\n", str_replace("\r", "", $paket->benefits ?? ''));
                                    $topBenefits = array_slice(array_filter($benefits), 0, 3);
                                @endphp
                                @forelse($topBenefits as $benefit)
                                    <div class="flex items-start gap-2.5">
                                        <div class="flex-shrink-0 w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mt-0.5">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <span class="text-xs sm:text-sm text-slate-600 font-medium leading-relaxed">
                                            {{ trim($benefit) }}
                                        </span>
                                    </div>
                                @empty
                                    <div class="text-xs text-slate-400 italic">Program intensif terstruktur dengan modul terkini.</div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Price Section -->
                        <div class="mt-4 pt-5 border-t border-slate-100 flex items-baseline justify-between">
                            <div>
                                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Investasi Mulai</span>
                                <span class="text-2xl font-black text-slate-900 tracking-tight">
                                    Rp {{ number_format($paket->nominal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="px-6 pb-6 sm:px-7 sm:pb-7 flex-shrink-0">
                        <a href="{{ route('paket.detail', $paket->id) }}" 
                           class="w-full bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold py-3.5 rounded-2xl transition-all duration-200 shadow-md shadow-orange-500/20 hover:shadow-lg hover:shadow-orange-500/30 flex items-center justify-center gap-2 active:scale-95 text-sm">
                            <span>Info Detail Program</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20 bg-white rounded-3xl border border-dashed border-slate-300 w-full">
                    <p class="text-base font-bold text-slate-500">Belum ada program bimbingan yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>

        <!-- Mobile View All Button -->
        <div class="mt-6 text-center md:hidden" data-aos="fade-up">
            <a href="{{ route('paket.index') }}" class="inline-flex items-center justify-center gap-2 w-full px-6 py-3.5 bg-white border border-slate-200 text-slate-800 font-bold rounded-2xl shadow-sm text-sm">
                Lihat Semua Program &rarr;
            </a>
        </div>

    </div>
</section>

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
            slider.style.scrollSnapType = 'none';
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('active');
            slider.style.scrollSnapType = 'x mandatory';
        });

        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('active');
            slider.style.scrollSnapType = 'x mandatory';
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
