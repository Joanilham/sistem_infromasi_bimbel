@extends('layouts.admin')

@section('title', 'Rekapitulasi Data')

@section('content')
<div class="space-y-6" x-data="ajaxTable()">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Rekapitulasi Data</h1>
                <a href="{{ route('admin.rekapitulasi.export', array_merge(['tab' => $tab], request()->except('tab'))) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold transition-all shadow-md shadow-emerald-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export Excel
                </a>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Ringkasan agregat data operasional harian dan bulanan.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 mt-4 md:mt-0">
            @php
                $tabs = [
                    'siswa' => [
                        'label' => 'Siswa', 
                        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>'
                    ],
                    'guru' => [
                        'label' => 'Guru', 
                        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                    ],
                    'keuangan' => [
                        'label' => 'Keuangan', 
                        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                    ],
                    'absensi' => [
                        'label' => 'Absensi', 
                        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>'
                    ],
                ];
            @endphp
            @foreach($tabs as $key => $t)
                <a href="{{ route('admin.rekapitulasi.index', ['tab' => $key]) }}"
                   class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl text-xs font-black uppercase tracking-widest transition-all border
                   {{ $tab === $key 
                        ? 'bg-indigo-600 text-white border-indigo-600 shadow-xl shadow-indigo-500/30'
                        : 'bg-white dark:bg-zinc-900 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-zinc-800 hover:bg-slate-50 dark:hover:bg-zinc-800 shadow-sm' }}">
                    <span class="text-base">{!! $t['icon'] !!}</span>
                    {{ $t['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    @if(in_array($tab, ['keuangan', 'absensi']))
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm">
            <form @submit.prevent="fetchData" method="GET" action="{{ route('admin.rekapitulasi.index') }}" class="flex flex-col gap-4">
                <input type="hidden" name="tab" value="{{ $tab }}">
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1 flex flex-col">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date', $start_date) }}" class="w-full h-[42px] bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                    <div class="flex-1 flex flex-col">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ request('end_date', $end_date) }}" class="w-full h-[42px] bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                    @if($tab === 'absensi')
                        <div class="flex-1 flex flex-col">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Cari</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." class="w-full h-[42px] bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        </div>
                    @endif
                    
                    @if($tab === 'absensi')
                        <div class="w-full sm:w-auto shrink-0 flex flex-col">
                            <label class="block text-xs font-black uppercase tracking-widest text-transparent mb-2 hidden sm:block">&nbsp;</label>
                            <button type="submit" class="w-full sm:w-auto flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs px-8 rounded-xl transition-all shadow-lg shadow-indigo-500/20 uppercase tracking-widest h-[42px]">
                                Terapkan
                            </button>
                        </div>
                    @endif
                </div>

                @if($tab === 'keuangan')
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1 flex flex-col">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Kategori</label>
                        <select name="kategori_id" @change="fetchData" class="no-tomselect w-full h-[42px] flex-1 bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                            <option value="">Semua Kategori</option>
                            <optgroup label="Pemasukan">
                                <option value="spp" {{ request('kategori_id') == 'spp' ? 'selected' : '' }}>SPP/Bimbingan</option>
                                @foreach($kategori_pemasukan_list as $kp)
                                    <option value="in_{{ $kp->id }}" {{ request('kategori_id') == 'in_'.$kp->id ? 'selected' : '' }}>{{ $kp->nama }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Pengeluaran">
                                @foreach($kategori_pengeluaran_list as $kp)
                                    <option value="out_{{ $kp->id }}" {{ request('kategori_id') == 'out_'.$kp->id ? 'selected' : '' }}>{{ $kp->nama }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="flex-1 flex flex-col">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Cari</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari keterangan..." class="w-full h-[42px] bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                    <div class="w-full sm:w-auto shrink-0 flex flex-col">
                        <label class="block text-xs font-black uppercase tracking-widest text-transparent mb-2 hidden sm:block">&nbsp;</label>
                        <button type="submit" class="w-full sm:w-auto flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs px-8 rounded-xl transition-all shadow-lg shadow-indigo-500/20 uppercase tracking-widest h-[42px]">
                            Terapkan Filter
                        </button>
                    </div>
                </div>
                @endif
            </form>
        </div>
    @elseif($tab === 'siswa')
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm">
            <form @submit.prevent="fetchData" method="GET" action="{{ route('admin.rekapitulasi.index') }}" class="flex flex-col sm:flex-row gap-4">
                <input type="hidden" name="tab" value="{{ $tab }}">
                
                <div class="flex-1 flex flex-col">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Filter Kelas</label>
                    <select name="kelompok_belajar_id" @change="fetchData" class="no-tomselect w-full flex-1 bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <option value="">Semua Kelas</option>
                        @foreach($filter_kelas as $k)
                            <option value="{{ $k->id }}" {{ $selected_kelas == $k->id ? 'selected' : '' }}>{{ $k->nama_kelompok }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 flex flex-col">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin" @change="fetchData" class="no-tomselect w-full flex-1 bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <option value="">Semua Jenis Kelamin</option>
                        <option value="L" {{ $selected_gender == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                        <option value="P" {{ $selected_gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="flex-1 flex flex-col">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Status</label>
                    <select name="status" @change="fetchData" class="no-tomselect w-full flex-1 bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ (isset($selected_status) && $selected_status == 'aktif') ? 'selected' : '' }}>Aktif</option>
                        <option value="keluar" {{ (isset($selected_status) && $selected_status == 'keluar') ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>
                <div class="flex-1 flex flex-col">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / NISN..." class="w-full h-[42px] bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                </div>
                <div class="w-full sm:w-auto shrink-0 flex flex-col">
                    <label class="block text-xs font-black uppercase tracking-widest text-transparent mb-2 hidden sm:block">&nbsp;</label>
                    <button type="submit" class="w-full sm:w-auto flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs px-8 rounded-xl transition-all shadow-lg shadow-indigo-500/20 uppercase tracking-widest h-[42px]">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    @elseif($tab === 'guru')
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm">
            <form @submit.prevent="fetchData" method="GET" action="{{ route('admin.rekapitulasi.index') }}" class="flex flex-col sm:flex-row gap-4">
                <input type="hidden" name="tab" value="{{ $tab }}">
                
                <div class="flex-1 flex flex-col">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Filter Mapel</label>
                    <select name="matapelajaran" @change="fetchData" class="no-tomselect w-full flex-1 bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <option value="">Semua Mata Pelajaran</option>
                        @foreach($filter_mapel as $m)
                            <option value="{{ $m }}" {{ $selected_mapel == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 flex flex-col">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin" @change="fetchData" class="no-tomselect w-full flex-1 bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <option value="">Semua Jenis Kelamin</option>
                        <option value="Laki-Laki" {{ $selected_gender == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                        <option value="Perempuan" {{ $selected_gender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="flex-1 flex flex-col">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Status</label>
                    <select name="status" @change="fetchData" class="no-tomselect w-full flex-1 bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ (isset($selected_status) && $selected_status == 'aktif') ? 'selected' : '' }}>Aktif</option>
                        <option value="keluar" {{ (isset($selected_status) && $selected_status == 'keluar') ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>
                <div class="flex-1 flex flex-col">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama guru..." class="w-full h-[42px] bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                </div>
                <div class="w-full sm:w-auto shrink-0 flex flex-col">
                    <label class="block text-xs font-black uppercase tracking-widest text-transparent mb-2 hidden sm:block">&nbsp;</label>
                    <button type="submit" class="w-full sm:w-auto flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs px-8 rounded-xl transition-all shadow-lg shadow-indigo-500/20 uppercase tracking-widest h-[42px]">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Content Area --}}
    <div id="ajax-table-body" class="space-y-6 relative" @click="if($event.target.closest('th a')) { navigate($event, $event.target.closest('a').href) }; if($event.target.closest('nav[role=navigation] a')) { navigate($event, $event.target.closest('a').href) }">
        {{-- Loading Overlay --}}
        <div x-show="isLoading" class="absolute inset-0 z-50 bg-white/50 dark:bg-zinc-900/50 backdrop-blur-sm flex items-start justify-center pt-20 transition-opacity duration-300" style="display: none;">
            <div class="bg-white dark:bg-zinc-800 p-4 rounded-2xl shadow-xl border border-slate-100 dark:border-zinc-700 flex items-center gap-3">
                <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm font-bold text-slate-700 dark:text-slate-200">Memuat data...</span>
            </div>
        </div>
        @if($tab === 'siswa')
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 text-indigo-50 dark:text-indigo-900/10 transition-transform group-hover:scale-110">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                    </div>
                    <div class="relative">
                        <h3 class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Total Siswa Aktif</h3>
                        <p class="text-4xl font-black text-indigo-600 dark:text-indigo-400">{{ $total_aktif }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 text-rose-50 dark:text-rose-900/10 transition-transform group-hover:scale-110">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="relative">
                        <h3 class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Total Siswa Keluar</h3>
                        <p class="text-4xl font-black text-rose-600 dark:text-rose-400">{{ $total_keluar }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group sm:col-span-2">
                    <h3 class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-4">Distribusi Jenis Kelamin (Siswa Aktif)</h3>
                    <div class="flex items-center gap-6">
                        <div class="flex-1">
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Laki-Laki</span>
                                <span class="text-lg font-black text-sky-600">{{ $gender_aktif_l }}</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-zinc-800 rounded-full h-3">
                                <div class="bg-sky-500 h-3 rounded-full" style="width: {{ $total_aktif > 0 ? ($gender_aktif_l / $total_aktif) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Perempuan</span>
                                <span class="text-lg font-black text-pink-600">{{ $gender_aktif_p }}</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-zinc-800 rounded-full h-3">
                                <div class="bg-pink-500 h-3 rounded-full" style="width: {{ $total_aktif > 0 ? ($gender_aktif_p / $total_aktif) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
                    <h2 class="text-lg font-black text-slate-900 dark:text-white mb-6">Siswa Aktif Berdasarkan Paket Bimbingan</h2>
                    <div class="space-y-4">
                        @forelse($rekap_paket as $rp)
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 hover:border-indigo-300 dark:hover:border-indigo-500/50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-black">
                                        {{ substr($rp->paketBimbingan->nama_paket ?? '?', 0, 1) }}
                                    </div>
                                    <span class="font-bold text-slate-700 dark:text-slate-300">{{ $rp->paketBimbingan->nama_paket ?? 'Tanpa Paket' }}</span>
                                </div>
                                <span class="text-xl font-black text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 px-4 py-1.5 rounded-xl">{{ $rp->total }} <span class="text-[10px] text-indigo-400 dark:text-indigo-500 ml-1 uppercase">Siswa</span></span>
                            </div>
                        @empty
                            <p class="text-slate-500 dark:text-slate-400 text-sm italic">Belum ada data distribusi paket.</p>
                        @endforelse
                    </div>
                </div>
                
                <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
                    <h2 class="text-lg font-black text-slate-900 dark:text-white mb-6">Siswa Aktif Berdasarkan Kelas</h2>
                    <div class="space-y-4">
                        @forelse($rekap_kelas as $rk)
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 hover:border-teal-300 dark:hover:border-teal-500/50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 flex items-center justify-center font-black">
                                        {{ substr($rk->kelompokBelajar->nama_kelompok ?? '?', 0, 1) }}
                                    </div>
                                    <span class="font-bold text-slate-700 dark:text-slate-300">{{ $rk->kelompokBelajar->nama_kelompok ?? 'Tanpa Kelas' }}</span>
                                </div>
                                <span class="text-xl font-black text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-900/20 px-4 py-1.5 rounded-xl">{{ $rk->total }} <span class="text-[10px] text-teal-400 dark:text-teal-500 ml-1 uppercase">Siswa</span></span>
                            </div>
                        @empty
                            <p class="text-slate-500 dark:text-slate-400 text-sm italic">Belum ada data distribusi kelas.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div id="table-rincian" class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm mt-6">
                <h2 class="text-lg font-black text-slate-900 dark:text-white mb-6">Daftar Rincian Siswa</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse border border-slate-200 dark:border-zinc-800">
                        <thead class="bg-slate-50 dark:bg-zinc-950 text-[10px] uppercase tracking-widest text-slate-500 dark:text-slate-400 font-black">
                            <tr>
                                <th class="px-4 py-3 text-left border border-slate-200 dark:border-zinc-800">Nama Lengkap</th>
                                <th class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">L/P</th>
                                <th class="px-4 py-3 text-left border border-slate-200 dark:border-zinc-800">Kelas & Paket</th>
                                <th class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900">
                            @forelse($list_siswa as $siswa)
                                <tr class="hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-all">
                                    <td class="px-4 py-3 font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-zinc-800">{{ $siswa->nama_lengkap }}</td>
                                    <td class="px-4 py-3 text-center text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-zinc-800">{{ $siswa->jenis_kelamin }}</td>
                                    <td class="px-4 py-3 text-xs border border-slate-200 dark:border-zinc-800">
                                        <div class="font-bold text-slate-600 dark:text-slate-400">{{ $siswa->kelompokBelajar->nama_kelompok ?? '-' }}</div>
                                        <div class="text-[10px] text-slate-400 uppercase tracking-widest mt-0.5">{{ $siswa->paketBimbingan->nama_paket ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">
                                        @if(strtolower($siswa->status) === 'aktif')
                                            <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Aktif</span>
                                        @else
                                            <span class="bg-rose-100 text-rose-700 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Keluar</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400 italic border border-slate-200 dark:border-zinc-800">Belum ada data siswa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $list_siswa->fragment('table-rincian')->links() }}
                </div>
            </div>

        @elseif($tab === 'guru')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 text-emerald-50 dark:text-emerald-900/10 transition-transform group-hover:scale-110">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="relative">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Total Guru Aktif</h3>
                                <p class="text-4xl font-black text-emerald-600 dark:text-emerald-400">{{ $total_aktif }}</p>
                            </div>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-zinc-800/50">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Laki-Laki</span>
                                <span class="text-lg font-black text-indigo-600">{{ $guru_gender_aktif_l }}</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-zinc-800 rounded-full h-3 mb-4">
                                <div class="bg-indigo-500 h-3 rounded-full" style="width: {{ $total_aktif > 0 ? ($guru_gender_aktif_l / $total_aktif) * 100 : 0 }}%"></div>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Perempuan</span>
                                <span class="text-lg font-black text-pink-600">{{ $guru_gender_aktif_p }}</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-zinc-800 rounded-full h-3">
                                <div class="bg-pink-500 h-3 rounded-full" style="width: {{ $total_aktif > 0 ? ($guru_gender_aktif_p / $total_aktif) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 text-rose-50 dark:text-rose-900/10 transition-transform group-hover:scale-110">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="relative">
                        <h3 class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Total Guru Keluar</h3>
                        <p class="text-4xl font-black text-rose-600 dark:text-rose-400">{{ $total_keluar }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
                <h2 class="text-lg font-black text-slate-900 dark:text-white mb-6">Guru Aktif Berdasarkan Mata Pelajaran</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($rekap_mapel as $rm)
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 hover:border-emerald-300 dark:hover:border-emerald-500/50 transition-colors">
                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ $rm->matapelajaran ?: 'Tanpa Mapel' }}</span>
                            <span class="text-lg font-black text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1 rounded-xl">{{ $rm->total }} <span class="text-[10px] ml-0.5">Guru</span></span>
                        </div>
                    @empty
                        <p class="text-slate-500 dark:text-slate-400 text-sm italic col-span-full">Belum ada data mata pelajaran.</p>
                    @endforelse
                </div>
            </div>

            <div id="table-rincian" class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm mt-6">
                <h2 class="text-lg font-black text-slate-900 dark:text-white mb-6">Daftar Rincian Guru</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse border border-slate-200 dark:border-zinc-800">
                        <thead class="bg-slate-50 dark:bg-zinc-950 text-[10px] uppercase tracking-widest text-slate-500 dark:text-slate-400 font-black">
                            <tr>
                                <th class="px-4 py-3 text-left border border-slate-200 dark:border-zinc-800">Nama Guru</th>
                                <th class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">L/P</th>
                                <th class="px-4 py-3 text-left border border-slate-200 dark:border-zinc-800">Mata Pelajaran</th>
                                <th class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900">
                            @forelse($list_guru as $guru)
                                <tr class="hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-all">
                                    <td class="px-4 py-3 font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-zinc-800">{{ $guru->name }}</td>
                                    <td class="px-4 py-3 text-center text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-zinc-800">{{ $guru->jenis_kelamin == 'Laki-Laki' ? 'L' : ($guru->jenis_kelamin == 'Perempuan' ? 'P' : '-') }}</td>
                                    <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-zinc-800">{{ $guru->matapelajaran ?: '-' }}</td>
                                    <td class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">
                                        @if(strtolower($guru->status) === 'aktif')
                                            <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Aktif</span>
                                        @else
                                            <span class="bg-rose-100 text-rose-700 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Keluar</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400 italic border border-slate-200 dark:border-zinc-800">Belum ada data guru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $list_guru->fragment('table-rincian')->links() }}
                </div>
            </div>

        @elseif($tab === 'keuangan')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 text-emerald-50 dark:text-emerald-900/10 transition-transform group-hover:scale-110">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="relative">
                        <h3 class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Total Pemasukan</h3>
                        <p class="text-3xl lg:text-4xl font-black text-emerald-600 dark:text-emerald-400">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 text-rose-50 dark:text-rose-900/10 transition-transform group-hover:scale-110">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="relative">
                        <h3 class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Total Pengeluaran</h3>
                        <p class="text-3xl lg:text-4xl font-black text-rose-600 dark:text-rose-400">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
                    <h2 class="text-lg font-black text-slate-900 dark:text-white mb-6">Pemasukan Berdasarkan Kategori</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 hover:border-emerald-300 transition-colors">
                            <span class="font-bold text-slate-700 dark:text-slate-300">Pembayaran SPP/Bimbingan</span>
                            <span class="text-lg font-black text-emerald-600 dark:text-emerald-400">Rp {{ number_format($pemasukan_spp, 0, ',', '.') }}</span>
                        </div>
                        @forelse($rekap_pemasukan_lain as $rpl)
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 hover:border-emerald-300 transition-colors">
                                <span class="font-bold text-slate-700 dark:text-slate-300">{{ $rpl->kategori->nama_kategori ?? 'Lainnya' }}</span>
                                <span class="text-lg font-black text-emerald-600 dark:text-emerald-400">Rp {{ number_format($rpl->total, 0, ',', '.') }}</span>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
                
                <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
                    <h2 class="text-lg font-black text-slate-900 dark:text-white mb-6">Pengeluaran Berdasarkan Kategori</h2>
                    <div class="space-y-4">
                        @forelse($rekap_pengeluaran as $rp)
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 hover:border-rose-300 transition-colors">
                                <span class="font-bold text-slate-700 dark:text-slate-300">{{ $rp->kategori->nama_kategori ?? 'Lainnya' }}</span>
                                <span class="text-lg font-black text-rose-600 dark:text-rose-400">Rp {{ number_format($rp->total, 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <p class="text-slate-500 dark:text-slate-400 text-sm italic">Belum ada data pengeluaran di rentang tanggal ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div id="table-rincian" class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm mt-6">
                <h2 class="text-lg font-black text-slate-900 dark:text-white mb-6">Rincian Transaksi Keuangan</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse border border-slate-200 dark:border-zinc-800">
                        <thead class="bg-slate-50 dark:bg-zinc-950 text-[10px] uppercase tracking-widest text-slate-500 dark:text-slate-400 font-black">
                            <tr>
                                <th class="px-4 py-3 text-left border border-slate-200 dark:border-zinc-800">Tanggal</th>
                                <th class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">Tipe</th>
                                <th class="px-4 py-3 text-left border border-slate-200 dark:border-zinc-800">Kategori / Jenis</th>
                                <th class="px-4 py-3 text-left border border-slate-200 dark:border-zinc-800">Keterangan</th>
                                <th class="px-4 py-3 text-right border border-slate-200 dark:border-zinc-800">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900">
                            @forelse($list_keuangan as $trx)
                                <tr class="hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-all">
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-zinc-800 whitespace-nowrap">{{ \Carbon\Carbon::parse($trx['tanggal'])->translatedFormat('d M Y') }}</td>
                                    <td class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">
                                        @if($trx['tipe'] === 'pemasukan')
                                            <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Pemasukan</span>
                                        @else
                                            <span class="bg-rose-100 text-rose-700 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Pengeluaran</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs border border-slate-200 dark:border-zinc-800">
                                        <div class="font-bold text-slate-700 dark:text-slate-300">{{ $trx['jenis'] }}</div>
                                        <div class="text-[10px] text-slate-400 uppercase tracking-widest mt-0.5">{{ $trx['kategori'] }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-zinc-800">{{ $trx['keterangan'] }}</td>
                                    <td class="px-4 py-3 text-right font-black text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-zinc-800 whitespace-nowrap">Rp {{ number_format($trx['nominal'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400 italic border border-slate-200 dark:border-zinc-800">Belum ada transaksi keuangan pada rentang tanggal ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $list_keuangan->fragment('table-rincian')->links() }}
                </div>
            </div>

        @elseif($tab === 'absensi')
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <h3 class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Hadir</h3>
                    <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ $total_hadir }}</p>
                    <div class="mt-2 text-xs font-bold text-slate-400">{{ $total_absensi > 0 ? round(($total_hadir / $total_absensi) * 100) : 0 }}% dari total</div>
                </div>
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <h3 class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Sakit</h3>
                    <p class="text-3xl font-black text-amber-500">{{ $total_sakit }}</p>
                    <div class="mt-2 text-xs font-bold text-slate-400">{{ $total_absensi > 0 ? round(($total_sakit / $total_absensi) * 100) : 0 }}% dari total</div>
                </div>
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <h3 class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Izin</h3>
                    <p class="text-3xl font-black text-blue-500">{{ $total_izin }}</p>
                    <div class="mt-2 text-xs font-bold text-slate-400">{{ $total_absensi > 0 ? round(($total_izin / $total_absensi) * 100) : 0 }}% dari total</div>
                </div>
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <h3 class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Alpha</h3>
                    <p class="text-3xl font-black text-rose-500">{{ $total_alpha }}</p>
                    <div class="mt-2 text-xs font-bold text-slate-400">{{ $total_absensi > 0 ? round(($total_alpha / $total_absensi) * 100) : 0 }}% dari total</div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
                <h2 class="text-lg font-black text-slate-900 dark:text-white mb-6">Rekap Absensi Per Siswa</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse border border-slate-200 dark:border-zinc-800">
                        <thead class="bg-slate-50 dark:bg-zinc-950 text-[10px] uppercase tracking-widest text-slate-500 dark:text-slate-400 font-black">
                            <tr>
                                <th class="px-4 py-3 text-left border border-slate-200 dark:border-zinc-800">Nama Siswa</th>
                                <th class="px-4 py-3 text-left border border-slate-200 dark:border-zinc-800">Kelas / Paket</th>
                                <th class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800 text-emerald-600">Hadir</th>
                                <th class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800 text-amber-500">Sakit</th>
                                <th class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800 text-blue-500">Izin</th>
                                <th class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800 text-rose-500">Alpha</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900">
                            @forelse($rekap_siswa as $siswa)
                                <tr class="hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-all">
                                    <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                        <a href="{{ route('absensi.detail', ['id' => $siswa->id, 'bulan' => \Carbon\Carbon::parse($start_date)->month, 'tahun' => \Carbon\Carbon::parse($start_date)->year]) }}" class="font-bold text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                            {{ $siswa->nama_lengkap }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-xs border border-slate-200 dark:border-zinc-800">
                                        <div class="font-bold text-slate-600 dark:text-slate-400">{{ $siswa->kelompokBelajar->nama_kelompok ?? '-' }}</div>
                                        <div class="text-[10px] text-slate-400 uppercase tracking-widest mt-0.5">{{ $siswa->paketBimbingan->nama_paket ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center font-black text-emerald-600 border border-slate-200 dark:border-zinc-800">{{ $siswa->hadir_count }}</td>
                                    <td class="px-4 py-3 text-center font-black text-amber-500 border border-slate-200 dark:border-zinc-800">{{ $siswa->sakit_count }}</td>
                                    <td class="px-4 py-3 text-center font-black text-blue-500 border border-slate-200 dark:border-zinc-800">{{ $siswa->izin_count }}</td>
                                    <td class="px-4 py-3 text-center font-black text-rose-500 border border-slate-200 dark:border-zinc-800">{{ $siswa->alpha_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400 italic border border-slate-200 dark:border-zinc-800">Tidak ada rekam absensi pada rentang tanggal ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @endif
    </div>

</div>
@endsection
