@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<!-- Welcome & Real-Time Clock Banner -->
<div class="mb-8 bg-gradient-to-br from-indigo-700 via-blue-800 to-indigo-900 rounded-[1.5rem] p-8 md:p-10 text-white shadow-2xl shadow-indigo-900/30 overflow-hidden relative" x-data="dashboardClock">
    <!-- Decorative Elements -->
    <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-16 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none"></div>

    <div class="relative z-10 flex flex-col xl:flex-row xl:items-center justify-between gap-8">
        <div class="flex-1">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 mb-4">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-xs font-semibold tracking-wide text-indigo-50 uppercase">Online & Aktif</span>
            </div>
            <h2 class="text-4xl md:text-5xl font-extrabold pb-2 mb-3 leading-tight tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-white to-indigo-100 italic">
                Selamat Datang, {{ auth()->user()->name ?? 'Administrator' }}! <span class="not-italic inline-block text-white">👋</span>
            </h2>
            <p class="text-indigo-100/90 text-lg md:text-xl font-medium max-w-2xl leading-relaxed">
                Kelola seluruh aktivitas akademik, administrasi bimbingan, dan evaluasi hasil belajar dalam satu ekosistem pintar.
            </p>
        </div>

        <!-- Real-Time Clock -->
        <div class="bg-black/25 backdrop-blur-xl rounded-[1.25rem] p-6 lg:p-8 min-w-[280px] border border-white/10 shadow-[inset_0_1px_1px_rgba(255,255,255,0.1)] group hover:bg-black/30 transition-all duration-300">
            <div class="flex items-center justify-center gap-3 mb-2">
                <svg class="w-5 h-5 text-indigo-300 animate-[spin_4s_linear_infinite]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm font-semibold text-indigo-200 uppercase tracking-widest" x-text="date">Memuat tanggal...</div>
            </div>
            <div class="text-5xl lg:text-6xl font-sans font-black tracking-tight text-center text-white drop-shadow-md tabular-nums" x-text="time">00:00:00</div>
            <div class="mt-3 text-center text-xs font-medium text-indigo-300/80 bg-white/5 rounded-lg py-1.5" x-text="zonaWaktu">Waktu Lokal</div>
        </div>
    </div>
</div>

<!-- Key Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Stat 1 -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[1.5rem] p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Peserta Didik</p>
                <h3 class="text-4xl font-black text-slate-800 dark:text-white">{{ $totalPesertaAktif }}</h3>
            </div>
            <div class="p-3.5 rounded-2xl bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
        <div class="flex items-center text-sm font-medium {{ $pesertaBaru7Hari > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400' }}">
            @if($pesertaBaru7Hari > 0)
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
            <span>+{{ $pesertaBaru7Hari }} peserta baru minggu ini</span>
            @else
            <span>Tidak ada penambahan minggu ini</span>
            @endif
        </div>
    </div>

    <!-- Stat 2 -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[1.5rem] p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Tenaga Pengajar</p>
                <h3 class="text-4xl font-black text-slate-800 dark:text-white">24</h3>
            </div>
            <div class="p-3.5 rounded-2xl bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
        <div class="flex items-center text-sm font-medium text-slate-500 dark:text-slate-400">
            <span>Stabil</span>
        </div>
    </div>

    <!-- Stat 3 -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[1.5rem] p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Paket Aktif</p>
                <h3 class="text-4xl font-black text-slate-800 dark:text-white">{{ $totalPaketAktif }}</h3>
            </div>
            <div class="p-3.5 rounded-2xl bg-rose-50 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
        </div>
        <div class="flex items-center text-sm font-medium text-slate-500 dark:text-slate-400">
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Program Bimbingan tersinkron</span>
        </div>
    </div>

    <!-- Stat 4 -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[1.5rem] p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Pemasukan Bulan Ini</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white mt-1">Rp 0</h3>
            </div>
            <div class="p-3.5 rounded-2xl bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="flex items-center text-sm font-medium text-slate-500 dark:text-slate-400">
            <span>Belum ada transaksi</span>
        </div>
    </div>
</div>

<!-- Main Application Features (Menu Akses Cepat) -->
<div class="mb-4 flex items-center justify-between mt-12">
    <div>
        <h3 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Menu Utama Pusat</h3>
        <p class="text-base text-slate-500 dark:text-slate-400 mt-1">Akses cepat ke seluruh fitur yang tersedia dalam sistem.</p>
    </div>
</div>

