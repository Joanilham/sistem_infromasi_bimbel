<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Ruang Kerja - Genius Education</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>

<body class="min-h-screen bg-slate-50 flex items-center justify-center px-4">

    <div class="w-full max-w-sm">

        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-600 rounded-xl mb-4">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h1 class="text-xl font-bold text-slate-800">Pilih Ruang Kerja</h1>
            <p class="text-sm text-slate-500 mt-1">
                Halo, <span class="font-semibold text-slate-700">{{ explode(' ', Auth::user()->name)[0] }}</span>. Pilih kantor dan periode terlebih dahulu.
            </p>
        </div>

        {{-- Error --}}
        @if (session('error'))
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('session.konteks') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="redirect" value="/dashboard">

            <div>
                <label for="kantor_id" class="block text-sm font-medium text-slate-700 mb-1">Kantor Cabang</label>
                <select id="kantor_id" name="kantor_id" required
                    class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="" disabled selected>— Pilih Kantor —</option>
                    @foreach($kantors as $kantor)
                        <option value="{{ $kantor->id }}">{{ $kantor->nama_kantor }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="periode_id" class="block text-sm font-medium text-slate-700 mb-1">Tahun Ajaran / Periode</label>
                <select id="periode_id" name="periode_id" required
                    class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="" disabled selected>— Pilih Periode —</option>
                    @foreach($periodes as $periode)
                        <option value="{{ $periode->id }}">{{ $periode->tahun_periode }} - {{ ucfirst($periode->semester) }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                Masuk ke Dashboard
            </button>
        </form>

        {{-- Logout --}}
        <div class="mt-6 pt-5 border-t border-slate-200 flex items-center justify-between">
            <p class="text-xs text-slate-400">Bukan akun Anda?</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors">
                    Log Out
                </button>
            </form>
        </div>

    </div>

</body>
</html>
