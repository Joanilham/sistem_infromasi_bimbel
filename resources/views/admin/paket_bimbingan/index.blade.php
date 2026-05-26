@extends('layouts.admin')

@section('title', 'Paket Bimbingan')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Paket Bimbingan</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm font-medium">Kelola paket belajar dan harga promo untuk calon siswa.</p>
        </div>
        <a href="{{ route('paket-bimbingan.create') }}"
           class="inline-flex items-center gap-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-sm px-6 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95 shrink-0">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Paket Baru
        </a>
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
                        placeholder="Cari nama paket…"
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
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama_paket', 'order' => request('sort') == 'nama_paket' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-between group">
                                Detail Paket
                                <span class="transition-all {{ request('sort') == 'nama_paket' ? 'opacity-100' : 'opacity-30 group-hover:opacity-100' }}">
                                    @if(request('sort') == 'nama_paket' && request('order') == 'asc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7"/></svg>
                                    @elseif(request('sort') == 'nama_paket' && request('order') == 'desc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M7 10l5-5 5 5M7 14l5 5 5-5"/></svg>
                                    @endif
                                </span>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left border border-white/20">Harga</th>
                        <th class="px-4 py-3 text-left border border-white/20">Durasi</th>
                        <th class="px-4 py-3 text-center border border-white/20">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'urutan', 'order' => request('sort') == 'urutan' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-between group">
                                Status / Label
                                <span class="transition-all {{ request('sort') == 'urutan' ? 'opacity-100' : 'opacity-30 group-hover:opacity-100' }}">
                                    @if(request('sort') == 'urutan' && request('order') == 'asc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7"/></svg>
                                    @elseif(request('sort') == 'urutan' && request('order') == 'desc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M7 10l5-5 5 5M7 14l5 5 5-5"/></svg>
                                    @endif
                                </span>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-center w-32 border border-white/20">Opsi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900">
                    @forelse($paketBimbingans as $i => $paket)
                        <tr class="hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all even:bg-slate-50/50 dark:even:bg-zinc-800/30">
                            <td class="px-4 py-3 text-slate-500 font-bold text-xs border border-slate-200 dark:border-zinc-800 text-center">
                                #{{ str_pad((method_exists($paketBimbingans, 'firstItem') ? $paketBimbingans->firstItem() : 1) + $i, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex items-center gap-3">
                                    @if($paket->gambar_paket)
                                        <img src="{{ asset('storage/'.$paket->gambar_paket) }}" class="w-10 h-10 rounded-lg object-cover shadow-sm ring-1 ring-slate-200 dark:ring-zinc-700" alt="">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-[10px] ring-1 ring-indigo-100/50">
                                            {{ substr($paket->nama_paket, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white leading-tight">{{ $paket->nama_paket }}</p>
                                        @if($paket->deskripsi_singkat)
                                            <p class="text-[9px] text-slate-400 mt-0.5 line-clamp-1 font-medium">{{ $paket->deskripsi_singkat }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex flex-col">
                                    @if($paket->harga_coret)
                                        <span class="text-[9px] text-slate-400 line-through font-bold">
                                            Rp {{ number_format($paket->harga_coret, 0, ',', '.') }}
                                        </span>
                                    @endif
                                    <span class="text-xs font-black text-emerald-600 dark:text-emerald-400">
                                        Rp {{ number_format($paket->nominal, 0, ',', '.') }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                @if($paket->durasi_jumlah)
                                    <div class="flex items-center gap-1.5 text-slate-600 dark:text-slate-300 text-[10px] font-bold">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $paket->durasi_jumlah }} {{ $paket->durasi_satuan }}
                                    </div>
                                @else
                                    <span class="text-slate-300 dark:text-zinc-600 font-black text-xs">—</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">
                                <div class="flex flex-col items-center gap-1">
                                    @if($paket->label_populer)
                                        <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest
                                            {{ strtolower($paket->label_populer) === 'promo' ? 'bg-orange-500 text-white' : '' }}
                                            {{ strtolower($paket->label_populer) === 'populer' ? 'bg-purple-600 text-white' : '' }}
                                            {{ strtolower($paket->label_populer) === 'terlaris' ? 'bg-rose-600 text-white' : '' }}
                                            {{ !in_array(strtolower($paket->label_populer), ['promo','populer','terlaris']) ? 'bg-indigo-600 text-white' : '' }}">
                                            {{ $paket->label_populer }}
                                        </span>
                                    @endif
                                    @if($paket->is_featured)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-[9px] font-black uppercase ring-1 ring-blue-100">
                                            Featured
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('paket-bimbingan.edit', $paket->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded bg-amber-50 dark:bg-amber-900/10 text-amber-600 dark:text-amber-400 hover:bg-amber-100 transition-all shadow-sm border border-amber-200/50"
                                       title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    @include('admin.paket_bimbingan.delete')
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-24 text-center">
                                <div class="w-20 h-20 bg-slate-50 dark:bg-zinc-800 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner">📦</div>
                                <h3 class="font-black text-slate-900 dark:text-white text-lg">Belum Ada Paket</h3>
                                <p class="text-slate-400 text-sm mt-2 font-medium">Tambahkan paket bimbingan belajar pertama Anda.</p>
                                <a href="{{ route('paket-bimbingan.create') }}" class="mt-8 inline-flex items-center gap-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                    Tambah Paket
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Pagination --}}
        <div class="px-6 py-4 border-t border-slate-100 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <p class="text-xs text-slate-500 dark:text-slate-400">
                @if(method_exists($paketBimbingans, 'total'))
                    Menampilkan
                    <span class="font-bold text-slate-700 dark:text-slate-300">{{ $paketBimbingans->firstItem() }}</span>
                    <span class="font-bold text-slate-700 dark:text-slate-300">{{ $paketBimbingans->lastItem() }}</span>
                    dari <span class="font-bold text-slate-700 dark:text-slate-300">{{ $paketBimbingans->total() }}</span> entri
                @else
                    Menampilkan <span class="font-bold">{{ $paketBimbingans->count() }}</span> entri
                @endif
            </p>
            @if(method_exists($paketBimbingans, 'hasPages') && $paketBimbingans->hasPages())
                {{ $paketBimbingans->withQueryString()->links() }}
            @endif
        </div>
    </div>

</div>
@endsection