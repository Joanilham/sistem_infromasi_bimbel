<!-- Header / Navbar -->
    <nav class="fixed w-full z-50 top-0 transition-all duration-300 glass-nav shadow-sm" id="navbar">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 sm:h-24">
                <div class="flex-shrink-0 flex items-center gap-3">
                    @if(isset($masterData) && $masterData->logo)
                        <img src="{{ Storage::url($masterData->logo) }}" alt="Logo" class="h-10 sm:h-12 w-auto object-contain">
                    @endif
                    <span class="font-black text-lg sm:text-xl tracking-tight block text-slate-900">{{ $masterData->nama_lembaga ?? 'Genius Education' }}</span>
                </div>
                
                <div class="flex items-center gap-2 sm:gap-4">
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-indigo-600 px-3 py-2 transition-colors">Masuk</a>
                    <a href="{{ route('daftar.step1') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-5 sm:px-6 py-2.5 rounded-xl transition-all shadow-lg shadow-indigo-100 active:scale-95">Daftar</a>
                </div>
            </div>
        </div>
    </nav>
