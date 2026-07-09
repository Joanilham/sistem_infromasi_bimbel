<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Logs - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="{{ asset('vendor/alpinejs/alpine.min.js') }}"></script>
</head>
<body class="bg-slate-50 dark:bg-zinc-950 min-h-screen p-4 sm:p-8">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-zinc-900 p-6 rounded-[2rem] shadow-sm border border-slate-100 dark:border-zinc-800">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-500/10 text-indigo-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    System Logs
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 font-medium">Memantau aktivitas dan error sistem ({{ $fileSize }} - {{ $selectedFile }})</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <form action="{{ route('admin.log-viewer') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                    {{-- Search Input --}}
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pesan error..." class="bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold pl-10 pr-4 py-2 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none w-full sm:w-48 placeholder-slate-400">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    {{-- Level Filter --}}
                    <select name="level" onchange="this.form.submit()" class="bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold px-4 py-2 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none">
                        <option value="">Semua Status</option>
                        @foreach(['EMERGENCY', 'ALERT', 'CRITICAL', 'ERROR', 'WARNING', 'NOTICE', 'INFO', 'DEBUG'] as $lvl)
                            <option value="{{ $lvl }}" {{ request('level') === $lvl ? 'selected' : '' }}>
                                {{ $lvl }}
                            </option>
                        @endforeach
                    </select>

                    {{-- File Filter --}}
                    <select name="file" onchange="this.form.submit()" class="bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold px-4 py-2 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none">
                        @foreach($files as $file)
                            <option value="{{ $file['name'] }}" {{ $file['name'] === $selectedFile ? 'selected' : '' }}>
                                {{ $file['name'] }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Search Submit --}}
                    <button type="submit" class="hidden"></button>
                </form>

                @if($selectedFile)
                <form action="{{ route('admin.log-viewer.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus isi file log ini?');">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="file" value="{{ $selectedFile }}">
                    <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white font-bold text-sm px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-rose-500/20 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Clear Log
                    </button>
                </form>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-100 text-emerald-800 px-4 py-3 rounded-xl text-sm font-bold border border-emerald-200 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-100 text-rose-800 px-4 py-3 rounded-xl text-sm font-bold border border-rose-200 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Summary Badges -->
        @if(count($summary) > 0)
        <div class="flex flex-wrap gap-3 px-2">
            @foreach($summary as $level => $count)
                @php
                    $color = match(strtoupper($level)) {
                        'EMERGENCY', 'ALERT', 'CRITICAL', 'ERROR' => 'rose',
                        'WARNING' => 'amber',
                        'NOTICE', 'INFO' => 'indigo',
                        'DEBUG' => 'slate',
                        default => 'slate',
                    };
                @endphp
                <div class="bg-{{ $color }}-100 dark:bg-{{ $color }}-500/10 text-{{ $color }}-700 dark:text-{{ $color }}-400 px-4 py-2 rounded-xl text-sm font-black border border-{{ $color }}-200 dark:border-{{ $color }}-500/20 shadow-sm flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-{{ $color }}-500 animate-pulse"></span>
                    {{ $level }}: <span class="text-{{ $color }}-900 dark:text-{{ $color }}-200">{{ $count }}</span>
                </div>
            @endforeach
        </div>
        @endif

        <!-- Log Table -->
        <div class="bg-white dark:bg-zinc-900 rounded-[2rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">
            @if(empty($logs) || $logs->isEmpty())
                <div class="p-16 text-center">
                    <div class="w-24 h-24 bg-emerald-50 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white">Log Kosong</h3>
                    <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium">Sistem berjalan dengan baik. Tidak ada error log pada file ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 dark:bg-zinc-950/50 border-b border-slate-200 dark:border-zinc-800">
                            <tr>
                                <th class="px-6 py-4 text-left font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest text-[10px] w-48">Waktu</th>
                                <th class="px-6 py-4 text-left font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest text-[10px] w-32">Level</th>
                                <th class="px-6 py-4 text-left font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest text-[10px]">Pesan & Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @foreach($logs as $index => $log)
                                @php
                                    $color = match($log['level']) {
                                        'EMERGENCY', 'ALERT', 'CRITICAL', 'ERROR' => 'rose',
                                        'WARNING' => 'amber',
                                        'NOTICE', 'INFO' => 'indigo',
                                        'DEBUG' => 'slate',
                                        default => 'slate',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/30 transition-colors group">
                                    <td class="px-6 py-5 whitespace-nowrap text-xs font-bold text-slate-600 dark:text-slate-400 align-top">
                                        {{ $log['date'] }}
                                    </td>
                                    <td class="px-6 py-5 align-top">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-{{ $color }}-100 dark:bg-{{ $color }}-500/10 text-{{ $color }}-700 dark:text-{{ $color }}-400 border border-{{ $color }}-200 dark:border-{{ $color }}-500/20">
                                            {{ $log['level'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 align-top">
                                        <div class="text-sm font-bold text-slate-900 dark:text-white break-words leading-relaxed">
                                            {{ $log['message'] }}
                                        </div>
                                        @if(!empty($log['stack']))
                                            <div x-data="{ expanded: false }" class="mt-3">
                                                <button @click="expanded = !expanded" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 flex items-center gap-1 transition-colors">
                                                    <svg class="w-4 h-4 transition-transform" :class="expanded ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    <span x-text="expanded ? 'Sembunyikan Detail' : 'Tampilkan Stack Trace'"></span>
                                                </button>
                                                <div x-show="expanded" style="display: none;" class="mt-4">
                                                    <pre class="p-5 bg-slate-900 dark:bg-[#0a0a0a] text-emerald-400 rounded-2xl text-[11px] overflow-x-auto max-h-96 overflow-y-auto border border-slate-800 dark:border-zinc-800 shadow-inner font-mono leading-relaxed whitespace-pre-wrap word-break">{{ $log['stack'] }}</pre>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($logs->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950">
                        {{ $logs->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</body>
</html>
