<!-- Header / Navbar -->
    <nav class="fixed w-full z-50 top-0 transition-all duration-300 glass-nav shadow-sm" id="navbar">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 sm:h-24">
                <a href="{{ route('welcome') }}" class="flex-shrink-1 flex items-center gap-2 sm:gap-3 overflow-hidden hover:opacity-90 transition-opacity cursor-pointer">
                    @if(isset($masterData) && $masterData->logo)
                        <img src="{{ Storage::url($masterData->logo) }}" alt="Logo" class="h-8 sm:h-12 w-auto object-contain flex-shrink-0 rounded-xl sm:rounded-2xl shadow-sm">
                    @endif
                    <span class="font-black text-base sm:text-xl tracking-tight block text-slate-900 truncate">{{ $masterData->nama_lembaga ?? config('app.name') }}</span>
                </a>
                
                <div class="flex-shrink-0 flex items-center gap-1 sm:gap-4">
                    <a href="{{ route('login') }}" class="text-xs sm:text-sm font-bold text-slate-600 hover:text-indigo-600 px-2 sm:px-3 py-2 transition-colors">Masuk</a>
                    <a href="{{ route('daftar.step1') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-bold px-3 sm:px-6 py-2 sm:py-2.5 rounded-lg sm:rounded-xl transition-all shadow-lg shadow-indigo-100 active:scale-95 whitespace-nowrap">Daftar</a>
                </div>
            </div>
        </div>
    </nav>
