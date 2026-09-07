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
    $hariList = \App\Models\Akademik\Jadwal::HARI_LIST;

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

<div x-data="{ viewMode: 'calendar', showDuplikasi: false, ...ajaxTable() }" class="space-y-6">

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
            <a href="{{ route('admin.jadwal.create') }}"
                class="inline-flex items-center gap-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95 group">
                <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Tambah Jadwal
            </a>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>

        {{-- Loading Overlay --}}
        <x-table.loading-overlay />

        <form @submit.prevent="fetchData" method="GET" action="{{ route('admin.jadwal.index') }}" class="flex flex-col lg:flex-row items-end gap-6 relative">
            <div class="flex-1 w-full space-y-3">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Filter Tenaga Pengajar</label>
                <div class="relative">
                    <select name="guru_id" @change="fetchData" class="no-tomselect w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all appearance-none text-slate-800 dark:text-white">
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
                    <select name="rombel_id" @change="fetchData" class="no-tomselect w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all appearance-none text-slate-800 dark:text-white">
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
                <button type="submit" class="flex-1 lg:flex-none bg-slate-900 dark:bg-white dark:text-slate-900 text-white font-black text-xs px-10 py-4 rounded-2xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-all active:scale-95 shadow-lg shadow-slate-900/10 uppercase tracking-widest shrink-0">Filter</button>
                @if($filterGuru || $filterRombel)
                    <a href="{{ route('admin.jadwal.index') }}" class="flex items-center gap-2 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/40 px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-widest transition-all shrink-0" title="Reset Filter">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div id="ajax-table-body">
        {{-- VIEW 1: Kalender Grid View (Refined & Compact) --}}
        <div x-show="viewMode === 'calendar'" class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden transition-all">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full min-w-full text-sm table-fixed border-collapse border border-slate-200 dark:border-zinc-800">
                <thead class="bg-slate-50 dark:bg-zinc-800 text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-200 font-black">
                    <tr>
                        <th class="py-4 px-2 text-center w-16 border border-slate-200 dark:border-zinc-800 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-800 dark:text-indigo-300">Jam</th>
                        @foreach($hariList as $hari)
                        <th class="py-3 px-1 text-center border border-slate-200 dark:border-zinc-800 {{ (now()->locale('id')->isoFormat('dddd') === $hari && $hari !== 'Minggu') ? 'bg-indigo-600 text-white' : ($hari === 'Minggu' ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' : 'bg-slate-50 dark:bg-zinc-800 text-slate-600 dark:text-slate-300') }}">
                            <div class="font-black text-[10px] sm:text-xs">{{ $hari }}</div>
                            <div class="text-[8px] sm:text-[9px] opacity-85 mt-0.5 font-bold tracking-tight lowercase first-letter:uppercase">{{ $datesOfWeek[$hari] ?? '' }}</div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900">
                    @foreach($timeSlots as $slot)
                    <tr class="hover:bg-slate-50/20 dark:hover:bg-zinc-800/10 transition-colors">
                        {{-- Label Jam Kolom Kiri --}}
                        <td class="py-4 px-1 text-[10px] font-black font-mono text-indigo-700 dark:text-indigo-400 align-middle border border-slate-200 dark:border-zinc-800 bg-indigo-50/50 dark:bg-indigo-900/20 text-center shadow-inner">
                            {{ $slot }}
                        </td>
                        
                        {{-- Sel Hari --}}
                        @foreach($hariList as $hari)
                        <td class="p-1 align-top h-auto {{ (now()->locale('id')->isoFormat('dddd') === $hari && $hari !== 'Minggu') ? 'bg-indigo-50/5 dark:bg-indigo-955/5' : ($hari === 'Minggu' ? 'bg-red-50/30 dark:bg-red-950/10' : '') }} relative border border-slate-200 dark:border-zinc-800 group">
                            @php
                                $slotJadwals = $jadwals->filter(function($j) use ($hari, $slot) {
                                    return $j->hari === $hari && \Carbon\Carbon::parse($j->jam_mulai)->format('H:00') === $slot;
                                });
                            @endphp
                            
                            <div class="flex flex-col gap-1 h-full min-h-[5rem]">
                                @foreach($slotJadwals as $j)
                                <div @click="window.location.href = '{{ route('admin.jadwal.edit', $j->id) }}'"
                                    class="group/card flex-1 flex flex-col justify-center p-1.5 rounded-lg cursor-pointer transition-all hover:scale-[1.03] hover:shadow-lg active:scale-95 ring-1 ring-black/5 dark:ring-white/5 border-l-[3px]
                                        {{ ['border-indigo-500 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/40',
                                            'border-emerald-500 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:hover:bg-emerald-900/40',
                                            'border-amber-500 bg-amber-50 hover:bg-amber-100 dark:bg-amber-900/20 dark:hover:bg-amber-900/40',
                                            'border-rose-500 bg-rose-50 hover:bg-rose-100 dark:bg-rose-900/20 dark:hover:bg-rose-900/40',
                                            'border-purple-500 bg-purple-50 hover:bg-purple-100 dark:bg-purple-900/20 dark:hover:bg-purple-900/40',
                                            'border-cyan-500 bg-cyan-50 hover:bg-cyan-100 dark:bg-cyan-900/20 dark:hover:bg-cyan-900/40',
                                           ][$j->id % 6] }}">
                                    
                                    {{-- Subject Name --}}
                                    <div class="flex items-start justify-between gap-1">
                                        <p class="font-black text-[9px] text-slate-900 dark:text-white leading-none break-words group-hover/card:text-indigo-600 dark:group-hover/card:text-indigo-400 transition-colors">
                                            {{ $j->mataPelajaran?->nama ?? ($j->guru?->matapelajaran ?? 'Sesi Belajar') }}
                                        </p>
                                    </div>
                                    
                                    {{-- Time Details --}}
                                    <div class="flex items-center gap-0.5 mt-0.5 text-[7px] font-black text-slate-500 dark:text-slate-400 tracking-tighter">
                                        <span>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</span>
                                    </div>
                                    
                                    {{-- Guru & Room Info --}}
                                    <div class="mt-0.5 space-y-0 text-[7px] font-bold text-slate-600 dark:text-slate-400 leading-tight">
                                        <p class="truncate">{{ $j->guru?->name ?? '-' }}</p>
                                        <p class="truncate text-slate-500">{{ $j->rombel?->nama_kelompok ?? '-' }} {{ $j->ruangan ? '('.$j->ruangan.')' : '' }}</p>
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
                <div @click="window.location.href = '{{ route('admin.jadwal.edit', $j->id) }}'"
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
    </div>
    {{-- Modal Duplikasi --}}
    <template x-teleport="body">
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
                                @foreach(\App\Models\MasterData\Periode::all() as $p)
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
    </template>
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


@endsection

