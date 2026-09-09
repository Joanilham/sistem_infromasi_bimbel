<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Semua Program Bimbingan - {{ $masterData->nama_lembaga ?? config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400..700;1,6..72,400..700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="{{ asset('vendor/alpinejs/alpine.min.js') }}"></script>

    <style>
        [x-cloak] { display: none !important; }
        html, body { 
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif; 
            background-color: #FAF8F5;
            color: #141413;
            max-width: 100%;
        }
        .font-editorial {
            font-family: 'Newsreader', Georgia, serif;
        }
        .glass-nav {
            background: rgba(250, 248, 245, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .wrapper {
            overflow-x: hidden;
            width: 100%;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
</head>
<body class="bg-[#FAF8F5] text-[#141413] antialiased selection:bg-[#FDEEE9] selection:text-[#E14D2A]">

    <div class="wrapper" x-data="{ 
        activeJenjang: 'all', 
        searchQuery: '',
        matches(jenjang, title, desc) {
            const matchesJenjang = (this.activeJenjang === 'all' || jenjang.toLowerCase().includes(this.activeJenjang.toLowerCase()));
            const q = this.searchQuery.trim().toLowerCase();
            const matchesSearch = !q || title.toLowerCase().includes(q) || desc.toLowerCase().includes(q);
            return matchesJenjang && matchesSearch;
        }
    }">
        @include('landing.partials.navbar')
        
        <!-- Header Section: Clean Warm Editorial -->
        <section class="pt-32 sm:pt-36 pb-12 sm:pb-16 bg-[#FAF8F5] border-b border-[#E7E2D9] relative overflow-hidden">
            <!-- Subtle decorative background warmth -->
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <div class="absolute -top-32 right-1/4 w-96 h-96 bg-[#fed7aa]/30 rounded-full blur-3xl"></div>
                <div class="absolute top-10 left-10 w-80 h-80 bg-[#fecdd3]/20 rounded-full blur-3xl"></div>
            </div>

            <div class="w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 relative z-10">
                
                <!-- Breadcrumbs -->
                <nav class="flex items-center gap-2 text-xs font-mono text-[#78716C] uppercase tracking-wider mb-6">
                    <a href="{{ route('welcome') }}" class="hover:text-[#E14D2A] transition-colors">Beranda</a>
                    <span>/</span>
                    <span class="text-[#141413] font-bold">Katalog Program</span>
                </nav>

                <div class="max-w-3xl mb-10">
                    <span class="text-xs font-mono uppercase tracking-widest text-[#78716C] font-semibold block mb-3">
                        [ KATALOG PROGRAM LENGKAP ]
                    </span>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#141413] tracking-tight leading-tight mb-4">
                        Semua Program Bimbingan
                    </h1>
                    <p class="text-[#57534E] text-base sm:text-lg leading-relaxed font-normal">
                        Temukan dan pilih program bimbingan belajar terbaik yang dirancang secara khusus dan adaptif untuk mencapai target akademik dan kelulusan impian Anda.
                    </p>
                </div>

                <!-- Filter & Search Bar -->
                <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 pt-4 border-t border-[#E7E2D9]/80">
                    
                    <!-- Jenjang Filter Pills -->
                    @php
                        $uniqueJenjangs = $pakets->pluck('target_peserta')->filter()->unique()->values();
                    @endphp
                    <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 custom-scrollbar">
                        <button type="button" 
                                @click="activeJenjang = 'all'"
                                :class="activeJenjang === 'all' ? 'bg-[#141413] text-white shadow-xs' : 'bg-white text-[#57534E] hover:bg-[#F4EFEA] hover:text-[#141413] border border-[#E7E2D9]'"
                                class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all duration-200 cursor-pointer">
                            Semua Jenjang
                        </button>
                        @foreach($uniqueJenjangs as $jenjang)
                            <button type="button" 
                                    @click="activeJenjang = '{{ strtolower($jenjang) }}'"
                                    :class="activeJenjang === '{{ strtolower($jenjang) }}' ? 'bg-[#141413] text-white shadow-xs' : 'bg-white text-[#57534E] hover:bg-[#F4EFEA] hover:text-[#141413] border border-[#E7E2D9]'"
                                    class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all duration-200 cursor-pointer">
                                {{ $jenjang }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Search Input -->
                    <div class="relative min-w-[280px] sm:min-w-[340px]">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#78716C]">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" 
                               x-model="searchQuery" 
                               placeholder="Cari program bimbingan..." 
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[#E7E2D9] bg-white text-sm text-[#141413] placeholder-[#A8A29E] focus:outline-none focus:border-[#E14D2A] focus:ring-2 focus:ring-[#E14D2A]/15 transition-all">
                        <button type="button" 
                                x-show="searchQuery.length > 0" 
                                @click="searchQuery = ''"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#A8A29E] hover:text-[#141413]">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                </div>

            </div>
        </section>

        <!-- Programs Grid Section -->
        <section class="py-16 sm:py-20 bg-[#FAF8F5] min-h-[50vh]">
            <div class="w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @forelse($pakets as $paket)
                        @php
                            $isPopular = !empty($paket->label_populer);
                            $targetPeserta = $paket->target_peserta ?? 'Semua Jenjang';
                            $deskripsiSafe = $paket->deskripsi ?? 'Pendampingan belajar intensif dengan kurikulum adaptif, modul terstruktur, simulasi berkala, dan bimbingan mentor berpengalaman.';
                        @endphp
                        
                        <div x-show="matches('{{ addslashes($targetPeserta) }}', '{{ addslashes($paket->nama_paket) }}', '{{ addslashes($deskripsiSafe) }}')"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="group bg-white rounded-2xl overflow-hidden border {{ $isPopular ? 'border-[#E14D2A] ring-1 ring-[#E14D2A]/20' : 'border-[#E7E2D9]' }} shadow-xs hover:border-[#D5CEC4] hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                            
                            <div>
                                <!-- Cover Image -->
                                <div class="relative h-40 sm:h-44 overflow-hidden bg-[#141413]">
                                    @if($paket->gambar_paket)
                                        <img src="{{ asset('storage/' . $paket->gambar_paket) }}" alt="{{ $paket->nama_paket }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-[#262422] via-[#1C1A18] to-[#141413] flex items-center justify-center text-[#A8A29E]/60 relative">
                                            <div class="w-11 h-11 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center shadow-inner">
                                                <svg class="w-5 h-5 text-[#E14D2A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#141413]/80 via-transparent to-transparent"></div>
                                    
                                    <!-- Badges -->
                                    <div class="absolute top-3 left-3 right-3 flex items-center justify-between">
                                        <span class="px-2 py-0.5 rounded bg-[#141413]/85 backdrop-blur-sm text-white text-[10px] font-mono uppercase tracking-wider">
                                            {{ $targetPeserta }}
                                        </span>
                                        @if($isPopular)
                                            <span class="px-2 py-0.5 rounded bg-[#E14D2A] text-white text-[10px] font-mono uppercase tracking-wider font-bold shadow-xs">
                                                ✨ {{ $paket->label_populer }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Card Content -->
                                <div class="p-5 sm:p-6 flex flex-col flex-grow">
                                    <h2 class="text-base sm:text-lg font-bold text-[#141413] mb-2 leading-snug group-hover:text-[#E14D2A] transition-colors line-clamp-2">
                                        <a href="{{ route('paket.detail', $paket->id) }}">
                                            {{ $paket->nama_paket }}
                                        </a>
                                    </h2>

                                    @if($paket->deskripsi)
                                        <p class="text-xs sm:text-sm text-[#57534E] leading-relaxed mb-4 font-normal line-clamp-2">
                                            {{ $paket->deskripsi }}
                                        </p>
                                    @endif

                                    <!-- Facilities / Benefits Checklist -->
                                    @php 
                                        $benefits = array_filter(array_map('trim', explode("\n", str_replace("\r", "", $paket->benefits ?? ''))));
                                        $topBenefits = array_slice($benefits, 0, 3);
                                    @endphp
                                    @if(count($topBenefits) > 0)
                                        <div class="space-y-1.5 pt-3 border-t border-[#F4EFEA] mb-4">
                                            <div class="text-[10px] font-mono uppercase tracking-wider text-[#78716C] mb-1.5 font-semibold">Benefit & Fasilitas:</div>
                                            @foreach($topBenefits as $benefit)
                                                <div class="flex items-start gap-2 text-[11px] sm:text-xs text-[#44403C]">
                                                    <svg class="w-3.5 h-3.5 text-[#E14D2A] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span class="leading-relaxed line-clamp-1">{{ $benefit }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Price & Action Footer -->
                            <div class="p-5 pt-0 border-t border-[#F4EFEA] mt-auto">
                                <div class="flex items-baseline justify-between mb-3 pt-3">
                                    <div>
                                        <span class="text-[10px] font-mono text-[#78716C] uppercase tracking-wider block">Biaya Investasi</span>
                                        <div class="flex items-baseline gap-2">
                                            <span class="text-xl sm:text-2xl font-extrabold text-[#141413] tracking-tight">
                                                Rp {{ number_format($paket->nominal, 0, ',', '.') }}
                                            </span>
                                            @if($paket->harga_coret && $paket->harga_coret > $paket->nominal)
                                                <span class="text-xs text-[#A8A29E] line-through">
                                                    Rp {{ number_format($paket->harga_coret, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($paket->durasi_jumlah)
                                        <span class="text-[10px] text-[#78716C] font-mono">/ {{ $paket->durasi_jumlah }} {{ $paket->durasi_satuan }}</span>
                                    @else
                                        <span class="text-[10px] text-[#78716C] font-mono">Per Program</span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('paket.detail', $paket->id) }}" 
                                       class="w-full bg-[#141413] hover:bg-[#292524] text-white font-semibold py-2.5 rounded-lg text-xs transition-colors text-center cursor-pointer">
                                        Detail
                                    </a>
                                    <a href="{{ route('daftar.step1', ['paket_id' => $paket->id]) }}" 
                                       class="w-full bg-[#E14D2A] hover:bg-[#C93B1A] text-white font-semibold py-2.5 rounded-lg text-xs transition-colors text-center shadow-xs cursor-pointer">
                                        Daftar Paket
                                    </a>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="col-span-full text-center py-20 bg-white rounded-2xl border border-dashed border-[#E7E2D9]">
                            <div class="w-12 h-12 rounded-full bg-[#F4EFEA] flex items-center justify-center mx-auto mb-3 text-[#78716C]">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                            </div>
                            <h3 class="text-base font-bold text-[#141413] mb-1">Belum Ada Program Bimbingan</h3>
                            <p class="text-xs text-[#78716C]">Paket bimbingan belum dipublikasikan oleh administrator.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Call to Action Banner -->
                <div class="mt-16 sm:mt-24 p-8 sm:p-12 rounded-3xl bg-[#141413] text-white relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="absolute -right-16 -top-16 w-80 h-80 bg-[#E14D2A]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 max-w-xl text-center md:text-left">
                        <span class="text-xs font-mono uppercase tracking-widest text-[#E14D2A] font-bold block mb-2">
                            [ KONSULTASI GRATIS ]
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight mb-3">
                            Bingung Menentukan Paket yang Tepat?
                        </h2>
                        <p class="text-[#A8A29E] text-sm sm:text-base leading-relaxed">
                            Diskusikan minat, target nilai, dan rencana studi masa depan Anda dengan tim konsultan pendidikan kami. Dapatkan rekomendasi paket terbaik tanpa biaya.
                        </p>
                    </div>
                    <div class="relative z-10 flex flex-col sm:flex-row items-center gap-3 shrink-0 w-full sm:w-auto">
                        @if($masterData && $masterData->wa_number)
                            <a href="https://wa.me/{{ $masterData->wa_number }}?text={{ urlencode('Halo, saya ingin konsultasi mengenai pemilihan paket bimbingan belajar.') }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#E14D2A] hover:bg-[#C93B1A] text-white font-semibold px-6 py-3.5 rounded-xl text-sm transition-colors shadow-sm">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                Tanya Tim Akademik
                            </a>
                        @endif
                        <a href="{{ route('daftar.step1') }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/15 text-white font-semibold px-6 py-3.5 rounded-xl text-sm transition-colors">
                            Daftar Siswa Baru
                        </a>
                    </div>
                </div>

            </div>
        </section>

        @include('landing.partials.footer')
        @include('landing.partials.widget')
        @include('landing.partials.modals')
    </div>

    @include('components.loading-overlay')
</body>
</html>
