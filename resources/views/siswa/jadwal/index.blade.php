<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Pelajaran - GeniusEdu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4318FF;
            --primary-light: #F4F7FE;
            --text-main: #2B3674;
            --text-muted: #A3AED0;
            --bg-body: #F4F7FE;
            --white: #FFFFFF;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            padding-bottom: 80px;
        }

        .top-bar {
            background: var(--white);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .back-btn {
            width: 36px; height: 36px; border-radius: 10px;
            background: var(--primary-light); color: var(--primary);
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; transition: 0.2s;
        }
        .back-btn:hover { background: var(--primary); color: white; }
        .top-bar h1 { font-size: 1.1rem; font-weight: 800; }

        .content { padding: 20px 16px; max-width: 900px; margin: 0 auto; }

        /* Today Hero */
        .today-hero {
            background: linear-gradient(135deg, #4318FF, #868CFF);
            border-radius: 20px;
            padding: 24px 20px;
            color: white;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(67, 24, 255, 0.2);
        }
        .today-hero::after {
            content: ''; position: absolute; top: -40px; right: -40px;
            width: 120px; height: 120px;
            background: rgba(255,255,255,0.1); border-radius: 50%;
        }
        .today-hero h2 { font-size: 1.3rem; font-weight: 800; margin-bottom: 4px; position: relative; z-index: 2; }
        .today-hero .sub { font-size: 0.85rem; opacity: 0.8; margin-bottom: 16px; position: relative; z-index: 2; }
        .today-item {
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
        .today-time { text-align: center; min-width: 55px; }
        .today-time .t-start { font-size: 0.9rem; font-weight: 800; }
        .today-time .t-end { font-size: 0.7rem; opacity: 0.7; }
        .today-sep { width: 2px; height: 36px; background: rgba(255,255,255,0.25); border-radius: 1px; }
        .today-info { flex: 1; min-width: 0; }
        .today-info .mapel { font-weight: 800; font-size: 0.95rem; }
        .today-info .detail { font-size: 0.75rem; opacity: 0.8; }
        .today-empty { text-align: center; padding: 16px; opacity: 0.8; font-weight: 600; }

        /* Day Tabs */
        .day-tabs {
            display: flex;
            gap: 6px;
            overflow-x: auto;
            padding-bottom: 4px;
            margin-bottom: 16px;
            -webkit-overflow-scrolling: touch;
        }
        .day-tabs::-webkit-scrollbar { display: none; }
        .day-tab {
            padding: 10px 18px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 700;
            white-space: nowrap;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            background: var(--white);
            color: var(--text-muted);
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }
        .day-tab.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 6px 15px rgba(67, 24, 255, 0.25);
        }
        .day-tab:hover:not(.active) { background: #EDE9FF; color: var(--primary); }

        /* Schedule Cards */
        .schedule-card {
            background: var(--white);
            border-radius: 16px;
            padding: 16px 18px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            border: 1px solid #E9EDF7;
            transition: transform 0.15s;
        }
        .schedule-card:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.06); }
        .sc-time {
            min-width: 60px;
            text-align: center;
            padding: 8px;
            border-radius: 10px;
            background: var(--primary-light);
        }
        .sc-time .sc-start { font-size: 0.95rem; font-weight: 800; color: var(--primary); }
        .sc-time .sc-end { font-size: 0.7rem; color: var(--text-muted); font-weight: 600; }
        .sc-info { flex: 1; min-width: 0; }
        .sc-info .sc-mapel { font-size: 0.95rem; font-weight: 800; color: var(--text-main); }
        .sc-info .sc-meta { font-size: 0.78rem; color: var(--text-muted); font-weight: 600; margin-top: 2px; }
        .sc-dot {
            width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
        }
        .dot-0 { background: #4318FF; } .dot-1 { background: #01B574; }
        .dot-2 { background: #FFCE20; } .dot-3 { background: #EE5D50; }
        .dot-4 { background: #7B61FF; }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-muted);
        }
        .empty-state svg { width: 64px; height: 64px; margin: 0 auto 12px; opacity: 0.4; }
        .empty-state p { font-weight: 600; }

        /* Bottom Nav */
        .bottom-nav {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: var(--white); padding: 10px 20px;
            display: flex; justify-content: space-around; align-items: center;
            border-top: 1px solid #E2E8F0; z-index: 100;
            padding-bottom: calc(10px + env(safe-area-inset-bottom));
        }
        .bnav-item {
            display: flex; flex-direction: column; align-items: center; gap: 4px;
            text-decoration: none; color: var(--text-muted); font-size: 0.7rem; font-weight: 600;
        }
        .bnav-item svg { width: 24px; height: 24px; }
        .bnav-item.active { color: var(--primary); }

        @media (min-width: 1024px) {
            .bottom-nav { display: none; }
            .content { padding: 30px 40px; }
        }
    </style>
</head>
<body>

{{-- TOP BAR --}}
<header class="top-bar">
    <a href="{{ route('siswa.dashboard') }}" class="back-btn">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <h1>Jadwal Pelajaran</h1>
</header>

<div class="content">
    {{-- TODAY HERO --}}
    @php
        $todayItems = $jadwal->filter(fn($j) => $j->hari === $hariIni)->sortBy('jam_mulai');
    @endphp
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

    {{-- DAY TABS + SCHEDULE LIST --}}
    <div x-data="{ activeDay: '{{ $hariIni }}' }">
        <div class="day-tabs">
            @foreach(\App\Models\Jadwal::HARI_LIST as $h)
            <button class="day-tab" :class="activeDay === '{{ $h }}' ? 'active' : ''" @click="activeDay = '{{ $h }}'">{{ $h }}</button>
            @endforeach
        </div>

        @foreach(\App\Models\Jadwal::HARI_LIST as $h)
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
</div>

{{-- BOTTOM NAV --}}
<nav class="bottom-nav">
    <a href="{{ route('siswa.dashboard') }}" class="bnav-item">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Home
    </a>
    <a href="{{ route('siswa.jadwal.index') }}" class="bnav-item active">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Jadwal
    </a>
    <a href="{{ route('siswa.ujian.index') }}" class="bnav-item">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        Ujian
    </a>
    <a href="{{ route('siswa.profile.edit') }}" class="bnav-item">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        Profil
    </a>
</nav>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
