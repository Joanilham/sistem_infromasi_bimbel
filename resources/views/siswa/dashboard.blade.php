<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - GeniusEdu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F1F5F9; min-height: 100vh; }

        /* NAVBAR */
        .navbar { background: white; border-bottom: 1px solid #E2E8F0; padding: 0 5%; display: flex; align-items: center; justify-content: space-between; height: 64px; position: sticky; top: 0; z-index: 50; }
        .nav-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-icon { width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #4F46E5, #06B6D4); display: flex; align-items: center; justify-content: center; }
        .nav-icon svg { width: 20px; height: 20px; }
        .nav-title { font-size: 1rem; font-weight: 800; color: #0F172A; }
        .nav-title span { color: #4F46E5; }
        .nav-right { display: flex; align-items: center; gap: 16px; }
        .nav-user { display: flex; align-items: center; gap: 10px; }
        .nav-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #4F46E5, #7C3AED); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 0.85rem; }
        .nav-name { font-size: 0.85rem; font-weight: 700; color: #0F172A; }
        .nav-level { font-size: 0.7rem; color: #64748B; }
        .btn-logout { background: #FEF2F2; color: #EF4444; border: 1px solid #FECACA; padding: 8px 16px; border-radius: 10px; font-size: 0.8rem; font-weight: 700; cursor: pointer; text-decoration: none; transition: all 0.2s; }
        .btn-logout:hover { background: #EF4444; color: white; }

        /* MAIN */
        .main { padding: 28px 5%; max-width: 1200px; margin: 0 auto; }
        .welcome-msg { font-size: 1.5rem; font-weight: 800; color: #0F172A; margin-bottom: 4px; }
        .welcome-sub { color: #64748B; font-size: 0.9rem; margin-bottom: 28px; }

        /* GRID */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }

        /* CARD */
        .card { background: white; border-radius: 18px; padding: 24px; box-shadow: 0 1px 8px rgba(0,0,0,0.04); border: 1px solid #F1F5F9; }
        .card-title { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #4F46E5; margin-bottom: 16px; }

        /* QR CARD */
        .qr-card { text-align: center; }
        .qr-wrap { background: white; border: 2px solid #E2E8F0; border-radius: 16px; padding: 16px; display: inline-block; margin-bottom: 12px; }
        .qr-wrap img { border-radius: 8px; display: block; }
        #qr-canvas { display: flex; align-items: center; justify-content: center; }
        .qr-nisn { font-size: 1.1rem; font-weight: 800; color: #0F172A; letter-spacing: 0.05em; }
        .qr-label { font-size: 0.78rem; color: #64748B; margin-top: 2px; }

        /* DATA ROWS */
        .data-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #F8FAFC; }
        .data-row:last-child { border-bottom: none; }
        .data-label { font-size: 0.82rem; color: #94A3B8; }
        .data-value { font-size: 0.85rem; font-weight: 600; color: #0F172A; text-align: right; }

        /* STAT MINI CARDS */
        .stat-mini { text-align: center; padding: 18px 14px; border-radius: 16px; }
        .stat-mini.indigo { background: linear-gradient(135deg, #EEF2FF, #E0E7FF); }
        .stat-mini.emerald { background: linear-gradient(135deg, #ECFDF5, #D1FAE5); }
        .stat-mini.amber { background: linear-gradient(135deg, #FFFBEB, #FEF3C7); }
        .stat-num { font-size: 1.8rem; font-weight: 900; }
        .stat-mini.indigo .stat-num { color: #4F46E5; }
        .stat-mini.emerald .stat-num { color: #059669; }
        .stat-mini.amber .stat-num { color: #D97706; }
        .stat-label { font-size: 0.72rem; font-weight: 600; color: #64748B; margin-top: 4px; }

        /* STATUS BADGE */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; }
        .badge-active { background: #ECFDF5; color: #059669; }
        .badge-hadir { background: #ECFDF5; color: #059669; }
        .badge-izin { background: #EEF2FF; color: #4F46E5; }
        .badge-sakit { background: #FFFBEB; color: #D97706; }
        .badge-alpha { background: #FEF2F2; color: #EF4444; }

        /* MENU CARDS */
        .menu-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 12px; }
        .menu-card { display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 20px 14px; border-radius: 14px; text-decoration: none; transition: all 0.2s; border: 1.5px solid #F1F5F9; }
        .menu-card:hover { border-color: #C7D2FE; transform: translateY(-2px); box-shadow: 0 6px 18px rgba(79,70,229,0.1); }
        .menu-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .menu-icon svg { width: 22px; height: 22px; }
        .menu-label { font-size: 0.8rem; font-weight: 700; color: #0F172A; }

        /* ABSENSI TABLE */
        .absensi-table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        .absensi-table th { background: #F8FAFC; text-align: left; padding: 10px 12px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.05em; }
        .absensi-table td { padding: 10px 12px; font-size: 0.83rem; border-top: 1px solid #F1F5F9; color: #334155; }
        .absensi-table tr:hover td { background: #F8FAFC; }

        @media (max-width: 768px) {
            .grid-2 { grid-template-columns: 1fr; }
            .grid-3 { grid-template-columns: 1fr; }
            .menu-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<nav class="navbar">
    <a href="{{ route('siswa.dashboard') }}" class="nav-brand">
        <div class="nav-icon"><svg fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>
        <span class="nav-title">Genius<span>Edu</span></span>
    </a>
    <div class="nav-right">
        <div class="nav-user">
            <div class="nav-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
            <div>
                <div class="nav-name">{{ $user->name }}</div>
                <div class="nav-level">Siswa</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</nav>

<div class="main">
    <div class="welcome-msg">Halo, {{ explode(' ', $peserta->nama_lengkap)[0] }}! 👋</div>
    <div class="welcome-sub">Selamat datang di dashboard siswa Anda.</div>

    {{-- STAT CARDS --}}
    <div class="grid-3">
        <div class="stat-mini indigo">
            <div class="stat-num">{{ $totalHadir }}</div>
            <div class="stat-label">Kehadiran Bulan Ini</div>
        </div>
        <div class="stat-mini emerald">
            <div class="stat-num">{{ $totalAbsen }}</div>
            <div class="stat-label">Total Absensi</div>
        </div>
        <div class="stat-mini amber">
            <div class="stat-num">{{ $peserta->paketBimbingan ? '✓' : '-' }}</div>
            <div class="stat-label">Paket Aktif</div>
        </div>
    </div>

    <div class="grid-2">
        {{-- QR CODE --}}
        <div class="card qr-card">
            <div class="card-title">Kartu Identitas Digital</div>
            <div class="qr-wrap">
                <div id="qr-canvas"></div>
            </div>
            <div class="qr-nisn">{{ $peserta->nisn }}</div>
            <div class="qr-label">NISN / Kode Siswa</div>
            <div style="margin-top: 16px;">
                <span class="badge badge-active">● Status Aktif</span>
            </div>
        </div>

        {{-- DATA SISWA --}}
        <div class="card">
            <div class="card-title">Data Pribadi</div>
            <div class="data-row"><span class="data-label">Nama Lengkap</span><span class="data-value">{{ $peserta->nama_lengkap }}</span></div>
            <div class="data-row"><span class="data-label">NISN</span><span class="data-value">{{ $peserta->nisn ?? '-' }}</span></div>
            <div class="data-row"><span class="data-label">Jenis Kelamin</span><span class="data-value">{{ $peserta->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
            <div class="data-row"><span class="data-label">Asal Sekolah</span><span class="data-value">{{ $peserta->asal_sekolah }}</span></div>
            <div class="data-row"><span class="data-label">Paket Bimbingan</span><span class="data-value">{{ $peserta->paketBimbingan?->nama_paket ?? '-' }}</span></div>
            <div class="data-row"><span class="data-label">Kelompok</span><span class="data-value">{{ $peserta->kelompokBelajar?->nama_kelompok ?? '-' }}</span></div>
            <div class="data-row"><span class="data-label">Periode</span><span class="data-value">{{ $peserta->periode?->tahun_periode ?? '-' }}</span></div>
        </div>
    </div>

    {{-- MENU --}}
    <div class="card" style="margin-top: 20px;">
        <div class="card-title">Menu</div>
        <div class="menu-grid">
            <a href="#" class="menu-card">
                <div class="menu-icon" style="background:#EEF2FF;"><svg fill="none" viewBox="0 0 24 24" stroke="#4F46E5" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></div>
                <span class="menu-label">Riwayat Absensi</span>
            </a>
            <a href="{{ route('siswa.dashboard') }}" class="menu-card">
                <div class="menu-icon" style="background:#ECFDF5;"><svg fill="none" viewBox="0 0 24 24" stroke="#059669" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                <span class="menu-label">Profil Saya</span>
            </a>
            <a href="#" class="menu-card">
                <div class="menu-icon" style="background:#FEF3C7;"><svg fill="none" viewBox="0 0 24 24" stroke="#D97706" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>
                <span class="menu-label">CBT / Ujian Online</span>
            </a>
            <a href="#" class="menu-card">
                <div class="menu-icon" style="background:#FEF2F2;"><svg fill="none" viewBox="0 0 24 24" stroke="#EF4444" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>
                <span class="menu-label">Pembayaran</span>
            </a>
        </div>
    </div>

    {{-- RIWAYAT ABSENSI --}}
    <div class="card" style="margin-top: 20px;">
        <div class="card-title">Riwayat Absensi (30 Hari Terakhir)</div>
        @if($absensis->count() > 0)
        <table class="absensi-table">
            <thead>
                <tr><th>Tanggal</th><th>Masuk</th><th>Pulang</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($absensis as $a)
                <tr>
                    <td>{{ $a->tanggal->format('d M Y') }}</td>
                    <td>{{ $a->jam_masuk ?? '-' }}</td>
                    <td>{{ $a->jam_pulang ?? '-' }}</td>
                    <td><span class="badge badge-{{ $a->status_masuk }}">{{ ucfirst($a->status_masuk) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="color: #94A3B8; text-align: center; padding: 24px 0; font-size: 0.9rem;">Belum ada data absensi.</p>
        @endif
    </div>
</div>

<script>
new QRCode(document.getElementById('qr-canvas'), {
    text: '{{ $peserta->nisn }}',
    width: 180,
    height: 180,
    colorDark: '#1E293B',
    colorLight: '#FFFFFF',
    correctLevel: QRCode.CorrectLevel.H
});
</script>
</body>
</html>
