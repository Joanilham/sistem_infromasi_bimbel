@extends('layouts.admin')

@section('title', 'Pembayaran Siswa')

@section('content')
<div class="space-y-6" x-data="ajaxTable()">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 sm:p-8 border border-slate-100 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Pembayaran Siswa</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm">Kelola administrasi pembayaran dan tunggakan biaya bimbingan belajar.</p>
        </div>
        <div class="shrink-0">
            <span class="inline-flex items-center gap-2 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 font-bold text-xs px-5 py-3 rounded-xl border border-indigo-100 dark:border-indigo-800">
                <span class="relative flex h-2 w-2">
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                Monitoring Pembayaran
            </span>
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
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden hidden sm:flex h-[42px]">
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

                    {{-- Kelompok Belajar --}}
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden hidden md:flex h-[42px]">
                        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Kelompok</span>
                        </div>
                        <select name="kelompok_id" @change="fetchData"
                            class="no-tomselect bg-transparent border-none text-xs font-bold focus:ring-0 py-2 pl-3 pr-8 text-slate-800 dark:text-white cursor-pointer h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors max-w-[150px] truncate w-full">
                            <option value="">Semua Kelompok</option>
                            @foreach($kelompoks as $k)
                                <option value="{{ $k->id }}" {{ request('kelompok_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelompok }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Right: Search & Reset --}}
                <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full md:w-auto shrink-0">
                    <x-table.search :alpine="true" placeholder="Cari Nama / NISN..." />
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-colors shadow-sm shadow-indigo-500/30">
                        Filter
                    </button>
                    @if(request()->anyFilled(['paket_id', 'kelompok_id', 'search']))
                        <a href="{{ route('keuangan.pembayaran.index') }}" 
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
                        <th class="px-4 py-3 text-left border border-white/20">Program</th>
                        <th class="px-4 py-3 text-left border border-white/20 w-32">
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
                        <th class="px-4 py-3 text-center border border-white/20">Status Keuangan</th>
                        <th class="px-4 py-3 text-center w-40 border border-white/20">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900">
                    @forelse($siswa as $i => $s)
                        <tr class="hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all even:bg-slate-50/50 dark:even:bg-zinc-800/30">
                            <td class="px-4 py-3 text-slate-500 font-bold text-xs border border-slate-200 dark:border-zinc-800 text-center">
                                #{{ $s->id }}
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-50 dark:bg-zinc-800 flex items-center justify-center shrink-0 ring-1 ring-slate-200 transition-all">
                                        <span class="text-[10px] font-black uppercase {{ $s->jenis_kelamin === 'L' ? 'text-blue-500' : 'text-rose-500' }}">
                                            {{ $s->jenis_kelamin }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white leading-tight transition-colors">{{ $s->nama_lengkap }}</p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $s->nomor_induk ?? $s->nisn }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <p class="text-xs font-bold text-slate-600 dark:text-slate-300 leading-tight">{{ $s->paketBimbingan?->nama_paket ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                @if($s->pembayaran && $s->pembayaran->batas_waktu)
                                    @php $overdue = $s->pembayaran->batas_waktu->isPast() && !$s->pembayaran->lunas; @endphp
                                    <div class="flex flex-col">
                                        <span class="font-bold text-xs {{ $overdue ? 'text-rose-600 dark:text-rose-400' : 'text-slate-700 dark:text-slate-300' }}">
                                            {{ $s->pembayaran->batas_waktu->format('d/m/Y') }}
                                        </span>
                                        @if($overdue)
                                            <span class="text-[8px] font-black text-rose-500 uppercase tracking-widest mt-0.5 animate-pulse">Jatuh Tempo</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400 font-semibold italic text-[10px]">Belum Diatur</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">
                                @if($s->pembayaran)
                                    @php 
                                        $lunas = $s->pembayaran->lunas; 
                                        $overdue = $s->pembayaran->batas_waktu && $s->pembayaran->batas_waktu->isPast() && !$lunas;
                                    @endphp
                                    @if($lunas)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 text-[9px] font-black uppercase ring-1 ring-emerald-100 shadow-sm">
                                            <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                                            Lunas
                                        </span>
                                    @elseif($overdue)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 text-[9px] font-black uppercase ring-1 ring-rose-100 shadow-sm">
                                            <span class="w-1 h-1 rounded-full bg-rose-500 animate-pulse"></span>
                                            Tunggakan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 text-[9px] font-black uppercase ring-1 ring-amber-100 shadow-sm">
                                            <span class="w-1 h-1 rounded-full bg-amber-500 animate-pulse"></span>
                                            Belum Lunas
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-slate-50 dark:bg-zinc-800 text-slate-400 dark:text-slate-500 text-[9px] font-black uppercase ring-1 ring-slate-100 shadow-sm">
                                        Data Kosong
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex items-center justify-center">
                                    <a href="{{ route('keuangan.pembayaran.show', $s->id) }}"
                                       class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white text-[9px] font-black uppercase tracking-widest px-4 py-2 rounded shadow-lg shadow-indigo-500/20 transition-all active:scale-95">
                                        Detail / Bayar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center border border-slate-200 dark:border-zinc-800">
                                <div class="w-20 h-20 bg-slate-50 dark:bg-zinc-800 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner">
                                    💸
                                </div>
                                <h3 class="font-black text-slate-900 dark:text-white text-lg">Siswa Tidak Ditemukan</h3>
                                <p class="text-slate-400 text-sm mt-2 font-medium max-w-xs mx-auto">Tidak ada data siswa yang sesuai dengan kriteria pencarian Anda.</p>
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
                Menampilkan <span class="text-slate-900 dark:text-white">{{ $siswa->firstItem() ?? 0 }}</span> – <span class="text-slate-900 dark:text-white">{{ $siswa->lastItem() ?? 0 }}</span> dari <span class="text-slate-900 dark:text-white">{{ $siswa->total() ?? 0 }}</span> Siswa
            </p>
            @if($siswa->hasPages())
                <div class="flex justify-end">
                    {{ $siswa->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
