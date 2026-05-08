@extends('layouts.guru')
@section('title', 'Jadwal Mengajar')
@section('content')

<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white">Jadwal Mengajar</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Jadwal pelajaran yang ditugaskan kepada Anda</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl text-sm font-medium">
        {{ session('success') }}
    </div>
    @endif

    {{-- JADWAL HARI INI (Quick Widget) --}}
    @php
        $hariIni = now()->locale('id')->isoFormat('dddd');
        $jadwalHariIni = $jadwal->filter(fn($j) => $j->hari === $hariIni)->sortBy('jam_mulai');
    @endphp
    <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl p-6 text-white shadow-xl shadow-indigo-200 dark:shadow-none">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h2 class="font-bold text-lg">Jadwal Hari Ini — {{ $hariIni }}</h2>
                <p class="text-indigo-200 text-sm">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</p>
            </div>
        </div>
        @if($jadwalHariIni->isEmpty())
            <div class="bg-white/10 rounded-xl p-4 text-center">
                <p class="text-indigo-200 font-medium">Tidak ada jadwal mengajar hari ini 🎉</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach($jadwalHariIni as $j)
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 flex items-center gap-4 hover:bg-white/15 transition-colors">
                    <div class="text-center min-w-[70px]">
                        <div class="text-sm font-bold">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}</div>
                        <div class="text-xs text-indigo-200">{{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</div>
                    </div>
                    <div class="h-10 w-px bg-white/20"></div>
                    <div class="flex-1 min-w-0">
                        <div class="font-bold truncate">{{ $j->mataPelajaran?->nama ?? '-' }}</div>
                        <div class="text-xs text-indigo-200 truncate">{{ $j->rombel?->nama_kelompok ?? '-' }} {{ $j->ruangan ? '· '.$j->ruangan : '' }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- KALENDER MINGGUAN --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-zinc-800">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">Jadwal Mingguan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="bg-slate-50 dark:bg-zinc-800/50">
                        <th class="py-3 px-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider w-20">Jam</th>
                        @foreach(\App\Models\Jadwal::HARI_LIST as $hari)
                        <th class="py-3 px-3 text-center text-xs font-bold uppercase tracking-wider {{ $hariIni === $hari ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'text-slate-500 dark:text-slate-400' }}">
                            {{ $hari }}
                            @if($hariIni === $hari)
                            <span class="block text-[10px] text-indigo-500 font-medium">Hari Ini</span>
                            @endif
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $timeSlots = [];
                        for ($h = 7; $h <= 20; $h++) { $timeSlots[] = sprintf('%02d:00', $h); }
                    @endphp
                    @foreach($timeSlots as $slot)
                    <tr class="border-t border-slate-100 dark:border-zinc-800">
                        <td class="py-2 px-4 text-xs font-mono text-slate-400 dark:text-slate-500 align-top">{{ $slot }}</td>
                        @foreach(\App\Models\Jadwal::HARI_LIST as $hari)
                        <td class="py-1 px-1 align-top {{ $hariIni === $hari ? 'bg-indigo-50/50 dark:bg-indigo-900/10' : '' }}">
                            @php
                                $slotItems = $jadwal->filter(function($j) use ($hari, $slot) {
                                    return $j->hari === $hari && \Carbon\Carbon::parse($j->jam_mulai)->format('H:00') === $slot;
                                });
                            @endphp
                            @foreach($slotItems as $j)
                            <div class="mb-1 rounded-lg p-2 text-xs {{ ['bg-indigo-100 dark:bg-indigo-900/40 border-l-4 border-indigo-500 text-indigo-800 dark:text-indigo-200','bg-emerald-100 dark:bg-emerald-900/40 border-l-4 border-emerald-500 text-emerald-800 dark:text-emerald-200','bg-amber-100 dark:bg-amber-900/40 border-l-4 border-amber-500 text-amber-800 dark:text-amber-200','bg-rose-100 dark:bg-rose-900/40 border-l-4 border-rose-500 text-rose-800 dark:text-rose-200','bg-purple-100 dark:bg-purple-900/40 border-l-4 border-purple-500 text-purple-800 dark:text-purple-200'][$j->id % 5] }}">
                                <div class="font-bold truncate">{{ $j->mataPelajaran?->nama ?? '-' }}</div>
                                <div class="text-[10px] opacity-75 mt-0.5">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</div>
                                <div class="text-[10px] opacity-75 truncate">{{ $j->rombel?->nama_kelompok ?? '-' }} {{ $j->ruangan ? '· '.$j->ruangan : '' }}</div>
                            </div>
                            @endforeach
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($jadwal->isEmpty())
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p class="text-slate-400 dark:text-slate-500 font-medium">Belum ada jadwal yang ditugaskan</p>
            <p class="text-slate-400 dark:text-slate-500 text-sm mt-1">Hubungi admin untuk pengelolaan jadwal</p>
        </div>
        @endif
    </div>
</div>

@endsection