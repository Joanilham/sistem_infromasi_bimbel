<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $masterData->nama_lembaga ?? 'Genius Education' }} - Platform Bimbel Terbaik</title>
    <meta name="description" content="Platform sistem informasi bimbingan belajar terpadu. Kelola peserta didik, guru, dan jadwal dengan mudah.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $masterData->nama_lembaga ?? 'Genius Education' }} - Platform Bimbel Terbaik">
    <meta property="og:description" content="Kelola Bimbel Lebih Cerdas & Efisien dengan GeniusEdu.">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #4F46E5;
            --primary-dark: #3730A3;
            --secondary: #06B6D4;
            --accent: #F59E0B;
            --green: #10B981;
            --text: #0F172A;
            --muted: #64748B;
            --bg: #F8FAFC;
            --white: #ffffff;
        }
        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); overflow-x: hidden; }

        /* NAV */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 999;
            background: rgba(255,255,255,0.9); backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0,0,0,0.06);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 5%; height: 68px;
            transition: background 0.3s;
        }
        .nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-logo-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex; align-items: center; justify-content: center;
        }
        .nav-logo-icon svg { width: 22px; height: 22px; color: white; }
        .nav-logo-text { font-size: 1.1rem; font-weight: 800; color: var(--text); }
        .nav-logo-text span { color: var(--primary); }
        .nav-links { display: flex; align-items: center; gap: 8px; }
        .nav-link { text-decoration: none; color: var(--muted); font-size: 0.9rem; font-weight: 500; padding: 8px 16px; border-radius: 8px; transition: all 0.2s; }
        .nav-link:hover { color: var(--primary); background: rgba(79,70,229,0.06); }
        .btn-nav {
            text-decoration: none; background: var(--primary); color: white;
            font-size: 0.9rem; font-weight: 600; padding: 9px 22px;
            border-radius: 10px; transition: all 0.2s; box-shadow: 0 4px 12px rgba(79,70,229,0.3);
        }
        .btn-nav:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 6px 16px rgba(79,70,229,0.4); }

        /* HERO */
        .hero {
            min-height: 100vh; display: flex; align-items: center;
            padding: 100px 5% 60px;
            background: linear-gradient(135deg, #EEF2FF 0%, #F0FDFF 50%, #ECFDF5 100%);
            position: relative; overflow: hidden;
            -webkit-tap-highlight-color: transparent;
        }
        .hero::before {
            content: ''; position: absolute; top: -100px; right: -100px;
            width: 500px; height: 500px; border-radius: 50%;
            background: radial-gradient(circle, rgba(79,70,229,0.12) 0%, transparent 70%);
            pointer-events: none; /* Jangan blokir klik */
        }
        .hero::after {
            content: ''; position: absolute; bottom: -80px; left: -80px;
            width: 400px; height: 400px; border-radius: 50%;
            background: radial-gradient(circle, rgba(6,182,212,0.1) 0%, transparent 70%);
            pointer-events: none; /* Jangan blokir klik */
        }
        .hero-inner { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; width: 100%; position: relative; z-index: 1; }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: white; border: 1px solid rgba(79,70,229,0.2);
            padding: 6px 14px; border-radius: 50px; font-size: 0.8rem;
            font-weight: 600; color: var(--primary); margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(79,70,229,0.1);
        }
        .hero-badge .dot { width: 8px; height: 8px; border-radius: 50%; background: var(--green); animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1}50%{opacity:0.4} }
        .hero h1 { font-size: 3.2rem; font-weight: 900; line-height: 1.15; margin-bottom: 20px; }
        .hero h1 .highlight {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .hero p { font-size: 1.1rem; color: var(--muted); line-height: 1.7; margin-bottom: 36px; max-width: 480px; }
        .hero-buttons { display: flex; gap: 14px; flex-wrap: wrap; position: relative; z-index: 2; }
        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white; text-decoration: none; padding: 14px 28px;
            border-radius: 12px; font-weight: 700; font-size: 1rem;
            box-shadow: 0 8px 20px rgba(79,70,229,0.35); transition: all 0.3s;
            cursor: pointer; position: relative; z-index: 2;
            -webkit-tap-highlight-color: rgba(79,70,229,0.2);
            touch-action: manipulation;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(79,70,229,0.45); }
        .btn-primary:active { transform: translateY(0); }
        .btn-secondary {
            display: inline-flex; align-items: center; gap: 8px;
            background: white; color: var(--primary); text-decoration: none;
            padding: 14px 28px; border-radius: 12px; font-weight: 700;
            font-size: 1rem; border: 2px solid rgba(79,70,229,0.2); transition: all 0.3s;
            cursor: pointer; position: relative; z-index: 2;
            -webkit-tap-highlight-color: rgba(79,70,229,0.1);
            touch-action: manipulation;
        }
        .btn-secondary:hover { border-color: var(--primary); background: rgba(79,70,229,0.04); transform: translateY(-2px); }
        .btn-secondary:active { transform: translateY(0); }
        .hero-visual { position: relative; z-index: 1; }
        .hero-card {
            background: white; border-radius: 24px; padding: 28px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1); position: relative;
        }
        .hero-card-header { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
        .hero-avatar {
            width: 48px; height: 48px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
        }
        .hero-card-title { font-weight: 700; font-size: 0.95rem; }
        .hero-card-sub { font-size: 0.78rem; color: var(--muted); }
        .stats-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; }
        .stat-box {
            background: linear-gradient(135deg, #F0F4FF, #E8F3FF);
            border-radius: 14px; padding: 16px; text-align: center;
        }
        .stat-box.green { background: linear-gradient(135deg, #ECFDF5, #D1FAE5); }
        .stat-box.amber { background: linear-gradient(135deg, #FFFBEB, #FEF3C7); }
        .stat-number { font-size: 1.6rem; font-weight: 900; color: var(--primary); }
        .stat-box.green .stat-number { color: var(--green); }
        .stat-box.amber .stat-number { color: var(--accent); }
        .stat-label { font-size: 0.72rem; color: var(--muted); margin-top: 2px; font-weight: 500; }
        .floating-badge {
            position: absolute; background: white; border-radius: 12px; padding: 10px 14px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12); display: flex; align-items: center; gap: 8px;
            font-size: 0.8rem; font-weight: 600;
        }
        .floating-badge.top-right { top: -20px; right: -30px; }
        .floating-badge.bottom-left { bottom: -20px; left: -30px; }
        .badge-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem; }

        /* STATS BAR */
        .stats-bar {
            background: white; padding: 40px 5%;
            border-top: 1px solid #F1F5F9; border-bottom: 1px solid #F1F5F9;
        }
        .stats-bar-inner { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(4,1fr); gap: 20px; }
        .stats-bar-item { text-align: center; }
        .stats-bar-number { font-size: 2rem; font-weight: 900; color: var(--primary); }
        .stats-bar-label { font-size: 0.85rem; color: var(--muted); margin-top: 4px; }

        /* FEATURES */
        .features { padding: 80px 5%; }
        .section-label {
            display: inline-block; background: rgba(79,70,229,0.08);
            color: var(--primary); font-size: 0.78rem; font-weight: 700;
            padding: 5px 14px; border-radius: 50px; margin-bottom: 14px;
            letter-spacing: 0.05em; text-transform: uppercase;
        }
        .section-title { font-size: 2.2rem; font-weight: 900; margin-bottom: 14px; }
        .section-sub { color: var(--muted); font-size: 1rem; max-width: 520px; line-height: 1.7; margin-bottom: 50px; }
        .features-inner { max-width: 1200px; margin: 0 auto; }
        .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .feature-card {
            background: white; border-radius: 20px; padding: 28px;
            border: 1px solid #F1F5F9; transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,0.1); border-color: rgba(79,70,229,0.2); }
        .feature-icon {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin-bottom: 16px;
        }
        .feature-icon.blue { background: linear-gradient(135deg, #EEF2FF, #E0E7FF); }
        .feature-icon.cyan { background: linear-gradient(135deg, #ECFEFF, #CFFAFE); }
        .feature-icon.green { background: linear-gradient(135deg, #ECFDF5, #D1FAE5); }
        .feature-icon.amber { background: linear-gradient(135deg, #FFFBEB, #FEF3C7); }
        .feature-icon.rose { background: linear-gradient(135deg, #FFF1F2, #FFE4E6); }
        .feature-icon.purple { background: linear-gradient(135deg, #F5F3FF, #EDE9FE); }
        .feature-title { font-size: 1rem; font-weight: 700; margin-bottom: 8px; }
        .feature-desc { font-size: 0.875rem; color: var(--muted); line-height: 1.6; }

        /* CTA */
        .cta {
            background: linear-gradient(135deg, var(--primary) 0%, #6D28D9 50%, var(--primary-dark) 100%);
            padding: 80px 5%; text-align: center; position: relative; overflow: hidden;
        }
        .cta::before {
            content: ''; position: absolute; top: -60px; left: 50%; transform: translateX(-50%);
            width: 600px; height: 600px; border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        }
        .cta-inner { max-width: 700px; margin: 0 auto; position: relative; z-index: 1; }
        .cta h2 { font-size: 2.5rem; font-weight: 900; color: white; margin-bottom: 16px; }
        .cta p { color: rgba(255,255,255,0.75); font-size: 1.05rem; line-height: 1.7; margin-bottom: 36px; }
        .btn-white {
            display: inline-flex; align-items: center; gap: 10px;
            background: white; color: var(--primary); text-decoration: none;
            padding: 15px 32px; border-radius: 12px; font-weight: 800;
            font-size: 1rem; box-shadow: 0 8px 24px rgba(0,0,0,0.2); transition: all 0.3s;
        }
        .btn-white:hover { transform: translateY(-2px); box-shadow: 0 14px 32px rgba(0,0,0,0.3); }

        /* FOOTER */
        footer {
            background: var(--text); color: rgba(255,255,255,0.6);
            padding: 40px 5%; text-align: center;
        }
        footer .footer-logo { display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 12px; }
        footer .footer-logo-icon {
            width: 34px; height: 34px; border-radius: 9px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex; align-items: center; justify-content: center;
        }
        footer .footer-brand { font-size: 1rem; font-weight: 800; color: white; }
        footer p { font-size: 0.85rem; }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .hero-inner { grid-template-columns: 1fr; text-align: center; }
            .hero h1 { font-size: 2.2rem; }
            .hero p { margin: 0 auto 32px; }
            .hero-buttons { justify-content: center; }
            .hero-visual { display: none; }
            .stats-bar-inner { grid-template-columns: repeat(2,1fr); }
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            nav .nav-links .nav-link { display: none; }
        }
        @media (max-width: 600px) {
            .hero { padding: 90px 5% 50px; }
            .hero h1 { font-size: 1.9rem; }
            .hero-buttons { flex-direction: column; align-items: center; gap: 12px; }
            .btn-primary, .btn-secondary { width: 100%; max-width: 320px; justify-content: center; padding: 15px 24px; }
            .features-grid { grid-template-columns: 1fr; }
            .stats-bar-inner { grid-template-columns: repeat(2,1fr); }
            .cta h2 { font-size: 1.8rem; }
            .btn-white { width: 100%; max-width: 280px; justify-content: center; }
        }
    </style>
</head>
<body>

<!-- NAV -->
<nav>
    <a href="/" class="nav-logo">
        <div class="nav-logo-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <span class="nav-logo-text">
            @if($masterData && $masterData->nama_lembaga)
                {{ $masterData->nama_lembaga }}
            @else
                Genius<span>Edu</span>
            @endif
        </span>
    </a>
    <div class="nav-links">
        <a href="#fitur" class="nav-link">Fitur</a>
        <a href="#tentang" class="nav-link">Tentang</a>
        <a href="{{ route('login') }}" class="btn-nav">Masuk</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-badge">
                <span class="dot"></span>
                Platform Manajemen Bimbel #1
            </div>
            <h1>Kelola Bimbel Lebih <span class="highlight">Cerdas & Efisien</span></h1>
            <p>Sistem informasi terpadu untuk bimbingan belajar. Kelola peserta didik, guru, jadwal, dan laporan dalam satu platform yang mudah digunakan.</p>
            <div class="hero-buttons">
                <a href="{{ route('daftar.step1') }}" class="btn-primary" id="hero-daftar-btn">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="btn-secondary" id="hero-login-btn">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Masuk
                </a>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-card">
                <div class="floating-badge top-right">
                    <div class="badge-icon" style="background:#ECFDF5;">✅</div>
                    <div>
                        <div style="font-size:0.75rem;color:#64748B;">Peserta Baru</div>
                        <div style="color:#10B981;">+12 minggu ini</div>
                    </div>
                </div>
                <div class="floating-badge bottom-left">
                    <div class="badge-icon" style="background:#FEF3C7;">⭐</div>
                    <div>
                        <div style="font-size:0.75rem;color:#64748B;">Rating Platform</div>
                        <div style="color:#F59E0B;">4.9 / 5.0</div>
                    </div>
                </div>
                <div class="hero-card-header">
                    <div class="hero-avatar">📊</div>
                    <div>
                        <div class="hero-card-title">Dashboard Admin</div>
                        <div class="hero-card-sub">Ringkasan data hari ini</div>
                    </div>
                </div>
                <div class="stats-row">
                    <div class="stat-box">
                        <div class="stat-number">248</div>
                        <div class="stat-label">Peserta Aktif</div>
                    </div>
                    <div class="stat-box green">
                        <div class="stat-number">32</div>
                        <div class="stat-label">Guru Aktif</div>
                    </div>
                    <div class="stat-box amber">
                        <div class="stat-number">18</div>
                        <div class="stat-label">Paket Kelas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- STATS BAR -->
<div class="stats-bar">
    <div class="stats-bar-inner">
        <div class="stats-bar-item">
            <div class="stats-bar-number">500+</div>
            <div class="stats-bar-label">Peserta Terdaftar</div>
        </div>
        <div class="stats-bar-item">
            <div class="stats-bar-number">50+</div>
            <div class="stats-bar-label">Tenaga Pengajar</div>
        </div>
        <div class="stats-bar-item">
            <div class="stats-bar-number">30+</div>
            <div class="stats-bar-label">Paket Bimbingan</div>
        </div>
        <div class="stats-bar-item">
            <div class="stats-bar-number">99%</div>
            <div class="stats-bar-label">Kepuasan Pengguna</div>
        </div>
    </div>
</div>

<!-- FEATURES -->
<section class="features" id="fitur">
    <div class="features-inner">
        <span class="section-label">Fitur Unggulan</span>
        <h2 class="section-title">Semua yang Kamu Butuhkan</h2>
        <p class="section-sub">Dari manajemen peserta didik hingga pelaporan, semua tersedia dalam satu platform yang terintegrasi.</p>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon blue">📚</div>
                <div class="feature-title">Manajemen Peserta Didik</div>
                <div class="feature-desc">Kelola data peserta aktif dan keluar dengan mudah. Pencarian cepat dan ekspor data tersedia.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon cyan">👨‍🏫</div>
                <div class="feature-title">Manajemen Guru</div>
                <div class="feature-desc">Pantau data guru aktif, riwayat mengajar, dan mata pelajaran yang diampu secara terpusat.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon green">🎓</div>
                <div class="feature-title">Paket Bimbingan</div>
                <div class="feature-desc">Buat dan kelola berbagai paket bimbingan belajar sesuai kebutuhan lembaga Anda.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon amber">📅</div>
                <div class="feature-title">Kelompok Belajar</div>
                <div class="feature-desc">Atur kelompok belajar dan jadwal kelas agar kegiatan belajar mengajar lebih terstruktur.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon rose">🏢</div>
                <div class="feature-title">Multi Kantor</div>
                <div class="feature-desc">Dukung pengelolaan lebih dari satu cabang kantor dengan konteks data yang terpisah.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon purple">📊</div>
                <div class="feature-title">Laporan & Ekspor</div>
                <div class="feature-desc">Unduh laporan data peserta didik dan guru dalam format yang siap digunakan kapan saja.</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta" id="tentang">
    <div class="cta-inner">
        <h2>Siap Mulai Kelola Bimbel Anda?</h2>
        <p>Bergabung dan rasakan kemudahan manajemen lembaga bimbingan belajar dengan teknologi modern yang mudah digunakan.</p>
        <a href="{{ route('login') }}" class="btn-white" id="cta-login-btn">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            Masuk ke Dashboard
        </a>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="footer-logo">
        <div class="footer-logo-icon">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <span class="footer-brand">
            @if($masterData && $masterData->nama_lembaga) {{ $masterData->nama_lembaga }} @else GeniusEdu @endif
        </span>
    </div>
    <p>&copy; {{ date('Y') }} {{ $masterData->nama_lembaga ?? 'GeniusEdu' }}. Platform Sistem Informasi Bimbingan Belajar.</p>
</footer>

</body>
</html>
