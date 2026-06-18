<!-- Mitra Logo Tab -->
    <div x-show="tab === 'mitra'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" x-cloak>
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm">
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-6">Tambah Logo Mitra / Alumni</h2>
            <form action="{{ route('admin.landing-page.mitra.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Instansi / PTN</label>
                    <input type="text" name="name" required class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Misal: Universitas Indonesia">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Link Website (Opsional)</label>
                    <input type="text" name="link" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="https://...">
                </div>
                <div class="md:col-span-2 space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Pilih Logo</label>
                    <input type="file" name="logo" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer mt-1 border border-slate-200 rounded-lg">
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-6 py-2.5 rounded-lg shadow-sm transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Unggah Logo
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @forelse($mitras ?? [] as $mitra)
                <div class="relative aspect-video rounded-xl bg-white border border-slate-200 shadow-sm flex items-center justify-center p-4 group">
                    <img src="{{ asset('storage/' . $mitra->logo) }}" class="max-h-full max-w-full object-contain grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100 transition-all">
                    
                    <form action="{{ route('admin.landing-page.mitra.destroy', $mitra) }}" method="POST" class="absolute -top-2 -right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-6 h-6 rounded-full bg-rose-600 text-white flex items-center justify-center hover:bg-rose-700 shadow-sm" onclick="event.preventDefault(); confirmDelete('Hapus Logo?', 'Logo ini akan dihapus!', this.closest('form'))">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </form>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white dark:bg-zinc-900 rounded-xl border border-slate-200 shadow-sm">
                    <h3 class="font-semibold text-slate-900 dark:text-white text-lg">Belum Ada Logo Mitra</h3>
                </div>
            @endforelse
        </div>
