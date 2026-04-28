<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Terjadi Kesalahan') — GeniusEdu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --accent: @yield('accent-color', '#4F46E5');
            --accent2: @yield('accent-color2', '#06B6D4');
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0F172A;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(79, 70, 229, 0.15) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(6, 182, 212, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .container {
            text-align: center;
            padding: 40px 24px;
            max-width: 520px;
            position: relative;
            z-index: 1;
        }

        .icon-wrap {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #1E293B, #334155);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            animation: float 3s ease-in-out infinite;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-10px); }
        }

        .icon-wrap svg { width: 36px; height: 36px; }

        .error-code {
            font-size: clamp(80px, 20vw, 140px);
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, @yield('gradient', '#4F46E5, #06B6D4, #8B5CF6'));
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 4s ease infinite;
            margin-bottom: 8px;
            letter-spacing: -4px;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50%       { background-position: 100% 50%; }
        }

        .title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #F1F5F9;
            margin-bottom: 12px;
        }

        .subtitle {
            font-size: 0.95rem;
            color: #64748B;
            line-height: 1.6;
            margin-bottom: 36px;
        }

        .btn-group { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(135deg, @yield('btn-gradient', '#4F46E5, #6366F1'));
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 700;
            transition: all 0.2s;
            box-shadow: 0 4px 14px @yield('btn-shadow', 'rgba(79,70,229,0.4)');
        }

        .btn-primary:hover { transform: translateY(-2px); }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: rgba(255,255,255,0.05);
            color: #94A3B8;
            text-decoration: none;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            border: 1px solid rgba(255,255,255,0.08);
            transition: all 0.2s;
        }

        .btn-secondary:hover { background: rgba(255,255,255,0.08); color: #CBD5E1; }

        .brand {
            margin-top: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .brand-icon {
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #4F46E5, #06B6D4);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-icon svg { width: 16px; height: 16px; }
        .brand-name { font-size: 0.9rem; font-weight: 800; color: #475569; }
        .brand-name span { color: #4F46E5; }
    </style>
</head>
<body>
<div class="container">
    <div class="icon-wrap">
        @yield('icon')
    </div>

    <div class="error-code">@yield('code')</div>
    <div class="title">@yield('title')</div>
    <p class="subtitle">@yield('message')</p>

    <div class="btn-group">
        @yield('actions')
    </div>

    <a href="{{ url('/') }}" class="brand">
        <div class="brand-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <div class="brand-name">Genius<span>Edu</span></div>
    </a>
</div>
</body>
</html>
