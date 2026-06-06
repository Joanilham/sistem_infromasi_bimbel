@extends('layouts.admin')

@section('title', 'Manajemen Landing Page')

@section('content')
<div class="space-y-6" x-data="{ 
    tab: 'general', 
    overlayOpacity: {{ ($master->hero_overlay_opacity ?? 0.5) * 100 }} 
}">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Manajemen Landing Page</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm max-w-2xl">
                Kustomisasi tampilan depan portal bimbingan belajar. Kelola narasi, visual, testimoni, dan galeri untuk memikat calon peserta didik.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 ring-1 ring-indigo-100 dark:ring-indigo-900/30">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex flex-wrap gap-2 p-1 bg-slate-100 dark:bg-zinc-800/80 rounded-lg w-fit border border-slate-200 dark:border-zinc-700/50">
        <button @click="tab = 'general'" :class="tab === 'general' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white'" class="px-5 py-2 rounded-md text-sm font-medium transition-all">
            Umum
        </button>
        <button @click="tab = 'packages'" :class="tab === 'packages' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white'" class="px-5 py-2 rounded-md text-sm font-medium transition-all">
            Paket
        </button>
        <button @click="tab = 'testimonials'" :class="tab === 'testimonials' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white'" class="px-5 py-2 rounded-md text-sm font-medium transition-all">
            Testimoni
        </button>
        <button @click="tab = 'faq'" :class="tab === 'faq' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white'" class="px-5 py-2 rounded-md text-sm font-medium transition-all">
            FAQ
        </button>
        <button @click="tab = 'gallery'" :class="tab === 'gallery' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white'" class="px-5 py-2 rounded-md text-sm font-medium transition-all">
            Galeri
        </button>
    </div>

    <!-- General Settings Tab -->
    <div x-show="tab === 'general'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <form action="{{ route('admin.landing-page.update-general') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf
            <!-- Left Column: Socials & Contacts -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Identitas Instansi -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500 rounded-t-xl"></div>
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-6 mt-2">Identitas Instansi</h2>
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Instansi / Lembaga</label>
                            <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga', $master->nama_lembaga) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Genius Education">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Logo Instansi</label>
                            <div class="relative rounded-lg overflow-hidden aspect-video bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 flex items-center justify-center p-4">
                                @if($master->logo)
                                    <img id="logo-preview" src="{{ asset('storage/' . $master->logo) }}" class="max-h-20 w-auto object-contain">
                                    <div id="logo-placeholder" class="hidden flex-col items-center justify-center text-slate-400 gap-2">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-xs font-medium">Belum Ada Logo</span>
                                    </div>
                                @else
                                    <img id="logo-preview" class="max-h-20 w-auto object-contain hidden">
                                    <div id="logo-placeholder" class="flex flex-col items-center justify-center text-slate-400 gap-2">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-xs font-medium">Belum Ada Logo</span>
                                    </div>
                                @endif
                                <input type="file" name="logo" class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewImage(event, 'logo-preview', 'logo-placeholder')">
                            </div>
                            <p class="text-xs text-slate-500 mt-1">Klik pada area gambar untuk mengubah logo.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-emerald-500 rounded-t-xl"></div>
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-6 mt-2">Koneksi & Sosial</h2>
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">WhatsApp CS (Format: 62...)</label>
                            <input type="text" name="wa_number" value="{{ old('wa_number', $master->wa_number) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 transition-all" placeholder="628123456789">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Instagram Profile URL</label>
                            <input type="text" name="instagram_url" value="{{ old('instagram_url', $master->instagram_url) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-pink-500 focus:border-pink-500 transition-all" placeholder="https://instagram.com/...">
                        </div>

                        <!-- WA Widget Settings -->
                        <div class="mt-6 pt-6 border-t border-slate-200 dark:border-zinc-800">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-sm font-semibold text-emerald-600">WhatsApp Widget</h3>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="wa_widget_status" value="1" class="sr-only peer" {{ $master->wa_widget_status ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-emerald-300 dark:peer-focus:ring-emerald-800 dark:bg-zinc-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-zinc-600 peer-checked:bg-emerald-500"></div>
                                </label>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Pesan Sapaan (Greeting)</label>
                                <textarea name="wa_widget_message" rows="2" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 transition-all resize-none" placeholder="Halo Admin...">{{ old('wa_widget_message', $master->wa_widget_message) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-blue-50 dark:bg-blue-900/10 rounded-lg border border-blue-100 dark:border-blue-900/30">
                    <p class="text-sm text-blue-700 dark:text-blue-300 leading-relaxed font-medium">
                        <strong class="block mb-1">Info Sinkronisasi</strong>
                        Nama Lembaga dan Logo Utama yang diubah di sini akan otomatis sinkron dengan pengaturan master utama.
                    </p>
                </div>
            </div>

            <!-- Right Column: Hero Section -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-slate-800 dark:bg-slate-200 rounded-t-xl"></div>
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-6 mt-2">Visual & Narasi Hero</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Headline Utama</label>
                                <input type="text" name="hero_title" value="{{ old('hero_title', $master->hero_title) }}" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Bimbingan Belajar Genius">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Deskripsi Sub-headline</label>
                                <textarea name="hero_subtitle" rows="6" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none" placeholder="Deskripsikan visi utama lembaga Anda...">{{ old('hero_subtitle', $master->hero_subtitle) }}</textarea>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Gambar Latar Belakang</label>
                                <div class="relative rounded-lg overflow-hidden aspect-video bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 flex items-center justify-center p-2">
                                    @if($master->hero_image)
                                        <img id="hero-preview" src="{{ asset('storage/' . $master->hero_image) }}" class="w-full h-full object-cover rounded-md">
                                        <div id="hero-placeholder" class="hidden flex-col items-center justify-center h-full text-slate-400 gap-2">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-xs font-medium">Unggah Gambar Latar</span>
                                        </div>
                                    @else
                                        <img id="hero-preview" class="w-full h-full object-cover rounded-md hidden">
                                        <div id="hero-placeholder" class="flex flex-col items-center justify-center h-full text-slate-400 gap-2">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-xs font-medium">Unggah Gambar Latar</span>
                                        </div>
                                    @endif
                                    <!-- Live Preview Overlay -->
                                    <div class="absolute inset-2 bg-black rounded-md pointer-events-none transition-opacity" :style="{ opacity: overlayOpacity / 100 }"></div>
                                    <input type="file" name="hero_image" class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewImage(event, 'hero-preview', 'hero-placeholder')">
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Kecerahan Overlay</label>
                                    <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded" x-text="overlayOpacity + '%'"></span>
                                </div>
                                <input type="range" name="hero_overlay_opacity" min="0" max="90" step="5" x-model="overlayOpacity" class="w-full h-2 bg-slate-200 dark:bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                                <p class="text-xs text-slate-500 italic mt-1">*Meningkatkan overlay mempermudah pembacaan teks putih di atas gambar.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-slate-800 dark:bg-slate-200 rounded-t-xl"></div>
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 mt-2">Tentang Lembaga</h2>
                    <textarea name="tentang_kami" rows="6" class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-3 px-4 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none" placeholder="Tuliskan sejarah, visi, dan pencapaian lembaga...">{{ old('tentang_kami', $master->tentang_kami) }}</textarea>
                </div>

                <!-- Sticky Save Button -->
                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-6 rounded-lg shadow-sm transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Semua Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>

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
                                <div class="w-full h-full flex items-center justify-center text-lg text-slate-400">👤</div>
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
                    <div class="w-16 h-16 bg-slate-50 dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100 dark:border-zinc-700 text-3xl">💬</div>
                    <h3 class="font-semibold text-slate-900 dark:text-white text-lg">Belum Ada Testimoni</h3>
                    <p class="text-slate-500 text-sm mt-1">Testimoni akan membangun kepercayaan calon siswa Anda.</p>
                </div>
            @endforelse
        </div>
    </div>

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
</div>

@push('scripts')
<script>
function previewImage(event, previewId, placeholderId) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById(previewId);
        if(output) {
            output.src = reader.result;
            output.classList.remove('hidden');
        }
        const placeholder = document.getElementById(placeholderId);
        if(placeholder) {
            placeholder.classList.add('hidden');
            placeholder.classList.remove('flex');
        }
    };
    if(event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>
@endpush
@endsection
