@extends('layouts.siswa')

@section('title', 'Riwayat Ujian & Nilai')

@section('content')
<div class="max-w-5xl mx-auto space-y-10 pb-20">

    {{-- Header & Stats Summary --}}
    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">Riwayat & Nilai</h1>
                <p class="text-slate-500 dark:text-zinc-400 mt-2 font-medium">Pantau perkembangan belajarmu dari hasil ujian sebelumnya.</p>
            </div>
            <a href="{{ route('siswa.ujian.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl text-sm font-black text-white bg-[#388782] hover:bg-[#2D6A66] transition-all shadow-xl shadow-[#388782]/20 hover:scale-[1.02] active:scale-95">
                Ke halaman Ujian
            </a>
        </div>

    @if(!$pembayaranBelumLunas)
        @if($pesertas->count() > 0)
            @php
                $selesai = $pesertas->where('status', 'selesai');
                $avgSkor = $selesai->avg('skor');
                $maxSkor = $selesai->max('skor');
                $totalLulus = $selesai->where('skor', '>=', 75)->count();
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="relative overflow-hidden group p-8 rounded-[2.5rem] bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 shadow-sm transition-all hover:shadow-xl hover:shadow-slate-200/50 dark:hover:shadow-none">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#388782]/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Rata-rata Skor</p>
                    <div class="flex items-end gap-2">
                        <span class="text-5xl font-black text-[#388782] tracking-tighter">{{ number_format($avgSkor ?? 0, 0) }}</span>
                        <span class="text-sm font-bold text-slate-300 mb-2">/ 100</span>
                    </div>
                </div>
                <div class="relative overflow-hidden group p-8 rounded-[2.5rem] bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 shadow-sm transition-all hover:shadow-xl hover:shadow-slate-200/50 dark:hover:shadow-none">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Skor Tertinggi</p>
                    <div class="flex items-end gap-2">
                        <span class="text-5xl font-black text-amber-500 tracking-tighter">{{ number_format($maxSkor ?? 0, 0) }}</span>
                        <span class="text-sm font-bold text-slate-300 mb-2">PTS</span>
                    </div>
                </div>
                <div class="relative overflow-hidden group p-8 rounded-[2.5rem] bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 shadow-sm transition-all hover:shadow-xl hover:shadow-slate-200/50 dark:hover:shadow-none">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Tingkat Kelulusan</p>
                    <div class="flex items-end gap-2">
                        <span class="text-5xl font-black text-emerald-500 tracking-tighter">{{ $pesertas->count() > 0 ? number_format(($totalLulus / $pesertas->count()) * 100, 0) : 0 }}</span>
                        <span class="text-sm font-bold text-slate-300 mb-2">%</span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if($pesertas->count() > 0)
        <div class="space-y-6">
            <div class="flex items-center justify-between px-4">
                <h2 class="text-sm font-black uppercase tracking-widest text-slate-800 dark:text-zinc-200">Daftar Hasil Ujian</h2>
                <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    <span>Terbaru</span>
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </div>

            <div class="grid gap-4">
                @foreach($pesertas as $p)
                <div class="group relative bg-white dark:bg-zinc-900 rounded-[2rem] border border-slate-100 dark:border-zinc-800 overflow-hidden shadow-sm hover:shadow-xl hover:shadow-slate-200/50 dark:hover:shadow-none transition-all duration-300">
                    <div class="p-6 md:p-8 flex flex-col md:flex-row items-center gap-6 md:gap-10">
                        {{-- Score Circle --}}
                        <div class="relative shrink-0 w-20 h-20 rounded-[1.5rem] flex flex-col items-center justify-center font-black transition-transform group-hover:scale-105 duration-500
                            {{ $p->belum_dikoreksi_count > 0 ? 'bg-amber-50 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400' : ($p->status === 'selesai' ? ($p->skor >= 75 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400' : ($p->skor >= 50 ? 'bg-amber-50 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400' : 'bg-red-50 text-red-500 dark:bg-red-900/20 dark:text-red-400')) : 'bg-slate-50 dark:bg-zinc-800 text-slate-400') }}">
                            @if($p->belum_dikoreksi_count > 0)
                                <svg class="w-6 h-6 mb-1 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="text-[8px] uppercase tracking-widest opacity-80 text-center leading-tight">Menunggu<br>Koreksi</span>
                            @else
                                <span class="text-2xl leading-none">{{ number_format($p->skor, 0) }}</span>
                                <span class="text-[8px] uppercase tracking-widest opacity-60 mt-1">Skor</span>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 text-center md:text-left space-y-2">
                            <div class="flex flex-wrap justify-center md:justify-start items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-lg bg-slate-50 dark:bg-zinc-800 text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest">
                                    {{ $p->ujian->mode }}
                                </span>
                                <span class="text-[10px] font-bold text-slate-300 dark:text-zinc-600">•</span>
                                <span class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 italic">{{ $p->waktu_mulai?->diffForHumans() }}</span>
                            </div>
                            <h3 class="text-lg font-black text-slate-800 dark:text-zinc-100 tracking-tight leading-tight">{{ $p->ujian->judul }}</h3>
                            <div class="flex flex-wrap justify-center md:justify-start items-center gap-4 text-xs font-bold text-slate-400 dark:text-zinc-500">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    {{ $p->waktu_mulai?->format('d M Y') }}
                                </span>
                                @if($p->waktu_mulai && $p->waktu_selesai)
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ $p->waktu_mulai->diff($p->waktu_selesai)->format('%H:%I:%S') }}
                                </span>
                                @endif
                            </div>
                        </div>

                        {{-- Action --}}
                        <div class="shrink-0 w-full md:w-auto">
                            <a href="{{ route('siswa.ujian.hasil', $p->id) }}" class="flex items-center justify-center gap-3 w-full px-8 py-3.5 rounded-2xl bg-slate-50 dark:bg-zinc-800 text-[#388782] dark:text-[#5EEAD4] font-black text-sm transition-all hover:bg-[#388782] hover:text-white hover:shadow-lg hover:shadow-[#388782]/20">
                                Analisis Hasil
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7-7 7" /></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="pt-10">
                {{ $pesertas->links() }}
            </div>
        </div>
    @else
        <div class="py-20 flex flex-col items-center justify-center text-center space-y-6">
            <div class="relative">
                <div class="absolute inset-0 bg-[#388782]/10 blur-3xl rounded-full"></div>
                <div class="relative w-32 h-32 rounded-[2.5rem] bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 flex items-center justify-center text-slate-200 dark:text-zinc-800">
                    <svg class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
            </div>
            <div class="max-w-sm">
                <h3 class="text-xl font-black text-slate-800 dark:text-zinc-200 tracking-tight">Belum Ada Riwayat</h3>
                <p class="text-sm text-slate-500 dark:text-zinc-500 mt-2 font-medium">Sepertinya kamu belum pernah menyelesaikan ujian apapun. Yuk, mulai belajar sekarang!</p>
            </div>
            <a href="{{ route('siswa.ujian.index') }}" class="inline-flex items-center justify-center gap-3 px-10 py-4 rounded-2xl text-base font-black text-white bg-gradient-to-r from-[#388782] to-[#206D6C] shadow-2xl shadow-[#388782]/30 hover:scale-[1.02] active:scale-95 transition-all">
                Pilih Ujian Pertama
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
            </a>
        </div>
    @endif
    @else
        @php
            $master = \App\Models\MasterData\Master::first();
            $waNum = $master?->wa_number ?? '6281234567890';
            $pesertaDidik = Auth::user()->pesertaDidik;
            $pesanKeuangan = "Halo Admin Keuangan, saya " . ($pesertaDidik?->nama_lengkap ?? Auth::user()->name) . " (NISN: " . ($pesertaDidik?->nisn ?? $pesertaDidik?->nomor_induk ?? '-') . ") ingin menanyakan / mengonfirmasi mengenai kekurangan tagihan saya sebesar Rp " . number_format($kekurangan, 0, ',', '.') . ". Mohon bantuannya.";
            $waKeuanganUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $waNum) . "?text=" . urlencode($pesanKeuangan);
        @endphp

        <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-amber-50/90 to-orange-50/90 dark:from-zinc-900/90 dark:to-zinc-950/90 border border-amber-200/50 dark:border-amber-500/20 p-8 md:p-12 shadow-2xl backdrop-blur-xl flex flex-col items-center text-center space-y-8">
            {{-- Glowing Background Effects --}}
            <div class="absolute -left-20 -top-20 w-60 h-60 bg-amber-500/10 dark:bg-amber-500/5 rounded-full blur-3xl"></div>
            <div class="absolute -right-20 -bottom-20 w-60 h-60 bg-yellow-500/10 dark:bg-yellow-500/5 rounded-full blur-3xl"></div>

            {{-- Golden Glowing Lock Icon --}}
            <div class="relative group">
                <div class="absolute inset-0 bg-gradient-to-tr from-amber-500 to-yellow-400 rounded-full blur-xl opacity-40 group-hover:opacity-60 transition duration-700 animate-pulse"></div>
                <div class="relative w-24 h-24 rounded-full bg-gradient-to-tr from-amber-500 to-yellow-400 flex items-center justify-center text-white shadow-xl shadow-amber-500/20 transform group-hover:scale-110 transition duration-500">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
            </div>

            {{-- Content Texts --}}
            <div class="max-w-xl space-y-3 relative">
                <span class="px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 text-[10px] font-black uppercase tracking-[0.2em]">Akses Terbatas</span>
                <h2 class="text-2xl md:text-3xl font-black text-slate-800 dark:text-zinc-100 tracking-tight leading-tight">
                    Halaman Nilai dan Evaluasi Anda Dikunci Sementara
                </h2>
                <p class="text-sm text-slate-500 dark:text-zinc-400 font-medium">
                    Untuk menjamin transparansi administrasi bimbingan, akses menu <strong class="text-slate-700 dark:text-zinc-200">Nilai Saya</strong> &amp; <strong class="text-slate-700 dark:text-zinc-200">Analisis Hasil Ujian</strong> ditangguhkan sementara sampai sisa tagihan Anda dilunasi.
                </p>
            </div>

            {{-- Sisa Tagihan Block --}}
            <div class="relative w-full max-w-sm p-6 rounded-3xl bg-white/60 dark:bg-zinc-900/60 border border-white dark:border-zinc-800/80 shadow-md backdrop-blur-md">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-zinc-500 mb-1">Sisa Tagihan Anda</p>
                <p class="text-3xl font-black text-amber-500 tracking-tight">
                    Rp {{ number_format($kekurangan, 0, ',', '.') }}
                </p>
                <div class="mt-3 flex items-center justify-center gap-2 text-xs text-slate-400 dark:text-zinc-500">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Status Pembayaran: Belum Lunas</span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full max-w-md pt-2">
                <a href="{{ route('siswa.pembayaran.index') }}" class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl text-sm font-black text-white bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 transition-all shadow-xl shadow-amber-500/20 hover:scale-[1.02] active:scale-95">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Bayar Tagihan Sekarang
                </a>
                <a href="{{ $waKeuanganUrl }}" target="_blank" class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl text-sm font-black text-slate-600 dark:text-zinc-300 bg-slate-100 dark:bg-zinc-800 hover:bg-slate-200 dark:hover:bg-zinc-700/80 transition-all hover:scale-[1.02] active:scale-95">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Hubungi Admin Keuangan
                </a>
            </div>
        </div>
    @endif

</div>
@endsection

