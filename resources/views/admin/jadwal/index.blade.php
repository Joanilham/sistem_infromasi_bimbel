@extends('layouts.admin')

@section('title', 'Manajemen Jadwal')

@section('content')
@php
    // Cerdas: Menghitung jam minimum & maksimum aktif agar tabel adaptif & tidak boros ruang vertikal
    $minHour = 13; // default bimbel biasanya siang
    $maxHour = 19; // default bimbel sore/malam
    foreach ($jadwals as $j) {
        if ($j->jam_mulai) {
            $startHour = (int) \Carbon\Carbon::parse($j->jam_mulai)->format('H');
            if ($startHour < $minHour && $startHour >= 6) { // Batasi min jam 6 pagi
                $minHour = $startHour;
            }
        }
        if ($j->jam_selesai) {
            $endHour = (int) \Carbon\Carbon::parse($j->jam_selesai)->format('H');
            if ($endHour > $maxHour && $endHour <= 22) { // Batasi max jam 10 malam
                $maxHour = $endHour;
            }
        }
    }
    
    $timeSlots = [];
    for ($h = $minHour; $h <= $maxHour; $h++) { 
        $timeSlots[] = sprintf('%02d:00', $h); 
    }
    $hariList = \App\Models\Jadwal::HARI_LIST;

    // Cerdas: Hitung tanggal spesifik untuk hari Senin - Sabtu di minggu berjalan
    $currentDate = now();
    $datesOfWeek = [];
    $mapDayNum = [
        'Senin'   => 1,
        'Selasa'  => 2,
        'Rabu'    => 3,
        'Kamis'   => 4,
        'Jumat'   => 5,
        'Sabtu'   => 6,
        'Minggu'  => 7
    ];
    foreach ($hariList as $hari) {
        $targetDayNum = $mapDayNum[$hari] ?? 1;
        $currentDayNum = $currentDate->dayOfWeekIso; // 1 (Mon) - 7 (Sun)
        $diff = $targetDayNum - $currentDayNum;
        $datesOfWeek[$hari] = $currentDate->copy()->addDays($diff)->locale('id')->isoFormat('D MMM YYYY');
    }
@endphp

