<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pendaftaran Siswa') - Genius Education</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #EEF2FF 0%, #F0FDFF 50%, #ECFDF5 100%);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }
        .top-bar {
            background: white; border-bottom: 1px solid #E2E8F0;
            padding: 14px 5%; display: flex; align-items: center; justify-content: space-between;
        }
        .logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .logo-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, #4F46E5, #06B6D4);
            display: flex; align-items: center; justify-content: center;
        }
        .logo-icon svg { width: 20px; height: 20px; }
        .logo-text { font-size: 1rem; font-weight: 800; color: #0F172A; }
        .logo-text span { color: #4F46E5; }
        .top-bar-right { font-size: 0.85rem; color: #64748B; }
        .top-bar-right a { color: #4F46E5; font-weight: 600; text-decoration: none; }
        /* STEPPER */
        .stepper-wrap { background: white; border-bottom: 1px solid #E2E8F0; padding: 20px 5%; }
        .stepper { max-width: 600px; margin: 0 auto; display: flex; align-items: center; gap: 0; }
        .step { display: flex; align-items: center; flex: 1; }
        .step-circle {
            width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem; font-weight: 700; border: 2px solid #CBD5E1;
            background: white; color: #94A3B8; position: relative; z-index: 1;
        }
        .step-circle.active { border-color: #4F46E5; background: #4F46E5; color: white; }
        .step-circle.done { border-color: #10B981; background: #10B981; color: white; }
        .step-label { font-size: 0.72rem; font-weight: 600; color: #94A3B8; margin-top: 5px; text-align: center; }
        .step-label.active { color: #4F46E5; }
        .step-label.done { color: #10B981; }
        .step-inner { display: flex; flex-direction: column; align-items: center; }
        .step-line { flex: 1; height: 2px; background: #E2E8F0; margin: 0 6px; margin-bottom: 18px; }
        .step-line.done { background: #10B981; }
        /* MAIN */
        .main { flex: 1; padding: 40px 5%; }
        .card { max-width: 780px; margin: 0 auto; background: white; border-radius: 20px; padding: 36px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); }
        .card-title { font-size: 1.3rem; font-weight: 800; color: #0F172A; margin-bottom: 6px; }
        .card-sub { font-size: 0.88rem; color: #64748B; margin-bottom: 28px; }
        .section-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #4F46E5; margin-bottom: 16px; margin-top: 24px; padding-top: 20px; border-top: 1px solid #F1F5F9; }
        .section-label:first-of-type { border-top: none; margin-top: 0; padding-top: 0; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-grid.full { grid-template-columns: 1fr; }
        .form-col-full { grid-column: 1 / -1; }
        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .form-group label { font-size: 0.82rem; font-weight: 600; color: #374151; }
        .form-group label .req { color: #EF4444; }
        .form-control {
            border: 1.5px solid #E2E8F0; border-radius: 10px; padding: 10px 14px;
            font-size: 0.9rem; font-family: inherit; color: #0F172A;
            transition: border-color 0.2s, box-shadow 0.2s; outline: none;
            background: #FAFBFC; width: 100%;
        }
        .form-control:focus { border-color: #4F46E5; box-shadow: 0 0 0 3px rgba(79,70,229,0.1); background: white; }
        select.form-control { cursor: pointer; }
        textarea.form-control { resize: vertical; min-height: 90px; }
        .invalid-feedback { font-size: 0.78rem; color: #EF4444; margin-top: 2px; }
        .alert-danger {
            background: #FEF2F2; border: 1px solid #FECACA; border-radius: 12px;
            padding: 14px 16px; margin-bottom: 24px; font-size: 0.85rem; color: #DC2626;
        }
        .alert-danger ul { margin: 8px 0 0 16px; }
        .btn-row { display: flex; justify-content: flex-end; gap: 12px; margin-top: 28px; padding-top: 20px; border-top: 1px solid #F1F5F9; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 11px 24px; border-radius: 10px; font-weight: 700; font-size: 0.9rem; cursor: pointer; border: none; text-decoration: none; transition: all 0.2s; }
        .btn-primary { background: linear-gradient(135deg, #4F46E5, #3730A3); color: white; box-shadow: 0 4px 12px rgba(79,70,229,0.3); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79,70,229,0.4); }
        .btn-secondary { background: #F8FAFC; color: #64748B; border: 1.5px solid #E2E8F0; }
        .btn-secondary:hover { border-color: #94A3B8; color: #374151; }
        @media (max-width: 600px) {
            .form-grid { grid-template-columns: 1fr; }
            .card { padding: 22px 16px; }
        }
    </style>
    @yield('extra_style')
</head>
<body>
<div class="top-bar">
    <a href="{{ route('welcome') }}" class="logo">
        <div class="logo-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <span class="logo-text">Genius<span>Edu</span></span>
    </a>
    <div class="top-bar-right">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></div>
</div>

<div class="stepper-wrap">
    <div class="stepper">
        <div class="step">
            <div class="step-inner">
                <div class="step-circle @yield('step1_class', '')">1</div>
                <div class="step-label @yield('step1_label_class', '')">Buat Akun</div>
            </div>
        </div>
        <div class="step-line @yield('line1_class', '')"></div>
        <div class="step">
            <div class="step-inner">
                <div class="step-circle @yield('step2_class', '')">2</div>
                <div class="step-label @yield('step2_label_class', '')">Data Diri</div>
            </div>
        </div>
        <div class="step-line @yield('line2_class', '')"></div>
        <div class="step">
            <div class="step-inner">
                <div class="step-circle @yield('step3_class', '')">3</div>
                <div class="step-label @yield('step3_label_class', '')">Pembayaran</div>
            </div>
        </div>
        <div class="step-line @yield('line3_class', '')"></div>
        <div class="step">
            <div class="step-inner">
                <div class="step-circle @yield('step4_class', '')">✓</div>
                <div class="step-label @yield('step4_label_class', '')">Selesai</div>
            </div>
        </div>
    </div>
</div>

<div class="main">
    @yield('content')
</div>
</body>
</html>
