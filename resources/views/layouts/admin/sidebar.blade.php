<!-- Sidebar Header -->
<div class="flex items-center justify-center h-20 border-b border-slate-100 dark:border-zinc-800 px-4 bg-white dark:bg-zinc-950">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
        {{-- Logo: tampilkan gambar dari DB jika ada, fallback ke icon SVG --}}
        @if($masterData && $masterData->logo)
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
        <span class="text-xl font-extrabold text-slate-800 dark:text-white tracking-tight">Genius<span class="text-emerald-600">Edu</span></span>
        @endif
    </a>
</div>

<!-- Sidebar Content -->
<div id="sidebar-scroll-container" class="flex-1 min-h-0 overflow-y-auto p-4 pb-24 custom-scrollbar bg-white dark:bg-zinc-950">
    <nav class="space-y-1 mb-4">
        <a href="{{ route('dashboard') }}" aria-label="Dashboard"
            class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-500 shadow-md shadow-blue-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-white/25' : 'bg-blue-500 shadow-sm shadow-blue-500/40' }}">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <span class="text-sm font-semibold {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Dashboard</span>
        </a>
    </nav>

    @include('layouts.admin.sidebar-components.menu-pengguna')
    @include('layouts.admin.sidebar-components.menu-akademik')
    @include('layouts.admin.sidebar-components.menu-keuangan')
    @include('layouts.admin.sidebar-components.menu-laporan')
    @include('layouts.admin.sidebar-components.menu-pengaturan')
</div>
<script>
// Instant scroll restore — runs synchronously as the browser parses this element.
// No x-cloak means the sidebar is already laid out, so scrollTop works immediately.
(function(){
    var s = document.getElementById('sidebar-scroll-container');
    var v = localStorage.getItem('sidebarScrollTop');
    if (s && v) s.scrollTop = parseInt(v, 10);
})();
</script>
