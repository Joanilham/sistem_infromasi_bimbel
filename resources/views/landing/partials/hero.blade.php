<!-- Hero Section -->
    <section class="relative pt-24 pb-16 lg:pt-32 lg:pb-24 overflow-hidden min-h-[85vh] lg:min-h-[90vh] flex flex-col justify-center bg-slate-900 w-full">
        <!-- Dynamic Background Image -->
        @if($masterData && $masterData->hero_image)
            <div class="absolute inset-0 z-0 w-full h-full">
                <img src="{{ asset('storage/' . $masterData->hero_image) }}" alt="Hero Background" class="w-full h-full object-cover object-center">
                <div class="absolute inset-0 bg-gradient-to-b from-slate-900/80 via-slate-900/60 to-slate-900/90" style="opacity: {{ $masterData->hero_overlay_opacity ?? 0.8 }};"></div>
            </div>
        @else
            <!-- Fallback Gradient if no image -->
            <div class="absolute inset-0 z-0 bg-gradient-to-br from-indigo-900 via-slate-900 to-black opacity-90 w-full h-full"></div>
        @endif
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center w-full">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl 2xl:text-7xl font-black text-white drop-shadow-lg mb-4 sm:mb-6 lg:mb-8 leading-[1.2] sm:leading-[1.1] tracking-tight max-w-3xl lg:max-w-5xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                {{ $masterData->hero_title ?? 'Wujudkan Impian Akademik Bersama Kami' }}
            </h1>
            <p class="text-slate-200 text-sm sm:text-base lg:text-xl 2xl:text-2xl drop-shadow-md max-w-2xl lg:max-w-3xl mx-auto mb-8 sm:mb-10 lg:mb-12 leading-relaxed font-medium px-2 sm:px-4" data-aos="fade-up" data-aos-delay="200">
                {{ $masterData->hero_subtitle ?? 'Platform pembelajaran terintegrasi yang memudahkan manajemen pendaftaran, progres belajar, dan evaluasi hasil belajar.' }}
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 px-4 sm:px-6" data-aos="fade-up" data-aos-delay="300">
                <a href="{{ $masterData->hero_cta_link ?? '#program' }}" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 sm:px-10 sm:py-4 rounded-xl sm:rounded-2xl shadow-xl shadow-indigo-500/30 transition-all active:scale-95 flex items-center justify-center gap-2 sm:gap-3 text-sm sm:text-base">
                    {{ $masterData->hero_cta_text ?? 'Pilih Program' }}
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg>
                </a>
                <a href="#tentang" class="w-full sm:w-auto bg-white/10 hover:bg-white/20 border-2 border-white/20 text-white font-bold px-6 py-3 sm:px-10 sm:py-4 rounded-xl sm:rounded-2xl transition-all backdrop-blur-md flex items-center justify-center text-sm sm:text-base">Tentang Kami</a>
            </div>
        </div>
    </section>
