<!-- Gallery Tab -->
    <div x-show="tab === 'gallery'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" x-cloak>
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm">
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-6">Tambah Foto Galeri</h2>
            <form action="{{ route('admin.landing-page.gallery.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @csrf
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Keterangan / Judul</label>
                    <input type="text" name="judul" required class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Misal: Suasana Kelas">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Kategori</label>
                    <select name="kategori" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <option value="Fasilitas">Infrastruktur & Fasilitas</option>
                        <option value="Kegiatan">Aktivitas Belajar</option>
                        <option value="Prestasi">Siswa Berprestasi</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Pilih File Gambar</label>
                    <input type="file" name="foto" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer mt-1 border border-slate-200 rounded-lg">
                </div>
                <div class="lg:col-span-3 flex justify-end pt-2">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-6 py-2.5 rounded-lg shadow-sm transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Unggah Ke Galeri
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($galleries as $gal)
                <div class="relative aspect-square rounded-xl overflow-hidden group shadow-sm border border-slate-200 dark:border-zinc-800">
                    <img src="{{ asset('storage/' . $gal->foto) }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity p-4 flex flex-col justify-end">
                        <p class="text-sm text-white font-semibold mb-0.5 truncate">{{ $gal->judul }}</p>
                        <span class="text-xs text-indigo-300">{{ $gal->kategori }}</span>
                        
                        <form action="{{ route('admin.landing-page.gallery.destroy', $gal) }}" method="POST" class="absolute top-2 right-2">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-lg bg-rose-600 text-white flex items-center justify-center hover:bg-rose-700 shadow-sm transition-colors" onclick="event.preventDefault(); confirmDelete('Hapus Foto?', 'Foto galeri ini akan dihapus permanen!', this.closest('form'))">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm">
                    <div class="w-16 h-16 bg-slate-50 dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100 dark:border-zinc-700 text-3xl">📸</div>
                    <h3 class="font-semibold text-slate-900 dark:text-white text-lg">Galeri Masih Kosong</h3>
                    <p class="text-slate-500 text-sm mt-1">Unggah foto-foto kegiatan belajar mengajar untuk menarik minat.</p>
                </div>
            @endforelse
        </div>
    </div>
