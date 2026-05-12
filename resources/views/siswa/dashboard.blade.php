@extends('layouts.siswa')

@section('title', 'Dashboard')

@section('content')
@php
    $namaDepan = explode(' ', $peserta->nama_lengkap)[0];
@endphp

<div class="space-y-8 pb-12">

    {{-- Hero — pola warna & spacing mengikuti dashboard guru --}}
    <div class="relative mb-2 rounded-[1.75rem] overflow-hidden shadow-2xl bg-gradient-to-br from-[#388782] via-[#206D6C] to-[#0F5253]">
        <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-16 w-80 h-80 rounded-full bg-[#388782]/25 blur-3xl pointer-events-none"></div>
        <div class="absolute inset-0 opacity-[0.05]" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;"></div>

        <div class="relative z-10 p-6 md:p-10 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8">
            <div class="flex-1 min-w-0">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-5 mt-0.5">
                    <span class="w-2 h-2 rounded-full bg-[#A2D5CB]"></span>
                    <span class="text-xs font-semibold tracking-widest text-[#A2D5CB] uppercase">Ringkasan hari ini</span>
                </div>
                <h1 class="text-2xl md:text-4xl font-extrabold text-white leading-tight tracking-tight mb-3">
                    Halo, {{ $namaDepan }}! 👋
                </h1>
                <p class="text-[#A2D5CB] text-sm md:text-base max-w-xl leading-relaxed font-medium">
                    Pantau kehadiran, paket bimbingan, dan QR absensi Anda di satu tempat.
                </p>
            </div>
            <div class="flex flex-wrap gap-3 shrink-0">
                <span class="rounded-2xl bg-white/15 border border-white/20 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur-sm">{{ $peserta->kelompokBelajar?->nama_kelompok ?? 'Rombel' }}</span>
                <span class="rounded-2xl bg-white/10 px-4 py-2.5 text-sm font-semibold text-[#A2D5CB]">{{ $peserta->paketBimbingan?->nama_paket ?? 'Paket' }}</span>
            </div>
        </div>
    </div>

    {{-- Stats — kartu gradasi seperti guru --}}
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

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- QR --}}
        <div class="group relative overflow-hidden rounded-3xl border border-slate-100 bg-white p-8 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="absolute -right-12 -top-12 h-40 w-40 rounded-full bg-[#388782]/10 blur-3xl transition group-hover:bg-[#388782]/20"></div>
            <div class="relative z-10 text-center">
                <div class="mx-auto mb-6 flex h-20 w-20 rotate-3 items-center justify-center rounded-3xl bg-[#388782] text-white shadow-lg shadow-teal-900/25 transition group-hover:rotate-0">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                </div>
                <div class="mb-1 text-2xl font-black tracking-[0.2em] text-slate-800 dark:text-slate-100">{{ $peserta->nisn ?? '—' }}</div>
                <div class="mb-8 text-xs font-bold uppercase tracking-widest text-slate-400">NISN / kode identitas</div>
                <a href="{{ route('siswa.qr.show') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#388782] px-8 py-3.5 text-sm font-bold text-white shadow-lg shadow-teal-900/20 transition hover:bg-[#2f736e] hover:shadow-xl">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    Tampilkan QR absensi
                </a>
            </div>
        </div>

        {{-- Data pribadi --}}
        <div class="rounded-3xl border border-slate-100 bg-white p-8 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-xl font-extrabold text-slate-800 dark:text-slate-100">Informasi pribadi</h2>
                <span class="rounded-xl bg-emerald-100 px-3 py-1 text-xs font-black uppercase text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Aktif</span>
            </div>
            <dl class="space-y-4">
                <div class="border-b border-slate-100 pb-3 dark:border-slate-700">
                    <dt class="text-[10px] font-black uppercase tracking-wider text-slate-400">Nama lengkap</dt>
                    <dd class="mt-1 font-bold text-slate-700 dark:text-slate-200">{{ $peserta->nama_lengkap }}</dd>
                </div>
                <div class="border-b border-slate-100 pb-3 dark:border-slate-700">
                    <dt class="text-[10px] font-black uppercase tracking-wider text-slate-400">Asal sekolah</dt>
                    <dd class="mt-1 font-bold text-slate-700 dark:text-slate-200">{{ $peserta->asal_sekolah ?? '—' }}</dd>
                </div>
                <div class="border-b border-slate-100 pb-3 dark:border-slate-700">
                    <dt class="text-[10px] font-black uppercase tracking-wider text-slate-400">Paket bimbingan</dt>
                    <dd class="mt-1 font-bold text-slate-700 dark:text-slate-200">{{ $peserta->paketBimbingan?->nama_paket ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] font-black uppercase tracking-wider text-slate-400">Kelompok belajar</dt>
                    <dd class="mt-1 font-bold text-slate-700 dark:text-slate-200">{{ $peserta->kelompokBelajar?->nama_kelompok ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Absensi --}}
        <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900 lg:col-span-2">
            <div class="flex flex-col gap-1 border-b border-slate-100 px-6 py-5 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-extrabold text-slate-800 dark:text-slate-100">Riwayat absensi terakhir</h2>
                <span class="text-xs font-bold text-slate-400">5 entri teratas</span>
            </div>

            @if($absensis->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[560px] text-left">
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
    </div>
</div>
@endsection
