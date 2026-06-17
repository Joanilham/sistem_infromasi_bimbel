<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $masterData->nama_lembaga ?? 'Genius Education' }} - Platform Manajemen Pendidikan</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ isset($masterData) && $masterData->logo ? Storage::url($masterData->logo) : asset('favicon.png') }}">
    
    <!-- Meta Tags SEO -->
    <meta name="description" content="{{ $masterData->hero_subtitle ?? 'Sistem informasi manajemen pendidikan terpadu untuk bimbingan belajar modern.' }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        /* Fix for mobile horizontal scroll */
        html, body {
            max-width: 100%;
            overflow-x: hidden;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

    
    @include('landing.partials.navbar')
    @include('landing.partials.hero')
    @include('landing.partials.features')
    @include('landing.partials.programs')
    @include('landing.partials.guru')
    @include('landing.partials.about')
    @include('landing.partials.mitra')
    @include('landing.partials.footer')
    @include('landing.partials.widget')
    @include('landing.partials.modals')

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            duration: 800,
            offset: 50,
        });

        // Modal Logic
        function openModal(id) {
            const modal = document.getElementById(id);
            const content = document.getElementById(id + '-content');
            modal.classList.remove('hidden');
            // Trigger reflow
            void modal.offsetWidth;
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            const content = document.getElementById(id + '-content');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }
    </script>
    @include('components.loading-overlay')
</body>
</html>
