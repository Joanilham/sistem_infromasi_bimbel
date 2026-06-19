@extends('layouts.admin')

@section('title', 'Kategori Pengeluaran')

@section('content')
<div class="space-y-8 pb-12">
    {{-- Header Section --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6 transition-all hover:shadow-md">
        <div class="flex items-center gap-5">
            <a href="{{ route('keuangan.pengeluaran.index') }}" 
               class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-zinc-800 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all active:scale-90 group"
               title="Kembali ke Daftar Pengeluaran">
                <svg class="w-6 h-6 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Kategori Pengeluaran</h1>
                <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Manajemen Pengelompokan Biaya Keluar</p>
            </div>
        </div>
        <div class="shrink-0 hidden sm:block">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 dark:bg-rose-900/20 flex items-center justify-center text-rose-600 dark:text-rose-400 ring-1 ring-rose-100 dark:ring-rose-900/30 shadow-inner">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Form Tambah Section --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-6 sm:p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
        <h2 class="font-black text-slate-900 dark:text-white mb-6 flex items-center justify-between gap-3 text-xs uppercase tracking-wider">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-rose-100 dark:bg-rose-900/30 text-rose-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                </span>
                Tambah Kategori Baru
            </div>
        </h2>
        <form action="{{ route('keuangan.pengeluaran.kategori.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4 items-center">
            @csrf
            <div class="flex-1 relative group w-full">
                <input type="text" name="nama" value="{{ old('nama') }}" required autofocus
                    placeholder="Contoh: GAJI GURU, OPERASIONAL, LISTRIK..." 
                    class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 rounded-xl text-sm py-3 px-4 focus:ring-2 focus:ring-rose-500/20 outline-none transition-colors">
            </div>
            <button type="submit" 
                class="w-full sm:w-auto bg-rose-600 hover:bg-rose-700 text-white font-black px-6 py-3 rounded-xl text-sm transition-colors shadow-sm shadow-rose-500/30 flex items-center justify-center gap-2 shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Simpan Kategori</span>
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
                <thead class="bg-rose-600 dark:bg-rose-900/80 text-[10px] uppercase tracking-[0.2em] text-white font-black">
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
                        <tr class="hover:bg-rose-50/50 dark:hover:bg-rose-900/10 transition-all even:bg-slate-50/30 dark:even:bg-zinc-800/30 group">
                            <td class="px-6 py-5 text-slate-400 font-bold text-xs border-r border-slate-100 dark:border-zinc-800 text-center">
                                #{{ $k->id }}
                            </td>
                            <td class="px-6 py-5 border-r border-slate-100 dark:border-zinc-800">
                                <div class="flex items-center gap-4">
                                    <div class="w-2 h-2 rounded-full bg-rose-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    <span class="font-black text-slate-800 dark:text-white">{{ $k->nama }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center border-r border-slate-100 dark:border-zinc-800">
                                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-slate-400 text-[10px] font-black uppercase ring-1 ring-slate-200 dark:ring-zinc-700 shadow-inner">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    {{ $k->pengeluaran_count }} Record
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center">
                                    <form action="{{ route('keuangan.pengeluaran.kategori.destroy', $k->id) }}" method="POST" onsubmit="event.preventDefault(); confirmDelete('Hapus Kategori?', 'Kategori yang dihapus tidak dapat dikembalikan!', this)">
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
                                    📉
                                </div>
                                <h3 class="font-black text-slate-900 dark:text-white text-xl tracking-tight uppercase">Belum Ada Kategori</h3>
                                <p class="text-slate-400 text-sm mt-3 font-bold max-w-xs mx-auto leading-relaxed">Silahkan tambahkan kategori baru di atas untuk memulai pengelompokan dana keluar.</p>
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
