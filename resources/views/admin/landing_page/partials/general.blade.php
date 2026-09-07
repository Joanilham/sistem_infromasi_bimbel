<!-- Hero & Identitas Settings Tab -->
<div x-show="tab === 'general'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
     x-data="{
         activeSlideTab: 1,
         overlayOpacity: {{ ($master->hero_overlay_opacity ?? 0.5) * 100 }},
         slide1Src: '{{ $master->hero_image ? asset('storage/' . $master->hero_image) : asset('images/hero-slide-1.png') }}',
         slide2Src: '{{ $master->hero_image_2 ? asset('storage/' . $master->hero_image_2) : asset('images/hero-slide-2.png') }}',
         slide3Src: '{{ $master->hero_image_3 ? asset('storage/' . $master->hero_image_3) : asset('images/hero-slide-3.png') }}',
         previewSlide(e, slideNum) {
             const file = e.target.files && e.target.files[0];
             if(!file) return;
             const reader = new FileReader();
             reader.onload = (event) => {
                 if(slideNum === 1) this.slide1Src = event.target.result;
                 if(slideNum === 2) this.slide2Src = event.target.result;
                 if(slideNum === 3) this.slide3Src = event.target.result;
             };
             reader.readAsDataURL(file);
         }
     }">

    <form action="{{ route('admin.landing-page.update-general') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <input type="hidden" name="active_tab" value="general">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left Column: Identitas & Trust Stats (5 Cols) -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Identitas Instansi -->
                <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-slate-200/90 dark:border-zinc-800 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-500"></div>
                    <div class="flex items-center justify-between mb-5 mt-1">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-100 dark:border-indigo-800">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900 dark:text-white leading-none">Identitas Instansi</h2>
                                <p class="text-[11px] text-slate-400 mt-0.5">Nama brand dan logo utama lembaga</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Nama Instansi / Lembaga</label>
                            <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga', $master->nama_lembaga) }}" class="w-full bg-slate-50/50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm py-2.5 px-3.5 focus:bg-white dark:focus:bg-zinc-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-semibold text-slate-800 dark:text-slate-100" placeholder="Contoh: GENIUS EDUCATION">
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Logo Instansi</label>
                            <div class="p-3 bg-slate-50/60 dark:bg-zinc-950/60 border border-slate-200/80 dark:border-zinc-800 rounded-xl">
                                <div class="flex items-center gap-4">
                                    <div class="relative w-20 h-20 rounded-xl overflow-hidden bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 flex items-center justify-center p-2 shrink-0 shadow-xs group">
                                        @if($master->logo)
                                            <img id="logo-preview" src="{{ asset('storage/' . $master->logo) }}" class="max-h-full max-w-full object-contain">
                                            <div id="logo-placeholder" class="hidden flex-col items-center justify-center text-slate-400 gap-1 text-[10px]">
                                                <span>No Logo</span>
                                            </div>
                                        @else
                                            <img id="logo-preview" class="max-h-full max-w-full object-contain hidden">
                                            <div id="logo-placeholder" class="flex flex-col items-center justify-center text-slate-400 gap-1 text-[10px] text-center">
                                                <svg class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <span>Pilih File</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <label class="inline-flex items-center gap-2 px-3.5 py-2 bg-white dark:bg-zinc-800 hover:bg-slate-100 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-lg shadow-xs cursor-pointer transition-all">
                                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                            Unggah Logo Baru
                                            <input type="file" name="logo" class="hidden" onchange="previewImage(event, 'logo-preview', 'logo-placeholder')">
                                        </label>
                                        <p class="text-[11px] text-slate-400 mt-1.5 leading-snug">Format PNG / SVG transparan direkomendasikan.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Indikator Pencapaian & Statistik (Trust Numbers) -->
                <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-slate-200/90 dark:border-zinc-800 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-amber-500"></div>
                    <div class="flex items-center justify-between mb-5 mt-1">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-100 dark:border-amber-800">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900 dark:text-white leading-none">Indikator Prestasi (Hero Stats)</h2>
                                <p class="text-[11px] text-slate-400 mt-0.5">3 metrik kredibilitas di bawah tombol utama</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div class="p-3.5 bg-slate-50/60 dark:bg-zinc-950/60 border border-slate-200/80 dark:border-zinc-800 rounded-xl space-y-1.5">
                            <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Siswa Terdaftar</span>
                            <input type="text" name="stats_siswa" value="{{ old('stats_siswa', $master->stats_siswa ?? '1,000+') }}" class="w-full bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-lg text-sm py-1.5 px-2.5 font-mono font-bold text-slate-800 dark:text-slate-100 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" placeholder="1,000+">
                            <span class="text-[10px] text-slate-400 block leading-tight">Mendukung simbol (cth: 1,000+ atau 500+)</span>
                        </div>

                        <div class="p-3.5 bg-slate-50/60 dark:bg-zinc-950/60 border border-slate-200/80 dark:border-zinc-800 rounded-xl space-y-1.5">
                            <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kelulusan</span>
                            <input type="text" name="stats_tutor" value="{{ old('stats_tutor', $master->stats_tutor ?? '98%') }}" class="w-full bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-lg text-sm py-1.5 px-2.5 font-mono font-bold text-slate-800 dark:text-slate-100 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" placeholder="98%">
                            <span class="text-[10px] text-slate-400 block leading-tight">Mendukung persentase (cth: 98% atau 99.4%)</span>
                        </div>

                        <div class="p-3.5 bg-slate-50/60 dark:bg-zinc-950/60 border border-slate-200/80 dark:border-zinc-800 rounded-xl space-y-1.5">
                            <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Rating</span>
                            <input type="text" name="stats_kepuasan" value="{{ old('stats_kepuasan', $master->stats_kepuasan ?? '100%') }}" class="w-full bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-lg text-sm py-1.5 px-2.5 font-mono font-bold text-slate-800 dark:text-slate-100 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" placeholder="100% atau 4.9/5">
                            <span class="text-[10px] text-slate-400 block leading-tight">Mendukung skala (cth: 100% atau 4.9/5)</span>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="p-4 bg-indigo-50/70 dark:bg-indigo-950/30 rounded-2xl border border-indigo-100 dark:border-indigo-900/40 flex items-start gap-3">
                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs text-indigo-950 dark:text-indigo-200 leading-relaxed font-medium">
                        <strong>Kustomisasi Multi-Foto Hero:</strong> Anda dapat mengunggah hingga <strong>3 foto berbeda</strong> untuk slider interaktif di sebelah kanan headline. Jika foto belum diunggah, sistem otomatis memakai fotografi autentik bimbingan standar.
                    </p>
                </div>
            </div>

            <!-- Right Column: Visual & Narasi Hero + Multi-Slide Manager (7 Cols) -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Card 1: Narasi & Tombol CTA -->
                <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-slate-200/90 dark:border-zinc-800 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-stone-700 dark:bg-stone-300"></div>
                    <div class="flex items-center justify-between mb-5 mt-1">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-stone-100 dark:bg-zinc-800 text-stone-700 dark:text-stone-300 flex items-center justify-center border border-stone-200 dark:border-zinc-700">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900 dark:text-white leading-none">Narasi Utama &amp; Tombol Aksi</h2>
                                <p class="text-[11px] text-slate-400 mt-0.5">Teks display dan tombol ajakan pendaftaran</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Headline Utama (Judul Besar)</label>
                            <input type="text" name="hero_title" value="{{ old('hero_title', $master->hero_title) }}" class="w-full bg-slate-50/50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm py-2.5 px-3.5 focus:bg-white dark:focus:bg-zinc-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-bold text-slate-800 dark:text-slate-100" placeholder="Belajar Lebih Terarah. Bertumbuh Lebih Percaya Diri.">
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Deskripsi Sub-headline</label>
                            <textarea name="hero_subtitle" rows="3" class="w-full bg-slate-50/50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm py-2.5 px-3.5 focus:bg-white dark:focus:bg-zinc-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all resize-none text-slate-700 dark:text-slate-200 leading-relaxed" placeholder="Deskripsikan pendekatan pembelajaran dan nilai tambah bimbingan...">{{ old('hero_subtitle', $master->hero_subtitle) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-1">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Teks Tombol Utama</label>
                                <input type="text" name="hero_cta_text" value="{{ old('hero_cta_text', $master->hero_cta_text) }}" class="w-full bg-slate-50/50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm py-2 px-3 focus:bg-white dark:focus:bg-zinc-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-semibold" placeholder="Jelajahi Program">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Target Tautan / Link</label>
                                <input type="text" name="hero_cta_link" value="{{ old('hero_cta_link', $master->hero_cta_link) }}" class="w-full bg-slate-50/50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm py-2 px-3 focus:bg-white dark:focus:bg-zinc-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-mono text-xs" placeholder="#program">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Interactive Multi-Slide Hero Photo Manager -->
                <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-slate-200/90 dark:border-zinc-800 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-teal-500"></div>
                    <div class="flex items-center justify-between mb-5 mt-1">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center border border-teal-100 dark:border-teal-800">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900 dark:text-white leading-none">Galeri Slider Hero (Multi-Foto)</h2>
                                <p class="text-[11px] text-slate-400 mt-0.5">Kelola 3 slide foto yang berganti otomatis di landing page</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-mono font-bold bg-teal-50 dark:bg-teal-950/50 text-teal-700 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800">
                            3 Slide Aktif
                        </span>
                    </div>

                    <!-- Slide Selector Tabs & Prominent Actions -->
                    <div class="space-y-4">
                        <!-- 3 Slide Cards Grid with Direct Upload Buttons -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <!-- Slide 1 Card -->
                            <div @click="activeSlideTab = 1" 
                                 :class="activeSlideTab === 1 ? 'ring-2 ring-teal-500 border-teal-200 bg-teal-50/30 dark:bg-teal-950/20' : 'border-slate-200 dark:border-zinc-800 bg-slate-50/60 dark:bg-zinc-950/60 hover:border-slate-300'"
                                 class="p-3.5 rounded-2xl border transition-all cursor-pointer flex flex-col justify-between space-y-2.5">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-5 h-5 rounded-md bg-teal-600 text-white font-mono text-[10px] font-bold flex items-center justify-center">1</span>
                                            <span class="text-xs font-bold text-slate-900 dark:text-white">Slide Utama</span>
                                        </div>
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-md {{ $master->hero_image ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-zinc-800 dark:text-slate-400' }}">
                                            {{ $master->hero_image ? 'Kustom' : 'Default' }}
                                        </span>
                                    </div>
                                    <div class="aspect-[16/10] rounded-xl overflow-hidden bg-stone-900 border border-slate-200/80 dark:border-zinc-700 relative">
                                        <img :src="slide1Src" class="w-full h-full object-cover">
                                    </div>
                                </div>
                                <div class="space-y-1.5 pt-1">
                                    <label class="w-full py-2 px-3 rounded-xl bg-teal-600 hover:bg-teal-500 text-white font-semibold text-xs cursor-pointer shadow-xs transition-colors flex items-center justify-center gap-1.5 text-center">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        <span>Ganti Foto 1</span>
                                        <input type="file" name="hero_image" class="hidden" @change="previewSlide($event, 1)">
                                    </label>
                                    @if($master->hero_image)
                                        <label class="w-full py-1 text-center text-[11px] text-rose-500 hover:text-rose-600 hover:underline cursor-pointer flex items-center justify-center gap-1">
                                            <input type="checkbox" name="delete_hero_image_1" value="1" class="w-3 h-3 text-rose-600 rounded">
                                            Reset ke Foto Default
                                        </label>
                                    @endif
                                </div>
                            </div>

                            <!-- Slide 2 Card -->
                            <div @click="activeSlideTab = 2" 
                                 :class="activeSlideTab === 2 ? 'ring-2 ring-teal-500 border-teal-200 bg-teal-50/30 dark:bg-teal-950/20' : 'border-slate-200 dark:border-zinc-800 bg-slate-50/60 dark:bg-zinc-950/60 hover:border-slate-300'"
                                 class="p-3.5 rounded-2xl border transition-all cursor-pointer flex flex-col justify-between space-y-2.5">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-5 h-5 rounded-md bg-teal-600 text-white font-mono text-[10px] font-bold flex items-center justify-center">2</span>
                                            <span class="text-xs font-bold text-slate-900 dark:text-white">Slide Fasilitas</span>
                                        </div>
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-md {{ $master->hero_image_2 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-zinc-800 dark:text-slate-400' }}">
                                            {{ $master->hero_image_2 ? 'Kustom' : 'Default' }}
                                        </span>
                                    </div>
                                    <div class="aspect-[16/10] rounded-xl overflow-hidden bg-stone-900 border border-slate-200/80 dark:border-zinc-700 relative">
                                        <img :src="slide2Src" class="w-full h-full object-cover">
                                    </div>
                                </div>
                                <div class="space-y-1.5 pt-1">
                                    <label class="w-full py-2 px-3 rounded-xl bg-teal-600 hover:bg-teal-500 text-white font-semibold text-xs cursor-pointer shadow-xs transition-colors flex items-center justify-center gap-1.5 text-center">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        <span>Ganti Foto 2</span>
                                        <input type="file" name="hero_image_2" class="hidden" @change="previewSlide($event, 2)">
                                    </label>
                                    @if($master->hero_image_2)
                                        <label class="w-full py-1 text-center text-[11px] text-rose-500 hover:text-rose-600 hover:underline cursor-pointer flex items-center justify-center gap-1">
                                            <input type="checkbox" name="delete_hero_image_2" value="1" class="w-3 h-3 text-rose-600 rounded">
                                            Reset ke Foto Default
                                        </label>
                                    @endif
                                </div>
                            </div>

                            <!-- Slide 3 Card -->
                            <div @click="activeSlideTab = 3" 
                                 :class="activeSlideTab === 3 ? 'ring-2 ring-teal-500 border-teal-200 bg-teal-50/30 dark:bg-teal-950/20' : 'border-slate-200 dark:border-zinc-800 bg-slate-50/60 dark:bg-zinc-950/60 hover:border-slate-300'"
                                 class="p-3.5 rounded-2xl border transition-all cursor-pointer flex flex-col justify-between space-y-2.5">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-5 h-5 rounded-md bg-teal-600 text-white font-mono text-[10px] font-bold flex items-center justify-center">3</span>
                                            <span class="text-xs font-bold text-slate-900 dark:text-white">Slide Mentor</span>
                                        </div>
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-md {{ $master->hero_image_3 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-zinc-800 dark:text-slate-400' }}">
                                            {{ $master->hero_image_3 ? 'Kustom' : 'Default' }}
                                        </span>
                                    </div>
                                    <div class="aspect-[16/10] rounded-xl overflow-hidden bg-stone-900 border border-slate-200/80 dark:border-zinc-700 relative">
                                        <img :src="slide3Src" class="w-full h-full object-cover">
                                    </div>
                                </div>
                                <div class="space-y-1.5 pt-1">
                                    <label class="w-full py-2 px-3 rounded-xl bg-teal-600 hover:bg-teal-500 text-white font-semibold text-xs cursor-pointer shadow-xs transition-colors flex items-center justify-center gap-1.5 text-center">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        <span>Ganti Foto 3</span>
                                        <input type="file" name="hero_image_3" class="hidden" @change="previewSlide($event, 3)">
                                    </label>
                                    @if($master->hero_image_3)
                                        <label class="w-full py-1 text-center text-[11px] text-rose-500 hover:text-rose-600 hover:underline cursor-pointer flex items-center justify-center gap-1">
                                            <input type="checkbox" name="delete_hero_image_3" value="1" class="w-3 h-3 text-rose-600 rounded">
                                            Reset ke Foto Default
                                        </label>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Live Interactive Visual Stage Preview -->
                        <div class="relative rounded-2xl overflow-hidden aspect-[16/10] bg-stone-900 border border-slate-200 dark:border-zinc-800 shadow-inner group">
                            <!-- Image Frame -->
                            <img :src="activeSlideTab === 1 ? slide1Src : (activeSlideTab === 2 ? slide2Src : slide3Src)" 
                                 class="w-full h-full object-cover object-center transition-all duration-300">
                            
                            <!-- Live Overlay Layer -->
                            <div class="absolute inset-0 bg-black pointer-events-none transition-opacity duration-200" 
                                 :style="{ opacity: overlayOpacity / 100 }"></div>

                            <!-- Floating Badges on Preview -->
                            <div class="absolute top-3.5 left-3.5 flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-lg bg-black/75 backdrop-blur-md text-white text-[11px] font-mono font-bold tracking-wider uppercase border border-white/10"
                                      x-text="'Pratinjau Slide 0' + activeSlideTab">
                                </span>
                                <span class="px-2 py-1 rounded-lg bg-black/60 backdrop-blur-md text-stone-300 text-[10px] font-mono">
                                    Rasio 16:10 / 4:3
                                </span>
                            </div>

                            <!-- Indicator Dots at Bottom -->
                            <div class="absolute bottom-3 inset-x-0 flex items-center justify-center gap-2">
                                <span @click="activeSlideTab = 1" :class="activeSlideTab === 1 ? 'w-6 bg-teal-400' : 'w-2 bg-white/60'" class="h-2 rounded-full transition-all cursor-pointer"></span>
                                <span @click="activeSlideTab = 2" :class="activeSlideTab === 2 ? 'w-6 bg-teal-400' : 'w-2 bg-white/60'" class="h-2 rounded-full transition-all cursor-pointer"></span>
                                <span @click="activeSlideTab = 3" :class="activeSlideTab === 3 ? 'w-6 bg-teal-400' : 'w-2 bg-white/60'" class="h-2 rounded-full transition-all cursor-pointer"></span>
                            </div>
                        </div>

                        <!-- Overlay Opacity Slider -->
                        <div class="pt-3 border-t border-slate-100 dark:border-zinc-800">
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    Kecerahan Overlay Kontras Foto
                                </label>
                                <span class="text-xs font-bold text-teal-700 bg-teal-50 dark:bg-teal-950/60 px-2.5 py-0.5 rounded-md border border-teal-200/80 dark:border-teal-800 font-mono" x-text="overlayOpacity + '%'"></span>
                            </div>
                            <input type="range" name="hero_overlay_opacity" min="0" max="90" step="5" x-model="overlayOpacity" class="w-full h-2 bg-slate-200 dark:bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-teal-600">
                            <p class="text-[11px] text-slate-400 mt-1.5">Tingkat transparansi lapisan gelap di atas foto slide agar teks terbaca jelas.</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm py-3 px-8 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Semua Perubahan Hero &amp; Identitas
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>
