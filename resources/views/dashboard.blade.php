@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

@php
    $activeKantorName = \App\Models\Kantor::where('id', session('kantor_id'))->value('nama_kantor') ?? 'Belum Dipilih';
    $activePeriodeYear = \App\Models\Periode::where('id', session('periode_id'))->value('tahun_periode') ?? 'Belum Dipilih';
@endphp

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

                {{-- Sleek Active Context Info Capsule --}}
                <div class="flex flex-wrap items-center gap-3 mt-6">
                    <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 shadow-sm text-white">
                        <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-white/10 text-white shrink-0">
                            <svg class="h-4.5 w-4.5 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </span>
                        <div class="flex flex-col text-left pr-2">
                            <span class="text-[9px] uppercase tracking-widest text-indigo-100 font-bold leading-none mb-1">Kantor Cabang</span>
                            <span class="text-xs font-black text-white tracking-tight">{{ $activeKantorName }}</span>
                        </div>
                    </div>

                    <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 shadow-sm text-white">
                        <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-white/10 text-white shrink-0">
                            <svg class="h-4.5 w-4.5 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <div class="flex flex-col text-left pr-2">
                            <span class="text-[9px] uppercase tracking-widest text-indigo-100 font-bold leading-none mb-1">Tahun Ajaran</span>
                            <span class="text-xs font-black text-white tracking-tight">{{ $activePeriodeYear }}</span>
                        </div>
                    </div>
                </div>
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

    @include('layouts.admin.pesan-panel')

    {{-- ─── Stat Cards ─── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-10">
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
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Paket Bimbingan Aktif</span>
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
    </div>

    {{-- ─── Grafik Statistik ─── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        {{-- Chart: Peserta Didik --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-800 dark:text-white tracking-tight">Statistik Peserta Didik</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Peserta masuk vs keluar periode ini</p>
                    </div>
                    <div class="px-3.5 py-1 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest border border-indigo-100/50 dark:border-indigo-900/50">
                        Siswa
                    </div>
                </div>
                <div id="chart-peserta-didik" class="w-full min-h-[320px]"></div>
            </div>
        </div>

        {{-- Chart: Keuangan --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-800 dark:text-white tracking-tight">Statistik Keuangan</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Arus kas masuk vs keluar periode ini</p>
                    </div>
                    <div class="px-3.5 py-1 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest border border-emerald-100/50 dark:border-emerald-900/50">
                        Keuangan
                    </div>
                </div>
                <div id="chart-keuangan" class="w-full min-h-[320px]"></div>
            </div>
        </div>
    </div>

    {{-- ─── Lead Tracking: Pendaftar Terbaru ─── --}}
    <div class="mb-10">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 dark:text-white tracking-tight">Monitoring Pendaftar Terbaru</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Pantau dan tindak lanjuti pendaftar yang masuk</p>
            </div>
            <a href="{{ route('admin.pendaftaran.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-zinc-800">
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Pendaftar</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Program</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                        @forelse($recentPendaftaran ?? [] as $p)
                            <tr class="group hover:bg-slate-50/30 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-black text-sm">
                                            {{ substr($p->nama_lengkap, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-slate-800 dark:text-white">{{ $p->nama_lengkap }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $p->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $p->paketBimbingan->nama_paket ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-400 uppercase tracking-tighter">{{ $p->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="w-2 h-2 rounded-full {{ $p->email_verified_at ? 'bg-emerald-500' : 'bg-slate-300' }}" title="{{ $p->email_verified_at ? 'Email Terverifikasi' : 'Email Belum Verifikasi' }}"></span>
                                        @if($p->pembayaran)
                                            <span class="px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-[9px] font-black uppercase tracking-tighter border border-amber-100 dark:border-amber-800">Bukti Dikirim</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 text-[9px] font-black uppercase tracking-tighter border border-rose-100 dark:border-rose-800">Belum Bayar</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @php
                                        $noTelepon = preg_replace('/[^0-9]/', '', $p->no_telepon);
                                        if(str_starts_with($noTelepon, '0')) $noTelepon = '62' . substr($noTelepon, 1);
                                        $waLink = "https://wa.me/{$noTelepon}?text=".urlencode("Halo {$p->nama_lengkap}, kami dari tim Admin. Kami melihat Anda telah melakukan pendaftaran namun belum menyelesaikan prosesnya. Apakah ada yang bisa kami bantu?");
                                    @endphp
                                    <a href="{{ $waLink }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-50 dark:bg-green-950 text-green-600 dark:text-green-400 text-[10px] font-black uppercase tracking-widest hover:bg-green-600 hover:text-white transition-all">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.937 3.659 1.43 5.623 1.43h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                        Hubungi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500">Belum ada pendaftaran terbaru</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
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
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? '#334155' : '#f1f5f9';

        // 1. Chart Peserta Didik
        var optionsPeserta = {
            series: [{
                name: 'Peserta Masuk',
                data: @json($chartPesertaMasuk)
            }, {
                name: 'Peserta Keluar',
                data: @json($chartPesertaKeluar)
            }],
            chart: {
                type: 'area',
                height: 320,
                toolbar: { show: false },
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            colors: ['#4F46E5', '#EF4444'], // Indigo & Red
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.3,
                    opacityTo: 0.02,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            xaxis: {
                categories: @json($chartLabels),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 }
                }
            },
            grid: {
                borderColor: gridColor,
                strokeDashArray: 4,
                xaxis: { lines: { show: false } }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                fontWeight: 700,
                fontSize: '12px',
                labels: { colors: '#64748b' }
            },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                x: { format: 'dd MMM yyyy' }
            }
        };

        var chartPeserta = new ApexCharts(document.querySelector("#chart-peserta-didik"), optionsPeserta);
        chartPeserta.render();

        // 2. Chart Keuangan
        var optionsKeuangan = {
            series: [{
                name: 'Uang Masuk',
                data: @json($chartUangMasuk)
            }, {
                name: 'Uang Keluar',
                data: @json($chartUangKeluar)
            }],
            chart: {
                type: 'area',
                height: 320,
                toolbar: { show: false },
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            colors: ['#10B981', '#F43F5E'], // Emerald & Rose
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.3,
                    opacityTo: 0.02,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            xaxis: {
                categories: @json($chartLabels),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 }
                }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(value);
                    },
                    style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 }
                }
            },
            grid: {
                borderColor: gridColor,
                strokeDashArray: 4,
                xaxis: { lines: { show: false } }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                fontWeight: 700,
                fontSize: '12px',
                labels: { colors: '#64748b' }
            },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                y: {
                    formatter: function (value) {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(value);
                    }
                }
            }
        };

        var chartKeuangan = new ApexCharts(document.querySelector("#chart-keuangan"), optionsKeuangan);
        chartKeuangan.render();
    });

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