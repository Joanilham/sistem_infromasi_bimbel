<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Terkunci - Sistem Informasi Bimbingan Belajar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-gradient: radial-gradient(circle at 50% 10%, #1e1b4b 0%, #0f172a 60%, #030712 100%);
            --card-bg: rgba(30, 41, 59, 0.75);
            --card-border: rgba(99, 102, 241, 0.25);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent-primary: #6366f1;
            --accent-glow: rgba(99, 102, 241, 0.35);
            --danger-bg: rgba(239, 68, 68, 0.15);
            --danger-border: rgba(239, 68, 68, 0.4);
            --danger-text: #fca5a5;
            --success-bg: rgba(34, 197, 94, 0.15);
            --success-border: rgba(34, 197, 94, 0.4);
            --success-text: #86efac;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: var(--bg-gradient);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.18), transparent 70%);
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            pointer-events: none;
        }

        .lock-container {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 20px;
            max-width: 620px;
            width: 100%;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7), 0 0 35px var(--accent-glow);
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .icon-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 76px;
            height: 76px;
            background: rgba(99, 102, 241, 0.15);
            border: 2px solid rgba(99, 102, 241, 0.4);
            border-radius: 50%;
            margin-bottom: 20px;
            box-shadow: 0 0 25px rgba(99, 102, 241, 0.35);
        }

        .icon-wrapper svg {
            width: 38px;
            height: 38px;
            color: #818cf8;
        }

        h1 {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
            color: #ffffff;
        }

        .system-name {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #a5b4fc;
            margin-bottom: 20px;
            display: inline-block;
            padding: 4px 14px;
            background: rgba(99, 102, 241, 0.12);
            border-radius: 20px;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .alert-box {
            background: var(--danger-bg);
            border: 1px solid var(--danger-border);
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 24px;
            color: var(--danger-text);
            font-size: 14px;
            line-height: 1.5;
            text-align: left;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .alert-box svg {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
            margin-top: 2px;
        }

        .notification-success {
            background: var(--success-bg);
            border: 1px solid var(--success-border);
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 24px;
            color: var(--success-text);
            font-size: 14px;
            line-height: 1.5;
            text-align: left;
        }

        /* Installation ID Card */
        .id-card {
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(99, 102, 241, 0.35);
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 24px;
            text-align: left;
        }

        .id-header {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            color: #94a3b8;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .id-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #020617;
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 12px 16px;
            border-radius: 10px;
            gap: 12px;
        }

        .id-text {
            font-family: 'JetBrains Mono', monospace;
            font-size: 18px;
            font-weight: 700;
            color: #38bdf8;
            letter-spacing: 0.05em;
            word-break: break-all;
        }

        .btn-copy {
            background: #1e293b;
            color: #f8fafc;
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .btn-copy:hover {
            background: #334155;
            border-color: #6366f1;
        }

        .id-instructions {
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.5;
            margin-top: 12px;
        }

        /* Action Buttons */
        .actions-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 24px;
        }

        .btn-refresh {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            padding: 14px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.15s, box-shadow 0.2s, opacity 0.2s;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4);
        }

        .btn-refresh:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
        }

        .btn-refresh svg {
            width: 18px;
            height: 18px;
        }

        .contact-box {
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(148, 163, 184, 0.12);
            border-radius: 12px;
            padding: 16px;
            text-align: left;
            margin-bottom: 20px;
        }

        .contact-title {
            font-size: 13px;
            font-weight: 700;
            color: #cbd5e1;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contact-desc {
            font-size: 13px;
            color: var(--text-secondary);
            line-height: 1.4;
        }

        .contact-badge {
            margin-top: 10px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #1e293b;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            color: #f1f5f9;
            font-weight: 600;
        }

        .footer-note {
            margin-top: 20px;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>

    <div class="lock-container">
        <!-- Lock Icon -->
        <div class="icon-wrapper">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>

        <div class="system-name">Platform Authorization System</div>
        <h1>Akses Sistem Terkunci</h1>

        @if(session('success'))
            <div class="notification-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-box">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>{{ session('error') }}</div>
            </div>
        @elseif(!empty($reason))
            <div class="alert-box">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <strong>Pemberitahuan Sistem:</strong><br>
                    {{ $reason }}
                </div>
            </div>
        @endif

        <!-- Installation ID Box -->
        <div class="id-card">
            <div class="id-header">
                <span>Unique Installation ID</span>
                <span style="color: #6366f1;">Otorisasi Cloud</span>
            </div>
            <div class="id-box">
                <span class="id-text" id="installIdText">{{ $installation_id ?? 'BIMBEL-UNREGISTERED' }}</span>
                <button type="button" class="btn-copy" onclick="copyInstallId()">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2z"></path>
                    </svg>
                    <span id="copyBtnText">Salin ID</span>
                </button>
            </div>
            <div class="id-instructions">
                Salin <strong>Installation ID</strong> di atas dan kirimkan kepada pengembang resmi (<strong>Joan Ilham</strong>) untuk mendaftarkan lisensi atau memperpanjang masa aktif instansi Anda.
            </div>
        </div>

        <!-- Action Button -->
        <div class="actions-group">
            <a href="{{ url('/system/platform-verify/refresh') }}" class="btn-refresh">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Periksa Ulang Status Lisensi
            </a>
        </div>

        <!-- Developer Contact Card -->
        <div class="contact-box">
            <div class="contact-title">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Pengembang & Pemegang Hak Cipta
            </div>
            <div class="contact-desc">
                Sistem ini dilindungi hak cipta intelektual. Untuk perpanjangan masa aktif atau aktivasi instansi resmi:
            </div>
            <div class="contact-badge">
                Joan Ilham (Lead Developer / Intellectual Property Owner)
            </div>
        </div>

        <div class="footer-note">
            &copy; {{ date('Y') }} Sistem Informasi Bimbingan Belajar. Hak Cipta Dilindungi.
        </div>
    </div>

    <script>
        function copyInstallId() {
            const text = document.getElementById('installIdText').innerText.trim();
            navigator.clipboard.writeText(text).then(() => {
                const btnText = document.getElementById('copyBtnText');
                btnText.innerText = 'Tersalin!';
                setTimeout(() => {
                    btnText.innerText = 'Salin ID';
                }, 2000);
            }).catch(err => {
                prompt('Salin Installation ID Anda:', text);
            });
        }
    </script>
</body>
</html>
