<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Semua Program Bimbingan | {{ $masterData->nama_lembaga ?? config('app.name') }}</title>
    
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
        html, body { max-width: 100%; }
        .wrapper { overflow-x: hidden; width: 100%; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

    <div class="wrapper">
        @include('landing.partials.navbar')
        
        <!-- Header Section -->
        <section class="pt-32 pb-16 bg-gradient-to-br from-indigo-900 via-indigo-800 to-indigo-900 relative overflow-hidden">
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
                <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
            </div>
            
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white mb-6 tracking-tight" data-aos="fade-down">
                    Semua Program Bimbingan
                </h1>
                <p class="text-indigo-100 text-lg max-w-2xl mx-auto font-medium" data-aos="fade-up" data-aos-delay="100">
                    Temukan dan pilih program bimbingan belajar terbaik yang dirancang khusus untuk mencapai target akademikmu bersama kami.
                </p>
            </div>
        </section>

        <!-- Programs Section (Reused styling from partials.programs) -->
        <section class="py-16 bg-slate-50 min-h-screen">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @forelse($pakets as $paket)
                        <div class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 flex flex-col h-full relative" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 50 }}">
                            <!-- Badge Diskon Removed -->

                            <!-- Header Image -->
                            <div class="relative h-40 sm:h-48 overflow-hidden">
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

                            <div class="p-6 flex-grow flex flex-col">
                                <h3 class="text-xl font-black text-slate-900 mb-1 group-hover:text-indigo-600 transition-colors tracking-tight line-clamp-2 break-words">{{ $paket->nama_paket }}</h3>
                                <p class="text-indigo-600 text-[9px] sm:text-[10px] uppercase tracking-[0.2em] mb-4 font-black break-words">{{ $paket->target_peserta ?? 'Semua Jenjang' }}</p>
                                
                                <div class="space-y-2.5 mb-6">
                                    @php 
                                        $benefits = explode("\n", str_replace("\r", "", $paket->benefits ?? ''));
                                        $topBenefits = array_slice(array_filter($benefits), 0, 4);
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
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-xl font-black text-indigo-600">Rp {{ number_format($paket->nominal, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 pb-6 flex-shrink-0 mt-2">
                                <a href="{{ route('paket.detail', $paket->id) }}" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 rounded-xl transition-all shadow-lg shadow-indigo-100 flex items-center justify-center gap-2 active:scale-95 group/btn text-sm">
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

        @include('landing.partials.footer')
        @include('landing.partials.widget')
        @include('landing.partials.modals')
    </div>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            AOS.init({ once: true, duration: 800, offset: 50 });
            window.addEventListener('load', () => AOS.refresh());
        });
    </script>
    @include('components.loading-overlay')
</body>
</html>
