<!-- Footer Section -->
    <footer class="bg-slate-900 pt-16 sm:pt-24 pb-12 relative overflow-hidden w-full">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Mobile Back to Top Button -->
            <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="md:hidden absolute -top-14 right-4 w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center shadow-2xl active:scale-90 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
            </button>            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 sm:gap-16 mb-12 sm:mb-24 mt-4">
                <div class="col-span-1 md:col-span-2 space-y-6 sm:space-y-8">
                    <a href="#" class="flex items-center gap-3 sm:gap-4">
                        @if($masterData && $masterData->logo)
                            <img src="{{ asset('storage/' . $masterData->logo) }}" alt="Logo" class="h-10 sm:h-14 w-auto object-contain bg-white rounded-xl p-1.5 sm:p-2">
                        @else
                            <div class="h-10 w-10 sm:h-14 sm:w-14 bg-white rounded-xl sm:rounded-2xl flex items-center justify-center text-indigo-600 font-black text-2xl sm:text-3xl">G</div>
                        @endif
                        <span class="font-black text-2xl sm:text-3xl text-white tracking-tight">{{ $masterData->nama_lembaga ?? 'Sistem Akademik' }}</span>
                    </a>
                    <p class="text-slate-400 text-sm sm:text-lg leading-relaxed max-w-sm font-medium">
                        {{ $masterData->hero_subtitle ?? 'Bimbingan belajar masa kini dengan sistem terpadu.' }}
                    </p>
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 mt-6">
                        @if($masterData && $masterData->wa_number)
                            <a href="https://wa.me/{{ $masterData->wa_number }}" target="_blank" aria-label="WhatsApp" class="inline-block">
                                <svg class="w-9 h-9 sm:w-10 sm:h-10 hover:-translate-y-1 hover:scale-105 transition-all drop-shadow-lg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="#25D366" d="M12.013 2.003c-5.506 0-9.98 4.475-9.98 9.982 0 1.761.458 3.473 1.328 4.996L2.016 22l5.161-1.353c1.472.801 3.123 1.222 4.836 1.222 5.503 0 9.977-4.475 9.977-9.982 0-5.507-4.474-9.984-9.977-9.984z"/>
                                    <path fill="#FFFFFF" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/>
                                </svg>
                            </a>
                        @endif
                        @if($masterData && $masterData->instagram_url)
                            <a href="{{ $masterData->instagram_url }}" target="_blank" aria-label="Instagram" class="inline-block">
                                <svg class="w-9 h-9 sm:w-10 sm:h-10 hover:-translate-y-1 hover:scale-105 transition-all drop-shadow-lg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                  <defs>
                                    <linearGradient id="ig-grad-footer" x1="0%" y1="100%" x2="100%" y2="0%">
                                      <stop offset="0%" stop-color="#f09433" />
                                      <stop offset="25%" stop-color="#e6683c" />
                                      <stop offset="50%" stop-color="#dc2743" />
                                      <stop offset="75%" stop-color="#cc2366" />
                                      <stop offset="100%" stop-color="#bc1888" />
                                    </linearGradient>
                                  </defs>
                                  <rect x="2" y="2" width="20" height="20" rx="6" fill="url(#ig-grad-footer)" />
                                  <path fill="#fff" d="M12 7.162c-2.668 0-4.838 2.17-4.838 4.838s2.17 4.838 4.838 4.838 4.838-2.17 4.838-4.838-2.17-4.838-4.838-4.838zm0 7.94c-1.71 0-3.102-1.392-3.102-3.102S10.29 8.898 12 8.898 15.102 10.29 15.102 12s-1.392 3.102-3.102 3.102zm3.252-6.52a1.155 1.155 0 11-2.31 0 1.155 1.155 0 012.31 0z"/>
                                  <path fill="#fff" d="M16.94 4.5H7.06C5.372 4.5 4 5.872 4 7.56v9.88C4 19.128 5.372 20.5 7.06 20.5h9.88c1.688 0 3.06-1.372 3.06-3.06V7.56C20 5.872 18.628 4.5 16.94 4.5zm1.56 12.94c0 .859-.701 1.56-1.56 1.56H7.06c-.859 0-1.56-.701-1.56-1.56V7.56c0-.859.701-1.56 1.56-1.56h9.88c.859 0 1.56.701 1.56 1.56v9.88z"/>
                                </svg>
                            </a>
                        @endif
                        @if($masterData && $masterData->facebook_url)
                            <a href="{{ $masterData->facebook_url }}" target="_blank" aria-label="Facebook" class="inline-block">
                                <svg class="w-9 h-9 sm:w-10 sm:h-10 hover:-translate-y-1 hover:scale-105 transition-all drop-shadow-lg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                  <circle cx="12" cy="12" r="11" fill="#FFF"/>
                                  <path fill="#1877F2" d="M12 0C5.373 0 0 5.373 0 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12c0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </a>
                        @endif
                        @if($masterData && $masterData->youtube_url)
                            <a href="{{ $masterData->youtube_url }}" target="_blank" aria-label="YouTube" class="inline-block">
                                <svg class="w-9 h-9 sm:w-10 sm:h-10 hover:-translate-y-1 hover:scale-105 transition-all drop-shadow-lg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                  <circle cx="12" cy="12" r="10" fill="#FFF"/>
                                  <path fill="#FF0000" d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                            </a>
                        @endif
                        @if($masterData && $masterData->tiktok_url)
                            <a href="{{ $masterData->tiktok_url }}" target="_blank" aria-label="TikTok" class="inline-block">
                                <svg class="w-9 h-9 sm:w-10 sm:h-10 hover:-translate-y-1 hover:scale-105 transition-all drop-shadow-lg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                  <circle cx="12" cy="12" r="11" fill="#000"/>
                                  <path fill="#FFF" d="M17.5 7.5a4 4 0 0 1-3.5-3.5h-2.5v10.5a3 3 0 1 1-3-3 2.9 2.9 0 0 1 1.5.4v-2.8a5.5 5.5 0 1 0 4 5.4v-7.5a6.5 6.5 0 0 0 3.5 1.5v-2.5a4 4 0 0 1-1.5-.4z"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-span-1">
                    <h4 class="font-black text-white mb-6 uppercase tracking-[0.2em] text-xs">Program</h4>
                    <ul class="space-y-4 mb-8">
                        @foreach($pakets->take(4) as $p)
                            <li><a href="{{ route('paket.detail', $p->id) }}" class="text-slate-400 hover:text-white transition-colors text-sm font-bold flex items-center gap-3 group">
                                <span class="w-2 h-2 rounded-full bg-slate-800 group-hover:bg-indigo-500 transition-all"></span>
                                {{ $p->nama_paket }}
                            </a></li>
                        @endforeach
                    </ul>
                    <a href="{{ route('paket.index') }}" class="inline-flex items-center gap-3 text-[10px] font-black text-white bg-slate-800 hover:bg-indigo-600 px-5 py-3 rounded-2xl transition-all uppercase tracking-widest active:scale-95">
                        Semua Program &rarr;
                    </a>
                </div>

                <div class="col-span-1">
                    <h4 class="font-black text-white mb-6 uppercase tracking-[0.2em] text-xs">Pendaftaran & Akun</h4>
                    <ul class="space-y-4 text-sm font-bold flex flex-col items-start">
                        <li><a href="{{ route('welcome') }}" class="text-slate-400 hover:text-white transition-colors">Beranda Utama</a></li>
                        <li><a href="{{ route('daftar.step1') }}" class="text-slate-400 hover:text-white transition-colors">Daftar Sekarang</a></li>
                        <li><a href="{{ route('login') }}" class="text-slate-400 hover:text-white transition-colors">Masuk Ke Portal</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.2em]">
                    &copy; {{ date('Y') }} {{ $masterData->nama_lembaga ?? 'Sistem Akademik' }}. Sistem Manajemen Pendidikan Terpadu.
                </p>
                <div class="flex items-center gap-6 text-[10px] font-black text-slate-600 uppercase tracking-widest">
                    <span onclick="openModal('modal-tc')" class="hover:text-white transition-colors cursor-pointer">T&C</span>
                    <span onclick="openModal('modal-privacy')" class="hover:text-white transition-colors cursor-pointer">Privacy Policy</span>
                </div>
            </div>
        </div>
        
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-600/10 rounded-full blur-[100px]"></div>
    </footer>
