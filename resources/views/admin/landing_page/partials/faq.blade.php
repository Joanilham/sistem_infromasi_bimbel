<!-- FAQ Tab -->
    <div x-show="tab === 'faq'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" x-cloak>
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm">
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-6">Tambahkan FAQ</h2>
            <form action="{{ route('admin.landing-page.faq.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Pertanyaan Umum</label>
                    <input type="text" name="pertanyaan" required class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Contoh: Apakah bisa bayar cicil?">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Jawaban Penjelasan</label>
                    <textarea name="jawaban" rows="3" required class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none" placeholder="Berikan jawaban yang jelas dan ringkas..."></textarea>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 dark:bg-slate-100 dark:hover:bg-white text-white dark:text-slate-900 font-medium text-sm px-6 py-2.5 rounded-lg shadow-sm transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambahkan Ke Daftar
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($faqs as $faq)
                <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-start gap-3 mb-2">
                            <span class="w-6 h-6 rounded bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-slate-300 flex items-center justify-center text-xs font-bold mt-0.5 shrink-0">Q</span>
                            <h4 class="font-semibold text-slate-900 dark:text-white">{{ $faq->pertanyaan }}</h4>
                        </div>
                        <p class="text-sm text-slate-600 dark:text-slate-400 ml-9">{{ $faq->jawaban }}</p>
                    </div>
                    <form action="{{ route('admin.landing-page.faq.destroy', $faq) }}" method="POST" class="ml-4 shrink-0">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="event.preventDefault(); confirmDelete('Hapus FAQ?', 'FAQ ini akan dihapus permanen!', this.closest('form'))" class="w-8 h-8 bg-rose-50 dark:bg-rose-900/20 text-rose-600 rounded-lg flex items-center justify-center hover:bg-rose-100 hover:text-rose-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm">
                    <div class="w-16 h-16 bg-slate-50 dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100 dark:border-zinc-700 text-3xl">❓</div>
                    <h3 class="font-semibold text-slate-900 dark:text-white text-lg">Belum Ada FAQ</h3>
                    <p class="text-slate-500 text-sm mt-1">Bantu calon pendaftar memahami layanan Anda lebih cepat.</p>
                </div>
            @endforelse
        </div>
    </div>
