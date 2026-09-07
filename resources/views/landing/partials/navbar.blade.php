<!-- Header / Navbar: Lightweight & Elegant Editorial -->
<nav class="fixed w-full z-50 top-0 transition-all duration-300 glass-nav border-b border-[#E7E2D9]" id="navbar" x-data="{ mobileMenuOpen: false }">
    <div class="w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
        <div class="flex items-center justify-between h-20">
            
            <!-- Left: NIVORA Logo + Wordmark -->
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 hover:opacity-95 transition-opacity group">
                @if(isset($masterData) && $masterData->logo)
                    <div class="h-10 w-10 rounded-lg bg-white border border-[#E7E2D9] flex items-center justify-center p-1.5 transition-transform group-hover:scale-105">
                        <img src="{{ Storage::url($masterData->logo) }}" alt="{{ $masterData->nama_lembaga ?? 'NIVORA' }}" class="h-full w-full object-contain">
                    </div>
                @else
                    <div class="h-10 w-10 rounded-lg bg-[#E14D2A] flex items-center justify-center text-white font-black text-lg tracking-wider shadow-xs">
                        N
                    </div>
                @endif
                <div class="flex flex-col">
                    <span class="font-black text-xl tracking-tight text-[#141413]">
                        {{ $masterData->nama_lembaga ?? 'NIVORA' }}
                    </span>
                    <span class="text-[10px] font-semibold uppercase tracking-widest text-[#78716C] -mt-1 hidden sm:block">
                        Education & Academy
                    </span>
                </div>
            </a>

            <!-- Center: Desktop Navigation Links -->
            <div class="hidden lg:flex items-center gap-1 xl:gap-2">
                <a href="#program" class="text-sm font-medium text-[#44403C] hover:text-[#E14D2A] px-3.5 py-2 rounded-md hover:bg-[#F4EFEA]/60 transition-colors">Program</a>
                <a href="#keunggulan" class="text-sm font-medium text-[#44403C] hover:text-[#E14D2A] px-3.5 py-2 rounded-md hover:bg-[#F4EFEA]/60 transition-colors">Keunggulan</a>
                <a href="#ekosistem" class="text-sm font-medium text-[#44403C] hover:text-[#E14D2A] px-3.5 py-2 rounded-md hover:bg-[#F4EFEA]/60 transition-colors">Eksplorasi Kampus</a>
                <a href="#tentang" class="text-sm font-medium text-[#44403C] hover:text-[#E14D2A] px-3.5 py-2 rounded-md hover:bg-[#F4EFEA]/60 transition-colors">Tentang Kami</a>
                @if(isset($featuredGurus) && $featuredGurus->count() > 0)
                    <a href="#pengajar" class="text-sm font-medium text-[#44403C] hover:text-[#E14D2A] px-3.5 py-2 rounded-md hover:bg-[#F4EFEA]/60 transition-colors">Pengajar</a>
                @endif
                @if(isset($testimonials) && $testimonials->count() > 0)
                    <a href="#testimoni" class="text-sm font-medium text-[#44403C] hover:text-[#E14D2A] px-3.5 py-2 rounded-md hover:bg-[#F4EFEA]/60 transition-colors">Testimoni</a>
                @endif
                @if(isset($faqs) && $faqs->count() > 0)
                    <a href="#faq" class="text-sm font-medium text-[#44403C] hover:text-[#E14D2A] px-3.5 py-2 rounded-md hover:bg-[#F4EFEA]/60 transition-colors">FAQ</a>
                @endif
            </div>

            <!-- Right: Secondary Text CTA + Primary Coral CTA -->
            <div class="hidden sm:flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-[#44403C] hover:text-[#141413] px-4 py-2.5 rounded-lg hover:bg-[#F4EFEA] transition-colors">
                    Masuk
                </a>
                <a href="{{ route('daftar.step1') }}" class="bg-[#E14D2A] hover:bg-[#C93B1A] text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-xs transition-colors active:scale-98" style="background-color: #E14D2A;">
                    Daftar Sekarang
                </a>
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="flex items-center gap-2 lg:hidden">
                <a href="{{ route('login') }}" class="sm:hidden text-xs font-semibold text-[#44403C] px-3 py-1.5 rounded-md hover:bg-[#F4EFEA]">
                    Masuk
                </a>
                <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="p-2 rounded-lg text-[#44403C] hover:text-[#141413] hover:bg-[#F4EFEA] focus:outline-none" 
                    aria-label="Toggle Menu">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div x-show="mobileMenuOpen" x-cloak 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="lg:hidden pb-6 pt-2 border-t border-[#E7E2D9]">
            <div class="flex flex-col space-y-1">
                <a href="#program" @click="mobileMenuOpen = false" class="px-3 py-2.5 rounded-lg text-sm font-medium text-[#44403C] hover:bg-[#F4EFEA] hover:text-[#E14D2A]">Program</a>
                <a href="#keunggulan" @click="mobileMenuOpen = false" class="px-3 py-2.5 rounded-lg text-sm font-medium text-[#44403C] hover:bg-[#F4EFEA] hover:text-[#E14D2A]">Keunggulan</a>
                <a href="#ekosistem" @click="mobileMenuOpen = false" class="px-3 py-2.5 rounded-lg text-sm font-medium text-[#44403C] hover:bg-[#F4EFEA] hover:text-[#E14D2A]">Eksplorasi Kampus</a>
                <a href="#tentang" @click="mobileMenuOpen = false" class="px-3 py-2.5 rounded-lg text-sm font-medium text-[#44403C] hover:bg-[#F4EFEA] hover:text-[#E14D2A]">Tentang Kami</a>
                @if(isset($featuredGurus) && $featuredGurus->count() > 0)
                    <a href="#pengajar" @click="mobileMenuOpen = false" class="px-3 py-2.5 rounded-lg text-sm font-medium text-[#44403C] hover:bg-[#F4EFEA] hover:text-[#E14D2A]">Pengajar</a>
                @endif
                @if(isset($testimonials) && $testimonials->count() > 0)
                    <a href="#testimoni" @click="mobileMenuOpen = false" class="px-3 py-2.5 rounded-lg text-sm font-medium text-[#44403C] hover:bg-[#F4EFEA] hover:text-[#E14D2A]">Testimoni</a>
                @endif
                @if(isset($faqs) && $faqs->count() > 0)
                    <a href="#faq" @click="mobileMenuOpen = false" class="px-3 py-2.5 rounded-lg text-sm font-medium text-[#44403C] hover:bg-[#F4EFEA] hover:text-[#E14D2A]">FAQ</a>
                @endif
                <div class="pt-3 flex flex-col gap-2">
                    <a href="{{ route('daftar.step1') }}" class="w-full text-center bg-[#E14D2A] hover:bg-[#C93B1A] text-white font-semibold py-3 rounded-lg text-sm shadow-xs transition-colors">
                        Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
