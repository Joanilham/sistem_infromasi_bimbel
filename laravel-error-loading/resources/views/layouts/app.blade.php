<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', config('app.name'))</title>

  {{-- CSS --}}
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('css/loading.css') }}">

  @stack('styles')
</head>
<body>

  {{-- === TOP PROGRESS BAR === --}}
  <div id="top-progress"></div>

  {{-- === PAGE LOADING OVERLAY === --}}
  <div id="page-loader" role="status" aria-label="Memuat halaman">
    <div class="loader-spinner">
      <div class="ring ring-1"></div>
      <div class="ring ring-2"></div>
      <div class="ring ring-3"></div>
    </div>

    <div class="loader-bar-wrap">
      <div class="loader-bar"></div>
    </div>

    <p class="loader-text">
      Memuat halaman<span class="loader-dots">
        <span>.</span><span>.</span><span>.</span>
      </span>
    </p>
  </div>

  {{-- === BANNER OFFLINE === --}}
  <div id="offline-banner" style="
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 9990;
    background: #E24B4A;
    color: #fff;
    padding: 10px 20px;
    font-size: 13px;
    font-weight: 500;
    align-items: center;
    justify-content: center;
    gap: 8px;
  ">
    <span>📡</span>
    <span>Anda sedang offline. Periksa koneksi internet Anda.</span>
  </div>

  {{-- === TOAST CONTAINER === --}}
  <div id="toast-container" role="region" aria-live="polite" aria-label="Notifikasi"></div>

  {{-- === KONTEN UTAMA === --}}
  <main>
    @yield('content')
  </main>

  {{-- Flash Messages (otomatis jadi toast) --}}
  @if (session('success'))
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        App.Toast.success('Berhasil', @json(session('success')));
      });
    </script>
  @endif

  @if (session('error'))
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        App.Toast.error('Gagal', @json(session('error')));
      });
    </script>
  @endif

  @if (session('warning'))
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        App.Toast.warning('Perhatian', @json(session('warning')));
      });
    </script>
  @endif

  @if (session('info'))
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        App.Toast.info('Info', @json(session('info')));
      });
    </script>
  @endif

  {{-- JS --}}
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="{{ asset('js/app-loader.js') }}"></script>

  @stack('scripts')
</body>
</html>
