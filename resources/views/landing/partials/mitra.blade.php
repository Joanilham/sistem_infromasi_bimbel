<!-- Mitra Logos Section -->
    @if(isset($mitras) && $mitras->count() > 0)
    <section class="py-12 bg-white border-t border-b border-slate-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-8">Telah Dipercaya Oleh</p>
            <div class="flex flex-wrap justify-center items-center gap-8 sm:gap-16 opacity-60 hover:opacity-100 transition-opacity duration-500 grayscale hover:grayscale-0">
                @foreach($mitras as $mitra)
                    <img src="{{ asset('storage/' . $mitra->logo) }}" alt="{{ $mitra->name }}" class="h-10 sm:h-12 object-contain" title="{{ $mitra->name }}">
                @endforeach
            </div>
        </div>
    </section>
    @endif
