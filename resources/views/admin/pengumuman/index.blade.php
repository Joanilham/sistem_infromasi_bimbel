@extends('layouts.admin')

@section('title', 'Berita & Informasi')

@section('content')
<div class="space-y-6" x-data="ajaxTable()">

    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Berita & Informasi</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-2">Kelola berita, informasi, atau pengumuman seputar bimbel.</p>
        </div>
        <a href="{{ route('admin.pengumuman.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-indigo-500/20 active:scale-95 shrink-0">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Berita
        </a>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50 relative">
            
            {{-- Loading Overlay --}}
            <div x-show="isLoading" class="absolute inset-0 z-50 bg-white/50 dark:bg-zinc-900/50 backdrop-blur-sm flex items-center justify-center transition-opacity duration-300" style="display: none;">
                <div class="bg-white dark:bg-zinc-800 p-4 rounded-2xl shadow-xl border border-slate-100 dark:border-zinc-700 flex items-center gap-3">
                    <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm font-bold text-slate-700 dark:text-slate-200">Memuat data...</span>
                </div>
            </div>

            <form @submit.prevent="fetchData" action="{{ route('admin.pengumuman.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center justify-end gap-4 w-full">
                <div class="flex items-center gap-2 w-full sm:w-auto shrink-0">
                    <div class="relative group flex-1 md:w-64">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari judul berita..." class="w-full bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-2.5 pl-10 pr-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-colors shadow-sm shadow-indigo-500/30 shrink-0">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search']))
                        <a href="{{ route('admin.pengumuman.index') }}" 
                           class="flex items-center gap-2 bg-slate-100 dark:bg-zinc-800 hover:bg-rose-100 dark:hover:bg-rose-900/40 text-slate-600 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 px-4 py-2.5 rounded-xl text-sm font-bold transition-all shrink-0"
                           title="Reset Filter">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div id="ajax-table-body" class="overflow-x-auto" @click="if($event.target.closest('th a')) { navigate($event, $event.target.closest('a').href) }">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-zinc-800/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">Berita</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @forelse($pengumumans as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors group">
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 font-medium">
                                {{ $pengumumans->firstItem() + $loop->index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-zinc-800 overflow-hidden shrink-0 flex items-center justify-center border border-slate-200 dark:border-zinc-700">
                                        @if($item->foto)
                                            <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 8M6 12h.01M6 16h.01M12 12h.01M12 16h.01M18 12h.01M18 16h.01" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-1">{{ $item->judul }}</p>
                                        <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400 mt-1 line-clamp-1">{{ Str::limit($item->isi, 60) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.pengumuman.toggle-active', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $item->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 dark:bg-zinc-800 dark:text-slate-400 hover:bg-slate-200' }} transition-colors">
                                        {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.pengumuman.edit', $item->id) }}" class="w-8 h-8 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 dark:text-indigo-400 flex items-center justify-center transition-colors tooltip" data-tip="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.pengumuman.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-900/30 dark:hover:bg-rose-900/50 dark:text-rose-400 flex items-center justify-center transition-colors tooltip" data-tip="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 dark:bg-zinc-800 mb-4">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 8M6 12h.01M6 16h.01M12 12h.01M12 16h.01M18 12h.01M18 16h.01" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Belum ada berita / informasi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengumumans->hasPages())
            <div id="ajax-pagination" class="p-6 border-t border-slate-100 dark:border-zinc-800"
                 @click="if($event.target.closest('nav[role=navigation] a')) { navigate($event, $event.target.closest('a').href) }">
                {{ $pengumumans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
