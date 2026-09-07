<!-- Testimonials Section -->
@if(isset($testimonials) && $testimonials->count() > 0)
<section id="testimoni" class="py-20 sm:py-28 bg-white relative">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-14 sm:mb-20" data-aos="fade-up">
            <span class="px-3.5 py-1.5 rounded-full bg-orange-50 border border-orange-200/60 text-orange-600 text-xs font-bold uppercase tracking-wider mb-4 inline-block">
                Kisah Keberhasilan
            </span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight mb-4">
                Apa Kata Mereka?
            </h2>
            <p class="text-slate-500 font-medium text-base sm:text-lg leading-relaxed">
                Pengalaman nyata siswa dan orang tua yang telah membuktikan kualitas pendampingan belajar kami.
            </p>
        </div>

        <!-- Testimonial Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($testimonials as $item)
            <div class="bg-slate-50/70 rounded-3xl p-8 border border-slate-200/70 hover:border-orange-200 hover:bg-white hover:shadow-xl hover:shadow-orange-500/5 transition-all duration-300 flex flex-col justify-between"
                 data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                
                <div>
                    <!-- Star Rating -->
                    <div class="flex items-center gap-1 mb-5 text-amber-400">
                        @for($i = 0; $i < ($item->bintang ?? 5); $i++)
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>

                    <!-- Testimonial Quote -->
                    <p class="text-slate-700 text-sm sm:text-base leading-relaxed mb-6 italic">
                        "{{ $item->ulasan }}"
                    </p>
                </div>

                <!-- Author Identity -->
                <div class="flex items-center gap-3.5 pt-4 border-t border-slate-200/60">
                    <div class="w-11 h-11 rounded-xl overflow-hidden bg-slate-200 ring-2 ring-white flex-shrink-0 shadow-sm flex items-center justify-center">
                        @if($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-slate-600 font-bold text-sm">{{ substr($item->nama, 0, 1) }}</span>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 leading-snug">{{ $item->nama }}</h4>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $item->posisi ?? 'Siswa Aktif' }}</p>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

    </div>
</section>
@endif
