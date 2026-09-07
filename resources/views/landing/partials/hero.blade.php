<!-- Hero Section: Editorial & Sophisticated Educational Photography -->
<section class="relative min-h-[calc(100vh-5rem)] lg:min-h-screen flex flex-col justify-center pt-24 pb-12 lg:pt-28 lg:pb-16 overflow-hidden bg-[#FAF8F5] text-[#141413] border-b border-[#E7E2D9] w-full">
    <!-- Editorial Hero Layout with Authentic Photography Slider -->
        <div class="w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 relative z-10 w-full my-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 xl:gap-16 2xl:gap-20 items-start">
                
                <!-- Left Column: Editorial Headline, Copy, & Structured Action -->
                <div class="lg:col-span-6 text-center lg:text-left lg:pt-1.5">
                    <!-- Main Editorial Display Headline -->
                    <h1 class="text-4xl sm:text-6xl lg:text-[4rem] font-extrabold text-[#141413] leading-[1.08] tracking-[-0.03em] mb-6" data-aos="fade-up">
                        @if(isset($masterData) && $masterData->hero_title)
                            {!! nl2br(e($masterData->hero_title)) !!}
                        @else
                            Belajar Lebih Terarah.<br>
                            <span class="text-[#E14D2A]">Bertumbuh</span> Lebih Percaya Diri.
                        @endif
                    </h1>

                    <!-- Supporting Copy -->
                    <p class="text-[#57534E] text-base sm:text-lg lg:text-xl leading-relaxed max-w-xl mx-auto lg:mx-0 mb-8 font-normal" data-aos="fade-up" data-aos-delay="150">
                        {{ $masterData->hero_subtitle ?? 'Nivora menghadirkan bimbingan belajar terstruktur, mentor profesional, materi adaptif, simulasi CBT akurat, dan pemantauan perkembangan akademik yang terukur.' }}
                    </p>

                    <!-- Dual Action CTAs -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center lg:justify-start gap-3.5 mb-10" data-aos="fade-up" data-aos-delay="200">
                        <a href="{{ $masterData->hero_cta_link ?? '#program' }}" 
                           class="bg-[#E14D2A] hover:bg-[#C93B1A] text-white font-semibold px-7 py-3.5 rounded-lg shadow-xs transition-colors flex items-center justify-center gap-2 text-sm" style="background-color: #E14D2A;">
                            <span>{{ $masterData->hero_cta_text ?? 'Jelajahi Program' }}</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                        <a href="#tentang" 
                           class="bg-white hover:bg-[#F4EFEA] border border-[#E7E2D9] text-[#141413] font-semibold px-6 py-3.5 rounded-lg transition-colors flex items-center justify-center text-sm">
                            Tentang Institusi
                        </a>
                    </div>

                    <!-- Trust Indicators: Clean Integrated Numbers -->
                    <div class="pt-8 border-t border-[#E7E2D9] grid grid-cols-3 gap-4 sm:gap-6 max-w-lg mx-auto lg:mx-0 text-left" data-aos="fade-up" data-aos-delay="250">
                        <div class="border-l-2 border-[#141413] pl-3.5">
                            <div class="font-mono text-2xl sm:text-3xl font-extrabold text-[#141413] tracking-tight">{{ (isset($masterData) && $masterData->stats_siswa) ? $masterData->stats_siswa : '1,500+' }}</div>
                            <div class="text-xs text-[#78716C] font-medium mt-1">Siswa Terdaftar</div>
                        </div>
                        <div class="border-l-2 border-[#E14D2A] pl-3.5">
                            <div class="font-mono text-2xl sm:text-3xl font-extrabold text-[#141413] tracking-tight">{{ (isset($masterData) && $masterData->stats_tutor) ? $masterData->stats_tutor : '98.4%' }}</div>
                            <div class="text-xs text-[#78716C] font-medium mt-1">Tingkat Kelulusan</div>
                        </div>
                        <div class="border-l-2 border-[#A8A29E] pl-3.5">
                            <div class="font-mono text-2xl sm:text-3xl font-extrabold text-[#141413] tracking-tight">{{ (isset($masterData) && $masterData->stats_kepuasan) ? $masterData->stats_kepuasan : '4.9/5' }}</div>
                            <div class="text-xs text-[#78716C] font-medium mt-1">Rating Kepuasan</div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Interactive Authentic Photography Slider -->
                <div class="lg:col-span-6" data-aos="fade-left" data-aos-delay="200"
                     x-data="{
                         current: 0,
                         total: 3,
                         timer: null,
                         startX: 0,
                         currentX: 0,
                         isDragging: false,
                         init() {
                             this.startAuto();
                             window.addEventListener('focus', () => this.startAuto());
                         },
                         next() {
                             this.current = (this.current + 1) % this.total;
                             this.restartAuto();
                         },
                         prev() {
                             this.current = (this.current - 1 + this.total) % this.total;
                             this.restartAuto();
                         },
                         goTo(index) {
                             this.current = index;
                             this.restartAuto();
                         },
                         startAuto() {
                             this.stopAuto();
                             this.timer = setInterval(() => { 
                                 this.next(); 
                             }, 3800);
                         },
                         stopAuto() {
                             if (this.timer) { 
                                 clearInterval(this.timer); 
                                 this.timer = null; 
                             }
                         },
                         restartAuto() {
                             this.stopAuto();
                             this.startAuto();
                         },
                         onTouchStart(e) {
                             this.startX = e.touches[0].clientX;
                             this.currentX = this.startX;
                         },
                         onTouchMove(e) {
                             this.currentX = e.touches[0].clientX;
                         },
                         onTouchEnd() {
                             const diff = this.currentX - this.startX;
                             if (Math.abs(diff) > 40) {
                                 diff < 0 ? this.next() : this.prev();
                             }
                             this.restartAuto();
                         },
                         onMouseDown(e) {
                             this.isDragging = true;
                             this.startX = e.clientX;
                             this.currentX = this.startX;
                         },
                         onMouseMove(e) {
                             if (!this.isDragging) return;
                             this.currentX = e.clientX;
                         },
                         onMouseUp() {
                             if (!this.isDragging) return;
                             this.isDragging = false;
                             const diff = this.currentX - this.startX;
                             if (Math.abs(diff) > 40) {
                                 diff < 0 ? this.next() : this.prev();
                             }
                             this.restartAuto();
                         }
                     }">
                    <div class="bg-white border border-[#E7E2D9] rounded-2xl p-3 sm:p-4 shadow-sm relative">
                        <!-- Slide Frame / Viewport -->
                        <div class="relative aspect-[4/3] sm:aspect-[16/11] rounded-xl overflow-hidden bg-stone-100 cursor-grab active:cursor-grabbing select-none"
                             @touchstart.passive="onTouchStart($event)"
                             @touchmove.passive="onTouchMove($event)"
                             @touchend="onTouchEnd()"
                             @mousedown="onMouseDown($event)"
                             @mousemove="onMouseMove($event)"
                             @mouseup="onMouseUp()">
                             
                            <!-- Slides Track -->
                            <div class="flex h-full w-full transition-transform duration-500 ease-out"
                                 :style="`transform: translateX(-${current * 100}%)`">
                                
                                <!-- Slide 1 -->
                                <div class="w-full h-full flex-shrink-0 relative">
                                    <img src="{{ (isset($masterData) && $masterData->hero_image) ? asset('storage/' . $masterData->hero_image) : asset('images/hero-slide-1.png') }}" 
                                         alt="Interaksi Belajar dan Diskusi Bersama Guru di Kelas" 
                                         class="w-full h-full object-cover object-center pointer-events-none"
                                         draggable="false"
                                         loading="eager">
                                    @if(isset($masterData) && $masterData->hero_overlay_opacity)
                                        <div class="absolute inset-0 bg-black pointer-events-none" style="opacity: {{ $masterData->hero_overlay_opacity }};"></div>
                                    @endif
                                </div>

                                <!-- Slide 2 -->
                                <div class="w-full h-full flex-shrink-0 relative">
                                    <img src="{{ (isset($masterData) && $masterData->hero_image_2) ? asset('storage/' . $masterData->hero_image_2) : asset('images/hero-slide-2.png') }}" 
                                         alt="Pembelajaran Berbasis Komputer dan Fasilitas Digital" 
                                         class="w-full h-full object-cover object-center pointer-events-none"
                                         draggable="false"
                                         loading="lazy">
                                    @if(isset($masterData) && $masterData->hero_overlay_opacity)
                                        <div class="absolute inset-0 bg-black pointer-events-none" style="opacity: {{ $masterData->hero_overlay_opacity }};"></div>
                                    @endif
                                </div>

                                <!-- Slide 3 -->
                                <div class="w-full h-full flex-shrink-0 relative">
                                    <img src="{{ (isset($masterData) && $masterData->hero_image_3) ? asset('storage/' . $masterData->hero_image_3) : asset('images/hero-slide-3.png') }}" 
                                         alt="Pendampingan Akademik Terpadu Siswa di Kelas" 
                                         class="w-full h-full object-cover object-center pointer-events-none"
                                         draggable="false"
                                         loading="lazy">
                                    @if(isset($masterData) && $masterData->hero_overlay_opacity)
                                        <div class="absolute inset-0 bg-black pointer-events-none" style="opacity: {{ $masterData->hero_overlay_opacity }};"></div>
                                    @endif
                                </div>
                            </div>

                            <!-- Left Hover Zone: Tombol tersembunyi, muncul saat kursor mendekati sisi kiri -->
                            <div class="absolute left-0 top-0 bottom-0 w-24 sm:w-36 z-20 flex items-center justify-start pl-3 sm:pl-4 group/prev cursor-pointer select-none"
                                 @click.stop="prev()">
                                <button type="button" 
                                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/95 hover:bg-white text-[#141413] shadow-lg border border-stone-200/80 flex items-center justify-center transition-all duration-300 transform -translate-x-2 group-hover/prev:translate-x-0 opacity-0 group-hover/prev:opacity-100 pointer-events-none group-hover/prev:pointer-events-auto focus:outline-none cursor-pointer"
                                        aria-label="Foto Sebelumnya">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-stone-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Right Hover Zone: Tombol tersembunyi, muncul saat kursor mendekati sisi kanan -->
                            <div class="absolute right-0 top-0 bottom-0 w-24 sm:w-36 z-20 flex items-center justify-end pr-3 sm:pr-4 group/next cursor-pointer select-none"
                                 @click.stop="next()">
                                <button type="button" 
                                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/95 hover:bg-white text-[#141413] shadow-lg border border-stone-200/80 flex items-center justify-center transition-all duration-300 transform translate-x-2 group-hover/next:translate-x-0 opacity-0 group-hover/next:opacity-100 pointer-events-none group-hover/next:pointer-events-auto focus:outline-none cursor-pointer"
                                        aria-label="Foto Selanjutnya">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-stone-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Bulat-bulat Pagination Indicators: Berada di Bawah Tengah Foto -->
                        <div class="flex items-center justify-center gap-2.5 pt-4 pb-1">
                            <button type="button"
                                    @click="goTo(0)"
                                    class="h-2.5 rounded-full transition-all duration-300 focus:outline-none cursor-pointer"
                                    :style="current === 0 ? 'width: 28px; background-color: #E14D2A;' : 'width: 10px; background-color: #D6D3D1;'"
                                    style="width: 28px; height: 10px; background-color: #E14D2A;"
                                    aria-label="Foto 1"
                                    title="Foto 1"></button>
                            <button type="button"
                                    @click="goTo(1)"
                                    class="h-2.5 rounded-full transition-all duration-300 focus:outline-none cursor-pointer"
                                    :style="current === 1 ? 'width: 28px; background-color: #E14D2A;' : 'width: 10px; background-color: #D6D3D1;'"
                                    style="width: 10px; height: 10px; background-color: #D6D3D1;"
                                    aria-label="Foto 2"
                                    title="Foto 2"></button>
                            <button type="button"
                                    @click="goTo(2)"
                                    class="h-2.5 rounded-full transition-all duration-300 focus:outline-none cursor-pointer"
                                    :style="current === 2 ? 'width: 28px; background-color: #E14D2A;' : 'width: 10px; background-color: #D6D3D1;'"
                                    style="width: 10px; height: 10px; background-color: #D6D3D1;"
                                    aria-label="Foto 3"
                                    title="Foto 3"></button>
                        </div>

                    </div>
                </div>

            </div>
        </div>
</section>
