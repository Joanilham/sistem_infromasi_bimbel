<!-- Mitra Logos Section -->
@if(isset($mitras) && $mitras->count() > 0)
<section class="py-14 bg-white border-t border-b border-slate-200/70">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-8">
            Telah Dipercaya & Bekerjasama Dengan
        </p>
        <div class="flex flex-wrap justify-center items-center gap-8 sm:gap-16 opacity-70 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0">
            @foreach($mitras as $mitra)
                <div class="h-10 sm:h-12 flex items-center justify-center p-1">
                    @if($mitra->link)
                        <a href="{{ $mitra->link }}" target="_blank" rel="noopener noreferrer" title="{{ $mitra->name }}">
                            <img src="{{ asset('storage/' . $mitra->logo) }}" alt="{{ $mitra->name }}" class="h-8 sm:h-10 w-auto object-contain">
                        </a>
                    @else
                        <img src="{{ asset('storage/' . $mitra->logo) }}" alt="{{ $mitra->name }}" class="h-8 sm:h-10 w-auto object-contain" title="{{ $mitra->name }}">
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
