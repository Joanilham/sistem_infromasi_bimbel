<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - GeniusEdu</title>
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
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: var(--bg-body); 
            color: var(--text-main);
            min-height: 100vh;
        }

        /* ── Top Header ─────────────────────────── */
        .top-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 5%; background: var(--white);
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            position: sticky; top: 0; z-index: 50;
        }
        .btn-back {
            display: flex; align-items: center; gap: 8px; font-weight: 700;
            color: var(--text-muted); text-decoration: none; transition: 0.2s;
        }
        .btn-back:hover { color: var(--primary); }
        .btn-back svg { width: 24px; height: 24px; }
        
        .header-title { font-size: 1.2rem; font-weight: 800; color: var(--text-main); position: absolute; left: 50%; transform: translateX(-50%); }

        /* ── Content Wrapper ────────────────────── */
        .content-wrap { padding: 40px 5%; max-width: 600px; margin: 0 auto; width: 100%; }

        /* ALERTS */
        .alert { padding: 16px 20px; border-radius: 16px; margin-bottom: 24px; font-size: 0.9rem; font-weight: 600; display: flex; gap: 12px; align-items: center; }
        .alert-success { background: #E6F8F1; color: var(--success); border: 1px solid #A7F3D0; }
        .alert-error { background: #FDE8E8; color: var(--danger); border: 1px solid #FECACA; }
        .alert svg { width: 24px; height: 24px; flex-shrink: 0; }

        /* CARD */
        .card { 
            background: var(--white); border-radius: 24px; padding: 32px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid #E9EDF7; margin-bottom: 24px;
        }
        .card-header { display: flex; align-items: center; gap: 16px; margin-bottom: 30px; }
        .card-icon {
            width: 56px; height: 56px; border-radius: 16px;
            background: var(--primary-light); color: var(--primary);
            display: flex; align-items: center; justify-content: center;
        }
        .card-icon svg { width: 28px; height: 28px; }
        .card-title { font-size: 1.2rem; font-weight: 800; color: var(--text-main); }
        .card-subtitle { font-size: 0.85rem; color: var(--text-muted); font-weight: 500; margin-top: 4px; }

        /* FORM */
        .form-group { margin-bottom: 24px; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-main); margin-bottom: 10px; }
        .form-control {
            width: 100%; padding: 16px 20px; border-radius: 16px;
            border: 1px solid #E9EDF7; background: var(--bg-body);
            font-size: 0.95rem; color: var(--text-main); font-weight: 500;
            font-family: inherit; transition: all 0.2s;
        }
        .form-control:focus { outline: none; border-color: var(--primary); background: var(--white); box-shadow: 0 0 0 4px rgba(67, 24, 255, 0.1); }
        .text-danger { color: var(--danger); font-size: 0.8rem; font-weight: 600; margin-top: 6px; display: block; }

        /* BUTTONS */
        .btn-submit {
            width: 100%; padding: 18px; border-radius: 16px; border: none;
            background: var(--primary); color: white;
            font-size: 1rem; font-weight: 800; font-family: inherit;
            cursor: pointer; transition: all 0.2s;
            box-shadow: 0 10px 20px rgba(67, 24, 255, 0.2);
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 12px 25px rgba(67, 24, 255, 0.3); }

    </style>
</head>
<body>

<header class="top-header">
    <a href="{{ route('siswa.dashboard') }}" class="btn-back">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>
    <div class="header-title">Profil Saya</div>
    <div style="width:24px;"></div> <!-- Spacer -->
</header>

<div class="content-wrap">
    
    @if(session('success'))
        <div class="alert alert-success">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div>{{ session('success') }}</div>
        </div>
    @endif
    @if($errors->any() && !session('success'))
        <div class="alert alert-error">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            <div>Mohon periksa kembali form pengisian di bawah.</div>
        </div>
    @endif

    {{-- Form Data Pribadi --}}
    <div class="card">
        <div class="card-header">
            <div class="card-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            </div>
            <div>
                <div class="card-title">Informasi Akun</div>
                <div class="card-subtitle">Perbarui nama dan alamat email.</div>
            </div>
        </div>

        <form method="POST" action="{{ route('siswa.profile.update') }}">
            @csrf
            @method('patch')

            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Email / Username</label>
                <input type="text" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn-submit">
                Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- Form Ubah Password --}}
    <div class="card">
        <div class="card-header">
            <div class="card-icon" style="background: #FFF8D6; color: #FFCE20;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
            </div>
            <div>
                <div class="card-title">Ubah Kata Sandi</div>
                <div class="card-subtitle">Pastikan akun Anda tetap aman.</div>
            </div>
        </div>

        <form method="POST" action="{{ route('siswa.profile.update') }}">
            @csrf
            @method('patch')

            <div class="form-group">
                <label class="form-label">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" class="form-control" required>
                @error('current_password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Kata Sandi Baru</label>
                <input type="password" name="password" class="form-control" required>
                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn-submit" style="background: var(--text-main); box-shadow: 0 10px 20px rgba(43, 54, 116, 0.2);">
                Perbarui Kata Sandi
            </button>
        </form>
    </div>

</div>

</body>
</html>
