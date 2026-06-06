<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $masterData->nama_lembaga ?? 'Genius Education' }} - Platform Manajemen Pendidikan</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ isset($masterData) && $masterData->logo ? Storage::url($masterData->logo) : asset('favicon.png') }}">
    
    <!-- Meta Tags SEO -->
    <meta name="description" content="{{ $masterData->hero_subtitle ?? 'Sistem informasi manajemen pendidikan terpadu untuk bimbingan belajar modern.' }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        /* Fix for mobile horizontal scroll */
        html, body {
            max-width: 100%;
            overflow-x: hidden;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

    <!-- Header / Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 glass-nav border-b border-slate-200/60 h-20">
        <div class="h-full w-full px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-full w-full">
                <div class="flex items-center gap-2 sm:gap-3">
                    @if($masterData && $masterData->logo)
                        <img src="{{ asset('storage/' . $masterData->logo) }}" alt="Logo" class="h-10 w-auto object-contain">
                    @else
                        <div class="h-10 w-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-indigo-200">G</div>
                    @endif
                    <span class="font-black text-lg sm:text-xl tracking-tight block">{{ $masterData->nama_lembaga ?? 'Genius Education' }}</span>
                </div>
                
                <div class="flex items-center gap-2 sm:gap-4">
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-indigo-600 px-3 py-2 transition-colors">Masuk</a>
                    <a href="{{ route('daftar.step1') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-5 sm:px-6 py-2.5 rounded-xl transition-all shadow-lg shadow-indigo-100 active:scale-95">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-24 pb-16 sm:pt-32 sm:pb-20 lg:pt-48 lg:pb-32 overflow-hidden min-h-[75vh] sm:min-h-[85vh] flex items-center bg-slate-900 w-full">
        <!-- Dynamic Background Image -->
        @if($masterData && $masterData->hero_image)
            <div class="absolute inset-0 z-0 w-full h-full">
                <img src="{{ asset('storage/' . $masterData->hero_image) }}" alt="Hero Background" class="w-full h-full object-cover object-center">
                <div class="absolute inset-0 bg-black" style="opacity: {{ $masterData->hero_overlay_opacity ?? 0.5 }};"></div>
            </div>
        @else
            <!-- Fallback Gradient if no image -->
            <div class="absolute inset-0 z-0 bg-gradient-to-br from-indigo-900 via-slate-900 to-black opacity-90 w-full h-full"></div>
        @endif
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center w-full mt-8 sm:mt-0">
            <h1 class="text-3xl sm:text-5xl lg:text-7xl font-black text-white mb-4 sm:mb-6 lg:mb-8 leading-[1.2] sm:leading-[1.1] tracking-tight max-w-4xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                {{ $masterData->hero_title ?? 'Wujudkan Impian Akademik Bersama Kami' }}
            </h1>
            <p class="text-slate-300 text-sm sm:text-base lg:text-xl max-w-2xl mx-auto mb-8 sm:mb-10 lg:mb-12 leading-relaxed font-medium px-2 sm:px-4" data-aos="fade-up" data-aos-delay="200">
                {{ $masterData->hero_subtitle ?? 'Platform pembelajaran terintegrasi yang memudahkan manajemen pendaftaran, progres belajar, dan evaluasi hasil belajar.' }}
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 px-4 sm:px-6" data-aos="fade-up" data-aos-delay="300">
                <a href="#program" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 sm:px-10 sm:py-4 rounded-xl sm:rounded-2xl shadow-xl shadow-indigo-500/30 transition-all active:scale-95 flex items-center justify-center gap-2 sm:gap-3 text-sm sm:text-base">
                    Pilih Program
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg>
                </a>
                <a href="#tentang" class="w-full sm:w-auto bg-white/10 hover:bg-white/20 border-2 border-white/20 text-white font-bold px-6 py-3 sm:px-10 sm:py-4 rounded-xl sm:rounded-2xl transition-all backdrop-blur-md flex items-center justify-center text-sm sm:text-base">Tentang Kami</a>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section id="program" class="py-16 sm:py-20 bg-slate-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 sm:mb-16 gap-4 sm:gap-6 text-center md:text-left">
                <div data-aos="fade-up">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 mb-3 sm:mb-4 tracking-tight">Program Bimbingan Unggulan</h2>
                    <p class="text-slate-500 font-medium max-w-lg mx-auto md:mx-0 text-sm sm:text-base">Pilih jalur bimbingan yang sesuai dengan target dan kebutuhan akademikmu.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @forelse($pakets as $paket)
                    <div class="group bg-white rounded-3xl sm:rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 flex flex-col h-full relative" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <!-- Badge Diskon -->
                        @if($paket->harga_coret && $paket->harga_coret > $paket->nominal)
                            @php $diskon = round((($paket->harga_coret - $paket->nominal) / $paket->harga_coret) * 100); @endphp
                            <div class="absolute top-4 left-4 z-20">
                                <div class="bg-red-500 text-white text-[10px] font-black px-2 py-1 rounded-xl shadow-lg">
                                    {{ $diskon }}% OFF
                                </div>
                            </div>
                        @endif

                        <!-- Header Image -->
                        <div class="relative h-40 sm:h-56 overflow-hidden">
                            @if($paket->gambar_paket)
                                <img src="{{ asset('storage/' . $paket->gambar_paket) }}" alt="{{ $paket->nama_paket }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white/20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>
                            @if($paket->label_populer)
                                <div class="absolute bottom-4 left-4">
                                    <span class="bg-[#F97316] text-white text-[10px] font-black px-3 py-1 rounded-full shadow-lg">✨ {{ $paket->label_populer }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="p-6 sm:p-8 flex-grow flex flex-col">
                            <h3 class="text-xl sm:text-2xl font-black text-slate-900 mb-1 group-hover:text-indigo-600 transition-colors tracking-tight line-clamp-2 break-words">{{ $paket->nama_paket }}</h3>
                            <p class="text-indigo-600 text-[9px] sm:text-[10px] uppercase tracking-[0.2em] mb-4 sm:mb-6 font-black break-words">{{ $paket->target_peserta ?? 'Semua Jenjang' }}</p>
                            
                            <div class="space-y-2.5 mb-6">
                                @php 
                                    $benefits = explode("\n", str_replace("\r", "", $paket->benefits ?? ''));
                                    $topBenefits = array_slice(array_filter($benefits), 0, 3);
                                @endphp
                                @forelse($topBenefits as $benefit)
                                    <div class="flex items-start gap-2">
                                        <div class="flex-shrink-0 w-4 h-4 rounded-full bg-yellow-100 flex items-center justify-center mt-0.5">
                                            <svg class="w-2.5 h-2.5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        </div>
                                        <span class="text-xs text-slate-600 font-bold leading-relaxed break-words line-clamp-2">{{ trim($benefit) }}</span>
                                    </div>
                                @empty
                                    <div class="text-xs text-slate-400 italic">Program intensif terstruktur.</div>
                                @endforelse
                            </div>

                            <div class="mt-auto bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                @if($paket->harga_coret)
                                    <div class="text-[9px] sm:text-[10px] text-slate-400 line-through mb-0.5 font-bold">Rp {{ number_format($paket->harga_coret, 0, ',', '.') }}</div>
                                @endif
                                <div class="flex items-baseline gap-1">
                                    <span class="text-xl sm:text-2xl font-black text-indigo-600">Rp {{ number_format($paket->nominal, 0, ',', '.') }}</span>
                                    @if($paket->durasi_jumlah)
                                        <span class="text-[9px] sm:text-[10px] text-slate-500 font-black uppercase">/ {{ $paket->durasi_jumlah }} {{ $paket->durasi_satuan }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="px-6 pb-6 sm:px-8 sm:pb-8">
                            <a href="{{ route('paket.detail', $paket->id) }}" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 sm:py-4.5 rounded-xl sm:rounded-2xl transition-all shadow-lg shadow-indigo-100 flex items-center justify-center gap-2 active:scale-95 group/btn text-sm sm:text-base">
                                Info Lebih Lanjut
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                        <h3 class="text-xl font-bold text-slate-900 uppercase tracking-wider">Belum Ada Program</h3>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-16 sm:py-24 bg-white overflow-hidden w-full">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <div class="order-2 lg:order-1 relative" data-aos="fade-right">
                    <div class="bg-slate-50 p-6 sm:p-10 lg:p-14 rounded-3xl sm:rounded-[3rem] border border-slate-100 relative z-10 shadow-xl sm:shadow-2xl shadow-slate-100/50">
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 mb-6 sm:mb-8 flex items-center gap-3 sm:gap-4">
                            <span class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xs sm:text-sm shadow-lg shadow-indigo-100">V</span>
                            Visi Kami
                        </h3>
                        <p class="text-slate-600 italic text-base sm:text-xl leading-relaxed mb-8 sm:mb-12 border-l-4 border-indigo-600 pl-4 sm:pl-8">
                            "Menjadi lembaga pendidikan terdepan yang mengintegrasikan teknologi modern dengan metode pembelajaran efektif."
                        </p>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 mb-6 sm:mb-8 flex items-center gap-3 sm:gap-4">
                            <span class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xs sm:text-sm shadow-lg shadow-indigo-100">M</span>
                            Misi Institusi
                        </h3>
                        <ul class="space-y-4 sm:space-y-6 text-slate-600 font-bold">
                            @foreach(['Fasilitas Pembelajaran Digital','Kurikulum Adaptif Standar Tinggi','Evaluasi Sistem CBT Akurat'] as $index => $misi)
                                <li class="flex items-center gap-4 sm:gap-5">
                                    <div class="bg-white text-indigo-600 font-black rounded-lg sm:rounded-xl w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center flex-shrink-0 text-[10px] sm:text-xs shadow-md border border-slate-100">{{ $index + 1 }}</div>
                                    <span class="text-xs sm:text-sm uppercase tracking-wider">{{ $misi }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="order-1 lg:order-2 text-center lg:text-left" data-aos="fade-left">
                    <span class="bg-indigo-50 text-indigo-700 text-[9px] sm:text-[10px] font-black px-3 py-1.5 sm:px-4 sm:py-2 rounded-full mb-4 sm:mb-6 inline-block uppercase tracking-[0.2em] border border-indigo-100">Profil Institusi</span>
                    <h2 class="text-3xl sm:text-4xl lg:text-6xl font-black text-slate-900 mb-6 sm:mb-8 leading-[1.2] sm:leading-[1.1]">Eksosistem Belajar <span class="text-indigo-600 underline decoration-indigo-200 underline-offset-4 sm:underline-offset-8">Modern</span>.</h2>
                    <p class="text-slate-500 text-sm sm:text-lg leading-relaxed mb-8 sm:mb-12 font-medium max-w-2xl mx-auto lg:mx-0">
                        {{ $masterData->tentang_kami ?? 'Kami adalah institusi pendidikan yang berdedikasi tinggi dalam menyediakan bimbingan belajar berkualitas dengan teknologi informasi terkini.' }}
                    </p>
                    <a href="{{ route('daftar.step1') }}" class="inline-flex items-center justify-center gap-3 sm:gap-5 bg-indigo-600 text-white font-black px-6 py-3.5 sm:px-10 sm:py-5 rounded-xl sm:rounded-2xl hover:bg-indigo-700 transition-all shadow-xl sm:shadow-2xl shadow-indigo-200 active:scale-95 group text-sm sm:text-base">
                        Mulai Bergabung
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 group-hover:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>    <!-- Footer Section -->
    <footer class="bg-slate-900 pt-16 sm:pt-24 pb-12 relative overflow-hidden w-full">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Mobile Back to Top Button -->
            <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="md:hidden absolute -top-14 right-4 w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center shadow-2xl active:scale-90 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
            </button>            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 sm:gap-16 mb-12 sm:mb-24 mt-4">
                <div class="col-span-1 md:col-span-2 space-y-6 sm:space-y-8">
                    <a href="#" class="flex items-center gap-3 sm:gap-4">
                        @if($masterData && $masterData->logo)
                            <img src="{{ asset('storage/' . $masterData->logo) }}" alt="Logo" class="h-10 sm:h-14 w-auto object-contain bg-white rounded-xl p-1.5 sm:p-2">
                        @else
                            <div class="h-10 w-10 sm:h-14 sm:w-14 bg-white rounded-xl sm:rounded-2xl flex items-center justify-center text-indigo-600 font-black text-2xl sm:text-3xl">G</div>
                        @endif
                        <span class="font-black text-2xl sm:text-3xl text-white tracking-tight">{{ $masterData->nama_lembaga ?? 'Genius Education' }}</span>
                    </a>
                    <p class="text-slate-400 text-sm sm:text-lg leading-relaxed max-w-sm font-medium">
                        {{ $masterData->hero_subtitle ?? 'Bimbingan belajar masa kini dengan sistem terpadu.' }}
                    </p>
                    <div class="flex items-center gap-4 sm:gap-6">
                        @if($masterData && $masterData->instagram_url)
                            <a href="{{ $masterData->instagram_url }}" class="text-slate-500 hover:text-white transition-colors"><svg class="w-6 h-6 sm:w-7 sm:h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                        @endif
                        @if($masterData && $masterData->wa_number)
                            <a href="https://wa.me/{{ $masterData->wa_number }}" class="text-slate-500 hover:text-white transition-colors"><svg class="w-6 h-6 sm:w-7 sm:h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181 0 6.167 1.24 8.407 3.481 2.242 2.242 3.48 5.226 3.481 8.408-.003 6.557-5.338 11.892-11.893 11.892-1.997 0-3.956-.503-5.69-1.448l-6.301 1.667zm6.155-3.642l.354.21c1.558.924 3.355 1.411 5.2 1.412 5.398 0 9.791-4.393 9.794-9.792.001-2.614-1.017-5.072-2.866-6.922-1.849-1.85-4.307-2.868-6.921-2.868-5.398 0-9.791-4.393-9.794 9.792 0 2.059.54 4.062 1.562 5.807l.233.395-1.015 3.702 3.847-.999zm12.316-8.736c-.3-.149-1.776-.877-2.051-.976-.275-.099-.476-.149-.675.149-.199.299-.773.976-.948 1.176-.175.199-.35.224-.65.075-.3-.149-1.265-.465-2.41-1.487-.89-.793-1.49-1.773-1.665-2.072-.175-.299-.019-.461.13-.609.135-.134.3-.314.45-.471.15-.157.2-.269.3-.449.1-.179.05-.337-.025-.486-.075-.149-.675-1.628-.925-2.226-.243-.586-.489-.507-.675-.516-.173-.008-.372-.01-.571-.01-.198 0-.52.074-.797.373-.274.299-1.047 1.023-1.047 2.493 0 1.47 1.071 2.891 1.22 3.091.149.199 2.108 3.218 5.105 4.512.713.308 1.27.492 1.705.631.716.227 1.368.195 1.883.118.574-.085 1.776-.726 2.025-1.42.25-.694.25-1.288.175-1.42-.075-.133-.275-.208-.575-.357z"/></svg></a>
                        @endif
                    </div>
                </div>

                <div class="col-span-1">
                    <h4 class="font-black text-white mb-6 uppercase tracking-[0.2em] text-xs">Program</h4>
                    <ul class="space-y-4 mb-8">
                        @foreach($pakets->take(4) as $p)
                            <li><a href="{{ route('paket.detail', $p->id) }}" class="text-slate-400 hover:text-white transition-colors text-sm font-bold flex items-center gap-3 group">
                                <span class="w-2 h-2 rounded-full bg-slate-800 group-hover:bg-indigo-500 transition-all"></span>
                                {{ $p->nama_paket }}
                            </a></li>
                        @endforeach
                    </ul>
                    <a href="#program" class="inline-flex items-center gap-3 text-[10px] font-black text-white bg-slate-800 hover:bg-indigo-600 px-5 py-3 rounded-2xl transition-all uppercase tracking-widest active:scale-95">
                        Semua Program &rarr;
                    </a>
                </div>

                <div class="col-span-1">
                    <h4 class="font-black text-white mb-6 uppercase tracking-[0.2em] text-xs">Pendaftaran & Akun</h4>
                    <ul class="space-y-4 text-sm font-bold flex flex-col items-start">
                        <li><a href="{{ route('welcome') }}" class="text-slate-400 hover:text-white transition-colors">Beranda Utama</a></li>
                        <li><a href="{{ route('daftar.step1') }}" class="text-indigo-400 hover:text-indigo-300 transition-colors">Daftar Sekarang</a></li>
                        <li><a href="{{ route('login') }}" class="text-slate-400 hover:text-white transition-colors">Masuk Ke Portal</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.2em]">
                    &copy; {{ date('Y') }} {{ $masterData->nama_lembaga ?? 'Genius Education' }}. Sistem Manajemen Pendidikan Terpadu.
                </p>
                <div class="flex items-center gap-6 text-[10px] font-black text-slate-600 uppercase tracking-widest">
                    <span onclick="openModal('modal-tc')" class="hover:text-white transition-colors cursor-pointer">T&C</span>
                    <span onclick="openModal('modal-privacy')" class="hover:text-white transition-colors cursor-pointer">Privacy Policy</span>
                </div>
            </div>
        </div>
        
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-600/10 rounded-full blur-[100px]"></div>
    </footer>

    <!-- Floating WA Widget -->
    @if($masterData && $masterData->wa_widget_status && $masterData->wa_number)
        <div class="fixed bottom-6 right-6 z-50">
            <a href="https://wa.me/{{ $masterData->wa_number }}?text={{ urlencode($masterData->wa_widget_message ?? 'Halo, saya ingin mendapatkan informasi lebih lanjut.') }}" 
               target="_blank"
               class="bg-[#25D366] text-white rounded-2xl w-14 h-14 sm:w-16 sm:h-16 flex items-center justify-center shadow-2xl transition-all hover:scale-110 hover:-rotate-6 active:scale-90 group"
               aria-label="Hubungi WhatsApp">
                <svg class="w-8 h-8 sm:w-9 sm:h-9" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.937 3.659 1.43 5.623 1.43h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </a>
        </div>
    @endif

    <!-- Modal T&C -->
    <div id="modal-tc" class="fixed inset-0 z-[100] hidden flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modal-tc')"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-3xl p-6 sm:p-8 shadow-2xl scale-95 opacity-0 transition-all duration-300" id="modal-tc-content">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Syarat & Ketentuan</h2>
                <button onclick="closeModal('modal-tc')" class="w-8 h-8 flex items-center justify-center bg-slate-100 hover:bg-red-100 hover:text-red-600 rounded-full transition-colors">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="prose prose-sm text-slate-600 max-h-[60vh] overflow-y-auto pr-2">
                <p>Selamat datang di <strong>{{ $masterData->nama_lembaga ?? 'Genius Education' }}</strong>. Dengan mengakses dan menggunakan sistem kami, Anda menyetujui syarat berikut:</p>
                <h4 class="font-bold text-slate-800 mt-4">1. Penggunaan Layanan</h4>
                <p>Platform ini disediakan untuk menunjang kegiatan akademik, ujian CBT, dan pembayaran tagihan bimbingan belajar. Segala bentuk penyalahgunaan sistem akan ditindak tegas.</p>
                <h4 class="font-bold text-slate-800 mt-4">2. Keamanan Akun</h4>
                <p>Anda bertanggung jawab penuh untuk menjaga kerahasiaan kata sandi akun Anda. Kami tidak bertanggung jawab atas kerugian yang timbul akibat kelalaian pengguna.</p>
                <h4 class="font-bold text-slate-800 mt-4">3. Transaksi & Pembayaran</h4>
                <p>Pembayaran paket bimbingan bersifat final. Layanan yang sudah dibeli tidak dapat di-refund kecuali terdapat kesalahan teknis dari pihak kami yang menyebabkan layanan tidak dapat digunakan sama sekali.</p>
            </div>
            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                <button onclick="closeModal('modal-tc')" class="bg-indigo-600 text-white font-bold px-6 py-2.5 rounded-xl hover:bg-indigo-700 transition-colors text-sm">Mengerti</button>
            </div>
        </div>
    </div>

    <!-- Modal Privacy Policy -->
    <div id="modal-privacy" class="fixed inset-0 z-[100] hidden flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modal-privacy')"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-3xl p-6 sm:p-8 shadow-2xl scale-95 opacity-0 transition-all duration-300" id="modal-privacy-content">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Kebijakan Privasi</h2>
                <button onclick="closeModal('modal-privacy')" class="w-8 h-8 flex items-center justify-center bg-slate-100 hover:bg-red-100 hover:text-red-600 rounded-full transition-colors">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="prose prose-sm text-slate-600 max-h-[60vh] overflow-y-auto pr-2">
                <p>Privasi Anda sangat penting bagi <strong>{{ $masterData->nama_lembaga ?? 'Genius Education' }}</strong>. Kebijakan ini menjelaskan bagaimana kami mengumpulkan dan melindungi data Anda.</p>
                <h4 class="font-bold text-slate-800 mt-4">1. Pengumpulan Data</h4>
                <p>Kami mengumpulkan informasi pribadi yang Anda berikan saat mendaftar, seperti nama, email, nomor telepon, dan data akademik yang diperlukan untuk proses belajar.</p>
                <h4 class="font-bold text-slate-800 mt-4">2. Penggunaan Informasi</h4>
                <p>Data Anda hanya digunakan untuk keperluan internal institusi, seperti komunikasi akademik, penilaian hasil CBT, dan riwayat tagihan.</p>
                <h4 class="font-bold text-slate-800 mt-4">3. Keamanan Data</h4>
                <p>Kami berkomitmen untuk melindungi data pribadi Anda menggunakan standar keamanan server yang memadai, dan tidak akan menjual atau membagikan data Anda kepada pihak ketiga tanpa izin resmi.</p>
            </div>
            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                <button onclick="closeModal('modal-privacy')" class="bg-indigo-600 text-white font-bold px-6 py-2.5 rounded-xl hover:bg-indigo-700 transition-colors text-sm">Tutup</button>
            </div>
        </div>
    </div>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            duration: 800,
            offset: 50,
        });

        // Modal Logic
        function openModal(id) {
            const modal = document.getElementById(id);
            const content = document.getElementById(id + '-content');
            modal.classList.remove('hidden');
            // Trigger reflow
            void modal.offsetWidth;
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            const content = document.getElementById(id + '-content');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }
    </script>
    @include('components.loading-overlay')
</body>
</html>
