<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Otorisasi Sistem Diperlukan — GeniusEdu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f8fafc;
            color: #0f172a;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .container {
            width: 100%;
            max-width: 520px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 32px 28px;
            text-align: center;
        }

        .icon-wrapper {
            width: 52px;
            height: 52px;
            background-color: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            color: #2563eb;
        }

        .icon-wrapper svg {
            width: 26px;
            height: 26px;
        }

        h1 {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .description {
            font-size: 14px;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 24px;
        }

        /* Alert Notifications */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 8px;
            font-size: 13px;
            line-height: 1.5;
            text-align: left;
            margin-bottom: 20px;
        }

        .alert-warning {
            background-color: #fffbeb;
            border: 1px solid #fef3c7;
            color: #92400e;
        }

        .alert-danger {
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            color: #991b1b;
        }

        .alert-success {
            background-color: #f0fdf4;
            border: 1px solid #dcfce7;
            color: #166534;
        }

        .alert svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* Installation ID Card */
        .id-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
            text-align: left;
        }

        .id-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .id-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px 12px;
        }

        .id-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 15px;
            font-weight: 600;
            color: #0f172a;
            letter-spacing: 0.02em;
        }

        .btn-copy {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #334155;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.15s ease;
        }

        .btn-copy:hover {
            background-color: #e2e8f0;
            color: #0f172a;
        }

        .id-hint {
            font-size: 12px;
            color: #64748b;
            margin-top: 8px;
            line-height: 1.4;
        }

        /* Action Buttons */
        .actions {
            margin-bottom: 20px;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            background-color: #2563eb;
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 10px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
        }

        .btn-primary svg {
            width: 16px;
            height: 16px;
        }

        /* Author Attribution */
        .author-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 12px;
            margin-bottom: 16px;
            text-align: left;
        }

        .author-info {
            color: #475569;
        }

        .author-name {
            font-weight: 600;
            color: #0f172a;
        }

        .badge-verified {
            background-color: #e0f2fe;
            color: #0369a1;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 4px;
        }

        /* Accordion for Manual Key */
        .manual-section {
            border-top: 1px solid #f1f5f9;
            padding-top: 16px;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: #64748b;
            font-size: 12px;
            cursor: pointer;
            text-decoration: underline;
        }

        .toggle-btn:hover {
            color: #2563eb;
        }

        .manual-content {
            display: none;
            margin-top: 12px;
            text-align: left;
        }

        .manual-content.active {
            display: block;
        }

        .manual-textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            min-height: 70px;
            margin-bottom: 8px;
            outline: none;
        }

        .manual-textarea:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }

        .btn-submit {
            width: 100%;
            background-color: #0f172a;
            color: #ffffff;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #334155;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Clean Lock Icon -->
        <div class="icon-wrapper">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>

        <h1>Otorisasi Sistem Diperlukan</h1>
        <p class="description">
            Akses sistem memerlukan otorisasi lisensi resmi yang terverifikasi. Silakan periksa status lisensi Anda atau hubungi pengembang.
        </p>

        @if(session('success'))
            <div class="alert alert-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>{{ session('error') }}</div>
            </div>
        @elseif(!empty($reason))
            <div class="alert alert-warning">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <strong>Pemberitahuan Sistem:</strong><br>
                    {{ $reason }}
                </div>
            </div>
        @endif

        <!-- Installation ID Box -->
        <div class="id-card">
            <div class="id-label">
                <span>Installation ID</span>
                <span>Terdaftar di Perangkat Ini</span>
            </div>
            <div class="id-row">
                <span class="id-value" id="installIdText">{{ $installation_id ?? 'BIMBEL-UNREGISTERED' }}</span>
                <button type="button" class="btn-copy" onclick="copyInstallId()">
                    <span id="copyBtnText">Salin ID</span>
                </button>
            </div>
            <div class="id-hint">
                Kirimkan ID di atas kepada <strong>Admin</strong> untuk aktivasi atau perpanjangan lisensi.
            </div>
        </div>

        <!-- Action Button -->
        <div class="actions">
            <a href="{{ url('/system/platform-verify/refresh') }}" class="btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Periksa Ulang Otorisasi
            </a>
        </div>

        <!-- Developer Attribution -->
        <div class="author-box">
            <div class="author-info">
                Pusat Dukungan: <span class="author-name">Admin</span>
            </div>
            <span class="badge-verified">Hak Cipta Terdaftar</span>
        </div>

        <!-- Manual Offline Key Collapsible -->
        <div class="manual-section">
            <button type="button" class="toggle-btn" onclick="toggleManualForm()">
                Input Kunci Lisensi Manual / Offline
            </button>
            <div class="manual-content" id="manualFormBlock">
                <form method="POST" action="{{ url('/system/platform-verify') }}">
                    @csrf
                    <textarea 
                        class="manual-textarea" 
                        name="license_key" 
                        placeholder="Tempelkan token lisensi resmi di sini..."
                        required
                    ></textarea>
                    <button type="submit" class="btn-submit">
                        Simpan Kunci
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} GeniusEdu — Sistem Informasi Bimbingan Belajar.
    </div>

    <script>
        function copyInstallId() {
            const text = document.getElementById('installIdText').innerText.trim();
            const btnText = document.getElementById('copyBtnText');

            navigator.clipboard.writeText(text).then(() => {
                btnText.innerText = 'Tersalin!';
                setTimeout(() => {
                    btnText.innerText = 'Salin ID';
                }, 2000);
            }).catch(err => {
                prompt('Salin Installation ID Anda:', text);
            });
        }

        function toggleManualForm() {
            const form = document.getElementById('manualFormBlock');
            form.classList.toggle('active');
        }
    </script>
</body>
</html>
