@extends('layouts.siswa')

@section('title', 'Jadwal Pelajaran')

@push('head')
<style>
    .jadwal-scope {
        --primary: #388782;
        --primary-light: #e6f4f2;
        --text-main: #0f172a;
        --text-muted: #64748b;
        --bg-inner: transparent;
        --card: #ffffff;
        --border-soft: #e2e8f0;
    }
    .dark .jadwal-scope {
        --text-main: #f1f5f9;
        --text-muted: #94a3b8;
        --card: #18181b;
        --border-soft: #27272a;
        --primary-light: rgba(56, 135, 130, 0.15);
    }
    .jadwal-scope *, .jadwal-scope *::before, .jadwal-scope *::after { box-sizing: border-box; }

    .jadwal-scope .content { max-width: 950px; margin: 0 auto; }

    .jadwal-scope .today-hero {
        background: linear-gradient(135deg, #388782, #206D6C);
        border-radius: 1.25rem;
        padding: 24px 20px;
        color: white;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(56, 135, 130, 0.25);
    }
    .jadwal-scope .today-hero::after {
        content: ''; position: absolute; top: -40px; right: -40px;
        width: 120px; height: 120px;
        background: rgba(255,255,255,0.1); border-radius: 50%;
    }
    .jadwal-scope .today-hero h2 { font-size: 1.3rem; font-weight: 800; margin-bottom: 4px; position: relative; z-index: 2; }
    .jadwal-scope .today-hero .sub { font-size: 0.85rem; opacity: 0.9; margin-bottom: 16px; position: relative; z-index: 2; color: #A2D5CB; }
    .jadwal-scope .today-item {
        background: rgba(255,255,255,0.12);
        backdrop-filter: blur(8px);
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 14px;
        position: relative;
        z-index: 2;
    }
    .jadwal-scope .today-time { text-align: center; min-width: 55px; }
    .jadwal-scope .today-time .t-start { font-size: 0.9rem; font-weight: 800; }
    .jadwal-scope .today-time .t-end { font-size: 0.7rem; opacity: 0.85; }
    .jadwal-scope .today-sep { width: 2px; height: 36px; background: rgba(255,255,255,0.25); border-radius: 1px; }
    .jadwal-scope .today-info { flex: 1; min-width: 0; }
    .jadwal-scope .today-info .mapel { font-weight: 800; font-size: 0.95rem; }
    .jadwal-scope .today-info .detail { font-size: 0.75rem; opacity: 0.85; }
    .jadwal-scope .today-empty { text-align: center; padding: 16px; opacity: 0.9; font-weight: 600; }

    .jadwal-scope .day-tabs {
        display: flex;
        gap: 6px;
        overflow-x: auto;
        padding-bottom: 4px;
        margin-bottom: 16px;
        -webkit-overflow-scrolling: touch;
    }
    .jadwal-scope .day-tabs::-webkit-scrollbar { display: none; }
    .jadwal-scope .day-tab {
        padding: 10px 18px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 700;
        white-space: nowrap;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        background: var(--card);
        color: var(--text-muted);
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        border: 1px solid var(--border-soft);
    }
    .jadwal-scope .day-tab.active {
        background: var(--primary);
        color: white;
        box-shadow: 0 6px 15px rgba(56, 135, 130, 0.35);
        border-color: transparent;
    }
    .jadwal-scope .day-tab:hover:not(.active) { background: var(--primary-light); color: var(--primary); }

    .jadwal-scope .schedule-card {
        background: var(--card);
        border-radius: 16px;
        padding: 16px 18px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        border: 1px solid var(--border-soft);
        transition: transform 0.15s;
    }
    .jadwal-scope .schedule-card:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
    .jadwal-scope .sc-time {
        min-width: 60px;
        text-align: center;
        padding: 8px;
        border-radius: 10px;
        background: var(--primary-light);
    }
    .jadwal-scope .sc-time .sc-start { font-size: 0.95rem; font-weight: 800; color: var(--primary); }
    .jadwal-scope .sc-time .sc-end { font-size: 0.7rem; color: var(--text-muted); font-weight: 600; }
    .jadwal-scope .sc-info { flex: 1; min-width: 0; }
    .jadwal-scope .sc-info .sc-mapel { font-size: 0.95rem; font-weight: 800; color: var(--text-main); }
    .jadwal-scope .sc-info .sc-meta { font-size: 0.78rem; color: var(--text-muted); font-weight: 600; margin-top: 2px; }
    .jadwal-scope .sc-dot {
        width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
    }
    .jadwal-scope .dot-0 { background: #388782; } .jadwal-scope .dot-1 { background: #01B574; }
    .jadwal-scope .dot-2 { background: #FFCE20; } .jadwal-scope .dot-3 { background: #EE5D50; }
    .jadwal-scope .dot-4 { background: #55A199; }

    .jadwal-scope .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-muted);
    }
    .jadwal-scope .empty-state svg { width: 64px; height: 64px; margin: 0 auto 12px; opacity: 0.4; }
    .jadwal-scope .empty-state p { font-weight: 600; }

    /* Modern Table Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        height: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.2);
        border-radius: 9999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(156, 163, 175, 0.4);
    }
</style>
@endpush

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
    $hariList = \App\Models\Akademik\Jadwal::HARI_LIST;
@endphp

<div class="jadwal-scope pb-4">
    <div class="content">
        @php
            $todayItems = $jadwal->filter(fn($j) => $j->hari === $hariIni)->sortBy('jam_mulai');
        @endphp
        
        {{-- Widget Hari Ini --}}
        <div class="today-hero">
            <h2>📅 {{ $hariIni }}, {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</h2>
            <p class="sub">Jadwal hari ini — {{ $peserta?->kelompokBelajar?->nama_kelompok ?? 'Belum ada rombel' }}</p>
            @if($todayItems->isEmpty())
                <div class="today-empty">Tidak ada jadwal hari ini 🎉</div>
            @else
                @foreach($todayItems as $j)
                <div class="today-item">
                    <div class="today-time">
                        <div class="t-start">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}</div>
                        <div class="t-end">{{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</div>
                    </div>
                    <div class="today-sep"></div>
                    <div class="today-info">
                        <div class="mapel">{{ $j->mataPelajaran?->nama ?? '-' }}</div>
                        <div class="detail">{{ $j->guru?->name ?? '-' }} {{ $j->ruangan ? '· '.$j->ruangan : '' }}</div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        {{-- Switch View Mode Premium untuk Siswa --}}
        <div x-data="{ viewMode: 'list', activeDay: '{{ $hariIni }}' }">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-5 px-1">
                <h3 class="text-sm font-black uppercase tracking-widest text-[#388782] dark:text-[#A2D5CB]">Struktur Jadwal Mingguan</h3>
                <div class="flex w-full sm:w-auto items-center bg-slate-100 dark:bg-zinc-800 p-1 rounded-2xl border border-slate-200/50 dark:border-zinc-700/50 shadow-inner">
                    <button @click="viewMode = 'list'" 
                        :class="viewMode === 'list' ? 'bg-white dark:bg-zinc-700 text-[#388782] dark:text-white shadow-sm' : 'text-slate-450 hover:text-slate-700 dark:text-slate-400'"
                        class="flex-1 sm:flex-none justify-center inline-flex items-center gap-1.5 font-black text-[10px] uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all">
                        List Harian
                    </button>
                    <button @click="viewMode = 'calendar'" 
                        :class="viewMode === 'calendar' ? 'bg-white dark:bg-zinc-700 text-[#388782] dark:text-white shadow-sm' : 'text-slate-450 hover:text-slate-700 dark:text-slate-400'"
                        class="flex-1 sm:flex-none justify-center inline-flex items-center gap-1.5 font-black text-[10px] uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all">
                        Kalender Grid
                    </button>
                </div>
            </div>

            {{-- VIEW 1: List Harian (Scrolled tabs) --}}
            <div x-show="viewMode === 'list'" class="transition-all">
                <div class="day-tabs">
                    @foreach($hariList as $h)
                    <button type="button" class="day-tab" :class="activeDay === '{{ $h }}' ? 'active' : ''" @click="activeDay = '{{ $h }}'">{{ $h }}</button>
                    @endforeach
                </div>

                @foreach($hariList as $h)
                <div x-show="activeDay === '{{ $h }}'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    @php $dayItems = $jadwal->filter(fn($j) => $j->hari === $h)->sortBy('jam_mulai'); @endphp
                    @if($dayItems->isEmpty())
                    <div class="empty-state">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p>Tidak ada jadwal di hari {{ $h }}</p>
                    </div>
                    @else
                        @foreach($dayItems as $idx => $j)
                        <div class="schedule-card">
                            <div class="sc-dot dot-{{ $idx % 5 }}"></div>
                            <div class="sc-time">
                                <div class="sc-start">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}</div>
                                <div class="sc-end">{{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</div>
                            </div>
                            <div class="sc-info">
                                <div class="sc-mapel">{{ $j->mataPelajaran?->nama ?? '-' }}</div>
                                <div class="sc-meta">{{ $j->guru?->name ?? '-' }} {{ $j->ruangan ? '· '.$j->ruangan : '' }}</div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
                @endforeach
            </div>

            {{-- VIEW 2: Kalender Grid (Siswa Version - Gorgeous & Compact) --}}
            <div x-show="viewMode === 'calendar'" x-cloak class="bg-white dark:bg-zinc-900 rounded-[2rem] border border-slate-150 dark:border-zinc-800 shadow-sm overflow-hidden transition-all">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full min-w-[850px] text-sm table-fixed border-collapse border border-slate-150 dark:border-zinc-800">
                        <thead class="bg-[#388782]/10 dark:bg-zinc-950/65 text-[10px] uppercase tracking-widest text-[#388782] dark:text-[#A2D5CB] font-black">
                            <tr>
                                <th class="py-4 px-4 text-center w-28 border border-slate-200 dark:border-zinc-800 bg-[#388782]/20 dark:bg-zinc-900">Jam</th>
                                @foreach($hariList as $hari)
                                <th class="py-4 px-2 text-center border border-slate-200 dark:border-zinc-800 {{ ($hariIni === $hari && $hari !== 'Minggu') ? 'bg-[#388782]/15 dark:bg-[#388782]/20 font-black text-[#388782] dark:text-[#A2D5CB]' : '' }}">
                                    {{ $hari }}
                                    @if($hariIni === $hari && $hari !== 'Minggu')
                                        <span class="inline-block ml-1.5 w-1.5 h-1.5 rounded-full bg-[#388782] animate-pulse"></span>
                                    @endif
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900">
                            @foreach($timeSlots as $slot)
                            <tr class="hover:bg-slate-50/20 dark:hover:bg-zinc-850/10 transition-colors">
                                {{-- Label Jam Kolom Kiri --}}
                                <td class="py-3 px-4 text-[11px] font-black font-mono text-slate-500 dark:text-slate-400 align-middle border border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50 text-center shadow-inner">
                                    {{ $slot }}
                                </td>
                                
                                {{-- Sel Hari --}}
                                @foreach($hariList as $hari)
                                <td class="p-2 align-top min-h-[105px] h-28 {{ ($hariIni === $hari && $hari !== 'Minggu') ? 'bg-[#388782]/5 dark:bg-[#388782]/5' : '' }} relative border border-slate-150 dark:border-zinc-800">
                                    @php
                                        $slotItems = $jadwal->filter(function($j) use ($hari, $slot) {
                                            return $j->hari === $hari && \Carbon\Carbon::parse($j->jam_mulai)->format('H:00') === $slot;
                                        });
                                    @endphp
                                    
                                    <div class="space-y-2">
                                        @foreach($slotItems as $j)
                                        <div class="group/card p-2.5 rounded-xl border-l-[4px] ring-1 ring-black/5 dark:ring-white/5 border-[#388782] bg-[#388782]/5 hover:bg-[#388782]/10 dark:bg-[#388782]/10 dark:hover:bg-[#388782]/20 transition-all hover:scale-[1.02]">
                                            <p class="font-extrabold text-[10px] text-slate-800 dark:text-white leading-tight break-words">
                                                {{ $j->mataPelajaran?->nama ?? 'Sesi Belajar' }}
                                            </p>
                                            <div class="flex items-center gap-1 mt-1 text-[8px] font-black text-slate-450 dark:text-slate-400 uppercase tracking-tighter">
                                                <span>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</span>
                                            </div>
                                            <div class="mt-2 space-y-0.5 border-t border-slate-200/50 dark:border-zinc-800/50 pt-1">
                                                <p class="text-[8.5px] font-bold text-slate-500 dark:text-slate-400 truncate">
                                                    {{ $j->guru?->name ?? '-' }}
                                                </p>
                                                @if($j->ruangan)
                                                <p class="text-[8px] font-bold text-slate-400 dark:text-slate-500 truncate">
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
                <div class="p-12 text-center bg-white dark:bg-zinc-900">
                    <svg class="w-12 h-12 mx-auto text-slate-350 dark:text-zinc-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-slate-400 dark:text-slate-500 font-black text-sm">Belum Ada Jadwal Mengajar</p>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection

