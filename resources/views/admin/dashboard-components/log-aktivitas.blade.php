    {{-- ─── Log Aktivitas Terbaru ─── --}}
    <div class="mb-10">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Log Aktivitas Sistem</h2>
                <p class="text-sm text-slate-500 mt-0.5">Rekam jejak tindakan yang dilakukan pengguna secara real-time</p>
            </div>
            <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-xs font-bold text-slate-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="bg-white border border-slate-100 rounded-[2rem] overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100">
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Pengguna</th>
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Aktivitas</th>
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Modul</th>
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 hidden md:table-cell">Perangkat</th>
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($recentAuditLogs ?? [] as $log)
                        @php
                            $eventConfig = match(true) {
                                $log->event === 'created'       => ['label' => 'Tambah',  'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'ring' => 'ring-emerald-100', 'dot' => 'bg-emerald-500'],
                                $log->event === 'updated'       => ['label' => 'Ubah',    'bg' => 'bg-amber-50',   'text' => 'text-amber-700',   'ring' => 'ring-amber-100',   'dot' => 'bg-amber-500'],
                                $log->event === 'deleted'       => ['label' => 'Hapus',   'bg' => 'bg-rose-50',    'text' => 'text-rose-700',    'ring' => 'ring-rose-100',    'dot' => 'bg-rose-500'],
                                str_contains($log->event, 'Login')  => ['label' => 'Login',   'bg' => 'bg-indigo-50',  'text' => 'text-indigo-700',  'ring' => 'ring-indigo-100',  'dot' => 'bg-indigo-500'],
                                str_contains($log->event, 'Logout') => ['label' => 'Logout',  'bg' => 'bg-slate-100',  'text' => 'text-slate-600',   'ring' => 'ring-slate-200',   'dot' => 'bg-slate-400'],
                                default                         => ['label' => $log->event, 'bg' => 'bg-blue-50',   'text' => 'text-blue-700',    'ring' => 'ring-blue-100',    'dot' => 'bg-blue-500'],
                            };
                            $userName  = $log->user?->name ?? 'Sistem';
                            $userLevel = $log->user?->level ?? '-';
                            $initial   = strtoupper(substr($userName, 0, 1));
                        @endphp
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            {{-- Pengguna --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-xs shrink-0">
                                        {{ $initial }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-800 leading-tight">{{ $userName }}</p>
                                        <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider">{{ $userLevel }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Event Badge --}}
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider ring-1 {{ $eventConfig['bg'] }} {{ $eventConfig['text'] }} {{ $eventConfig['ring'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $eventConfig['dot'] }}"></span>
                                    {{ $eventConfig['label'] }}
                                </span>
                            </td>

                            {{-- Modul --}}
                            <td class="px-5 py-3.5">
                                <p class="text-xs font-semibold text-slate-600">{{ $log->formatted_model_name }}</p>
                                @if($log->auditable_id)
                                <p class="text-[9px] text-slate-500 mt-0.5 truncate max-w-[160px]" title="ID #{{ $log->auditable_id }} {{ $log->record_title ? '- '.$log->record_title : '' }}">
                                    <span class="font-mono text-slate-400">#{{ $log->auditable_id }}</span> 
                                    {{ $log->record_title ? '• '.$log->record_title : '' }}
                                </p>
                                @endif
                            </td>

                            {{-- Perangkat --}}
                            <td class="px-5 py-3.5 hidden md:table-cell">
                                <p class="text-[10px] text-slate-500 font-medium">{{ $log->formatted_user_agent }}</p>
                                <p class="text-[9px] text-slate-400 font-mono mt-0.5">{{ $log->ip_address }}</p>
                            </td>

                            {{-- Waktu --}}
                            <td class="px-5 py-3.5 text-right">
                                <p class="text-[10px] font-bold text-slate-500">{{ $log->created_at->diffForHumans() }}</p>
                                <p class="text-[9px] text-slate-400">{{ $log->created_at->format('d M, H:i') }}</p>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm">
                                Belum ada aktivitas yang tercatat
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


