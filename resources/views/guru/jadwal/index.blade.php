@extends('layouts.guru')

@section('title', 'Jadwal Mengajar')

@section('content')
@php
    // Cerdas: Menghitung jam minimum & maksimum aktif agar tabel adaptif & tidak boros ruang vertikal
    $minHour = 13; // default bimbel siang
    $maxHour = 19; // default bimbel sore/malam
    foreach ($jadwal as $j) {
        if ($j->jam_mulai) {
            $startHour = (int) \Carbon\Carbon::parse($j->jam_mulai)->format('H');
            if ($startHour < $minHour && $startHour >= 6) {
                $minHour = $startHour;
            }
        }
        if ($j->jam_selesai) {
            $endHour = (int) \Carbon\Carbon::parse($j->jam_selesai)->format('H');
            if ($endHour > $maxHour && $endHour <= 22) {
                $maxHour = $endHour;
            }
        }
    }
    
    $timeSlots = [];
    for ($h = $minHour; $h <= $maxHour; $h++) { 
        $timeSlots[] = sprintf('%02d:00', $h); 
    }
    $hariIni = now()->locale('id')->isoFormat('dddd');
    $hariList = \App\Models\Jadwal::HARI_LIST;
    $jadwalHariIni = $jadwal->filter(fn($j) => $j->hari === $hariIni)->sortBy('jam_mulai');

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

