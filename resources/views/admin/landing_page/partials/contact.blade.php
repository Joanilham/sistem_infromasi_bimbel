<!-- Contact & Socials Tab -->
<div x-show="tab === 'contact'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
    <form action="{{ route('admin.landing-page.update-general') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="active_tab" value="contact">
        <input type="hidden" name="wa_widget_form_submitted" value="1">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- Left Column: WhatsApp & Media Sosial (6 cols) -->
            <div class="lg:col-span-6 space-y-6">
                <!-- WhatsApp Widget & Customer Service -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-emerald-500 rounded-t-xl"></div>
                    <div class="flex items-center justify-between mb-5 mt-1">
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            WhatsApp CS &amp; Floating Widget
                        </h2>
                        <span class="text-[11px] font-mono text-emerald-600 bg-emerald-50 dark:bg-emerald-950/50 px-2 py-0.5 rounded font-semibold">Live Chat</span>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Nomor WhatsApp CS (Format: 62...)</label>
                            <input type="text" name="wa_number" value="{{ old('wa_number', $master->wa_number) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-mono" placeholder="628123456789">
                            <p class="text-[11px] text-slate-500">Gunakan awalan kode negara 62 tanpa spasi atau strip (+).</p>
                        </div>

                        <!-- WA Widget Settings -->
                        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-zinc-800">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200">Tombol Melayang (Floating Widget)</h3>
                                    <p class="text-[11px] text-slate-500">Tampilkan tombol WhatsApp melayang di sudut kanan bawah landing page.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer shrink-0 ml-3">
                                    <input type="checkbox" name="wa_widget_status" value="1" class="sr-only peer" {{ $master->wa_widget_status ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-emerald-300 dark:peer-focus:ring-emerald-800 dark:bg-zinc-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-zinc-600 peer-checked:bg-emerald-500"></div>
                                </label>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Pesan Sapaan Awal (Template Chat)</label>
                                <textarea name="wa_widget_message" rows="2" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 transition-all resize-none" placeholder="Halo Admin, saya ingin konsultasi mengenai pendaftaran bimbel...">{{ old('wa_widget_message', $master->wa_widget_message) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tautan Media Sosial -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-pink-500 rounded-t-xl"></div>
                    <div class="flex items-center justify-between mb-5 mt-1">
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-pink-500"></span>
                            Tautan Media Sosial Publik
                        </h2>
                        <span class="text-[11px] font-mono text-slate-400 uppercase">Social Icons</span>
                    </div>

                    <div class="space-y-3.5">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 flex items-center gap-1.5">
                                <span class="text-pink-600">📷</span> Instagram URL
                            </label>
                            <input type="text" name="instagram_url" value="{{ old('instagram_url', $master->instagram_url) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-pink-500 focus:border-pink-500 transition-all font-mono" placeholder="https://instagram.com/akun_lembaga">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 flex items-center gap-1.5">
                                <span class="text-blue-600">🌐</span> Facebook URL
                            </label>
                            <input type="text" name="facebook_url" value="{{ old('facebook_url', $master->facebook_url) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all font-mono" placeholder="https://facebook.com/akun_lembaga">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 flex items-center gap-1.5">
                                <span class="text-red-600">▶️</span> YouTube URL
                            </label>
                            <input type="text" name="youtube_url" value="{{ old('youtube_url', $master->youtube_url) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500 transition-all font-mono" placeholder="https://youtube.com/@channel_lembaga">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 flex items-center gap-1.5">
                                <span class="text-slate-800 dark:text-white">🎵</span> TikTok URL
                            </label>
                            <input type="text" name="tiktok_url" value="{{ old('tiktok_url', $master->tiktok_url) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-slate-500 focus:border-slate-500 transition-all font-mono" placeholder="https://tiktok.com/@akun_lembaga">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Kontak & Sekretariat (6 cols) -->
            <div class="lg:col-span-6 space-y-6">
                <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-amber-500 rounded-t-xl"></div>
                    <div class="flex items-center justify-between mb-5 mt-1">
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            Kontak &amp; Sekretariat (Footer)
                        </h2>
                        <span class="text-[11px] font-mono text-slate-400 uppercase">Footer Info</span>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Alamat Kantor / Sekretariat</label>
                            <textarea name="alamat_lembaga" rows="3" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 transition-all resize-none leading-relaxed" placeholder="Contoh: Jl. Boulevard Akademik No. 88, Gd. EduCenter Lt. 3, Jakarta Selatan">{{ old('alamat_lembaga', $master->alamat_lembaga) }}</textarea>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Email Kontak Publik</label>
                            <input type="email" name="email_kontak" value="{{ old('email_kontak', $master->email_kontak ?? $master->mail_from_address) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 transition-all font-mono" placeholder="admin@geniusedu.my.id">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Nomor Telepon / Hotline</label>
                            <input type="text" name="telepon_kantor" value="{{ old('telepon_kantor', $master->telepon_kantor ?? $master->wa_number) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 transition-all font-mono" placeholder="+62 812-3456-7890">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Jam Layanan Operasional</label>
                            <input type="text" name="jam_layanan" value="{{ old('jam_layanan', $master->jam_layanan) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 transition-all" placeholder="Senin – Sabtu (08.00 – 20.00 WIB)">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm py-2.5 px-6 rounded-lg shadow-sm transition-all flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Kontak &amp; Sosial
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
