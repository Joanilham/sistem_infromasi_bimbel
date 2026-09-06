<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Otorisasi Sistem Diperlukan — GeniusEdu Platform Suite</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-base: #06080e;
            --surface-card: rgba(13, 18, 30, 0.75);
            --surface-card-hover: rgba(18, 24, 40, 0.85);
            --surface-inner: rgba(6, 10, 19, 0.85);
            --border-subtle: rgba(99, 102, 241, 0.18);
            --border-highlight: rgba(129, 140, 248, 0.4);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --accent-indigo: #6366f1;
            --accent-cyan: #38bdf8;
            --accent-glow: rgba(99, 102, 241, 0.25);
            --warning-surface: rgba(245, 158, 11, 0.1);
            --warning-border: rgba(245, 158, 11, 0.35);
            --warning-text: #fbbf24;
            --danger-surface: rgba(239, 68, 68, 0.12);
            --danger-border: rgba(239, 68, 68, 0.35);
            --danger-text: #fca5a5;
            --success-surface: rgba(16, 185, 129, 0.12);
            --success-border: rgba(16, 185, 129, 0.35);
            --success-text: #6ee7b7;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-base);
            color: var(--text-primary);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* High-tech Canvas Cyber Grid */
        .bg-grid-overlay {
            position: fixed;
            inset: 0;
            background-image: 
                radial-gradient(rgba(99, 102, 241, 0.12) 1px, transparent 1px),
                linear-gradient(to right, rgba(255, 255, 255, 0.015) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.015) 1px, transparent 1px);
            background-size: 32px 32px, 96px 96px, 96px 96px;
            pointer-events: none;
            z-index: 0;
            mask-image: radial-gradient(ellipse at 50% 50%, black 40%, transparent 85%);
            -webkit-mask-image: radial-gradient(ellipse at 50% 50%, black 40%, transparent 85%);
        }

        /* Ambient Aurora Glow Spheres */
        .ambient-sphere {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
            z-index: 0;
            opacity: 0.6;
        }

        .ambient-1 {
            width: 550px;
            height: 550px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.28) 0%, rgba(56, 189, 248, 0.08) 60%, transparent 70%);
            top: -150px;
            left: 50%;
            transform: translateX(-50%);
            animation: pulseAura 8s ease-in-out infinite alternate;
        }

        .ambient-2 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.18) 0%, transparent 70%);
            bottom: -100px;
            right: 15%;
        }

        @keyframes pulseAura {
            0% { transform: translateX(-50%) scale(1); opacity: 0.5; }
            100% { transform: translateX(-50%) scale(1.12); opacity: 0.75; }
        }

        /* Top Brand Navigation Pill */
        .brand-pill {
            position: relative;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(148, 163, 184, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 8px 20px;
            border-radius: 100px;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .brand-logo-node {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            background: linear-gradient(135deg, #6366f1 0%, #38bdf8 100%);
            border-radius: 6px;
            box-shadow: 0 0 10px rgba(56, 189, 248, 0.5);
        }

        .brand-logo-node svg {
            width: 13px;
            height: 13px;
            color: #ffffff;
        }

        .brand-text {
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: #f1f5f9;
            text-transform: uppercase;
        }

        .status-dot-pulse {
            width: 7px;
            height: 7px;
            background-color: #f59e0b;
            border-radius: 50%;
            box-shadow: 0 0 10px #f59e0b;
            animation: blinkDot 1.6s infinite ease-in-out;
        }

        @keyframes blinkDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }

        .status-label {
            font-size: 11px;
            font-weight: 600;
            color: #fbbf24;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        /* Main Glassmorphic Security Module Card */
        .security-console {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 680px;
            background: var(--surface-card);
            border: 1px solid var(--border-subtle);
            border-radius: 28px;
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            padding: 44px 40px;
            box-shadow: 
                0 0 0 1px rgba(255, 255, 255, 0.05) inset,
                0 30px 60px -15px rgba(0, 0, 0, 0.8),
                0 0 50px -10px var(--accent-glow);
            text-align: center;
        }

        /* Top Inner Accent Light Line */
        .security-console::after {
            content: '';
            position: absolute;
            top: 0;
            left: 20%;
            right: 20%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(129, 140, 248, 0.8), transparent);
            pointer-events: none;
        }

        /* Holographic Security Emblem */
        .shield-emblem-container {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 88px;
            height: 88px;
            margin-bottom: 24px;
        }

        .shield-outer-ring {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 1px dashed rgba(99, 102, 241, 0.35);
            animation: rotateRadar 24s linear infinite;
        }

        .shield-glow-ring {
            position: absolute;
            inset: 6px;
            border-radius: 50%;
            border: 2px solid rgba(99, 102, 241, 0.3);
            box-shadow: 0 0 25px rgba(99, 102, 241, 0.35), inset 0 0 15px rgba(99, 102, 241, 0.25);
        }

        .shield-core-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(30, 27, 75, 0.9) 0%, rgba(15, 23, 42, 0.95) 100%);
            border: 1px solid rgba(129, 140, 248, 0.5);
            border-radius: 50%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }

        .shield-core-icon svg {
            width: 28px;
            height: 28px;
            color: #818cf8;
            filter: drop-shadow(0 0 8px rgba(129, 140, 248, 0.6));
        }

        @keyframes rotateRadar {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Title & Headings */
        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.25;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #ffffff 30%, #c7d2fe 70%, #818cf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle-desc {
            font-size: 14px;
            line-height: 1.6;
            color: var(--text-secondary);
            max-width: 520px;
            margin: 0 auto 28px;
        }

        /* Status & Alert Banners */
        .notification-banner {
            border-radius: 14px;
            padding: 14px 18px;
            margin-bottom: 24px;
            font-size: 13px;
            line-height: 1.5;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            text-align: left;
        }

        .banner-warning {
            background: var(--warning-surface);
            border: 1px solid var(--warning-border);
            color: var(--warning-text);
        }

        .banner-danger {
            background: var(--danger-surface);
            border: 1px solid var(--danger-border);
            color: var(--danger-text);
        }

        .banner-success {
            background: var(--success-surface);
            border: 1px solid var(--success-border);
            color: var(--success-text);
        }

        .notification-banner svg {
            flex-shrink: 0;
            width: 18px;
            height: 18px;
            margin-top: 1px;
        }

        /* Installation ID Cyber Hardware Key Block */
        .id-cyber-console {
            background: var(--surface-inner);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 18px;
            padding: 24px;
            margin-bottom: 24px;
            text-align: left;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4), inset 0 0 20px rgba(99, 102, 241, 0.05);
        }

        .id-cyber-console::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 100%;
            background: linear-gradient(180deg, #6366f1, #38bdf8);
        }

        .console-meta-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .console-badge-label {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .live-chip {
            background: rgba(16, 185, 129, 0.12);
            color: #34d399;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            border: 1px solid rgba(16, 185, 129, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .id-display-frame {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(2, 6, 23, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 14px 18px;
            border-radius: 12px;
            gap: 14px;
        }

        .id-crypto-string {
            font-family: 'JetBrains Mono', monospace;
            font-size: 20px;
            font-weight: 800;
            color: #38bdf8;
            letter-spacing: 0.06em;
            word-break: break-all;
            text-shadow: 0 0 12px rgba(56, 189, 248, 0.4);
        }

        .btn-cyber-copy {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(15, 23, 42, 0.9));
            color: #f8fafc;
            border: 1px solid rgba(99, 102, 241, 0.4);
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.02em;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .btn-cyber-copy:hover {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(56, 189, 248, 0.2));
            border-color: #38bdf8;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(56, 189, 248, 0.25);
        }

        .btn-cyber-copy:active {
            transform: translateY(0);
        }

        .id-helper-caption {
            margin-top: 12px;
            font-size: 13px;
            line-height: 1.5;
            color: var(--text-secondary);
        }

        .id-helper-caption strong {
            color: #f1f5f9;
        }

        /* Primary Action Section */
        .action-hub {
            margin-bottom: 24px;
        }

        .btn-authorize-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 50%, #4338ca 100%);
            color: #ffffff;
            font-family: 'Outfit', sans-serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.02em;
            padding: 16px 24px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.45), 0 0 0 1px rgba(99, 102, 241, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-authorize-action::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }

        .btn-authorize-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(99, 102, 241, 0.6), 0 0 30px rgba(56, 189, 248, 0.3);
        }

        .btn-authorize-action:hover::before {
            left: 100%;
        }

        .btn-authorize-action:active {
            transform: translateY(0);
        }

        .btn-authorize-action svg {
            width: 19px;
            height: 19px;
            transition: transform 0.4s ease;
        }

        .btn-authorize-action:hover svg {
            transform: rotate(180deg);
        }

        /* Official Authority & Legal Ownership Card */
        .authority-card {
            background: rgba(15, 23, 42, 0.55);
            border: 1px solid rgba(148, 163, 184, 0.12);
            border-radius: 16px;
            padding: 18px 20px;
            text-align: left;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .authority-meta {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .authority-avatar-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(15, 23, 42, 0.9));
            border: 1px solid rgba(129, 140, 248, 0.4);
            border-radius: 12px;
            flex-shrink: 0;
        }

        .authority-avatar-badge svg {
            width: 22px;
            height: 22px;
            color: #a5b4fc;
        }

        .authority-name {
            font-size: 14px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .verified-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 15px;
            height: 15px;
            background: #10b981;
            color: #ffffff;
            border-radius: 50%;
            font-size: 9px;
        }

        .authority-role {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .authority-badge-right {
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.25);
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            color: #a5b4fc;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }

        /* Collapsible Emergency Offline Key Section */
        .manual-key-accordion {
            margin-top: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            padding-top: 16px;
        }

        .accordion-toggle {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s;
        }

        .accordion-toggle:hover {
            color: #c7d2fe;
        }

        .manual-form-body {
            display: none;
            margin-top: 14px;
            text-align: left;
        }

        .manual-form-body.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .manual-textarea {
            width: 100%;
            background: rgba(2, 6, 23, 0.9);
            border: 1px solid rgba(148, 163, 184, 0.25);
            border-radius: 10px;
            padding: 12px;
            color: #f8fafc;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            resize: vertical;
            min-height: 70px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .manual-textarea:focus {
            border-color: var(--accent-indigo);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
        }

        .btn-manual-submit {
            margin-top: 10px;
            width: 100%;
            background: #1e293b;
            color: #e2e8f0;
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 10px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-manual-submit:hover {
            background: #334155;
            color: #ffffff;
            border-color: #6366f1;
        }

        /* Footer Copyright */
        .footer-legal {
            margin-top: 24px;
            font-size: 12px;
            color: var(--text-muted);
            letter-spacing: 0.02em;
        }

        @media (max-width: 640px) {
            .security-console {
                padding: 32px 20px;
            }
            .id-crypto-string {
                font-size: 16px;
            }
            .authority-card {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

    <!-- Cyber Grid Canvas Overlay -->
    <div class="bg-grid-overlay"></div>
    <div class="ambient-sphere ambient-1"></div>
    <div class="ambient-sphere ambient-2"></div>

    <!-- Top Identity Pill -->
    <div class="brand-pill">
        <div class="brand-logo-node">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
        </div>
        <span class="brand-text">GeniusEdu Platform Suite</span>
        <div style="width: 1px; height: 14px; background: rgba(255,255,255,0.15);"></div>
        <div class="status-dot-pulse"></div>
        <span class="status-label">Menunggu Otorisasi</span>
    </div>

    <!-- Main Security Console Module -->
    <div class="security-console">

        <!-- Holographic Shield Emblem -->
        <div class="shield-emblem-container">
            <div class="shield-outer-ring"></div>
            <div class="shield-glow-ring"></div>
            <div class="shield-core-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
        </div>

        <h1>Otorisasi Lisensi Diperlukan</h1>
        <p class="subtitle-desc">
            Instansi perangkat lunak ini terikat dalam protokol perlindungan lisensi terpusat. Untuk mengaktifkan akses seluruh modul akademik & manajemen, hubungkan dengan lisensi resmi.
        </p>

        @if(session('success'))
            <div class="notification-banner banner-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="notification-banner banner-danger">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>{{ session('error') }}</div>
            </div>
        @elseif(!empty($reason))
            <div class="notification-banner banner-warning">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <strong>Pemberitahuan Sistem:</strong><br>
                    {{ $reason }}
                </div>
            </div>
        @endif

        <!-- Unique Installation Hardware Key Box -->
        <div class="id-cyber-console">
            <div class="console-meta-bar">
                <div class="console-badge-label">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    SYSTEM INSTALLATION FINGERPRINT
                </div>
                <span class="live-chip">Otorisasi Cloud</span>
            </div>

            <div class="id-display-frame">
                <span class="id-crypto-string" id="installIdText">{{ $installation_id ?? 'BIMBEL-UNREGISTERED' }}</span>
                <button type="button" class="btn-cyber-copy" onclick="copyInstallId()">
                    <svg id="copyIcon" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2z"></path>
                    </svg>
                    <span id="copyBtnText">Salin ID</span>
                </button>
            </div>

            <div class="id-helper-caption">
                Kirimkan <strong>Installation ID</strong> di atas kepada pengembang resmi (<strong>Joan Ilham</strong>) untuk penerbitan izin lisensi instansi Anda.
            </div>
        </div>

        <!-- Primary Action Hub -->
        <div class="action-hub">
            <a href="{{ url('/system/platform-verify/refresh') }}" class="btn-authorize-action">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Periksa Ulang Otorisasi Cloud
            </a>
        </div>

        <!-- Official Authority & Legal Ownership Card -->
        <div class="authority-card">
            <div class="authority-meta">
                <div class="authority-avatar-badge">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <div class="authority-name">
                        Joan Ilham
                        <span class="verified-badge">✓</span>
                    </div>
                    <div class="authority-role">Lead Architect & Pemegang Hak Cipta Sah</div>
                </div>
            </div>
            <div class="authority-badge-right">
                Resmi Terdaftar
            </div>
        </div>

        <!-- Emergency Offline Key Collapsible -->
        <div class="manual-key-accordion">
            <button type="button" class="accordion-toggle" onclick="toggleManualForm()">
                <span>Punya Kunci Lisensi Manual / Token Offline?</span>
                <span id="toggleArrow">▼</span>
            </button>
            <div class="manual-form-body" id="manualFormBlock">
                <form method="POST" action="{{ url('/system/platform-verify') }}">
                    @csrf
                    <textarea 
                        class="manual-textarea" 
                        name="license_key" 
                        placeholder="Tempelkan token lisensi HMAC/RSA resmi Anda di sini..."
                        required
                    ></textarea>
                    <button type="submit" class="btn-manual-submit">
                        Aktifkan Kunci Manual
                    </button>
                </form>
            </div>
        </div>

        <div class="footer-legal">
            &copy; {{ date('Y') }} GeniusEdu Sistem Informasi Bimbingan Belajar. Hak Cipta Dilindungi Undang-Undang.
        </div>
    </div>

    <script>
        function copyInstallId() {
            const text = document.getElementById('installIdText').innerText.trim();
            const btnText = document.getElementById('copyBtnText');
            const copyIcon = document.getElementById('copyIcon');

            navigator.clipboard.writeText(text).then(() => {
                btnText.innerText = 'Tersalin!';
                copyIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>';
                setTimeout(() => {
                    btnText.innerText = 'Salin ID';
                    copyIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2z"></path>';
                }, 2500);
            }).catch(err => {
                prompt('Salin Installation ID Anda:', text);
            });
        }

        function toggleManualForm() {
            const form = document.getElementById('manualFormBlock');
            const arrow = document.getElementById('toggleArrow');
            if (form.classList.contains('active')) {
                form.classList.remove('active');
                arrow.innerText = '▼';
            } else {
                form.classList.add('active');
                arrow.innerText = '▲';
            }
        }
    </script>
</body>
</html>