<div x-data="jadwalManager()" class="space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Manajemen Jadwal</h1>
            <div class="flex flex-wrap items-center gap-3 mt-2.5">
                <span class="inline-flex items-center gap-2 text-xs font-black bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 px-4 py-2 rounded-xl border border-indigo-100 dark:border-indigo-900/30 uppercase tracking-wider shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                </span>
                <p class="text-slate-500 dark:text-slate-500 text-sm font-medium">· Kelola jadwal bimbingan belajar guru dan rombel secara efisien.</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3 shrink-0">
            {{-- Toggle Mode Tampilan Premium --}}
            <div class="flex items-center bg-slate-100 dark:bg-zinc-900 p-1 rounded-2xl border border-slate-200/50 dark:border-zinc-800/50 mr-2 shadow-inner">
                <button @click="viewMode = 'calendar'" 
                    :class="viewMode === 'calendar' ? 'bg-white dark:bg-zinc-800 text-indigo-600 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400'"
                    class="inline-flex items-center gap-2 font-black text-xs px-5 py-3 rounded-xl transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Kalender Grid
                </button>
                <button @click="viewMode = 'list'" 
                    :class="viewMode === 'list' ? 'bg-white dark:bg-zinc-800 text-indigo-600 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400'"
                    class="inline-flex items-center gap-2 font-black text-xs px-5 py-3 rounded-xl transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    List Agenda
                </button>
            </div>
            
            <button @click="showDuplikasi = true"
                class="inline-flex items-center gap-3 bg-white dark:bg-zinc-800 hover:bg-slate-50 dark:hover:bg-zinc-700 text-slate-700 dark:text-slate-200 font-black text-xs px-6 py-4 rounded-2xl shadow-sm border border-slate-200 dark:border-zinc-700 transition-all active:scale-95">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Duplikasi
            </button>
            <button @click="openAddModal()"
                class="inline-flex items-center gap-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95 group">
                <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Tambah Jadwal
            </button>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>
        <form method="GET" action="{{ route('admin.jadwal.index') }}" class="flex flex-col lg:flex-row items-end gap-6 relative">
            <div class="flex-1 w-full space-y-3">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Filter Tenaga Pengajar</label>
                <div class="relative">
                    <select name="guru_id" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all appearance-none text-slate-800 dark:text-white">
                        <option value="">Semua Guru</option>
                        @foreach($guruList as $g)
                        <option value="{{ $g->id }}" {{ $filterGuru == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
            <div class="flex-1 w-full space-y-3">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Filter Kelompok Belajar</label>
                <div class="relative">
                    <select name="rombel_id" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all appearance-none text-slate-800 dark:text-white">
                        <option value="">Semua Rombel</option>
                        @foreach($rombelList as $r)
                        <option value="{{ $r->id }}" {{ $filterRombel == $r->id ? 'selected' : '' }}>{{ $r->nama_kelompok }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 w-full lg:w-auto">
                <button type="submit" class="flex-1 lg:flex-none bg-slate-900 dark:bg-white dark:text-slate-900 text-white font-black text-xs px-10 py-4 rounded-2xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-all active:scale-95 shadow-lg shadow-slate-900/10 uppercase tracking-widest">Terapkan</button>
                @if($filterGuru || $filterRombel)
                    <a href="{{ route('admin.jadwal.index') }}" class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-rose-50 dark:bg-rose-900/20 text-rose-500 hover:bg-rose-100 dark:hover:bg-rose-900/40 transition-all active:scale-95 border border-rose-100 dark:border-rose-900/30" title="Reset Filter">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- VIEW 1: Kalender Grid View (Refined & Compact) --}}
    <div x-show="viewMode === 'calendar'" class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden transition-all">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full min-w-[1200px] text-sm table-fixed border-collapse border border-slate-200 dark:border-zinc-800">
                <thead class="bg-slate-50 dark:bg-zinc-800 text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-200 font-black">
                    <tr>
                        <th class="py-4 px-4 text-center w-28 border border-slate-200 dark:border-zinc-800 bg-slate-100 dark:bg-zinc-700/50">Jam</th>
                        @foreach($hariList as $hari)
                        <th class="py-3 px-2 text-center border border-slate-200 dark:border-zinc-800 {{ (now()->locale('id')->isoFormat('dddd') === $hari && $hari !== 'Minggu') ? 'bg-indigo-600 text-white' : 'bg-slate-50 dark:bg-zinc-800 text-slate-600 dark:text-slate-300' }}">
                            <div class="font-black text-xs">{{ $hari }}</div>
                            <div class="text-[9px] opacity-85 mt-0.5 font-bold tracking-tight lowercase first-letter:uppercase">{{ $datesOfWeek[$hari] ?? '' }}</div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900">
                    @foreach($timeSlots as $slot)
                    <tr class="hover:bg-slate-50/20 dark:hover:bg-zinc-800/10 transition-colors">
                        {{-- Label Jam Kolom Kiri --}}
                        <td class="py-4 px-4 text-[11px] font-black font-mono text-slate-500 dark:text-slate-400 align-middle border border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50 text-center shadow-inner">
                            {{ $slot }}
                        </td>
                        
                        {{-- Sel Hari --}}
                        @foreach($hariList as $hari)
                        <td class="p-2.5 align-top min-h-[110px] h-32 {{ (now()->locale('id')->isoFormat('dddd') === $hari && $hari !== 'Minggu') ? 'bg-indigo-50/5 dark:bg-indigo-955/5' : '' }} relative border border-slate-200 dark:border-zinc-800 group">
                            @php
                                $slotJadwals = $jadwals->filter(function($j) use ($hari, $slot) {
                                    return $j->hari === $hari && \Carbon\Carbon::parse($j->jam_mulai)->format('H:00') === $slot;
                                });
                            @endphp
                            
                            <div class="space-y-2">
                                @foreach($slotJadwals as $j)
                                <div @click="openEditModal({{ $j->toJson() }})"
                                    class="group/card p-3 rounded-2xl cursor-pointer transition-all hover:scale-[1.03] hover:shadow-lg active:scale-95 ring-1 ring-black/5 dark:ring-white/5 border-l-[5px]
                                        {{ ['border-indigo-500 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/40',
                                            'border-emerald-500 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:hover:bg-emerald-900/40',
                                            'border-amber-500 bg-amber-50 hover:bg-amber-100 dark:bg-amber-900/20 dark:hover:bg-amber-900/40',
                                            'border-rose-500 bg-rose-50 hover:bg-rose-100 dark:bg-rose-900/20 dark:hover:bg-rose-900/40',
                                            'border-purple-500 bg-purple-50 hover:bg-purple-100 dark:bg-purple-900/20 dark:hover:bg-purple-900/40',
                                            'border-cyan-500 bg-cyan-50 hover:bg-cyan-100 dark:bg-cyan-900/20 dark:hover:bg-cyan-900/40',
                                           ][$j->id % 6] }}">
                                    
                                    {{-- Subject Name --}}
                                    <div class="flex items-start justify-between gap-1.5">
                                        <p class="font-extrabold text-[11px] text-slate-900 dark:text-white leading-tight break-words group-hover/card:text-indigo-600 dark:group-hover/card:text-indigo-400 transition-colors">
                                            {{ $j->mataPelajaran?->nama ?? ($j->guru?->matapelajaran ?? 'Sesi Belajar') }}
                                        </p>
                                    </div>
                                    
                                    {{-- Time Details --}}
                                    <div class="flex items-center gap-1 mt-1 text-[9px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-tighter">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</span>
                                    </div>
                                    
                                    {{-- Guru & Room Info --}}
                                    <div class="mt-2 space-y-0.5 border-t border-slate-200/50 dark:border-zinc-800/50 pt-1.5">
                                        <p class="text-[9px] font-bold text-slate-600 dark:text-slate-400 truncate flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 shrink-0"></span>
                                            {{ $j->guru?->name ?? '-' }}
                                        </p>
                                        <p class="text-[9px] font-bold text-slate-500 dark:text-slate-400 truncate flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300 shrink-0"></span>
                                            {{ $j->rombel?->nama_kelompok ?? '-' }} {{ $j->ruangan ? '('.$j->ruangan.')' : '' }}
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- VIEW 2: Tampilan Agenda List View (Sangat User-Friendly & Mobile-Responsive) --}}
    <div x-show="viewMode === 'list'" x-cloak class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 transition-all">
        @foreach($hariList as $hari)
        @php
            $hariJadwals = $jadwals->filter(fn($j) => $j->hari === $hari)->sortBy('jam_mulai');
        @endphp
        <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-6 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col min-h-[300px]">
            {{-- Card Header --}}
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-zinc-800 mb-4">
                <div class="flex flex-col gap-0.5">
                    <h3 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full {{ (now()->locale('id')->isoFormat('dddd') === $hari && $hari !== 'Minggu') ? 'bg-emerald-500' : 'bg-indigo-500' }}"></span>
                        {{ $hari }}
                        @if(now()->locale('id')->isoFormat('dddd') === $hari && $hari !== 'Minggu')
                            <span class="text-[9px] bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 px-2 py-0.5 rounded-md uppercase font-black tracking-wider">Hari Ini</span>
                        @endif
                    </h3>
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 ml-5">{{ $datesOfWeek[$hari] ?? '' }}</span>
                </div>
                <span class="text-[9px] font-black px-3 py-1 bg-slate-50 dark:bg-zinc-950 text-slate-500 dark:text-slate-400 rounded-full border border-slate-200 dark:border-zinc-800">
                    {{ $hariJadwals->count() }} Kelas
                </span>
            </div>

            {{-- Card Content / List --}}
            <div class="flex-1 space-y-3 overflow-y-auto max-h-[350px] pr-1 scrollbar-thin">
                @forelse($hariJadwals as $j)
                <div @click="openEditModal({{ $j->toJson() }})"
                    class="group p-4 bg-slate-50 hover:bg-indigo-50/30 dark:bg-zinc-950 dark:hover:bg-zinc-800 rounded-2xl border border-slate-100 dark:border-zinc-800/80 cursor-pointer transition-all active:scale-[0.98] hover:shadow-md">
                    
                    <div class="flex items-start justify-between gap-3">
                        <p class="font-extrabold text-sm text-slate-800 dark:text-white leading-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                            {{ $j->mataPelajaran?->nama ?? ($j->guru?->matapelajaran ?? 'Sesi Belajar') }}
                        </p>
                        <span class="shrink-0 text-[10px] font-mono font-black px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg">
                            {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}
                        </span>
                    </div>

                    {{-- Waktu details --}}
                    <div class="flex items-center gap-1.5 mt-2 text-[10px] font-bold text-slate-500 dark:text-slate-400">
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</span>
                    </div>

                    {{-- Info Badges --}}
                    <div class="mt-3 pt-3 border-t border-slate-200/40 dark:border-zinc-800/40 grid grid-cols-2 gap-2 text-[10px] font-bold text-slate-500 dark:text-slate-400">
                        <div class="truncate flex items-center gap-1.5">
                            <div class="w-4 h-4 rounded bg-slate-300 dark:bg-zinc-800 flex items-center justify-center text-[8px] font-black uppercase text-slate-500 shrink-0">G</div>
                            <span class="truncate" title="{{ $j->guru?->name }}">{{ $j->guru?->name ?? '-' }}</span>
                        </div>
                        <div class="truncate flex items-center gap-1.5">
                            <div class="w-4 h-4 rounded bg-slate-300 dark:bg-zinc-800 flex items-center justify-center text-[8px] font-black uppercase text-slate-500 shrink-0">R</div>
                            <span class="truncate" title="{{ $j->rombel?->nama_kelompok }}">{{ $j->rombel?->nama_kelompok ?? '-' }}</span>
                        </div>
                    </div>

                    @if($j->ruangan)
                    <div class="mt-2.5 text-[9px] font-black text-indigo-600 dark:text-indigo-400 flex items-center gap-1 uppercase tracking-wider">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Ruangan: {{ $j->ruangan }}</span>
                    </div>
                    @endif
                </div>
                @empty
                <div class="flex-1 flex flex-col items-center justify-center text-center p-8 bg-slate-50/50 dark:bg-zinc-900/20 rounded-[2rem] border border-dashed border-slate-200 dark:border-zinc-800/80 my-auto">
                    <svg class="w-8 h-8 text-slate-400 dark:text-zinc-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <p class="text-xs font-black text-slate-400 dark:text-zinc-600">Tidak Ada Jadwal Kelas</p>
                </div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>

    {{-- Slide-Over Panel Tambah/Edit (Drawer Premium) --}}
    <div x-show="showModal" 
         x-cloak 
         class="fixed inset-0 z-[60] overflow-hidden" 
         aria-labelledby="slide-over-title" 
         role="dialog" 
         aria-modal="true">
        <div class="absolute inset-0 overflow-hidden">
            <!-- Backdrop -->
            <div x-show="showModal"
                 x-transition:enter="ease-in-out duration-500"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-500"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showModal = false"
                 class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

            <!-- Panel Container -->
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div x-show="showModal"
                     x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                     x-transition:enter-start="translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="translate-x-full"
                     @click.stop
                     class="pointer-events-auto w-screen max-w-xl bg-white dark:bg-zinc-900 shadow-2xl border-l border-slate-100 dark:border-zinc-800 flex flex-col h-full overflow-hidden">
                    
                    <!-- Header -->
                    <div class="px-8 py-6 bg-slate-50 dark:bg-zinc-950 border-b border-slate-200 dark:border-zinc-800 flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight" x-text="editId ? 'Edit Sesi Jadwal' : 'Tambah Sesi Baru'"></h2>
                            <p class="text-slate-400 dark:text-slate-500 text-xs font-bold mt-0.5">Lengkapi data jadwal belajar mengajar.</p>
                        </div>
                        <button @click="showModal = false" class="w-10 h-10 rounded-2xl bg-white dark:bg-zinc-900 hover:bg-slate-100 dark:hover:bg-zinc-800 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-all border border-slate-200/60 dark:border-zinc-800 shadow-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Scrollable Body / Form -->
                    <form :action="editId ? '{{ url('admin/jadwal') }}/' + editId : '{{ route('admin.jadwal.store') }}'" 
                          method="POST" 
                          class="flex-1 flex flex-col overflow-hidden">
                        @csrf
                        <template x-if="editId"><input type="hidden" name="_method" value="PUT"></template>

                        <div class="flex-1 overflow-y-auto px-8 py-8 space-y-6 custom-scrollbar">
                            
                            <!-- Alert Warning Tabrakan -->
                            <div class="bg-indigo-50/50 dark:bg-indigo-950/15 border border-indigo-100 dark:border-indigo-900/30 rounded-[2rem] p-5 flex gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-indigo-500/10 flex items-center justify-center shrink-0 text-indigo-600 dark:text-indigo-400">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-wider">Validasi Bentrok Pintar</h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-bold leading-normal">Sistem akan secara otomatis memeriksa dan memberikan peringatan jika ada jadwal mengajar guru yang bentrok/tumpang tindih.</p>
                                </div>
                            </div>

                            <div class="space-y-5">
                                {{-- Section: Tenaga Pengajar & Hari --}}
                                <div class="bg-slate-50 dark:bg-zinc-950/40 p-6 rounded-[2rem] border border-slate-100 dark:border-zinc-800 space-y-4">
                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-500 uppercase tracking-widest ml-1">Tenaga Pengajar <span class="text-rose-500">*</span></label>
                                        <div class="relative" x-data="{ open: false, search: '' }" @click.away="open = false">
                                            <!-- Tombol Trigger -->
                                            <div @click="open = !open" 
                                                 class="w-full bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 flex justify-between items-center cursor-pointer focus:ring-2 focus:ring-indigo-500/20 transition-all text-slate-800 dark:text-white group hover:border-indigo-300 dark:hover:border-indigo-700">
                                                <div class="flex flex-col gap-0.5">
                                                    <span x-text="form.guru_id ? guruList.find(g => g.id == form.guru_id)?.name : 'Pilih Tenaga Pengajar'" 
                                                          :class="form.guru_id ? 'text-slate-900 dark:text-white' : 'text-slate-400 dark:text-slate-500'"></span>
                                                    <template x-if="form.guru_id && guruList.find(g => g.id == form.guru_id)?.matapelajaran">
                                                        <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest" x-text="'Mapel: ' + guruList.find(g => g.id == form.guru_id)?.matapelajaran"></span>
                                                    </template>
                                                </div>
                                                <div class="text-slate-400 group-hover:text-indigo-500 transition-colors">
                                                    <svg class="w-4 h-4 transform transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                                </div>
                                            </div>

                                            <!-- Dropdown Pop-up Premium -->
                                            <div x-show="open" 
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 translate-y-[-10px]"
                                                 x-transition:enter-end="opacity-100 translate-y-0"
                                                 x-transition:leave="transition ease-in duration-150"
                                                 x-transition:leave-start="opacity-100 translate-y-0"
                                                 x-transition:leave-end="opacity-0 translate-y-[-10px]"
                                                 class="absolute z-50 w-full mt-2 bg-white dark:bg-zinc-800 rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] dark:shadow-none border border-slate-100 dark:border-zinc-700 overflow-hidden flex flex-col">
                                                
                                                <!-- Search Bar dalam Dropdown -->
                                                <div class="p-3 border-b border-slate-100 dark:border-zinc-700 bg-slate-50/50 dark:bg-zinc-900/50">
                                                    <div class="relative">
                                                        <input type="text" x-model="search" placeholder="Cari nama guru..." class="w-full bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-xl text-xs py-2.5 pl-9 pr-3 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-700 dark:text-slate-300">
                                                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                                    </div>
                                                </div>

                                                <!-- List Guru -->
                                                <div class="max-h-60 overflow-y-auto custom-scrollbar p-2">
                                                    <template x-for="g in guruList.filter(g => g.name.toLowerCase().includes(search.toLowerCase()))" :key="g.id">
                                                        <div @click="form.guru_id = g.id; open = false; search = ''" 
                                                             :class="form.guru_id == g.id ? 'bg-indigo-50 dark:bg-indigo-900/20 border-indigo-100 dark:border-indigo-800/30' : 'hover:bg-slate-50 dark:hover:bg-zinc-700/50 border-transparent'"
                                                             class="p-3 rounded-xl cursor-pointer transition-all border flex items-center gap-3 group/item mb-1">
                                                            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center shrink-0 text-slate-500 font-black group-hover/item:bg-indigo-100 group-hover/item:text-indigo-600 transition-colors">
                                                                <span x-text="g.name.charAt(0)"></span>
                                                            </div>
                                                            <div class="flex-1">
                                                                <div class="font-bold text-sm text-slate-800 dark:text-slate-200" x-text="g.name"></div>
                                                                <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 mt-0.5 flex items-center gap-1">
                                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                                                    <span x-text="g.matapelajaran ? g.matapelajaran : 'Belum diatur mapel'"></span>
                                                                </div>
                                                            </div>
                                                            <div x-show="form.guru_id == g.id" class="text-indigo-600 dark:text-indigo-400">
                                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    
                                                    <!-- Empty State -->
                                                    <div x-show="guruList.filter(g => g.name.toLowerCase().includes(search.toLowerCase())).length === 0" class="py-6 text-center">
                                                        <p class="text-xs text-slate-400 font-bold">Guru tidak ditemukan</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Hidden input to submit form -->
                                            <input type="hidden" name="guru_id" x-model="form.guru_id" required>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-500 uppercase tracking-widest ml-1">Hari Pelaksanaan <span class="text-rose-500">*</span></label>
                                        <div class="relative">
                                            <select name="hari" x-model="form.hari" required class="w-full bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white appearance-none">
                                                <option value="">Pilih Hari</option>
                                                @foreach($hariList as $h)<option value="{{ $h }}">{{ $h }}</option>@endforeach
                                            </select>
                                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Section: Detail Kelas --}}
                                <div class="bg-slate-50 dark:bg-zinc-950/40 p-6 rounded-[2rem] border border-slate-100 dark:border-zinc-800">
                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-500 uppercase tracking-widest ml-1">Kelompok Belajar</label>
                                        <div class="relative">
                                            <select name="rombel_id" x-model="form.rombel_id" class="w-full bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white appearance-none">
                                                <option value="">— Opsional —</option>
                                                @foreach($rombelList as $r)<option value="{{ $r->id }}">{{ $r->nama_kelompok }}</option>@endforeach
                                            </select>
                                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Section: Waktu & Tempat --}}
                                <div class="bg-slate-50 dark:bg-zinc-950/40 p-6 rounded-[2rem] border border-slate-100 dark:border-zinc-800 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-500 uppercase tracking-widest ml-1">Jam Mulai <span class="text-rose-500">*</span></label>
                                        <input type="time" name="jam_mulai" x-model="form.jam_mulai" required class="w-full bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-500 uppercase tracking-widest ml-1">Jam Selesai <span class="text-rose-500">*</span></label>
                                        <input type="time" name="jam_selesai" x-model="form.jam_selesai" required class="w-full bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                                    </div>

                                    <div class="sm:col-span-2 space-y-2">
                                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-500 uppercase tracking-widest ml-1">Ruangan / Tempat</label>
                                        <input type="text" name="ruangan" x-model="form.ruangan" placeholder="Contoh: Lab Komputer, Ruang Kelas A" class="w-full bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sticky Footer -->
                        <div class="px-8 py-6 bg-slate-50 dark:bg-zinc-950 border-t border-slate-200 dark:border-zinc-800 flex items-center justify-between shrink-0">
                            <template x-if="editId">
                                <button type="button" @click="hapusJadwal()" class="inline-flex items-center gap-2 text-rose-500 hover:text-rose-700 text-[10px] font-black uppercase tracking-widest transition-all">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus Jadwal
                                </button>
                            </template>
                            <template x-if="!editId"><span></span></template>
                            <div class="flex gap-4">
                                <button type="button" @click="showModal = false" class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 hover:bg-slate-100 dark:hover:bg-zinc-800 rounded-2xl transition-all">Batal</button>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95">Simpan Jadwal</button>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>

    {{-- Modal Duplikasi --}}
    <div x-show="showDuplikasi" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md" @click="showDuplikasi = false"></div>
        <div class="relative bg-white dark:bg-zinc-900 rounded-[3rem] shadow-2xl border border-slate-200/50 dark:border-zinc-700/50 w-full max-w-md overflow-hidden" @click.stop>
            <div class="p-10">
                <div class="w-16 h-16 bg-amber-50 dark:bg-amber-900/20 rounded-[1.5rem] flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Duplikasi Jadwal</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 font-medium">Salin seluruh struktur jadwal dari periode saat ini ke periode tujuan.</p>
                
                <form action="{{ route('admin.jadwal.duplikasi') }}" method="POST" class="mt-8 space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Periode Tujuan</label>
                        <select name="target_periode_id" required class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all appearance-none text-slate-800 dark:text-white">
                            <option value="">-- Pilih Periode --</option>
                            @foreach(\App\Models\Periode::all() as $p)
                            <option value="{{ $p->id }}">{{ $p->tahun_periode ?? 'Periode #'.$p->id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-4 pt-4">
                        <button type="button" @click="showDuplikasi = false" class="flex-1 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 hover:bg-slate-50 dark:hover:bg-zinc-800 rounded-2xl transition-all">Batal</button>
                        <button type="submit" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white text-[10px] font-black uppercase tracking-widest px-8 py-4 rounded-2xl shadow-xl shadow-amber-500/20 transition-all active:scale-95">Mulai Salin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Hidden Delete Form --}}
    <form x-ref="deleteForm" method="POST" style="display:none;">
        @csrf @method('DELETE')
    </form>
</div>

@push('head')
<style>
    [x-cloak] { display: none !important; }
    
    /* Modern Scrollbar Styling */
    .custom-scrollbar::-webkit-scrollbar {
        height: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.3);
        border-radius: 9999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(156, 163, 175, 0.5);
    }
</style>
@endpush

<script>
function jadwalManager() {
    return {
        viewMode: 'calendar', // Default view mode
        showModal: false,
        showDuplikasi: false,
        editId: null,
        guruList: @json($guruList),
        form: { guru_id: '', rombel_id: '', mata_pelajaran_id: '', hari: '', jam_mulai: '', jam_selesai: '', ruangan: '' },

        openAddModal() {
            this.editId = null;
            this.form = { guru_id: '', rombel_id: '', mata_pelajaran_id: '', hari: '', jam_mulai: '', jam_selesai: '', ruangan: '' };
            this.showModal = true;
        },

        openEditModal(jadwal) {
            this.editId = jadwal.id;
            this.form = {
                guru_id: jadwal.guru_id || '',
                rombel_id: jadwal.rombel_id || '',
                mata_pelajaran_id: jadwal.mata_pelajaran_id || '',
                hari: jadwal.hari || '',
                jam_mulai: jadwal.jam_mulai ? jadwal.jam_mulai.substring(0, 5) : '',
                jam_selesai: jadwal.jam_selesai ? jadwal.jam_selesai.substring(0, 5) : '',
                ruangan: jadwal.ruangan || '',
            };
            this.showModal = true;
        },

        hapusJadwal() {
            Swal.fire({
                title: 'Hapus Jadwal?',
                text: "Yakin ingin menghapus jadwal ini? Data tidak dapat dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
                customClass: {
                    popup: 'rounded-2xl border border-slate-100 dark:border-slate-700',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = this.$refs.deleteForm;
                    form.action = '{{ url("admin/jadwal") }}/' + this.editId;
                    form.submit();
                }
            });
        }
    };
}
</script>
@endsection
