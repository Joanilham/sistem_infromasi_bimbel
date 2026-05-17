@extends('layouts.admin')

@section('title', 'Guru Keluar')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Guru Keluar</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm font-medium">Monitoring dan rekap data tenaga pengajar yang sudah tidak aktif di lembaga.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('manajemen-guru.keluar.export') }}"
               class="inline-flex items-center gap-3 bg-white dark:bg-zinc-800 hover:bg-slate-50 dark:hover:bg-zinc-700 text-slate-700 dark:text-slate-200 font-black text-xs px-6 py-4 rounded-2xl shadow-sm border border-slate-200 dark:border-zinc-700 transition-all active:scale-95">
                <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export Excel
            </a>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">

        {{-- Toolbar --}}
        <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-6 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/30 dark:bg-zinc-900/30">
            <div class="flex items-center gap-4">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Tampilkan</label>
                <form method="GET" id="perPageForm">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="per_page" onchange="document.getElementById('perPageForm').submit()"
                        class="bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-xs font-black py-2 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        @foreach([10, 25, 50, 100] as $n)
                            <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <form method="GET" class="flex gap-2">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                <div class="relative group">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama, nip, email…"
                        class="w-64 bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-2.5 pl-11 pr-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <button type="submit"
                    class="bg-slate-900 dark:bg-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100 text-white font-black text-xs px-6 py-2.5 rounded-xl transition-all active:scale-95 shadow-lg shadow-slate-900/10">
                    Cari
                </button>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
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
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'order' => request('sort') == 'name' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-between group">
                                Nama Guru
                                <span class="transition-all {{ request('sort') == 'name' ? 'opacity-100' : 'opacity-30 group-hover:opacity-100' }}">
                                    @if(request('sort') == 'name' && request('order') == 'asc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7"/></svg>
                                    @elseif(request('sort') == 'name' && request('order') == 'desc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M7 10l5-5 5 5M7 14l5 5 5-5"/></svg>
                                    @endif
                                </span>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left border border-white/20">NIP</th>
                        <th class="px-4 py-3 text-left border border-white/20">Mata Pelajaran</th>
                        <th class="px-4 py-3 text-left border border-white/20">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'tanggal_keluar', 'order' => request('sort') == 'tanggal_keluar' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-between group">
                                Status Keluar
                                <span class="transition-all {{ request('sort') == 'tanggal_keluar' ? 'opacity-100' : 'opacity-30 group-hover:opacity-100' }}">
                                    @if(request('sort') == 'tanggal_keluar' && request('order') == 'asc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7"/></svg>
                                    @elseif(request('sort') == 'tanggal_keluar' && request('order') == 'desc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M7 10l5-5 5 5M7 14l5 5 5-5"/></svg>
                                    @endif
                                </span>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-center w-24 border border-white/20">Opsi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900">
                    @forelse($gurus as $i => $guru)
                        <tr class="hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all even:bg-slate-50/50 dark:even:bg-zinc-800/30">
                            <td class="px-4 py-3 text-slate-500 font-bold text-xs border border-slate-200 dark:border-zinc-800 text-center">
                                @php
                                    $offset = method_exists($gurus, 'firstItem') ? ($gurus->firstItem() - 1) : 0;
                                @endphp
                                #{{ $offset + $loop->iteration }}
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-zinc-800 flex items-center justify-center shrink-0 grayscale opacity-60 ring-1 ring-slate-200 transition-all">
                                        <span class="text-slate-600 dark:text-slate-400 font-black text-xs">{{ substr($guru->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white leading-tight transition-colors">{{ $guru->name }}</p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $guru->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300 font-bold font-mono text-xs border border-slate-200 dark:border-zinc-800">
                                {{ $guru->nip ?? '-' }}
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-slate-400 text-[10px] font-black uppercase ring-1 ring-slate-200">
                                    {{ $guru->matapelajaran }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex flex-col gap-1 items-start">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 text-[10px] font-black uppercase ring-1 ring-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></span>
                                        {{ $guru->tanggal_keluar ? \Carbon\Carbon::parse($guru->tanggal_keluar)->format('d/m/Y') : 'Tanpa Tanggal' }}
                                    </span>
                                    <p class="text-[9px] text-slate-400 font-bold tracking-tight line-clamp-1 italic max-w-[200px]">"{{ $guru->alasan_keluar ?? '-' }}"</p>
                                </div>
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('manajemen-guru.edit', $guru->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-200 transition-all border border-amber-200/50 shadow-sm"
                                       title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center border border-slate-200 dark:border-zinc-800">
                                <div class="text-4xl mb-3">📭</div>
                                <p class="font-bold text-slate-600 dark:text-slate-300">Tidak ada data guru keluar</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Pagination --}}
        <div class="px-6 py-4 border-t border-slate-100 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <p class="text-xs text-slate-500 dark:text-slate-400">
                @if(method_exists($gurus, 'total'))
                    Menampilkan
                    <span class="font-bold text-slate-700 dark:text-slate-300">{{ $gurus->firstItem() }}</span>
                    –
                    <span class="font-bold text-slate-700 dark:text-slate-300">{{ $gurus->lastItem() }}</span>
                    dari <span class="font-bold text-slate-700 dark:text-slate-300">{{ $gurus->total() }}</span> entri
                @else
                    Menampilkan <span class="font-bold">{{ $gurus->count() }}</span> entri
                @endif
            </p>
            @if(method_exists($gurus, 'hasPages') && $gurus->hasPages())
                {{ $gurus->withQueryString()->links() }}
            @endif
        </div>
    </div>

</div>
@endsection