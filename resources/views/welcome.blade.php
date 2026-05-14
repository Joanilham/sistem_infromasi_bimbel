<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $masterData->nama_lembaga ?? 'Genius Education' }} - Platform Bimbel Terbaik</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass { @apply bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl border-b border-slate-200/50 dark:border-zinc-800/50; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-zinc-950 text-slate-900 dark:text-slate-100 transition-colors duration-300 antialiased selection:bg-indigo-500 selection:text-white" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))">

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 glass h-20 flex items-center justify-between px-6 lg:px-12">
        <a href="/" class="flex items-center gap-3 group">
            @if($masterData && $masterData->logo)
                <img src="{{ asset('storage/' . $masterData->logo) }}" class="w-10 h-10 object-contain group-hover:scale-110 transition-transform">
            @else
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            @endif
            <span class="text-xl font-black tracking-tight dark:text-white">
                {{ $masterData->nama_lembaga ?? 'Genius Education' }}
            </span>
        </a>

        <div class="hidden md:flex items-center gap-8">
            <a href="#about" class="text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-white transition-colors">Tentang Kami</a>
            <a href="#packages" class="text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-white transition-colors">Program</a>
            <a href="#faq" class="text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-white transition-colors">FAQ</a>
        </div>

        <div class="flex items-center gap-4">
            <!-- Dark Mode Toggle -->
            <button @click="darkMode = !darkMode" class="p-2.5 rounded-xl bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 hover:scale-110 transition-all">
                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                <svg x-show="darkMode" class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </button>
            <a href="{{ route('login') }}" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/25 transition-all transform hover:-translate-y-0.5">Masuk</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center pt-20 overflow-hidden">
        <!-- Background with Overlay -->
        <div class="absolute inset-0 z-0">
            @if($masterData && $masterData->hero_image)
                <img src="{{ asset('storage/' . $masterData->hero_image) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-zinc-900 dark:to-zinc-950"></div>
            @endif
            <div class="absolute inset-0 bg-black" style="opacity: {{ ($masterData->hero_overlay_opacity ?? 0) / 100 }}"></div>
            <!-- Decorative Gradients -->
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-500/20 blur-[120px] rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-purple-500/20 blur-[100px] rounded-full translate-y-1/2 -translate-x-1/2"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-100/10 backdrop-blur-md border border-indigo-500/30 text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase tracking-widest mb-8">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    Platform Pilihan Bimbingan Belajar
                </div>
                <h1 class="text-5xl lg:text-7xl font-black text-slate-900 dark:text-white leading-[1.1] mb-8">
                    {{ $masterData->hero_title ?? 'Membangun Masa Depan Lebih Cemerlang' }}
                </h1>
                <p class="text-xl text-slate-600 dark:text-slate-300 leading-relaxed mb-12 max-w-2xl mx-auto">
                    {{ $masterData->hero_subtitle ?? 'Platform manajemen bimbingan belajar terpadu untuk pengelolaan peserta didik, guru, dan jadwal yang lebih efisien.' }}
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('daftar.step1') }}" class="w-full sm:w-auto px-10 py-5 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-2xl shadow-indigo-500/40 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-3">
                        Daftar Sekarang
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </a>
                    <a href="#about" class="w-full sm:w-auto px-10 py-5 bg-white dark:bg-zinc-900 text-slate-900 dark:text-white font-black rounded-2xl border border-slate-200 dark:border-zinc-800 transition-all hover:bg-slate-50 dark:hover:bg-zinc-800 flex items-center justify-center gap-3">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <div class="py-12 bg-white dark:bg-zinc-900 border-y border-slate-100 dark:border-zinc-800">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center group">
                    <div class="text-3xl font-black text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">500+</div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-2">Siswa Terdaftar</div>
                </div>
                <div class="text-center group">
                    <div class="text-3xl font-black text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">50+</div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-2">Guru Profesional</div>
                </div>
                <div class="text-center group">
                    <div class="text-3xl font-black text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">99%</div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-2">Tingkat Kelulusan</div>
                </div>
                <div class="text-center group">
                    <div class="text-3xl font-black text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">24/7</div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-2">Dukungan Belajar</div>
                </div>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <section id="about" class="py-24 bg-slate-50 dark:bg-zinc-950">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="relative">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-indigo-500/10 blur-3xl rounded-full"></div>
                    <div class="bg-white dark:bg-zinc-900 p-8 rounded-[3rem] shadow-2xl border border-slate-100 dark:border-zinc-800 relative z-10 overflow-hidden">
                         <div class="aspect-video bg-indigo-50 dark:bg-zinc-800 rounded-2xl flex items-center justify-center mb-8">
                            <svg class="w-20 h-20 text-indigo-200 dark:text-zinc-700" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 16h2v2h-2zm0-6h2v4h-2z"/></svg>
                         </div>
                         <h3 class="text-2xl font-black mb-4 dark:text-white">Visi & Misi Kami</h3>
                         <p class="text-slate-600 dark:text-slate-400 leading-relaxed italic">
                            "Menjadi bimbingan belajar pilihan yang mengutamakan kualitas, inovasi, dan integritas dalam mendidik generasi bangsa."
                         </p>
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-purple-500/10 blur-3xl rounded-full"></div>
                </div>
                <div class="space-y-8">
                    <div class="inline-block px-4 py-1.5 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase tracking-widest">Tentang Kami</div>
                    <h2 class="text-4xl lg:text-5xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">
                        Pendidikan Berkualitas, Masa Depan Cemerlang.
                    </h2>
                    <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed">
                        {{ $masterData->tentang_kami ?? 'Kami berkomitmen memberikan pendampingan belajar terbaik dengan metode yang adaptif dan guru-guru yang berpengalaman di bidangnya.' }}
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold dark:text-white">Metode Adaptif</h4>
                                <p class="text-xs text-slate-500 mt-1">Pembelajaran disesuaikan kemampuan siswa.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-900/30 text-orange-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold dark:text-white">Efisiensi Waktu</h4>
                                <p class="text-xs text-slate-500 mt-1">Jadwal fleksibel dan terstruktur.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section id="packages" class="py-24 bg-white dark:bg-zinc-900">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="inline-block px-4 py-1.5 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 text-xs font-black uppercase tracking-widest mb-4">Pilihan Paket</div>
                <h2 class="text-4xl lg:text-5xl font-black text-slate-900 dark:text-white tracking-tight mb-6">Investasi Terbaik Untuk Prestasi</h2>
                <p class="text-lg text-slate-600 dark:text-slate-400">Pilih program yang paling sesuai dengan kebutuhan belajar putra-putri Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($pakets as $paket)
                    <div class="group relative bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 hover:border-indigo-500 dark:hover:border-indigo-500 transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-500/10 hover:-translate-y-2 flex flex-col h-full">
                        
                        <!-- Popular Label Badge -->
                        @if($paket->label_populer)
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1.5 bg-orange-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg shadow-orange-500/30 z-20">
                                {{ $paket->label_populer }}
                            </div>
                        @endif

                        <div class="flex items-start justify-between mb-8">
                            <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 flex items-center justify-center text-3xl shadow-inner border border-indigo-100 dark:border-indigo-900/30">
                                @if($paket->gambar_paket)
                                    <img src="{{ asset('storage/' . $paket->gambar_paket) }}" class="w-10 h-10 object-contain p-1">
                                @else
                                    🎓
                                @endif
                            </div>
                            @if($paket->durasi_jumlah)
                                <div class="px-3 py-1 bg-slate-100 dark:bg-zinc-800 rounded-lg text-[10px] font-black text-slate-500 dark:text-zinc-400 uppercase tracking-tighter">
                                    {{ $paket->durasi_jumlah }} {{ $paket->durasi_satuan }}
                                </div>
                            @endif
                        </div>

                        <h3 class="text-2xl font-black mb-4 dark:text-white leading-tight">{{ $paket->nama_paket }}</h3>
                        
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 line-clamp-2">
                            {{ $paket->deskripsi ?? 'Dapatkan bimbingan intensif untuk menguasai berbagai mata pelajaran dengan mudah.' }}
                        </p>

                        <!-- Benefits List -->
                        @if($paket->benefits)
                            <div class="space-y-3 mb-10 flex-grow">
                                @php $benefitList = explode("\n", str_replace("\r", "", $paket->benefits)); @endphp
                                @foreach($benefitList as $benefit)
                                    @if(trim($benefit))
                                        <div class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-300">
                                            <div class="w-5 h-5 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 flex items-center justify-center shrink-0">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                            <span class="font-medium tracking-tight">{{ trim($benefit) }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <div class="pt-8 border-t border-slate-100 dark:border-zinc-800 mt-auto flex items-center justify-between">
                            <div>
                                @if($paket->harga_coret)
                                    <div class="text-xs text-slate-400 line-through mb-1">Rp {{ number_format($paket->harga_coret, 0, ',', '.') }}</div>
                                @endif
                                <div class="text-3xl font-black text-indigo-600 dark:text-indigo-400 tracking-tighter">
                                    <span class="text-sm font-bold align-top mt-1 inline-block">Rp</span>
                                    {{ number_format($paket->nominal, 0, ',', '.') }}
                                </div>
                            </div>
                            <a href="{{ route('daftar.step1') }}" class="w-14 h-14 rounded-2xl bg-indigo-600 text-white flex items-center justify-center hover:bg-indigo-700 hover:scale-110 shadow-lg shadow-indigo-500/30 transition-all">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20 bg-slate-50 dark:bg-zinc-800 rounded-[3rem]">
                        <p class="text-slate-500">Belum ada paket bimbingan tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    @if(isset($galleries) && $galleries->count() > 0)
    <section id="gallery" class="py-24 bg-white dark:bg-zinc-900 overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                <div class="max-w-2xl">
                    <div class="inline-block px-4 py-1.5 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase tracking-widest mb-4">Gallery Kami</div>
                    <h2 class="text-4xl lg:text-5xl font-black text-slate-900 dark:text-white tracking-tight mb-4">Lihat Fasilitas & Kegiatan Kami</h2>
                    <p class="text-lg text-slate-600 dark:text-slate-400">Suasana belajar yang nyaman dan fasilitas lengkap untuk mendukung prestasi siswa.</p>
                </div>
                <div class="flex gap-2 overflow-x-auto pb-2 md:pb-0">
                    <button class="px-6 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold shadow-lg shadow-indigo-500/25 whitespace-nowrap">Semua Foto</button>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($galleries as $gal)
                    <div class="group relative aspect-square rounded-[2rem] overflow-hidden border border-slate-100 dark:border-zinc-800 shadow-sm hover:shadow-2xl transition-all duration-500">
                        <img src="{{ asset('storage/' . $gal->foto) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 p-6 flex flex-col justify-end">
                            <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">{{ $gal->kategori }}</span>
                            <h4 class="text-sm font-bold text-white leading-tight">{{ $gal->judul }}</h4>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Testimonials -->
    @if(isset($testimonials) && $testimonials->count() > 0)
    <section class="py-24 bg-slate-50 dark:bg-zinc-950 overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <div class="inline-block px-4 py-1.5 rounded-full bg-pink-100 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400 text-xs font-black uppercase tracking-widest mb-4">Apa Kata Mereka</div>
                <h2 class="text-4xl lg:text-5xl font-black text-slate-900 dark:text-white">Ulasan Siswa & Orang Tua</h2>
            </div>
            <div class="flex gap-8 overflow-x-auto pb-12 custom-scrollbar snap-x">
                @foreach($testimonials as $testi)
                    <div class="snap-center shrink-0 w-[320px] md:w-[400px] bg-white dark:bg-zinc-900 p-8 rounded-[2rem] shadow-xl border border-slate-100 dark:border-zinc-800">
                        <div class="flex items-center gap-1 text-yellow-400 mb-6">
                            @for($i=0; $i<$testi->bintang; $i++)
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            @endfor
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 italic mb-8 leading-relaxed">"{{ $testi->ulasan }}"</p>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-zinc-800 flex items-center justify-center overflow-hidden">
                                @if($testi->foto)
                                    <img src="{{ asset('storage/' . $testi->foto) }}" class="w-full h-full object-cover">
                                @else
                                    👤
                                @endif
                            </div>
                            <div>
                                <h4 class="font-black text-sm dark:text-white">{{ $testi->nama }}</h4>
                                <span class="text-xs text-slate-500">{{ $testi->posisi }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- FAQ -->
    @if(isset($faqs) && $faqs->count() > 0)
    <section id="faq" class="py-24 bg-white dark:bg-zinc-900">
        <div class="container mx-auto px-6 max-w-4xl">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-slate-900 dark:text-white mb-4">Pertanyaan Umum</h2>
                <p class="text-slate-500">Mungkin Anda memiliki pertanyaan yang sama dengan yang lain.</p>
            </div>
            <div class="space-y-4">
                @foreach($faqs as $faq)
                    <div x-data="{ open: false }" class="bg-slate-50 dark:bg-zinc-800/50 rounded-2xl overflow-hidden transition-all duration-200" :class="open ? 'ring-2 ring-indigo-500 shadow-xl' : ''">
                        <button @click="open = !open" class="w-full px-8 py-6 flex items-center justify-between text-left group">
                            <span class="font-black text-slate-800 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $faq->pertanyaan }}</span>
                            <svg class="w-5 h-5 text-slate-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse x-cloak class="px-8 pb-6 text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ $faq->jawaban }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Final -->
    <section class="py-24">
        <div class="container mx-auto px-6">
            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-[3rem] p-12 lg:p-20 text-center relative overflow-hidden shadow-2xl shadow-indigo-500/40">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="absolute -top-20 -right-20 w-80 h-80 bg-white/10 blur-[100px] rounded-full"></div>
                <div class="relative z-10 max-w-2xl mx-auto">
                    <h2 class="text-4xl lg:text-6xl font-black text-white mb-8">Daftar Sekarang & Raih Prestasimu!</h2>
                    <p class="text-white/80 text-xl mb-12">Jangan tunda kesempatan emas untuk mendapatkan pendampingan belajar terbaik.</p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ route('daftar.step1') }}" class="w-full sm:w-auto px-10 py-5 bg-white text-indigo-600 font-black rounded-2xl shadow-xl transition-all transform hover:-translate-y-1 hover:bg-slate-50">Mulai Belajar Sekarang</a>
                        @if($masterData && $masterData->wa_number)
                            <a href="https://wa.me/{{ $masterData->wa_number }}" class="w-full sm:w-auto px-10 py-5 bg-green-500 text-white font-black rounded-2xl shadow-xl transition-all transform hover:-translate-y-1 hover:bg-green-600 flex items-center justify-center gap-2">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.937 3.659 1.43 5.623 1.43h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                Konsultasi via WA
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white dark:bg-zinc-950 pt-20 pb-10 border-t border-slate-100 dark:border-zinc-800">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-16">
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        @if($masterData && $masterData->logo)
                            <img src="{{ asset('storage/' . $masterData->logo) }}" class="w-10 h-10 object-contain">
                        @endif
                        <span class="text-xl font-black dark:text-white">{{ $masterData->nama_lembaga ?? 'Genius Education' }}</span>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400">Pusat bimbingan belajar terbaik dengan fasilitas modern dan pengajar profesional.</p>
                </div>
                <div>
                    <h4 class="font-black mb-6 dark:text-white">Alamat</h4>
                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ $masterData->alamat_lembaga ?? 'Jl. Contoh Alamat No. 123, Kota ABC' }}
                    </p>
                </div>
                <div>
                    <h4 class="font-black mb-6 dark:text-white">Ikuti Kami</h4>
                    <div class="flex gap-4">
                        @if($masterData && $masterData->instagram_url)
                            <a href="{{ $masterData->instagram_url }}" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-indigo-600 hover:text-white transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.334 3.608 1.31.976.975 1.247 2.242 1.31 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.334 2.633-1.31 3.608-.975.976-2.242 1.247-3.608 1.31-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.334-3.608-1.31-.976-.975-1.247-2.242-1.31-3.608-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.062-1.366.334-2.633 1.31-3.608.975-.976 2.242-1.247 3.608-1.31 1.266-.058 1.646-.07 4.85-.07zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948s.014 3.667.072 4.947c.2 4.337 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072s3.667-.014 4.947-.072c4.337-.2 6.78-2.618 6.98-6.98.058-1.281.072-1.689.072-4.947s-.014-3.667-.072-4.947c-.2-4.358-2.618-6.78-6.98-6.98-1.281-.059-1.689-.073-4.948-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="pt-8 border-t border-slate-100 dark:border-zinc-800 text-center text-sm text-slate-500">
                &copy; {{ date('Y') }} {{ $masterData->nama_lembaga ?? 'Genius Education' }}. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Floating WA Widget -->
    @if($masterData && $masterData->wa_widget_status && $masterData->wa_number)
        <div class="fixed bottom-8 right-8 z-[100]" x-data="{ showGreeting: false }" x-init="setTimeout(() => showGreeting = true, 3000)">
            <!-- Greeting Bubble -->
            <div x-show="showGreeting" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                 class="absolute bottom-20 right-0 w-64 bg-white dark:bg-zinc-900 p-4 rounded-2xl shadow-2xl border border-slate-100 dark:border-zinc-800 mb-2">
                <button @click="showGreeting = false" class="absolute top-2 right-2 text-slate-400 hover:text-slate-600 dark:hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white text-xs">Admin</div>
                    <div class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Online Now</div>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                    {{ $masterData->wa_widget_message ?? 'Halo! Ada yang bisa kami bantu seputar pendaftaran bimbingan belajar?' }}
                </p>
                <div class="absolute bottom-[-8px] right-6 w-4 h-4 bg-white dark:bg-zinc-900 border-r border-b border-slate-100 dark:border-zinc-800 rotate-45"></div>
            </div>

            <!-- Main Button -->
            <a href="https://wa.me/{{ $masterData->wa_number }}?text={{ urlencode($masterData->wa_widget_message ?? 'Halo Admin, saya ingin bertanya tentang program bimbel...') }}" 
               target="_blank"
               class="w-16 h-16 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-2xl shadow-green-500/40 flex items-center justify-center transition-all hover:scale-110 active:scale-95 group relative">
                <span class="absolute inset-0 rounded-full bg-green-500 animate-ping opacity-20 group-hover:hidden"></span>
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.937 3.659 1.43 5.623 1.43h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </a>
        </div>
    @endif

    <script>
        // Custom scrollbar handling or other JS
    </script>
</body>
</html>
