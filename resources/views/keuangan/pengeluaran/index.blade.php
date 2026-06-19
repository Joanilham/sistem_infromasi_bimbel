@extends('layouts.admin')

@section('title', 'Pengeluaran')

@section('content')
<div class="space-y-6" x-data="ajaxTable()">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 sm:p-8 border border-slate-100 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Pengeluaran</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm">Catatan seluruh pengeluaran operasional dan biaya lainnya.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 text-emerald-800 dark:text-emerald-400 rounded-3xl px-8 py-4 text-sm font-bold flex items-center gap-3">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800 text-rose-800 dark:text-rose-400 rounded-3xl px-8 py-4 text-sm font-bold flex flex-col gap-2">
            <div class="flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                <span>Terdapat kesalahan pada isian form:</span>
            </div>
            <ul class="list-disc list-inside ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Arus Kas Summary Cards & Form --}}
    @php
        $hasTwoSummaries = $selectedKantorName == 'Semua Cabang';
    @endphp
    <div id="ajax-summary-cards" class="grid grid-cols-1 lg:grid-cols-{{ $hasTwoSummaries ? '4' : '3' }} gap-6">
        
        {{-- Card 1: Pengeluaran Cabang Ini --}}
        <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-6 sm:p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col justify-between relative overflow-hidden group">
            <div class="absolute -right-8 -top-8 w-40 h-40 bg-rose-50 dark:bg-rose-900/20 rounded-full blur-3xl opacity-60 group-hover:opacity-100 group-hover:scale-110 transition duration-700"></div>
            
            <div class="relative z-10 flex justify-between items-start">
                <div class="w-14 h-14 rounded-[1rem] bg-rose-50 dark:bg-rose-900/30 text-rose-600 flex items-center justify-center shadow-sm">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            
            <div class="relative z-10 mt-8">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Total Pengeluaran</h3>
                <p class="text-xs font-bold text-rose-500 dark:text-rose-400 mb-3 line-clamp-1">{{ $selectedKantorName }}</p>
                <div class="text-3xl xl:text-4xl font-black text-slate-900 dark:text-white leading-none tracking-tight">Rp {{ number_format($totalCabangIni, 0, ',', '.') }}</div>
            </div>
        </div>

        @if($hasTwoSummaries)
        {{-- Card 2: Pengeluaran Seluruh Cabang --}}
        <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-6 sm:p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col justify-between relative overflow-hidden group">
            <div class="absolute -right-8 -top-8 w-40 h-40 bg-amber-50 dark:bg-amber-900/20 rounded-full blur-3xl opacity-60 group-hover:opacity-100 group-hover:scale-110 transition duration-700"></div>
            
            <div class="relative z-10 flex justify-between items-start">
                <div class="w-14 h-14 rounded-[1rem] bg-amber-50 dark:bg-amber-900/30 text-amber-600 flex items-center justify-center shadow-sm">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            
            <div class="relative z-10 mt-8">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Total Pengeluaran</h3>
                <p class="text-xs font-bold text-amber-500 dark:text-amber-400 mb-3 line-clamp-1">Seluruh Cabang</p>
                <div class="text-3xl xl:text-4xl font-black text-slate-900 dark:text-white leading-none tracking-tight">Rp {{ number_format($totalSeluruhCabang, 0, ',', '.') }}</div>
            </div>
        </div>
        @endif

        {{-- Form Entri Pengeluaran --}}
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 rounded-[2rem] p-6 sm:p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col justify-between">
            <h2 class="font-black text-slate-900 dark:text-white mb-6 flex items-center justify-between gap-3 text-xs uppercase tracking-wider">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-rose-100 dark:bg-rose-900/30 text-rose-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    </span>
                    Catat Pengeluaran
                </div>
                <a href="{{ route('keuangan.pengeluaran.kategori.index') }}" class="text-[9px] font-black text-rose-600 hover:text-rose-700 dark:text-rose-400 transition-colors">Kelola Kategori &rarr;</a>
            </h2>
            <form action="{{ route('keuangan.pengeluaran.store') }}" method="POST" class="mt-auto">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5 mb-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-rose-500/20 outline-none transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kategori</label>
                        <select name="kategori_id" required class="no-tomselect w-full bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-rose-500/20 outline-none transition-colors">
                            <option value="">-- Pilih --</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nominal (Rp)</label>
                        <input type="text" inputmode="numeric" name="nominal" value="{{ old('nominal') }}" required placeholder="0" class="nominal-format w-full bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-rose-500/20 outline-none transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Keterangan</label>
                        <input type="text" name="keterangan" value="{{ old('keterangan') }}" placeholder="Contoh: Beli spidol..." class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-rose-500/20 outline-none transition-colors">
                    </div>
                </div>
                <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-black px-6 py-3.5 rounded-xl text-sm transition-colors shadow-sm shadow-rose-500/30 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Simpan Pengeluaran
                </button>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-slate-100 dark:border-zinc-800 overflow-hidden">
        
        {{-- Toolbar Filter (Ala Manajemen Guru) --}}
        <div class="p-6 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50 relative">
            
            {{-- Loading Overlay --}}
            <x-table.loading-overlay />

            <form @submit.prevent="fetchData" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                
                {{-- Left: Dropdown Filters & Dates --}}
                <div class="flex flex-wrap items-center gap-3">
                    {{-- Per Page --}}
                    <x-table.filter-limit :alpine="true" />

                    {{-- Cabang Filter --}}
                    @if($isSuperAdmin)
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden h-[42px]">
                        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Cabang</span>
                        </div>
                        <select name="kantor_id" @change="fetchData"
                            class="no-tomselect bg-transparent border-none text-xs font-bold focus:ring-0 py-2 pl-3 pr-8 text-slate-800 dark:text-white cursor-pointer h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors max-w-[150px] truncate w-full">
                            <option value="">Semua Cabang</option>
                            @foreach($kantors as $kantor)
                                <option value="{{ $kantor->id }}" {{ $kantorId == $kantor->id ? 'selected' : '' }}>{{ $kantor->nama_kantor }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- Kategori Filter --}}
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden hidden sm:flex h-[42px]">
                        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Kategori</span>
                        </div>
                        <select name="kategori_id" @change="fetchData"
                            class="no-tomselect bg-transparent border-none text-xs font-bold focus:ring-0 py-2 pl-3 pr-8 text-slate-800 dark:text-white cursor-pointer h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors max-w-[150px] truncate w-full">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal Start Filter --}}
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden hidden lg:flex h-[42px]">
                        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Mulai</span>
                        </div>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="bg-transparent border-none text-xs font-bold focus:ring-0 py-2 px-3 text-slate-800 dark:text-white h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors w-full">
                    </div>

                    {{-- Tanggal End Filter --}}
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden hidden lg:flex h-[42px]">
                        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Sampai</span>
                        </div>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="bg-transparent border-none text-xs font-bold focus:ring-0 py-2 px-3 text-slate-800 dark:text-white h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors w-full">
                    </div>
                </div>

                {{-- Right: Search & Reset --}}
                <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full md:w-auto shrink-0">
                    <x-table.search :alpine="true" placeholder="Cari kategori / keterangan..." />
                    <button type="submit"
                        class="bg-rose-600 hover:bg-rose-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-colors shadow-sm shadow-rose-500/30">
                        Filter
                    </button>
                    @if(request()->anyFilled(['kantor_id', 'kategori_id', 'search', 'start_date', 'end_date']))
                        <a href="{{ route('keuangan.pengeluaran.index') }}"
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
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'tanggal', 'order' => request('sort') == 'tanggal' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-between group">
                                Tanggal
                                <span class="transition-all {{ request('sort') == 'tanggal' ? 'opacity-100' : 'opacity-30 group-hover:opacity-100' }}">
                                    @if(request('sort') == 'tanggal' && request('order') == 'asc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7"/></svg>
                                    @elseif(request('sort') == 'tanggal' && request('order') == 'desc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M7 10l5-5 5 5M7 14l5 5 5-5"/></svg>
                                    @endif
                                </span>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left border border-white/20">Kategori</th>
                        <th class="px-4 py-3 text-left border border-white/20">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'nominal', 'order' => request('sort') == 'nominal' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-between group">
                                Nominal
                                <span class="transition-all {{ request('sort') == 'nominal' ? 'opacity-100' : 'opacity-30 group-hover:opacity-100' }}">
                                    @if(request('sort') == 'nominal' && request('order') == 'asc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7"/></svg>
                                    @elseif(request('sort') == 'nominal' && request('order') == 'desc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M7 10l5-5 5 5M7 14l5 5 5-5"/></svg>
                                    @endif
                                </span>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left border border-white/20">Keterangan</th>
                        <th class="px-4 py-3 text-center w-32 border border-white/20">Opsi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900">
                    @forelse($pengeluaran as $p)
                        <tr class="hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all even:bg-slate-50/50 dark:even:bg-zinc-800/30">
                            <td class="px-4 py-3 text-slate-500 font-bold text-xs border border-slate-200 dark:border-zinc-800 text-center">
                                #{{ $p->id }}
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <p class="font-bold text-slate-900 dark:text-white">{{ $p->tanggal->format('d/m/Y') }}</p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Selesai</p>
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 text-[9px] font-black uppercase ring-1 ring-rose-100">
                                    {{ $p->kategori?->nama }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-black text-rose-600 dark:text-rose-400 border border-slate-200 dark:border-zinc-800">
                                Rp {{ number_format($p->nominal, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-slate-500 dark:text-slate-400 font-medium border border-slate-200 dark:border-zinc-800 leading-tight">
                                {{ $p->keterangan ?? '-' }}
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex items-center justify-center gap-2">
                                    @if(strtolower(auth()->user()->level) === 'super admin')
                                        <a href="{{ route('keuangan.pengeluaran.edit', $p->id) }}" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded bg-amber-50 dark:bg-amber-900/10 text-amber-600 dark:text-amber-400 hover:bg-amber-100 transition-all"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="{{ route('keuangan.pengeluaran.destroy', $p->id) }}" method="POST" onsubmit="event.preventDefault(); confirmDelete('Hapus Data?', 'Data ini tidak dapat dikembalikan!', this)">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded bg-rose-50 dark:bg-rose-900/10 text-rose-600 dark:text-rose-400 hover:bg-rose-100 transition-all" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 dark:text-slate-500 italic">No Action</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-24 text-center border border-slate-200 dark:border-zinc-800">
                                <div class="w-20 h-20 bg-slate-50 dark:bg-zinc-800 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner">
                                    💸
                                </div>
                                <h3 class="font-black text-slate-900 dark:text-white text-lg">Pengeluaran Kosong</h3>
                                <p class="text-slate-400 text-sm mt-2 font-medium max-w-xs mx-auto">Belum ada catatan pengeluaran operasional yang tersimpan.</p>
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
                Menampilkan <span class="text-slate-900 dark:text-white">{{ $pengeluaran->firstItem() ?? 0 }}</span> – <span class="text-slate-900 dark:text-white">{{ $pengeluaran->lastItem() ?? 0 }}</span> dari <span class="text-slate-900 dark:text-white">{{ $pengeluaran->total() ?? 0 }}</span> Transaksi
            </p>
            @if($pengeluaran->hasPages())
                <div class="flex justify-end">
                    {{ $pengeluaran->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    </div>
</div>
@endsection
