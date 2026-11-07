<!-- University & Study Program Explorer (Direktori PTN & Prodi Terlengkap se-Indonesia) -->
<section id="ekosistem" class="py-12 sm:py-24 bg-[#FAF8F5] border-b border-[#E7E2D9] relative w-full"
         x-data="{
             searchQuery: '',
             currentFilter: 'all',
             currentPage: {{ $initialUniversities['current_page'] ?? 1 }},
             lastPage: {{ $initialUniversities['last_page'] ?? 1 }},
             totalCount: {{ $initialUniversities['total'] ?? 0 }},
             universities: {{ Js::from($initialUniversities['data'] ?? []) }},
             loading: false,
             isMobile: window.innerWidth < 640,

             get displayedUniversities() {
                 return this.isMobile ? this.universities.slice(0, 5) : this.universities;
             },

             init() {
                 this.isMobile = window.innerWidth < 640;
                 this.lastPage = Math.ceil(this.totalCount / (this.isMobile ? 5 : 12)) || 1;
                 let resizeTimer = null;
                 window.addEventListener('resize', () => {
                     const wasMobile = this.isMobile;
                     this.isMobile = window.innerWidth < 640;
                     if (wasMobile !== this.isMobile) {
                         this.lastPage = Math.ceil(this.totalCount / (this.isMobile ? 5 : 12)) || 1;
                         clearTimeout(resizeTimer);
                         resizeTimer = setTimeout(() => {
                             this.fetchUniversities(1);
                         }, 150);
                     }
                 });
             },
             // Modal Detail Program Studi
             modalOpen: false,
             selectedUniv: null,
             modalLogoFailed: false,
             prodiList: [],
             prodiLoading: false,
             prodiCategory: 'all',
             prodiSearch: '',
             prodiPage: 1,
             prodiPerPage: 10,
             fetchUniversities(page = 1, autoScroll = false) {
                 this.loading = true;
                 this.currentPage = page;
                 const perPage = this.isMobile ? 5 : 12;
                 const url = `/eksplorasi-kampus/data?q=${encodeURIComponent(this.searchQuery)}&filter=${this.currentFilter}&page=${page}&per_page=${perPage}`;
                 fetch(url)
                     .then(res => res.json())
                     .then(data => {
                         this.universities = data.data || [];
                         this.lastPage = data.last_page || 1;
                         this.totalCount = data.total || 0;
                         if (autoScroll) {
                             this.scrollToResults();
                         }
                     })
                     .catch(err => console.error(err))
                     .finally(() => this.loading = false);
             },

             setFilter(filter) {
                 this.currentFilter = filter;
                 this.fetchUniversities(1, true);
             },

             onSearch() {
                 this.fetchUniversities(1, true);
             },

             scrollToResults() {
                 const el = document.getElementById('daftar-kampus-container');
                 if (el) {
                     const offset = window.innerWidth < 640 ? 120 : 80;
                     const y = el.getBoundingClientRect().top + window.pageYOffset - offset;
                     window.scrollTo({ top: y, behavior: 'smooth' });
                 }
             },

             openProdiModal(univ) {
                 this.selectedUniv = univ;
                 this.modalLogoFailed = false;
                 this.modalOpen = true;
                 this.prodiCategory = 'all';
                 this.prodiSearch = '';
                 this.prodiPage = 1;
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
                 this.prodiPage = 1;
                 if (this.selectedUniv) {
                     this.fetchProdi(this.selectedUniv.code || this.selectedUniv.id);
                 }
             },

             onProdiSearch() {
                 this.prodiPage = 1;
                 this.scrollProdiToTop();
             },

             changeProdiPage(page) {
                 this.prodiPage = page;
                 this.scrollProdiToTop();
             },

             scrollProdiToTop() {
                 this.$nextTick(() => {
                     const body = document.getElementById('modal-prodi-body');
                     if (body) {
                         body.scrollTo({ top: 0, behavior: 'smooth' });
                     }
                     const modalRoot = document.getElementById('modal-prodi-root');
                     if (modalRoot) {
                         modalRoot.scrollTo({ top: 0, behavior: 'smooth' });
                     }
                     const modalBox = document.getElementById('modal-prodi-container');
                     if (modalBox) {
                         modalBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
                     }
                 });
             },

             get allFilteredProdi() {
                 if (!this.prodiSearch) return this.prodiList;
                 const q = this.prodiSearch.toLowerCase();
                 return this.prodiList.filter(p => (p.name || '').toLowerCase().includes(q) || (p.degree || '').toLowerCase().includes(q));
             },

             get prodiLastPage() {
                 return Math.ceil(this.allFilteredProdi.length / this.prodiPerPage) || 1;
             },

             get paginatedProdi() {
                 const start = (this.prodiPage - 1) * this.prodiPerPage;
                 return this.allFilteredProdi.slice(start, start + this.prodiPerPage);
             }
         }">
    <div class="w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
        
        <!-- Section Header: Academic Editorial -->
        <div class="text-center max-w-3xl mx-auto mb-6 sm:mb-14" data-aos="fade-up">
            <div class="text-[10px] sm:text-xs font-mono uppercase tracking-[0.2em] text-[#78716C] mb-2 sm:mb-3 font-semibold">
                [ PUSAT DATA SELEKSI NASIONAL PTN & SNBT 2026 ]
            </div>
            <h2 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-[#141413] tracking-tight leading-tight mb-2 sm:mb-4">
                Eksplorasi Kampus Impian & Program Studi
            </h2>
            <p class="hidden sm:block text-[#57534E] text-base sm:text-lg leading-relaxed font-normal">
                Petakan pilihan universitas negeri terbaik di seluruh Indonesia. Cek daya tampung resmi, jumlah peminat, dan rasio persaingan jurusan untuk menyusun strategi lolos impian akademik Anda.
            </p>
        </div>

        <!-- Search & Filter Controls (Sticky on Mobile) -->
        <div class="sticky top-14 sm:top-20 z-30 -mx-4 px-4 sm:mx-auto sm:px-0 py-2.5 sm:py-0 bg-[#FAF8F5]/95 backdrop-blur-md sm:bg-transparent sm:backdrop-blur-none sm:static max-w-4xl mx-auto mb-6 sm:mb-10 space-y-3 sm:space-y-4 transition-all" data-aos="fade-up" data-aos-delay="100">
            <!-- Search Bar -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 sm:pl-4 flex items-center pointer-events-none text-[#A8A29E]">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text"
                       x-model="searchQuery"
                       @input.debounce.350ms="onSearch()"
                       placeholder="Cari universitas (contoh: UI, ITB, UGM) atau kota..."
                       class="w-full pl-10 sm:pl-11 pr-16 sm:pr-12 py-2.5 sm:py-3.5 bg-white border border-[#E7E2D9] rounded-xl text-[#141413] placeholder-[#A8A29E] text-xs sm:text-base focus:outline-none focus:ring-2 focus:ring-[#E14D2A]/30 focus:border-[#E14D2A] transition-all shadow-xs">
                
                <button type="button" 
                        x-show="searchQuery.length > 0" 
                        @click="searchQuery = ''; onSearch()"
                        class="absolute inset-y-0 right-0 pr-3.5 sm:pr-4 flex items-center text-[11px] sm:text-xs font-mono font-semibold text-[#E14D2A] hover:text-[#141413]">
                    Reset
                </button>
            </div>

            <!-- Filter Chips (Horizontal swipe on mobile, wrap on desktop) -->
            <div class="flex items-center overflow-x-auto sm:flex-wrap sm:justify-center gap-1.5 sm:gap-2 pb-1 sm:pb-0 scrollbar-none snap-x -mx-1 px-1">
                <button type="button" 
                        @click="setFilter('all')"
                        :class="currentFilter === 'all' ? 'bg-[#141413] text-white border-[#141413]' : 'bg-white text-[#57534E] border-[#E7E2D9] hover:bg-[#F4EFEA]'"
                        class="whitespace-nowrap px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg border text-xs sm:text-sm font-semibold transition-all shadow-2xs shrink-0 snap-start cursor-pointer">
                    Semua Kampus
                </button>
                <button type="button" 
                        @click="setFilter('ptn')"
                        :class="currentFilter === 'ptn' ? 'bg-[#141413] text-white border-[#141413]' : 'bg-white text-[#57534E] border-[#E7E2D9] hover:bg-[#F4EFEA]'"
                        class="whitespace-nowrap px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg border text-xs sm:text-sm font-semibold transition-all shadow-2xs shrink-0 snap-start cursor-pointer">
                    Universitas & Institut
                </button>
                <button type="button" 
                        @click="setFilter('poltek')"
                        :class="currentFilter === 'poltek' ? 'bg-[#141413] text-white border-[#141413]' : 'bg-white text-[#57534E] border-[#E7E2D9] hover:bg-[#F4EFEA]'"
                        class="whitespace-nowrap px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg border text-xs sm:text-sm font-semibold transition-all shadow-2xs shrink-0 snap-start cursor-pointer">
                    Politeknik Negeri
                </button>
                <button type="button" 
                        @click="setFilter('jawa')"
                        :class="currentFilter === 'jawa' ? 'bg-[#141413] text-white border-[#141413]' : 'bg-white text-[#57534E] border-[#E7E2D9] hover:bg-[#F4EFEA]'"
                        class="whitespace-nowrap px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg border text-xs sm:text-sm font-semibold transition-all shadow-2xs shrink-0 snap-start cursor-pointer">
                    Pulau Jawa
                </button>
                <button type="button" 
                        @click="setFilter('luar_jawa')"
                        :class="currentFilter === 'luar_jawa' ? 'bg-[#141413] text-white border-[#141413]' : 'bg-white text-[#57534E] border-[#E7E2D9] hover:bg-[#F4EFEA]'"
                        class="whitespace-nowrap px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg border text-xs sm:text-sm font-semibold transition-all shadow-2xs shrink-0 snap-start cursor-pointer">
                    Luar Pulau Jawa
                </button>
            </div>
        </div>

        <!-- Meta Summary -->
        <div id="daftar-kampus-container" class="flex items-center justify-between w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto mb-4 sm:mb-6 text-xs text-[#78716C] font-mono border-b border-[#E7E2D9] pb-3" data-aos="fade-up">
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-[#10B981]"></span>
                <span><strong class="text-[#141413]" x-text="totalCount">143</strong> PTN Terdata</span>
            </span>

            <div class="flex items-center gap-2">
                <span class="text-[11px] sm:text-xs text-[#78716C]">Pangkalan Data SNBT 2026</span>
            </div>
        </div>

        <!-- Loading State Indicator -->
        <div x-show="loading" class="py-12 text-center text-[#78716C]" x-cloak>
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-3 border-stone-300 border-t-[#E14D2A]"></div>
            <p class="mt-3 text-xs sm:text-sm font-medium">Memuat data perguruan tinggi...</p>
        </div>

        <!-- 1. Tampilan Mobile Ringkas (Khusus Layar Mobile: Ringkas & Maksimal 5 Data) -->
        <div x-show="!loading && isMobile" class="space-y-2.5">
            <template x-for="univ in displayedUniversities" :key="'compact-' + (univ.code || univ.name)">
                <div @click="openProdiModal(univ)"
                     class="bg-white border border-[#E7E2D9] active:border-[#E14D2A] rounded-xl p-3 flex items-center justify-between gap-3 shadow-2xs transition-all cursor-pointer">
                    <div class="flex items-center gap-3 min-w-0">
                        <!-- Logo Kampus Mini -->
                        <div x-data="{ imgFailed: false }" class="w-11 h-11 rounded-lg bg-white border border-[#E7E2D9] p-1.5 flex items-center justify-center shrink-0">
                            <img x-show="!imgFailed"
                                 :src="univ.logo_url" 
                                 :alt="'Logo ' + univ.name" 
                                 class="w-full h-full object-contain"
                                 loading="lazy"
                                 x-on:error="imgFailed = true">
                            <div x-show="imgFailed" 
                                 class="w-full h-full rounded bg-[#FAF8F5] flex items-center justify-center text-[10px] font-mono font-bold text-[#141413]">
                                <span x-text="univ.code || univ.name.substring(0, 3).toUpperCase()"></span>
                            </div>
                        </div>

                        <!-- Detail Kampus Ringkas -->
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5 mb-0.5">
                                <span class="text-[10px] font-mono font-bold text-[#E14D2A]" x-text="univ.code"></span>
                                <span class="text-[10px] text-stone-300">•</span>
                                <span class="text-[10px] text-[#78716C] truncate" x-text="univ.city || 'Indonesia'"></span>
                            </div>
                            <h3 class="font-bold text-xs text-[#141413] leading-tight line-clamp-1" x-text="univ.name"></h3>
                            <div class="flex items-center gap-3 mt-1 text-[10px] font-mono text-[#57534E]">
                                <span><strong class="text-[#141413]" x-text="univ.total_majors || 0"></strong> Prodi</span>
                                <span><strong class="text-[#E14D2A]" x-text="(univ.total_quota || 0).toLocaleString('id-ID')"></strong> Kuota</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Indicator -->
                    <div class="shrink-0 flex items-center text-[#E14D2A] pl-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </template>
        </div>

        <!-- 2. Tampilan Kartu Grid (Khusus Layar Tablet & Desktop: 12 Data) -->
        <div x-show="!loading && !isMobile" 
             class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-4 gap-4 sm:gap-6 w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto">
            <template x-for="univ in displayedUniversities" :key="univ.code || univ.name">
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
        <div x-show="!loading && displayedUniversities.length === 0" class="py-16 text-center max-w-md mx-auto" x-cloak>
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
                    @click="fetchUniversities(currentPage - 1, true)"
                    :disabled="currentPage <= 1"
                    class="px-4 py-2 text-xs font-semibold rounded-lg border border-[#E7E2D9] bg-white text-[#141413] disabled:opacity-40 disabled:cursor-not-allowed hover:bg-[#F4EFEA] transition-colors cursor-pointer">
                ← Sebelumnya
            </button>
            <span class="text-xs font-mono text-[#78716C] px-3">
                Halaman <strong class="text-[#141413]" x-text="currentPage"></strong> dari <span x-text="lastPage"></span>
            </span>
            <button type="button" 
                    @click="fetchUniversities(currentPage + 1, true)"
                    :disabled="currentPage >= lastPage"
                    class="px-4 py-2 text-xs font-semibold rounded-lg border border-[#E7E2D9] bg-white text-[#141413] disabled:opacity-40 disabled:cursor-not-allowed hover:bg-[#F4EFEA] transition-colors cursor-pointer">
                Selanjutnya →
            </button>
        </div>

        <!-- MODAL DETAIL PROGRAM STUDI (INTERAKTIF) -->
        <div id="modal-prodi-root"
             x-show="modalOpen" 
             class="fixed inset-0 z-50 overflow-y-auto"
             x-cloak
             @keydown.escape.window="closeProdiModal()">
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" 
                 @click="closeProdiModal()"></div>

            <!-- Modal Container (Bottom-Sheet di Mobile, Center Popup Elegan di Desktop) -->
            <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-6 text-center">
                <div id="modal-prodi-container"
                     class="relative w-full max-w-5xl bg-white rounded-t-3xl sm:rounded-2xl text-left shadow-2xl border border-[#E7E2D9] overflow-hidden transition-all flex flex-col max-h-[92vh] sm:max-h-[88vh] sm:my-6"
                     @click.stop>
                    
                    <!-- Mobile Handle Bar -->
                    <div class="sm:hidden pt-3 pb-1 flex justify-center bg-white">
                        <div class="w-12 h-1.5 rounded-full bg-stone-300"></div>
                    </div>

                    <!-- Modal Header with Campus Brand -->
                    <div class="px-5 pt-4 pb-4 sm:px-8 sm:pt-6 sm:pb-5 bg-white border-b border-[#E7E2D9] relative shrink-0">
                        <button type="button" 
                                @click="closeProdiModal()"
                                class="absolute top-4 sm:top-6 right-4 sm:right-6 w-9 h-9 rounded-full bg-stone-100 hover:bg-stone-200 border border-[#E7E2D9] text-stone-600 hover:text-black flex items-center justify-center transition-colors focus:outline-none cursor-pointer"
                                aria-label="Tutup Modal">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div class="flex items-start sm:items-center gap-4 pr-10">
                            <!-- Modal University Logo -->
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white border border-[#E7E2D9] p-2 flex items-center justify-center overflow-hidden shrink-0 shadow-xs">
                                <template x-if="selectedUniv && !modalLogoFailed">
                                    <img :src="selectedUniv.logo_url"
                                         :alt="'Logo ' + selectedUniv.name"
                                         class="w-full h-full object-contain"
                                         x-on:error="modalLogoFailed = true">
                                </template>
                                <div x-show="modalLogoFailed || !selectedUniv"
                                     class="w-full h-full rounded-xl bg-[#FAF8F5] flex items-center justify-center text-center font-mono font-black text-xs sm:text-sm text-[#141413]">
                                    <span x-text="selectedUniv ? (selectedUniv.code || selectedUniv.name.substring(0, 3).toUpperCase()) : ''"></span>
                                </div>
                            </div>

                            <div class="min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-mono font-bold bg-[#FDEEE9] text-[#E14D2A]"
                                          x-text="selectedUniv ? selectedUniv.code : ''"></span>
                                    <span class="text-xs text-[#A8A29E]">•</span>
                                    <span class="text-xs font-mono text-[#78716C] truncate"
                                          x-text="selectedUniv ? (selectedUniv.city + ', ' + selectedUniv.region) : ''"></span>
                                </div>

                                <h3 class="text-lg sm:text-2xl font-extrabold text-[#141413] tracking-tight leading-snug line-clamp-1 sm:line-clamp-none"
                                    x-text="selectedUniv ? selectedUniv.name : 'Daftar Program Studi'">
                                </h3>
                                <p class="hidden sm:block text-xs text-[#57534E] mt-1 font-normal">
                                    Data resmi seleksi masuk perguruan tinggi: kuota daya tampung, jumlah peminat, dan rasio keketatan persaingan.
                                </p>
                            </div>
                        </div>

                        <!-- Modal Filter & Search Sub-bar -->
                        <div class="mt-4 sm:mt-5 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-3 border-t border-stone-100">
                            <!-- Category Filter Switcher -->
                            <div class="inline-flex items-center gap-1 p-1 bg-[#FAF8F5] border border-[#E7E2D9] rounded-xl self-start sm:self-auto text-xs">
                                <button type="button" 
                                        @click="filterProdiCategory('all')"
                                        :class="prodiCategory === 'all' ? 'bg-[#141413] text-white shadow-2xs' : 'text-[#57534E] hover:text-[#141413]'"
                                        class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all cursor-pointer">
                                    Semua Rumpun
                                </button>
                                <button type="button" 
                                        @click="filterProdiCategory('SAINTEK')"
                                        :class="prodiCategory === 'SAINTEK' ? 'bg-[#059669] text-white shadow-2xs' : 'text-[#57534E] hover:text-[#141413]'"
                                        class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all cursor-pointer">
                                    Saintek
                                </button>
                                <button type="button" 
                                        @click="filterProdiCategory('SOSHUM')"
                                        :class="prodiCategory === 'SOSHUM' ? 'bg-[#E14D2A] text-white shadow-2xs' : 'text-[#57534E] hover:text-[#141413]'"
                                        class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all cursor-pointer">
                                    Soshum
                                </button>
                            </div>

                            <!-- Filter by Program Name inside modal -->
                            <div class="relative flex-1 sm:max-w-xs">
                                <input type="text"
                                       x-model="prodiSearch"
                                       @input="onProdiSearch()"
                                       placeholder="Cari jurusan atau jenjang..."
                                       class="w-full pl-9 pr-3.5 py-2 bg-[#FAF8F5] border border-[#E7E2D9] rounded-xl text-xs sm:text-sm text-[#141413] placeholder-[#A8A29E] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#E14D2A]/30 focus:border-[#E14D2A] transition-all">
                                <svg class="w-4 h-4 text-[#A8A29E] absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Body: Daftar Program Studi -->
                    <div id="modal-prodi-body" class="p-4 sm:p-7 overflow-y-auto grow bg-[#FAF8F5]">
                        <!-- Loading State inside Modal -->
                        <div x-show="prodiLoading" class="py-16 text-center text-[#78716C]">
                            <div class="inline-block animate-spin rounded-full h-7 w-7 border-3 border-stone-300 border-t-[#E14D2A]"></div>
                            <p class="mt-3 text-xs sm:text-sm font-medium">Memuat data jurusan & keketatan...</p>
                        </div>

                        <!-- 1. Tampilan Mobile: List Cards Modern & Rapi -->
                        <!-- 1. Tampilan Mobile: List Cards Modern & Rapi (Maks 10 Data per Halaman) -->
                        <div x-show="!prodiLoading && paginatedProdi.length > 0" class="sm:hidden space-y-2.5">
                            <template x-for="(item, idx) in paginatedProdi" :key="'m-prodi-' + (item.id || idx)">
                                <div class="p-3.5 bg-white border border-[#E7E2D9] rounded-xl shadow-2xs flex flex-col gap-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <div class="font-bold text-[#141413] text-sm leading-snug" x-text="item.name"></div>
                                            <div class="text-[10px] text-[#78716C] font-mono mt-0.5" x-text="item.classification || 'Program Studi Terakreditasi'"></div>
                                        </div>
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold shrink-0"
                                              :class="item.category === 'SAINTEK' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-orange-50 text-orange-700 border border-orange-200'"
                                              x-text="item.category">
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-4 gap-1.5 p-2 bg-[#FAF8F5] border border-[#E7E2D9] rounded-lg text-center font-mono">
                                        <div>
                                            <span class="text-[9px] uppercase text-[#78716C] block">Jenjang</span>
                                            <strong class="text-xs text-[#141413]" x-text="item.degree === 'Sarjana' ? 'S1' : (item.degree === 'Sarjana Terapan' ? 'D4' : (item.degree || 'D3'))"></strong>
                                        </div>
                                        <div class="border-l border-[#E7E2D9]">
                                            <span class="text-[9px] uppercase text-[#78716C] block">Kuota</span>
                                            <strong class="text-xs text-[#141413]" x-text="item.quota || '-'"></strong>
                                        </div>
                                        <div class="border-l border-[#E7E2D9]">
                                            <span class="text-[9px] uppercase text-[#78716C] block">Peminat</span>
                                            <strong class="text-xs text-[#141413]" x-text="(item.applicants || 0).toLocaleString('id-ID')"></strong>
                                        </div>
                                        <div class="border-l border-[#E7E2D9]">
                                            <span class="text-[9px] uppercase text-[#78716C] block">Keketatan</span>
                                            <strong class="text-xs font-bold"
                                                    :class="item.competition_ratio > 20 ? 'text-[#E14D2A]' : 'text-[#059669]'">
                                                <span x-text="'1 : ' + Math.round(item.competition_ratio || 0)"></span>
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <!-- 2. Tampilan Desktop: Modern Card Table View (Maks 10 Data per Halaman) -->
                        <div x-show="!prodiLoading && paginatedProdi.length > 0" class="hidden sm:block bg-white border border-[#E7E2D9] rounded-2xl overflow-hidden shadow-2xs">
                            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                <thead>
                                    <tr class="bg-[#FAF8F5] border-b border-[#E7E2D9] text-[#78716C] font-mono text-[11px] uppercase tracking-wider">
                                        <th class="py-3.5 px-4 font-bold">Program Studi</th>
                                        <th class="py-3.5 px-3 text-center font-bold">Jenjang</th>
                                        <th class="py-3.5 px-3 text-center font-bold">Rumpun</th>
                                        <th class="py-3.5 px-4 text-right font-bold">Daya Tampung</th>
                                        <th class="py-3.5 px-4 text-right font-bold">Peminat</th>
                                        <th class="py-3.5 px-5 text-right font-bold">Peluang Lolos</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#E7E2D9]/70 bg-white">
                                    <template x-for="(item, idx) in paginatedProdi" :key="item.id || idx">
                                        <tr class="hover:bg-[#FAF8F5]/80 transition-colors">
                                            <td class="py-3 px-4">
                                                <div class="font-bold text-[#141413] text-sm" x-text="item.name"></div>
                                                <div class="text-[11px] text-[#78716C] font-mono mt-0.5" x-text="item.classification || 'Program Sarjana & Vokasi'"></div>
                                            </td>
                                            <td class="py-3 px-3 text-center">
                                                <span class="inline-block px-2.5 py-1 rounded-md text-xs font-mono font-bold bg-[#FAF8F5] border border-[#E7E2D9] text-[#141413]"
                                                      x-text="item.degree === 'Sarjana' ? 'S1' : (item.degree === 'Sarjana Terapan' ? 'D4' : (item.degree || 'D3'))">
                                                </span>
                                            </td>
                                            <td class="py-3 px-3 text-center">
                                                <span class="inline-block px-2.5 py-1 rounded-md text-[11px] font-mono font-bold"
                                                      :class="item.category === 'SAINTEK' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-orange-50 text-orange-700 border border-orange-200'"
                                                      x-text="item.category">
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 text-right font-mono font-bold text-sm text-[#141413]" x-text="item.quota"></td>
                                            <td class="py-3 px-4 text-right font-mono text-sm text-[#57534E]" x-text="(item.applicants || 0).toLocaleString('id-ID')"></td>
                                            <td class="py-3 px-5 text-right font-mono">
                                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold"
                                                     :class="item.competition_ratio > 20 ? 'bg-rose-50 text-[#E14D2A] border border-rose-200' : 'bg-emerald-50 text-[#059669] border border-emerald-200'">
                                                    <span x-text="'1 : ' + Math.round(item.competition_ratio)"></span>
                                                    <span class="text-[10px] font-normal" x-text="item.competition_ratio > 20 ? '(Ketat)' : '(Sedang)'"></span>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Empty search results inside modal -->
                        <!-- Empty search results inside modal -->
                        <div x-show="!prodiLoading && allFilteredProdi.length === 0" class="py-16 text-center text-xs sm:text-sm text-[#78716C]">
                            <div class="w-10 h-10 mx-auto rounded-full bg-white border border-[#E7E2D9] flex items-center justify-center text-stone-400 mb-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            Tidak ada program studi yang cocok dengan filter atau kata kunci pencarian.
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <!-- Modal Footer & Prodi Pagination -->
                    <div class="px-5 py-3.5 sm:px-8 sm:py-4 bg-white border-t border-[#E7E2D9] flex flex-col sm:flex-row items-center justify-between text-xs sm:text-sm text-[#78716C] gap-3 shrink-0">
                        <div class="flex items-center gap-2">
                            <span>Menampilkan <strong class="text-[#141413]" x-text="paginatedProdi.length"></strong> dari <strong class="text-[#141413]" x-text="allFilteredProdi.length"></strong> Prodi</span>
                        </div>

                        <!-- Pagination Prodi Controls (Maksimal 10 Prodi per Halaman) -->
                        <div class="flex items-center gap-2" x-show="prodiLastPage > 1">
                            <button type="button" 
                                    @click="changeProdiPage(Math.max(1, prodiPage - 1))"
                                    :disabled="prodiPage <= 1"
                                    class="px-2.5 py-1 text-xs font-semibold rounded-lg border border-[#E7E2D9] bg-[#FAF8F5] text-[#141413] disabled:opacity-40 disabled:cursor-not-allowed hover:bg-stone-200 transition-colors cursor-pointer">
                                ← Prev
                            </button>
                            <span class="text-xs font-mono text-[#78716C] px-1">
                                <strong class="text-[#141413]" x-text="prodiPage"></strong> / <span x-text="prodiLastPage"></span>
                            </span>
                            <button type="button" 
                                    @click="changeProdiPage(Math.min(prodiLastPage, prodiPage + 1))"
                                    :disabled="prodiPage >= prodiLastPage"
                                    class="px-2.5 py-1 text-xs font-semibold rounded-lg border border-[#E7E2D9] bg-[#FAF8F5] text-[#141413] disabled:opacity-40 disabled:cursor-not-allowed hover:bg-stone-200 transition-colors cursor-pointer">
                                Next →
                            </button>
                        </div>

                        <button type="button" 
                                @click="closeProdiModal()"
                                class="px-4 py-2 rounded-xl bg-[#141413] hover:bg-black text-white font-semibold transition-all cursor-pointer text-xs sm:text-sm shadow-xs">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>
