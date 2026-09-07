<!-- Hero & Identitas Settings Tab -->
<div x-show="tab === 'general'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
    <form action="{{ route('admin.landing-page.update-general') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        @csrf
        <input type="hidden" name="active_tab" value="general">

        <!-- Left Column: Identitas & Trust Stats (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Identitas Instansi -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm relative">
                <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500 rounded-t-xl"></div>
                <div class="flex items-center justify-between mb-5 mt-1">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        Identitas Instansi
                    </h2>
                    <span class="text-[11px] font-mono text-slate-400 uppercase">Header &amp; Brand</span>
                </div>

                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Nama Instansi / Lembaga</label>
                        <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga', $master->nama_lembaga) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium" placeholder="Contoh: GENIUS EDUCATION">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Logo Instansi</label>
                        <div class="relative rounded-lg overflow-hidden aspect-video bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 flex items-center justify-center p-4">
                            @if($master->logo)
                                <img id="logo-preview" src="{{ asset('storage/' . $master->logo) }}" class="max-h-20 w-auto object-contain">
                                <div id="logo-placeholder" class="hidden flex-col items-center justify-center text-slate-400 gap-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-xs font-medium">Belum Ada Logo</span>
                                </div>
                            @else
                                <img id="logo-preview" class="max-h-20 w-auto object-contain hidden">
                                <div id="logo-placeholder" class="flex flex-col items-center justify-center text-slate-400 gap-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-xs font-medium">Belum Ada Logo</span>
                                </div>
                            @endif
                            <input type="file" name="logo" class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewImage(event, 'logo-preview', 'logo-placeholder')">
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1">Klik pada area preview gambar untuk memilih file logo baru (PNG/JPG/SVG/WEBP).</p>
                    </div>
                </div>
            </div>

            <!-- Indikator Pencapaian & Statistik (Trust Numbers) -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm relative">
                <div class="absolute top-0 left-0 w-full h-1 bg-amber-500 rounded-t-xl"></div>
                <div class="flex items-center justify-between mb-5 mt-1">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Indikator Prestasi (Hero Stats)
                    </h2>
                    <span class="text-[11px] font-mono text-slate-400 uppercase">Trust Strip</span>
                </div>

                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Total Siswa Terdaftar</label>
                        <input type="text" name="stats_siswa" value="{{ old('stats_siswa', $master->stats_siswa) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 transition-all font-mono" placeholder="1,500+">
                        <p class="text-[11px] text-slate-500">Ditampilkan di baris angka kepercayaan pertama pada hero section.</p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Tingkat Kelulusan</label>
                        <input type="text" name="stats_tutor" value="{{ old('stats_tutor', $master->stats_tutor) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 transition-all font-mono" placeholder="98.4%">
                        <p class="text-[11px] text-slate-500">Persentase kelulusan siswa ke sekolah / PTN tujuan.</p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Rating Kepuasan</label>
                        <input type="text" name="stats_kepuasan" value="{{ old('stats_kepuasan', $master->stats_kepuasan) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 transition-all font-mono" placeholder="4.9/5">
                        <p class="text-[11px] text-slate-500">Indeks kepuasan peserta bimbingan dan wali murid.</p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-indigo-50 dark:bg-indigo-900/10 rounded-xl border border-indigo-100 dark:border-indigo-900/30">
                <p class="text-xs text-indigo-800 dark:text-indigo-300 leading-relaxed font-medium">
                    <strong class="block mb-0.5">ℹ️ Sinkronisasi Otomatis</strong>
                    Nama dan logo lembaga akan otomatis terpasang pada navbar, hero title, footer, dan tab browser secara terintegrasi.
                </p>
            </div>
        </div>

        <!-- Right Column: Visual & Narasi Hero (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm relative">
                <div class="absolute top-0 left-0 w-full h-1 bg-slate-800 dark:bg-slate-200 rounded-t-xl"></div>
                <div class="flex items-center justify-between mb-5 mt-1">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-slate-800 dark:bg-slate-200"></span>
                        Visual &amp; Narasi Hero
                    </h2>
                    <span class="text-[11px] font-mono text-slate-400 uppercase">Main Showcase</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Headline Utama</label>
                            <input type="text" name="hero_title" value="{{ old('hero_title', $master->hero_title) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Contoh: Belajar Lebih Terarah. Bertumbuh Lebih Percaya Diri.">
                            <p class="text-[11px] text-slate-500">Judul besar pertama yang dilihat pengunjung saat membuka halaman depan.</p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Deskripsi Sub-headline</label>
                            <textarea name="hero_subtitle" rows="3" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none" placeholder="Deskripsikan pendekatan pembelajaran dan nilai tambah bimbingan...">{{ old('hero_subtitle', $master->hero_subtitle) }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Teks Tombol CTA</label>
                                <input type="text" name="hero_cta_text" value="{{ old('hero_cta_text', $master->hero_cta_text) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Jelajahi Program">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Link Tombol CTA</label>
                                <input type="text" name="hero_cta_link" value="{{ old('hero_cta_link', $master->hero_cta_link) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-mono" placeholder="#program">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Gambar Slide Utama / Hero</label>
                            <div class="relative rounded-lg overflow-hidden aspect-[4/3] bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 flex items-center justify-center p-2">
                                @if($master->hero_image)
                                    <img id="hero-preview" src="{{ asset('storage/' . $master->hero_image) }}" class="w-full h-full object-cover rounded-md">
                                    <div id="hero-placeholder" class="hidden flex-col items-center justify-center h-full text-slate-400 gap-2">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-xs font-medium">Unggah Foto Hero</span>
                                    </div>
                                @else
                                    <img id="hero-preview" class="w-full h-full object-cover rounded-md hidden">
                                    <div id="hero-placeholder" class="flex flex-col items-center justify-center h-full text-slate-400 gap-2">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-xs font-medium">Unggah Foto Hero</span>
                                    </div>
                                @endif
                                <!-- Live Preview Overlay -->
                                <div class="absolute inset-2 bg-black rounded-md pointer-events-none transition-opacity" :style="{ opacity: overlayOpacity / 100 }"></div>
                                <input type="file" name="hero_image" class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewImage(event, 'hero-preview', 'hero-placeholder')">
                            </div>
                            <p class="text-[11px] text-slate-500">Format landscape rekomendasi rasio 4:3 atau 16:11.</p>
                        </div>

                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Kecerahan Overlay Gelap</label>
                                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded" x-text="overlayOpacity + '%'"></span>
                            </div>
                            <input type="range" name="hero_overlay_opacity" min="0" max="90" step="5" x-model="overlayOpacity" class="w-full h-2 bg-slate-200 dark:bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                            <p class="text-[11px] text-slate-500 italic">*Mengatur tingkat kontras gelap di atas foto slide.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Save Button -->
            <div class="flex justify-end pt-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm py-2.5 px-6 rounded-lg shadow-sm transition-all flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Perubahan Hero &amp; Identitas
                </button>
            </div>
        </div>
    </form>
</div>
