@extends('layouts.admin')

@section('title', 'Kelompok Belajar')

@section('content')
<div class="space-y-6" x-data="ajaxTable()">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Kelompok Belajar</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm font-medium">Kelola data pembagian kelompok belajar peserta didik secara terpusat.</p>
        </div>
        <a href="{{ route('kelompok-belajar.create') }}"
           class="inline-flex items-center gap-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-sm px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95 shrink-0 group">
            <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Kelompok
        </a>
    </div>

    {{-- Alert Handling --}}
    @if(session('error'))
        <div class="bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 p-4 rounded-2xl font-bold text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Table Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">

        {{-- Toolbar Filter & Search --}}
        <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50 relative">

            {{-- Loading Overlay --}}
            <x-table.loading-overlay />

            <form @submit.prevent="fetchData" method="GET" action="{{ route('kelompok-belajar.index') }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
                
                {{-- Per Page --}}
                <x-table.filter-limit :alpine="true" />

                {{-- Search & Reset --}}
                <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full md:w-auto shrink-0">
                    <x-table.search :alpine="true" placeholder="Cari nama kelompok…" />
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-colors shadow-sm shadow-indigo-500/30 shrink-0">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search']))
                        <a href="{{ route('kelompok-belajar.index') }}" 
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
                                ID
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
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama_kelompok', 'order' => request('sort') == 'nama_kelompok' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-between group">
                                Nama Kelompok Belajar
                                <span class="transition-all {{ request('sort') == 'nama_kelompok' ? 'opacity-100' : 'opacity-30 group-hover:opacity-100' }}">
                                    @if(request('sort') == 'nama_kelompok' && request('order') == 'asc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7"/></svg>
                                    @elseif(request('sort') == 'nama_kelompok' && request('order') == 'desc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M7 10l5-5 5 5M7 14l5 5 5-5"/></svg>
                                    @endif
                                </span>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-center border border-white/20">Jumlah Siswa</th>
                        <th class="px-4 py-3 text-center w-40 border border-white/20">Opsi Kelola</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900">
                    @forelse($kelompokBelajars as $i => $kelompok)
                        <tr class="hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all even:bg-slate-50/50 dark:even:bg-zinc-800/30">
                            <td class="px-4 py-3 text-slate-500 font-bold text-xs border border-slate-200 dark:border-zinc-800 text-center">
                                {{ (method_exists($kelompokBelajars, 'firstItem') && $kelompokBelajars->firstItem() ? $kelompokBelajars->firstItem() - 1 : 0) + $loop->iteration }}
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-zinc-800 flex items-center justify-center shrink-0 ring-1 ring-slate-100 dark:ring-zinc-800 group-hover:ring-indigo-100 transition-all">
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <span class="font-bold text-slate-900 dark:text-white leading-tight transition-colors">@highlight($kelompok->nama_kelompok)</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">
                                <span class="px-3 py-1 text-[10px] uppercase tracking-widest font-black rounded bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800/50">
                                    {{ $kelompok->peserta_didiks_count ?? 0 }} Siswa
                                </span>
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('kelompok-belajar.edit', $kelompok->id) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded bg-amber-50 dark:bg-amber-900/10 text-amber-600 dark:text-amber-400 hover:bg-amber-100 transition-all border border-amber-200/50 shadow-sm"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    @include('admin.kelompok_belajar.delete')
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-24 text-center border border-slate-200 dark:border-zinc-800">
                                <div class="w-20 h-20 bg-slate-50 dark:bg-zinc-800 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner">👥</div>
                                <h3 class="font-black text-slate-900 dark:text-white text-lg">Kelompok Kosong</h3>
                                <p class="text-slate-400 text-sm mt-2 font-medium max-w-xs mx-auto">Belum ada data kelompok belajar yang terdaftar dalam sistem.</p>
                                <a href="{{ route('kelompok-belajar.create') }}" class="mt-8 inline-flex items-center gap-3 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest px-8 py-4 rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/20 active:scale-95">
                                    + Tambah Kelompok
                                </a>
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
                @if(method_exists($kelompokBelajars, 'total'))
                    Menampilkan <span class="text-slate-900 dark:text-white">{{ $kelompokBelajars->firstItem() ?? 0 }}</span> – <span class="text-slate-900 dark:text-white">{{ $kelompokBelajars->lastItem() ?? 0 }}</span> dari <span class="text-slate-900 dark:text-white">{{ $kelompokBelajars->total() ?? 0 }}</span> Kelompok
                @else
                    Menampilkan <span class="text-slate-900 dark:text-white">{{ $kelompokBelajars->count() }}</span> Kelompok
                @endif
            </p>
            @if(method_exists($kelompokBelajars, 'hasPages') && $kelompokBelajars->hasPages())
                <div class="flex justify-end">
                    {{ $kelompokBelajars->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
