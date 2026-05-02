<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - GeniusEdu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4318FF;
            --primary-light: #F4F7FE;
            --secondary: #E9EDF7;
            --text-main: #2B3674;
            --text-muted: #A3AED0;
            --bg-body: #F4F7FE;
            --white: #FFFFFF;
            --danger: #EE5D50;
            --success: #01B574;
            --warning: #FFCE20;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body {
            overflow-x: hidden;
            max-width: 100vw;
        }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: var(--bg-body); 
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Layout Wrapper ─────────────────────── */
        .app-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar (Desktop) ──────────────────── */
        .sidebar {
            width: 280px;
            background: var(--white);
            flex-shrink: 0;
            display: none; /* Hidden on mobile */
            flex-direction: column;
            padding: 30px 20px;
            border-right: 1px solid #E2E8F0;
            z-index: 100;
        }
        .sidebar-brand { display: flex; align-items: center; gap: 12px; font-size: 1.4rem; font-weight: 800; color: var(--text-main); margin-bottom: 50px; padding: 0 10px; text-decoration: none;}
        .sidebar-brand svg { width: 32px; height: 32px; color: var(--primary); }
        
        .sidebar-menu { list-style: none; display: flex; flex-direction: column; gap: 8px; flex: 1; }
        .sidebar-link {
            display: flex; align-items: center; gap: 14px; padding: 14px 16px;
            border-radius: 12px; color: var(--text-muted); font-weight: 600; text-decoration: none;
            transition: all 0.2s;
        }
        .sidebar-link svg { width: 22px; height: 22px; }
        .sidebar-link.active { background: var(--primary); color: var(--white); box-shadow: 0 10px 20px rgba(67, 24, 255, 0.2); }
        .sidebar-link:hover:not(.active) { background: var(--primary-light); color: var(--primary); }
        
        .sidebar-footer { margin-top: auto; padding-top: 20px; border-top: 1px solid #E2E8F0; }

        /* ── Main Content ───────────────────────── */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            width: 100%;
            min-width: 0; max-width: 100vw;
            padding: 0 0 80px 0; /* Padding bottom for mobile nav */
        }

        /* ── Top Header ─────────────────────────── */
        .top-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 5%; background: var(--white);
            width: 100%; box-sizing: border-box;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky; top: 0; z-index: 50;
        }
        .header-mobile-brand { display: flex; align-items: center; gap: 8px; font-size: 1.2rem; font-weight: 800; color: var(--text-main); text-decoration:none; }
        .header-mobile-brand svg { width: 24px; height: 24px; color: var(--primary); }
        
        .header-user { display: flex; align-items: center; gap: 12px; }
        .header-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #8F9BFA);
            color: white; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.9rem;
        }

        /* ── Content Wrapper ────────────────────── */
        .content-wrap { padding: 24px 5%; max-width: 1200px; margin: 0 auto; width: 100%; }

        /* ── Greeting ───────────────────────────── */
        .greeting { margin-bottom: 24px; }
        .greeting-title { font-size: 1.8rem; font-weight: 800; color: var(--text-main); margin-bottom: 4px; }
        .greeting-sub { font-size: 0.95rem; color: var(--text-muted); font-weight: 500; }

        /* ── Stats ──────────────────────────────── */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
        .stat-card {
            background: var(--white); border-radius: 20px; padding: 20px;
            display: flex; align-items: center; gap: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid #E9EDF7;
        }
        .stat-icon { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .stat-icon.primary { background: #F4F7FE; color: var(--primary); }
        .stat-icon.danger { background: #FDE8E8; color: var(--danger); }
        .stat-icon.success { background: #E6F8F1; color: var(--success); }
        .stat-icon svg { width: 24px; height: 24px; }
        .stat-info { display: flex; flex-direction: column; }
        .stat-label { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
        .stat-value { font-size: 1.4rem; font-weight: 800; color: var(--text-main); line-height: 1.2; }

        /* ── Bento Grid ─────────────────────────── */
        .bento-grid { display: grid; grid-template-columns: minmax(0, 1fr); gap: 24px; width: 100%; }
        
        .card { background: var(--white); border-radius: 24px; padding: 28px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid #E9EDF7; min-width: 0; max-width: 100%; overflow: hidden; }
        .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .card-title { font-size: 1.1rem; font-weight: 800; color: var(--text-main); }

        /* QR Section */
        .qr-section { text-align: center; }
        .qr-wrapper {
            background: linear-gradient(135deg, var(--primary), #8F9BFA);
            border-radius: 24px; padding: 40px 20px; color: white;
            box-shadow: 0 10px 30px rgba(67, 24, 255, 0.2);
            position: relative; overflow: hidden;
        }
        .qr-wrapper::after {
            content: ''; position: absolute; top: -50px; right: -50px; width: 150px; height: 150px;
            background: rgba(255,255,255,0.1); border-radius: 50%;
        }
        .qr-icon {
            width: 80px; height: 80px; background: white; border-radius: 20px; margin: 0 auto 20px;
            display: flex; align-items: center; justify-content: center; color: var(--primary);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .qr-icon svg { width: 40px; height: 40px; }
        .nisn-text { font-size: 1.6rem; font-weight: 900; letter-spacing: 2px; margin-bottom: 4px; position: relative; z-index: 2; word-break: break-all; }
        .nisn-label { font-size: 0.85rem; font-weight: 500; opacity: 0.8; margin-bottom: 24px; position: relative; z-index: 2; }
        .btn-qr {
            display: inline-flex; align-items: center; justify-content: center; gap: 10px; padding: 14px 28px;
            background: white; color: var(--primary); border-radius: 14px; font-weight: 800;
            text-decoration: none; transition: transform 0.2s; position: relative; z-index: 2; max-width: 100%; white-space: normal; text-align: center;
        }
        .btn-qr:hover { transform: scale(1.05); }

        /* Data Section */
        .data-list { display: flex; flex-direction: column; gap: 16px; width: 100%; }
        .data-item { display: flex; flex-direction: column; gap: 4px; padding-bottom: 16px; border-bottom: 1px dashed #E2E8F0; width: 100%; }
        .data-item:last-child { border-bottom: none; padding-bottom: 0; }
        .data-label { font-size: 0.8rem; color: var(--text-muted); font-weight: 600; }
        .data-value { font-size: 1rem; font-weight: 700; color: var(--text-main); word-break: break-word; }
        .badge { display: inline-block; padding: 6px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 800; background: #E6F8F1; color: var(--success); }

        /* Table */
        .table-responsive { overflow-x: auto; width: 100%; max-width: 100vw; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; min-width: 500px; }
        th { text-align: left; padding: 12px 16px; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; border-bottom: 1px solid #E2E8F0; }
        td { padding: 16px; font-size: 0.9rem; font-weight: 600; color: var(--text-main); border-bottom: 1px solid #F1F5F9; }
        .status-badge { padding: 6px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; }
        .bg-hadir { background: #E6F8F1; color: var(--success); }
        .bg-alpha { background: #FDE8E8; color: var(--danger); }
        .bg-izin { background: #F4F7FE; color: var(--primary); }

        /* ── Bottom Nav (Mobile) ────────────────── */
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
        .bnav-item svg { width: 24px; height: 24px; transition: 0.2s; }
        .bnav-item.active { color: var(--primary); }
        .bnav-item.active svg { transform: translateY(-3px); }
        
        .bnav-fab {
            width: 50px; height: 50px; background: var(--primary); color: white;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            transform: translateY(-20px); box-shadow: 0 8px 20px rgba(67, 24, 255, 0.3);
            border: 4px solid var(--bg-body);
        }

        /* ── Responsive Desktop ─────────────────── */
        @media (min-width: 1024px) {
            .sidebar { display: flex; }
            .bottom-nav { display: none; }
            .top-header { display: none; } /* On desktop, we can use a simpler header inside content-wrap */
            .main-content { padding-bottom: 0; padding-top: 20px; }
            .bento-grid { grid-template-columns: 1fr 1fr; }
            .full-width { grid-column: 1 / -1; }
        }

        @media (max-width: 1023px) {
            .content-wrap { padding: 20px 5%; overflow: hidden; }
            .greeting-title { font-size: 1.5rem; }
            .greeting-sub { font-size: 0.85rem; }
            .stats-grid { grid-template-columns: minmax(0, 1fr); gap: 12px; }
            .stat-card { padding: 16px; flex-direction: column; text-align: center; justify-content: center; gap: 8px; min-width: 0;}
            .stat-info { align-items: center; }
            .card { padding: 20px; border-radius: 20px; min-width: 0; }
            .qr-wrapper { padding: 24px 12px; }
            .nisn-text { font-size: 1.3rem; }
            .btn-qr { font-size: 0.8rem; padding: 10px 14px; width: 100%; }
        }
    </style>
</head>
<body>

<div class="app-layout">
    
    {{-- SIDEBAR DESKTOP --}}
    <aside class="sidebar">
        <a href="#" class="sidebar-brand">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            GeniusEdu
        </a>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('siswa.dashboard') }}" class="sidebar-link active">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-link" onclick="alert('Fitur Ujian Segera Hadir')">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Ujian Online
                </a>
            </li>
            <li>
                <a href="{{ route('siswa.profile.edit') }}" class="sidebar-link">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profil Saya
                </a>
            </li>
        </ul>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="width:100%; display:flex; align-items:center; gap:14px; padding:14px 16px; background:#FDE8E8; color:var(--danger); border:none; border-radius:12px; font-weight:700; font-family:inherit; cursor:pointer;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="main-content">
        
        {{-- TOP HEADER (MOBILE) --}}
        <header class="top-header">
            <a href="#" class="header-mobile-brand">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                GeniusEdu
            </a>
            <div class="header-user">
                <div class="header-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
            </div>
        </header>

        <div class="content-wrap">
            
            <div class="greeting">
                <h1 class="greeting-title">Halo, {{ explode(' ', $peserta->nama_lengkap)[0] }}! 👋</h1>
                <p class="greeting-sub">Berikut adalah ringkasan aktivitas dan data absensi Anda.</p>
            </div>

            {{-- STATS GRID --}}
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon primary">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Kehadiran (Bulan Ini)</span>
                        <span class="stat-value">{{ $totalHadir }} <span style="font-size:0.8rem;color:var(--text-muted);">Hari</span></span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon danger">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Total Alpha</span>
                        <span class="stat-value">{{ $totalAlpha ?? 0 }} <span style="font-size:0.8rem;color:var(--text-muted);">Hari</span></span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon success">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Status Paket</span>
                        <span class="stat-value" style="font-size:1.1rem;margin-top:2px;">{{ $peserta->paketBimbingan ? 'Aktif' : 'Tidak Aktif' }}</span>
                    </div>
                </div>
            </div>

            {{-- BENTO GRID --}}
            <div class="bento-grid">
                
                {{-- QR CODE & GENERATE --}}
                <div class="card qr-section">
                    <div class="qr-wrapper">
                        <div class="qr-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                        </div>
                        <div class="nisn-text">{{ $peserta->nisn ?? '—' }}</div>
                        <div class="nisn-label">NISN / Kode Identitas</div>
                        
                        <a href="{{ route('siswa.qr.show') }}" class="btn-qr">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            Tampilkan QR Absensi
                        </a>
                    </div>
                </div>

                {{-- DATA PRIBADI --}}
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Informasi Pribadi</h2>
                        <span class="badge">Siswa Aktif</span>
                    </div>
                    <div class="data-list">
                        <div class="data-item">
                            <span class="data-label">Nama Lengkap</span>
                            <span class="data-value">{{ $peserta->nama_lengkap }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Asal Sekolah</span>
                            <span class="data-value">{{ $peserta->asal_sekolah }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Paket Bimbingan</span>
                            <span class="data-value">{{ $peserta->paketBimbingan?->nama_paket ?? '-' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Kelompok Belajar</span>
                            <span class="data-value">{{ $peserta->kelompokBelajar?->nama_kelompok ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- TABEL ABSENSI --}}
                <div class="card full-width">
                    <div class="card-header">
                        <h2 class="card-title">Riwayat Absensi Terakhir</h2>
                    </div>
                    @if($absensis->count() > 0)
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($absensis->take(5) as $a)
                                <tr>
                                    <td>{{ $a->tanggal->format('d M Y') }}</td>
                                    <td>{{ $a->jam_masuk ? substr($a->jam_masuk,0,5) : '-' }}</td>
                                    <td>{{ $a->jam_pulang ? substr($a->jam_pulang,0,5) : '-' }}</td>
                                    <td>
                                        <span class="status-badge bg-{{ $a->status_masuk }}">
                                            {{ ucfirst($a->status_masuk) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div style="text-align:center; padding: 30px; color:var(--text-muted); font-weight:600;">
                        Belum ada data absensi untuk ditampilkan.
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </main>

    {{-- BOTTOM NAV (MOBILE) --}}
    <nav class="bottom-nav">
        <a href="{{ route('siswa.dashboard') }}" class="bnav-item active">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Home
        </a>
        <a href="#" class="bnav-item" onclick="alert('Fitur Ujian Segera Hadir')">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            Ujian
        </a>
        
        <a href="{{ route('siswa.qr.show') }}" class="bnav-item bnav-fab">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
        </a>

        <a href="{{ route('siswa.profile.edit') }}" class="bnav-item">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Profil
        </a>
        
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="bnav-item" style="border:none;background:transparent;cursor:pointer;font-family:inherit;padding:0;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
            </button>
        </form>
    </nav>
</div>

</body>
</html>
