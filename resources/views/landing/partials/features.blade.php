<!-- Features Section -->
@if(isset($features) && $features->count() > 0)
<section id="keunggulan" class="py-20 sm:py-28 bg-white relative">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-14 sm:mb-20" data-aos="fade-up">
            <span class="px-3.5 py-1.5 rounded-full bg-orange-50 border border-orange-200/60 text-orange-600 text-xs font-bold uppercase tracking-wider mb-4 inline-block">
                Keunggulan Pembelajaran
            </span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight mb-4">
                Mengapa Memilih Kami?
            </h2>
            <p class="text-slate-500 font-medium text-base sm:text-lg leading-relaxed">
                Fasilitas terpadu dan teknologi modern yang dirancang untuk mendukung setiap tahap perkembangan akademik Anda.
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($features as $feature)
            <div class="bg-slate-50/70 hover:bg-white rounded-3xl p-8 border border-slate-200/70 hover:border-orange-200 hover:shadow-xl hover:shadow-orange-500/5 transition-all duration-300 group flex flex-col justify-between" 
                 data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <div>
                    <!-- Feature Icon -->
                    <div class="w-14 h-14 rounded-2xl bg-orange-100/60 text-orange-600 flex items-center justify-center mb-6 shadow-sm ring-1 ring-orange-200/50 group-hover:scale-110 group-hover:bg-gradient-to-br group-hover:from-orange-500 group-hover:to-amber-500 group-hover:text-white transition-all duration-300">
                        {!! $feature->icon !!}
                    </div>
                    <!-- Title -->
                    <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-orange-600 transition-colors">
                        {{ $feature->title }}
                    </h3>
                    <!-- Description -->
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed font-normal">
                        {{ $feature->description }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
