@extends('layouts.admin')

@section('title', 'Audit Trail & Log Aktivitas')

@section('content')
<div class="space-y-6" x-data="{ 
    selectedLog: null,
    showModal: false,
    showPruneModal: false,
    openDetail(log) {
        this.selectedLog = log;
        this.showModal = true;
    },
    formatKey(key) {
        const translations = {
            'is_featured': 'Tampil Unggulan',
            'max_cicilan': 'Maksimal Cicilan (Tenor)',
            'bisa_dicicil': 'Bisa Dicicil',
            'dp_persen_minimal': 'Minimal DP (%)',
            'nominal': 'Harga / Nominal',
            'harga_coret': 'Harga Coret',
            'durasi_jumlah': 'Lama Durasi',
            'durasi_satuan': 'Satuan Durasi',
            'nama_paket': 'Nama Paket',
            'deskripsi': 'Deskripsi',
            'id': 'ID Data',
            'created_at': 'Waktu Dibuat',
            'updated_at': 'Waktu Diperbarui',
            'name': 'Nama Lengkap',
            'email': 'Alamat Email',
            'password': 'Kata Sandi',
            'role': 'Hak Akses (Role)'
        };
        if (translations[key]) return translations[key];
        return key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    },
    formatValue(val, key) {
        if (val === null || val === '') return '— (Kosong)';
        
        if (val === '0' || val === '1' || val === 0 || val === 1 || val === false || val === true || val === 'false' || val === 'true') {
            if (key.startsWith('is_') || key.startsWith('bisa_') || val === true || val === false || val === 'true' || val === 'false') {
                return (val === '1' || val === 1 || val === true || val === 'true') ? 'Ya / Aktif' : 'Tidak / Nonaktif';
            }
        }
        
        if (typeof val === 'object') return JSON.stringify(val);
        return val;
    }
}">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-slate-100 dark:border-zinc-800 shadow-sm">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Audit Trail & Log Aktivitas</h1>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if(in_array(strtolower(auth()->user()->level), ['super admin', 'admin', 'administrator']))
            <button type="button" @click="showPruneModal = true" class="inline-flex items-center px-3 py-1.5 rounded-xl border border-red-200 dark:border-red-900/50 text-xs font-semibold bg-white dark:bg-zinc-800 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 hover:border-red-300 transition-colors shadow-sm cursor-pointer">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                Bersihkan Log (> 30 Hari)
            </button>
            @endif
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                <span class="w-1.5 h-1.5 mr-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                Sistem Terlindungi (Deferred Active)
            </span>
        </div>
    </div>

    <!-- Quick Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Stat 1: Total Logs -->
        <div class="bg-white dark:bg-zinc-900 p-5 rounded-2xl border border-slate-100 dark:border-zinc-800 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-600 dark:text-slate-300 shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total aktivitas user</p>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mt-0.5">{{ $logs->total() }}</h3>
            </div>
        </div>

        <!-- Stat 2: Created -->
        <div class="bg-white dark:bg-zinc-900 p-5 rounded-2xl border border-slate-100 dark:border-zinc-800 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-950/20 flex items-center justify-center text-green-600 dark:text-green-400 shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0" /></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Data dibuat</p>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mt-0.5">{{ $created }}</h3>
            </div>
        </div>

        <!-- Stat 3: Updated -->
        <div class="bg-white dark:bg-zinc-900 p-5 rounded-2xl border border-slate-100 dark:border-zinc-800 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/20 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Data diubah</p>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mt-0.5">{{ $updated }}</h3>
            </div>
        </div>

        <!-- Stat 4: Deleted -->
        <div class="bg-white dark:bg-zinc-900 p-5 rounded-2xl border border-slate-100 dark:border-zinc-800 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-950/20 flex items-center justify-center text-red-600 dark:text-red-400 shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Data dihapus</p>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mt-0.5">{{ $deleted }}</h3>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm p-5 sm:p-6 mb-6">
        <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="flex flex-col gap-4 sm:gap-5">
            
            {{-- Top Row: Universal Controls --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
                {{-- Per Page --}}
                <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden w-max">
                    <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Lihat</span>
                    </div>
                    <select name="per_page" onchange="this.form.submit()"
                        class="no-tomselect bg-transparent border-none text-xs font-black focus:ring-0 py-2 pl-3 pr-8 text-slate-800 dark:text-white cursor-pointer h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors">
                        @foreach([10, 25, 50, 100] as $n)
                            <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Search & Reset --}}
                <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full sm:w-auto shrink-0">
                    <div class="relative group flex-1 sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari user, model, atau IP…"
                            class="w-full bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-2.5 pl-10 pr-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm">
                        <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-colors shadow-sm shadow-indigo-500/30 shrink-0">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'event']))
                        <a href="{{ route('admin.audit-logs.index') }}" 
                           class="flex items-center gap-2 bg-slate-100 dark:bg-zinc-800 hover:bg-rose-100 dark:hover:bg-rose-900/40 text-slate-600 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 px-4 py-2.5 rounded-xl text-sm font-bold transition-all shrink-0"
                           title="Reset Filter">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Clear
                        </a>
                    @endif
                </div>
            </div>

            {{-- Bottom Row: Data Filters --}}
            <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-slate-100 dark:border-zinc-800/50">
                {{-- Event Filter --}}
                <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden">
                    <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Jenis Aksi</span>
                    </div>
                    <select name="event" onchange="this.form.submit()"
                        class="no-tomselect bg-transparent border-none text-xs font-bold focus:ring-0 py-2 pl-3 pr-8 text-slate-800 dark:text-white cursor-pointer h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors max-w-[150px] sm:max-w-[200px] truncate">
                        <option value="">Semua Aksi</option>
                        <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Dibuat (Created)</option>
                        <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Diubah (Updated)</option>
                        <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Dihapus (Deleted)</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 dark:divide-zinc-800">
                <thead class="bg-slate-50 dark:bg-zinc-800/40">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Waktu Kejadian</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Pelaku (User)</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Jenis Aksi</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Deskripsi Aktivitas</th>
                        <th scope="col" class="px-6 py-4 class-basename text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-slate-100 dark:divide-zinc-800">
                    @forelse ($logs as $log)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/20 transition-colors">
                        <!-- Waktu -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $log->created_at->format('d M Y') }}</span><br>
                            <span class="text-xs text-slate-400">{{ $log->created_at->format('H:i:s') }}</span>
                        </td>
                        <!-- Pelaku -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-9 w-9 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-extrabold text-sm border border-indigo-500/20">
                                    {{ substr($log->user?->name ?? 'S', 0, 2) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $log->user?->name ?? 'System/Guest' }}</div>
                                    <div class="text-xs text-slate-400 font-mono">{{ $log->ip_address }}</div>
                                </div>
                            </div>
                        </td>
                        <!-- Jenis Aksi -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($log->event === 'created')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400 border border-emerald-500/10">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-emerald-500 rounded-full"></span>
                                    Dibuat
                                </span>
                            @elseif($log->event === 'updated')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/20 dark:text-blue-400 border border-blue-500/10">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-blue-500 rounded-full"></span>
                                    Diubah
                                </span>
                            @elseif($log->event === 'deleted')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-700 dark:bg-red-950/20 dark:text-red-400 border border-red-500/10">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-red-500 rounded-full"></span>
                                    Dihapus
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-50 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300">
                                    {{ $log->event }}
                                </span>
                            @endif
                        </td>
                        <!-- Deskripsi -->
                        <td class="px-6 py-4 text-sm text-slate-700 dark:text-slate-300 max-w-md truncate">
                            <div class="font-medium truncate">{{ $log->description }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">
                                Modul: {{ $log->formatted_model_name }} 
                                @if($log->auditable_id)
                                    <span class="text-indigo-500 dark:text-indigo-400 font-semibold px-1">• ID: {{ $log->auditable_id }} {{ $log->record_title ? '- '.$log->record_title : '' }}</span>
                                @endif
                            </div>
                        </td>
                        <!-- Aksi Detail -->
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <button @click="openDetail({{ json_encode($log->load('user'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }})" 
                                    class="inline-flex items-center px-3.5 py-1.5 border border-slate-200 dark:border-zinc-700 hover:border-indigo-500 dark:hover:border-indigo-500 text-xs font-semibold rounded-lg text-slate-700 dark:text-slate-300 bg-white dark:bg-zinc-800 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 hover:text-indigo-600 transition-all cursor-pointer">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                Detail Trail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500">
                            <div class="max-w-xs mx-auto">
                                <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-zinc-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-4 text-sm font-semibold text-slate-700 dark:text-slate-300">Belum Ada Log</h3>
                                <p class="mt-1 text-xs">Jejak audit akan otomatis tercatat ketika ada aktivitas perubahan data pada sistem.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Footer Pagination --}}
        <div class="px-8 py-6 border-t border-slate-100 dark:border-zinc-800 bg-slate-50/30 dark:bg-zinc-900/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <p class="text-xs text-slate-500 dark:text-slate-400 font-bold">
                @if(method_exists($logs, 'total'))
                    Menampilkan <span class="text-slate-900 dark:text-white">{{ $logs->firstItem() ?? 0 }}</span> – <span class="text-slate-900 dark:text-white">{{ $logs->lastItem() ?? 0 }}</span> dari <span class="text-slate-900 dark:text-white">{{ $logs->total() ?? 0 }}</span> Log
                @else
                    Menampilkan <span class="text-slate-900 dark:text-white">{{ $logs->count() }}</span> Log
                @endif
            </p>
            @if(method_exists($logs, 'hasPages') && $logs->hasPages())
                <div class="flex justify-end">
                    {{ $logs->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    
    @include('admin.audit-logs.partials.modals')
</div>
@endsection
