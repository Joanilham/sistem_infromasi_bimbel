@extends('layouts.admin')

@section('title', 'Peserta Didik Aktif')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Peserta Didik Aktif</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm font-medium">Kelola data peserta didik yang sedang aktif belajar di lembaga.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('peserta-didik.export') }}"
               class="inline-flex items-center gap-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-black text-sm px-6 py-4 rounded-2xl shadow-xl shadow-emerald-500/20 transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export Excel
            </a>
            <a href="{{ route('peserta-didik.create') }}"
               class="inline-flex items-center gap-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-sm px-6 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Peserta
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 text-emerald-800 dark:text-emerald-400 rounded-3xl px-8 py-4 text-sm font-bold flex items-center gap-3">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            {{ session('success') }}
        </div>
    @endif

    {{-- Table Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">

        {{-- Toolbar --}}
        <div class="p-6 flex flex-col gap-6 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/30 dark:bg-zinc-900/30">
            <form method="GET" class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 flex-1">
                    {{-- Per Page --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Tampilkan</label>
                        <select name="per_page" onchange="this.form.submit()"
                            class="bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-xs font-black py-2 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                            @foreach([10, 25, 50, 100] as $n)
                                <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Paket Filter --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Program</label>
                        <select name="paket_id" onchange="this.form.submit()"
                            class="bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-xs font-black py-2 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                            <option value="">Semua Program</option>
                            @foreach($paketBimbingans as $p)
                                <option value="{{ $p->id }}" {{ request('paket_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_paket }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kelompok Filter --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Kelas</label>
                        <select name="kelompok_id" onchange="this.form.submit()"
                            class="bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-xs font-black py-2 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                            <option value="">Semua Kelas</option>
                            @foreach($kelompokBelajars as $k)
                                <option value="{{ $k->id }}" {{ request('kelompok_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelompok }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- JK Filter --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Gender</label>
                        <select name="jenis_kelamin" onchange="this.form.submit()"
                            class="bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-xs font-black py-2 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                            <option value="">Semua</option>
                            <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="relative group flex-1 sm:flex-none">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama, nisn, sekolah…"
                            class="w-full sm:w-64 bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-2.5 pl-11 pr-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit"
                        class="bg-slate-900 dark:bg-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100 text-white font-black text-xs px-6 py-2.5 rounded-xl transition-all active:scale-95 shadow-lg shadow-slate-900/10">
                        Cari
                    </button>
                    @if(request()->anyFilled(['search', 'paket_id', 'kelompok_id', 'jenis_kelamin']))
                        <a href="{{ route('peserta-didik.index') }}" 
                           class="bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 hover:bg-rose-100 p-2.5 rounded-xl transition-all"
                           title="Reset Filter">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
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
                        <th class="px-4 py-3 text-left border border-white/20">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'paket_bimbingan_id', 'order' => request('sort') == 'paket_bimbingan_id' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-between group">
                                Program & Kelas
                                <span class="transition-all {{ request('sort') == 'paket_bimbingan_id' ? 'opacity-100' : 'opacity-30 group-hover:opacity-100' }}">
                                    @if(request('sort') == 'paket_bimbingan_id' && request('order') == 'asc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7"/></svg>
                                    @elseif(request('sort') == 'paket_bimbingan_id' && request('order') == 'desc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M7 10l5-5 5 5M7 14l5 5 5-5"/></svg>
                                    @endif
                                </span>
                            </a>
                        </th>
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
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('peserta-didik.edit', $peserta->id) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded bg-amber-50 dark:bg-amber-900/10 text-amber-600 dark:text-amber-400 hover:bg-amber-100 transition-all border border-amber-200/50 shadow-sm"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button type="button" onclick="confirmDelete('{{ $peserta->id }}', '{{ $peserta->nama_lengkap }}')"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded bg-rose-50 dark:bg-rose-900/10 text-rose-600 dark:text-rose-400 hover:bg-rose-100 transition-all border border-rose-200/50 shadow-sm"
                                        title="Hapus">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-24 text-center border border-slate-200 dark:border-zinc-800">
                                <div class="w-20 h-20 bg-slate-50 dark:bg-zinc-800 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner">
                                    🎓
                                </div>
                                <h3 class="font-black text-slate-900 dark:text-white text-lg">Data Siswa Kosong</h3>
                                <p class="text-slate-400 text-sm mt-2 font-medium max-w-xs mx-auto">Belum ada data peserta didik aktif yang terdaftar di sistem.</p>
                                <a href="{{ route('peserta-didik.create') }}" class="mt-8 inline-flex items-center gap-2 bg-indigo-600 text-white text-xs font-black px-8 py-4 rounded-2xl hover:bg-indigo-700 transition-all active:scale-95 shadow-xl shadow-indigo-500/20">
                                    + Tambah Siswa Baru
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Pagination --}}
        <div class="px-8 py-6 border-t border-slate-100 dark:border-zinc-800 bg-slate-50/30 dark:bg-zinc-900/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
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

</div>
@endsection