<!-- Sidebar Header -->
<div class="flex items-center justify-center h-20 border-b border-slate-100 dark:border-zinc-800 px-4 bg-white dark:bg-zinc-950">
    <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#388782] to-[#206D6C] flex items-center justify-center shadow-md shadow-[#388782]/30 shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
        </div>
        <span class="text-xl font-extrabold text-slate-800 dark:text-white tracking-tight">Genius<span class="text-[#388782]">Guru</span></span>
    </div>
</div>

<!-- Sidebar Content -->
<div id="sidebar-scroll-container" class="flex-1 min-h-0 overflow-y-auto p-4 pb-24 custom-scrollbar bg-white dark:bg-zinc-950">
    <nav class="space-y-1 mb-8">
        <a href="{{ route('guru.dashboard') }}"
            class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('guru.dashboard') ? 'bg-[#388782] shadow-md shadow-[#388782]/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 transition-all duration-200 {{ request()->routeIs('guru.dashboard') ? 'bg-white/25' : 'bg-[#388782] shadow-sm shadow-[#388782]/40' }}">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <span class="text-sm font-semibold {{ request()->routeIs('guru.dashboard') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Dashboard</span>
        </a>
    </nav>

    <!-- MENU MENGAJAR -->
    <div class="mb-6">
        <h3 class="px-3 text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-widest mb-2">MENU MENGAJAR</h3>
        <nav class="space-y-1">

            <a href="{{ route('guru.jadwal.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('guru.jadwal.*') ? 'bg-[#388782] shadow-md shadow-[#388782]/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('guru.jadwal.*') ? 'bg-white/25' : 'bg-[#388782] shadow-sm shadow-[#388782]/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('guru.jadwal.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Jadwal Mengajar</span>
            </a>

        </nav>
    </div>

    <!-- MENU CBT -->
    <div class="mb-6">
        <h3 class="px-3 text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-widest mb-2">CBT / UJIAN</h3>
        <nav class="space-y-1">

            {{-- Bank Soal --}}
            <a href="{{ route('guru.bank-soal.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('guru.bank-soal.*') ? 'bg-[#388782] shadow-md shadow-[#388782]/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('guru.bank-soal.*') ? 'bg-white/25' : 'bg-[#388782] shadow-sm shadow-[#388782]/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('guru.bank-soal.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Bank Soal</span>
            </a>

            {{-- Manajemen Ujian --}}
            <a href="{{ route('guru.ujian.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('guru.ujian.*') ? 'bg-[#388782] shadow-md shadow-[#388782]/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('guru.ujian.*') ? 'bg-white/25' : 'bg-[#388782] shadow-sm shadow-[#388782]/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('guru.ujian.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Manajemen Ujian</span>
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
