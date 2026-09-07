<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Paket {{ $paket->nama_paket }} - {{ $masterData->nama_lembaga ?? config('app.name') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400..700;1,6..72,400..700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
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
        @media (min-width: 1024px) {
            .detail-grid {
                display: grid !important;
                grid-template-columns: minmax(0, 1fr) 380px !important;
                gap: 2.5rem !important;
                align-items: start !important;
            }
            .detail-main {
                width: 100% !important;
                min-width: 0 !important;
            }
            .detail-sidebar {
                width: 100% !important;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
</head>
<body class="bg-[#FAF8F5] text-[#141413] antialiased selection:bg-[#FDEEE9] selection:text-[#E14D2A] flex flex-col min-h-screen">

    <div class="wrapper flex-grow">
        @include('landing.partials.navbar')

        <!-- Hero / Breadcrumb Header -->
        <section class="pt-32 sm:pt-36 pb-10 bg-[#FAF8F5] border-b border-[#E7E2D9] relative overflow-hidden">
            <div class="w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
                <!-- Breadcrumbs -->
                <nav class="flex items-center gap-2 text-xs font-mono text-[#78716C] uppercase tracking-wider mb-6">
                    <a href="{{ route('welcome') }}" class="hover:text-[#E14D2A] transition-colors">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('paket.index') }}" class="hover:text-[#E14D2A] transition-colors">Katalog Program</a>
                    <span>/</span>
                    <span class="text-[#141413] font-bold truncate max-w-[200px] sm:max-w-none">{{ $paket->nama_paket }}</span>
                </nav>

                <div class="flex flex-wrap items-center gap-2.5 mb-4">
                    <span class="px-3 py-1 rounded-lg bg-[#141413] text-white text-xs font-mono uppercase tracking-wider">
                        {{ $paket->target_peserta ?: 'Semua Jenjang' }}
                    </span>
                    @if($paket->durasi_jumlah)
                        <span class="px-3 py-1 rounded-lg bg-white border border-[#E7E2D9] text-[#57534E] text-xs font-mono font-semibold">
                            Durasi: {{ $paket->durasi_jumlah }} {{ $paket->durasi_satuan }}
                        </span>
                    @endif
                    @if(!empty($paket->label_populer))
                        <span class="px-3 py-1 rounded-lg bg-[#E14D2A] text-white text-xs font-mono uppercase tracking-wider font-bold shadow-xs">
                            ✨ {{ $paket->label_populer }}
                        </span>
                    @endif
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#141413] tracking-tight leading-tight mb-4">
                    {{ $paket->nama_paket }}
                </h1>
                <p class="text-[#57534E] text-base sm:text-lg max-w-3xl leading-relaxed">
                    {{ $paket->deskripsi ?: 'Program bimbingan komprehensif yang dirancang untuk membantu siswa mencapai target akademik secara optimal.' }}
                </p>
            </div>
        </section>

        <!-- Main Content Section -->
        <main class="py-12 sm:py-16" x-data="{ activeTab: 'detail' }">
            <div class="w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start detail-grid">
                    
                    <!-- Left Column: Tabs & Detailed Info (8 cols) -->
                    <div class="lg:col-span-8 space-y-8 min-w-0 w-full detail-main">
                        
                        <!-- Tabs Navigation -->
                        <div class="flex gap-2 pb-2 border-b border-[#E7E2D9] overflow-x-auto custom-scrollbar">
                            <button @click="activeTab = 'detail'" 
                                    :class="activeTab === 'detail' ? 'bg-[#141413] text-white shadow-xs' : 'bg-white text-[#57534E] hover:bg-[#F4EFEA] hover:text-[#141413] border border-[#E7E2D9]'" 
                                    class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all duration-200 cursor-pointer">
                                Detail & Keunggulan
                            </button>
                            <button @click="activeTab = 'fasilitas'" 
                                    :class="activeTab === 'fasilitas' ? 'bg-[#141413] text-white shadow-xs' : 'bg-white text-[#57534E] hover:bg-[#F4EFEA] hover:text-[#141413] border border-[#E7E2D9]'" 
                                    class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all duration-200 cursor-pointer">
                                Fasilitas Belajar
                            </button>
                            <button @click="activeTab = 'ulasan'" 
                                    :class="activeTab === 'ulasan' ? 'bg-[#141413] text-white shadow-xs' : 'bg-white text-[#57534E] hover:bg-[#F4EFEA] hover:text-[#141413] border border-[#E7E2D9]'" 
                                    class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all duration-200 cursor-pointer">
                                Ulasan Siswa
                            </button>
                        </div>

                        <!-- Tab Content: Detail -->
                        <div x-show="activeTab === 'detail'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                            
                            @if($paket->gambar_paket)
                                <div class="bg-white rounded-2xl overflow-hidden border border-[#E7E2D9] shadow-xs">
                                    <div class="aspect-[21/9] sm:aspect-[16/7] w-full bg-stone-900 overflow-hidden">
                                        <img src="{{ asset('storage/' . $paket->gambar_paket) }}" alt="{{ $paket->nama_paket }}" class="w-full h-full object-cover">
                                    </div>
                                </div>
                            @endif

                            <!-- Card: Yang Akan Didapatkan -->
                            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-[#E7E2D9] shadow-xs">
                                <div class="flex items-center justify-between mb-6">
                                    <h2 class="text-xl font-bold text-[#141413]">Benefit & Keunggulan Paket</h2>
                                    <span class="text-xs font-mono uppercase tracking-wider text-[#78716C]">[ EKSKLUSIF ]</span>
                                </div>
                                
                                @php 
                                    $benefitList = array_filter(array_map('trim', explode("\n", str_replace("\r", "", $paket->benefits ?? '')))); 
                                @endphp
                                @if(count($benefitList) > 0)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($benefitList as $benefit)
                                            <div class="flex items-start gap-3 p-3.5 rounded-xl bg-[#FAF8F5] border border-[#F4EFEA]">
                                                <div class="mt-0.5 bg-[#E14D2A]/10 text-[#E14D2A] rounded-lg p-1 shrink-0">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                </div>
                                                <span class="text-xs sm:text-sm text-[#44403C] font-medium leading-relaxed">{{ $benefit }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-6 rounded-xl bg-[#FAF8F5] border border-[#F4EFEA] text-center">
                                        <p class="text-xs sm:text-sm text-[#78716C] leading-relaxed">
                                            Materi adaptif, modul pembelajaran terstruktur, dan pendampingan mentor berdedikasi untuk memaksimalkan capaian akademik siswa.
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Card: Tentang Program -->
                            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-[#E7E2D9] shadow-xs">
                                <h2 class="text-xl font-bold text-[#141413] mb-4">Mengenai Program Ini</h2>
                                <p class="text-[#57534E] text-sm sm:text-base leading-relaxed whitespace-pre-line">
                                    {{ $paket->deskripsi ?: 'Program bimbingan komprehensif yang dirancang untuk membantu siswa mencapai target akademik secara optimal melalui pendekatan belajar terstruktur dan evaluasi berkala.' }}
                                </p>
                            </div>

                        </div>

                        <!-- Tab Content: Fasilitas -->
                        <div x-show="activeTab === 'fasilitas'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-[#E7E2D9] shadow-xs">
                                <div class="flex items-center justify-between mb-6">
                                    <h2 class="text-xl font-bold text-[#141413]">Fasilitas Penunjang Pembelajaran</h2>
                                    <span class="text-xs font-mono uppercase tracking-wider text-[#78716C]">[ FASILITAS ]</span>
                                </div>
                                
                                @php 
                                    $fasilitasList = array_filter(array_map('trim', preg_split('/[\r\n,]+/', $paket->fasilitas ?? '')));
                                @endphp

                                @if(count($fasilitasList) > 0)
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @foreach($fasilitasList as $fasilitasItem)
                                            <div class="flex items-start gap-4 p-4 rounded-xl bg-[#FAF8F5] border border-[#F4EFEA]">
                                                <div class="w-10 h-10 rounded-xl bg-[#141413] text-white flex items-center justify-center shrink-0">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-sm text-[#141413]">{{ $fasilitasItem }}</h4>
                                                    <p class="text-xs text-[#78716C] mt-0.5">Fasilitas resmi untuk mendukung kenyamanan & fokus belajar siswa.</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="flex items-start gap-4 p-4 rounded-xl bg-[#FAF8F5] border border-[#F4EFEA]">
                                            <div class="w-10 h-10 rounded-xl bg-[#141413] text-white flex items-center justify-center shrink-0">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-sm text-[#141413]">Ruang Kelas & Fasilitas Belajar</h4>
                                                <p class="text-xs text-[#78716C] mt-0.5">Lingkungan belajar kondusif dengan rasio peserta proporsional.</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-4 p-4 rounded-xl bg-[#FAF8F5] border border-[#F4EFEA]">
                                            <div class="w-10 h-10 rounded-xl bg-[#E14D2A] text-white flex items-center justify-center shrink-0">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-sm text-[#141413]">Bank Soal & Modul Belajar</h4>
                                                <p class="text-xs text-[#78716C] mt-0.5">Materi terstruktur dan latihan soal sesuai kurikulum terbaru.</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Tab Content: Ulasan -->
                        <div x-show="activeTab === 'ulasan'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-[#E7E2D9] shadow-xs">
                                <h2 class="text-xl font-bold text-[#141413] mb-6">Pengalaman Siswa & Alumni</h2>
                                
                                <div class="space-y-4">
                                    @forelse($testimonials ?? [] as $testi)
                                        <div class="p-5 rounded-xl bg-[#FAF8F5] border border-[#F4EFEA]">
                                            <div class="flex items-center gap-3.5 mb-3">
                                                <div class="w-10 h-10 rounded-full bg-[#E7E2D9] overflow-hidden shrink-0 flex items-center justify-center font-bold text-xs text-[#78716C]">
                                                    @if($testi->foto)
                                                        <img src="{{ asset('storage/' . $testi->foto) }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ substr($testi->nama, 0, 1) }}
                                                    @endif
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-sm text-[#141413]">{{ $testi->nama }}</h4>
                                                    <p class="text-[11px] text-[#78716C]">{{ $testi->posisi }}</p>
                                                </div>
                                                <div class="ml-auto flex gap-0.5 text-amber-400">
                                                    @for($i=0; $i<$testi->bintang; $i++)
                                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    @endfor
                                                </div>
                                            </div>
                                            <p class="text-xs sm:text-sm text-[#57534E] italic leading-relaxed">"{{ $testi->ulasan }}"</p>
                                        </div>
                                    @empty
                                        <div class="text-center py-10 text-xs text-[#78716C] italic">
                                            Belum ada ulasan untuk program ini. Jadilah siswa pertama yang meraih prestasi bersama kami!
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column: Sticky Pricing & Checkout Card (4 cols) -->
                    <div class="lg:col-span-4 w-full detail-sidebar">
                        <div class="sticky top-28 bg-white rounded-2xl border border-[#E7E2D9] shadow-md p-6 sm:p-7">
                            
                            <span class="text-xs font-mono uppercase tracking-wider text-[#78716C] block mb-1">Investasi Program</span>
                            
                            <div class="mb-6">
                                @if($paket->harga_coret && $paket->harga_coret > $paket->nominal)
                                    <div class="text-xs text-[#A8A29E] line-through mb-1">
                                        Rp {{ number_format($paket->harga_coret, 0, ',', '.') }}
                                    </div>
                                @endif
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-extrabold text-[#141413] tracking-tight">
                                        Rp {{ number_format($paket->nominal, 0, ',', '.') }}
                                    </span>
                                    @if($paket->durasi_jumlah)
                                        <span class="text-xs text-[#78716C] font-mono">/ {{ $paket->durasi_jumlah }} {{ $paket->durasi_satuan }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="space-y-3 mb-6">
                                <a href="{{ route('daftar.step1', ['paket_id' => $paket->id]) }}" 
                                   class="block w-full text-center bg-[#E14D2A] hover:bg-[#C93B1A] text-white font-bold py-3.5 px-4 rounded-xl shadow-xs transition-colors text-sm">
                                    Daftar Paket Sekarang
                                </a>

                                @if($masterData && $masterData->wa_number)
                                    <a href="https://wa.me/{{ $masterData->wa_number }}?text={{ urlencode('Halo, saya tertarik dengan paket ' . $paket->nama_paket . '. Bisa minta info jadwal dan pendaftaran?') }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       class="block w-full text-center bg-white border border-[#E7E2D9] text-[#141413] hover:bg-[#F4EFEA] font-semibold py-3 px-4 rounded-xl transition-colors text-sm flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4 text-[#22C55E] fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                        Tanya Admin via WA
                                    </a>
                                @endif
                            </div>

                            <!-- Real Package Info / Guarantees list -->
                            <div class="pt-5 border-t border-[#F4EFEA] space-y-2.5 text-xs text-[#57534E]">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#E14D2A] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Minimal DP: <strong>{{ $paket->dp_persen_minimal ?? 10 }}%</strong> (Rp {{ number_format($paket->nominal * (($paket->dp_persen_minimal ?? 10) / 100), 0, ',', '.') }})</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#E14D2A] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    <span>Skema: <strong>{{ $paket->bisa_dicicil ? 'Tersedia cicilan fleksibel' : 'Pembayaran lunas' }}</strong></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#E14D2A] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Durasi: <strong>{{ $paket->durasi_jumlah ? $paket->durasi_jumlah . ' ' . $paket->durasi_satuan : '1 Tahun' }}</strong></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#E14D2A] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span>Sasaran: <strong>{{ $paket->target_peserta ?: 'Semua Jenjang' }}</strong></span>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </main>

        @include('landing.partials.footer')
        @include('landing.partials.widget')
        @include('landing.partials.modals')
    </div>

    @include('components.loading-overlay')
</body>
</html>
