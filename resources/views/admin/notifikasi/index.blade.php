@extends('layouts.admin')
@section('title', 'Pusat Notifikasi')

@section('content')
<div x-data="{ tab: 'siswa' }" class="mb-8">
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Pusat Notifikasi</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">Pemberitahuan terkait pendaftaran siswa dan verifikasi keuangan.</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="border-b border-slate-200 dark:border-zinc-700 mb-6">
        <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
            <button @click="tab = 'siswa'"
                :class="tab === 'siswa' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-zinc-400 dark:hover:text-zinc-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
                Pendaftaran Siswa
                @if($pendaftaranMenunggu->count() > 0)
                <span class="bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 py-0.5 px-2 rounded-full text-xs">{{ $pendaftaranMenunggu->count() }}</span>
                @endif
            </button>
            <button @click="tab = 'keuangan'"
                :class="tab === 'keuangan' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-zinc-400 dark:hover:text-zinc-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
                Verifikasi Keuangan
                @if($pembayaranBelumDikonfirmasi->count() > 0)
                <span class="bg-rose-100 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400 py-0.5 px-2 rounded-full text-xs">{{ $pembayaranBelumDikonfirmasi->count() }}</span>
                @endif
            </button>
        </nav>
    </div>

    <!-- Content: Pendaftaran Siswa -->
    <div x-show="tab === 'siswa'" x-transition.opacity class="bg-white dark:bg-zinc-800 shadow-sm rounded-xl border border-slate-200 dark:border-zinc-700 overflow-hidden">
        @if($pendaftaranMenunggu->count() === 0)
        <div class="p-8 text-center text-slate-500 dark:text-zinc-400">
            <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-zinc-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p>Tidak ada pendaftaran siswa baru yang menunggu verifikasi.</p>
        </div>
        @else
        <ul class="divide-y divide-slate-200 dark:divide-zinc-700">
            @foreach($pendaftaranMenunggu as $pendaftaran)
            <li class="p-4 hover:bg-slate-50 dark:hover:bg-zinc-700/50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-800 dark:text-zinc-200">Pendaftaran Baru: {{ $pendaftaran->nama_lengkap }}</p>
                            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Kantor: {{ $pendaftaran->kantor->nama_kantor ?? '-' }} | Paket: {{ $pendaftaran->paketBimbingan->nama_paket ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs text-slate-400">{{ $pendaftaran->created_at->diffForHumans() }}</span>
                        <a href="{{ route('admin.pendaftaran.show', $pendaftaran->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
        @endif
    </div>

    <!-- Content: Keuangan -->
    <div x-show="tab === 'keuangan'" x-cloak x-transition.opacity class="bg-white dark:bg-zinc-800 shadow-sm rounded-xl border border-slate-200 dark:border-zinc-700 overflow-hidden">
        @if($pembayaranBelumDikonfirmasi->count() === 0)
        <div class="p-8 text-center text-slate-500 dark:text-zinc-400">
            <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-zinc-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
            <p>Tidak ada pembayaran yang menunggu konfirmasi.</p>
        </div>
        @else
        <ul class="divide-y divide-slate-200 dark:divide-zinc-700">
            @foreach($pembayaranBelumDikonfirmasi as $pembayaran)
            <li class="p-4 hover:bg-slate-50 dark:hover:bg-zinc-700/50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-800 dark:text-zinc-200">Konfirmasi Pembayaran: {{ $pembayaran->pendaftaranSiswa->nama_lengkap ?? 'Tanpa Nama' }}</p>
                            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Metode: {{ $pembayaran->metode_pembayaran }} | Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs text-slate-400">{{ $pembayaran->created_at->diffForHumans() }}</span>
                        <a href="{{ route('admin.pendaftaran.show', $pembayaran->pendaftaran_siswa_id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Cek Pembayaran
                        </a>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
</div>
@endsection
