<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Ujian CBT</title>
    @if(isset($masterData) && $masterData->logo)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $masterData->logo) }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
</head>
<body class="bg-gray-100 min-h-screen text-gray-800">
    <nav class="bg-blue-600 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between">
            <h1 class="text-xl font-bold">CBT System</h1>
            <div class="flex gap-4">
                <a href="{{ route('ujian.index') }}" class="hover:underline">Daftar Ujian</a>
                <a href="{{ route('hasil.index') }}" class="hover:underline">Hasil</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hover:underline">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    <main class="container mx-auto mt-6 p-4">
        @if(session('error'))
            <div class="bg-red-500 text-white p-3 rounded mb-4">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="bg-green-500 text-white p-3 rounded mb-4">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
    @include('components.autosave-script')
    @include('components.loading-overlay')
</body>
</html>