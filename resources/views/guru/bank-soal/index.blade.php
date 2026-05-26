@extends('layouts.guru')

@section('title', 'Bank Soal')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 rounded-xl shadow-sm border border-slate-100 dark:border-[#388782]/30 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800 dark:text-white">Bank Soal</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola repositori soal Anda untuk digunakan pada ujian CBT</p>
            </div>
            <div class="flex items-center gap-3" x-data="{ showImportModal: false }">
                <button @click="showImportModal = true"
                    class="bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-slate-300 px-4 py-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-zinc-700 flex items-center gap-2 text-sm font-semibold transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Import Excel
                </button>
                <a href="{{ route('guru.bank-soal.create') }}"
                    class="bg-gradient-to-r from-[#388782] to-[#206D6C] text-white px-5 py-2.5 rounded-xl hover:from-[#206D6C] hover:to-[#0F5253] flex items-center gap-2 text-sm font-semibold shadow-md shadow-[#388782]/25 transition-all duration-200 hover:shadow-lg hover:shadow-[#388782]/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Soal Baru
                </a>

                {{-- Modal Import --}}
                <div x-show="showImportModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" @click.self="showImportModal = false" style="display:none;">
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-[#388782]/30 p-6 w-full max-w-md" @click.stop>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Import Soal (Excel)</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Upload file Excel menggunakan template yang disediakan.</p>
                        
                        <div class="mb-6">
                            <a href="{{ route('guru.bank-soal.template') }}" class="inline-flex items-center gap-2 text-sm text-[#388782] dark:text-[#A2D5CB] hover:underline font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Download Template Excel (.xls)
                            </a>
                        </div>

                        <form action="{{ route('guru.bank-soal.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Pilih File Excel (.xls) atau CSV</label>
                            <input type="file" name="file" accept=".xls,.csv,.txt" required class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-[#A2D5CB] file:text-[#388782] hover:file:bg-[#78BBB0] dark:file:bg-[#388782]/40 dark:file:text-[#A2D5CB] border border-slate-200 dark:border-zinc-700 rounded-xl mb-6">
                            
                            <div class="flex justify-end gap-3">
                                <button type="button" @click="showImportModal = false" class="px-5 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-zinc-800 rounded-xl transition-colors">Batal</button>
                                <button type="submit" class="px-5 py-2.5 bg-[#388782] text-white rounded-xl text-sm font-semibold hover:bg-[#206D6C] transition-colors">Import Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 rounded-xl border border-slate-100 dark:border-[#388782]/30 p-4 text-center group hover:shadow-md transition-all duration-200">
            <div class="text-2xl font-extrabold text-[#388782] dark:text-[#A2D5CB]">{{ $stats['total'] }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Total Soal</div>
        </div>
        <div class="bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 rounded-xl border border-slate-100 dark:border-[#388782]/30 p-4 text-center group hover:shadow-md transition-all duration-200">
            <div class="text-2xl font-extrabold text-[#388782] dark:text-[#A2D5CB]">{{ $stats['pg'] }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Pilihan Ganda</div>
        </div>
        <div class="bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 rounded-xl border border-slate-100 dark:border-[#388782]/30 p-4 text-center group hover:shadow-md transition-all duration-200">
            <div class="text-2xl font-extrabold text-[#388782] dark:text-[#A2D5CB]">{{ $stats['essay'] }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Essay</div>
        </div>
        <div class="bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 rounded-xl border border-slate-100 dark:border-[#388782]/30 p-4 text-center group hover:shadow-md transition-all duration-200">
            <div class="text-2xl font-extrabold text-green-600 dark:text-green-400">{{ $stats['mudah'] }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Mudah</div>
        </div>
        <div class="bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 rounded-xl border border-slate-100 dark:border-[#388782]/30 p-4 text-center group hover:shadow-md transition-all duration-200">
            <div class="text-2xl font-extrabold text-yellow-600 dark:text-yellow-400">{{ $stats['sedang'] }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Sedang</div>
        </div>
        <div class="bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 rounded-xl border border-slate-100 dark:border-[#388782]/30 p-4 text-center group hover:shadow-md transition-all duration-200">
            <div class="text-2xl font-extrabold text-red-600 dark:text-red-400">{{ $stats['sulit'] }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Sulit</div>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 rounded-xl shadow-sm border border-slate-100 dark:border-[#388782]/30 p-5"
         x-data="{ showFilters: {{ request()->hasAny(['mapel','bab','tipe','kesulitan']) ? 'true' : 'false' }} }">
        <form method="GET" action="{{ route('guru.bank-soal.index') }}">
            <div class="flex flex-col sm:flex-row gap-3">
                {{-- Search --}}
                <div class="relative flex-1">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari soal..."
                        class="w-full pl-10 pr-4 py-2.5 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm bg-slate-50 dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-400 dark:placeholder-slate-500">
                </div>
                <button type="button" @click="showFilters = !showFilters"
                    class="px-4 py-2.5 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-zinc-800 flex items-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                </button>
                <button type="submit"
                    class="px-5 py-2.5 bg-[#388782] text-white rounded-xl text-sm font-semibold hover:bg-[#206D6C] transition-colors shadow-sm">
                    Cari
                </button>
            </div>

            {{-- Filter Dropdowns --}}
            <div x-show="showFilters" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mt-4 pt-4 border-t border-slate-100 dark:border-zinc-800">
                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Mata Pelajaran</label>
                    <select name="mapel" class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua</option>
                        @foreach($mapels as $m)
                        <option value="{{ $m->id }}" {{ request('mapel') == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Topik / Bab</label>
                    <select name="bab" class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua</option>
                        @foreach($babs as $b)
                        <option value="{{ $b->id }}" {{ request('bab') == $b->id ? 'selected' : '' }}>{{ $b->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Tipe Soal</label>
                    <select name="tipe" class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua</option>
                        <option value="pg" {{ request('tipe') == 'pg' ? 'selected' : '' }}>Pilihan Ganda</option>
                        <option value="essay" {{ request('tipe') == 'essay' ? 'selected' : '' }}>Essay</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Tingkat Kesulitan</label>
                    <select name="kesulitan" class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua</option>
                        <option value="easy" {{ request('kesulitan') == 'easy' ? 'selected' : '' }}>Mudah</option>
                        <option value="medium" {{ request('kesulitan') == 'medium' ? 'selected' : '' }}>Sedang</option>
                        <option value="hard" {{ request('kesulitan') == 'hard' ? 'selected' : '' }}>Sulit</option>
                    </select>
                </div>
            </div>

            @if(request()->hasAny(['search','mapel','bab','tipe','kesulitan']))
            <div class="mt-3">
                <a href="{{ route('guru.bank-soal.index') }}" class="text-xs text-[#388782] dark:text-[#A2D5CB] hover:underline font-medium">
                    ✕ Reset semua filter
                </a>
            </div>
            @endif
        </form>
    </div>

    {{-- Daftar Soal --}}
    @if($soals->isEmpty())
    <div class="bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 rounded-xl shadow-sm border border-slate-100 dark:border-[#388782]/30 p-16 text-center">
        <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-emerald-100 to-green-50 dark:from-emerald-900/30 dark:to-green-900/20 flex items-center justify-center">
            <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
        </div>
        <p class="text-slate-600 dark:text-slate-300 font-semibold text-lg">Belum ada soal</p>
        <p class="text-slate-400 dark:text-slate-500 text-sm mt-2 max-w-sm mx-auto">Mulai buat soal pertama Anda untuk membangun bank soal yang dapat digunakan pada ujian CBT</p>
        <a href="{{ route('guru.bank-soal.create') }}" class="mt-6 inline-flex items-center gap-2 bg-gradient-to-r from-[#388782] to-[#206D6C] text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md shadow-[#388782]/25 hover:shadow-lg transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Soal Pertama
        </a>
    </div>
    @else
    <div class="bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 rounded-xl shadow-sm border border-slate-100 dark:border-[#388782]/30 overflow-hidden">
        {{-- Table Header --}}
        <div class="hidden lg:grid grid-cols-12 gap-4 px-6 py-3 bg-gradient-to-r from-[#A2D5CB]/40 to-transparent dark:from-[#388782]/20 dark:to-transparent border-b border-slate-100 dark:border-[#388782]/20 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
            <div class="col-span-1">No</div>
            <div class="col-span-4">Pertanyaan</div>
            <div class="col-span-2">Mata Pelajaran</div>
            <div class="col-span-1">Tipe</div>
            <div class="col-span-1">Level</div>
            <div class="col-span-1">Status</div>
            <div class="col-span-2 text-right">Aksi</div>
        </div>

        {{-- Table Body --}}
        @foreach($soals as $index => $soal)
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 lg:gap-4 px-5 lg:px-6 py-4 border-b border-slate-50 dark:border-zinc-800/50 hover:bg-slate-50/50 dark:hover:bg-zinc-800/30 transition-colors group items-center">
            {{-- No --}}
            <div class="col-span-1 hidden lg:flex">
                <span class="w-8 h-8 bg-[#A2D5CB] dark:bg-[#388782]/40 rounded-lg flex items-center justify-center text-[#388782] dark:text-[#A2D5CB] text-xs font-bold">
                    {{ $soals->firstItem() + $index }}
                </span>
            </div>

            {{-- Pertanyaan --}}
            <div class="col-span-4">
                <p class="text-sm text-slate-800 dark:text-slate-100 font-medium leading-relaxed line-clamp-2">{!! Str::limit(strip_tags($soal->pertanyaan), 120) !!}</p>
                @if($soal->bab)
                <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 inline-block">{{ $soal->bab->nama }}</span>
                @endif
            </div>

            {{-- Mapel --}}
            <div class="col-span-2">
                @if($soal->mapel)
                <span class="text-xs font-medium text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-zinc-800 px-2 py-1 rounded-lg">{{ $soal->mapel->nama }}</span>
                @else
                <span class="text-xs text-slate-400 dark:text-slate-500 italic">Tanpa mapel</span>
                @endif
            </div>

            {{-- Tipe --}}
            <div class="col-span-1">
                @if($soal->tipe_soal === 'pg')
                <span class="text-xs font-semibold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-1 rounded-lg">PG</span>
                @else
                <span class="text-xs font-semibold text-purple-700 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/30 px-2 py-1 rounded-lg">Essay</span>
                @endif
            </div>

            {{-- Kesulitan --}}
            <div class="col-span-1">
                @php
                    $kesulitanColors = [
                        'easy'   => 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/30',
                        'medium' => 'text-yellow-700 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/30',
                        'hard'   => 'text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/30',
                    ];
                @endphp
                <span class="text-xs font-semibold {{ $kesulitanColors[$soal->tingkat_kesulitan] ?? '' }} px-2 py-1 rounded-lg">
                    {{ $soal->tingkat_kesulitan_label }}
                </span>
            </div>

            {{-- Status --}}
            <div class="col-span-1">
                @if($soal->status === 'published')
                <span class="flex items-center gap-1 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    Aktif
                </span>
                @else
                <span class="flex items-center gap-1 text-xs font-medium text-slate-400">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                    Draft
                </span>
                @endif
            </div>

            {{-- Aksi --}}
            <div class="col-span-2 flex items-center justify-end gap-2">
                <a href="{{ route('guru.bank-soal.show', $soal->id) }}"
                    class="text-xs text-slate-500 dark:text-slate-400 hover:text-[#388782] dark:hover:text-[#A2D5CB] font-medium border border-slate-200 dark:border-zinc-700 px-3 py-1.5 rounded-lg hover:border-[#388782] dark:hover:border-[#388782] transition-colors" title="Detail">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </a>
                <a href="{{ route('guru.bank-soal.edit', $soal->id) }}"
                    class="text-xs text-slate-500 dark:text-slate-400 hover:text-[#388782] dark:hover:text-[#A2D5CB] font-medium border border-slate-200 dark:border-zinc-700 px-3 py-1.5 rounded-lg hover:border-[#388782] dark:hover:border-[#388782] transition-colors" title="Edit">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                <form action="{{ route('guru.bank-soal.destroy', $soal->id) }}" method="POST" class="inline"
                    onsubmit="event.preventDefault(); confirmDelete('Hapus Soal?', 'Tindakan ini tidak dapat dibatalkan!', this)">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-xs text-slate-500 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 font-medium border border-slate-200 dark:border-zinc-700 px-3 py-1.5 rounded-lg hover:border-red-300 dark:hover:border-red-600 transition-colors" title="Hapus">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="flex justify-center">
        {{ $soals->links() }}
    </div>
    @endif
</div>
@endsection