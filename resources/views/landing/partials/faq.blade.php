<!-- FAQ Section: Clean Hairline Accordion (No Giant Cards) -->
@if(isset($faqs) && $faqs->count() > 0)
<section id="faq" class="py-20 sm:py-28 bg-[#FAF8F5] border-b border-[#E7E2D9] relative w-full" x-data="{ activeAccordion: null }">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center mb-14 sm:mb-20" data-aos="fade-up">
            <span class="text-xs font-mono uppercase tracking-widest text-[#78716C] font-semibold block mb-3">
                [ TANYA JAWAB UMUM ]
            </span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#141413] tracking-tight leading-tight mb-4">
                Pertanyaan yang Sering Ditanyakan
            </h2>
            <p class="text-[#57534E] text-base sm:text-lg leading-relaxed font-normal">
                Jawaban ringkas dan transparan seputar sistem pembelajaran, kurikulum, pendaftaran, dan jadwal kelas di Nivora.
            </p>
        </div>

        <!-- Clean Hairline Accordion List -->
        <div class="divide-y divide-[#E7E2D9] border-y border-[#E7E2D9]" data-aos="fade-up" data-aos-delay="100">
            @foreach($faqs as $faq)
            <div class="py-5 sm:py-6">
                <button type="button" 
                        @click="activeAccordion = (activeAccordion === {{ $loop->index }} ? null : {{ $loop->index }})"
                        class="w-full text-left flex items-start justify-between gap-6 focus:outline-none select-none group">
                    <span class="text-base sm:text-lg font-bold text-[#141413] group-hover:text-[#E14D2A] transition-colors leading-snug">
                        {{ $faq->pertanyaan }}
                    </span>
                    <span class="w-6 h-6 rounded border border-[#E7E2D9] bg-white flex items-center justify-center shrink-0 text-[#141413] font-mono text-sm transition-transform duration-200 mt-0.5"
                          :class="{ 'rotate-45 text-[#E14D2A] border-[#E14D2A]': activeAccordion === {{ $loop->index }} }">
                        +
                    </span>
                </button>
                <div x-show="activeAccordion === {{ $loop->index }}" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     class="pt-4 text-[#57534E] text-sm sm:text-base leading-relaxed max-w-3xl font-normal">
                    {!! nl2br(e($faq->jawaban)) !!}
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>
@endif
