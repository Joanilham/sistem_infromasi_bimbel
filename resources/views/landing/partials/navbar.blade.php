<!-- Header / Navbar -->
<nav class="fixed w-full z-50 top-0 transition-all duration-300 glass-nav border-b border-slate-200/60" id="navbar" x-data="{ mobileMenuOpen: false }">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Brand Logo & Name -->
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 hover:opacity-95 transition-opacity group">
                @if(isset($masterData) && $masterData->logo)
                    <div class="h-11 w-11 rounded-xl bg-white shadow-sm ring-1 ring-slate-900/5 flex items-center justify-center p-1.5 transition-transform group-hover:scale-105">
                        <img src="{{ Storage::url($masterData->logo) }}" alt="{{ $masterData->nama_lembaga ?? config('app.name') }}" class="h-full w-full object-contain">
                    </div>
                @else
                    <div class="h-11 w-11 rounded-xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center text-white shadow-md shadow-orange-500/20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                @endif
                <span class="font-extrabold text-lg sm:text-xl tracking-tight text-slate-900 truncate max-w-[200px] sm:max-w-xs">
                    {{ $masterData->nama_lembaga ?? config('app.name') }}
                </span>
            </a>

            <!-- Desktop Navigation Links -->
            <div class="hidden lg:flex items-center gap-1 xl:gap-2">
                <a href="#program" class="text-sm font-semibold text-slate-600 hover:text-orange-600 hover:bg-orange-50/50 px-3.5 py-2 rounded-xl transition-all">Program</a>
                <a href="#keunggulan" class="text-sm font-semibold text-slate-600 hover:text-orange-600 hover:bg-orange-50/50 px-3.5 py-2 rounded-xl transition-all">Keunggulan</a>
                <a href="#tentang" class="text-sm font-semibold text-slate-600 hover:text-orange-600 hover:bg-orange-50/50 px-3.5 py-2 rounded-xl transition-all">Tentang Kami</a>
                @if(isset($featuredGurus) && $featuredGurus->count() > 0)
                    <a href="#pengajar" class="text-sm font-semibold text-slate-600 hover:text-orange-600 hover:bg-orange-50/50 px-3.5 py-2 rounded-xl transition-all">Pengajar</a>
                @endif
                @if(isset($testimonials) && $testimonials->count() > 0)
                    <a href="#testimoni" class="text-sm font-semibold text-slate-600 hover:text-orange-600 hover:bg-orange-50/50 px-3.5 py-2 rounded-xl transition-all">Testimoni</a>
                @endif
                @if(isset($faqs) && $faqs->count() > 0)
                    <a href="#faq" class="text-sm font-semibold text-slate-600 hover:text-orange-600 hover:bg-orange-50/50 px-3.5 py-2 rounded-xl transition-all">FAQ</a>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="hidden sm:flex items-center gap-2 sm:gap-3">
                <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-orange-600 px-4 py-2.5 rounded-xl hover:bg-slate-100/70 transition-all">
                    Masuk
                </a>
                <a href="{{ route('daftar.step1') }}" class="bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow-md shadow-orange-500/20 hover:shadow-lg hover:shadow-orange-500/30 active:scale-95 transition-all">
                    Daftar Sekarang
                </a>
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="flex items-center gap-2 lg:hidden">
                <a href="{{ route('login') }}" class="sm:hidden text-xs font-bold text-slate-700 px-2.5 py-1.5 rounded-lg hover:bg-slate-100">
                    Masuk
                </a>
                <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none" 
                    aria-label="Toggle Menu">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" x-cloak 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="lg:hidden pb-6 pt-2 border-t border-slate-100">
            <div class="flex flex-col space-y-1">
                <a href="#program" @click="mobileMenuOpen = false" class="px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-orange-50 hover:text-orange-600">Program</a>
                <a href="#keunggulan" @click="mobileMenuOpen = false" class="px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-orange-50 hover:text-orange-600">Keunggulan</a>
                <a href="#tentang" @click="mobileMenuOpen = false" class="px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-orange-50 hover:text-orange-600">Tentang Kami</a>
                @if(isset($featuredGurus) && $featuredGurus->count() > 0)
                    <a href="#pengajar" @click="mobileMenuOpen = false" class="px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-orange-50 hover:text-orange-600">Pengajar</a>
                @endif
                @if(isset($testimonials) && $testimonials->count() > 0)
                    <a href="#testimoni" @click="mobileMenuOpen = false" class="px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-orange-50 hover:text-orange-600">Testimoni</a>
                @endif
                @if(isset($faqs) && $faqs->count() > 0)
                    <a href="#faq" @click="mobileMenuOpen = false" class="px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-orange-50 hover:text-orange-600">FAQ</a>
                @endif
                <div class="pt-3 flex flex-col gap-2">
                    <a href="{{ route('daftar.step1') }}" class="w-full text-center bg-gradient-to-r from-orange-500 to-amber-600 text-white font-bold py-3 rounded-xl shadow-md shadow-orange-500/20 text-sm">
                        Daftar Siswa Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
