<!-- Testimonial Section: Editorial Student Journey & Proof -->
@if(isset($testimonials) && $testimonials->count() > 0)
<section id="testimoni" class="py-20 sm:py-28 bg-[#FAF8F5] border-b border-[#E7E2D9] relative w-full">
    <div class="w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
        
        <!-- Section Header -->
        <div class="max-w-3xl mb-14 sm:mb-20" data-aos="fade-up">
            <span class="text-xs font-mono uppercase tracking-widest text-[#78716C] font-semibold block mb-3">
                [ CERITA KEBERHASILAN ALUMNI ]
            </span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#141413] tracking-tight leading-tight mb-4">
                Perjalanan Mereka, Cerita Mereka
            </h2>
            <p class="text-[#57534E] text-base sm:text-lg leading-relaxed font-normal">
                Refleksi pengalaman nyata para siswa dan orang tua yang telah bertumbuh bersama metodologi bimbingan terstruktur {{ ucwords(strtolower($masterData->nama_lembaga ?? 'Nivora')) }}.
            </p>
        </div>

        <!-- Editorial Testimonial Layout (Asymmetric / Emphasized Story) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-stretch">
            
            @php
                $featuredTestimonial = $testimonials->first();
                $supportingTestimonials = $testimonials->slice(1);
            @endphp

            <!-- Emphasized Lead Testimonial -->
            @if($featuredTestimonial)
                <div class="lg:col-span-7 bg-white rounded-2xl p-8 sm:p-12 border border-[#E7E2D9] shadow-xs flex flex-col justify-between" data-aos="fade-up">
                    <div>
                        <!-- Rating Stars -->
                        <div class="flex items-center gap-1 text-[#E14D2A] mb-6">
                            @for($i = 0; $i < ($featuredTestimonial->bintang ?? 5); $i++)
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                            <span class="text-xs font-mono font-semibold text-[#78716C] ml-2">Ulasan Terverifikasi</span>
                        </div>

                        <!-- Quote -->
                        <blockquote class="text-xl sm:text-2xl font-semibold text-[#141413] leading-relaxed mb-8">
                            "{{ $featuredTestimonial->ulasan }}"
                        </blockquote>
                    </div>

                    <!-- Author Details with Institutional Achievement Placement -->
                    <div class="pt-6 border-t border-[#F4EFEA] flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-[#F4EFEA] border border-[#E7E2D9] flex items-center justify-center shrink-0">
                                @if($featuredTestimonial->foto)
                                    <img src="{{ asset('storage/' . $featuredTestimonial->foto) }}" alt="{{ $featuredTestimonial->nama }}" class="w-full h-full object-cover">
                                @else
                                    <span class="font-mono text-base font-bold text-[#141413]">{{ substr($featuredTestimonial->nama, 0, 1) }}</span>
                                @endif
                            </div>
                            <div>
                                <div class="font-bold text-base text-[#141413]">{{ $featuredTestimonial->nama }}</div>
                                <div class="text-xs text-[#78716C]">{{ $featuredTestimonial->posisi ?? 'Alumni Program Intensif' }}</div>
                            </div>
                        </div>

                        <span class="hidden sm:inline-flex text-xs font-mono font-semibold bg-[#FDF0EB] text-[#E14D2A] px-3 py-1 rounded border border-[#FCD8CC]">
                            Lolos Target PTN
                        </span>
                    </div>
                </div>
            @endif

            <!-- Supporting Testimonials Column -->
            <div class="lg:col-span-5 flex flex-col gap-6">
                @foreach($supportingTestimonials->take(2) as $item)
                    <div class="bg-white rounded-2xl p-7 border border-[#E7E2D9] shadow-xs flex-1 flex flex-col justify-between" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <div>
                            <div class="flex items-center gap-1 text-[#E14D2A] mb-3">
                                @for($i = 0; $i < ($item->bintang ?? 5); $i++)
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <p class="text-sm text-[#44403C] leading-relaxed mb-6 font-normal">
                                "{{ $item->ulasan }}"
                            </p>
                        </div>
                        <div class="pt-4 border-t border-[#F4EFEA] flex items-center justify-between">
                            <div>
                                <div class="font-bold text-sm text-[#141413]">{{ $item->nama }}</div>
                                <div class="text-xs text-[#78716C]">{{ $item->posisi ?? 'Siswa Terdaftar' }}</div>
                            </div>
                            <span class="text-xs font-mono text-[#059669] font-medium">Terverifikasi</span>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

    </div>
</section>
@endif