<!-- SPRINT 1 : Active Features -->
<div class="mb-10">
    <div class="flex items-center gap-3 mb-6">
        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/60 dark:text-blue-400 font-bold text-sm">1</span>
        <h4 class="text-lg font-bold text-slate-700 dark:text-slate-200">Fase Administrasi & Pengaturan (Berjalan)</h4>
        <div class="h-px flex-1 bg-slate-200 dark:bg-slate-800 ml-4"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">

        <!-- Setup Pengguna -->
        <a href="{{ route('pengguna.index') }}" class="relative block bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 group hover:border-blue-500 hover:shadow-lg hover:shadow-blue-500/10 transition-all duration-300 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent dark:from-blue-900/20 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10 flex items-start gap-4">
                <div class="p-3 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 rounded-xl group-hover:scale-110 group-hover:rotate-3 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h5 class="font-bold text-slate-800 dark:text-white text-lg mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Pengaturan Pengguna</h5>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-snug">Kelola data seluruh role mulai dari administrator hingga staff.</p>
                </div>
            </div>
            <div class="absolute bottom-4 right-4 text-blue-500 opacity-0 group-hover:opacity-100 transform translate-x-4 group-hover:translate-x-0 transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </div>
        </a>

        <!-- Master Data / Pengaturan Instansi -->
        <a href="{{ route('master.index') }}" class="relative block bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 group hover:border-indigo-500 hover:shadow-lg hover:shadow-indigo-500/10 transition-all duration-300 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10 flex items-start gap-4">
                <div class="p-3 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 rounded-xl group-hover:scale-110 group-hover:rotate-3 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <div>
                    <h5 class="font-bold text-slate-800 dark:text-white text-lg mb-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Data Instansi</h5>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-snug">Atur profil, logo instansi, dan integrasi WhatsApp Gateway.</p>
                </div>
            </div>
        </a>

        <!-- Paket Bimbingan (Active) -->
        <a href="{{ route('paket-bimbingan.index') }}" class="relative block bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 group hover:border-emerald-500 hover:shadow-lg hover:shadow-emerald-500/10 transition-all duration-300 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-transparent dark:from-emerald-900/20 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10 flex items-start gap-4">
                <div class="p-3 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 rounded-xl group-hover:scale-110 group-hover:-rotate-3 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <div>
                    <h5 class="font-bold text-slate-800 dark:text-white text-lg mb-1 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Paket Bimbingan</h5>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-snug">Manajemen jenis paket kursus, harga & layanan.</p>
                </div>
            </div>
        </a>

        <!-- Manajemen Peserta Didik (Blueprint ready) -->
        <a href="{{ route('peserta-didik.index') }}" class="relative block bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 group hover:border-rose-500 hover:shadow-lg hover:shadow-rose-500/10 transition-all duration-300 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-rose-50 to-transparent dark:from-rose-900/20 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10 flex items-start gap-4">
                <div class="p-3 bg-rose-100 dark:bg-rose-900/50 text-rose-600 dark:text-rose-400 rounded-xl group-hover:scale-110 group-hover:rotate-3 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                </div>
                <div>
                    <h5 class="font-bold text-slate-800 dark:text-white text-lg mb-1 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">Peserta Didik</h5>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-snug">Database murid, biodata lengkap, dan pendaftaran.</p>
                </div>
            </div>
            <div class="absolute top-4 right-4 text-rose-500 opacity-0 group-hover:opacity-100 transform translate-x-4 group-hover:translate-x-0 transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </div>
        </a>

    </div>
</div>

<!-- UPCOMING Sprints -->
<div class="mb-10">
    <div class="flex items-center gap-3 mb-6">
        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-slate-200 text-slate-600 dark:bg-slate-800 dark:text-slate-400 font-bold text-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </span>
        <h4 class="text-lg font-bold text-slate-700 dark:text-slate-200">Modul Mendatang (Tahap Pengembangan)</h4>
        <div class="h-px flex-1 bg-slate-200 dark:bg-slate-800 ml-4 border-dashed border-t-2 border-slate-300 dark:border-slate-700"></div>
    </div>

    <!-- SPRINT 2 -->
    <h5 class="text-sm font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-4 flex items-center gap-2">
        <span>Tahap 2</span> <span class="bg-emerald-100 dark:bg-emerald-900/50 px-2 py-0.5 rounded text-xs">Akan datang</span>
    </h5>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        @foreach(['Jurnal Mengajar', 'Manajemen Pembayaran', 'Manajemen Tagihan', 'Data Kelompok Belajar', 'Jadwal Bimbel'] as $feature)
        <div class="bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/80 rounded-xl p-4 opacity-80 cursor-not-allowed filter grayscale hover:grayscale-0 transition-all duration-300 group">
            <div class="w-8 h-8 rounded-lg bg-slate-200 dark:bg-slate-800 mb-3 flex items-center justify-center text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h6 class="font-semibold text-sm text-slate-700 dark:text-slate-300 mb-1 leading-tight group-hover:text-emerald-600 transition-colors">{{ $feature }}</h6>
        </div>
        @endforeach
    </div>

    <!-- SPRINT 3 & 4 combined display -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <h5 class="text-sm font-bold text-amber-500 dark:text-amber-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                <span>Tahap 3</span>
            </h5>
            <div class="space-y-3">
                @foreach(['Sistem Absensi', 'Rekap Laporan Data', 'Integrasi QR Code', 'Manajemen Soal & Ujian'] as $feature)
                <div class="flex items-center gap-3 p-3 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/20 opacity-70">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ $feature }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div>
            <h5 class="text-sm font-bold text-orange-500 dark:text-orange-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                <span>Tahap 4 (Evaluasi)</span>
            </h5>
            <div class="space-y-3">
                @foreach(['Portal Daftar Ujian / CBT Tersedia', 'Sistem Pelaksanaan Ujian', 'Riwayat Nilai & Pembahasan Soal', 'Otomatisasi Rekapitulasi Nilai'] as $feature)
                <div class="flex items-center gap-3 p-3 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/20 opacity-70">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ $feature }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dashboardClock', () => ({
            time: '00:00:00',
            date: 'Memuat...',
            zonaWaktu: 'WIB',

            init() {
                this.updateClock();
                setInterval(() => this.updateClock(), 1000);
            },

            updateClock() {
                const now = new Date();

                // Jam digital font-mono
                this.time = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                }).replace(/\./g, ':');

                // Tanggal berbahasa indonesia
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                this.date = now.toLocaleDateString('id-ID', options);

                // Auto detect zona waktu untuk display
                const offset = -now.getTimezoneOffset() / 60;
                if (offset === 7) this.zonaWaktu = 'Waktu Indonesia Barat (WIB)';
                else if (offset === 8) this.zonaWaktu = 'Waktu Indonesia Tengah (WITA)';
                else if (offset === 9) this.zonaWaktu = 'Waktu Indonesia Timur (WIT)';
                else this.zonaWaktu = 'Waktu Lokal: GMT' + (offset > 0 ? '+' : '') + offset;
            }
        }));
    });
</script>
@endsection