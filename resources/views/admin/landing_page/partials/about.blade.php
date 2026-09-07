<!-- About Lembaga Tab -->
<div x-show="tab === 'about'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
    <form action="{{ route('admin.landing-page.update-general') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="active_tab" value="about">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- Left Column: Edit Content Form (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-emerald-500 rounded-t-xl"></div>
                    <div class="flex items-center justify-between mb-5 mt-1">
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Narasi &amp; Filosofi Pembelajaran Lembaga
                        </h2>
                        <span class="text-[11px] font-mono text-slate-400 uppercase">Section Tentang Kami</span>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Deskripsi &amp; Filosofi Bimbingan</label>
                            <textarea name="tentang_kami" rows="8" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-3 px-4 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 transition-all resize-y leading-relaxed" placeholder="Tuliskan latar belakang, komitmen akademik, dan filosofi bimbingan belajar lembaga Anda...">{{ old('tentang_kami', $master->tentang_kami) }}</textarea>
                            <p class="text-xs text-slate-500">
                                Paragraf ini akan langsung tampil di kolom kanan bagian <strong>Tentang Lembaga</strong> pada halaman utama publik.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm py-2.5 px-6 rounded-lg shadow-sm transition-all flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Narasi Tentang Lembaga
                    </button>
                </div>
            </div>

            <!-- Right Column: Visual Guide & Structure Explanation (5 cols) -->
            <div class="lg:col-span-5 space-y-4">
                <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-200 mb-3 flex items-center gap-2">
                        <span>ℹ️</span> Panduan Tampilan Landing Page
                    </h3>
                    <div class="space-y-3 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        <div class="p-3 bg-slate-50 dark:bg-zinc-800 rounded-lg border border-slate-100 dark:border-zinc-700">
                            <span class="font-bold text-slate-800 dark:text-slate-200 block mb-1">Visi Institusi:</span>
                            <em>"Menjadi lembaga pendidikan terdepan yang mengintegrasikan keunggulan materi akademik dengan teknologi pembelajaran mutakhir untuk melahirkan generasi berprestasi."</em>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-zinc-800 rounded-lg border border-slate-100 dark:border-zinc-700">
                            <span class="font-bold text-slate-800 dark:text-slate-200 block mb-1">Misi Strategis:</span>
                            <ul class="list-disc pl-4 space-y-1">
                                <li>Penyediaan modul adaptif &amp; kurikulum terstruktur.</li>
                                <li>Evaluasi berkala dan simulasi CBT akurat (IRT).</li>
                                <li>Pendampingan intensif &amp; konsultasi jurusan PTN/Kedinasan.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
