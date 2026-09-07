<!-- FAQ Section -->
@if(isset($faqs) && $faqs->count() > 0)
<section id="faq" class="py-20 sm:py-28 bg-slate-50/80 relative" x-data="{ activeAccordion: null }">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
        
        <!-- Section Header -->
        <div class="text-center mb-14 sm:mb-20" data-aos="fade-up">
            <span class="px-3.5 py-1.5 rounded-full bg-orange-100/60 border border-orange-200/70 text-orange-600 text-xs font-bold uppercase tracking-wider mb-4 inline-block">
                Pusat Bantuan & Tanya Jawab
            </span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight mb-4">
                Pertanyaan yang Sering Diajukan
            </h2>
            <p class="text-slate-500 font-medium text-base sm:text-lg leading-relaxed">
                Informasi penting dan jawaban atas pertanyaan umum seputar pendaftaran dan sistem belajar.
            </p>
        </div>

        <!-- Accordion List -->
        <div class="space-y-4" data-aos="fade-up" data-aos-delay="100">
            @foreach($faqs as $faq)
            <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm transition-all duration-200 hover:border-orange-200">
                <button type="button" 
                        @click="activeAccordion = (activeAccordion === {{ $loop->index }} ? null : {{ $loop->index }})"
                        class="w-full px-6 py-5 sm:px-8 sm:py-6 text-left flex items-center justify-between gap-4 focus:outline-none select-none">
                    <span class="text-base sm:text-lg font-bold text-slate-900">
                        {{ $faq->pertanyaan }}
                    </span>
                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center shrink-0 transition-transform duration-300"
                         :class="{ 'rotate-180 bg-orange-50 text-orange-600': activeAccordion === {{ $loop->index }} }">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </button>
                <div x-show="activeAccordion === {{ $loop->index }}" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="px-6 pb-6 sm:px-8 sm:pb-8 pt-0 text-slate-600 text-sm sm:text-base leading-relaxed border-t border-slate-100 mt-2">
                    {!! nl2br(e($faq->jawaban)) !!}
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>
@endif
