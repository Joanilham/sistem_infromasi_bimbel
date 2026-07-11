    <!-- MENU LAPORAN & LOG -->
    @if(auth()->check() && (auth()->user()->hasPermission('manage_rekapitulasi') || in_array(strtolower(auth()->user()->level), ['super admin', 'administrator']) || auth()->user()->hasPermission('manage_audit_logs')))
    <div class="mb-4">
        <h3 class="px-3 text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-widest mb-2">LAPORAN & LOG</h3>
        <nav class="space-y-1">

            {{-- Rekapitulasi --}}
            @if(auth()->user()->hasPermission('manage_rekapitulasi'))
            <a href="{{ route('admin.rekapitulasi.index') }}"
                class="flex items-center px-3 py-1.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.rekapitulasi.*') ? 'bg-indigo-500 shadow-md shadow-indigo-500/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('admin.rekapitulasi.*') ? 'bg-white/25' : 'bg-indigo-500 shadow-sm shadow-indigo-500/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('admin.rekapitulasi.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Rekapitulasi Data</span>
            </a>
            @endif

            {{-- Log Aktivitas --}}
            @if(in_array(strtolower(auth()->user()->level), ['super admin', 'administrator']) || auth()->user()->hasPermission('manage_audit_logs'))
            <a href="{{ route('admin.audit-logs.index') }}"
                class="flex items-center px-3 py-1.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.audit-logs.*') ? 'bg-slate-800 shadow-md shadow-slate-800/30' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }} group">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('admin.audit-logs.*') ? 'bg-white/25' : 'bg-slate-700 shadow-sm shadow-slate-700/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('admin.audit-logs.*') ? 'text-white' : 'text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white' }}">Log Aktivitas</span>
            </a>
            @endif

            {{-- Log Sistem --}}
            @if(strtolower(auth()->user()->level) === 'super admin')
            <a href="{{ route('log-viewer.index') }}"
                target="_blank"
                class="flex items-center px-3 py-1.5 rounded-2xl transition-all duration-200 hover:bg-slate-100 dark:hover:bg-zinc-800 {{ request()->routeIs('log-viewer.index') ? 'bg-indigo-500 shadow-md shadow-indigo-500/30 text-white' : 'text-slate-700 dark:text-slate-200' }} group">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mr-3 {{ request()->routeIs('log-viewer.index') ? 'bg-white/25' : 'bg-indigo-600 shadow-sm shadow-indigo-600/40' }}">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold {{ request()->routeIs('log-viewer.index') ? 'text-white' : 'group-hover:text-slate-900 dark:group-hover:text-white' }}">Log Sistem (Error)</span>
            </a>
            @endif
        </nav>
    </div>
    @endif
