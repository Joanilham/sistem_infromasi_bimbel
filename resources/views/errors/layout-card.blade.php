<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('page_title', 'Terjadi Kesalahan') — {{ config('app.name', 'Sistem') }}</title>
    @if(isset($masterData) && $masterData->logo)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $masterData->logo) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
    <link rel="stylesheet" href="{{ asset('css/error-card.css') }}">
    {{-- Dynamic theme colors injected as CSS custom properties --}}
    <style>
        :root {
            --code-color: @yield('code-color', '#DC2626');
            --bg-badge: @yield('badge-bg', '#FEF2F2');
            --text-badge: @yield('badge-text', '#991B1B');
            --badge-dot-color: @yield('badge-dot', '#DC2626');
        }
    </style>
    @yield('extra-styles')
</head>
<body>
    {{-- Background Effects --}}
    <div class="bg-pattern"></div>
    <canvas id="interactive-bg" class="bg-canvas" data-theme-color="@yield('code-color', '#3B82F6')"></canvas>
    <div class="bg-glow bg-glow-1"></div>
    <div class="bg-glow bg-glow-2"></div>

    {{-- Error Card --}}
    <div class="error-card">
        {{-- Pixel Cat Pet --}}
        <div class="pet-floor">
            <canvas id="pet-canvas"></canvas>
        </div>

        {{-- Icon or Image --}}
        @hasSection('image')
            <div class="image-container">
                @yield('image')
            </div>
        @else
            <div class="error-icon">
                @yield('icon')
            </div>
        @endif

        {{-- Badge --}}
        <div class="badge">
            <span class="badge-dot"></span>
            @yield('badge')
        </div>

        {{-- Title & Message --}}
        <h1 class="error-title">@yield('title')</h1>
        <p class="error-desc">
            @yield('message')
        </p>

        {{-- Action Buttons --}}
        <div class="btn-group">
            @yield('actions')
        </div>

        {{-- Details Box --}}
        @hasSection('details')
        <div class="details-box">
            @yield('details')
        </div>
        @endif
    </div>

    @include('components.loading-overlay')

    {{-- Scripts --}}
    <script src="{{ asset('js/error-particles.js') }}"></script>
    <script src="{{ asset('js/error-pet.js') }}"></script>
</body>
</html>
