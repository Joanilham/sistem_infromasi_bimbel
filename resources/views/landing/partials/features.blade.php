<!-- Features Section -->
    @if(isset($features) && $features->count() > 0)
    <section class="py-16 sm:py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16" data-aos="fade-up">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 mb-4">Mengapa Memilih Kami?</h2>
                <p class="text-slate-500 font-medium text-sm sm:text-base">Keunggulan yang menjadikan kami pilihan terbaik untuk masa depan Anda.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($features as $feature)
                <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:shadow-xl transition-all group" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                        {!! $feature->icon !!}
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">{{ $feature->title }}</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">{{ $feature->description }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
