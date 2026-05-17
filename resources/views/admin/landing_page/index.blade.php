@extends('layouts.admin')

@section('title', 'Manajemen Landing Page')

@section('content')
<div class="space-y-8" x-data="{ 
    tab: 'general', 
    overlayOpacity: {{ $master->hero_overlay_opacity ?? 50 }} 
}">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl group-hover:bg-indigo-500/10 transition-colors duration-1000"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Manajemen Landing Page</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-2 max-w-2xl font-medium text-sm leading-relaxed">
                    Kustomisasi tampilan depan portal bimbingan belajar. Kelola narasi, visual, testimoni, dan galeri untuk memikat calon peserta didik.
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-inner">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex flex-wrap gap-2 p-2 bg-slate-100/80 dark:bg-zinc-800/80 backdrop-blur-xl rounded-[2rem] w-fit border border-slate-200/50 dark:border-zinc-700/50">
        <button @click="tab = 'general'" :class="tab === 'general' ? 'bg-white dark:bg-zinc-700 shadow-lg text-indigo-600 dark:text-white scale-105' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="px-8 py-3 rounded-[1.5rem] text-xs font-black uppercase tracking-widest transition-all duration-300 active:scale-95">
            Umum
        </button>
        <button @click="tab = 'packages'" :class="tab === 'packages' ? 'bg-white dark:bg-zinc-700 shadow-lg text-indigo-600 dark:text-white scale-105' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="px-8 py-3 rounded-[1.5rem] text-xs font-black uppercase tracking-widest transition-all duration-300 active:scale-95">
            Paket
        </button>
        <button @click="tab = 'testimonials'" :class="tab === 'testimonials' ? 'bg-white dark:bg-zinc-700 shadow-lg text-indigo-600 dark:text-white scale-105' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="px-8 py-3 rounded-[1.5rem] text-xs font-black uppercase tracking-widest transition-all duration-300 active:scale-95">
            Testimoni
        </button>
        <button @click="tab = 'faq'" :class="tab === 'faq' ? 'bg-white dark:bg-zinc-700 shadow-lg text-indigo-600 dark:text-white scale-105' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="px-8 py-3 rounded-[1.5rem] text-xs font-black uppercase tracking-widest transition-all duration-300 active:scale-95">
            FAQ
        </button>
        <button @click="tab = 'gallery'" :class="tab === 'gallery' ? 'bg-white dark:bg-zinc-700 shadow-lg text-indigo-600 dark:text-white scale-105' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="px-8 py-3 rounded-[1.5rem] text-xs font-black uppercase tracking-widest transition-all duration-300 active:scale-95">
            Galeri
        </button>
    </div>

    <!-- General Settings Tab -->
    <div x-show="tab === 'general'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
        <form action="{{ route('admin.landing-page.update-general') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            <!-- Left Column: Socials & Contacts -->
            <div class="lg:col-span-1 space-y-8">
                <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>
                    <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                        <span class="w-8 h-[2px] bg-emerald-500"></span>
                        Koneksi & Sosial
                    </h2>
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">WhatsApp CS (Format: 62...)</label>
                            <input type="text" name="wa_number" value="{{ old('wa_number', $master->wa_number) }}" class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all" placeholder="628123456789">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Instagram Profile URL</label>
                            <input type="text" name="instagram_url" value="{{ old('instagram_url', $master->instagram_url) }}" class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-all" placeholder="https://instagram.com/...">
                        </div>

                        <!-- WA Widget Settings -->
                        <div class="mt-12 pt-10 border-t border-slate-100 dark:border-zinc-800">
                            <div class="flex items-center justify-between mb-8">
                                <h3 class="text-xs font-black uppercase tracking-widest text-emerald-600">WhatsApp Widget</h3>
                                <label class="relative inline-flex items-center cursor-pointer group">
                                    <input type="checkbox" name="wa_widget_status" value="1" class="sr-only peer" {{ $master->wa_widget_status ? 'checked' : '' }}>
                                    <div class="w-12 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-zinc-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-500 shadow-inner transition-colors"></div>
                                </label>
                            </div>
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pesan Sapaan (Greeting)</label>
                                    <textarea name="wa_widget_message" rows="2" class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-xs font-bold py-4 px-5 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all resize-none" placeholder="Halo Admin...">{{ old('wa_widget_message', $master->wa_widget_message) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-indigo-50 dark:bg-zinc-800 rounded-[2.5rem] border border-indigo-100 dark:border-zinc-700/50 relative overflow-hidden group">
                    <div class="absolute -bottom-8 -right-8 w-32 h-32 bg-indigo-500/5 rounded-full blur-2xl group-hover:bg-indigo-500/10 transition-colors"></div>
                    <p class="text-[11px] text-indigo-700 dark:text-indigo-300 leading-relaxed font-bold italic relative">
                        <span class="text-indigo-900 dark:text-white uppercase tracking-widest block mb-2 not-italic underline">Pusat Data Master</span>
                        Informasi <span class="font-black">Nama Lembaga & Logo Utama</span> dikonfigurasi melalui modul <a href="{{ route('master.index') }}" class="text-indigo-600 dark:text-indigo-400 font-black hover:underline px-1">Master</a>. 
                    </p>
                </div>
            </div>

            <!-- Right Column: Hero Section -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>
                    <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                        <span class="w-8 h-[2px] bg-indigo-500"></span>
                        Visual & Narasi Hero
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-8">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Headline Utama</label>
                                <input type="text" name="hero_title" value="{{ old('hero_title', $master->hero_title) }}" class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-xl font-black py-5 px-6 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-900 dark:text-white" placeholder="Bimbingan Belajar Genius">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Deskripsi Sub-headline</label>
                                <textarea name="hero_subtitle" rows="5" class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-5 px-6 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all leading-relaxed resize-none" placeholder="Deskripsikan visi utama lembaga Anda...">{{ old('hero_subtitle', $master->hero_subtitle) }}</textarea>
                            </div>
                        </div>
                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Gambar Latar Belakang (High-Res)</label>
                                <div class="relative group rounded-[2rem] overflow-hidden aspect-video bg-slate-100 dark:bg-zinc-800 ring-4 ring-slate-50 dark:ring-zinc-900 transition-all shadow-inner">
                                    @if($master->hero_image)
                                        <img src="{{ asset('storage/' . $master->hero_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                        <div class="absolute inset-0 bg-indigo-900/40 opacity-0 group-hover:opacity-100 transition-opacity backdrop-blur-[2px] flex items-center justify-center">
                                            <span class="bg-white text-slate-900 px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-xl">Ganti Visual</span>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center justify-center h-full text-slate-300 gap-3">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-[10px] font-black uppercase tracking-widest">Unggah Gambar</span>
                                        </div>
                                    @endif
                                    <input type="file" name="hero_image" class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Kecerahan Overlay</label>
                                    <span class="text-[10px] font-black text-indigo-600 bg-indigo-50 px-3 py-1 rounded-lg shadow-sm" x-text="overlayOpacity + '%'"></span>
                                </div>
                                <input type="range" name="hero_overlay_opacity" min="0" max="90" step="5" x-model="overlayOpacity" class="w-full h-1.5 bg-slate-200 dark:bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                                <p class="text-[10px] text-slate-400 italic font-medium leading-relaxed">*Meningkatkan overlay mempermudah pembacaan teks putih di atas gambar.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-slate-500/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>
                    <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                        <span class="w-8 h-[2px] bg-slate-800 dark:bg-white"></span>
                        Tentang Lembaga
                    </h2>
                    <textarea name="tentang_kami" rows="6" class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-[2rem] text-sm font-bold py-6 px-8 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all leading-relaxed resize-none" placeholder="Tuliskan sejarah, visi, dan pencapaian lembaga...">{{ old('tentang_kami', $master->tentang_kami) }}</textarea>
                </div>

                <!-- Sticky Save Button -->
                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black py-5 px-12 rounded-[2rem] shadow-2xl shadow-indigo-600/20 transition-all active:scale-95 flex items-center gap-4 group">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center group-hover:rotate-12 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="uppercase tracking-widest text-xs">Simpan Semua Perubahan</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Packages Tab -->
    <div x-show="tab === 'packages'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-10" x-cloak>
        <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-10 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                <div>
                    <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-2 flex items-center gap-3">
                        <span class="w-8 h-[2px] bg-indigo-500"></span>
                        Katalog Paket Bimbingan
                    </h2>
                    <p class="text-slate-500 dark:text-slate-400 text-[10px] font-bold italic ml-11">*Daftar ini ditampilkan secara otomatis di landing page.</p>
                </div>
                <a href="{{ route('paket-bimbingan.index') }}" class="inline-flex items-center gap-3 bg-indigo-600 text-white font-black text-[10px] uppercase tracking-widest px-8 py-4 rounded-2xl shadow-xl shadow-indigo-600/20 hover:bg-indigo-700 transition-all active:scale-95 shrink-0">
                    Kelola Harga & Deskripsi
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($pakets ?? [] as $paket)
                <div class="bg-white dark:bg-zinc-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm relative group hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-500/5 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-150 duration-700"></div>
                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-2xl mb-6 shadow-inner ring-1 ring-indigo-100 dark:ring-indigo-800">
                        🎓
                    </div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white mb-3 tracking-tight">{{ $paket->nama_paket }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-3 italic mb-6 font-medium">"{{ $paket->deskripsi ?? 'Deskripsi paket belum diatur.' }}"</p>
                    <div class="pt-6 border-t border-slate-50 dark:border-zinc-800 flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Investasi</span>
                            <span class="text-base font-black text-indigo-600 dark:text-indigo-400">Rp {{ number_format($paket->nominal, 0, ',', '.') }}</span>
                        </div>
                        @if($paket->is_featured)
                            <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[9px] font-black rounded-lg uppercase tracking-widest ring-1 ring-emerald-100 dark:ring-emerald-800">Populer</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="lg:col-span-3 py-20 text-center">
                    <div class="w-24 h-24 bg-slate-50 dark:bg-zinc-800 rounded-[3rem] flex items-center justify-center mx-auto mb-6 shadow-inner text-5xl">🏷️</div>
                    <h3 class="font-black text-slate-900 dark:text-white text-xl">Paket Belum Tersedia</h3>
                    <p class="text-slate-400 text-sm mt-3 font-medium">Silahkan buat paket bimbingan di menu pengaturan master.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Testimonials Tab -->
    <div x-show="tab === 'testimonials'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-10" x-cloak>
        <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-10 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-pink-500/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>
            <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-10 flex items-center gap-3">
                <span class="w-8 h-[2px] bg-pink-500"></span>
                Entri Testimoni Baru
            </h2>
            <form action="{{ route('admin.landing-page.testimonial.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @csrf
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                    <input type="text" name="nama" required class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-all" placeholder="Misal: Andi Wijaya">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Keterangan / Jabatan</label>
                    <input type="text" name="posisi" class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-all" placeholder="Misal: Alumni Lulus PTN">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Rating Penilaian</label>
                    <select name="bintang" class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-all cursor-pointer">
                        <option value="5">⭐⭐⭐⭐⭐ (Sempurna)</option>
                        <option value="4">⭐⭐⭐⭐ (Sangat Baik)</option>
                        <option value="3">⭐⭐⭐ (Cukup)</option>
                    </select>
                </div>
                <div class="lg:col-span-2 space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Naskah Testimoni</label>
                    <textarea name="ulasan" rows="2" required class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-all resize-none" placeholder="Tuliskan ulasan positif mereka..."></textarea>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Foto Profil (Opsional)</label>
                    <input type="file" name="foto" class="block w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-6 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-pink-50 file:text-pink-600 hover:file:bg-pink-100 transition-all cursor-pointer mt-2">
                </div>
                <div class="lg:col-span-3 flex justify-end">
                    <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-black text-xs px-10 py-4 rounded-2xl shadow-xl shadow-pink-500/20 transition-all active:scale-95 flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Tambahkan Testimoni
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($testimonials as $testi)
                <div class="bg-white dark:bg-zinc-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm relative group hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <form action="{{ route('admin.landing-page.testimonial.destroy', $testi) }}" method="POST" class="absolute top-6 right-6 opacity-0 group-hover:opacity-100 transition-opacity">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus testimonial ini?')" class="w-10 h-10 bg-rose-50 dark:bg-rose-900/20 text-rose-600 rounded-xl flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                    <div class="flex items-center gap-5 mb-8">
                        <div class="w-16 h-16 rounded-[1.25rem] bg-slate-50 dark:bg-zinc-800 overflow-hidden border-2 border-white dark:border-zinc-700 shadow-md">
                            @if($testi->foto)
                                <img src="{{ asset('storage/' . $testi->foto) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-2xl">👤</div>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-black text-slate-900 dark:text-white text-lg tracking-tight">{{ $testi->nama }}</h4>
                            <span class="text-[10px] font-black uppercase tracking-widest text-indigo-500">{{ $testi->posisi }}</span>
                        </div>
                    </div>
                    <div class="mb-6">
                        <div class="flex gap-1 text-amber-400 mb-4">
                            @for($i=0; $i<$testi->bintang; $i++) 
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-medium italic">"{{ $testi->ulasan }}"</p>
                    </div>
                </div>
            @empty
                <div class="lg:col-span-3 py-20 text-center">
                    <div class="w-24 h-24 bg-slate-50 dark:bg-zinc-800 rounded-[3rem] flex items-center justify-center mx-auto mb-6 shadow-inner text-5xl">💬</div>
                    <h3 class="font-black text-slate-900 dark:text-white text-xl">Belum Ada Testimoni</h3>
                    <p class="text-slate-400 text-sm mt-3 font-medium">Testimoni akan membangun kepercayaan calon siswa Anda.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- FAQ Tab -->
    <div x-show="tab === 'faq'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-10" x-cloak>
        <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-10 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
             <div class="absolute top-0 right-0 w-32 h-32 bg-slate-500/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>
            <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-10 flex items-center gap-3">
                <span class="w-8 h-[2px] bg-slate-800 dark:bg-white"></span>
                Katalog Pertanyaan (FAQ)
            </h2>
            <form action="{{ route('admin.landing-page.faq.store') }}" method="POST" class="space-y-8">
                @csrf
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pertanyaan Umum</label>
                    <input type="text" name="pertanyaan" required class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-slate-500/20 focus:border-slate-500 transition-all" placeholder="Contoh: Apakah bisa bayar cicil?">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jawaban Penjelasan</label>
                    <textarea name="jawaban" rows="3" required class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-slate-500/20 focus:border-slate-500 transition-all resize-none" placeholder="Berikan jawaban yang jelas dan ringkas..."></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-slate-900 dark:bg-white dark:text-slate-900 hover:bg-black dark:hover:bg-slate-100 text-white font-black text-xs px-10 py-4 rounded-2xl shadow-xl transition-all active:scale-95 flex items-center gap-3 group">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Tambahkan Ke Daftar
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($faqs as $faq)
                <div class="bg-white dark:bg-zinc-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm flex justify-between items-start group hover:shadow-md transition-all">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-zinc-800 text-slate-900 dark:text-white flex items-center justify-center text-[10px] font-black">Q</span>
                            <h4 class="font-black text-slate-900 dark:text-white tracking-tight">{{ $faq->pertanyaan }}</h4>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium ml-9">{{ $faq->jawaban }}</p>
                    </div>
                    <form action="{{ route('admin.landing-page.faq.destroy', $faq) }}" method="POST" class="ml-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus FAQ ini?')" class="w-9 h-9 bg-rose-50 dark:bg-rose-900/20 text-rose-600 rounded-xl flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            @empty
                <div class="md:col-span-2 py-20 text-center">
                    <div class="w-24 h-24 bg-slate-50 dark:bg-zinc-800 rounded-[3rem] flex items-center justify-center mx-auto mb-6 shadow-inner text-5xl">❓</div>
                    <h3 class="font-black text-slate-900 dark:text-white text-xl">Belum Ada FAQ</h3>
                    <p class="text-slate-400 text-sm mt-3 font-medium">Bantu calon pendaftar memahami layanan Anda lebih cepat.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Gallery Tab -->
    <div x-show="tab === 'gallery'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-10" x-cloak>
        <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-10 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>
            <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-10 flex items-center gap-3">
                <span class="w-8 h-[2px] bg-indigo-500"></span>
                Koleksi Visual (Galeri)
            </h2>
            <form action="{{ route('admin.landing-page.gallery.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @csrf
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Keterangan Singkat / Judul</label>
                    <input type="text" name="judul" required class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="Misal: Suasana Kelas">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Label Kategori</label>
                    <select name="kategori" class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all cursor-pointer">
                        <option value="Fasilitas">Infrastruktur & Fasilitas</option>
                        <option value="Kegiatan">Aktivitas Belajar</option>
                        <option value="Prestasi">Siswa Berprestasi</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih File Gambar</label>
                    <input type="file" name="foto" required class="block w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-6 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all cursor-pointer mt-2">
                </div>
                <div class="lg:col-span-3 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs px-10 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95 flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Unggah Ke Galeri
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @forelse($galleries as $gal)
                <div class="relative aspect-square rounded-[2.5rem] overflow-hidden group shadow-lg border-4 border-white dark:border-zinc-900 hover:shadow-2xl transition-all duration-500">
                    <img src="{{ asset('storage/' . $gal->foto) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-6 flex flex-col justify-end backdrop-blur-[1px]">
                        <p class="text-xs text-white font-black uppercase tracking-[0.2em] mb-1 translate-y-4 group-hover:translate-y-0 transition-transform duration-500">{{ $gal->judul }}</p>
                        <span class="text-[10px] text-indigo-300 font-black uppercase tracking-widest translate-y-4 group-hover:translate-y-0 transition-transform duration-500 delay-75">{{ $gal->kategori }}</span>
                        
                        <form action="{{ route('admin.landing-page.gallery.destroy', $gal) }}" method="POST" class="absolute top-4 right-4">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-9 h-9 rounded-xl bg-rose-600/90 text-white flex items-center justify-center hover:bg-rose-600 shadow-xl transition-all active:scale-90" onclick="return confirm('Hapus foto ini?')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="w-24 h-24 bg-slate-50 dark:bg-zinc-800 rounded-[3rem] flex items-center justify-center mx-auto mb-6 shadow-inner text-5xl">📸</div>
                    <h3 class="font-black text-slate-900 dark:text-white text-xl">Galeri Masih Kosong</h3>
                    <p class="text-slate-400 text-sm mt-3 font-medium">Unggah foto-foto kegiatan belajar mengajar untuk menarik minat.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
