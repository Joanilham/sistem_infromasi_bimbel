<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pendaftaran Siswa') - Sistem Akademik</title>
    @if(isset($masterData) && $masterData->logo)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $masterData->logo) }}">
    @endif
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- TomSelect CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* TomSelect Styles */
        .ts-control { border-radius: 0.75rem !important; border: 1.5px solid #E2E8F0 !important; padding: 0.75rem 1rem !important; font-size: 0.95rem !important; box-shadow: none !important; background: #FAFBFC !important; }
        .ts-control.focus { border-color: #4F46E5 !important; box-shadow: 0 0 0 4px rgba(79,70,229,0.1) !important; background: white !important; }
        .ts-dropdown { border-radius: 0.75rem !important; border: 1px solid #e2e8f0 !important; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important; font-size: 0.95rem !important; }
        .ts-dropdown .active { background-color: #EEF2FF !important; color: #4F46E5 !important; }

        [x-cloak] { display: none !important; }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #EEF2FF 0%, #F0FDFF 50%, #ECFDF5 100%);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }
        
        /* Modern Select Reset */
        select.form-control {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748B'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.2em;
            padding-right: 2.5rem !important;
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
            border: 1.5px solid #E2E8F0; border-radius: 12px; padding: 12px 16px;
            font-size: 0.95rem; font-family: inherit; color: #0F172A;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); outline: none;
            background: #FAFBFC; width: 100%;
        }
        .form-control:focus { border-color: #4F46E5; box-shadow: 0 0 0 4px rgba(79,70,229,0.1); background: white; transform: translateY(-1px); }
        select.form-control { cursor: pointer; }
        textarea.form-control { resize: vertical; min-height: 90px; }
        .invalid-feedback { font-size: 0.78rem; color: #EF4444; margin-top: 2px; }
        .alert-danger {
            background: #FEF2F2; border: 1px solid #FECACA; border-radius: 12px;
            padding: 14px 16px; margin-bottom: 24px; font-size: 0.85rem; color: #DC2626;
        }
        .alert-danger ul { margin: 8px 0 0 16px; }
        .btn-row { display: flex; justify-content: flex-end; gap: 12px; margin-top: 28px; padding-top: 20px; border-top: 1px solid #F1F5F9; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; border-radius: 12px; font-weight: 700; font-size: 0.95rem; cursor: pointer; border: none; text-decoration: none; transition: all 0.3s; }
        .btn-primary { background: linear-gradient(135deg, #4F46E5, #3730A3); color: white; box-shadow: 0 8px 20px rgba(79,70,229,0.3); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(79,70,229,0.45); }
        .btn-secondary { background: white; color: #64748B; border: 2px solid #E2E8F0; }
        .btn-secondary:hover { border-color: #4F46E5; color: #4F46E5; background: #F8FAFC; }
        @media (max-width: 600px) {
            .form-grid { grid-template-columns: 1fr; }
            .card { padding: 22px 16px; }
            
            /* Responsive Header Fix */
            .top-bar {
                padding: 12px 16px;
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
            .logo {
                flex-shrink: 0;
            }
            .logo-icon { width: 28px; height: 28px; border-radius: 8px; }
            .logo-icon svg { width: 16px; height: 16px; }
            .logo-text { font-size: 0.9rem; }
            
            .top-bar-right {
                font-size: 0.75rem;
                text-align: right;
                max-width: 150px;
                line-height: 1.4;
            }
            .stepper-wrap { padding: 16px 10px; }
            .step-circle { width: 30px; height: 30px; font-size: 0.8rem; }
            .step-label { font-size: 0.65rem; }

            /* Responsive Buttons */
            .btn-row {
                flex-direction: row;
                gap: 8px;
                justify-content: space-between;
            }
            .btn {
                width: auto;
                flex: 1;
                padding: 12px 10px;
                font-size: 0.85rem;
                justify-content: center;
            }
        }
    </style>
    @yield('extra_style')
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
</head>
<body>
<div class="top-bar">
    <a href="{{ route('welcome') }}" class="logo">
        @if(isset($masterData) && $masterData->logo)
            <img src="{{ Storage::url($masterData->logo) }}" alt="Logo" style="height: 40px; width: auto; object-fit: contain;">
        @else
            <div class="logo-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <span class="logo-text">{{ $masterData->nama_lembaga ?? 'Sistem Akademik' }}</span>
        @endif
    </a>
    <div class="top-bar-right">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></div>
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

<!-- TomSelect JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('select').forEach((el) => {
            if (el.classList.contains('no-tomselect')) return;
            let ts = new TomSelect(el, {
                create: false,
                sortField: null,
            });
            // Hapus class form-control dari wrapper agar border dan padding tidak menjadi double/ganda
            ts.wrapper.classList.remove('form-control');
        });
    });
</script>

@include('components.autosave-script')
    @include('components.loading-overlay')
</body>
</html>
