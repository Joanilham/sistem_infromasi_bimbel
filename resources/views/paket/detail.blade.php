<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Paket - {{ $paket->nama_paket }} | {{ $masterData->nama_lembaga ?? 'Genius Education' }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
</head>
<body class="bg-[#F3F4F6] text-slate-800 antialiased selection:bg-[#1A56DB] selection:text-white flex flex-col min-h-screen">

    <!-- Navigation -->
    <header x-data="{ mobileMenuOpen: false }" class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                        @if($masterData && $masterData->logo)
                            <img src="{{ asset('storage/' . $masterData->logo) }}" alt="Logo" class="h-8 w-auto object-contain">
                        @else
                            <div class="h-8 w-8 bg-[#1A56DB] rounded flex items-center justify-center text-white font-bold text-lg">
                                G
                            </div>
                        @endif
                        <span class="font-bold text-lg text-slate-900 hidden sm:block">{{ $masterData->nama_lembaga ?? 'Genius Education' }}</span>
                    </a>
                </div>
                <!-- Actions -->
                <div class="hidden md:flex items-center gap-4">
                    <a href="{{ route('welcome') }}" class="text-sm font-medium text-slate-600 hover:text-[#1A56DB]">Kembali ke Beranda</a>
                    <a href="{{ route('daftar.step1') }}" class="bg-[#1A56DB] hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ activeTab: 'detail' }">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Left Column: Tabs & Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tabs -->
                <div class="flex gap-2 sm:gap-4 overflow-x-auto pb-2 scrollbar-hide">
                    <button @click="activeTab = 'detail'" :class="activeTab === 'detail' ? 'bg-[#EBF5FF] text-[#1A56DB] border-[#BFDBFE] font-bold shadow-sm' : 'bg-white text-slate-600 border-slate-200 font-medium hover:bg-slate-50'" class="px-6 py-2.5 border rounded-full text-sm whitespace-nowrap transition-all duration-200">
                        Detail Paket
                    </button>
                    <button @click="activeTab = 'fasilitas'" :class="activeTab === 'fasilitas' ? 'bg-[#EBF5FF] text-[#1A56DB] border-[#BFDBFE] font-bold shadow-sm' : 'bg-white text-slate-600 border-slate-200 font-medium hover:bg-slate-50'" class="px-6 py-2.5 border rounded-full text-sm whitespace-nowrap transition-all duration-200">
                        Fasilitas
                    </button>
                    <button @click="activeTab = 'ulasan'" :class="activeTab === 'ulasan' ? 'bg-[#EBF5FF] text-[#1A56DB] border-[#BFDBFE] font-bold shadow-sm' : 'bg-white text-slate-600 border-slate-200 font-medium hover:bg-slate-50'" class="px-6 py-2.5 border rounded-full text-sm whitespace-nowrap transition-all duration-200">
                        Ulasan
                    </button>
                </div>

                <!-- Tab Content: Detail -->
                <div x-show="activeTab === 'detail'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <!-- Card 1: Yang akan kamu dapatkan -->
                    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-slate-200">
                        <div class="flex justify-between items-start mb-6">
                            <h2 class="text-xl font-bold text-slate-900">Yang akan kamu dapatkan</h2>
                            @if($paket->target_peserta)
                                <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-3 py-1.5 rounded-lg border border-indigo-100">{{ $paket->target_peserta }}</span>
                            @endif
                        </div>
                        
                        @if($paket->benefits)
                            <ul class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @php $benefitList = explode("\n", str_replace("\r", "", $paket->benefits)); @endphp
                                @foreach($benefitList as $benefit)
                                    @if(trim($benefit))
                                        <li class="flex items-start gap-3">
                                            <div class="mt-0.5 bg-[#22C55E] rounded-full p-0.5 flex-shrink-0">
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                            <span class="text-slate-700 leading-relaxed font-medium break-words overflow-hidden">{{ trim($benefit) }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <p class="text-slate-500 italic">Belum ada data benefit untuk paket ini.</p>
                        @endif
                    </div>

                    <!-- Card 2: Deskripsi Program -->
                    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-slate-200">
                        <h2 class="text-xl font-bold text-slate-900 mb-6">Tentang Program Ini</h2>
                        <p class="text-slate-600 leading-relaxed whitespace-pre-line">
                            {{ $paket->deskripsi ?? 'Program bimbingan komprehensif yang dirancang untuk membantu siswa mencapai target akademik secara optimal.' }}
                        </p>
                    </div>
                </div>

                <!-- Tab Content: Fasilitas -->
                <div x-show="activeTab === 'fasilitas'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-slate-200">
                        <h2 class="text-xl font-bold text-slate-900 mb-6">Fasilitas Belajar</h2>
                        @if($paket->fasilitas)
                            <p class="text-slate-600 leading-relaxed mb-6">{{ $paket->fasilitas }}</p>
                        @endif
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900">Ruang Kelas AC</h4>
                                    <p class="text-xs text-slate-500">Kenyamanan maksimal selama belajar</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                                <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900">Free WiFi</h4>
                                    <p class="text-xs text-slate-500">Akses internet cepat 24 jam</p>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <!-- Tab Content: Ulasan -->
                <div x-show="activeTab === 'ulasan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-slate-200">
                        <h2 class="text-xl font-bold text-slate-900 mb-8">Apa Kata Mereka?</h2>
                        
                        <div class="space-y-6">
                            @forelse($testimonials ?? [] as $testi)
                                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-12 h-12 rounded-full bg-slate-200 overflow-hidden flex-shrink-0">
                                            @if($testi->foto)
                                                <img src="{{ asset('storage/' . $testi->foto) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-400">👤</div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-900">{{ $testi->nama }}</h4>
                                            <p class="text-xs text-slate-500">{{ $testi->posisi }}</p>
                                        </div>
                                        <div class="ml-auto flex gap-0.5">
                                            @for($i=0; $i<$testi->bintang; $i++)
                                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-slate-600 italic leading-relaxed">"{{ $testi->ulasan }}"</p>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <p class="text-slate-500 italic">Belum ada ulasan untuk paket ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Sticky Checkout Card -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-slate-100 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-6">Beli paket {{ $paket->nama_paket }}</h3>
                        
                        <div class="mb-6">
                            <span class="text-slate-500 text-sm block mb-1">Mulai dari</span>
                            <div class="flex items-end gap-1.5 flex-wrap">
                                @if($paket->harga_coret)
                                    <span class="text-sm text-slate-400 line-through w-full mb-1">Rp {{ number_format($paket->harga_coret, 0, ',', '.') }}</span>
                                @endif
                                <span class="text-2xl sm:text-3xl font-extrabold text-[#EF4444] tracking-tight">Rp {{ number_format($paket->nominal, 0, ',', '.') }}</span>
                                @if($paket->durasi_jumlah)
                                    <span class="text-sm font-medium text-slate-500 mb-1">/ {{ $paket->durasi_jumlah }} {{ $paket->durasi_satuan }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-3">
                            <a href="{{ route('daftar.step1') }}" class="block w-full text-center bg-[#F97316] hover:bg-[#EA580C] text-white font-bold py-3.5 px-4 rounded-xl transition-colors shadow-sm">
                                Daftar Sekarang
                            </a>
                            @if($masterData && $masterData->wa_number)
                                <a href="https://wa.me/{{ $masterData->wa_number }}?text={{ urlencode('Halo, saya tertarik dengan paket ' . $paket->nama_paket . '. Bisa minta info lebih lanjut?') }}" target="_blank" class="block w-full text-center bg-white border border-slate-200 text-[#0E7490] hover:bg-slate-50 font-bold py-3 px-4 rounded-xl transition-colors flex items-center justify-center gap-2">
                                    Tanya CS
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer Simple -->
    <footer class="bg-white border-t border-slate-200 mt-auto">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-sm text-slate-500">
            &copy; {{ date('Y') }} {{ $masterData->nama_lembaga ?? 'Genius Education' }}. Sistem Informasi Manajemen Pendidikan.
        </div>
    </footer>

    @include('components.loading-overlay')
</body>
</html>
