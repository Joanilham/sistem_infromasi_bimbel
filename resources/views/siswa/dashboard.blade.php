@extends('layouts.siswa')

@section('title', 'Dashboard')

@section('content')
@php
    $namaDepan = explode(' ', $peserta ? $peserta->nama_lengkap : auth()->user()->name)[0];
    $isAktif = strtolower(auth()->user()->status) === 'aktif';
@endphp

@push('head')
<style>
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(60px, -60px) scale(1.2); }
        66% { transform: translate(-40px, 40px) scale(0.8); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
</style>
@endpush

<div class="space-y-6 pb-12">

    {{-- HERO SECTION --}}
    <div class="relative mb-8 rounded-3xl shadow-xl z-20 overflow-hidden">
        {{-- Animated gradient background layer --}}
        <div class="absolute inset-0 rounded-3xl overflow-hidden pointer-events-none -z-10"
             style="background: linear-gradient(135deg, #0d9488 0%, #115e59 45%, #042f2e 100%);">
            {{-- Animated Blobs --}}
            <div class="absolute -top-20 -left-20 w-80 h-80 rounded-full bg-emerald-400/30 blur-3xl animate-blob pointer-events-none"></div>
            <div class="absolute top-0 -right-20 w-96 h-96 rounded-full bg-white/20 blur-3xl animate-blob animation-delay-2000 pointer-events-none"></div>
            <div class="absolute -bottom-32 left-1/3 w-80 h-80 rounded-full bg-teal-300/30 blur-3xl animate-blob animation-delay-4000 pointer-events-none"></div>
            {{-- Dot grid overlay --}}
            <div class="absolute inset-0 opacity-[0.05]"
                style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;">
            </div>
        </div>

        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-px bg-gradient-to-r from-transparent via-white/20 to-transparent pointer-events-none"></div>

        <div class="relative z-10 p-6 sm:p-8 lg:p-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6 sm:gap-8">
            <div class="flex-1 min-w-0">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/25 mb-4 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                    <span class="text-xs font-bold tracking-widest text-emerald-100 uppercase">Portal Siswa</span>
                </div>
                
                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight mb-3 leading-tight">
                    Selamat Datang,<br>
                    <span class="text-emerald-200">{{ $namaDepan }}</span>
                </h1>
                
                <p class="text-emerald-50/95 text-sm sm:text-base max-w-xl font-medium leading-relaxed">
                    Pantau kemajuan akademik, jadwal bimbingan belajar, ujian CBT, dan administrasi Anda dalam satu portal terpadu.
                </p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 shrink-0">
                <div class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-2xl bg-white/15 backdrop-blur-md border border-white/20 text-xs sm:text-sm font-bold shadow-xs text-white">
                    <svg class="w-4.5 h-4.5 text-emerald-200 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>{{ $peserta?->kelompokBelajar?->nama_kelompok ?? 'Belum Masuk Kelas' }}</span>
                </div>
                <div class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-2xl bg-white/15 backdrop-blur-md border border-white/20 text-xs sm:text-sm font-bold shadow-xs text-white">
                    <svg class="w-4.5 h-4.5 text-emerald-200 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $peserta?->paketBimbingan?->nama_paket ?? 'Paket Belum Diatur' }}</span>
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('siswa.lms.index') }}" class="group flex items-center justify-between rounded-2xl border border-indigo-200 bg-indigo-50 p-5 shadow-sm transition hover:border-indigo-400 hover:shadow-md dark:border-indigo-900 dark:bg-indigo-950/40">
        <div class="flex items-center gap-4"><div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-600 text-white"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332 0.477 4.5 1.253v13C19.832 18 18.247 18 16.5 18c-1.746 0 0 0-4.5 1.253" /></svg></div><div><h2 class="font-black text-slate-900 dark:text-white">LMS</h2><p class="text-sm text-slate-600 dark:text-slate-300">Lihat materi dan tugas dari guru</p></div></div><span class="text-indigo-600 transition group-hover:translate-x-1">&rarr;</span>
    </a>

    {{-- WARNINGS --}}
    @if(!$isAktif || session('pending_message'))
        <div class="relative bg-amber-50/90 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 shadow-xs rounded-2xl overflow-hidden">
            <div class="relative z-10 p-5 sm:p-6 flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-5">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-center sm:text-left flex-1">
                    <h3 class="text-base font-bold text-amber-900 dark:text-amber-200 mb-1">Akun Belum Diverifikasi</h3>
                    <p class="text-xs sm:text-sm font-medium text-amber-800/80 dark:text-amber-300/80 leading-relaxed mb-3 max-w-3xl">
                        @php
                            $masterWa = \App\Models\MasterData\Master::first()?->wa_number ?? '6281234567890';
                        @endphp
                        {{ session('pending_message') ?? 'Akun Anda saat ini sedang dalam antrean verifikasi oleh Admin. Silakan hubungi bagian administrasi jika membutuhkan aktivasi segera.' }}
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $masterWa) }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-xs transition hover:bg-emerald-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Hubungi Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($isAktif && $peserta && $peserta->pembayaran && $peserta->pembayaran->kekurangan > 0 && $peserta->pembayaran->jatuh_tempo_berikutnya)
        @php
            $pembayaran = $peserta->pembayaran;
            $jatuhTempo = \Carbon\Carbon::parse($pembayaran->jatuh_tempo_berikutnya);
            $sisaHari = now()->startOfDay()->diffInDays($jatuhTempo->startOfDay(), false);
            $isOverdue = $sisaHari < 0;
            $showWarning = $sisaHari <= 7;
        @endphp
        
        @if($showWarning)
        <div class="relative {{ $isOverdue ? 'bg-red-50/90 dark:bg-red-950/40 border-red-200 dark:border-red-800/60' : 'bg-orange-50/90 dark:bg-orange-950/40 border-orange-200 dark:border-orange-800/60' }} border shadow-xs rounded-2xl overflow-hidden">
            <div class="relative z-10 p-5 sm:p-6 flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-5">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $isOverdue ? 'bg-red-100 text-red-600 dark:bg-red-900/50 dark:text-red-400' : 'bg-orange-100 text-orange-600 dark:bg-orange-900/50 dark:text-orange-400' }}">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="text-center sm:text-left flex-1">
                    <h3 class="text-base font-bold mb-1 {{ $isOverdue ? 'text-red-900 dark:text-red-200' : 'text-orange-900 dark:text-orange-200' }}">
                        {{ $isOverdue ? 'Cicilan Menunggak' : 'Jatuh Tempo Cicilan' }}
                    </h3>
                    <p class="text-xs sm:text-sm font-medium leading-relaxed mb-3 max-w-3xl {{ $isOverdue ? 'text-red-800/80 dark:text-red-300/80' : 'text-orange-800/80 dark:text-orange-300/80' }}">
                        @if($isOverdue)
                            Batas waktu pembayaran cicilan telah terlampaui ({{ $jatuhTempo->format('d M Y') }}). Segera selesaikan pembayaran sebesar <strong>Rp {{ number_format($pembayaran->nominal_per_cicilan, 0, ',', '.') }}</strong> agar akses belajar tetap aktif.
                        @else
                            Tagihan cicilan Anda sebesar <strong>Rp {{ number_format($pembayaran->nominal_per_cicilan, 0, ',', '.') }}</strong> jatuh tempo pada <strong>{{ $jatuhTempo->format('d M Y') }}</strong> ({{ ceil($sisaHari) }} hari lagi).
                        @endif
                    </p>
                    <a href="{{ route('siswa.pembayaran.index') }}" class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-bold text-white shadow-xs transition {{ $isOverdue ? 'bg-red-600 hover:bg-red-700' : 'bg-orange-600 hover:bg-orange-700' }}">
                        Bayar Sekarang &rarr;
                    </a>
                </div>
            </div>
        </div>
        @endif
    @endif

    {{-- MAIN BENTO GRID --}}
    @if($isAktif)
    <div class="grid grid-cols-1 md:grid-cols-12 gap-5 sm:gap-6">

        <!-- Info Pribadi (Bento Box) -->
        <div class="md:col-span-8 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 rounded-2xl p-6 sm:p-8 flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight mb-1">Identitas Siswa</h2>
                        <p class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Data profil bimbingan belajar Anda.</p>
                    </div>
                    <div class="bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 font-bold px-3 py-1 rounded-lg text-xs uppercase tracking-wider border border-emerald-200 dark:border-emerald-800/40">
                        Aktif
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-5 gap-x-8">
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Nama Lengkap</div>
                        <div class="text-base sm:text-lg font-bold text-slate-800 dark:text-slate-100">{{ $peserta?->nama_lengkap ?? auth()->user()->name }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">NISN</div>
                        <div class="text-base sm:text-lg font-bold text-slate-800 dark:text-slate-100">{{ $peserta?->nisn ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Asal Sekolah</div>
                        <div class="text-base sm:text-lg font-bold text-slate-800 dark:text-slate-100">{{ $peserta?->asal_sekolah ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Cabang / Kantor</div>
                        <div class="text-base sm:text-lg font-bold text-slate-800 dark:text-slate-100">{{ $peserta?->kantor?->nama_kantor ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code Action (Bento Box) -->
        <div class="md:col-span-4 rounded-2xl shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 overflow-hidden relative p-6 sm:p-8 flex flex-col items-center justify-center text-center text-white"
             style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);">
            <div class="w-16 h-16 rounded-2xl bg-white/15 backdrop-blur-md border border-white/20 flex items-center justify-center mb-4 shadow-sm text-white">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
            </div>
            <h3 class="text-lg font-black text-white mb-1">QR Presensi</h3>
            <p class="text-xs text-teal-100/90 mb-6 font-medium">Pindai kode QR untuk konfirmasi kehadiran di kelas.</p>
            
            <a href="{{ route('siswa.qr.show') }}" class="w-full bg-white text-teal-800 hover:bg-teal-50 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition-colors shadow-sm">
                Tampilkan QR Presensi
            </a>
        </div>

        <!-- Stats: Hadir -->
        <div class="md:col-span-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 rounded-2xl p-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kehadiran Bulan Ini</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-1.5">{{ $totalHadir }}</p>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>Sesi pembelajaran dihadiri</span>
            </p>
        </div>

        <!-- Stats: Alpha -->
        <div class="md:col-span-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 rounded-2xl p-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Alpha / Izin</span>
                <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-950/50 text-red-600 dark:text-red-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-1.5">{{ $totalAlpha ?? 0 }}</p>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full {{ ($totalAlpha ?? 0) > 0 ? 'bg-red-500' : 'bg-emerald-500' }}"></span>
                <span>{{ ($totalAlpha ?? 0) > 0 ? 'Sesi tidak dihadiri' : 'Presensi tertib sempurna' }}</span>
            </p>
        </div>

        <!-- Bantuan WA -->
        <div class="md:col-span-4 bg-slate-900 dark:bg-zinc-900 text-white rounded-2xl p-5 sm:p-6 shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 border border-slate-800 flex flex-col justify-between">
            @php
                $master = \App\Models\MasterData\Master::first();
                $waNum = $master?->wa_number ?? '6281234567890';
                $namaSiswa = $peserta?->nama_lengkap ?? auth()->user()->name;
                $pesanAduan = "Halo Admin, saya " . $namaSiswa . ", ingin menanyakan informasi akademik...";
                $waUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $waNum) . "?text=" . urlencode($pesanAduan);
            @endphp
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white">Butuh Bantuan?</h3>
                        <p class="text-xs text-slate-400">Pusat Layanan Siswa</p>
                    </div>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed mb-4">Konsultasikan kendala jadwal, materi, atau administrasi langsung dengan admin.</p>
            </div>
            <a href="{{ $waUrl }}" target="_blank" class="w-full text-center bg-white/10 hover:bg-white/20 border border-white/15 text-white font-bold text-xs py-2.5 rounded-xl transition-colors">
                Hubungi via WhatsApp &rarr;
            </a>
        </div>

        <!-- Ujian CBT -->
        <div class="md:col-span-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 rounded-2xl p-5 sm:p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Ujian CBT Aktif</h2>
                </div>
                <span class="bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 text-xs font-bold px-2.5 py-1 rounded-full">
                    {{ $ujianAktif->count() }} Tersedia
                </span>
            </div>

            @if($ujianAktif->count() > 0)
                <div class="space-y-2.5 flex-1">
                    @foreach($ujianAktif->take(4) as $ujian)
                        <a href="{{ route('siswa.ujian.show', $ujian->id) }}" class="flex items-center gap-3.5 p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 hover:bg-indigo-50/50 dark:hover:bg-slate-800 border border-slate-100 dark:border-slate-700/60 hover:border-indigo-200 dark:hover:border-indigo-800 transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-105 transition-transform shadow-xs shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-slate-900 dark:text-white truncate text-sm">{{ $ujian->judul }}</h3>
                                <p class="text-[11px] font-semibold text-slate-400 mt-0.5">
                                    {{ $ujian->durasi }} Menit &bull; {{ $ujian->waktu_selesai ? $ujian->waktu_selesai->diffForHumans() : 'Tanpa batas waktu' }}
                                </p>
                            </div>
                            <div class="text-slate-400 group-hover:text-indigo-600 transition-colors shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                            </div>
                        </a>
                    @endforeach
                </div>
                @if($ujianAktif->count() > 4)
                    <a href="{{ route('siswa.ujian.index') }}" class="mt-4 block text-center text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                        Lihat semua ujian &rarr;
                    </a>
                @endif
            @else
                <div class="flex-1 flex flex-col items-center justify-center text-center p-6">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-3 text-slate-400">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 font-medium text-xs">Belum ada jadwal ujian CBT aktif saat ini.</p>
                </div>
            @endif
        </div>

        <!-- Riwayat Absensi -->
        <div class="md:col-span-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 rounded-2xl p-5 sm:p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Riwayat Kehadiran</h2>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">5 Terakhir</span>
            </div>

            @if($absensis->count() > 0)
                <div class="space-y-2 flex-1">
                    @foreach($absensis->take(5) as $a)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/70 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/60 hover:border-emerald-200 dark:hover:border-emerald-800 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $a->status_masuk === 'hadir' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/50 dark:text-emerald-400' : ($a->status_masuk === 'alpha' ? 'bg-red-100 text-red-600 dark:bg-red-900/50 dark:text-red-400' : 'bg-amber-100 text-amber-600 dark:bg-amber-900/50 dark:text-amber-400') }}">
                                    @if($a->status_masuk === 'hadir')
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    @elseif($a->status_masuk === 'alpha')
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <div class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $a->tanggal->format('d M Y') }}</div>
                                    <div class="text-[10px] font-semibold text-slate-400">
                                        {{ $a->jam_masuk ? substr($a->jam_masuk, 0, 5) : '--:--' }} - {{ $a->jam_pulang ? substr($a->jam_pulang, 0, 5) : '--:--' }}
                                    </div>
                                </div>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md shrink-0 {{ $a->status_masuk === 'hadir' ? 'bg-emerald-100/80 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' : ($a->status_masuk === 'alpha' ? 'bg-red-100/80 text-red-700 dark:bg-red-950/60 dark:text-red-300' : 'bg-amber-100/80 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300') }}">
                                {{ $a->status_masuk }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center text-center p-6">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-3 text-slate-400">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 font-medium text-xs">Belum ada rekaman presensi bulan ini.</p>
                </div>
            @endif
        </div>

    </div>
    @endif

</div>

@endsection
