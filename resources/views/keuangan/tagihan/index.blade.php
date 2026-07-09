@extends('layouts.admin')

@section('title', 'Tagihan Siswa')

@section('content')
<div class="space-y-6" x-data="ajaxTable()">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 sm:p-8 border border-slate-100 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Daftar Tagihan</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm">Monitoring tunggakan dan kekurangan pembayaran biaya bimbingan belajar.</p>
        </div>
        <div class="shrink-0">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'batas_waktu', 'order' => 'asc']) }}" 
                class="inline-flex items-center gap-2 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 font-bold text-xs px-5 py-3 rounded-xl border border-rose-100 dark:border-rose-900/30 transition-colors">
                <span class="relative flex h-2 w-2">
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                </span>
                Urutkan Jatuh Tempo
            </a>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-slate-100 dark:border-zinc-800 overflow-hidden">
        
        {{-- Toolbar Filter (Ala Manajemen Guru) --}}
        <div class="p-6 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50 relative">
            
            {{-- Loading Overlay --}}
            <x-table.loading-overlay />

            <form @submit.prevent="fetchData" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                
                {{-- Left: Dropdown Filters --}}
                <div class="flex flex-wrap items-center gap-3">
                    {{-- Per Page --}}
                    <x-table.filter-limit :alpine="true" />

                    {{-- Paket Bimbingan --}}
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-rose-500/20 focus-within:border-rose-500 transition-all overflow-hidden hidden sm:flex h-[42px]">
                        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Paket</span>
                        </div>
                        <select name="paket_id" @change="fetchData"
                            class="no-tomselect bg-transparent border-none text-xs font-bold focus:ring-0 py-2 pl-3 pr-8 text-slate-800 dark:text-white cursor-pointer h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors max-w-[150px] truncate w-full">
                            <option value="">Semua Paket</option>
                            @foreach($pakets as $p)
                                <option value="{{ $p->id }}" {{ request('paket_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_paket }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Right: Search & Reset --}}
                <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full md:w-auto shrink-0">
                    <x-table.search :alpine="true" placeholder="Cari Nama / NISN..." />
                    <button type="submit"
                        class="bg-rose-600 hover:bg-rose-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-colors shadow-sm shadow-rose-500/30">
                        Filter
                    </button>
                    @if(request()->anyFilled(['paket_id', 'search']))
                        <a href="{{ route('keuangan.tagihan.index') }}" 
                           class="flex items-center gap-2 bg-slate-100 dark:bg-zinc-800 hover:bg-rose-100 dark:hover:bg-rose-900/40 text-slate-600 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 px-4 py-2.5 rounded-xl text-sm font-bold transition-all"
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
                        <th class="px-4 py-3 text-left min-w-[220px] border border-white/20">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'batas_waktu', 'order' => request('sort') == 'batas_waktu' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-between group">
                                Jatuh Tempo
                                <span class="transition-all {{ request('sort') == 'batas_waktu' ? 'opacity-100' : 'opacity-30 group-hover:opacity-100' }}">
                                    @if(request('sort') == 'batas_waktu' && request('order') == 'asc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7"/></svg>
                                    @elseif(request('sort') == 'batas_waktu' && request('order') == 'desc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M7 10l5-5 5 5M7 14l5 5 5-5"/></svg>
                                    @endif
                                </span>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left border border-white/20">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama_lengkap', 'order' => request('sort') == 'nama_lengkap' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-between group">
                                Siswa
                                <span class="transition-all {{ request('sort') == 'nama_lengkap' ? 'opacity-100' : 'opacity-30 group-hover:opacity-100' }}">
                                    @if(request('sort') == 'nama_lengkap' && request('order') == 'asc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7"/></svg>
                                    @elseif(request('sort') == 'nama_lengkap' && request('order') == 'desc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M7 10l5-5 5 5M7 14l5 5 5-5"/></svg>
                                    @endif
                                </span>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-right border border-white/20">Total Biaya</th>
                        <th class="px-4 py-3 text-right border border-white/20">Terbayar</th>
                        <th class="px-4 py-3 text-right border border-white/20">Sisa Tagihan</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900">
                    @forelse($tagihan as $i => $t)
                        @php
                            $siswa   = $t->pesertaDidik;
                            $overdue = $t->batas_waktu && $t->batas_waktu->isPast();
                        @endphp
                        <tr class="hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all even:bg-slate-50/50 dark:even:bg-zinc-800/30 {{ $overdue ? 'bg-rose-50/30 dark:bg-rose-900/10' : '' }}">
                            <td class="px-4 py-3 text-slate-500 font-bold text-xs border border-slate-200 dark:border-zinc-800 text-center">
                                {{ $loop->iteration + ($tagihan->currentPage() - 1) * $tagihan->perPage() }}
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <form action="{{ route('keuangan.pembayaran.update', $t->id) }}" method="POST" class="flex flex-wrap items-center gap-2">
                                    @csrf @method('PUT')
                                    <input type="date" name="batas_waktu" 
                                        value="{{ $t->batas_waktu ? $t->batas_waktu->format('Y-m-d') : '' }}" 
                                        onchange="this.form.submit()"
                                        class="bg-slate-50 hover:bg-slate-100 dark:bg-zinc-800/50 dark:hover:bg-zinc-800 border-0 rounded-lg text-xs font-black px-2.5 py-1.5 focus:ring-2 focus:ring-rose-500/20 text-slate-700 dark:text-slate-300 transition-all cursor-pointer">
                                    @if($overdue)
                                        <span class="px-1.5 py-0.5 rounded bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400 text-[8px] font-black uppercase tracking-wider shrink-0 animate-pulse border border-rose-200/50">Jatuh Tempo</span>
                                    @endif
                                </form>
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-slate-50 dark:bg-zinc-800 flex items-center justify-center shrink-0 ring-1 ring-slate-200 transition-all">
                                        <span class="text-[10px] font-black uppercase text-indigo-500">
                                            {{ substr($siswa->nama_lengkap, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <a href="{{ route('keuangan.pembayaran.show', $siswa->id) }}" class="font-bold text-slate-900 dark:text-white hover:text-indigo-600 transition-all block leading-tight">
                                            @highlight($siswa->nama_lengkap)
                                        </a>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">@highlight($siswa->paketBimbingan?->nama_paket ?? 'Program Umum')</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-zinc-800">
                                Rp {{ number_format($t->total_harus_dibayar, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-emerald-600 dark:text-emerald-400 border border-slate-200 dark:border-zinc-800">
                                Rp {{ number_format($t->total_terbayar, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-right border border-slate-200 dark:border-zinc-800">
                                @if($t->kekurangan < 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 text-[9px] font-black uppercase ring-1 ring-emerald-100 shadow-sm">
                                        Lebih: Rp {{ number_format(abs($t->kekurangan), 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 text-[9px] font-black uppercase ring-1 ring-rose-100 shadow-sm">
                                        Rp {{ number_format($t->kekurangan, 0, ',', '.') }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center border border-slate-200 dark:border-zinc-800">
                                <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-900/10 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner">
                                    🎉
                                </div>
                                <h3 class="font-black text-slate-900 dark:text-white text-lg">Semua Tagihan Lunas</h3>
                                <p class="text-slate-400 text-sm mt-2 font-medium max-w-xs mx-auto">Luar biasa! Tidak ada siswa yang memiliki tunggakan saat ini.</p>
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
                Menampilkan <span class="text-slate-900 dark:text-white">{{ $tagihan->firstItem() ?? 0 }}</span> – <span class="text-slate-900 dark:text-white">{{ $tagihan->lastItem() ?? 0 }}</span> dari <span class="text-slate-900 dark:text-white">{{ $tagihan->total() ?? 0 }}</span> Tagihan
            </p>
            @if($tagihan->hasPages())
                <div class="flex justify-end">
                    {{ $tagihan->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
