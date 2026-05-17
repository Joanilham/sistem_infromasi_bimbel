@extends('layouts.admin')

@section('title', 'Kategori Pemasukan')

@section('content')
<div class="space-y-8 pb-12">
    {{-- Header Section --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6 transition-all hover:shadow-md">
        <div class="flex items-center gap-5">
            <a href="{{ route('keuangan.pemasukan.index') }}" 
               class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-zinc-800 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all active:scale-90 group"
               title="Kembali ke Daftar Pemasukan">
                <svg class="w-6 h-6 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Kategori Pemasukan</h1>
                <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Manajemen Pengelompokan Dana Masuk</p>
            </div>
        </div>
        <div class="shrink-0 hidden sm:block">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 ring-1 ring-indigo-100 dark:ring-indigo-900/30 shadow-inner">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 text-emerald-800 dark:text-emerald-400 rounded-3xl px-8 py-5 text-sm font-bold flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-white shrink-0 shadow-lg shadow-emerald-500/20">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Tambah Section --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
        <h2 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest mb-6 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
            Tambah Kategori Baru
        </h2>
        <form action="{{ route('keuangan.pemasukan.kategori.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4">
            @csrf
            <div class="flex-1 relative group">
                <input type="text" name="nama" value="{{ old('nama') }}" required autofocus
                    placeholder="Contoh: Pendaftaran, Modul..." 
                    class="w-full bg-slate-50 dark:bg-zinc-950 border-2 border-transparent rounded-2xl text-sm font-bold px-6 py-4 focus:ring-0 focus:border-indigo-500/30 focus:bg-white dark:focus:bg-zinc-900 transition-all outline-none">
                <div class="absolute inset-y-0 right-4 flex items-center opacity-0 group-focus-within:opacity-100 transition-opacity">
                    <kbd class="px-2 py-1 bg-slate-200 dark:bg-zinc-800 rounded text-[10px] font-black text-slate-500 tracking-tighter">ENTER</kbd>
                </div>
            </div>
            <button type="submit" 
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs px-8 py-4 rounded-2xl shadow-xl shadow-indigo-600/20 transition-all active:scale-95 uppercase tracking-widest flex items-center justify-center gap-3 shrink-0">
                <span>Simpan Kategori</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            </button>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="px-8 py-6 bg-slate-50/50 dark:bg-zinc-800/20 border-b border-slate-100 dark:border-zinc-800">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Daftar Kategori Terdaftar</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-indigo-600 dark:bg-indigo-900/80 text-[10px] uppercase tracking-[0.2em] text-white font-black">
                    <tr>
                        <th class="px-6 py-4 text-left w-24 border-r border-white/10">
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
                        <th class="px-6 py-4 text-left border-r border-white/10">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama', 'order' => request('sort') == 'nama' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-between group">
                                Nama Kategori
                                <span class="transition-all {{ request('sort') == 'nama' ? 'opacity-100' : 'opacity-30 group-hover:opacity-100' }}">
                                    @if(request('sort') == 'nama' && request('order') == 'asc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7"/></svg>
                                    @elseif(request('sort') == 'nama' && request('order') == 'desc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M7 10l5-5 5 5M7 14l5 5 5-5"/></svg>
                                    @endif
                                </span>
                            </a>
                        </th>
                        <th class="px-6 py-4 text-center border-r border-white/10">Populasi Data</th>
                        <th class="px-6 py-4 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900">
                    @forelse($kategoris as $k)
                        <tr class="hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all even:bg-slate-50/30 dark:even:bg-zinc-800/30 group">
                            <td class="px-6 py-5 text-slate-400 font-bold text-xs border-r border-slate-100 dark:border-zinc-800 text-center">
                                #{{ $k->id }}
                            </td>
                            <td class="px-6 py-5 border-r border-slate-100 dark:border-zinc-800">
                                <div class="flex items-center gap-4">
                                    <div class="w-2 h-2 rounded-full bg-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    <span class="font-black text-slate-800 dark:text-white">{{ $k->nama }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center border-r border-slate-100 dark:border-zinc-800">
                                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-slate-400 text-[10px] font-black uppercase ring-1 ring-slate-200 dark:ring-zinc-700 shadow-inner">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                    {{ $k->pemasukan_count }} Record
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center">
                                    <form action="{{ route('keuangan.pemasukan.kategori.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 hover:bg-rose-600 hover:text-white transition-all active:scale-90 shadow-sm hover:shadow-rose-500/20"
                                            title="Hapus Kategori">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-24 text-center">
                                <div class="w-24 h-24 bg-slate-50 dark:bg-zinc-800 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8 text-5xl shadow-inner animate-bounce duration-[3s]">
                                    📂
                                </div>
                                <h3 class="font-black text-slate-900 dark:text-white text-xl tracking-tight uppercase">Belum Ada Kategori</h3>
                                <p class="text-slate-400 text-sm mt-3 font-bold max-w-xs mx-auto leading-relaxed">Silahkan tambahkan kategori baru di atas untuk memulai pengelompokan dana masuk.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kategoris->count() > 0)
            <div class="px-8 py-4 bg-slate-50/50 dark:bg-zinc-800/20 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">
                Total {{ $kategoris->count() }} Kategori Terdaftar
            </div>
        @endif
    </div>
</div>
@endsection
