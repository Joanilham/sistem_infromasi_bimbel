@extends('layouts.admin')

@section('title', 'Recycle Bin (Recycle Bin)')

@section('content')
<div class="space-y-6" x-data="{ 
    tab: '{{ session('active_tab') }}' || localStorage.getItem('activeRecycleTab') || 'peserta_didik' 
}" x-init="$watch('tab', val => localStorage.setItem('activeRecycleTab', val))">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-slate-100 dark:border-zinc-800 shadow-sm">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center text-red-600 dark:text-red-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Recycle Bin</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola dan kembalikan data yang sudah dihapus.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="flex overflow-x-auto custom-scrollbar border-b border-slate-100 dark:border-zinc-800">
            <button @click="tab = 'peserta_didik'" :class="tab === 'peserta_didik' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 font-bold bg-indigo-50/50 dark:bg-indigo-900/20' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300'" class="whitespace-nowrap px-6 py-4 border-b-2 text-sm font-medium transition-all">
                Peserta Didik ({{ $trashedData['peserta_didik']->count() }})
            </button>
            <button @click="tab = 'transaksi'" :class="tab === 'transaksi' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 font-bold bg-indigo-50/50 dark:bg-indigo-900/20' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300'" class="whitespace-nowrap px-6 py-4 border-b-2 text-sm font-medium transition-all">
                Transaksi SPP ({{ $trashedData['transaksi']->count() }})
            </button>
            <button @click="tab = 'pemasukan'" :class="tab === 'pemasukan' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 font-bold bg-indigo-50/50 dark:bg-indigo-900/20' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300'" class="whitespace-nowrap px-6 py-4 border-b-2 text-sm font-medium transition-all">
                Pemasukan ({{ $trashedData['pemasukan']->count() }})
            </button>
            <button @click="tab = 'pengeluaran'" :class="tab === 'pengeluaran' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 font-bold bg-indigo-50/50 dark:bg-indigo-900/20' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300'" class="whitespace-nowrap px-6 py-4 border-b-2 text-sm font-medium transition-all">
                Pengeluaran ({{ $trashedData['pengeluaran']->count() }})
            </button>
            <button @click="tab = 'ujian'" :class="tab === 'ujian' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 font-bold bg-indigo-50/50 dark:bg-indigo-900/20' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300'" class="whitespace-nowrap px-6 py-4 border-b-2 text-sm font-medium transition-all">
                Ujian CBT ({{ $trashedData['ujian']->count() }})
            </button>
        </div>

        <div class="p-0">
            <!-- TAB: PESERTA DIDIK -->
            <div x-show="tab === 'peserta_didik'" class="overflow-x-auto">
                <form action="{{ route('admin.recycle-bin.bulk') }}" method="POST" x-data="{ selected: [], selectAll: false }">
                    @csrf
                    <input type="hidden" name="type" value="peserta_didik">
                    <div x-show="selected.length > 0" x-transition class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-lg p-3 mb-4 flex items-center justify-between" x-cloak>
                        <span class="text-sm text-indigo-700 dark:text-indigo-300 font-medium"><span x-text="selected.length"></span> item terpilih</span>
                        <div class="flex gap-2">
                            <button type="submit" name="action" value="restore" class="px-3 py-1.5 bg-emerald-600 text-white rounded text-xs font-bold hover:bg-emerald-700 transition-colors">Pulihkan Pilihan</button>
                            <button type="submit" name="action" value="force_delete" class="px-3 py-1.5 bg-red-600 text-white rounded text-xs font-bold hover:bg-red-700 transition-colors" onclick="return confirm('Hapus permanen ' + selected.length + ' item terpilih?')">Hapus Permanen Pilihan</button>
                        </div>
                    </div>

                <table class="min-w-full divide-y divide-slate-100 dark:divide-zinc-800">
                    <thead class="bg-slate-50 dark:bg-zinc-800/40">
                        <tr>
                            <th class="px-4 py-4 w-10 text-center"><input type="checkbox" x-model="selectAll" x-on:change="selected = selectAll ? Array.from($el.closest('table').querySelectorAll('tbody input[type=checkbox]')).map(cb => cb.value) : []" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"></th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase">Nama Peserta</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase">Tgl Dihapus</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @forelse($trashedData['peserta_didik'] as $item)
                        <tr>
                            <td class="px-4 py-4 w-10 text-center"><input type="checkbox" name="ids[]" value="{{ $item->id }}" x-model="selected" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"></td>
                            <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">{{ $item->nama_lengkap }} <br><span class="text-xs text-slate-400 font-normal">No: {{ $item->nomor_pendaftaran }}</span></td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $item->deleted_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <form action="{{ route('admin.recycle-bin.restore', ['type' => 'peserta_didik', 'id' => $item->id]) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded shadow-sm text-xs font-bold hover:bg-emerald-100">Restore</button>
                                    </form>
                                    <form action="{{ route('admin.recycle-bin.force-delete', ['type' => 'peserta_didik', 'id' => $item->id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus permanen? Tidak bisa dikembalikan lagi!');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-50 text-red-600 rounded shadow-sm text-xs font-bold hover:bg-red-100">Hapus Permanen</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">Recycle Bin kosong.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                            </form>
</div>

            <!-- TAB: TRANSAKSI SPP -->
            <div x-show="tab === 'transaksi'" style="display: none;" class="overflow-x-auto">
                <form action="{{ route('admin.recycle-bin.bulk') }}" method="POST" x-data="{ selected: [], selectAll: false }">
                    @csrf
                    <input type="hidden" name="type" value="transaksi">
                    <div x-show="selected.length > 0" x-transition class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-lg p-3 mb-4 flex items-center justify-between" x-cloak>
                        <span class="text-sm text-indigo-700 dark:text-indigo-300 font-medium"><span x-text="selected.length"></span> item terpilih</span>
                        <div class="flex gap-2">
                            <button type="submit" name="action" value="restore" class="px-3 py-1.5 bg-emerald-600 text-white rounded text-xs font-bold hover:bg-emerald-700 transition-colors">Pulihkan Pilihan</button>
                            <button type="submit" name="action" value="force_delete" class="px-3 py-1.5 bg-red-600 text-white rounded text-xs font-bold hover:bg-red-700 transition-colors" onclick="return confirm('Hapus permanen ' + selected.length + ' item terpilih?')">Hapus Permanen Pilihan</button>
                        </div>
                    </div>

                <table class="min-w-full divide-y divide-slate-100 dark:divide-zinc-800">
                    <thead class="bg-slate-50 dark:bg-zinc-800/40">
                        <tr>
                            <th class="px-4 py-4 w-10 text-center"><input type="checkbox" x-model="selectAll" x-on:change="selected = selectAll ? Array.from($el.closest('table').querySelectorAll('tbody input[type=checkbox]')).map(cb => cb.value) : []" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"></th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase">Keterangan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase">Nominal</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase">Tgl Dihapus</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @forelse($trashedData['transaksi'] as $item)
                        <tr>
                            <td class="px-4 py-4 w-10 text-center"><input type="checkbox" name="ids[]" value="{{ $item->id }}" x-model="selected" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"></td>
                            <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">Trans: {{ $item->kode_transaksi }}<br><span class="text-xs text-slate-400 font-normal">Siswa: {{ $item->pembayaranSiswa->pesertaDidik->nama_lengkap ?? 'N/A' }}</span></td>
                            <td class="px-6 py-4 text-sm font-bold text-emerald-600">Rp {{ number_format($item->nominal,0,',','.') }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $item->deleted_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <form action="{{ route('admin.recycle-bin.restore', ['type' => 'transaksi', 'id' => $item->id]) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded shadow-sm text-xs font-bold hover:bg-emerald-100">Restore</button>
                                    </form>
                                    <form action="{{ route('admin.recycle-bin.force-delete', ['type' => 'transaksi', 'id' => $item->id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus permanen? Tidak bisa dikembalikan lagi!');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-50 text-red-600 rounded shadow-sm text-xs font-bold hover:bg-red-100">Hapus Permanen</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">Recycle Bin kosong.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                            </form>
</div>

            <!-- TAB: PEMASUKAN -->
            <div x-show="tab === 'pemasukan'" style="display: none;" class="overflow-x-auto">
                <form action="{{ route('admin.recycle-bin.bulk') }}" method="POST" x-data="{ selected: [], selectAll: false }">
                    @csrf
                    <input type="hidden" name="type" value="pemasukan">
                    <div x-show="selected.length > 0" x-transition class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-lg p-3 mb-4 flex items-center justify-between" x-cloak>
                        <span class="text-sm text-indigo-700 dark:text-indigo-300 font-medium"><span x-text="selected.length"></span> item terpilih</span>
                        <div class="flex gap-2">
                            <button type="submit" name="action" value="restore" class="px-3 py-1.5 bg-emerald-600 text-white rounded text-xs font-bold hover:bg-emerald-700 transition-colors">Pulihkan Pilihan</button>
                            <button type="submit" name="action" value="force_delete" class="px-3 py-1.5 bg-red-600 text-white rounded text-xs font-bold hover:bg-red-700 transition-colors" onclick="return confirm('Hapus permanen ' + selected.length + ' item terpilih?')">Hapus Permanen Pilihan</button>
                        </div>
                    </div>

                <table class="min-w-full divide-y divide-slate-100 dark:divide-zinc-800">
                    <thead class="bg-slate-50 dark:bg-zinc-800/40">
                        <tr>
                            <th class="px-4 py-4 w-10 text-center"><input type="checkbox" x-model="selectAll" x-on:change="selected = selectAll ? Array.from($el.closest('table').querySelectorAll('tbody input[type=checkbox]')).map(cb => cb.value) : []" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"></th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase">Keterangan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase">Nominal</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase">Tgl Dihapus</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @forelse($trashedData['pemasukan'] as $item)
                        <tr>
                            <td class="px-4 py-4 w-10 text-center"><input type="checkbox" name="ids[]" value="{{ $item->id }}" x-model="selected" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"></td>
                            <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">{{ $item->keterangan }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-emerald-600">Rp {{ number_format($item->nominal,0,',','.') }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $item->deleted_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <form action="{{ route('admin.recycle-bin.restore', ['type' => 'pemasukan', 'id' => $item->id]) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded shadow-sm text-xs font-bold hover:bg-emerald-100">Restore</button>
                                    </form>
                                    <form action="{{ route('admin.recycle-bin.force-delete', ['type' => 'pemasukan', 'id' => $item->id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus permanen? Tidak bisa dikembalikan lagi!');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-50 text-red-600 rounded shadow-sm text-xs font-bold hover:bg-red-100">Hapus Permanen</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">Recycle Bin kosong.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                            </form>
</div>

            <!-- TAB: PENGELUARAN -->
            <div x-show="tab === 'pengeluaran'" style="display: none;" class="overflow-x-auto">
                <form action="{{ route('admin.recycle-bin.bulk') }}" method="POST" x-data="{ selected: [], selectAll: false }">
                    @csrf
                    <input type="hidden" name="type" value="pengeluaran">
                    <div x-show="selected.length > 0" x-transition class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-lg p-3 mb-4 flex items-center justify-between" x-cloak>
                        <span class="text-sm text-indigo-700 dark:text-indigo-300 font-medium"><span x-text="selected.length"></span> item terpilih</span>
                        <div class="flex gap-2">
                            <button type="submit" name="action" value="restore" class="px-3 py-1.5 bg-emerald-600 text-white rounded text-xs font-bold hover:bg-emerald-700 transition-colors">Pulihkan Pilihan</button>
                            <button type="submit" name="action" value="force_delete" class="px-3 py-1.5 bg-red-600 text-white rounded text-xs font-bold hover:bg-red-700 transition-colors" onclick="return confirm('Hapus permanen ' + selected.length + ' item terpilih?')">Hapus Permanen Pilihan</button>
                        </div>
                    </div>

                <table class="min-w-full divide-y divide-slate-100 dark:divide-zinc-800">
                    <thead class="bg-slate-50 dark:bg-zinc-800/40">
                        <tr>
                            <th class="px-4 py-4 w-10 text-center"><input type="checkbox" x-model="selectAll" x-on:change="selected = selectAll ? Array.from($el.closest('table').querySelectorAll('tbody input[type=checkbox]')).map(cb => cb.value) : []" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"></th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase">Keterangan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase">Nominal</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase">Tgl Dihapus</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @forelse($trashedData['pengeluaran'] as $item)
                        <tr>
                            <td class="px-4 py-4 w-10 text-center"><input type="checkbox" name="ids[]" value="{{ $item->id }}" x-model="selected" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"></td>
                            <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">{{ $item->keterangan }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-rose-600">Rp {{ number_format($item->nominal,0,',','.') }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $item->deleted_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <form action="{{ route('admin.recycle-bin.restore', ['type' => 'pengeluaran', 'id' => $item->id]) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded shadow-sm text-xs font-bold hover:bg-emerald-100">Restore</button>
                                    </form>
                                    <form action="{{ route('admin.recycle-bin.force-delete', ['type' => 'pengeluaran', 'id' => $item->id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus permanen? Tidak bisa dikembalikan lagi!');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-50 text-red-600 rounded shadow-sm text-xs font-bold hover:bg-red-100">Hapus Permanen</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">Recycle Bin kosong.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                            </form>
</div>

            <!-- TAB: UJIAN CBT -->
            <div x-show="tab === 'ujian'" style="display: none;" class="overflow-x-auto">
                <form action="{{ route('admin.recycle-bin.bulk') }}" method="POST" x-data="{ selected: [], selectAll: false }">
                    @csrf
                    <input type="hidden" name="type" value="ujian">
                    <div x-show="selected.length > 0" x-transition class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-lg p-3 mb-4 flex items-center justify-between" x-cloak>
                        <span class="text-sm text-indigo-700 dark:text-indigo-300 font-medium"><span x-text="selected.length"></span> item terpilih</span>
                        <div class="flex gap-2">
                            <button type="submit" name="action" value="restore" class="px-3 py-1.5 bg-emerald-600 text-white rounded text-xs font-bold hover:bg-emerald-700 transition-colors">Pulihkan Pilihan</button>
                            <button type="submit" name="action" value="force_delete" class="px-3 py-1.5 bg-red-600 text-white rounded text-xs font-bold hover:bg-red-700 transition-colors" onclick="return confirm('Hapus permanen ' + selected.length + ' item terpilih?')">Hapus Permanen Pilihan</button>
                        </div>
                    </div>

                <table class="min-w-full divide-y divide-slate-100 dark:divide-zinc-800">
                    <thead class="bg-slate-50 dark:bg-zinc-800/40">
                        <tr>
                            <th class="px-4 py-4 w-10 text-center"><input type="checkbox" x-model="selectAll" x-on:change="selected = selectAll ? Array.from($el.closest('table').querySelectorAll('tbody input[type=checkbox]')).map(cb => cb.value) : []" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"></th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase">Judul Ujian</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase">Tgl Dihapus</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @forelse($trashedData['ujian'] as $item)
                        <tr>
                            <td class="px-4 py-4 w-10 text-center"><input type="checkbox" name="ids[]" value="{{ $item->id }}" x-model="selected" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"></td>
                            <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">{{ $item->judul_ujian }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $item->deleted_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <form action="{{ route('admin.recycle-bin.restore', ['type' => 'ujian', 'id' => $item->id]) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded shadow-sm text-xs font-bold hover:bg-emerald-100">Restore</button>
                                    </form>
                                    <form action="{{ route('admin.recycle-bin.force-delete', ['type' => 'ujian', 'id' => $item->id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus permanen? Tidak bisa dikembalikan lagi!');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-50 text-red-600 rounded shadow-sm text-xs font-bold hover:bg-red-100">Hapus Permanen</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">Recycle Bin kosong.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
                </form>
</div>
@endsection
