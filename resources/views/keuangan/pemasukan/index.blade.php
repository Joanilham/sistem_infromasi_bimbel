@extends('layouts.admin')

@section('title', 'Pemasukan')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Pemasukan</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm font-medium">Catatan seluruh pemasukan di luar biaya bimbingan reguler siswa.</p>
        </div>
        <a href="{{ route('keuangan.pemasukan.kategori.index') }}" 
           class="inline-flex items-center gap-3 bg-slate-100 dark:bg-zinc-800 hover:bg-slate-200 dark:hover:bg-zinc-700 text-slate-700 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest px-8 py-4 rounded-2xl transition-all active:scale-95 shadow-sm border border-slate-200/50 dark:border-zinc-700/50">
            🗂 Kelola Kategori
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 text-emerald-800 dark:text-emerald-400 rounded-3xl px-8 py-4 text-sm font-bold flex items-center gap-3">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Tambah Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>
        <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
            <span class="w-8 h-[2px] bg-emerald-500"></span>
            Entri Pemasukan Baru
        </h2>
        <form action="{{ route('keuangan.pemasukan.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 relative">
            @csrf
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Transaksi</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required 
                    class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
            </div>
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kategori Pemasukan</label>
                <select name="kategori_id" required 
                    class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    <option value="">-- Pilih --</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nominal (IDR)</label>
                <div class="relative">
                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-black text-sm">Rp</span>
                    <input type="number" name="nominal" value="{{ old('nominal') }}" min="1" required placeholder="0" 
                        class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-black py-4 pl-12 pr-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                </div>
            </div>
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Keterangan Tambahan</label>
                <input type="text" name="keterangan" value="{{ old('keterangan') }}" placeholder="Contoh: Donasi Alumni…" 
                    class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
            </div>
            <div class="sm:col-span-2 lg:col-span-4 flex justify-end pt-4 border-t border-slate-50 dark:border-zinc-800 mt-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black text-sm px-12 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95 flex items-center gap-3 group">
                    <svg class="w-5 h-5 transition-transform group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>

    {{-- Table Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">
        
        {{-- Toolbar --}}
        <div class="p-6 flex flex-col lg:flex-row lg:items-end justify-between gap-6 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/30 dark:bg-zinc-900/30">
            <form method="GET" class="flex flex-col sm:flex-row sm:items-end gap-4 flex-1">
                {{-- Per Page --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Tampilkan</label>
                    <select name="per_page" onchange="this.form.submit()" 
                        class="bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-xs font-black py-2 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        @foreach([10,25,50,100] as $n)<option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>@endforeach
                    </select>
                </div>

                {{-- Kategori Filter --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Kategori</label>
                    <select name="kategori_id" onchange="this.form.submit()"
                        class="bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-xs font-black py-2 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Rentang Tanggal --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()"
                        class="bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-xs font-black py-2 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()"
                        class="bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-xs font-black py-2 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                </div>

                <div class="flex flex-col gap-1.5 flex-1 max-w-sm sm:ml-auto">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Pencarian</label>
                    <div class="flex gap-2">
                        <div class="relative group flex-1">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori / keterangan…"
                                class="w-full bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-2.5 pl-11 pr-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                            <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <button type="submit" class="bg-slate-900 dark:bg-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100 text-white font-black text-xs px-6 py-2.5 rounded-xl transition-all active:scale-95 shadow-lg shadow-slate-900/10">
                            Cari
                        </button>
                        @if(request()->anyFilled(['search', 'kategori_id', 'start_date', 'end_date']))
                            <a href="{{ route('keuangan.pemasukan.index') }}" 
                               class="bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 hover:bg-rose-100 p-2.5 rounded-xl transition-all"
                               title="Reset Filter">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

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
                                        <form action="{{ route('keuangan.pemasukan.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
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
        <div class="px-8 py-6 border-t border-slate-100 dark:border-zinc-800 bg-slate-50/30 dark:bg-zinc-900/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
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
