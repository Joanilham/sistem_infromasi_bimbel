<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - GeniusEdu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
        .btn-back { background: #EEF2FF; color: #4F46E5; border: 1px solid #C7D2FE; padding: 8px 16px; border-radius: 10px; font-size: 0.8rem; font-weight: 700; cursor: pointer; text-decoration: none; transition: all 0.2s; }
        .btn-back:hover { background: #4F46E5; color: white; }
        .btn-logout { background: #FEF2F2; color: #EF4444; border: 1px solid #FECACA; padding: 8px 16px; border-radius: 10px; font-size: 0.8rem; font-weight: 700; cursor: pointer; text-decoration: none; transition: all 0.2s; }
        .btn-logout:hover { background: #EF4444; color: white; }

        /* MAIN */
        .main { padding: 28px 5%; max-width: 900px; margin: 0 auto; }
        .page-title { font-size: 1.5rem; font-weight: 800; color: #0F172A; margin-bottom: 4px; }
        .page-sub { color: #64748B; font-size: 0.9rem; margin-bottom: 28px; }

        /* CARD */
        .card { background: white; border-radius: 18px; padding: 28px; box-shadow: 0 1px 8px rgba(0,0,0,0.04); border: 1px solid #F1F5F9; margin-bottom: 24px; position: relative; overflow: hidden; }
        .card-accent { position: absolute; top: 0; left: 0; width: 100%; height: 4px; }
        .card-title { font-size: 0.9rem; font-weight: 700; color: #0F172A; margin-bottom: 20px; }

        /* FORM */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .form-input { width: 100%; padding: 10px 14px; border: 1.5px solid #E2E8F0; border-radius: 10px; font-size: 0.9rem; font-family: inherit; color: #0F172A; background: #F8FAFC; transition: border-color 0.2s, box-shadow 0.2s; }
        .form-input:focus { outline: none; border-color: #4F46E5; box-shadow: 0 0 0 3px rgba(79,70,229,0.1); background: white; }
        .form-input[readonly], .form-input[disabled] { background: #F1F5F9; color: #94A3B8; cursor: not-allowed; }
        .form-error { font-size: 0.78rem; color: #EF4444; margin-top: 4px; }
        .form-hint { font-size: 0.72rem; color: #94A3B8; margin-top: 4px; }

        /* ROLE BADGE */
        .role-badge { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; background: #EEF2FF; color: #4F46E5; border: 1.5px solid #C7D2FE; border-radius: 10px; font-size: 0.82rem; font-weight: 700; }

        /* ALERT */
        .alert-success { background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 12px; padding: 14px 18px; color: #065F46; font-size: 0.87rem; font-weight: 600; margin-bottom: 20px; }
        .alert-error { background: #FEF2F2; border: 1px solid #FECACA; border-radius: 12px; padding: 14px 18px; color: #991B1B; font-size: 0.87rem; font-weight: 600; margin-bottom: 20px; }

        /* BUTTON */
        .btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; background: linear-gradient(135deg, #4F46E5, #6366F1); color: white; border: none; border-radius: 12px; font-size: 0.87rem; font-weight: 700; font-family: inherit; cursor: pointer; transition: all 0.2s; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79,70,229,0.3); }
        .footer-actions { display: flex; justify-content: flex-end; margin-top: 10px; }

        .divider { border: none; border-top: 1px solid #F1F5F9; margin: 8px 0 24px; }
    </style>
</head>
<body>
<nav class="navbar">
    <a href="{{ route('siswa.dashboard') }}" class="nav-brand">
        <div class="nav-icon"><svg fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>
        <span class="nav-title">Genius<span>Edu</span></span>
    </a>
    <div class="nav-right">
        <a href="{{ route('siswa.dashboard') }}" class="btn-back">← Kembali</a>
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
    <div class="page-title">Pengaturan Akun</div>
    <div class="page-sub">Kelola informasi profil dan kata sandi Anda.</div>

    @if(session('success'))
        <div class="alert-success">✓ {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-error">⚠ {{ $errors->first() }}</div>
    @endif

    {{-- Informasi Profil --}}
    <div class="card">
        <div class="card-accent" style="background: linear-gradient(90deg, #4F46E5, #06B6D4);"></div>
        <div class="card-title">Informasi Profil</div>
        <form action="{{ route('siswa.profile.update') }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label class="form-label" for="name">Nama Lengkap</label>
                <input class="form-input" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Alamat Surel (Email)</label>
                <input class="form-input" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Role Akun</label>
                <div class="role-badge">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Siswa
                </div>
                <p class="form-hint">Role tidak dapat diubah sendiri. Hubungi administrator.</p>
            </div>
            <div class="footer-actions">
                <button type="submit" class="btn-primary">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>

    <hr class="divider">

    {{-- Ubah Kata Sandi --}}
    <div class="card">
        <div class="card-accent" style="background: linear-gradient(90deg, #1E293B, #334155);"></div>
        <div class="card-title">Perbarui Kata Sandi</div>
        <p style="font-size:0.85rem;color:#64748B;margin-bottom:20px;">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.</p>
        <form action="{{ route('siswa.profile.update') }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label class="form-label" for="current_password">Kata Sandi Saat Ini</label>
                <input class="form-input" type="password" name="current_password" id="current_password">
                @error('current_password') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Kata Sandi Baru</label>
                <input class="form-input" type="password" name="password" id="password">
                @error('password') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi</label>
                <input class="form-input" type="password" name="password_confirmation" id="password_confirmation">
            </div>
            <div class="footer-actions">
                <button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #1E293B, #334155);">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Perbarui Sandi
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
