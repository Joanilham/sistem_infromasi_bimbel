@extends('layouts.admin')

@section('title', 'Peserta Didik Aktif')

@section('content')
<div class="space-y-6" x-data="ajaxTable()">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Peserta Didik Aktif</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm font-medium">Kelola data peserta didik yang sedang aktif belajar di lembaga.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto shrink-0">
            <a href="{{ route('peserta-didik.export') }}"
               class="inline-flex items-center justify-center w-full sm:w-auto gap-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-black text-xs px-6 py-4 rounded-2xl shadow-xl shadow-emerald-500/20 transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export Excel
            </a>
            <a href="{{ route('peserta-didik.create') }}"
               class="inline-flex items-center justify-center w-full sm:w-auto gap-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Peserta
            </a>
        </div>
    </div>

    {{-- Error/Success Alert Handling --}}
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 p-4 rounded-2xl font-bold text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 p-4 rounded-2xl font-bold text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Table Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">

        {{-- Toolbar Filter & Search --}}
        <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50 relative">

            {{-- Loading Overlay --}}
            <x-table.loading-overlay />

            <form @submit.prevent="fetchData" method="GET" class="flex flex-col gap-4 sm:gap-5">
                
                {{-- Top Row: Universal Controls --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
                    {{-- Per Page --}}
                    <x-table.filter-limit :alpine="true" />

                    {{-- Search & Reset --}}
                    <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full sm:w-auto shrink-0">
                        <x-table.search :alpine="true" placeholder="Cari nama, nisn, sekolah…" />
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-colors shadow-sm shadow-indigo-500/30 shrink-0">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'paket_id', 'kelompok_id', 'jenis_kelamin']))
                            <a href="{{ route('peserta-didik.index') }}" 
                               class="flex items-center gap-2 bg-slate-100 dark:bg-zinc-800 hover:bg-rose-100 dark:hover:bg-rose-900/40 text-slate-600 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 px-4 py-2.5 rounded-xl text-sm font-bold transition-all shrink-0"
                               title="Reset Filter">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Clear
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Bottom Row: Data Filters --}}
                <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-slate-100 dark:border-zinc-800/50">
                    {{-- Paket Filter --}}
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden">
                        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Program</span>
                        </div>
                        <select name="paket_id" @change="fetchData"
                            class="no-tomselect bg-transparent border-none text-xs font-bold focus:ring-0 py-2 pl-3 pr-8 text-slate-800 dark:text-white cursor-pointer h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors max-w-[120px] md:max-w-[160px] truncate">
                            <option value="">Semua Program</option>
                            @foreach($paketBimbingans as $p)
                                <option value="{{ $p->id }}" {{ request('paket_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_paket }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kelompok Filter --}}
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden">
                        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Kelas</span>
                        </div>
                        <select name="kelompok_id" @change="fetchData"
                            class="no-tomselect bg-transparent border-none text-xs font-bold focus:ring-0 py-2 pl-3 pr-8 text-slate-800 dark:text-white cursor-pointer h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors max-w-[100px] md:max-w-[140px] truncate">
                            <option value="">Semua Kelas</option>
                            @foreach($kelompokBelajars as $k)
                                <option value="{{ $k->id }}" {{ request('kelompok_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelompok }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- JK Filter --}}
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden">
                        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Jenis Kelamin</span>
                        </div>
                        <select name="jenis_kelamin" @change="fetchData"
                            class="no-tomselect bg-transparent border-none text-xs font-bold focus:ring-0 py-2 pl-3 pr-8 text-slate-800 dark:text-white cursor-pointer h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors">
                            <option value="">Semua Jenis Kelamin</option>
                            <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

            </form>
        </div>

        {{-- Table --}}
        <div id="ajax-table-body" class="overflow-x-auto" @click="if($event.target.closest('th a')) { navigate($event, $event.target.closest('a').href) }">
            <table class="w-full text-sm border-collapse border border-slate-200 dark:border-zinc-800">
                <thead class="bg-indigo-600 dark:bg-indigo-900/80 text-[10px] uppercase tracking-widest text-white font-black">
                    <tr>
                        <th class="px-4 py-3 text-left w-20 border border-white/20">
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
                                Nama Lengkap
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
                        <th class="px-4 py-3 text-left border border-white/20">NISN</th>
                        <th class="px-4 py-3 text-center w-20 border border-white/20">Jenis Kelamin</th>
                        <th class="px-4 py-3 text-left border border-white/20">Program & Kelas</th>
                        <th class="px-4 py-3 text-left border border-white/20">Kontak</th>
                        <th class="px-4 py-3 text-center w-32 border border-white/20">Opsi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900">
                    @forelse($pesertaDidiks as $i => $peserta)
                        <tr class="hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all even:bg-slate-50/50 dark:even:bg-zinc-800/30">
                            <td class="px-4 py-3 text-slate-500 font-bold text-xs border border-slate-200 dark:border-zinc-800 text-center">
                                #{{ $peserta->id }}
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center shrink-0 ring-1 ring-blue-100 transition-all">
                                        <span class="text-blue-600 dark:text-blue-400 font-black text-xs">{{ substr($peserta->nama_lengkap, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white leading-tight transition-colors">{{ $peserta->nama_lengkap }}</p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $peserta->asal_sekolah }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300 font-bold border border-slate-200 dark:border-zinc-800">
                                {{ $peserta->nisn }}
                            </td>
                            <td class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">
                                @if($peserta->jenis_kelamin == 'L')
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 font-black text-[10px] ring-1 ring-sky-200/50" title="Laki-Laki">L</span>
                                @elseif($peserta->jenis_kelamin == 'P')
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-pink-50 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400 font-black text-[10px] ring-1 ring-pink-200/50" title="Perempuan">P</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex flex-col gap-1 items-start">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-[9px] font-black uppercase tracking-tighter ring-1 ring-indigo-100">
                                        {{ $peserta->paketBimbingan->nama_paket ?? '-' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-slate-50 dark:bg-zinc-800 text-slate-500 dark:text-slate-400 text-[9px] font-black uppercase tracking-tighter ring-1 ring-slate-200">
                                        {{ $peserta->kelompokBelajar->nama_kelompok ?? 'Tanpa Kelas' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <p class="text-slate-700 dark:text-slate-200 font-bold text-xs">{{ $peserta->no_telepon ?? '-' }}</p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter truncate max-w-[150px] mt-0.5">{{ $peserta->alamat_lengkap ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex flex-wrap items-center justify-center gap-2">
                                    <a href="{{ route('peserta-didik.edit', $peserta->id) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded bg-amber-50 dark:bg-amber-900/10 text-amber-600 dark:text-amber-400 hover:bg-amber-100 transition-all border border-amber-200/50 shadow-sm"
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
                            <td colspan="7" class="px-8 py-24 text-center border border-slate-200 dark:border-zinc-800">
                                <div class="w-20 h-20 bg-slate-50 dark:bg-zinc-800 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner">🎓</div>
                                <h3 class="font-black text-slate-900 dark:text-white text-lg">Data Siswa Kosong</h3>
                                <p class="text-slate-400 text-sm mt-2 font-medium max-w-xs mx-auto">Belum ada data peserta didik aktif yang terdaftar di sistem.</p>
                                <a href="{{ route('peserta-didik.create') }}" class="mt-8 inline-flex items-center gap-2 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest px-8 py-4 rounded-2xl hover:bg-indigo-700 transition-all active:scale-95 shadow-xl shadow-indigo-500/20">
                                    + Tambah Siswa Baru
                                </a>
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
                Menampilkan <span class="text-slate-900 dark:text-white">{{ $pesertaDidiks->firstItem() ?? 0 }}</span> – <span class="text-slate-900 dark:text-white">{{ $pesertaDidiks->lastItem() ?? 0 }}</span> dari <span class="text-slate-900 dark:text-white">{{ $pesertaDidiks->total() ?? 0 }}</span> Siswa
            </p>
            @if($pesertaDidiks->hasPages())
                <div class="flex justify-end">
                    {{ $pesertaDidiks->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection