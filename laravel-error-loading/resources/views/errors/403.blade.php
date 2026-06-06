{{--
  FILE INI UNTUK: 403.blade.php, 419.blade.php, dan 503.blade.php
  Salin isi masing-masing section ke file yang sesuai.
--}}

{{--
=======================================================
  403.blade.php — Akses Ditolak
=======================================================
--}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>403 — Akses Ditolak | {{ config('app.name') }}</title>
  <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --blue-light: #E6F1FB; --blue-mid: #378ADD; --blue-dark: #185FA5;
      --text: #1a1a1a; --text-muted: #666; --bg: #fff; --surface: #f8f8f7; --border: rgba(0,0,0,0.07);
    }
    @media (prefers-color-scheme: dark) {
      :root { --text: #f0f0f0; --text-muted: #999; --bg: #141414; --surface: #1e1e1e; --border: rgba(255,255,255,0.07); --blue-light: #0a1828; }
    }
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
    .wrap { max-width: 440px; width: 100%; text-align: center; animation: appear 0.5s cubic-bezier(0.34,1.56,0.64,1) both; }
    @keyframes appear { from { transform: scale(0.9) translateY(20px); opacity: 0; } to { transform: scale(1) translateY(0); opacity: 1; } }
    .icon { font-size: 60px; display: block; margin-bottom: 16px; }
    .code { font-size: 80px; font-weight: 800; color: var(--blue-mid); letter-spacing: -2px; line-height: 1; margin-bottom: 8px; }
    .badge { display: inline-flex; align-items: center; gap: 6px; background: var(--blue-light); color: var(--blue-dark); padding: 5px 14px; border-radius: 999px; font-size: 12px; font-weight: 600; margin-bottom: 20px; }
    h1 { font-size: 22px; font-weight: 600; margin-bottom: 10px; }
    p { font-size: 15px; color: var(--text-muted); line-height: 1.65; margin-bottom: 28px; }
    .actions { display: flex; gap: 10px; justify-content: center; }
    .btn { padding: 10px 22px; border-radius: 10px; font-size: 14px; font-weight: 500; cursor: pointer; border: 1.5px solid transparent; text-decoration: none; display: inline-flex; align-items: center; gap: 7px; transition: all 0.15s ease; }
    .btn-p { background: var(--text); color: var(--bg); border-color: var(--text); }
    .btn-p:hover { opacity: 0.85; transform: translateY(-1px); }
    .btn-g { background: transparent; color: var(--text); border-color: var(--border); }
    .btn-g:hover { background: var(--surface); transform: translateY(-1px); }
  </style>
</head>
<body>
  <div class="wrap">
    <span class="icon">🔒</span>
    <div class="code">403</div>
    <span class="badge">Akses Ditolak</span>
    <h1>Anda Tidak Memiliki Izin</h1>
    <p>Halaman ini hanya dapat diakses oleh pengguna yang berwenang. Silakan login dengan akun yang sesuai atau hubungi administrator.</p>
    <div class="actions">
      <a href="{{ url('/') }}" class="btn btn-p">🏠 Ke Beranda</a>
      <a href="{{ route('login') ?? url('/login') }}" class="btn btn-g">Login</a>
    </div>
  </div>
</body>
</html>
