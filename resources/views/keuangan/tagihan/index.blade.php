@extends('layouts.admin')

@section('title', 'Tagihan Siswa')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Daftar Tagihan</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm font-medium">Monitoring tunggakan dan kekurangan pembayaran biaya bimbingan belajar.</p>
        </div>
        <div class="shrink-0">
            <span class="inline-flex items-center gap-3 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 font-black text-xs px-6 py-3.5 rounded-2xl ring-2 ring-rose-100/50 dark:ring-rose-900/30 shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                </span>
                Urutkan Jatuh Tempo
            </span>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">
        
        {{-- Toolbar --}}
        <div class="p-6 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/30 dark:bg-zinc-900/30">
            <form method="GET" class="flex flex-col lg:flex-row items-end gap-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 w-full">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Tampilkan</label>
                        <select name="per_page" onchange="this.form.submit()"
                            class="w-full bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-xs font-black py-2.5 px-4 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all">
                            @foreach([10,25,50,100] as $n)<option value="{{ $n }}" {{ $perPage == $n ? 'selected' : '' }}>{{ $n }}</option>@endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Paket Bimbingan</label>
                        <select name="paket_id" onchange="this.form.submit()"
                            class="w-full bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-xs font-black py-2.5 px-4 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all">
                            <option value="">Semua Paket</option>
                            @foreach($pakets as $p)
                                <option value="{{ $p->id }}" {{ request('paket_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_paket }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Cari Nama/NISN</label>
                        <div class="relative group">
                            <input type="text" name="search" value="{{ $search }}" placeholder="Ketik keyword..."
                                class="w-full bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-2.5 pl-11 pr-4 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all">
                            <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-rose-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 w-full lg:w-auto">
                    <button type="submit" class="flex-1 lg:flex-none bg-slate-900 dark:bg-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100 text-white font-black text-xs px-8 py-3 rounded-xl transition-all active:scale-95 shadow-lg shadow-slate-900/10 uppercase tracking-widest">
                        Filter
                    </button>
                    @if(request()->anyFilled(['paket_id', 'search']))
                        <a href="{{ route('keuangan.tagihan.index') }}" class="bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 hover:bg-rose-100 p-3 rounded-xl transition-all" title="Reset Filter">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                        </a>
                    @endif
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
                        <th class="px-4 py-3 text-left w-32 border border-white/20">Jatuh Tempo</th>
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
                                #{{ $t->id }}
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                @if($t->batas_waktu)
                                    <div class="flex flex-col">
                                        <span class="font-bold {{ $overdue ? 'text-rose-600 dark:text-rose-400' : 'text-slate-700 dark:text-slate-300' }}">
                                            {{ $t->batas_waktu->format('d/m/Y') }}
                                        </span>
                                        @if($overdue)
                                            <span class="text-[8px] font-black text-rose-500 uppercase tracking-widest mt-0.5">Overdue!</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400 font-bold italic text-[10px]">Belum Diatur</span>
                                @endif
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
                                            {{ $siswa->nama_lengkap }}
                                        </a>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $siswa->paketBimbingan?->nama_paket ?? 'Program Umum' }}</p>
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
        <div class="px-8 py-6 border-t border-slate-100 dark:border-zinc-800 bg-slate-50/30 dark:bg-zinc-900/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
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
