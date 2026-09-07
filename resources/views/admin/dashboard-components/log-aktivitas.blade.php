    {{-- ─── Log Aktivitas Terbaru ─── --}}
    <div class="mb-10">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 dark:text-white tracking-tight">Log Aktivitas Sistem</h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">Rekam jejak tindakan pengguna secara real-time</p>
            </div>
            <a href="{{ route('admin.audit-logs.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-indigo-600 hover:text-white transition-all shadow-xs shrink-0">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 dark:bg-slate-800/50 border-b border-slate-100 dark:border-zinc-800">
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Pengguna</th>
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Aktivitas</th>
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Modul</th>
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 hidden md:table-cell">Perangkat</th>
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-right">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        @forelse($recentAuditLogs ?? [] as $log)
                        @php
                            $eventConfig = match(true) {
                                $log->event === 'created'       => ['label' => 'Tambah',  'bg' => 'bg-emerald-50 dark:bg-emerald-950/40', 'text' => 'text-emerald-700 dark:text-emerald-400', 'ring' => 'ring-emerald-200/50 dark:ring-emerald-800/50', 'dot' => 'bg-emerald-500'],
                                $log->event === 'updated'       => ['label' => 'Ubah',    'bg' => 'bg-amber-50 dark:bg-amber-950/40',   'text' => 'text-amber-700 dark:text-amber-400',   'ring' => 'ring-amber-200/50 dark:ring-amber-800/50',   'dot' => 'bg-amber-500'],
                                $log->event === 'deleted'       => ['label' => 'Hapus',   'bg' => 'bg-rose-50 dark:bg-rose-950/40',    'text' => 'text-rose-700 dark:text-rose-400',    'ring' => 'ring-rose-200/50 dark:ring-rose-800/50',    'dot' => 'bg-rose-500'],
                                str_contains($log->event, 'Login')  => ['label' => 'Login',   'bg' => 'bg-indigo-50 dark:bg-indigo-950/40',  'text' => 'text-indigo-700 dark:text-indigo-400',  'ring' => 'ring-indigo-200/50 dark:ring-indigo-800/50',  'dot' => 'bg-indigo-500'],
                                str_contains($log->event, 'Logout') => ['label' => 'Logout',  'bg' => 'bg-slate-100 dark:bg-slate-800',  'text' => 'text-slate-600 dark:text-slate-400',   'ring' => 'ring-slate-200 dark:ring-slate-700',   'dot' => 'bg-slate-400'],
                                default                         => ['label' => $log->event, 'bg' => 'bg-blue-50 dark:bg-blue-950/40',   'text' => 'text-blue-700 dark:text-blue-400',    'ring' => 'ring-blue-200/50 dark:ring-blue-800/50',    'dot' => 'bg-blue-500'],
                            };
                            $userName  = $log->user?->name ?? 'Sistem';
                            $userLevel = $log->user?->level ?? '-';
                            $initial   = strtoupper(substr($userName, 0, 1));
                        @endphp
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            {{-- Pengguna --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-black text-xs shrink-0">
                                        {{ $initial }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-800 dark:text-white leading-tight">{{ $userName }}</p>
                                        <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider">{{ $userLevel }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Event Badge --}}
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider ring-1 {{ $eventConfig['bg'] }} {{ $eventConfig['text'] }} {{ $eventConfig['ring'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $eventConfig['dot'] }}"></span>
                                    {{ $eventConfig['label'] }}
                                </span>
                            </td>

                            {{-- Modul --}}
                            <td class="px-5 py-3.5">
                                <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $log->formatted_model_name }}</p>
                                @if($log->auditable_id)
                                <p class="text-[9px] text-slate-400 mt-0.5 truncate max-w-[160px]" title="ID #{{ $log->auditable_id }} {{ $log->record_title ? '- '.$log->record_title : '' }}">
                                    <span class="font-mono text-slate-400">#{{ $log->auditable_id }}</span> 
                                    {{ $log->record_title ? '• '.$log->record_title : '' }}
                                </p>
                                @endif
                            </td>

                            {{-- Perangkat --}}
                            <td class="px-5 py-3.5 hidden md:table-cell">
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">{{ $log->formatted_user_agent }}</p>
                                <p class="text-[9px] text-slate-400 font-mono mt-0.5">{{ $log->ip_address }}</p>
                            </td>

                            {{-- Waktu --}}
                            <td class="px-5 py-3.5 text-right">
                                <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400">{{ $log->created_at->diffForHumans() }}</p>
                                <p class="text-[9px] text-slate-400">{{ $log->created_at->format('d M, H:i') }}</p>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm">Belum ada aktivitas yang tercatat</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
