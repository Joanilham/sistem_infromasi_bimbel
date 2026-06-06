<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="refresh" content="30"> {{-- Auto-refresh tiap 30 detik --}}
  <title>Sedang Pemeliharaan | {{ config('app.name') }}</title>
  <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --green: #639922; --green-light: #EAF3DE; --green-dark: #3B6D11;
      --text: #1a1a1a; --text-muted: #666; --bg: #fff; --surface: #f8f8f7; --border: rgba(0,0,0,0.07);
    }
    @media (prefers-color-scheme: dark) {
      :root { --text: #f0f0f0; --text-muted: #999; --bg: #141414; --surface: #1e1e1e; --border: rgba(255,255,255,0.07); --green-light: #0e1f06; }
    }
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
    .wrap { max-width: 480px; width: 100%; text-align: center; }
    .icon { font-size: 60px; display: block; margin-bottom: 16px; animation: spin-slow 4s linear infinite; }
    @keyframes spin-slow { to { transform: rotate(360deg); } }
    .badge { display: inline-flex; align-items: center; gap: 6px; background: var(--green-light); color: var(--green-dark); padding: 5px 14px; border-radius: 999px; font-size: 12px; font-weight: 600; margin-bottom: 20px; }
    h1 { font-size: 24px; font-weight: 700; margin-bottom: 10px; }
    p { font-size: 15px; color: var(--text-muted); line-height: 1.65; margin-bottom: 28px; }
    .countdown { font-size: 13px; color: var(--text-muted); margin-top: 20px; }
    .countdown strong { color: var(--green); }
    .progress-wrap { width: 100%; height: 6px; background: var(--surface); border-radius: 999px; margin: 16px 0; overflow: hidden; }
    .progress-bar { height: 100%; background: var(--green); border-radius: 999px; animation: maintenance-bar 30s linear forwards; }
    @keyframes maintenance-bar { from { width: 0%; } to { width: 100%; } }
  </style>
</head>
<body>
  <div class="wrap">
    <span class="icon">⚙️</span>
    <span class="badge">🛠️ Sedang Pemeliharaan</span>
    <h1>Kami Sedang Melakukan Pembaruan</h1>
    <p>
      Website sedang dalam proses pemeliharaan untuk meningkatkan pengalaman Anda.
      Kami akan kembali segera. Halaman ini akan otomatis dimuat ulang.
    </p>

    <div class="progress-wrap">
      <div class="progress-bar"></div>
    </div>

    <div class="countdown">
      Halaman dimuat ulang dalam <strong id="countdown">30</strong> detik
    </div>
  </div>

  <script>
    let seconds = 30;
    const el = document.getElementById('countdown');
    const timer = setInterval(() => {
      seconds--;
      el.textContent = seconds;
      if (seconds <= 0) {
        clearInterval(timer);
        window.location.reload();
      }
    }, 1000);
  </script>
</body>
</html>
