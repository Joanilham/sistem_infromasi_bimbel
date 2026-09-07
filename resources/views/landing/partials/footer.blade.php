<!-- Footer Section -->
<footer class="bg-slate-950 pt-20 pb-12 text-slate-400 relative overflow-hidden w-full border-t border-slate-900">
    <!-- Ambient Glow in Footer -->
    <div class="absolute inset-0 pointer-events-none opacity-20">
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-orange-500/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 lg:gap-16 mb-16">
            
            <!-- Left Column: Brand Info -->
            <div class="md:col-span-6 lg:col-span-5 space-y-6">
                <!-- Brand Lockup -->
                <a href="{{ route('welcome') }}" class="flex items-center gap-3.5 group">
                    @if(isset($masterData) && $masterData->logo)
                        <div class="h-12 w-12 rounded-xl bg-white shadow-md flex items-center justify-center p-2 ring-1 ring-white/10 group-hover:scale-105 transition-transform">
                            <img src="{{ Storage::url($masterData->logo) }}" alt="{{ $masterData->nama_lembaga ?? config('app.name') }}" class="h-full w-full object-contain">
                        </div>
                    @else
                        <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center text-white font-black text-xl shadow-md">
                            {{ substr($masterData->nama_lembaga ?? config('app.name'), 0, 1) }}
                        </div>
                    @endif
                    <span class="font-extrabold text-2xl text-white tracking-tight">
                        {{ $masterData->nama_lembaga ?? config('app.name') }}
                    </span>
                </a>

                <p class="text-slate-400 text-sm sm:text-base leading-relaxed max-w-md font-normal">
                    {{ $masterData->hero_subtitle ?? 'Platform bimbingan belajar dan sistem manajemen akademik modern yang mengedepankan kualitas pembelajaran dan evaluasi berkala.' }}
                </p>

                <!-- Social Media Icons -->
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    @if($masterData && $masterData->wa_number)
                        <a href="https://wa.me/{{ $masterData->wa_number }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp" 
                           class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-[#25D366] hover:border-[#25D366] transition-all duration-300 hover:-translate-y-1">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M12.013 2.003c-5.506 0-9.98 4.475-9.98 9.982 0 1.761.458 3.473 1.328 4.996L2.016 22l5.161-1.353c1.472.801 3.123 1.222 4.836 1.222 5.503 0 9.977-4.475 9.977-9.982 0-5.507-4.474-9.984-9.977-9.984z"/>
                            </svg>
                        </a>
                    @endif
                    @if($masterData && $masterData->instagram_url)
                        <a href="{{ $masterData->instagram_url }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"
                           class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-gradient-to-tr hover:from-amber-500 hover:via-rose-500 hover:to-purple-600 hover:border-transparent transition-all duration-300 hover:-translate-y-1">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    @endif
                    @if($masterData && $masterData->facebook_url)
                        <a href="{{ $masterData->facebook_url }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"
                           class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-[#1877F2] hover:border-[#1877F2] transition-all duration-300 hover:-translate-y-1">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    @endif
                    @if($masterData && $masterData->youtube_url)
                        <a href="{{ $masterData->youtube_url }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube"
                           class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-[#FF0000] hover:border-[#FF0000] transition-all duration-300 hover:-translate-y-1">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Middle Column: Program Bimbingan -->
            <div class="md:col-span-3 lg:col-span-4">
                <h4 class="font-extrabold text-white text-xs uppercase tracking-widest mb-6">
                    Program Unggulan
                </h4>
                <ul class="space-y-3.5 mb-6">
                    @foreach($pakets->take(4) as $p)
                        <li>
                            <a href="{{ route('paket.detail', $p->id) }}" class="text-sm font-medium text-slate-400 hover:text-orange-400 transition-colors flex items-center gap-2.5 group">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-orange-400 transition-colors"></span>
                                <span class="truncate">{{ $p->nama_paket }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('paket.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-orange-400 hover:text-orange-300 transition-colors">
                    <span>Semua Program Bimbingan</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <!-- Right Column: Navigasi & Portal -->
            <div class="md:col-span-3 lg:col-span-3">
                <h4 class="font-extrabold text-white text-xs uppercase tracking-widest mb-6">
                    Akses Sistem
                </h4>
                <ul class="space-y-3.5 text-sm font-medium">
                    <li>
                        <a href="{{ route('login') }}" class="text-slate-400 hover:text-orange-400 transition-colors flex items-center gap-2">
                            <span>Masuk Portal Akun</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('daftar.step1') }}" class="text-slate-400 hover:text-orange-400 transition-colors flex items-center gap-2">
                            <span>Pendaftaran Siswa Baru</span>
                        </a>
                    </li>
                    <li>
                        <a href="#tentang" class="text-slate-400 hover:text-orange-400 transition-colors">
                            <span>Profil Institusi</span>
                        </a>
                    </li>
                    <li>
                        <button type="button" onclick="openModal('modal-tc')" class="text-slate-400 hover:text-orange-400 transition-colors text-left">
                            Syarat & Ketentuan
                        </button>
                    </li>
                    <li>
                        <button type="button" onclick="openModal('modal-privacy')" class="text-slate-400 hover:text-orange-400 transition-colors text-left">
                            Kebijakan Privasi
                        </button>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Bottom Copyright Bar -->
        <div class="pt-8 border-t border-slate-900 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs font-medium text-slate-500">
            <p>
                &copy; {{ date('Y') }} {{ $masterData->nama_lembaga ?? config('app.name') }}. All rights reserved.
            </p>
            <div class="flex items-center gap-6">
                <button type="button" onclick="openModal('modal-tc')" class="hover:text-slate-400 transition-colors">Syarat & Ketentuan</button>
                <button type="button" onclick="openModal('modal-privacy')" class="hover:text-slate-400 transition-colors">Kebijakan Privasi</button>
            </div>
        </div>
    </div>
</footer>
