<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>500 — Kesalahan Server | {{ config('app.name') }}</title>
  <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --red-light: #FCEBEB;
      --red-mid:   #E24B4A;
      --red-dark:  #A32D2D;
      --text:      #1a1a1a;
      --text-muted:#666;
      --bg:        #fff;
      --surface:   #f8f8f7;
      --border:    rgba(0,0,0,0.07);
    }

    @media (prefers-color-scheme: dark) {
      :root {
        --text:     #f0f0f0;
        --text-muted:#999;
        --bg:       #141414;
        --surface:  #1e1e1e;
        --border:   rgba(255,255,255,0.07);
        --red-light:#2a1414;
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
      to   { transform: scale(1) translateY(0);       opacity: 1; }
    }

    .error-icon {
      font-size: 56px;
      margin-bottom: 16px;
      display: block;
    }

    .error-code {
      font-size: 80px;
      font-weight: 800;
      line-height: 1;
      margin-bottom: 8px;
      color: var(--red-mid);
      letter-spacing: -2px;
    }

    .error-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--red-light);
      color: var(--red-dark);
      padding: 5px 14px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 600;
      margin-bottom: 20px;
      letter-spacing: 0.03em;
    }

    .error-badge::before {
      content: '';
      width: 6px; height: 6px;
      border-radius: 50%;
      background: var(--red-mid);
      animation: badge-pulse 1.5s ease-in-out infinite;
    }

    @keyframes badge-pulse {
      0%,100% { opacity: 1; transform: scale(1); }
      50%      { opacity: 0.4; transform: scale(0.8); }
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

    .error-actions {
      display: flex;
      gap: 10px;
      justify-content: center;
      flex-wrap: wrap;
      margin-bottom: 28px;
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

    .btn-primary:hover {
      opacity: 0.85;
      transform: translateY(-1px);
    }

    .btn-ghost {
      background: transparent;
      color: var(--text);
      border-color: var(--border);
    }

    .btn-ghost:hover {
      background: var(--surface);
      transform: translateY(-1px);
    }

    .error-detail {
      background: var(--surface);
      border: 0.5px solid var(--border);
      border-radius: 12px;
      padding: 16px;
      text-align: left;
    }

    .detail-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 6px 0;
      border-bottom: 0.5px solid var(--border);
      font-size: 13px;
    }

    .detail-row:last-child { border: none; }

    .detail-key { color: var(--text-muted); }

    .detail-val {
      font-family: 'Courier New', monospace;
      font-size: 12px;
      color: var(--text);
    }

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

    <span class="error-icon">⚠️</span>
    <div class="error-code">500</div>
    <span class="error-badge">Kesalahan Server</span>

    <h1 class="error-title">Ups! Terjadi Masalah di Server</h1>
    <p class="error-desc">
      Server kami mengalami masalah dan tidak dapat memproses permintaan ini.
      Tim teknis sudah mendapat notifikasi. Silakan coba lagi beberapa saat.
    </p>

    <div class="error-actions">
      <a href="{{ url()->previous() }}" class="btn btn-primary">
        🔄 Coba Lagi
      </a>
      <a href="{{ url('/') }}" class="btn btn-ghost">
        🏠 Ke Beranda
      </a>
    </div>

    <div class="error-detail">
      <div class="detail-row">
        <span class="detail-key">Kode Error</span>
        <span class="detail-val">HTTP 500 Internal Server Error</span>
      </div>
      <div class="detail-row">
        <span class="detail-key">Waktu</span>
        <span class="detail-val" id="err-time">—</span>
      </div>
      <div class="detail-row">
        <span class="detail-key">Halaman</span>
        <span class="detail-val" id="err-url">—</span>
      </div>
      @if(app()->hasDebugModeEnabled() && isset($exception))
      <div class="detail-row">
        <span class="detail-key">Pesan</span>
        <span class="detail-val">{{ Str::limit($exception->getMessage(), 60) }}</span>
      </div>
      @endif
    </div>
  </div>

  <script>
    document.getElementById('err-time').textContent =
      new Date().toLocaleString('id-ID');
    document.getElementById('err-url').textContent =
      window.location.pathname.substring(0, 50);
  </script>
</body>
</html>
