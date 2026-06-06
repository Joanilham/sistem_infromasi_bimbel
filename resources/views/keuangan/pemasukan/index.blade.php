@extends('layouts.admin')

@section('title', 'Pemasukan')

@section('content')
<div class="space-y-6" x-data="ajaxTable()">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 sm:p-8 border border-slate-100 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Pemasukan</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm">Catatan seluruh pemasukan di luar biaya bimbingan reguler siswa.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 text-emerald-800 dark:text-emerald-400 rounded-3xl px-8 py-4 text-sm font-bold flex items-center gap-3">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            {{ session('success') }}
        </div>
    @endif

    {{-- Arus Kas Summary Cards --}}
    <div id="ajax-summary-cards" class="grid grid-cols-1 {{ $selectedKantorName == 'Semua Cabang' ? 'md:grid-cols-2' : '' }} gap-6">
        {{-- Card 1: Pemasukan Cabang Ini --}}
        <div class="bg-indigo-600 dark:bg-indigo-900 border border-indigo-500/50 dark:border-indigo-800 rounded-3xl p-6 text-white flex items-center justify-between overflow-hidden relative">
            <div class="relative z-10">
                <div class="text-[10px] font-bold uppercase tracking-widest text-indigo-200">Pemasukan - {{ $selectedKantorName }}</div>
                <div class="text-3xl sm:text-4xl font-bold mt-2">Rp {{ number_format($totalCabangIni, 0, ',', '.') }}</div>
                <div class="text-xs text-indigo-200 mt-2 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Cabang: {{ $selectedKantorName }}
                </div>
            </div>
            <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-white/10 text-white shrink-0 text-2xl">
                🏢
            </div>
        </div>

        @if($selectedKantorName == 'Semua Cabang')
        {{-- Card 2: Pemasukan Seluruh Cabang --}}
        <div class="bg-emerald-500 dark:bg-emerald-800 border border-emerald-400/50 dark:border-emerald-700 rounded-3xl p-6 text-white flex items-center justify-between overflow-hidden relative">
            <div class="relative z-10">
                <div class="text-[10px] font-bold uppercase tracking-widest text-emerald-100">Pemasukan Seluruh Cabang</div>
                <div class="text-3xl sm:text-4xl font-bold mt-2">Rp {{ number_format($totalSeluruhCabang, 0, ',', '.') }}</div>
                <div class="text-xs text-emerald-100 mt-2 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                    Akumulasi nasional (seluruh cabang)
                </div>
            </div>
            <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-white/10 text-white shrink-0 text-2xl">
                🌍
            </div>
        </div>
        @endif
    </div>

    {{-- Form Tambah Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 sm:p-8 border border-slate-100 dark:border-zinc-800">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-0.5 bg-emerald-500"></div>
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Entri Pemasukan Baru</h2>
        </div>
        <form action="{{ route('keuangan.pemasukan.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal Transaksi</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required 
                        class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 rounded-xl text-sm py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-colors">
                </div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kategori Pemasukan</label>
                        <a href="{{ route('keuangan.pemasukan.kategori.index') }}" 
                           class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest text-white hover:text-white bg-indigo-500 hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-700 transition-colors px-3 py-1.5 rounded-lg shadow-sm">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Kelola Kategori
                        </a>
                    </div>
                    <select name="kategori_id" required 
                        class="no-tomselect w-full bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 rounded-xl text-sm py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-colors">
                        <option value="">-- Pilih --</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nominal (IDR)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-bold">Rp</span>
                        <input type="number" name="nominal" value="{{ old('nominal') }}" min="1" required placeholder="0" 
                            class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 rounded-xl text-sm py-3 pl-10 pr-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-colors">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Keterangan Tambahan</label>
                    <input type="text" name="keterangan" value="{{ old('keterangan') }}" placeholder="Contoh: Donasi Alumni…" 
                        class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 rounded-xl text-sm py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-colors">
                </div>
            </div>
            <div class="flex justify-end pt-4 border-t border-slate-100 dark:border-zinc-800">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-8 py-3 rounded-xl transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>

    {{-- Table Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-slate-100 dark:border-zinc-800 overflow-hidden">
        
        {{-- Toolbar Filter (Ala Manajemen Guru) --}}
        <div class="p-6 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50 relative">
            
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

            <form @submit.prevent="fetchData" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                
                {{-- Left: Dropdown Filters & Dates --}}
                <div class="flex flex-wrap items-center gap-3">
                    {{-- Per Page --}}
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden">
                        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Lihat</span>
                        </div>
                        <select name="per_page" @change="fetchData"
                            class="no-tomselect bg-transparent border-none text-xs font-black focus:ring-0 py-2 pl-3 pr-8 text-slate-800 dark:text-white cursor-pointer h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors">
                            @foreach([10, 25, 50, 100] as $n)
                                <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Cabang Filter --}}
                    @if($isSuperAdmin)
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden">
                        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Cabang</span>
                        </div>
                        <select name="kantor_id" @change="fetchData"
                            class="no-tomselect bg-transparent border-none text-xs font-bold focus:ring-0 py-2 pl-3 pr-8 text-slate-800 dark:text-white cursor-pointer h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors max-w-[150px] truncate">
                            <option value="">Semua Cabang</option>
                            @foreach($kantors as $kantor)
                                <option value="{{ $kantor->id }}" {{ $kantorId == $kantor->id ? 'selected' : '' }}>{{ $kantor->nama_kantor }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- Kategori Filter --}}
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden hidden sm:flex">
                        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Kategori</span>
                        </div>
                        <select name="kategori_id" @change="fetchData"
                            class="no-tomselect bg-transparent border-none text-xs font-bold focus:ring-0 py-2 pl-3 pr-8 text-slate-800 dark:text-white cursor-pointer h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors max-w-[150px] truncate">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal Start Filter --}}
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden hidden lg:flex">
                        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Mulai</span>
                        </div>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="bg-transparent border-none text-xs font-bold focus:ring-0 py-2 px-3 text-slate-800 dark:text-white h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors">
                    </div>

                    {{-- Tanggal End Filter --}}
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden hidden lg:flex">
                        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Sampai</span>
                        </div>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="bg-transparent border-none text-xs font-bold focus:ring-0 py-2 px-3 text-slate-800 dark:text-white h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors">
                    </div>
                </div>

                {{-- Right: Search & Reset --}}
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <div class="relative group flex-1 md:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari kategori / keterangan..."
                            class="w-full bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-2.5 pl-10 pr-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm">
                        <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-colors shadow-sm shadow-indigo-500/30">
                        Filter
                    </button>
                    @if(request()->anyFilled(['kantor_id', 'kategori_id', 'search', 'start_date', 'end_date']))
                        <a href="{{ route('keuangan.pemasukan.index') }}"
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
                    @forelse($pemasukan as $p)
                        <tr class="hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all even:bg-slate-50/50 dark:even:bg-zinc-800/30">
                            <td class="px-4 py-3 text-slate-500 font-bold text-xs border border-slate-200 dark:border-zinc-800 text-center">
                                #{{ $p->id }}
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <p class="font-bold text-slate-900 dark:text-white">{{ $p->tanggal->format('d/m/Y') }}</p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Selesai</p>
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 text-[9px] font-black uppercase ring-1 ring-emerald-100">
                                    {{ $p->kategori?->nama }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-black text-emerald-600 dark:text-emerald-400 border border-slate-200 dark:border-zinc-800">
                                Rp {{ number_format($p->nominal, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-slate-500 dark:text-slate-400 font-medium border border-slate-200 dark:border-zinc-800 leading-tight">
                                {{ $p->keterangan ?? '-' }}
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex items-center justify-center gap-2">
                                    @if(strtolower(auth()->user()->level) === 'super admin')
                                        <a href="{{ route('keuangan.pemasukan.edit', $p->id) }}" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded bg-amber-50 dark:bg-amber-900/10 text-amber-600 dark:text-amber-400 hover:bg-amber-100 transition-all"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="{{ route('keuangan.pemasukan.destroy', $p->id) }}" method="POST" onsubmit="event.preventDefault(); confirmDelete('Hapus Data?', 'Data ini tidak dapat dikembalikan!', this)">
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
                                    💰
                                </div>
                                <h3 class="font-black text-slate-900 dark:text-white text-lg">Pemasukan Kosong</h3>
                                <p class="text-slate-400 text-sm mt-2 font-medium max-w-xs mx-auto">Belum ada catatan pemasukan lainnya yang tersimpan.</p>
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
                Menampilkan <span class="text-slate-900 dark:text-white">{{ $pemasukan->firstItem() ?? 0 }}</span> – <span class="text-slate-900 dark:text-white">{{ $pemasukan->lastItem() ?? 0 }}</span> dari <span class="text-slate-900 dark:text-white">{{ $pemasukan->total() ?? 0 }}</span> Transaksi
            </p>
            @if($pemasukan->hasPages())
                <div class="flex justify-end">
                    {{ $pemasukan->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    </div>
</div>
@endsection
