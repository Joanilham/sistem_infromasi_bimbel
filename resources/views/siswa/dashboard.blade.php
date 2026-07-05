@extends('layouts.siswa')

@section('title', 'Dashboard')

@section('content')
@php
    $namaDepan = explode(' ', $peserta ? $peserta->nama_lengkap : auth()->user()->name)[0];
    $isAktif = strtolower(auth()->user()->status) === 'aktif';
@endphp

<style>
    .hero-gradient {
        background: linear-gradient(135deg, #0F5253 0%, #1a7471 50%, #299c98 100%);
    }
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

<div class="space-y-6 pb-12">

    {{-- HERO SECTION --}}
    <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl hero-gradient p-8 sm:p-12">
        <!-- Animated Blobs -->
        <div class="absolute -top-20 -left-20 w-80 h-80 rounded-full bg-emerald-300/40 blur-3xl animate-blob pointer-events-none"></div>
        <div class="absolute top-0 -right-20 w-96 h-96 rounded-full bg-white/30 blur-3xl animate-blob animation-delay-2000 pointer-events-none"></div>
        <div class="absolute -bottom-32 left-1/3 w-80 h-80 rounded-full bg-teal-200/40 blur-3xl animate-blob animation-delay-4000 pointer-events-none"></div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle, #fff 2px, transparent 2px); background-size: 32px 32px;"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 mb-6">
                    <span class="relative flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
                    </span>
                    <span class="text-xs font-bold tracking-widest text-white uppercase">Portal Siswa</span>
                </div>
                
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight mb-4 leading-tight">
                    Halo, {{ $namaDepan }}! <span class="inline-block animate-bounce origin-bottom" style="animation-duration: 2s;">👋</span>
                </h1>
                
                <p class="text-[#cce8e4] text-sm sm:text-base md:text-lg max-w-xl font-medium leading-relaxed">
                    Selamat datang di pusat aktivitas belajarmu. Pantau kemajuan, jadwal, dan tagihanmu dengan mudah di sini.
                </p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 shrink-0">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-white/30 dark:bg-black/30 backdrop-blur-md border border-white/40 dark:border-white/10 text-xs sm:text-sm font-bold shadow-sm text-white">
                    <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    {{ $peserta?->kelompokBelajar?->nama_kelompok ?? 'Belum Masuk Kelas' }}
                </div>
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-500/20 backdrop-blur-md border border-emerald-400/30 text-xs sm:text-sm font-bold shadow-sm text-emerald-50">
                    <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $peserta?->paketBimbingan?->nama_paket ?? 'Paket Belum Diatur' }}
                </div>
            </div>
        </div>
    </div>

    {{-- WARNINGS --}}
    @if(!$isAktif || session('pending_message'))
        <div class="relative bg-amber-50/90 border border-amber-200 shadow-sm rounded-[2rem] overflow-hidden">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-amber-400/20 blur-3xl rounded-full"></div>
            <div class="relative z-10 p-6 sm:p-8 h-full flex flex-col flex-col sm:flex-row items-center sm:items-start gap-6">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-[1.25rem] bg-gradient-to-br from-amber-300 to-amber-500 text-white shadow-lg shadow-amber-500/30">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="text-center sm:text-left flex-1">
                    <h3 class="text-xl font-black text-amber-900 mb-2">Akun Belum Diverifikasi ⏳</h3>
                    <p class="text-sm font-medium text-amber-800/80 leading-relaxed mb-4 max-w-3xl">
                        @php
                            $masterWa = \App\Models\MasterData\Master::first()?->wa_number ?? '6281234567890';
                        @endphp
                        {{ session('pending_message') ?? 'Akun Anda saat ini belum diverifikasi oleh Admin. Mohon tunggu beberapa saat untuk proses verifikasi. Jika tak kunjung diverifikasi, silakan hubungi Admin Pusat melalui WhatsApp.' }}
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $masterWa) }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-[#25D366] px-6 py-2.5 text-xs font-bold text-white shadow-lg shadow-[#25D366]/20 transition hover:bg-[#1DA851] hover:-translate-y-0.5">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
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
        <div class="relative {{ $isOverdue ? 'bg-red-50/90 border-red-200/50' : 'bg-orange-50/90 border-orange-200/50' }} border shadow-sm rounded-[2rem] overflow-hidden">
            <div class="absolute -right-20 -top-20 w-64 h-64 {{ $isOverdue ? 'bg-red-400/20' : 'bg-orange-400/20' }} blur-3xl rounded-full"></div>
            <div class="relative z-10 p-6 sm:p-8 h-full flex flex-col flex-col sm:flex-row items-center sm:items-start gap-6">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-[1.25rem] bg-gradient-to-br {{ $isOverdue ? 'from-red-400 to-red-600 shadow-red-500/30' : 'from-orange-400 to-orange-600 shadow-orange-500/30' }} text-white shadow-lg">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="text-center sm:text-left flex-1">
                    <h3 class="text-xl font-black mb-2 {{ $isOverdue ? 'text-red-900' : 'text-orange-900' }}">
                        {{ $isOverdue ? 'Cicilan Menunggak! ⚠️' : 'Jatuh Tempo Cicilan 📅' }}
                    </h3>
                    <p class="text-sm font-medium leading-relaxed mb-4 max-w-3xl {{ $isOverdue ? 'text-red-800/80' : 'text-orange-800/80' }}">
                        @if($isOverdue)
                            Anda telah melewati batas waktu pembayaran cicilan ({{ $jatuhTempo->format('d M Y') }}). Segera lunasi cicilan sebesar <strong>Rp {{ number_format($pembayaran->nominal_per_cicilan, 0, ',', '.') }}</strong> agar tidak dinonaktifkan.
                        @else
                            Tagihan cicilan Anda sebesar <strong>Rp {{ number_format($pembayaran->nominal_per_cicilan, 0, ',', '.') }}</strong> akan jatuh tempo pada <strong>{{ $jatuhTempo->format('d M Y') }}</strong> ({{ ceil($sisaHari) }} hari lagi).
                        @endif
                    </p>
                    <a href="{{ route('siswa.pembayaran.index') }}" class="inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-xs font-bold text-white shadow-lg transition hover:-translate-y-0.5 {{ $isOverdue ? 'bg-red-600 shadow-red-600/20 hover:bg-red-700' : 'bg-orange-600 shadow-orange-600/20 hover:bg-orange-700' }}">
                        Bayar Sekarang
                    </a>
                </div>
            </div>
        </div>
        @endif
    @endif

    {{-- MAIN BENTO GRID --}}
    @if($isAktif)
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

        <!-- Info Pribadi (Bento Box) -->
        <div class="md:col-span-8 relative bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 rounded-[2rem] overflow-hidden flex flex-col justify-between p-1">
            <div class="relative z-10 p-6 sm:p-8 h-full flex flex-col">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h2 class="text-2xl font-black text-slate-800 dark:text-white mb-1">Identitas Siswa</h2>
                        <p class="text-sm font-medium text-slate-400">Data profil bimbingan belajarmu.</p>
                    </div>
                    <div class="bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 font-bold px-3 py-1 rounded-lg text-xs uppercase tracking-widest border border-emerald-200 dark:border-emerald-500/30">
                        Aktif
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8 mt-auto">
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Lengkap</div>
                        <div class="text-lg font-bold text-slate-700 dark:text-slate-200">{{ $peserta?->nama_lengkap ?? auth()->user()->name }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">NISN</div>
                        <div class="text-lg font-bold text-slate-700 dark:text-slate-200">{{ $peserta?->nisn ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Asal Sekolah</div>
                        <div class="text-lg font-bold text-slate-700 dark:text-slate-200">{{ $peserta?->asal_sekolah ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Cabang/Kantor</div>
                        <div class="text-lg font-bold text-slate-700 dark:text-slate-200">{{ $peserta?->kantor?->nama_kantor ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code Action (Bento Box) -->
        <div class="md:col-span-4 relative bg-gradient-to-br from-[#0F5253] to-[#1a7471] shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 rounded-[2rem] overflow-hidden group border-none">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] [mask-image:linear-gradient(to_bottom_right,white,transparent)]"></div>
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-[#78BBB0]/30 blur-3xl rounded-full transition-all group-hover:bg-[#78BBB0]/40 group-hover:scale-110"></div>
            
            <div class="relative z-10 p-6 sm:p-8 h-full flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 rounded-3xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center mb-6 shadow-xl text-white transform group-hover:-translate-y-2 transition-all duration-300">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                </div>
                <h3 class="text-xl font-black text-white mb-2">QR Absensi</h3>
                <p class="text-sm text-teal-100/80 mb-8 font-medium">Gunakan QR Code ini untuk scan kehadiran di kelas.</p>
                
                <a href="{{ route('siswa.qr.show') }}" class="w-full bg-white text-[#0F5253] py-3.5 rounded-2xl font-bold text-sm hover:bg-teal-50 transition-colors shadow-lg shadow-black/10">
                    Tampilkan QR
                </a>
            </div>
        </div>

        <!-- Stats: Hadir -->
        <div class="md:col-span-4 relative bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 rounded-[2rem] overflow-hidden p-1">
            <div class="relative z-10 p-6 sm:p-8 h-full flex items-center gap-6">
                <div class="w-16 h-16 rounded-[1.25rem] bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center shrink-0">
                    <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kehadiran Bulan Ini</div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black text-slate-800 dark:text-white tabular-nums">{{ $totalHadir }}</span>
                        <span class="text-sm font-bold text-slate-400">Hari</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats: Alpha -->
        <div class="md:col-span-4 relative bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 rounded-[2rem] overflow-hidden p-1">
            <div class="relative z-10 p-6 sm:p-8 h-full flex items-center gap-6">
                <div class="w-16 h-16 rounded-[1.25rem] bg-red-100 dark:bg-red-500/20 flex items-center justify-center shrink-0">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Alpha / Bolos</div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black text-slate-800 dark:text-white tabular-nums">{{ $totalAlpha ?? 0 }}</span>
                        <span class="text-sm font-bold text-slate-400">Hari</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bantuan WA -->
        <div class="md:col-span-4 relative bg-zinc-900 dark:bg-zinc-800 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 rounded-[2rem] overflow-hidden border-none group">
            <div class="absolute inset-0 opacity-20" style="background-image: repeating-linear-gradient(45deg, #333 25%, transparent 25%, transparent 75%, #333 75%, #333), repeating-linear-gradient(45deg, #333 25%, #111 25%, #111 75%, #333 75%, #333); background-position: 0 0, 10px 10px; background-size: 20px 20px;"></div>
            <div class="relative z-10 p-6 sm:p-8 h-full flex flex-col justify-center">
                @php
                    $master = \App\Models\MasterData\Master::first();
                    $waNum = $master?->wa_number ?? '6281234567890';
                    $namaSiswa = $peserta?->nama_lengkap ?? auth()->user()->name;
                    $pesanAduan = "Halo Admin, saya " . $namaSiswa . ", ingin menanyakan/menyampaikan...";
                    $waUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $waNum) . "?text=" . urlencode($pesanAduan);
                @endphp
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg">Butuh Bantuan?</h3>
                        <p class="text-zinc-400 text-xs font-medium">Hubungi Admin via WA</p>
                    </div>
                </div>
                <a href="{{ $waUrl }}" target="_blank" class="block w-full text-center bg-white/10 hover:bg-white/20 border border-white/10 text-white font-bold text-sm py-3 rounded-xl transition-colors backdrop-blur-sm">
                    Kirim Pesan
                </a>
            </div>
        </div>

        <!-- Ujian CBT -->
        <div class="md:col-span-6 relative bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 rounded-[2rem] overflow-hidden p-1 flex flex-col">
            <div class="relative z-10 p-6 sm:p-8 h-full flex flex-col flex-1">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-black text-slate-800 dark:text-white">Ujian CBT Aktif</h2>
                    <span class="bg-[#0F5253]/10 text-[#0F5253] dark:text-[#78BBB0] text-xs font-bold px-3 py-1 rounded-full">{{ $ujianAktif->count() }} Tersedia</span>
                </div>

                @if($ujianAktif->count() > 0)
                    <div class="space-y-3 flex-1">
                        @foreach($ujianAktif->take(4) as $ujian)
                            <a href="{{ route('siswa.ujian.show', $ujian->id) }}" class="flex items-center gap-4 p-4 rounded-[1.25rem] bg-slate-50 dark:bg-zinc-800/50 hover:bg-[#f2fafa] dark:hover:bg-zinc-800 border border-transparent hover:border-[#78BBB0]/30 transition-all group">
                                <div class="w-12 h-12 rounded-xl bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-700 flex items-center justify-center text-[#0F5253] dark:text-[#78BBB0] group-hover:scale-110 transition-transform shadow-sm">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-slate-800 dark:text-slate-100 truncate text-sm sm:text-base">{{ $ujian->judul }}</h3>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-wider">
                                        {{ $ujian->durasi }} Menit &bull; {{ $ujian->waktu_selesai ? $ujian->waktu_selesai->diffForHumans() : 'Tanpa batas waktu' }}
                                    </p>
                                </div>
                                <div class="text-slate-300 group-hover:text-[#0F5253] transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    @if($ujianAktif->count() > 4)
                        <a href="{{ route('siswa.ujian.index') }}" class="mt-4 block text-center text-sm font-bold text-[#0F5253] dark:text-[#78BBB0] hover:underline">
                            Lihat semua ujian &rarr;
                        </a>
                    @endif
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-center p-6">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-zinc-800 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Belum ada jadwal ujian CBT aktif saat ini.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Riwayat Absensi -->
        <div class="md:col-span-6 relative bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 rounded-[2rem] overflow-hidden p-1 flex flex-col">
            <div class="relative z-10 p-6 sm:p-8 h-full flex flex-col flex-1">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-black text-slate-800 dark:text-white">Riwayat Kehadiran</h2>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">5 Terakhir</span>
                </div>

                @if($absensis->count() > 0)
                    <div class="flex-1 -mx-2">
                        <div class="space-y-1">
                            @foreach($absensis->take(5) as $a)
                                <div class="flex items-center justify-between p-3 sm:p-4 rounded-[1rem] hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $a->status_masuk === 'hadir' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : ($a->status_masuk === 'alpha' ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400') }}">
                                            @if($a->status_masuk === 'hadir')
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                            @elseif($a->status_masuk === 'alpha')
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $a->tanggal->format('d M Y') }}</div>
                                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                                                {{ $a->jam_masuk ? substr($a->jam_masuk, 0, 5) : '--:--' }} - {{ $a->jam_pulang ? substr($a->jam_pulang, 0, 5) : '--:--' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-xs font-black uppercase tracking-widest {{ $a->status_masuk === 'hadir' ? 'text-emerald-600 dark:text-emerald-400' : ($a->status_masuk === 'alpha' ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400') }}">
                                        {{ $a->status_masuk }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-center p-6">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-zinc-800 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Belum ada rekaman absensi bulan ini.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
    @endif

</div>

@endsection
