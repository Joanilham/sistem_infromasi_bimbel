@extends('layouts.admin')

@section('title', 'Audit Trail & Log Aktivitas')

@section('content')
<div class="space-y-6" x-data="{ 
    selectedLog: null,
    showModal: false,
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
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-100 dark:border-zinc-800 shadow-sm p-5">
        <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" value="{{ $search }}" 
                           class="block w-full pl-11 pr-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50/50 dark:bg-zinc-800/50 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm transition-all dark:text-white dark:placeholder-zinc-500"
                           placeholder="Cari user, model, detail aktivitas, atau alamat IP...">
                </div>
            </div>

            <!-- Event Filter -->
            <div class="w-full md:w-56">
                <select name="event" class="block w-full pl-4 pr-10 py-2.5 text-sm border-slate-200 dark:border-zinc-700 bg-slate-50/50 dark:bg-zinc-800/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                    <option value="">Semua Jenis Aksi</option>
                    <option value="created" {{ $event == 'created' ? 'selected' : '' }}>Dibuat (Created)</option>
                    <option value="updated" {{ $event == 'updated' ? 'selected' : '' }}>Diubah (Updated)</option>
                    <option value="deleted" {{ $event == 'deleted' ? 'selected' : '' }}>Dihapus (Deleted)</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center px-5 py-2.5 border border-transparent text-sm font-semibold rounded-xl shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Filter
                </button>
                @if($search || $event)
                <a href="{{ route('admin.audit-logs.index') }}" class="w-full md:w-auto inline-flex justify-center items-center px-5 py-2.5 border border-slate-200 dark:border-zinc-700 text-sm font-semibold rounded-xl text-slate-700 dark:text-slate-300 bg-white dark:bg-zinc-800 hover:bg-slate-50 dark:hover:bg-zinc-700 focus:outline-none transition-colors">
                    Reset
                </a>
                @endif
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
        
        @if($logs->hasPages())
        <div class="bg-white dark:bg-zinc-900 px-6 py-4 border-t border-slate-100 dark:border-zinc-800">
            {{ $logs->links() }}
        </div>
        @endif
    </div>

    <!-- DETAIL POPUP MODAL (Github-Style Diff Viewer) -->
    <div x-show="showModal" 
         class="fixed inset-0 z-[60] overflow-y-auto" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm z-[60]" @click="showModal = false"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div class="relative z-[70] inline-block align-bottom bg-white dark:bg-zinc-900 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-slate-100 dark:border-zinc-800"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                 
                <!-- Modal Header -->
                <div class="bg-slate-50 dark:bg-zinc-800/40 px-6 py-5 border-b border-slate-100 dark:border-zinc-800 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Detail Jejak Audit</span>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white mt-0.5" x-text="selectedLog ? selectedLog.formatted_model_name + (selectedLog.auditable_id ? ' (ID: ' + selectedLog.auditable_id + (selectedLog.record_title ? ' - ' + selectedLog.record_title : '') + ')' : '') : ''"></h3>
                    </div>
                    <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors cursor-pointer">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="p-6 space-y-6">
                    <!-- Metadata Info Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-slate-50 dark:bg-zinc-850 rounded-2xl border border-slate-100 dark:border-zinc-800 text-xs">
                        <div>
                            <span class="text-slate-400 block mb-0.5">Pelaku Utama</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200" x-text="selectedLog?.user?.name || 'System/Guest'"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">Alamat IP</span>
                            <span class="font-mono font-bold text-slate-800 dark:text-slate-200" x-text="selectedLog?.ip_address"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">Sistem Pengoperasi / Browser</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200" x-text="selectedLog?.formatted_user_agent"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">Waktu Eksekusi</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200" x-text="selectedLog ? new Date(selectedLog.created_at).toLocaleString('id-ID') : ''"></span>
                        </div>
                    </div>

                    <!-- URL Web Info -->
                    <div class="text-xs">
                        <span class="text-slate-400 block mb-1">Halaman URL Yang Diakses:</span>
                        <span class="font-mono bg-slate-100 dark:bg-zinc-850 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-zinc-700 block text-slate-700 dark:text-slate-300 overflow-x-auto" x-text="selectedLog?.url"></span>
                    </div>

                    <!-- COMPARATIVE VALUE VIEWER (Github-Style side-by-side diff) -->
                    <div>
                        <span class="text-xs font-semibold text-slate-400 block mb-2.5">Rekaman Perubahan Data:</span>
                        
                        <div class="space-y-4">
                            <!-- IF EVENT IS UPDATE (Unified Diff Table) -->
                            <template x-if="selectedLog?.event === 'updated' && ((selectedLog?.old_values && Object.keys(selectedLog.old_values).length > 0) || (selectedLog?.new_values && Object.keys(selectedLog.new_values).length > 0))">
                                <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-slate-200 dark:border-zinc-700 shadow-sm overflow-hidden">
                                    <div class="overflow-x-auto custom-scrollbar">
                                        <table class="w-full text-left text-xs border-collapse">
                                            <thead>
                                                <tr>
                                                    <th class="p-4 bg-slate-50 dark:bg-zinc-800/80 border-b border-r border-slate-200 dark:border-zinc-700 font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest w-1/4">
                                                        Modul / Kolom
                                                    </th>
                                                    <th class="p-4 bg-rose-50/50 dark:bg-rose-950/20 border-b border-r border-slate-200 dark:border-zinc-700 font-black text-rose-600 dark:text-rose-400 uppercase tracking-widest w-[37.5%]">
                                                        Data Sebelumnya <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-300">- Hapus</span>
                                                    </th>
                                                    <th class="p-4 bg-emerald-50/50 dark:bg-emerald-950/20 border-b border-slate-200 dark:border-zinc-700 font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest w-[37.5%]">
                                                        Data Terbaru <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300">+ Tambah</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 dark:divide-zinc-700/50">
                                                <!-- Loop keys that changed -->
                                                <template x-for="key in Array.from(new Set([...Object.keys(selectedLog?.old_values || {}), ...Object.keys(selectedLog?.new_values || {})]))" :key="key">
                                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-700/20 transition-colors group">
                                                        <!-- Field Name -->
                                                        <td class="p-4 border-r border-slate-100 dark:border-zinc-700/50 bg-slate-50/30 dark:bg-zinc-800/40">
                                                            <div class="flex items-center gap-2">
                                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                                <span class="font-bold text-slate-700 dark:text-slate-300 tracking-wider uppercase text-[10px]" x-text="formatKey(key)"></span>                                                            </div>
                                                        </td>
                                                        <!-- Old Value -->
                                                        <td class="p-4 border-r border-slate-100 dark:border-zinc-700/50 bg-rose-50/10 dark:bg-rose-950/10 relative">
                                                            <div class="font-medium text-rose-600 dark:text-rose-400 break-words line-through decoration-rose-200 dark:decoration-rose-900/50" 
                                                                 x-text="selectedLog?.old_values?.[key] !== undefined ? formatValue(selectedLog.old_values[key], key) : '—'">
                                                            </div>
                                                        </td>
                                                        <!-- New Value -->
                                                        <td class="p-4 bg-emerald-50/10 dark:bg-emerald-950/10 relative">
                                                            <div class="absolute inset-y-0 left-0 w-0.5 bg-emerald-400/50 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                                            <div class="font-bold text-emerald-600 dark:text-emerald-400 break-words"
                                                                 x-text="selectedLog?.new_values?.[key] !== undefined ? formatValue(selectedLog.new_values[key], key) : '—'">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </template>

                            <!-- IF EVENT IS CREATED -->
                            <template x-if="selectedLog?.event === 'created' && (selectedLog?.new_values && Object.keys(selectedLog.new_values).length > 0)">
                                <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-emerald-200 dark:border-emerald-900/50 shadow-sm overflow-hidden">
                                    <div class="overflow-x-auto custom-scrollbar">
                                        <table class="w-full text-left text-xs border-collapse">
                                            <thead>
                                                <tr>
                                                    <th class="p-4 bg-emerald-50 dark:bg-emerald-950/30 border-b border-r border-emerald-100 dark:border-emerald-900/50 font-black text-emerald-700 dark:text-emerald-400 uppercase tracking-widest w-1/3">Modul / Kolom</th>
                                                    <th class="p-4 bg-emerald-50 dark:bg-emerald-950/30 border-b border-emerald-100 dark:border-emerald-900/50 font-black text-emerald-700 dark:text-emerald-400 uppercase tracking-widest w-2/3">Data Baru Ditambahkan</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-emerald-50 dark:divide-emerald-900/20">
                                                <template x-for="(value, key) in selectedLog?.new_values" :key="key">
                                                    <tr class="hover:bg-emerald-50/30 dark:hover:bg-emerald-900/10 transition-colors">
                                                        <td class="p-4 border-r border-emerald-50 dark:border-emerald-900/20 bg-emerald-50/10 dark:bg-emerald-950/10">
                                                            <span class="font-bold text-slate-700 dark:text-slate-300 tracking-wider uppercase text-[10px]" x-text="formatKey(key)"></span>
                                                        </td>
                                                        <td class="p-4 font-medium text-emerald-600 dark:text-emerald-400 break-words"
                                                            x-text="formatValue(value, key)">
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </template>

                            <!-- IF EVENT IS DELETED -->
                            <template x-if="selectedLog?.event === 'deleted' && (selectedLog?.old_values && Object.keys(selectedLog.old_values).length > 0)">
                                <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-rose-200 dark:border-rose-900/50 shadow-sm overflow-hidden">
                                    <div class="overflow-x-auto custom-scrollbar">
                                        <table class="w-full text-left text-xs border-collapse">
                                            <thead>
                                                <tr>
                                                    <th class="p-4 bg-rose-50 dark:bg-rose-950/30 border-b border-r border-rose-100 dark:border-rose-900/50 font-black text-rose-700 dark:text-rose-400 uppercase tracking-widest w-1/3">Modul / Kolom</th>
                                                    <th class="p-4 bg-rose-50 dark:bg-rose-950/30 border-b border-rose-100 dark:border-rose-900/50 font-black text-rose-700 dark:text-rose-400 uppercase tracking-widest w-2/3">Data Dihapus</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-rose-50 dark:divide-rose-900/20">
                                                <template x-for="(value, key) in selectedLog?.old_values" :key="key">
                                                    <tr class="hover:bg-rose-50/30 dark:hover:bg-rose-900/10 transition-colors">
                                                        <td class="p-4 border-r border-rose-50 dark:border-rose-900/20 bg-rose-50/10 dark:bg-rose-950/10">
                                                            <span class="font-bold text-slate-700 dark:text-slate-300 tracking-wider uppercase text-[10px]" x-text="formatKey(key)"></span>
                                                        </td>
                                                        <td class="p-4 font-medium text-rose-600 dark:text-rose-400 break-words line-through decoration-rose-200"
                                                            x-text="formatValue(value, key)">
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </template> 
                        </div>

                            <!-- FALLBACK IF CHANGES ARE EMPTY (Authentication events or filtered sensitive attributes) -->
                            <template x-if="(!selectedLog?.old_values || Object.keys(selectedLog.old_values).length === 0) && (!selectedLog?.new_values || Object.keys(selectedLog.new_values).length === 0)">
                                <div class="p-8 bg-slate-50/50 dark:bg-zinc-850 rounded-[2rem] border border-dashed border-slate-200 dark:border-zinc-800 text-center space-y-4">
                                    <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center mx-auto text-2xl shadow-inner">
                                        🔒
                                    </div>
                                    <div class="space-y-1">
                                        <h4 class="font-black text-slate-800 dark:text-white text-sm">Aktivitas Non-Substantif / Data Terlindungi</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 max-w-lg mx-auto leading-relaxed">
                                            Operasi ini tidak mengubah nilai data substantif, atau seluruh perubahan data bersifat sangat sensitif (seperti token akses, kata sandi, atau kunci keamanan sesi) yang disaring secara otomatis demi kepatuhan regulasi perlindungan data.
                                        </p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-slate-50 dark:bg-zinc-800/40 px-6 py-4 border-t border-slate-100 dark:border-zinc-800 flex justify-end">
                    <button type="button" 
                            @click="showModal = false"
                            class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-white text-xs font-bold rounded-xl shadow transition-colors cursor-pointer">
                        Selesai Membaca
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
