<!-- Sidebar Header -->
<div class="flex items-center justify-center h-20 border-b border-slate-100 dark:border-zinc-800 px-4 bg-white dark:bg-zinc-950">
    <a href="{{ route('guru.dashboard') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
        {{-- Logo: tampilkan gambar dari DB jika ada, fallback ke icon SVG --}}
        @if($masterData && $masterData->hasCustomLogo())
        <div class="w-9 h-9 rounded-xl overflow-hidden shadow-md border border-slate-100 dark:border-zinc-700 shrink-0">
            <img src="{{ asset('storage/' . $masterData->logo) }}" alt="Logo" class="w-full h-full object-contain bg-white rounded-xl">
        </div>
        @else
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-md shadow-emerald-500/30 shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
        </div>
        @endif
        @if($masterData && $masterData->nama_lembaga)
        <span class="text-base font-extrabold text-slate-800 dark:text-white tracking-tight leading-tight max-w-[130px] truncate">
            {{ $masterData->nama_lembaga }}
        </span>
        @else
        <span class="text-base font-extrabold text-slate-800 dark:text-white tracking-tight leading-tight max-w-[130px] truncate">{{ config('app.name') }}</span>
        @endif
    </a>
</div>

<!-- Sidebar Content -->
<div id="sidebar-scroll-container" class="flex-1 min-h-0 overflow-y-auto p-4 pb-24 custom-scrollbar bg-white dark:bg-zinc-950">
    <nav class="space-y-1 mb-5">
        <a href="{{ route('guru.dashboard') }}" aria-label="Dashboard"
            class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('guru.dashboard') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
            <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('guru.dashboard') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-sm">Dashboard</span>
        </a>
    </nav>

    <!-- MENU MENGAJAR -->
    <div class="mb-6">
        <h3 class="px-3 text-[10px] font-bold uppercase text-slate-400 dark:text-zinc-500 tracking-widest mb-2">MENU MENGAJAR</h3>
        <nav class="space-y-1">
            <a href="{{ route('guru.jadwal.index') }}" aria-label="Jadwal Mengajar"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('guru.jadwal.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('guru.jadwal.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-sm">Jadwal Mengajar</span>
            </a>
        </nav>
    </div>

    <!-- LMS -->
    <div class="mb-6">
        <h3 class="px-3 text-[10px] font-bold uppercase text-slate-400 dark:text-zinc-500 tracking-widest mb-2">PEMBELAJARAN</h3>
        <nav class="space-y-1">
            <a href="{{ route('guru.lms.index') }}" aria-label="LMS"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('guru.lms.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 mr-3 shrink-0 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                <span class="text-sm">LMS</span>
            </a>
        </nav>
    </div>

    <!-- CBT / UJIAN -->
    <div class="mb-6">
        <h3 class="px-3 text-[10px] font-bold uppercase text-slate-400 dark:text-zinc-500 tracking-widest mb-2">CBT & UJIAN</h3>
        <nav class="space-y-1">
            <a href="{{ route('guru.bank-soal.index') }}" aria-label="Bank Soal"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('guru.bank-soal.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('guru.bank-soal.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-sm">Bank Soal</span>
            </a>

            <a href="{{ route('guru.ujian.index') }}" aria-label="Manajemen Ujian"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('guru.ujian.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('guru.ujian.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span class="text-sm">Manajemen Ujian</span>
            </a>
        </nav>
    </div>
</div>
<script>
(function(){
    var s = document.getElementById('sidebar-scroll-container');
    var v = localStorage.getItem('sidebarScrollTop');
    if (s && v) s.scrollTop = parseInt(v, 10);
})();
</script>
