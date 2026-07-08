<!-- Programs Section -->
    <section id="program" class="py-16 sm:py-20 bg-slate-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 sm:mb-16 gap-4 sm:gap-6 text-center md:text-left">
                <div data-aos="fade-up">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 mb-3 sm:mb-4 tracking-tight">Program Bimbingan Unggulan</h2>
                    <p class="text-slate-500 font-medium max-w-lg mx-auto md:mx-0 text-sm sm:text-base">Pilih jalur bimbingan yang sesuai dengan target dan kebutuhan akademikmu.</p>
                </div>
            </div>

            <style>
                .program-card { min-width: 85vw; }
                @media (min-width: 768px) { .program-card { min-width: 45vw; } }
                @media (min-width: 1024px) { .program-card { min-width: 31%; } }
                .program-slider.active { cursor: grabbing; cursor: -webkit-grabbing; }
                .program-slider:not(.active) { cursor: grab; cursor: -webkit-grab; }
            </style>
            <div id="programSlider" class="program-slider flex overflow-x-auto gap-6 sm:gap-8 snap-x snap-mandatory pb-8 custom-scrollbar">
                @forelse($pakets as $paket)
                    <div class="program-card flex-shrink-0 snap-center group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 flex flex-col h-full relative" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <!-- Badge Diskon Removed -->

                        <!-- Header Image -->
                        <div class="relative h-40 sm:h-48 overflow-hidden">
                            @if($paket->gambar_paket)
                                <img src="{{ asset('storage/' . $paket->gambar_paket) }}" alt="{{ $paket->nama_paket }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white/20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>
                            @if($paket->label_populer)
                                <div class="absolute bottom-4 left-4">
                                    <span class="bg-[#F97316] text-white text-[10px] font-black px-3 py-1 rounded-full shadow-lg">✨ {{ $paket->label_populer }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="p-6 flex-grow flex flex-col">
                            <h3 class="text-xl font-black text-slate-900 mb-1 group-hover:text-indigo-600 transition-colors tracking-tight line-clamp-2 break-words">{{ $paket->nama_paket }}</h3>
                            <p class="text-indigo-600 text-[9px] sm:text-[10px] uppercase tracking-[0.2em] mb-4 font-black break-words">{{ $paket->target_peserta ?? 'Semua Jenjang' }}</p>
                            
                            <div class="space-y-2.5 mb-6">
                                @php 
                                    $benefits = explode("\n", str_replace("\r", "", $paket->benefits ?? ''));
                                    $topBenefits = array_slice(array_filter($benefits), 0, 3);
                                @endphp
                                @forelse($topBenefits as $benefit)
                                    <div class="flex items-start gap-2">
                                        <div class="flex-shrink-0 w-4 h-4 rounded-full bg-yellow-100 flex items-center justify-center mt-0.5">
                                            <svg class="w-2.5 h-2.5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        </div>
                                        <span class="text-xs text-slate-600 font-bold leading-relaxed break-words line-clamp-2">{{ trim($benefit) }}</span>
                                    </div>
                                @empty
                                    <div class="text-xs text-slate-400 italic">Program intensif terstruktur.</div>
                                @endforelse
                            </div>

                            <div class="mt-auto bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                <div class="flex items-baseline gap-1">
                                    <span class="text-xl font-black text-indigo-600">Rp {{ number_format($paket->nominal, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 pb-6 flex-shrink-0 mt-2">
                            <a href="{{ route('paket.detail', $paket->id) }}" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 rounded-xl transition-all shadow-lg shadow-indigo-100 flex items-center justify-center gap-2 active:scale-95 group/btn text-sm">
                                Info Lebih Lanjut
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                        <h3 class="text-xl font-bold text-slate-900 uppercase tracking-wider">Belum Ada Program</h3>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-8 sm:mt-12 text-center" data-aos="fade-up">
                <a href="{{ route('paket.index') }}" class="inline-flex items-center gap-2 px-6 sm:px-8 py-3 sm:py-4 bg-white border border-slate-200 hover:border-indigo-600 text-slate-700 hover:text-indigo-600 font-bold rounded-xl sm:rounded-2xl transition-all shadow-sm hover:shadow-md">
                    Lihat Semua Program
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slider = document.getElementById('programSlider');
            let isDown = false;
            let startX;
            let scrollLeft;

            slider.addEventListener('mousedown', (e) => {
                isDown = true;
                slider.classList.add('active');
                slider.style.scrollSnapType = 'none'; // Disable snapping while dragging
                startX = e.pageX - slider.offsetLeft;
                scrollLeft = slider.scrollLeft;
            });
            
            slider.addEventListener('mouseleave', () => {
                isDown = false;
                slider.classList.remove('active');
                slider.style.scrollSnapType = ''; // Re-enable snapping
            });
            
            slider.addEventListener('mouseup', () => {
                isDown = false;
                slider.classList.remove('active');
                slider.style.scrollSnapType = ''; // Re-enable snapping
            });
            
            slider.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - slider.offsetLeft;
                const walk = (x - startX) * 2; // Kecepatan scroll
                slider.scrollLeft = scrollLeft - walk;
            });
            
            // Scroll menggunakan scroll/wheel mouse
            let isWheeling;
            slider.addEventListener('wheel', (e) => {
                // Mencegah scroll vertikal (landing page) jika mouse berada di atas card
                e.preventDefault();
                
                // Matikan snapping sementara agar scroll wheel tidak bertabrakan dengan CSS snap
                slider.style.scrollSnapType = 'none';
                
                // Mengubah arah scroll vertikal (deltaY) menjadi pergerakan horizontal (scrollLeft)
                slider.scrollLeft += e.deltaY * 1.5; // multiplier untuk sedikit mempercepat scroll
                
                // Hidupkan snapping kembali setelah user selesai scroll
                clearTimeout(isWheeling);
                isWheeling = setTimeout(() => {
                    slider.style.scrollSnapType = '';
                }, 150);
            }, { passive: false });
        });
    </script>