<div x-data="{ viewMode: 'calendar' }" class="space-y-6">
    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Jadwal Mengajar</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Jadwal pelajaran yang ditugaskan kepada Anda pada periode aktif.</p>
        </div>
        <div class="flex items-center bg-slate-100 dark:bg-zinc-950 p-1 rounded-2xl border border-slate-200/50 dark:border-zinc-800/50 shadow-inner shrink-0">
            <button @click="viewMode = 'calendar'" 
                :class="viewMode === 'calendar' ? 'bg-white dark:bg-zinc-800 text-indigo-650 dark:text-white shadow-sm' : 'text-slate-450 hover:text-slate-700 dark:text-slate-400'"
                class="inline-flex items-center gap-2 font-black text-xs px-5 py-3 rounded-xl transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Kalender Grid
            </button>
            <button @click="viewMode = 'list'" 
                :class="viewMode === 'list' ? 'bg-white dark:bg-zinc-800 text-indigo-650 dark:text-white shadow-sm' : 'text-slate-450 hover:text-slate-700 dark:text-slate-400'"
                class="inline-flex items-center gap-2 font-black text-xs px-5 py-3 rounded-xl transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                List Agenda
            </button>
        </div>
    </div>


    {{-- JADWAL HARI INI (Quick Widget Premium) --}}
    <div class="bg-gradient-to-br from-indigo-600 via-indigo-650 to-indigo-700 dark:from-zinc-950 dark:via-indigo-950/40 dark:to-zinc-950 rounded-[2.5rem] p-8 text-white shadow-xl shadow-indigo-600/10 dark:shadow-none relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-48 h-48 bg-white/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-125 duration-700"></div>
        <div class="flex items-center gap-4 mb-6 relative">
            <div class="w-12 h-12 bg-white/10 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center border border-white/10 dark:border-indigo-800/30">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h2 class="font-black text-xl tracking-tight">Jadwal Mengajar Hari Ini — {{ $hariIni }}</h2>
                <p class="text-indigo-200 dark:text-slate-400 text-xs font-bold mt-0.5 uppercase tracking-wider">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</p>
            </div>
        </div>
        
        @if($jadwalHariIni->isEmpty())
            <div class="bg-white/5 dark:bg-zinc-900/40 rounded-2xl p-6 text-center border border-white/5 dark:border-zinc-800 relative">
                <p class="text-indigo-100 dark:text-slate-400 text-sm font-black">Tidak ada jadwal mengajar untuk hari ini. Waktunya istirahat! 🎉</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 relative">
                @foreach($jadwalHariIni as $j)
                <div class="bg-white/10 dark:bg-zinc-900/60 backdrop-blur-md rounded-2xl p-5 flex items-center gap-5 hover:bg-white/15 dark:hover:bg-zinc-850 transition-all border border-white/10 dark:border-zinc-800 shadow-sm">
                    <div class="text-center min-w-[70px] bg-white/15 dark:bg-indigo-950/40 p-2.5 rounded-xl border border-white/10 dark:border-indigo-900/30">
                        <div class="text-xs font-black tracking-tight text-white">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}</div>
                        <div class="text-[10px] text-indigo-200 dark:text-indigo-400 font-bold mt-0.5">{{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-black text-sm truncate text-white leading-tight">{{ $j->mataPelajaran?->nama ?? '-' }}</div>
                        <div class="text-xs text-indigo-200 dark:text-slate-400 truncate font-bold mt-1.5 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            {{ $j->rombel?->nama_kelompok ?? '-' }} {{ $j->ruangan ? '· '.$j->ruangan : '' }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- VIEW 1: Kalender Grid (Refined & Compact) --}}
    <div x-show="viewMode === 'calendar'" class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden transition-all">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full min-w-[1000px] text-sm table-fixed border-collapse border border-slate-150 dark:border-zinc-800">
                <thead class="bg-slate-50 dark:bg-zinc-800 text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-200 font-black">
                    <tr>
                        <th class="py-4 px-4 text-center w-28 border border-slate-200 dark:border-zinc-800 bg-slate-100 dark:bg-zinc-700/50">Jam</th>
                        @foreach($hariList as $hari)
                        <th class="py-3 px-2 text-center border border-slate-200 dark:border-zinc-800 {{ ($hariIni === $hari && $hari !== 'Minggu') ? 'bg-indigo-600 text-white' : 'bg-slate-50 dark:bg-zinc-800 text-slate-655 dark:text-slate-300' }}">
                            <div class="font-black text-xs">{{ $hari }}</div>
                            <div class="text-[9px] opacity-85 mt-0.5 font-bold tracking-tight lowercase first-letter:uppercase">{{ $datesOfWeek[$hari] ?? '' }}</div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900">
                    @foreach($timeSlots as $slot)
                    <tr class="hover:bg-slate-50/20 dark:hover:bg-zinc-850/10 transition-colors">
                        {{-- Label Jam Kolom Kiri --}}
                        <td class="py-4 px-4 text-[11px] font-black font-mono text-slate-500 dark:text-slate-400 align-middle border border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50 text-center shadow-inner">
                            {{ $slot }}
                        </td>
                        
                        {{-- Sel Hari --}}
                        @foreach($hariList as $hari)
                        <td class="p-2.5 align-top min-h-[110px] h-32 {{ ($hariIni === $hari && $hari !== 'Minggu') ? 'bg-indigo-50/5 dark:bg-indigo-955/5' : '' }} relative border border-slate-150 dark:border-zinc-800 group">
                            @php
                                $slotItems = $jadwal->filter(function($j) use ($hari, $slot) {
                                    return $j->hari === $hari && \Carbon\Carbon::parse($j->jam_mulai)->format('H:00') === $slot;
                                });
                            @endphp
                            
                            <div class="space-y-2">
                                @foreach($slotItems as $j)
                                <div class="group/card p-3 rounded-2xl border-l-[5px] ring-1 ring-black/5 dark:ring-white/5 border-indigo-500 bg-indigo-50/40 hover:bg-indigo-50/80 dark:bg-indigo-950/20 dark:hover:bg-indigo-950/40 transition-all hover:scale-[1.03] hover:shadow-md">
                                    <p class="font-extrabold text-[11px] text-slate-900 dark:text-white leading-tight break-words">
                                        {{ $j->mataPelajaran?->nama ?? 'Sesi Belajar' }}
                                    </p>
                                    <div class="flex items-center gap-1 mt-1.5 text-[9px] font-black text-slate-450 dark:text-slate-400 uppercase tracking-tighter">
                                        <svg class="w-3 h-3 text-slate-450" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</span>
                                    </div>
                                    <div class="mt-2 space-y-0.5 border-t border-slate-200/50 dark:border-zinc-800/50 pt-1.5">
                                        <p class="text-[9px] font-bold text-slate-550 dark:text-slate-400 truncate flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 shrink-0"></span>
                                            Kelas: {{ $j->rombel?->nama_kelompok ?? '-' }}
                                        </p>
                                        @if($j->ruangan)
                                        <p class="text-[9px] font-bold text-slate-500 dark:text-slate-400 truncate flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-350 shrink-0"></span>
                                            Ruang: {{ $j->ruangan }}
                                        </p>
                                        @endif
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

        @if($jadwal->isEmpty())
        <div class="p-16 text-center bg-white dark:bg-zinc-900">
            <svg class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p class="text-slate-400 dark:text-slate-500 font-black">Belum Ada Jadwal Mengajar</p>
            <p class="text-slate-400 dark:text-slate-500 text-xs mt-1.5 font-semibold">Hubungi tim administrator pusat untuk penugasan kelas belajar Anda.</p>
        </div>
        @endif
    </div>

    {{-- VIEW 2: Tampilan Agenda List --}}
    <div x-show="viewMode === 'list'" x-cloak class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 transition-all">
        @foreach($hariList as $hari)
        @php
            $hariJadwals = $jadwal->filter(fn($j) => $j->hari === $hari)->sortBy('jam_mulai');
        @endphp
        <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-6 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col min-h-[300px]">
            {{-- Card Header --}}
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-zinc-800 mb-4">
                <h3 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full {{ ($hariIni === $hari && $hari !== 'Minggu') ? 'bg-emerald-500' : 'bg-indigo-500' }}"></span>
                    {{ $hari }}
                    @if($hariIni === $hari && $hari !== 'Minggu')
                        <span class="text-[9px] bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 px-2 py-0.5 rounded-md uppercase font-black tracking-wider">Hari Ini</span>
                    @endif
                </h3>
                <span class="text-[9px] font-black px-3 py-1 bg-slate-50 dark:bg-zinc-950 text-slate-500 dark:text-slate-400 rounded-full border border-slate-150 dark:border-zinc-850">
                    {{ $hariJadwals->count() }} Kelas
                </span>
            </div>

            {{-- Card Content --}}
            <div class="flex-1 space-y-3 overflow-y-auto max-h-[350px] pr-1 scrollbar-thin">
                @forelse($hariJadwals as $j)
                <div class="group p-4 bg-slate-50 hover:bg-indigo-50/30 dark:bg-zinc-950 dark:hover:bg-zinc-850 rounded-2xl border border-slate-100 dark:border-zinc-850/80 transition-all shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <p class="font-extrabold text-sm text-slate-850 dark:text-white leading-tight group-hover:text-indigo-650 dark:group-hover:text-indigo-400 transition-colors">
                            {{ $j->mataPelajaran?->nama ?? 'Sesi Belajar' }}
                        </p>
                        <span class="shrink-0 text-[10px] font-mono font-black px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg">
                            {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}
                        </span>
                    </div>

                    {{-- Waktu --}}
                    <div class="flex items-center gap-1.5 mt-2 text-[10px] font-bold text-slate-450 dark:text-slate-400">
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</span>
                    </div>

                    {{-- Detail Rombel & Ruangan --}}
                    <div class="mt-3 pt-3 border-t border-slate-200/40 dark:border-zinc-800/40 flex items-center justify-between text-[10px] font-bold text-slate-500 dark:text-slate-400">
                        <div class="truncate flex items-center gap-1.5">
                            <div class="w-4 h-4 rounded bg-slate-250 dark:bg-zinc-850 flex items-center justify-center text-[8px] font-black uppercase text-slate-500">R</div>
                            <span class="truncate">{{ $j->rombel?->nama_kelompok ?? '-' }}</span>
                        </div>
                        @if($j->ruangan)
                        <div class="truncate flex items-center gap-1.5">
                            <div class="w-4 h-4 rounded bg-slate-250 dark:bg-zinc-850 flex items-center justify-center text-[8px] font-black uppercase text-slate-500">L</div>
                            <span class="truncate">{{ $j->ruangan }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="flex-1 flex flex-col items-center justify-center text-center p-8 bg-slate-50/50 dark:bg-zinc-950/20 rounded-[2rem] border border-dashed border-slate-200 dark:border-zinc-800/80 my-auto">
                    <svg class="w-8 h-8 text-slate-350 dark:text-zinc-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <p class="text-xs font-black text-slate-400 dark:text-zinc-650">Tidak Ada Kelas Mengajar</p>
                </div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
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