<!-- Testimonials Tab -->
    <div x-show="tab === 'testimonials'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" x-cloak>
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm">
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-6">Entri Testimoni Baru</h2>
            <form action="{{ route('admin.landing-page.testimonial.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @csrf
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Lengkap</label>
                    <input type="text" name="nama" required class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Misal: Andi Wijaya">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Keterangan / Jabatan</label>
                    <input type="text" name="posisi" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Misal: Alumni Lulus PTN">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Rating Penilaian</label>
                    <select name="bintang" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <option value="5">5 Bintang (Sempurna)</option>
                        <option value="4">4 Bintang (Sangat Baik)</option>
                        <option value="3">3 Bintang (Cukup)</option>
                    </select>
                </div>
                <div class="lg:col-span-2 space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Naskah Testimoni</label>
                    <textarea name="ulasan" rows="2" required class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none" placeholder="Tuliskan ulasan positif mereka..."></textarea>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Foto Profil (Opsional)</label>
                    <input type="file" name="foto" class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer mt-1 border border-slate-200 rounded-lg">
                </div>
                <div class="lg:col-span-3 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-6 py-2.5 rounded-lg shadow-sm transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambahkan Testimoni
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($testimonials as $testi)
                <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm relative group">
                    <form action="{{ route('admin.landing-page.testimonial.destroy', $testi) }}" method="POST" class="absolute top-4 right-4">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="event.preventDefault(); confirmDelete('Hapus Testimonial?', 'Testimonial ini akan dihapus permanen!', this.closest('form'))" class="w-8 h-8 bg-rose-50 dark:bg-rose-900/20 text-rose-600 rounded-lg flex items-center justify-center hover:bg-rose-100 hover:text-rose-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-zinc-800 overflow-hidden border border-slate-200 dark:border-zinc-700">
                            @if($testi->foto)
                                <img src="{{ asset('storage/' . $testi->foto) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-100 dark:bg-zinc-800">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-semibold text-slate-900 dark:text-white text-base">{{ $testi->nama }}</h4>
                            <span class="text-xs font-medium text-slate-500">{{ $testi->posisi }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex gap-1 text-amber-400 mb-2">
                            @for($i=0; $i<$testi->bintang; $i++) 
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="text-sm text-slate-600 dark:text-slate-400">"{{ $testi->ulasan }}"</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm">
                    <div class="w-16 h-16 bg-slate-50 dark:bg-zinc-800 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100 dark:border-zinc-700 text-slate-400 shadow-sm">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 dark:text-white text-lg">Belum Ada Testimoni</h3>
                    <p class="text-slate-500 text-sm mt-1">Testimoni akan membangun kepercayaan calon siswa Anda.</p>
                </div>
            @endforelse
        </div>
    </div>
