@extends('layouts.admin')

@section('title', 'Manajemen Landing Page')

@section('content')
<div class="space-y-8" x-data="{ tab: 'general' }">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 shadow-sm border border-slate-100 dark:border-zinc-800 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-8 opacity-10">
            <svg class="w-32 h-32 text-indigo-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="relative z-10">
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Manajemen Landing Page</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 max-w-2xl">
                Kelola konten halaman depan website Anda tanpa perlu mengubah kode. Ubah teks, gambar, dan pengaturan lainnya dengan mudah.
            </p>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex gap-2 p-1.5 bg-slate-100 dark:bg-zinc-800 rounded-2xl w-fit">
        <button @click="tab = 'general'" :class="tab === 'general' ? 'bg-white dark:bg-zinc-700 shadow-sm text-indigo-600 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200">
            Konfigurasi Umum
        </button>
        <button @click="tab = 'packages'" :class="tab === 'packages' ? 'bg-white dark:bg-zinc-700 shadow-sm text-indigo-600 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200">
            Paket Bimbingan
        </button>
        <button @click="tab = 'testimonials'" :class="tab === 'testimonials' ? 'bg-white dark:bg-zinc-700 shadow-sm text-indigo-600 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200">
            Testimonial
        </button>
        <button @click="tab = 'faq'" :class="tab === 'faq' ? 'bg-white dark:bg-zinc-700 shadow-sm text-indigo-600 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200">
            FAQ
        </button>
        <button @click="tab = 'gallery'" :class="tab === 'gallery' ? 'bg-white dark:bg-zinc-700 shadow-sm text-indigo-600 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200">
            Gallery Fasilitas
        </button>
    </div>

    <!-- General Settings Tab -->
    <div x-show="tab === 'general'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <form action="{{ route('admin.landing-page.update-general') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            <!-- Left Column: Socials & Contacts -->
            <div class="lg:col-span-1 space-y-8">
                <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-6 border border-slate-100 dark:border-zinc-800 shadow-sm">
                    <h2 class="text-lg font-bold mb-6 flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 flex items-center justify-center mr-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </span>
                        Kontak & Sosmed
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">WhatsApp Number (Untuk Link Chat)</label>
                            <input type="text" name="wa_number" value="{{ old('wa_number', $master->wa_number) }}" class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 focus:ring-indigo-500 transition-all" placeholder="Contoh: 628123456789">
                            <p class="text-[10px] text-slate-400 mt-1 italic">*Gunakan format 62 (tanpa + atau 0).</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Instagram URL</label>
                            <input type="text" name="instagram_url" value="{{ old('instagram_url', $master->instagram_url) }}" class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 focus:ring-indigo-500 transition-all" placeholder="https://instagram.com/genius.edu">
                        </div>

                        <!-- WA Widget Settings -->
                        <div class="mt-8 pt-8 border-t border-slate-100 dark:border-zinc-800">
                            <h3 class="text-sm font-bold mb-4 flex items-center text-green-600">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.937 3.659 1.43 5.623 1.43h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                WA Floating Widget
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-zinc-950 rounded-xl border border-slate-100 dark:border-zinc-800">
                                    <span class="text-xs font-bold text-slate-600 dark:text-zinc-400">Aktifkan Tombol Melayang</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="wa_widget_status" value="1" class="sr-only peer" {{ $master->wa_widget_status ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-zinc-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600"></div>
                                    </label>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pesan Otomatis (Greeting)</label>
                                    <textarea name="wa_widget_message" rows="2" class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 focus:ring-indigo-500 transition-all text-xs" placeholder="Halo Admin, saya ingin bertanya tentang program bimbel...">{{ old('wa_widget_message', $master->wa_widget_message) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-blue-50 dark:bg-zinc-800/50 rounded-3xl border border-blue-100 dark:border-zinc-800">
                    <p class="text-[10px] text-blue-700 dark:text-blue-400 leading-relaxed font-medium text-center italic">
                        <span class="font-bold">Informasi:</span> Pengaturan <span class="underline">Nama & Logo Lembaga</span> dipusatkan di menu <a href="{{ route('master.index') }}" class="font-black text-indigo-900 dark:text-white underline">Data Master</a>. 
                        <br>Jangan merubah bagian ini tanpa koordinasi, hubungi TIM IT jika ingin merubah identitas utama.
                    </p>
                </div>
            </div>

            <!-- Right Column: Hero Section -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden">
                    <h2 class="text-xl font-bold mb-8 flex items-center">
                        <span class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 flex items-center justify-center mr-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </span>
                        Hero Section (Header Utama)
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Utama (Hero Title)</label>
                                <input type="text" name="hero_title" value="{{ old('hero_title', $master->hero_title) }}" class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 focus:ring-indigo-500 transition-all font-bold text-lg" placeholder="Contoh: Bimbingan Belajar Paling Genius">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Sub-judul (Hero Subtitle)</label>
                                <textarea name="hero_subtitle" rows="4" class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 focus:ring-indigo-500 transition-all" placeholder="Jelaskan secara singkat tentang lembaga Anda...">{{ old('hero_subtitle', $master->hero_subtitle) }}</textarea>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Background Hero Image</label>
                                <div class="relative group rounded-2xl overflow-hidden aspect-video bg-slate-100 dark:bg-zinc-800 border-2 border-dashed border-slate-200 dark:border-zinc-700">
                                    @if($master->hero_image)
                                        <img src="{{ asset('storage/' . $master->hero_image) }}" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">Ganti Gambar</span>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center h-full text-slate-400">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                    <input type="file" name="hero_image" class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>
                                <p class="text-[10px] text-slate-400 mt-2 italic">*Disarankan gambar landscape (16:9) resolusi tinggi. Maks 5MB.</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Overlay Darkness ({{ $master->hero_overlay_opacity }}%)</label>
                                <input type="range" name="hero_overlay_opacity" min="0" max="90" step="5" value="{{ $master->hero_overlay_opacity ?? 50 }}" class="w-full h-2 bg-slate-200 dark:bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                                <p class="text-[10px] text-slate-400 mt-2 italic">Semakin tinggi, gambar latar belakang semakin gelap agar teks lebih mudah dibaca.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
                    <h2 class="text-xl font-bold mb-8 flex items-center">
                        <span class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-zinc-800 text-slate-600 flex items-center justify-center mr-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </span>
                        Tentang Kami
                    </h2>
                    <textarea name="tentang_kami" rows="6" class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 focus:ring-indigo-500 transition-all" placeholder="Ceritakan sejarah atau visi misi bimbingan belajar Anda...">{{ old('tentang_kami', $master->tentang_kami) }}</textarea>
                </div>

                <!-- Sticky Save Button -->
                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-10 rounded-2xl shadow-lg shadow-indigo-500/30 transition-all transform hover:scale-105 active:scale-95 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Semua Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Packages Tab -->
    <div x-show="tab === 'packages'" x-transition class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm" x-cloak>
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-xl font-bold">Daftar Paket Bimbingan</h2>
            <a href="{{ route('paket-bimbingan.index') }}" class="text-indigo-600 font-bold hover:underline">Kelola di Manajemen Paket &rarr;</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($pakets ?? [] as $paket)
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700">
                    <div class="text-2xl mb-4">🎓</div>
                    <h3 class="font-bold dark:text-white">{{ $paket->nama_paket }}</h3>
                    <p class="text-xs text-slate-500 mt-2 line-clamp-2">{{ $paket->deskripsi ?? 'Belum ada deskripsi.' }}</p>
                    <div class="mt-4 pt-4 border-t border-slate-200 dark:border-zinc-700 flex justify-between items-center">
                        <span class="text-sm font-black text-indigo-600 dark:text-indigo-400">Rp {{ number_format($paket->nominal, 0, ',', '.') }}</span>
                        @if($paket->is_featured)
                            <span class="px-2 py-1 bg-indigo-100 text-indigo-600 text-[10px] font-bold rounded-lg uppercase">Featured</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Testimonials Tab -->
    <div x-show="tab === 'testimonials'" x-transition class="space-y-8" x-cloak>
        <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
            <h2 class="text-xl font-bold mb-8">Tambah Testimonial Baru</h2>
            <form action="{{ route('admin.landing-page.testimonial.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Pengulas</label>
                    <input type="text" name="nama" required class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 focus:ring-indigo-500 transition-all" placeholder="Contoh: Andi Wijaya">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jabatan / Posisi</label>
                    <input type="text" name="posisi" class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 focus:ring-indigo-500 transition-all" placeholder="Contoh: Alumni 2023 / Orang Tua Siswa">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Isi Ulasan</label>
                    <textarea name="ulasan" rows="3" required class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 focus:ring-indigo-500 transition-all" placeholder="Tuliskan pengalaman positif mereka..."></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Bintang (1-5)</label>
                    <select name="bintang" class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 focus:ring-indigo-500 transition-all">
                        <option value="5">⭐⭐⭐⭐⭐ (5 Bintang)</option>
                        <option value="4">⭐⭐⭐⭐ (4 Bintang)</option>
                        <option value="3">⭐⭐⭐ (3 Bintang)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Foto Pengulas</label>
                    <input type="file" name="foto" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100 transition-all cursor-pointer">
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:bg-indigo-700 transition-all">Tambah Testimonial</button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($testimonials as $testi)
                <div class="bg-white dark:bg-zinc-900 p-6 rounded-[2rem] border border-slate-100 dark:border-zinc-800 shadow-sm relative group">
                    <form action="{{ route('admin.landing-page.testimonial.destroy', $testi) }}" method="POST" class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus testimonial ini?')" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-zinc-800 overflow-hidden">
                            @if($testi->foto)
                                <img src="{{ asset('storage/' . $testi->foto) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">👤</div>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-bold dark:text-white">{{ $testi->nama }}</h4>
                            <span class="text-xs text-slate-500">{{ $testi->posisi }}</span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-400 italic">"{{ $testi->ulasan }}"</p>
                    <div class="mt-4 text-yellow-400 text-xs">
                        @for($i=0; $i<$testi->bintang; $i++) ⭐ @endfor
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- FAQ Tab -->
    <div x-show="tab === 'faq'" x-transition class="space-y-8" x-cloak>
        <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
            <h2 class="text-xl font-bold mb-8">Tambah Pertanyaan Baru (FAQ)</h2>
            <form action="{{ route('admin.landing-page.faq.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pertanyaan</label>
                    <input type="text" name="pertanyaan" required class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 focus:ring-indigo-500 transition-all" placeholder="Contoh: Bagaimana cara mendaftar?">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jawaban</label>
                    <textarea name="jawaban" rows="3" required class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 focus:ring-indigo-500 transition-all" placeholder="Tuliskan jawaban lengkapnya..."></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:bg-indigo-700 transition-all">Tambah FAQ</button>
                </div>
            </form>
        </div>

        <div class="space-y-4">
            @foreach($faqs as $faq)
                <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-slate-100 dark:border-zinc-800 shadow-sm flex justify-between items-start group">
                    <div class="flex-1">
                        <h4 class="font-bold dark:text-white mb-2">{{ $faq->pertanyaan }}</h4>
                        <p class="text-sm text-slate-600 dark:text-slate-400">{{ $faq->jawaban }}</p>
                    </div>
                    <form action="{{ route('admin.landing-page.faq.destroy', $faq) }}" method="POST" class="ml-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus FAQ ini?')" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Gallery Tab -->
    <div x-show="tab === 'gallery'" x-transition class="space-y-8" x-cloak>
        <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
            <h2 class="text-xl font-bold mb-8">Tambah Foto Gallery Baru</h2>
            <form action="{{ route('admin.landing-page.gallery.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Foto / Kegiatan</label>
                        <input type="text" name="judul" class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 focus:ring-indigo-500 transition-all" placeholder="Misal: Kelas Intensif UTBK">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori</label>
                        <select name="kategori" class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 focus:ring-indigo-500 transition-all">
                            <option value="Fasilitas">Fasilitas</option>
                            <option value="Kegiatan">Kegiatan</option>
                            <option value="Prestasi">Prestasi</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Foto</label>
                        <input type="file" name="foto" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all cursor-pointer">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:bg-indigo-700 transition-all">Unggah Ke Gallery</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($galleries as $gal)
                <div class="relative aspect-square rounded-2xl overflow-hidden group shadow-lg border border-slate-100 dark:border-zinc-800">
                    <img src="{{ asset('storage/' . $gal->foto) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-4 flex flex-col justify-end">
                        <p class="text-xs text-white font-black uppercase tracking-widest mb-1">{{ $gal->judul }}</p>
                        <span class="text-[10px] text-indigo-300 font-bold uppercase">{{ $gal->kategori }}</span>
                        
                        <form action="{{ route('admin.landing-page.gallery.destroy', $gal) }}" method="POST" class="absolute top-2 right-2">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-lg bg-red-600 text-white flex items-center justify-center hover:bg-red-700 shadow-lg" onclick="return confirm('Hapus foto ini?')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
