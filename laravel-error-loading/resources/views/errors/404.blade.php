<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 — Halaman Tidak Ditemukan | {{ config('app.name') }}</title>
  <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --amber-light:#FAEEDA;
      --amber-mid:  #EF9F27;
      --amber-dark: #854F0B;
      --text:       #1a1a1a;
      --text-muted: #666;
      --bg:         #fff;
      --surface:    #f8f8f7;
      --border:     rgba(0,0,0,0.07);
    }

    @media (prefers-color-scheme: dark) {
      :root {
        --text:       #f0f0f0;
        --text-muted: #999;
        --bg:         #141414;
        --surface:    #1e1e1e;
        --border:     rgba(255,255,255,0.07);
        --amber-light:#2a1e0a;
      }
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }

    .error-wrap {
      max-width: 480px;
      width: 100%;
      text-align: center;
      animation: err-appear 0.5s cubic-bezier(0.34,1.56,0.64,1) both;
    }

    @keyframes err-appear {
      from { transform: scale(0.9) translateY(20px); opacity: 0; }
      to   { transform: scale(1) translateY(0); opacity: 1; }
    }

    .error-illustration {
      font-size: 64px;
      display: block;
      margin-bottom: 16px;
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50%       { transform: translateY(-10px); }
    }

    .error-code {
      font-size: 80px;
      font-weight: 800;
      line-height: 1;
      margin-bottom: 8px;
      color: var(--amber-mid);
      letter-spacing: -2px;
    }

    .error-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--amber-light);
      color: var(--amber-dark);
      padding: 5px 14px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 600;
      margin-bottom: 20px;
    }

    .error-title {
      font-size: 22px;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .error-desc {
      font-size: 15px;
      color: var(--text-muted);
      line-height: 1.65;
      margin-bottom: 28px;
    }

    .search-box {
      display: flex;
      gap: 8px;
      margin-bottom: 24px;
    }

    .search-box input {
      flex: 1;
      padding: 10px 16px;
      border: 1px solid var(--border);
      border-radius: 10px;
      background: var(--surface);
      color: var(--text);
      font-size: 14px;
      outline: none;
      transition: border-color 0.2s;
    }

    .search-box input:focus {
      border-color: var(--amber-mid);
    }

    .search-box button {
      padding: 10px 18px;
      background: var(--text);
      color: var(--bg);
      border: none;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      transition: opacity 0.15s;
    }

    .search-box button:hover { opacity: 0.85; }

    .error-actions {
      display: flex;
      gap: 10px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .btn {
      padding: 10px 22px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      border: 1.5px solid transparent;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 7px;
      transition: all 0.15s ease;
    }

    .btn-primary {
      background: var(--text);
      color: var(--bg);
      border-color: var(--text);
    }

    .btn-primary:hover { opacity: 0.85; transform: translateY(-1px); }

    .btn-ghost {
      background: transparent;
      color: var(--text);
      border-color: var(--border);
    }

    .btn-ghost:hover { background: var(--surface); transform: translateY(-1px); }

    .logo {
      font-size: 13px;
      color: var(--text-muted);
      margin-bottom: 32px;
      font-weight: 500;
    }
  </style>
</head>
<body>

  <div class="error-wrap">
    <p class="logo">{{ config('app.name') }}</p>

    <span class="error-illustration">🔍</span>
    <div class="error-code">404</div>
    <span class="error-badge">🗺️ Halaman Tidak Ditemukan</span>

    <h1 class="error-title">Halaman Ini Tidak Ada</h1>
    <p class="error-desc">
      Halaman yang Anda cari mungkin telah dipindah, dihapus, atau URL-nya salah.
      Coba cari konten yang Anda butuhkan.
    </p>

    <form class="search-box" action="{{ route('search') ?? url('/search') }}" method="GET">
      <input type="text" name="q" placeholder="Cari di situs ini..." autofocus>
      <button type="submit">Cari</button>
    </form>

    <div class="error-actions">
      <a href="{{ url('/') }}" class="btn btn-primary">
        🏠 Ke Beranda
      </a>
      <a href="javascript:history.back()" class="btn btn-ghost">
        ← Kembali
      </a>
    </div>
  </div>

</body>
</html>
