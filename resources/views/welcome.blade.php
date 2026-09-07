<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $masterData->nama_lembaga ?? config('app.name') }} - Platform Pendidikan & Manajemen Akademik</title>
    
    <!-- Meta Tags SEO -->
    <meta name="description" content="{{ $masterData->hero_subtitle ?? 'Platform bimbingan belajar dan sistem manajemen akademik modern terpadu.' }}">
    
    <!-- Favicon -->
    @if(isset($masterData) && $masterData->logo)
        <link rel="icon" type="image/png" href="{{ Storage::url($masterData->logo) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('images/nivora-icon.png') }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif; 
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        /* Fix for mobile horizontal scroll */
        html, body {
            max-width: 100%;
            overflow-x: hidden;
        }
        .wrapper {
            overflow-x: hidden;
            width: 100%;
        }
        /* Custom smooth scrollbar for sliders */
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #fed7aa;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #f97316;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-orange-100 selection:text-orange-900">

    <div class="wrapper">
        @include('landing.partials.navbar')
        @include('landing.partials.hero')
        @include('landing.partials.features')
        @include('landing.partials.programs')
        @include('landing.partials.about')
        @include('landing.partials.guru')
        @include('landing.partials.testimonials')
        @include('landing.partials.faq')
        @include('landing.partials.mitra')
        @include('landing.partials.footer')
        @include('landing.partials.widget')
        @include('landing.partials.modals')
    </div>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            AOS.init({
                once: true,
                duration: 750,
                offset: 40,
                easing: 'ease-out-cubic'
            });
            // Trigger refresh after fonts/images load
            window.addEventListener('load', () => AOS.refresh());
        });

        // Modal Logic
        function openModal(id) {
            const modal = document.getElementById(id);
            const content = document.getElementById(id + '-content');
            if(!modal || !content) return;
            modal.classList.remove('hidden');
            void modal.offsetWidth; // Trigger reflow
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            const content = document.getElementById(id + '-content');
            if(!modal || !content) return;
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 250);
        }
    </script>
    @include('components.loading-overlay')
</body>
</html>
