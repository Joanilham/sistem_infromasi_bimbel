@extends('layouts.admin')

@section('title', 'Pengaturan Kantor')

@section('content')
<div class="space-y-6" x-data="ajaxTable()">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Manajemen Kantor</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm font-medium">Daftar lokasi kantor cabang dan pusat Sistem Akademik.</p>
        </div>
        <button onclick="document.getElementById('modal-create').classList.remove('hidden')"
           class="inline-flex items-center gap-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95 group shrink-0">
            <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Kantor
        </button>
    </div>



    {{-- Table Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">

        {{-- Toolbar Filter & Search --}}
        <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50 relative">

            {{-- Loading Overlay --}}
            <x-table.loading-overlay />

            <form @submit.prevent="fetchData" method="GET" action="{{ route('kantor.index') }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
                
                {{-- Per Page --}}
                <x-table.filter-limit :alpine="true" />

                {{-- Search & Reset --}}
                <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full md:w-auto shrink-0">
                    <x-table.search :alpine="true" placeholder="Cari nama atau alamat…" />
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-colors shadow-sm shadow-indigo-500/30 shrink-0">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search']))
                        <a href="{{ route('kantor.index') }}" 
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

        {{-- Table --}}
        <div id="ajax-table-body" class="overflow-x-auto" @click="if($event.target.closest('th a')) { navigate($event, $event.target.closest('a').href) }">
            <table class="w-full text-sm border-collapse border border-slate-200 dark:border-zinc-800">
                <thead class="bg-indigo-600 dark:bg-indigo-900/80 text-[10px] uppercase tracking-widest text-white font-black">
                    <tr>
                        <th class="px-4 py-3 text-left w-24 border border-white/20">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'order' => request('sort') == 'id' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-between group">
                                No.
                                <span class="transition-all {{ request('sort') == 'id' ? 'opacity-100' : 'opacity-30 group-hover:opacity-100' }}">
                                    @if(request('sort') == 'id' && request('order') == 'asc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7"/></svg>
                                    @elseif(request('sort') == 'id' && request('order') == 'desc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M7 10l5-5 5 5M7 14l5 5 5-5"/></svg>
                                    @endif
                                </span>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left border border-white/20">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama_kantor', 'order' => request('sort') == 'nama_kantor' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-between group">
                                Nama Kantor
                                <span class="transition-all {{ request('sort') == 'nama_kantor' ? 'opacity-100' : 'opacity-30 group-hover:opacity-100' }}">
                                    @if(request('sort') == 'nama_kantor' && request('order') == 'asc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7"/></svg>
                                    @elseif(request('sort') == 'nama_kantor' && request('order') == 'desc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M7 10l5-5 5 5M7 14l5 5 5-5"/></svg>
                                    @endif
                                </span>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left border border-white/20">Alamat Lengkap</th>
                        <th class="px-4 py-3 text-center w-32 border border-white/20">Opsi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900">
                    @forelse($kantors as $i => $kantor)
                        <tr class="hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all even:bg-slate-50/50 dark:even:bg-zinc-800/30">
                            <td class="px-4 py-3 text-slate-400 font-bold text-xs font-mono border border-slate-200 dark:border-zinc-800 text-center">
                                #{{ str_pad($kantors->firstItem() + $i, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-4 py-3 font-bold text-slate-900 dark:text-white border border-slate-200 dark:border-zinc-800">
                                @highlight($kantor->nama_kantor)
                            </td>
                            <td class="px-4 py-3 text-slate-500 dark:text-slate-400 font-medium italic border border-slate-200 dark:border-zinc-800 leading-tight">
                                @highlight($kantor->alamat ?? 'Alamat belum diset')
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" onclick="editKantor(this)"
                                        data-id="{{ $kantor->id }}"
                                        data-nama="{{ $kantor->nama_kantor }}"
                                        data-alamat="{{ $kantor->alamat }}"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded bg-amber-50 dark:bg-amber-900/10 text-amber-600 dark:text-amber-400 hover:bg-amber-100 transition-all"
                                        title="Edit Kantor">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    @include('admin.kantor.delete')
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-24 text-center border border-slate-200 dark:border-zinc-800">
                                <div class="w-20 h-20 bg-slate-50 dark:bg-zinc-800 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner">
                                    🏢
                                </div>
                                <h3 class="font-black text-slate-900 dark:text-white text-lg">Kantor Belum Terdaftar</h3>
                                <p class="text-slate-400 text-sm mt-2 font-medium">Data kantor cabang akan muncul di sini setelah Anda menambahkannya.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Pagination --}}
        <div id="ajax-pagination" class="px-8 py-6 border-t border-slate-100 dark:border-zinc-800 bg-slate-50/30 dark:bg-zinc-900/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
             @click="if($event.target.closest('nav[role=navigation] a')) { navigate($event, $event.target.closest('a').href) }">
            <p class="text-xs text-slate-500 dark:text-slate-400 font-bold">
                Menampilkan <span class="text-slate-900 dark:text-white">{{ $kantors->firstItem() ?? 0 }}</span> – <span class="text-slate-900 dark:text-white">{{ $kantors->lastItem() ?? 0 }}</span> dari <span class="text-slate-900 dark:text-white">{{ $kantors->total() ?? 0 }}</span> Kantor
            </p>
            @if($kantors->hasPages())
                <div class="flex justify-end">
                    {{ $kantors->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

@include('admin.kantor.create')
@include('admin.kantor.edit')
@include('admin.kantor.script')
@endsection