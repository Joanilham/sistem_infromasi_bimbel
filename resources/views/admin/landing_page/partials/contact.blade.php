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
                                <svg class="w-3.5 h-3.5 text-pink-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                Instagram URL
                            </label>
                            <input type="text" name="instagram_url" value="{{ old('instagram_url', $master->instagram_url) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-pink-500 focus:border-pink-500 transition-all font-mono" placeholder="https://instagram.com/akun_lembaga">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                Facebook URL
                            </label>
                            <input type="text" name="facebook_url" value="{{ old('facebook_url', $master->facebook_url) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all font-mono" placeholder="https://facebook.com/akun_lembaga">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                YouTube URL
                            </label>
                            <input type="text" name="youtube_url" value="{{ old('youtube_url', $master->youtube_url) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500 transition-all font-mono" placeholder="https://youtube.com/@channel_lembaga">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-800 dark:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                                TikTok URL
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
                            <textarea name="alamat_lembaga" rows="3" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 transition-all resize-none leading-relaxed" placeholder="Contoh: Jl. Adi Sucipto No. 88, Sobo, Kec. Banyuwangi, Kabupaten Banyuwangi, Jawa Timur 68418">{{ old('alamat_lembaga', $master->alamat_lembaga) }}</textarea>
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
