    <!-- MENU PENGATURAN -->
    @if(auth()->check() && (in_array(strtolower(auth()->user()->level), ['super admin', 'administrator']) || auth()->user()->hasPermission('manage_kantor') || auth()->user()->hasPermission('manage_periode') || auth()->user()->hasPermission('manage_pengguna') || auth()->user()->hasPermission('manage_master') || auth()->user()->hasPermission('manage_landing_page') || auth()->user()->hasPermission('manage_backup') || auth()->user()->hasPermission('manage_audit_logs')))
    <div class="mb-6">
        <h3 class="px-3 text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-widest mb-2">MENU PENGATURAN SISTEM</h3>
        <nav class="space-y-1">

            @if(in_array(strtolower(auth()->user()->level), ['super admin', 'administrator']) || auth()->user()->hasPermission('manage_kantor'))
            <a href="{{ route('kantor.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('kantor.*') ? 'bg-cyan-500 shadow-md shadow-cyan-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('kantor.*') ? 'bg-white/25' : 'bg-cyan-500 shadow-sm shadow-cyan-500/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('kantor.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Pengaturan Kantor</span>
            </a>
            @endif

            @if(in_array(strtolower(auth()->user()->level), ['super admin', 'administrator']) || auth()->user()->hasPermission('manage_periode'))
            <a href="{{ route('periode.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('periode.*') ? 'bg-rose-500 shadow-md shadow-rose-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('periode.*') ? 'bg-white/25' : 'bg-rose-500 shadow-sm shadow-rose-500/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('periode.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Pengaturan Periode</span>
            </a>
            @endif

            @if(in_array(strtolower(auth()->user()->level), ['super admin', 'administrator']) || auth()->user()->hasPermission('manage_master'))
            <a href="{{ route('master.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('master.*') ? 'bg-slate-600 shadow-md shadow-slate-600/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('master.*') ? 'bg-white/25' : 'bg-slate-500 shadow-sm shadow-slate-500/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('master.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Pengaturan Master</span>
            </a>
            @endif

            @if(in_array(strtolower(auth()->user()->level), ['super admin', 'administrator']) || auth()->user()->hasPermission('manage_landing_page'))
            <a href="{{ route('admin.landing-page.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.landing-page.*') ? 'bg-indigo-500 shadow-md shadow-indigo-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('admin.landing-page.*') ? 'bg-white/25' : 'bg-indigo-500 shadow-sm shadow-indigo-500/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('admin.landing-page.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Landing Page</span>
            </a>
            @endif


            @if(in_array(strtolower(auth()->user()->level), ['super admin', 'administrator']) || auth()->user()->hasPermission('manage_pengguna'))
            <a href="{{ route('pengguna.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('pengguna.*') ? 'bg-violet-500 shadow-md shadow-violet-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('pengguna.*') ? 'bg-white/25' : 'bg-violet-500 shadow-sm shadow-violet-500/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('pengguna.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Pengaturan Pengguna</span>
            </a>
            @endif

            @if(in_array(strtolower(auth()->user()->level), ['super admin', 'administrator']) || auth()->user()->hasPermission('manage_backup'))
            <a href="{{ route('admin.backup.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.backup.*') ? 'bg-zinc-700 shadow-md shadow-zinc-700/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('admin.backup.*') ? 'bg-white/25' : 'bg-zinc-600 shadow-sm shadow-zinc-600/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('admin.backup.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Backup Database</span>
            </a>
            @endif

            @if(strtolower(auth()->user()->level) === 'super admin')
            <a href="{{ route('admin.recycle-bin.index') }}"
                class="flex items-center px-3 py-2.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.recycle-bin.*') ? 'bg-red-500 shadow-md shadow-red-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('admin.recycle-bin.*') ? 'bg-white/25' : 'bg-red-500 shadow-sm shadow-red-500/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('admin.recycle-bin.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Recycle Bin</span>
            </a>
            @endif
        </nav>
    </div>
    @endif
