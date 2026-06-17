<!-- Packages Tab -->
    <div x-show="tab === 'packages'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" x-cloak>
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-1">Katalog Paket Bimbingan</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">Daftar ini ditampilkan secara otomatis di landing page.</p>
                </div>
                <a href="{{ route('paket-bimbingan.index') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2 rounded-lg shadow-sm transition-all shrink-0">
                    Kelola Harga & Deskripsi
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($pakets ?? [] as $paket)
                <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm relative">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl mb-4 border border-indigo-100 dark:border-indigo-800">
                        🎓
                    </div>
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-2">{{ $paket->nama_paket }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-3 mb-4">"{{ $paket->deskripsi ?? 'Deskripsi paket belum diatur.' }}"</p>
                    <div class="pt-4 border-t border-slate-100 dark:border-zinc-800 flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="text-xs text-slate-500 uppercase tracking-wide">Investasi</span>
                            <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($paket->nominal, 0, ',', '.') }}</span>
                        </div>
                        @if($paket->is_featured)
                            <span class="px-2 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-xs font-semibold rounded-md border border-emerald-200 dark:border-emerald-800">Populer</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm">
                    <div class="w-16 h-16 bg-slate-50 dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100 dark:border-zinc-700 text-3xl">🏷️</div>
                    <h3 class="font-semibold text-slate-900 dark:text-white text-lg">Paket Belum Tersedia</h3>
                    <p class="text-slate-500 text-sm mt-1">Silahkan buat paket bimbingan di menu pengaturan master.</p>
                </div>
            @endforelse
        </div>
    </div>
