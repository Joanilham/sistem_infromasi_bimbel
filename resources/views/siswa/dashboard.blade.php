@extends('layouts.siswa_cbt')

@section('title', 'Dashboard')

@section('content')

<div class="relative mb-8 rounded-3xl overflow-hidden shadow-2xl">
    {{-- Animated gradient background --}}
    <div class="absolute inset-0 bg-gradient-to-br from-[#388782] via-[#206D6C] to-[#0F5253]"></div>

    {{-- Decorative circles --}}
    <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -left-16 w-80 h-80 rounded-full bg-[#388782]/20 blur-3xl pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>

    {{-- Dot grid overlay --}}
    <div class="absolute inset-0 opacity-[0.04]" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;"></div>

    <div class="relative z-10 p-6 md:p-8 flex flex-col justify-between min-h-[200px]">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-4">
                <span class="w-2 h-2 rounded-full bg-[#A2D5CB] animate-pulse"></span>
                <span class="text-xs font-semibold tracking-widest text-[#A2D5CB] uppercase">Dashboard Siswa</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-extrabold text-white leading-snug mb-2">
                Selamat Datang, {{ explode(' ', $peserta->nama_lengkap)[0] }}! 👋
            </h1>
            <p class="text-[#A2D5CB] text-sm md:text-base max-w-2xl leading-relaxed">
                Ringkasan aktivitas dan data absensi Anda di <span class="font-bold text-white bg-white/20 px-2.5 py-1 rounded">{{ $peserta->kelompokBelajar?->nama_kelompok ?? 'Umum' }}</span>
            </p>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 mb-8">
    {{-- Kehadiran --}}
    <div class="relative bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 border border-slate-100 dark:border-[#388782]/30 rounded-2xl p-5 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-[#A2D5CB]/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kehadiran</span>
                <div class="p-2.5 rounded-xl bg-[#A2D5CB] text-[#388782] group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-800 dark:text-white mb-1">{{ $totalHadir }}</p>
            <p class="text-xs font-medium text-slate-400">Hari hadir bulan ini</p>
        </div>
        <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-[#78BBB0] to-[#388782] scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500 rounded-b-2xl"></div>
    </div>

    {{-- Alpha --}}
    <div class="relative bg-gradient-to-br from-white to-red-50/20 dark:from-zinc-900 dark:to-red-900/20 border border-slate-100 dark:border-red-900/30 rounded-2xl p-5 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-red-100/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Alpha</span>
                <div class="p-2.5 rounded-xl bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-800 dark:text-white mb-1">{{ $totalAlpha ?? 0 }}</p>
            <p class="text-xs font-medium text-slate-400">Hari tidak hadir</p>
        </div>
        <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-red-400 to-red-600 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500 rounded-b-2xl"></div>
    </div>

    {{-- Status Paket --}}
    <div class="relative bg-gradient-to-br from-white to-[#78BBB0]/20 dark:from-zinc-900 dark:to-[#388782]/30 border border-slate-100 dark:border-[#388782]/30 rounded-2xl p-5 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-[#A2D5CB]/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Paket</span>
                <div class="p-2.5 rounded-xl bg-[#A2D5CB] text-[#388782] group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-800 dark:text-white mb-1">{{ $peserta->paketBimbingan ? 'Aktif' : 'Tidak' }}</p>
            <p class="text-xs font-medium text-slate-400">Status bimbingan</p>
        </div>
        <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-[#78BBB0] to-[#55A199] scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500 rounded-b-2xl"></div>
    </div>
</div>

{{-- Content Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    {{-- QR Code --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-8 text-center relative overflow-hidden group shadow-sm">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#388782]/10 rounded-full blur-3xl group-hover:bg-[#388782]/20 transition-all"></div>
        
        <div class="relative z-10">
            <div class="w-20 h-20 bg-[#388782] rounded-3xl mx-auto mb-6 flex items-center justify-center text-white shadow-lg rotate-3 group-hover:rotate-0 transition-transform">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
            </div>
            <div class="text-2xl font-black tracking-wider mb-1 text-slate-800 dark:text-white">{{ $peserta->nisn ?? '—' }}</div>
            <div class="text-xs font-bold text-slate-400 mb-6 uppercase tracking-wider">NISN Anda</div>
            
            <a href="{{ route('siswa.qr.show') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#388782] hover:bg-[#206D6C] text-white rounded-xl font-semibold shadow-md hover:shadow-lg transition-all hover:scale-105">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                Tampilkan QR Absensi
            </a>
        </div>
    </div>

    {{-- Data Pribadi --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-8 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-extrabold text-slate-800 dark:text-white">Informasi Pribadi</h2>
            <span class="px-3 py-1.5 bg-[#388782]/20 text-[#388782] dark:text-[#A2D5CB] rounded-lg text-xs font-bold uppercase">Aktif</span>
        </div>
        <div class="space-y-4">
            <div class="pb-3 border-b border-slate-200 dark:border-slate-700">
                <span class="text-xs font-bold uppercase text-slate-400 tracking-wider">Nama Lengkap</span>
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mt-1">{{ $peserta->nama_lengkap }}</p>
            </div>
            <div class="pb-3 border-b border-slate-200 dark:border-slate-700">
                <span class="text-xs font-bold uppercase text-slate-400 tracking-wider">Asal Sekolah</span>
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mt-1">{{ $peserta->asal_sekolah }}</p>
            </div>
            <div class="pb-3 border-b border-slate-200 dark:border-slate-700">
                <span class="text-xs font-bold uppercase text-slate-400 tracking-wider">Paket Bimbingan</span>
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mt-1">{{ $peserta->paketBimbingan?->nama_paket ?? '-' }}</p>
            </div>
            <div>
                <span class="text-xs font-bold uppercase text-slate-400 tracking-wider">Kelompok Belajar</span>
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mt-1">{{ $peserta->kelompokBelajar?->nama_kelompok ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Attendance Table --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
    <div class="p-6 md:p-8 border-b border-slate-200 dark:border-slate-700">
        <h2 class="text-lg font-extrabold text-slate-800 dark:text-white">Riwayat Absensi Terakhir</h2>
    </div>
    
    @if($absensis->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase text-slate-400 tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase text-slate-400 tracking-wider">Jam Masuk</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase text-slate-400 tracking-wider">Jam Pulang</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase text-slate-400 tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @foreach($absensis->take(5) as $a)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-700 dark:text-slate-200">{{ $a->tanggal->format('d M Y') }}</td>
                        <td class="px-6 py-4 font-medium text-slate-600 dark:text-slate-300">{{ $a->jam_masuk ? substr($a->jam_masuk,0,5) : '-' }}</td>
                        <td class="px-6 py-4 font-medium text-slate-600 dark:text-slate-300">{{ $a->jam_pulang ? substr($a->jam_pulang,0,5) : '-' }}</td>
                        <td class="px-6 py-4 text-right">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
                                {{ $a->status_masuk === 'hadir' ? 'bg-[#388782]/20 text-[#388782] dark:text-[#A2D5CB]' : 
                                   ($a->status_masuk === 'alpha' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400') }}">
                                {{ ucfirst($a->status_masuk) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-12 text-center">
            <p class="text-slate-400 font-medium">Belum ada data absensi untuk ditampilkan.</p>
        </div>
    @endif
</div>

@endsection
