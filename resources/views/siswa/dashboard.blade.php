@extends('layouts.siswa')

@section('title', 'Dashboard')

@section('content')
@php
    $namaDepan = explode(' ', $peserta ? $peserta->nama_lengkap : auth()->user()->name)[0];
    $isAktif = auth()->user()->status === 'aktif';
@endphp

<div class="space-y-8 pb-12">

    {{-- Hero — pola warna & spacing mengikuti dashboard guru --}}
    <div class="relative mb-2 rounded-[1.75rem] overflow-hidden shadow-2xl bg-gradient-to-br from-[#388782] via-[#206D6C] to-[#0F5253]">
        <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-16 w-80 h-80 rounded-full bg-[#388782]/25 blur-3xl pointer-events-none"></div>
        <div class="absolute inset-0 opacity-[0.05]" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;"></div>

        <div class="relative z-10 p-5 md:p-10 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 md:gap-8">
            <div class="flex-1 min-w-0">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-4 mt-0.5">
                    <span class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-[#A2D5CB]"></span>
                    <span class="text-[10px] md:text-xs font-semibold tracking-widest text-[#A2D5CB] uppercase">Ringkasan hari ini</span>
                </div>
                <h1 class="text-xl sm:text-2xl md:text-4xl font-extrabold text-white leading-tight tracking-tight mb-2 md:mb-3">
                    Halo, {{ $namaDepan }}! 👋
                </h1>
                <p class="text-[#A2D5CB] text-xs sm:text-sm md:text-base max-w-xl leading-relaxed font-medium">
                    Pantau kehadiran, paket bimbingan, dan QR absensi Anda di satu tempat.
                </p>
            </div>
            <div class="flex flex-wrap gap-2 md:gap-3 shrink-0">
                <span class="rounded-xl md:rounded-2xl bg-white/15 border border-white/20 px-3 md:px-4 py-2 text-xs md:text-sm font-semibold text-white backdrop-blur-sm">{{ $peserta?->kelompokBelajar?->nama_kelompok ?? 'Menunggu Rombel' }}</span>
                <span class="rounded-xl md:rounded-2xl bg-white/10 px-3 md:px-4 py-2 text-xs md:text-sm font-semibold text-[#A2D5CB]">{{ $peserta?->paketBimbingan?->nama_paket ?? 'Paket Belum Diatur' }}</span>
            </div>
        </div>
    </div>

    {{-- PESAN PERINGATAN JIKA BELUM DIVERIFIKASI --}}
    @if(!$isAktif || session('pending_message'))
        <div class="relative overflow-hidden rounded-[1.75rem] bg-amber-50 border border-amber-200 p-6 sm:p-8 shadow-sm">
            <div class="absolute -right-12 -top-12 h-40 w-40 rounded-full bg-amber-400/20 blur-3xl"></div>
            <div class="relative z-10 flex flex-col sm:flex-row gap-6 items-start sm:items-center">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-amber-600 shadow-inner">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-black text-amber-900 mb-2">Pendaftaran Sedang Diproses ⏳</h3>
                    <p class="text-sm font-medium text-amber-800 leading-relaxed mb-4">
                        {{ session('pending_message') ?? 'Selamat datang! Akun Anda saat ini sedang dalam proses peninjauan oleh Admin Pusat. Menu kelas (seperti Ujian, Absensi, dan Jadwal) akan otomatis terbuka setelah pendaftaran dan pembayaran diverifikasi.' }}
                    </p>
                    <a href="{{ route('siswa.pembayaran.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-600 px-5 py-2.5 text-xs font-bold text-white shadow-lg shadow-amber-600/20 transition hover:bg-amber-700">
                        Cek Tagihan / Pembayaran
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- PESAN PERINGATAN JATUH TEMPO CICILAN --}}
    @if($isAktif && $peserta && $peserta->pembayaran && $peserta->pembayaran->kekurangan > 0 && $peserta->pembayaran->jatuh_tempo_berikutnya)
        @php
            $pembayaran = $peserta->pembayaran;
            $jatuhTempo = \Carbon\Carbon::parse($pembayaran->jatuh_tempo_berikutnya);
            // Hanya tampilkan jika H-7 sebelum jatuh tempo ATAU sudah lewat
            $sisaHari = now()->startOfDay()->diffInDays($jatuhTempo->startOfDay(), false);
            $isOverdue = $sisaHari < 0;
            $showWarning = $sisaHari <= 7;
        @endphp
        
        @if($showWarning)
        <div class="relative overflow-hidden rounded-[1.75rem] border p-6 sm:p-8 shadow-sm mb-6
            {{ $isOverdue ? 'bg-red-50 border-red-200' : 'bg-amber-50 border-amber-200' }}">
            <div class="relative z-10 flex flex-col sm:flex-row gap-6 items-start sm:items-center">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl shadow-inner
                    {{ $isOverdue ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-600' }}">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-black mb-2 {{ $isOverdue ? 'text-red-900' : 'text-amber-900' }}">
                        {{ $isOverdue ? 'Cicilan Menunggak! ⚠️' : 'Pengingat Jatuh Tempo Cicilan 📅' }}
                    </h3>
                    <p class="text-sm font-medium leading-relaxed mb-4 {{ $isOverdue ? 'text-red-800' : 'text-amber-800' }}">
                        @if($isOverdue)
                            Anda telah melewati batas waktu pembayaran cicilan bulan ini (Jatuh tempo: {{ $jatuhTempo->format('d M Y') }}). Mohon segera lakukan pembayaran sebesar <strong>Rp {{ number_format($pembayaran->nominal_per_cicilan, 0, ',', '.') }}</strong> agar akun Anda tetap aktif dan tidak dinonaktifkan oleh sistem.
                        @else
                            Tagihan cicilan Anda sebesar <strong>Rp {{ number_format($pembayaran->nominal_per_cicilan, 0, ',', '.') }}</strong> akan jatuh tempo pada <strong>{{ $jatuhTempo->format('d M Y') }}</strong> ({{ ceil($sisaHari) }} hari lagi).
                        @endif
                    </p>
                    <a href="{{ route('siswa.pembayaran.index') }}" class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-xs font-bold text-white shadow-lg transition
                        {{ $isOverdue ? 'bg-red-600 shadow-red-600/20 hover:bg-red-700' : 'bg-amber-600 shadow-amber-600/20 hover:bg-amber-700' }}">
                        Bayar Sekarang
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                </div>
            </div>
        </div>
        @endif
    @endif

    {{-- Stats — HANYA MUNCUL JIKA SUDAH AKTIF --}}
    @if($isAktif)
    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
        <div class="relative bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 border border-slate-100 dark:border-[#388782]/30 rounded-2xl p-6 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 overflow-hidden group">
            <div class="relative z-10 flex items-start gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-[#A2D5CB] text-[#388782] group-hover:scale-105 transition-transform">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <span class="block text-xs font-bold uppercase tracking-widest text-slate-400">Kehadiran bulan ini</span>
                    <span class="mt-1 block text-3xl font-black tabular-nums text-slate-800 dark:text-slate-100">{{ $totalHadir }}</span>
                    <span class="text-sm font-medium text-slate-400">hari hadir</span>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-[#78BBB0] to-[#388782] scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500 rounded-b-2xl"></div>
        </div>

        <div class="relative bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 border border-slate-100 dark:border-[#388782]/30 rounded-2xl p-6 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 overflow-hidden group">
            <div class="relative z-10 flex items-start gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600 dark:bg-red-950/50 dark:text-red-400 group-hover:scale-105 transition-transform">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <div>
                    <span class="block text-xs font-bold uppercase tracking-widest text-slate-400">Alpha</span>
                    <span class="mt-1 block text-3xl font-black tabular-nums text-slate-800 dark:text-slate-100">{{ $totalAlpha ?? 0 }}</span>
                    <span class="text-sm font-medium text-slate-400">tanpa keterangan</span>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-red-300 to-red-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500 rounded-b-2xl opacity-80"></div>
        </div>

        <div class="relative bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 border border-slate-100 dark:border-[#388782]/30 rounded-2xl p-6 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 overflow-hidden group">
            <div class="relative z-10 flex items-start gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 group-hover:scale-105 transition-transform">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                </div>
                <div>
                    <span class="block text-xs font-bold uppercase tracking-widest text-slate-400">Status paket</span>
                    <span class="mt-1 block text-xl font-black text-slate-800 dark:text-slate-100">{{ $peserta->paketBimbingan ? 'Aktif' : 'Belum di-set' }}</span>
                    <span class="text-sm font-medium text-slate-400">bimbingan</span>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-[#78BBB0] to-[#388782] scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500 rounded-b-2xl"></div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Data pribadi — MUNCUL UNTUK SEMUA --}}
        <div class="rounded-3xl border border-slate-100 bg-white p-5 sm:p-8 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-4 sm:mb-6 flex items-center justify-between">
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-800 dark:text-slate-100">Informasi pribadi</h2>
                @if($isAktif)
                    <span class="rounded-xl bg-emerald-100 px-3 py-1 text-[10px] sm:text-xs font-black uppercase text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Aktif</span>
                @else
                    <span class="rounded-xl bg-amber-100 px-3 py-1 text-[10px] sm:text-xs font-black uppercase text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">Pending</span>
                @endif
            </div>
            <dl class="space-y-4">
                <div class="border-b border-slate-100 pb-3 dark:border-slate-700">
                    <dt class="text-[10px] font-black uppercase tracking-wider text-slate-400">Nama lengkap</dt>
                    <dd class="mt-1 font-bold text-slate-700 dark:text-slate-200">{{ $peserta?->nama_lengkap ?? auth()->user()->name }}</dd>
                </div>
                <div class="border-b border-slate-100 pb-3 dark:border-slate-700">
                    <dt class="text-[10px] font-black uppercase tracking-wider text-slate-400">Asal sekolah</dt>
                    <dd class="mt-1 font-bold text-slate-700 dark:text-slate-200">{{ $peserta?->asal_sekolah ?? '—' }}</dd>
                </div>
                <div class="border-b border-slate-100 pb-3 dark:border-slate-700">
                    <dt class="text-[10px] font-black uppercase tracking-wider text-slate-400">Paket bimbingan</dt>
                    <dd class="mt-1 font-bold text-slate-700 dark:text-slate-200">{{ $peserta?->paketBimbingan?->nama_paket ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] font-black uppercase tracking-wider text-slate-400">Kelompok belajar</dt>
                    <dd class="mt-1 font-bold text-slate-700 dark:text-slate-200">{{ $peserta?->kelompokBelajar?->nama_kelompok ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Aduan & Bantuan — MUNCUL UNTUK SEMUA --}}
        <div class="group relative overflow-hidden rounded-3xl border border-slate-100 bg-white p-5 sm:p-8 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 flex flex-col justify-between">
            <div class="absolute -right-12 -top-12 h-40 w-40 rounded-full bg-emerald-500/10 blur-3xl transition group-hover:bg-emerald-500/20"></div>
            <div class="relative z-10">
                <div class="mb-4 sm:mb-6 flex h-16 w-16 sm:h-20 sm:w-20 rotate-3 items-center justify-center rounded-2xl sm:rounded-3xl bg-emerald-500 text-white shadow-lg shadow-emerald-950/25 transition group-hover:rotate-0">
                    <svg class="h-8 w-8 sm:h-10 sm:w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-slate-100 mb-2">Aduan & Keluhan</h3>
                <p class="text-xs sm:text-sm text-slate-400 dark:text-slate-500 leading-relaxed mb-6 font-medium">
                    Apakah Anda mengalami kendala belajar, sistem error, atau masalah verifikasi pembayaran? Sampaikan keluhan langsung via WhatsApp.
                </p>
            </div>
            <div class="relative z-10">
                @php
                    $master = \App\Models\Master::first();
                    $waNum = $master?->wa_number ?? '6281234567890';
                    $namaSiswa = $peserta?->nama_lengkap ?? auth()->user()->name;
                    $nisnSiswa = $peserta?->nisn ?? '-';
                    $pesanAduan = "Halo Admin, saya " . $namaSiswa . " (NISN: " . $nisnSiswa . "), ingin menanyakan/menyampaikan...";
                    $waUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $waNum) . "?text=" . urlencode($pesanAduan);
                @endphp
                <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-xl sm:rounded-2xl bg-emerald-500 px-6 py-3.5 text-xs sm:text-sm font-bold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-600 hover:shadow-xl w-full">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    Hubungi via WhatsApp
                </a>
            </div>
        </div>

        @if($isAktif)
        {{-- QR --}}
        <div class="group relative overflow-hidden rounded-3xl border border-slate-100 bg-white p-5 sm:p-8 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="absolute -right-12 -top-12 h-40 w-40 rounded-full bg-[#388782]/10 blur-3xl transition group-hover:bg-[#388782]/20"></div>
            <div class="relative z-10 text-center">
                <div class="mx-auto mb-4 sm:mb-6 flex h-16 w-16 sm:h-20 sm:w-20 rotate-3 items-center justify-center rounded-2xl sm:rounded-3xl bg-[#388782] text-white shadow-lg shadow-teal-900/25 transition group-hover:rotate-0">
                    <svg class="h-8 w-8 sm:h-10 sm:w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                </div>
                <div class="mb-1 text-xl sm:text-2xl font-black tracking-[0.2em] text-slate-800 dark:text-slate-100">{{ $peserta->nisn ?? '—' }}</div>
                <div class="mb-6 sm:mb-8 text-[10px] sm:text-xs font-bold uppercase tracking-widest text-slate-400">NISN / kode identitas</div>
                <a href="{{ route('siswa.qr.show') }}" class="inline-flex items-center justify-center gap-2 rounded-xl sm:rounded-2xl bg-[#388782] px-6 py-3 sm:px-8 sm:py-3.5 text-xs sm:text-sm font-bold text-white shadow-lg shadow-teal-900/20 transition hover:bg-[#2f736e] hover:shadow-xl w-full sm:w-auto">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    Tampilkan QR absensi
                </a>
            </div>
        </div>

        {{-- Absensi --}}
        <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex flex-col gap-1 border-b border-slate-100 px-5 sm:px-6 py-4 sm:py-5 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-800 dark:text-slate-100">Riwayat absensi terakhir</h2>
                <span class="text-[10px] sm:text-xs font-bold text-slate-400">5 entri teratas</span>
            </div>

            @if($absensis->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[500px] text-left">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:bg-slate-900/60">
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Jam masuk</th>
                                <th class="px-6 py-4">Jam pulang</th>
                                <th class="px-6 py-4 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($absensis->take(5) as $a)
                            <tr class="transition-colors hover:bg-slate-50/80 dark:hover:bg-slate-700/40">
                                <td class="px-6 py-4 font-bold text-slate-700 dark:text-slate-200">{{ $a->tanggal->format('d M Y') }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-600 dark:text-slate-400">{{ $a->jam_masuk ? substr($a->jam_masuk, 0, 5) : '—' }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-600 dark:text-slate-400">{{ $a->jam_pulang ? substr($a->jam_pulang, 0, 5) : '—' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-flex rounded-full px-3 py-1 text-[10px] font-black uppercase tracking-wider
                                        {{ $a->status_masuk === 'hadir' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' :
                                           ($a->status_masuk === 'alpha' ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200') }}">
                                        {{ $a->status_masuk }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-14 text-center">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-700">
                        <svg class="h-7 w-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </div>
                    <p class="font-bold text-slate-600 dark:text-slate-300">Belum ada data absensi</p>
                    <p class="mt-1 text-sm text-slate-400">Data akan muncul setelah presensi tercatat.</p>
                </div>
            @endif
        </div>

        {{-- Ujian yang tersedia --}}
        <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex flex-col gap-1 border-b border-slate-100 px-6 py-5 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-extrabold text-slate-800 dark:text-slate-100">Ujian yang tersedia</h2>
                <span class="text-xs font-bold text-[#388782]">{{ $ujianAktif->count() }} tersedia</span>
            </div>

            @if($ujianAktif->count() > 0)
                <div class="p-4 space-y-3">
                    @foreach($ujianAktif->take(5) as $ujian)
                        <a href="{{ route('siswa.ujian.show', $ujian->id) }}" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 hover:bg-teal-50 dark:hover:bg-teal-900/20 border border-transparent hover:border-teal-200 dark:hover:border-teal-800 transition-all group">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white dark:bg-zinc-900 text-[#388782] shadow-sm group-hover:scale-110 transition-transform">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-slate-800 dark:text-slate-100 truncate">{{ $ujian->judul }}</h3>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-0.5">
                                    {{ $ujian->durasi }} Menit • {{ $ujian->waktu_selesai ? $ujian->waktu_selesai->diffForHumans() : 'Tanpa batas waktu' }}
                                </p>
                            </div>
                            <div class="text-slate-300 group-hover:text-[#388782] transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                            </div>
                        </a>
                    @endforeach
                    @if($ujianAktif->count() > 5)
                        <div class="pt-2">
                            <a href="{{ route('siswa.ujian.index') }}" class="flex items-center justify-center gap-2 rounded-xl bg-slate-100 dark:bg-slate-800 px-4 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                                Lihat semua ujian
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div class="px-6 py-14 text-center">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-700">
                        <svg class="h-7 w-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <p class="font-bold text-slate-600 dark:text-slate-300">Tidak ada ujian aktif</p>
                    <p class="mt-1 text-sm text-slate-400">Semua ujian telah diselesaikan atau belum tersedia.</p>
                </div>
            @endif
        </div>
        @endif
        {{-- AKHIR DARI BLOK @if($isAktif) --}}

    </div>
</div>
@endsection