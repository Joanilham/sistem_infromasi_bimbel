<!-- Guru Section -->
    @if(isset($featuredGurus) && $featuredGurus->count() > 0)
    <section class="py-16 sm:py-20 bg-slate-900 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16" data-aos="fade-up">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white mb-4">Pengajar Profesional</h2>
                <p class="text-slate-400 font-medium text-sm sm:text-base">Didukung oleh tim pengajar yang ahli dan berpengalaman di bidangnya.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                @foreach($featuredGurus as $guru)
                <div class="bg-slate-800 rounded-3xl p-6 border border-slate-700 text-center hover:bg-slate-700 transition-colors group" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <div class="w-24 h-24 mx-auto bg-indigo-500/20 rounded-full flex items-center justify-center mb-4 overflow-hidden border-2 border-indigo-500/30">
                        @if($guru->foto)
                            <img src="{{ asset('storage/' . $guru->foto) }}" alt="{{ $guru->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-2xl font-black text-indigo-400">{{ substr($guru->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <h3 class="text-lg font-bold text-white mb-1 truncate">{{ $guru->name }}</h3>
                    <p class="text-indigo-400 text-xs font-black uppercase tracking-wider">{{ $guru->matapelajaran ?? 'Pengajar' }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
