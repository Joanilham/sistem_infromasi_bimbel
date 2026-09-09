<!-- Sidebar Header -->
<div class="flex items-center justify-center h-20 border-b border-slate-100 dark:border-zinc-800 px-4 bg-white dark:bg-zinc-950">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
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
        <a href="{{ route('dashboard') }}" aria-label="Dashboard"
            class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
            <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('dashboard') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-sm">Dashboard</span>
        </a>

        {{-- Pemberitahuan Menu --}}
        <a href="{{ route('notifikasi.index') }}" aria-label="Pemberitahuan"
            class="flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('notifikasi.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
            <div class="flex items-center min-w-0">
                <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('notifikasi.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="text-sm">Pemberitahuan</span>
            </div>
            @if(isset($totalPesan) && $totalPesan > 0)
                <span class="min-w-[20px] h-5 px-1.5 rounded-full bg-red-600 text-white text-[10px] font-black flex items-center justify-center shadow-xs">
                    {{ $totalPesan > 99 ? '99+' : $totalPesan }}
                </span>
            @endif
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
