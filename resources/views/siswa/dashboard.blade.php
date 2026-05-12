@extends('layouts.siswa_cbt')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6 pb-10">
    {{-- Greeting --}}
    <div class="greeting">
        <h1 class="text-3xl font-extrabold" style="color:var(--text-main)">Halo, {{ explode(' ', $peserta->nama_lengkap)[0] }}! 👋</h1>
        <p class="text-sm mt-1" style="color:var(--text-muted)">Berikut adalah ringkasan aktivitas dan data absensi Anda.</p>
    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 p-6 flex items-center gap-4 shadow-sm">
            <div class="w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <span class="block text-[10px] font-black uppercase tracking-wider text-slate-400">Kehadiran (Bulan Ini)</span>
                <span class="text-2xl font-black" style="color:var(--text-main)">{{ $totalHadir }} <span class="text-sm font-medium text-slate-400">Hari</span></span>
            </div>
        </div>
        
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 p-6 flex items-center gap-4 shadow-sm">
            <div class="w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <div>
                <span class="block text-[10px] font-black uppercase tracking-wider text-slate-400">Total Alpha</span>
                <span class="text-2xl font-black" style="color:var(--text-main)">{{ $totalAlpha ?? 0 }} <span class="text-sm font-medium text-slate-400">Hari</span></span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 p-6 flex items-center gap-4 shadow-sm">
            <div class="w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
            </div>
            <div>
                <span class="block text-[10px] font-black uppercase tracking-wider text-slate-400">Status Paket</span>
                <span class="text-xl font-black" style="color:var(--text-main)">{{ $peserta->paketBimbingan ? 'Aktif' : 'Tidak Aktif' }}</span>
            </div>
        </div>
    </div>

    {{-- BENTO GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- QR CODE & GENERATE --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 p-8 text-center relative overflow-hidden group shadow-sm">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-all"></div>
            
            <div class="relative z-10">
                <div class="w-20 h-20 bg-indigo-600 rounded-3xl mx-auto mb-6 flex items-center justify-center text-white shadow-xl rotate-3 group-hover:rotate-0 transition-transform">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                </div>
                <div class="text-2xl font-black tracking-[4px] mb-1" style="color:var(--text-main)">{{ $peserta->nisn ?? '—' }}</div>
                <div class="text-sm font-bold text-slate-400 mb-8 uppercase tracking-widest">NISN / Kode Identitas</div>
                
                <a href="{{ route('siswa.qr.show') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold shadow-lg shadow-indigo-200 dark:shadow-none transition-all hover:scale-105">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    Tampilkan QR Absensi
                </a>
            </div>
        </div>

        {{-- DATA PRIBADI --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-extrabold" style="color:var(--text-main)">Informasi Pribadi</h2>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-black uppercase">Aktif</span>
            </div>
            <div class="space-y-4">
                <div class="flex flex-col gap-1 pb-3 border-b border-slate-100 dark:border-slate-700">
                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Nama Lengkap</span>
                    <span class="font-bold text-slate-700 dark:text-slate-200">{{ $peserta->nama_lengkap }}</span>
                </div>
                <div class="flex flex-col gap-1 pb-3 border-b border-slate-100 dark:border-slate-700">
                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Asal Sekolah</span>
                    <span class="font-bold text-slate-700 dark:text-slate-200">{{ $peserta->asal_sekolah }}</span>
                </div>
                <div class="flex flex-col gap-1 pb-3 border-b border-slate-100 dark:border-slate-700">
                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Paket Bimbingan</span>
                    <span class="font-bold text-slate-700 dark:text-slate-200">{{ $peserta->paketBimbingan?->nama_paket ?? '-' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Kelompok Belajar</span>
                    <span class="font-bold text-slate-700 dark:text-slate-200">{{ $peserta->kelompokBelajar?->nama_kelompok ?? '-' }}</span>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-800 dark:text-white mb-1">{{ $peserta->paketBimbingan ? 'Aktif' : 'Tidak' }}</p>
            <p class="text-xs font-medium text-slate-400">Status bimbingan</p>
        </div>

        {{-- TABEL ABSENSI --}}
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
            <div class="p-8 pb-4">
                <h2 class="text-xl font-extrabold" style="color:var(--text-main)">Riwayat Absensi Terakhir</h2>
            </div>
            
            @if($absensis->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/50">
                                <th class="px-8 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Tanggal</th>
                                <th class="px-8 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Jam Masuk</th>
                                <th class="px-8 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Jam Pulang</th>
                                <th class="px-8 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($absensis->take(5) as $a)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="px-8 py-4 font-bold text-slate-700 dark:text-slate-200">{{ $a->tanggal->format('d M Y') }}</td>
                                <td class="px-8 py-4 font-bold text-slate-600 dark:text-slate-400">{{ $a->jam_masuk ? substr($a->jam_masuk,0,5) : '-' }}</td>
                                <td class="px-8 py-4 font-bold text-slate-600 dark:text-slate-400">{{ $a->jam_pulang ? substr($a->jam_pulang,0,5) : '-' }}</td>
                                <td class="px-8 py-4 text-right">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                        {{ $a->status_masuk === 'hadir' ? 'bg-emerald-100 text-emerald-700' : 
                                           ($a->status_masuk === 'alpha' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                        {{ $a->status_masuk }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center text-slate-400 font-bold">
                    Belum ada data absensi untuk ditampilkan.
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
