<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $masterData->nama_lembaga ?? 'NIVORA' }} — Bimbingan Belajar & Manajemen Pendidikan Terpadu</title>
    
    <!-- Meta Tags SEO -->
    <meta name="description" content="{{ $masterData->hero_subtitle ?? 'Platform bimbingan belajar dan sistem manajemen akademik modern terpadu untuk mendampingi prestasi siswa.' }}">
    
    <!-- Favicon -->
    @if(isset($masterData) && $masterData->logo)
        <link rel="icon" type="image/png" href="{{ Storage::url($masterData->logo) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('images/nivora-icon.png') }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@1,6..72,400;1,6..72,500;1,6..72,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="{{ asset('vendor/alpinejs/alpine.min.js') }}"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        html, body { 
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif; 
            background-color: #FAF8F5;
            color: #141413;
            max-width: 100%;
            overflow-x: hidden;
        }
        button, input, select, textarea {
            font-family: inherit;
        }
        .font-editorial {
            font-family: 'Newsreader', Georgia, serif;
        }
        .glass-nav {
            background: rgba(250, 248, 245, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
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
            background: #EFECE6;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #D5CEC4;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #E14D2A;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
</head>
<body class="bg-[#FAF8F5] text-[#141413] antialiased selection:bg-[#FDEEE9] selection:text-[#E14D2A]">

    <div class="wrapper">
        @include('landing.partials.navbar')
        @include('landing.partials.hero')
        @include('landing.partials.mitra')
        @include('landing.partials.programs')
        @include('landing.partials.features')
        @include('landing.partials.ecosystem')
        @include('landing.partials.about')
        @include('landing.partials.guru')
        @include('landing.partials.testimonials')
        @include('landing.partials.faq')
        @include('landing.partials.cta')
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
                duration: 650,
                offset: 30,
                easing: 'ease-out-cubic'
            });
            window.addEventListener('load', () => AOS.refresh());
        });

        // Modal Logic
        function openModal(id) {
            const modal = document.getElementById(id);
            const content = document.getElementById(id + '-content');
            if(!modal || !content) return;
            modal.classList.remove('hidden');
            void modal.offsetWidth;
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
