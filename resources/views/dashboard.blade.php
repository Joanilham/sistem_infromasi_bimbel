@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div x-data="{ 
    ...dashboardClock(),
    showListModal: false,
    modalTitle: '',
    modalType: '',
    openList(type, title) {
        this.modalType = type;
        this.modalTitle = title;
        this.showListModal = true;
    }
}">
    {{-- ─── Header ─── --}}
    <div class="relative mb-8 rounded-[1.75rem] overflow-hidden shadow-2xl">
        {{-- Animated gradient background --}}
        <div class="absolute inset-0 bg-gradient-to-br from-rose-600 via-orange-600 to-amber-500 animate-gradient-xy"></div>

        {{-- Decorative circles --}}
        <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full bg-white/5 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-16 w-80 h-80 rounded-full bg-blue-400/10 blur-3xl pointer-events-none"></div>
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>

        {{-- Dot grid overlay --}}
        <div class="absolute inset-0 opacity-[0.04]"
            style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;">
        </div>

        <div class="relative z-10 p-8 md:p-10 flex flex-col xl:flex-row xl:items-center justify-between gap-8">
            {{-- Greeting --}}
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-semibold tracking-widest text-indigo-100 uppercase">Sistem Aktif</span>
                </div>

                <h1 class="text-3xl md:text-5xl font-extrabold text-white leading-tight mb-3 tracking-tight">
                    Selamat Datang,<br>
                    <span class="">
                        {{ auth()->user()->name ?? 'Administrator' }}! 👋
                    </span>
                </h1>
            </div>

            {{-- Real-Time Clock --}}
            <div class="shrink-0 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/15 p-6 lg:p-8 min-w-[260px] shadow-[inset_0_1px_1px_rgba(255,255,255,0.12)] hover:bg-white/15 transition-colors duration-300 text-center text-white">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-white-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-xs font-semibold uppercase tracking-widest" x-text="date">Memuat...</span>
                </div>
                <div class="text-5xl lg:text-6xl font-black tabular-nums tracking-tight drop-shadow" x-text="time">00:00:00</div>
                <div class="mt-3 text-xs font-medium text-white/80 bg-white/5 rounded-lg py-1.5 px-3" x-text="zonaWaktu">Waktu Lokal</div>
            </div>
        </div>
    </div>

    {{-- ─── Stat Cards ─── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-10">
        {{-- Card: Peserta Didik --}}
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-transparent dark:from-indigo-950/40 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-5">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Peserta Didik</span>
                    <div class="p-2.5 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-black text-slate-800 dark:text-white mb-2">{{ $totalPesertaAktif }}</p>
                <div class="flex items-center gap-3 mt-1">
                    <button type="button" @click="openList('baru', 'Peserta Baru (7 Hari Terakhir)')" 
                        class="text-xs font-semibold px-2 py-1 rounded-md flex items-center gap-1 transition-all duration-200 hover:scale-105 hover:shadow-md cursor-pointer {{ ($listPesertaBaru ?? collect())->count() > 0 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20' : 'bg-slate-50 text-slate-400 dark:bg-slate-800 dark:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        {{ ($listPesertaBaru ?? collect())->count() }} Masuk
                    </button>
                    <button type="button" @click="openList('keluar', 'Peserta Keluar')" 
                        class="text-xs font-semibold px-2 py-1 rounded-md flex items-center gap-1 transition-all duration-200 hover:scale-105 hover:shadow-md cursor-pointer {{ ($listPesertaKeluar ?? collect())->count() > 0 ? 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-500/20' : 'bg-slate-50 text-slate-400 dark:bg-slate-800 dark:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                        {{ ($listPesertaKeluar ?? collect())->count() }} Keluar
                    </button>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-indigo-400 to-violet-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500 rounded-b-2xl"></div>
        </div>

        {{-- Card: Tenaga Pengajar --}}
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-50 to-transparent dark:from-amber-950/40 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-5">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Tenaga Pengajar</span>
                    <div class="p-2.5 rounded-xl bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-black text-slate-800 dark:text-white mb-2">{{ $totalTenagaPengajar }}</p>
                <p class="text-sm font-medium text-slate-400 flex items-center gap-1">
                    @if($totalTenagaPengajar > 0)
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Semua aktif bertugas
                    @else
                    Belum ada data pengajar
                    @endif
                </p>
            </div>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-amber-400 to-orange-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500 rounded-b-2xl"></div>
        </div>

        {{-- Card: Paket Aktif --}}
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-transparent dark:from-emerald-950/40 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-5">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Paket Aktif</span>
                    <div class="p-2.5 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-black text-slate-800 dark:text-white mb-2">{{ $totalPaketAktif }}</p>
                <p class="text-sm font-medium text-slate-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Program bimbingan tersinkron
                </p>
            </div>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-emerald-400 to-teal-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500 rounded-b-2xl"></div>
        </div>

        {{-- Card: Pemasukan --}}
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-rose-50 to-transparent dark:from-rose-950/40 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-5">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Pemasukan Bulan Ini</span>
                    <div class="p-2.5 rounded-xl bg-rose-100 dark:bg-rose-900/50 text-rose-600 dark:text-rose-400 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-800 dark:text-white mb-2">Rp 0</p>
                <p class="text-sm font-medium text-slate-400">Belum ada transaksi</p>
            </div>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-rose-400 to-pink-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500 rounded-b-2xl"></div>
        </div>
    </div>

    {{-- ─── Akses Cepat ─── --}}
    <div class="mb-3 flex items-end justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 dark:text-white tracking-tight">Akses Cepat</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Navigasi ke fitur utama sistem</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Pengguna --}}
        <a href="{{ route('pengguna.index') }}"
            class="group relative flex flex-col gap-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-6 hover:border-blue-400 dark:hover:border-blue-600 hover:shadow-xl hover:shadow-blue-500/10 transition-all duration-300 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/80 to-transparent dark:from-blue-950/40 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10 p-3 w-fit rounded-xl bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="relative z-10">
                <h3 class="font-bold text-slate-800 dark:text-white text-base mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Pengaturan Pengguna</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 leading-snug">Kelola role administrator &amp; staff sistem.</p>
            </div>
        </a>

        {{-- Data Instansi --}}
        <a href="{{ route('master.index') }}"
            class="group relative flex flex-col gap-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-6 hover:border-violet-400 dark:hover:border-violet-600 hover:shadow-xl hover:shadow-violet-500/10 transition-all duration-300 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-50/80 to-transparent dark:from-violet-950/40 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10 p-3 w-fit rounded-xl bg-violet-100 dark:bg-violet-900/50 text-violet-600 dark:text-violet-400 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
            </div>
            <div class="relative z-10">
                <h3 class="font-bold text-slate-800 dark:text-white text-base mb-1 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">Data Instansi</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 leading-snug">Profil, logo instansi &amp; integrasi Gateway.</p>
            </div>
        </a>

        {{-- Paket Bimbingan --}}
        <a href="{{ route('paket-bimbingan.index') }}"
            class="group relative flex flex-col gap-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-6 hover:border-emerald-400 dark:hover:border-emerald-600 hover:shadow-xl hover:shadow-emerald-500/10 transition-all duration-300 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/80 to-transparent dark:from-emerald-950/40 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10 p-3 w-fit rounded-xl bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
            </div>
            <div class="relative z-10">
                <h3 class="font-bold text-slate-800 dark:text-white text-base mb-1 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Paket Bimbingan</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 leading-snug">Jenis paket kursus, harga &amp; layanan tersedia.</p>
            </div>
        </a>

        {{-- Peserta Didik --}}
        <a href="{{ route('peserta-didik.index') }}"
            class="group relative flex flex-col gap-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-6 hover:border-rose-400 dark:hover:border-rose-600 hover:shadow-xl hover:shadow-rose-500/10 transition-all duration-300 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-rose-50/80 to-transparent dark:from-rose-950/40 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10 p-3 w-fit rounded-xl bg-rose-100 dark:bg-rose-900/50 text-rose-600 dark:text-rose-400 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                </svg>
            </div>
            <div class="relative z-10">
                <h3 class="font-bold text-slate-800 dark:text-white text-base mb-1 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">Peserta Didik</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 leading-snug">Database murid, biodata &amp; pendaftaran siswa.</p>
            </div>
        </a>
    </div>

    {{-- ─── Modal Detail Peserta ─── --}}
    <div x-show="showListModal" 
         class="fixed inset-0 z-[100] overflow-y-auto" 
         x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div x-show="showListModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" 
                 @click="showListModal = false"></div>

            <div x-show="showListModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-800">
                
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white" x-text="modalTitle"></h3>
                    <button @click="showListModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="max-h-[60vh] overflow-y-auto p-6 custom-scrollbar">
                    {{-- List Peserta Baru --}}
                    <div x-show="modalType === 'baru'">
                        @if(($listPesertaBaru ?? collect())->count() > 0)
                            <div class="space-y-4">
                                @foreach(($listPesertaBaru ?? collect()) as $p)
                                    <div class="flex items-center gap-4 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 hover:border-emerald-200 dark:hover:border-emerald-800 transition-colors">
                                        <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold">
                                            {{ substr($p->nama_lengkap, 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-slate-800 dark:text-slate-200 truncate">{{ $p->nama_lengkap }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $p->asal_sekolah }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $p->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-slate-500 dark:text-slate-400">Tidak ada peserta baru</p>
                            </div>
                        @endif
                    </div>

                    {{-- List Peserta Keluar --}}
                    <div x-show="modalType === 'keluar'">
                        @if(($listPesertaKeluar ?? collect())->count() > 0)
                            <div class="space-y-4">
                                @foreach(($listPesertaKeluar ?? collect()) as $p)
                                    <div class="flex items-center gap-4 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 hover:border-rose-200 dark:hover:border-rose-800 transition-colors">
                                        <div class="w-10 h-10 rounded-full bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 flex items-center justify-center font-bold">
                                            {{ substr($p->nama_lengkap, 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-slate-800 dark:text-slate-200 truncate">{{ $p->nama_lengkap }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $p->asal_sekolah }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-tighter">{{ $p->tanggal_keluar }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-slate-500 dark:text-slate-400">Tidak ada peserta keluar</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 text-right">
                    <button @click="showListModal = false" class="px-5 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors shadow-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        window.dashboardClock = () => ({
            time: '00:00:00',
            date: 'Memuat...',
            zonaWaktu: 'WIB',

            init() {
                this.updateClock();
                setInterval(() => this.updateClock(), 1000);
            },

            updateClock() {
                const now = new Date();

                this.time = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                }).replace(/\./g, ':');

                this.date = now.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                const offset = -now.getTimezoneOffset() / 60;
                if (offset === 7) this.zonaWaktu = 'Waktu Indonesia Barat (WIB)';
                else if (offset === 8) this.zonaWaktu = 'Waktu Indonesia Tengah (WITA)';
                else if (offset === 9) this.zonaWaktu = 'Waktu Indonesia Timur (WIT)';
                else this.zonaWaktu = 'Waktu Lokal: GMT' + (offset > 0 ? '+' : '') + offset;
            }
        });
    });
</script>
@endsection