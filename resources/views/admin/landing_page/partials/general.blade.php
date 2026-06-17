<!-- General Settings Tab -->
    <div x-show="tab === 'general'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <form action="{{ route('admin.landing-page.update-general') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf
            <!-- Left Column: Socials & Contacts -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Identitas Instansi -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500 rounded-t-xl"></div>
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-6 mt-2">Identitas Instansi</h2>
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Instansi / Lembaga</label>
                            <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga', $master->nama_lembaga) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Genius Education">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Logo Instansi</label>
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
                            <p class="text-xs text-slate-500 mt-1">Klik pada area gambar untuk mengubah logo.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-emerald-500 rounded-t-xl"></div>
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-6 mt-2">Koneksi & Sosial</h2>
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">WhatsApp CS (Format: 62...)</label>
                            <input type="text" name="wa_number" value="{{ old('wa_number', $master->wa_number) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 transition-all" placeholder="628123456789">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Instagram URL</label>
                            <input type="text" name="instagram_url" value="{{ old('instagram_url', $master->instagram_url) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-pink-500 focus:border-pink-500 transition-all" placeholder="https://instagram.com/...">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Facebook URL</label>
                            <input type="text" name="facebook_url" value="{{ old('facebook_url', $master->facebook_url) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="https://facebook.com/...">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">YouTube URL</label>
                            <input type="text" name="youtube_url" value="{{ old('youtube_url', $master->youtube_url) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500 transition-all" placeholder="https://youtube.com/...">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">TikTok URL</label>
                            <input type="text" name="tiktok_url" value="{{ old('tiktok_url', $master->tiktok_url) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-slate-500 focus:border-slate-500 transition-all" placeholder="https://tiktok.com/...">
                        </div>

                        <!-- WA Widget Settings -->
                        <div class="mt-6 pt-6 border-t border-slate-200 dark:border-zinc-800">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-sm font-semibold text-emerald-600">WhatsApp Widget</h3>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="wa_widget_status" value="1" class="sr-only peer" {{ $master->wa_widget_status ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-emerald-300 dark:peer-focus:ring-emerald-800 dark:bg-zinc-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-zinc-600 peer-checked:bg-emerald-500"></div>
                                </label>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Pesan Sapaan (Greeting)</label>
                                <textarea name="wa_widget_message" rows="2" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 transition-all resize-none" placeholder="Halo Admin...">{{ old('wa_widget_message', $master->wa_widget_message) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-blue-50 dark:bg-blue-900/10 rounded-lg border border-blue-100 dark:border-blue-900/30">
                    <p class="text-sm text-blue-700 dark:text-blue-300 leading-relaxed font-medium">
                        <strong class="block mb-1">Info Sinkronisasi</strong>
                        Nama Lembaga dan Logo Utama yang diubah di sini akan otomatis sinkron dengan pengaturan master utama.
                    </p>
                </div>
            </div>

            <!-- Right Column: Hero Section -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-slate-800 dark:bg-slate-200 rounded-t-xl"></div>
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-6 mt-2">Visual & Narasi Hero</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Headline Utama</label>
                                <input type="text" name="hero_title" value="{{ old('hero_title', $master->hero_title) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Bimbingan Belajar Genius">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Deskripsi Sub-headline</label>
                                <textarea name="hero_subtitle" rows="3" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none" placeholder="Deskripsikan visi utama lembaga Anda...">{{ old('hero_subtitle', $master->hero_subtitle) }}</textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Teks Tombol CTA</label>
                                    <input type="text" name="hero_cta_text" value="{{ old('hero_cta_text', $master->hero_cta_text) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Daftar Sekarang">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Link Tombol CTA</label>
                                    <input type="text" name="hero_cta_link" value="{{ old('hero_cta_link', $master->hero_cta_link) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="/pendaftaran">
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Gambar Latar Belakang</label>
                                <div class="relative rounded-lg overflow-hidden aspect-video bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 flex items-center justify-center p-2">
                                    @if($master->hero_image)
                                        <img id="hero-preview" src="{{ asset('storage/' . $master->hero_image) }}" class="w-full h-full object-cover rounded-md">
                                        <div id="hero-placeholder" class="hidden flex-col items-center justify-center h-full text-slate-400 gap-2">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-xs font-medium">Unggah Gambar Latar</span>
                                        </div>
                                    @else
                                        <img id="hero-preview" class="w-full h-full object-cover rounded-md hidden">
                                        <div id="hero-placeholder" class="flex flex-col items-center justify-center h-full text-slate-400 gap-2">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-xs font-medium">Unggah Gambar Latar</span>
                                        </div>
                                    @endif
                                    <!-- Live Preview Overlay -->
                                    <div class="absolute inset-2 bg-black rounded-md pointer-events-none transition-opacity" :style="{ opacity: overlayOpacity / 100 }"></div>
                                    <input type="file" name="hero_image" class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewImage(event, 'hero-preview', 'hero-placeholder')">
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Kecerahan Overlay</label>
                                    <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded" x-text="overlayOpacity + '%'"></span>
                                </div>
                                <input type="range" name="hero_overlay_opacity" min="0" max="90" step="5" x-model="overlayOpacity" class="w-full h-2 bg-slate-200 dark:bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                                <p class="text-xs text-slate-500 italic mt-1">*Meningkatkan overlay mempermudah pembacaan teks putih di atas gambar.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-slate-800 dark:bg-slate-200 rounded-t-xl"></div>
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 mt-2">Tentang Lembaga</h2>
                    <textarea name="tentang_kami" rows="6" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-3 px-4 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none" placeholder="Tuliskan sejarah, visi, dan pencapaian lembaga...">{{ old('tentang_kami', $master->tentang_kami) }}</textarea>
                </div>

                <!-- Sticky Save Button -->
                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-6 rounded-lg shadow-sm transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Semua Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
