<!-- University & Study Program Explorer (Direktori PTN & Prodi Terlengkap se-Indonesia) -->
<section id="ekosistem" class="py-20 sm:py-28 bg-[#FAF8F5] border-b border-[#E7E2D9] relative w-full"
         x-data="{
             searchQuery: '',
             currentFilter: 'all',
             currentPage: {{ $initialUniversities['current_page'] ?? 1 }},
             lastPage: {{ $initialUniversities['last_page'] ?? 1 }},
             totalCount: {{ $initialUniversities['total'] ?? 0 }},
             universities: {{ Js::from($initialUniversities['data'] ?? []) }},
             loading: false,

             // Modal Detail Program Studi
             modalOpen: false,
             selectedUniv: null,
             modalLogoFailed: false,
             prodiList: [],
             prodiLoading: false,
             prodiCategory: 'all',
             prodiSearch: '',

             fetchUniversities(page = 1) {
                 this.loading = true;
                 this.currentPage = page;
                 const url = `/eksplorasi-kampus/data?q=${encodeURIComponent(this.searchQuery)}&filter=${this.currentFilter}&page=${page}&per_page=12`;
                 fetch(url)
                     .then(res => res.json())
                     .then(data => {
                         this.universities = data.data || [];
                         this.lastPage = data.last_page || 1;
                         this.totalCount = data.total || 0;
                     })
                     .catch(err => console.error(err))
                     .finally(() => this.loading = false);
             },

             setFilter(filter) {
                 this.currentFilter = filter;
                 this.fetchUniversities(1);
             },

             onSearch() {
                 this.fetchUniversities(1);
             },

             openProdiModal(univ) {
                 this.selectedUniv = univ;
                 this.modalLogoFailed = false;
                 this.modalOpen = true;
                 this.prodiCategory = 'all';
                 this.prodiSearch = '';
                 this.fetchProdi(univ.code || univ.id);
                 document.body.style.overflow = 'hidden';
             },

             closeProdiModal() {
                 this.modalOpen = false;
                 this.selectedUniv = null;
                 this.modalLogoFailed = false;
                 this.prodiList = [];
                 document.body.style.overflow = '';
             },

             fetchProdi(code) {
                 this.prodiLoading = true;
                 fetch(`/eksplorasi-kampus/${encodeURIComponent(code)}/prodi?category=${this.prodiCategory}`)
                     .then(res => res.json())
                     .then(data => {
                         this.prodiList = data.prodi || [];
                     })
                     .catch(err => console.error(err))
                     .finally(() => this.prodiLoading = false);
             },

             filterProdiCategory(cat) {
                 this.prodiCategory = cat;
                 if (this.selectedUniv) {
                     this.fetchProdi(this.selectedUniv.code || this.selectedUniv.id);
                 }
             },

             get filteredProdi() {
                 if (!this.prodiSearch) return this.prodiList;
                 const q = this.prodiSearch.toLowerCase();
                 return this.prodiList.filter(p => (p.name || '').toLowerCase().includes(q) || (p.degree || '').toLowerCase().includes(q));
             }
         }">
    <div class="w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
        
        <!-- Section Header: Academic Editorial (No generic pills) -->
        <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-14" data-aos="fade-up">
            <div class="text-xs font-mono uppercase tracking-[0.2em] text-[#78716C] mb-3 font-semibold">
                [ PUSAT DATA SELEKSI NASIONAL PTN & SNBT 2026 ]
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#141413] tracking-tight leading-tight mb-4">
                Eksplorasi Kampus Impian & Program Studi
            </h2>
            <p class="text-[#57534E] text-base sm:text-lg leading-relaxed font-normal">
                Petakan pilihan universitas negeri terbaik di seluruh Indonesia. Cek daya tampung resmi, jumlah peminat, dan rasio persaingan jurusan untuk menyusun strategi lolos impian akademik Anda.
            </p>
        </div>

        <!-- Search & Filter Controls -->
        <div class="max-w-4xl mx-auto mb-10 space-y-4" data-aos="fade-up" data-aos-delay="100">
            <!-- Search Bar -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#A8A29E]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text"
                       x-model="searchQuery"
                       @input.debounce.350ms="onSearch()"
                       placeholder="Cari universitas (misal: UI, UGM, ITB, Brawijaya) atau kota asal..."
                       class="w-full pl-11 pr-12 py-3.5 bg-white border border-[#E7E2D9] rounded-xl text-[#141413] placeholder-[#A8A29E] text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#E14D2A]/30 focus:border-[#E14D2A] transition-all shadow-xs">
                
                <button type="button" 
                        x-show="searchQuery.length > 0" 
                        @click="searchQuery = ''; onSearch()"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-xs font-mono text-[#78716C] hover:text-[#141413]">
                    Reset
                </button>
            </div>

            <!-- Filter Chips -->
            <div class="flex flex-wrap items-center justify-center gap-2 pt-1">
                <button type="button" 
                        @click="setFilter('all')"
                        :class="currentFilter === 'all' ? 'bg-[#141413] text-white border-[#141413]' : 'bg-white text-[#57534E] border-[#E7E2D9] hover:bg-[#F4EFEA]'"
                        class="px-4 py-2 rounded-lg border text-xs sm:text-sm font-semibold transition-all shadow-2xs cursor-pointer">
                    Semua Kampus
                </button>
                <button type="button" 
                        @click="setFilter('ptn')"
                        :class="currentFilter === 'ptn' ? 'bg-[#141413] text-white border-[#141413]' : 'bg-white text-[#57534E] border-[#E7E2D9] hover:bg-[#F4EFEA]'"
                        class="px-4 py-2 rounded-lg border text-xs sm:text-sm font-semibold transition-all shadow-2xs cursor-pointer">
                    Universitas & Institut
                </button>
                <button type="button" 
                        @click="setFilter('poltek')"
                        :class="currentFilter === 'poltek' ? 'bg-[#141413] text-white border-[#141413]' : 'bg-white text-[#57534E] border-[#E7E2D9] hover:bg-[#F4EFEA]'"
                        class="px-4 py-2 rounded-lg border text-xs sm:text-sm font-semibold transition-all shadow-2xs cursor-pointer">
                    Politeknik Negeri
                </button>
                <button type="button" 
                        @click="setFilter('jawa')"
                        :class="currentFilter === 'jawa' ? 'bg-[#141413] text-white border-[#141413]' : 'bg-white text-[#57534E] border-[#E7E2D9] hover:bg-[#F4EFEA]'"
                        class="px-4 py-2 rounded-lg border text-xs sm:text-sm font-semibold transition-all shadow-2xs cursor-pointer">
                    Pulau Jawa
                </button>
                <button type="button" 
                        @click="setFilter('luar_jawa')"
                        :class="currentFilter === 'luar_jawa' ? 'bg-[#141413] text-white border-[#141413]' : 'bg-white text-[#57534E] border-[#E7E2D9] hover:bg-[#F4EFEA]'"
                        class="px-4 py-2 rounded-lg border text-xs sm:text-sm font-semibold transition-all shadow-2xs cursor-pointer">
                    Luar Pulau Jawa
                </button>
            </div>
        </div>

        <!-- Meta Summary -->
        <div class="flex items-center justify-between w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto mb-6 text-xs text-[#78716C] font-mono border-b border-[#E7E2D9] pb-3" data-aos="fade-up">
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-[#10B981]"></span>
                <span>Terdata: <strong class="text-[#141413]" x-text="totalCount">143</strong> Perguruan Tinggi Negeri Terverifikasi</span>
            </span>
            <span class="hidden sm:inline">Pangkalan Data Seleksi PTN & SNBT 2026</span>
        </div>

        <!-- Loading State Indicator -->
        <div x-show="loading" class="py-12 text-center text-[#78716C]" x-cloak>
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-3 border-stone-300 border-t-[#E14D2A]"></div>
            <p class="mt-3 text-sm font-medium">Memuat data perguruan tinggi...</p>
        </div>

        <!-- University Cards Grid (4 Columns per Row on Desktop) -->
        <div x-show="!loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-4 gap-6 w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto">
            <template x-for="univ in universities" :key="univ.code || univ.name">
                <div class="bg-white border border-[#E7E2D9] hover:border-stone-400 rounded-2xl p-5 sm:p-6 transition-all duration-200 hover:shadow-md flex flex-col justify-between group">
                    <div>
                        <!-- Header with University Logo -->
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <!-- Official Logo Frame -->
                            <div x-data="{ imgFailed: false }" class="w-14 h-14 rounded-xl bg-white border border-[#E7E2D9] p-2 flex items-center justify-center overflow-hidden flex-shrink-0 shadow-2xs group-hover:border-[#E14D2A]/60 transition-colors">
                                <img x-show="!imgFailed"
                                     :src="univ.logo_url" 
                                     :alt="'Logo ' + univ.name" 
                                     class="w-full h-full object-contain"
                                     loading="lazy"
                                     x-on:error="imgFailed = true">
                                <!-- Fallback if logo cannot load -->
                                <div x-show="imgFailed" 
                                     class="w-full h-full rounded-lg bg-[#FAF8F5] flex items-center justify-center text-center font-mono font-black text-xs text-[#141413] px-1 overflow-hidden">
                                    <span class="truncate" x-text="univ.code || univ.name.substring(0, 3).toUpperCase()"></span>
                                </div>
                            </div>

                            <!-- Campus Meta Tags -->
                            <div class="flex flex-col items-end gap-1">
                                <span class="inline-block text-[10px] font-mono uppercase font-bold px-2 py-0.5 rounded bg-[#FAF8F5] border border-[#E7E2D9] text-[#78716C]"
                                      x-text="univ.region || 'Indonesia'">
                                </span>
                                <span class="text-xs font-mono font-bold text-[#E14D2A]"
                                      x-text="univ.code">
                                </span>
                            </div>
                        </div>

                        <!-- University Name -->
                        <h3 class="font-extrabold text-lg sm:text-xl text-[#141413] leading-snug tracking-tight mb-2 group-hover:text-[#E14D2A] transition-colors line-clamp-2"
                            x-text="univ.name">
                        </h3>

                        <!-- Location -->
                        <p class="text-xs text-[#78716C] flex items-center gap-1.5 mb-6 font-medium">
                            <svg class="w-3.5 h-3.5 text-[#A8A29E] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span x-text="univ.city || 'Kampus Utama'"></span>
                        </p>

                        <!-- Key Metrics Row -->
                        <div class="grid grid-cols-3 gap-2 p-3 bg-[#FAF8F5] border border-[#E7E2D9] rounded-xl text-center mb-6">
                            <div>
                                <span class="text-[10px] font-mono uppercase text-[#78716C] block">Prodi</span>
                                <strong class="text-sm font-bold text-[#141413]" x-text="univ.total_majors || '-'"></strong>
                            </div>
                            <div class="border-x border-[#E7E2D9]">
                                <span class="text-[10px] font-mono uppercase text-[#78716C] block">Kuota</span>
                                <strong class="text-sm font-bold text-[#141413]" x-text="(univ.total_quota || 0).toLocaleString('id-ID')"></strong>
                            </div>
                            <div>
                                <span class="text-[10px] font-mono uppercase text-[#78716C] block">Peminat</span>
                                <strong class="text-sm font-bold text-[#E14D2A]" x-text="(univ.total_applicants || 0).toLocaleString('id-ID')"></strong>
                            </div>
                        </div>
                    </div>

                    <!-- Action Trigger -->
                    <button type="button" 
                            @click="openProdiModal(univ)"
                            class="w-full py-2.5 px-4 bg-white hover:bg-[#F4EFEA] border border-[#E7E2D9] hover:border-stone-400 text-[#141413] font-semibold text-xs sm:text-sm rounded-xl transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <span>Lihat Jurusan & Keketatan</span>
                        <svg class="w-4 h-4 text-[#E14D2A]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && universities.length === 0" class="py-16 text-center max-w-md mx-auto" x-cloak>
            <div class="w-12 h-12 mx-auto rounded-full bg-[#FAF8F5] border border-[#E7E2D9] flex items-center justify-center text-[#78716C] mb-3">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h4 class="text-base font-bold text-[#141413]">Perguruan Tinggi Tidak Ditemukan</h4>
            <p class="text-xs text-[#57534E] mt-1">Coba gunakan kata kunci lain seperti singkatan kampus (contoh: "UI", "UGM", "ITB") atau nama kota.</p>
        </div>

        <!-- Pagination Controls -->
        <div x-show="lastPage > 1" class="flex items-center justify-center gap-2 mt-12" data-aos="fade-up">
            <button type="button" 
                    @click="fetchUniversities(currentPage - 1)"
                    :disabled="currentPage <= 1"
                    class="px-4 py-2 text-xs font-semibold rounded-lg border border-[#E7E2D9] bg-white text-[#141413] disabled:opacity-40 disabled:cursor-not-allowed hover:bg-[#F4EFEA] transition-colors cursor-pointer">
                ← Sebelumnya
            </button>
            <span class="text-xs font-mono text-[#78716C] px-3">
                Halaman <strong class="text-[#141413]" x-text="currentPage"></strong> dari <span x-text="lastPage"></span>
            </span>
            <button type="button" 
                    @click="fetchUniversities(currentPage + 1)"
                    :disabled="currentPage >= lastPage"
                    class="px-4 py-2 text-xs font-semibold rounded-lg border border-[#E7E2D9] bg-white text-[#141413] disabled:opacity-40 disabled:cursor-not-allowed hover:bg-[#F4EFEA] transition-colors cursor-pointer">
                Selanjutnya →
            </button>
        </div>

        <!-- MODAL DETAIL PROGRAM STUDI (INTERAKTIF) -->
        <div x-show="modalOpen" 
             class="fixed inset-0 z-50 overflow-y-auto"
             x-cloak
             @keydown.escape.window="closeProdiModal()">
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" 
                 @click="closeProdiModal()"></div>

            <!-- Modal Container -->
            <div class="flex min-h-screen items-center justify-center p-3 sm:p-6 text-center">
                <div class="relative w-full max-w-4xl bg-white rounded-2xl text-left shadow-2xl border border-[#E7E2D9] overflow-hidden transition-all my-8"
                     @click.stop>
                    
                    <!-- Modal Header with University Logo -->
                    <div class="p-6 sm:p-8 bg-[#FAF8F5] border-b border-[#E7E2D9] relative">
                        <button type="button" 
                                @click="closeProdiModal()"
                                class="absolute top-6 right-6 w-9 h-9 rounded-full bg-white border border-[#E7E2D9] hover:bg-[#F4EFEA] text-stone-600 hover:text-black flex items-center justify-center transition-colors focus:outline-none cursor-pointer"
                                aria-label="Tutup Modal">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div class="flex items-start sm:items-center gap-4">
                            <!-- Modal University Logo -->
                            <div class="w-16 h-16 rounded-xl bg-white border border-[#E7E2D9] p-2 flex items-center justify-center overflow-hidden flex-shrink-0 shadow-xs">
                                <template x-if="selectedUniv && !modalLogoFailed">
                                    <img :src="selectedUniv.logo_url"
                                         :alt="'Logo ' + selectedUniv.name"
                                         class="w-full h-full object-contain"
                                         x-on:error="modalLogoFailed = true">
                                </template>
                                <div x-show="modalLogoFailed || !selectedUniv"
                                     class="w-full h-full rounded-lg bg-[#FAF8F5] flex items-center justify-center text-center font-mono font-black text-sm text-[#141413]">
                                    <span x-text="selectedUniv ? (selectedUniv.code || selectedUniv.name.substring(0, 3).toUpperCase()) : ''"></span>
                                </div>
                            </div>

                            <div class="pr-8">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-mono uppercase tracking-wider text-[#E14D2A] font-bold" x-text="selectedUniv ? selectedUniv.code : ''"></span>
                                    <span class="text-xs text-[#A8A29E]">•</span>
                                    <span class="text-xs font-mono text-[#78716C]" x-text="selectedUniv ? (selectedUniv.city + ', ' + selectedUniv.region) : ''"></span>
                                </div>

                                <h3 class="text-xl sm:text-2xl font-extrabold text-[#141413] tracking-tight leading-snug"
                                    x-text="selectedUniv ? selectedUniv.name : 'Daftar Program Studi'">
                                </h3>
                                <p class="text-xs text-[#57534E] mt-1 font-normal">
                                    Data resmi kuota daya tampung, jumlah peminat, dan rasio keketatan persaingan seleksi masuk perguruan tinggi.
                                </p>
                            </div>
                        </div>

                        <!-- Modal Filter & Search Sub-bar -->
                        <div class="mt-6 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                            <!-- Category Filter Switcher -->
                            <div class="flex items-center gap-1.5 p-1 bg-white border border-[#E7E2D9] rounded-lg self-start sm:self-auto">
                                <button type="button" 
                                        @click="filterProdiCategory('all')"
                                        :class="prodiCategory === 'all' ? 'bg-[#141413] text-white' : 'text-[#57534E] hover:text-[#141413]'"
                                        class="px-3 py-1.5 text-xs font-semibold rounded-md transition-colors cursor-pointer">
                                    Semua
                                </button>
                                <button type="button" 
                                        @click="filterProdiCategory('SAINTEK')"
                                        :class="prodiCategory === 'SAINTEK' ? 'bg-[#059669] text-white' : 'text-[#57534E] hover:text-[#141413]'"
                                        class="px-3 py-1.5 text-xs font-semibold rounded-md transition-colors cursor-pointer">
                                    Saintek
                                </button>
                                <button type="button" 
                                        @click="filterProdiCategory('SOSHUM')"
                                        :class="prodiCategory === 'SOSHUM' ? 'bg-[#E14D2A] text-white' : 'text-[#57534E] hover:text-[#141413]'"
                                        class="px-3 py-1.5 text-xs font-semibold rounded-md transition-colors cursor-pointer">
                                    Soshum
                                </button>
                            </div>

                            <!-- Filter by Program Name inside modal -->
                            <div class="relative flex-1 max-w-xs">
                                <input type="text"
                                       x-model="prodiSearch"
                                       placeholder="Saring nama jurusan..."
                                       class="w-full pl-8 pr-3 py-1.5 bg-white border border-[#E7E2D9] rounded-lg text-xs text-[#141413] focus:outline-none focus:border-[#E14D2A]">
                                <svg class="w-3.5 h-3.5 text-[#A8A29E] absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Body: Table / List of Prodi -->
                    <div class="p-4 sm:p-6 max-h-[60vh] overflow-y-auto">
                        <!-- Loading State inside Modal -->
                        <div x-show="prodiLoading" class="py-12 text-center text-[#78716C]">
                            <div class="inline-block animate-spin rounded-full h-6 w-6 border-2 border-stone-300 border-t-[#E14D2A]"></div>
                            <p class="mt-2 text-xs">Memuat daftar program studi...</p>
                        </div>

                        <!-- Table View -->
                        <div x-show="!prodiLoading && filteredProdi.length > 0" class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                <thead>
                                    <tr class="border-b border-[#E7E2D9] text-[#78716C] font-mono text-[11px] uppercase tracking-wider">
                                        <th class="py-3 px-3">Program Studi</th>
                                        <th class="py-3 px-3 text-center">Jenjang</th>
                                        <th class="py-3 px-3 text-center">Rumpun</th>
                                        <th class="py-3 px-3 text-right">Daya Tampung</th>
                                        <th class="py-3 px-3 text-right">Peminat</th>
                                        <th class="py-3 px-3 text-right">Keketatan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-100">
                                    <template x-for="(item, idx) in filteredProdi" :key="item.id || idx">
                                        <tr class="hover:bg-[#FAF8F5] transition-colors">
                                            <td class="py-3 px-3 font-semibold text-[#141413]">
                                                <div x-text="item.name"></div>
                                                <div class="text-[10px] text-[#78716C] font-mono" x-text="item.classification || ''"></div>
                                            </td>
                                            <td class="py-3 px-3 text-center">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-stone-100 border border-stone-200 text-stone-700"
                                                      x-text="item.degree === 'Sarjana' ? 'S1' : (item.degree === 'Sarjana Terapan' ? 'D4' : 'D3')">
                                                </span>
                                            </td>
                                            <td class="py-3 px-3 text-center">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-semibold"
                                                      :class="item.category === 'SAINTEK' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-orange-50 text-orange-700 border border-orange-200'"
                                                      x-text="item.category">
                                                </span>
                                            </td>
                                            <td class="py-3 px-3 text-right font-mono font-semibold text-[#141413]" x-text="item.quota"></td>
                                            <td class="py-3 px-3 text-right font-mono text-[#57534E]" x-text="(item.applicants || 0).toLocaleString('id-ID')"></td>
                                            <td class="py-3 px-3 text-right font-mono font-bold"
                                                :class="item.competition_ratio > 20 ? 'text-[#E14D2A]' : 'text-[#059669]'">
                                                <span x-text="'1 : ' + Math.round(item.competition_ratio)"></span>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Empty search results inside modal -->
                        <div x-show="!prodiLoading && filteredProdi.length === 0" class="py-12 text-center text-xs text-[#78716C]">
                            Tidak ada program studi yang cocok dengan filter atau kata kunci.
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="p-4 bg-[#FAF8F5] border-t border-[#E7E2D9] flex flex-col sm:flex-row items-center justify-between text-xs text-[#78716C] gap-3">
                        <span>Menampilkan <strong class="text-[#141413]" x-text="filteredProdi.length"></strong> Program Studi. Data terintegrasi SNBT 2026.</span>
                        <button type="button" 
                                @click="closeProdiModal()"
                                class="px-4 py-2 rounded-lg bg-white border border-[#E7E2D9] hover:bg-[#F4EFEA] text-[#141413] font-semibold transition-colors cursor-pointer">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>
