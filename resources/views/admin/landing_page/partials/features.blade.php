<!-- Features Tab -->
    <div x-show="tab === 'features'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" x-cloak>
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm">
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-6">Tambah Keunggulan / Fitur Baru</h2>
            <form action="{{ route('admin.landing-page.feature.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Judul Keunggulan</label>
                    <input type="text" name="title" required class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Misal: Pengajar Profesional">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ikon (Upload Gambar)</label>
                    <input type="file" name="icon" class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer mt-1 border border-slate-200 rounded-lg">
                </div>
                <div class="md:col-span-2 space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none" placeholder="Jelaskan secara singkat..."></textarea>
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-6 py-2.5 rounded-lg shadow-sm transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambahkan Keunggulan
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($features ?? [] as $feature)
                <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm relative group">
                    <form action="{{ route('admin.landing-page.feature.destroy', $feature) }}" method="POST" class="absolute top-4 right-4">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="event.preventDefault(); confirmDelete('Hapus Keunggulan?', 'Item ini akan dihapus!', this.closest('form'))" class="w-8 h-8 bg-rose-50 text-rose-600 rounded-lg flex items-center justify-center hover:bg-rose-100 hover:text-rose-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                    <div class="w-12 h-12 mb-4">
                        @if($feature->icon)
                            <img src="{{ asset('storage/' . $feature->icon) }}" class="w-full h-full object-contain">
                        @else
                            <div class="w-full h-full rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-100 dark:border-indigo-800">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                        @endif
                    </div>
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-2">{{ $feature->title }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $feature->description }}</p>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm">
                    <h3 class="font-semibold text-slate-900 dark:text-white text-lg">Belum Ada Keunggulan</h3>
                    <p class="text-slate-500 text-sm mt-1">Tambahkan poin keunggulan lembaga Anda.</p>
                </div>
            @endforelse
        </div>
    </div>
