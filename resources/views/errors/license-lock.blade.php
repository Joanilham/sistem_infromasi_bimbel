<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Terkunci - Sistem Informasi Bimbingan Belajar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-gradient: radial-gradient(circle at 50% 10%, #1e1b4b 0%, #0f172a 60%, #030712 100%);
            --card-bg: rgba(30, 41, 59, 0.7);
            --card-border: rgba(99, 102, 241, 0.25);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent-primary: #6366f1;
            --accent-glow: rgba(99, 102, 241, 0.4);
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

        /* Subtle background glow effect */
        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15), transparent 70%);
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
            max-width: 600px;
            width: 100%;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 0 0 30px var(--accent-glow);
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
            margin-bottom: 24px;
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
            margin-bottom: 12px;
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
            padding: 16px;
            margin-bottom: 24px;
            color: var(--danger-text);
            font-size: 14px;
            line-height: 1.6;
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
            padding: 16px;
            margin-bottom: 24px;
            color: var(--success-text);
            font-size: 14px;
            line-height: 1.6;
            text-align: left;
        }

        .contact-box {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(148, 163, 184, 0.15);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 28px;
            text-align: left;
        }

        .contact-title {
            font-size: 13px;
            font-weight: 700;
            color: #cbd5e1;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contact-desc {
            font-size: 13px;
            color: var(--text-secondary);
            line-height: 1.5;
            margin-bottom: 14px;
        }

        .contact-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #1e293b;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            color: #f1f5f9;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .contact-badge svg {
            width: 16px;
            height: 16px;
            color: #38bdf8;
        }

        /* Activation Form */
        .activation-form {
            text-align: left;
            margin-top: 10px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(148, 163, 184, 0.25);
            border-radius: 10px;
            padding: 12px 14px;
            color: #f8fafc;
            font-size: 13px;
            font-family: monospace;
            resize: vertical;
            min-height: 80px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
        }

        .btn-submit {
            margin-top: 14px;
            width: 100%;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            padding: 13px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.2s, opacity 0.2s;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4);
        }

        .btn-submit:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .footer-note {
            margin-top: 24px;
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
                    <strong>Pemberitahuan:</strong><br>
                    {{ $reason }}
                </div>
            </div>
        @endif

        <!-- Developer Contact Card -->
        <div class="contact-box">
            <div class="contact-title">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Otoritas Pengembang & Lisensi
            </div>
            <div class="contact-desc">
                Sistem ini dilindungi hak cipta intelektual. Untuk perpanjangan masa aktif, pendaftaran domain resmi, atau penerbitan License Key baru, silakan hubungi pengembang resmi:
            </div>
            <div class="contact-badge">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Pengembang Resmi: Joan Ilham (Lead Developer)
            </div>
        </div>

        <!-- Input License Form -->
        <form class="activation-form" method="POST" action="{{ url('/system/platform-verify') }}">
            @csrf
            <label class="form-label" for="license_key">Punya License Key Baru? Masukkan di sini:</label>
            <textarea 
                class="form-control" 
                id="license_key" 
                name="license_key" 
                rows="3" 
                placeholder="Tempelkan kode lisensi resmi Anda di sini..." 
                required
            ></textarea>
            <button type="submit" class="btn-submit">
                Verifikasi & Aktifkan Kembali Akses Sistem
            </button>
        </form>

        <div class="footer-note">
            &copy; {{ date('Y') }} Sistem Informasi Bimbingan Belajar. Hak Cipta Dilindungi.
        </div>
    </div>

</body>
</html>
