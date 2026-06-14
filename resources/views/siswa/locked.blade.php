@extends('layouts.siswa')

@section('title', 'Akses Terbatas')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-[#FCFBF4] shadow-2xl rounded-[2.5rem] p-8 sm:p-10 text-center relative overflow-hidden border border-amber-100">
        
        <!-- Background Ornaments -->
        <div class="absolute -top-20 -right-20 w-48 h-48 bg-amber-400/20 blur-3xl rounded-full"></div>
        <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-amber-200/30 blur-3xl rounded-full"></div>

        <!-- Lock Icon -->
        <div class="relative z-10 flex justify-center mb-8">
            <div class="w-24 h-24 bg-amber-500 rounded-full flex items-center justify-center shadow-lg shadow-amber-500/30">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
        </div>

        <!-- Heading -->
        <div class="relative z-10 mb-6">
            <span class="inline-block px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold tracking-widest uppercase rounded-full mb-4">
                Akses Terbatas
            </span>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-800 leading-tight mb-4">
                Halaman dan Fitur Anda Dikunci Sementara
            </h1>
            <p class="text-sm sm:text-base text-slate-500 font-medium leading-relaxed">
                Untuk menjamin kelancaran administrasi bimbingan, akses menu <strong class="text-slate-700">Pelajaran, Absensi, & Evaluasi Ujian</strong> ditangguhkan sementara sampai sisa tagihan angsuran yang telah melewati jatuh tempo dilunasi.
            </p>
        </div>

        <!-- Bill Card -->
        <div class="relative z-10 bg-white rounded-3xl p-6 shadow-sm border border-slate-100 mb-8">
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Sisa Tagihan Anda</div>
            <div class="text-3xl sm:text-4xl font-black text-amber-500 mb-4">
                Rp {{ number_format($pembayaran ? $pembayaran->kekurangan : 0, 0, ',', '.') }}
            </div>
            <div class="flex items-center justify-center gap-2 text-xs font-medium text-slate-500">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Status Pembayaran: <span class="text-red-500 font-bold">Belum Lunas</span>
            </div>
        </div>

        <!-- Action Button -->
        <div class="relative z-10">
            <a href="{{ route('siswa.pembayaran.index') }}" class="block w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-emerald-500/30 transition-all hover:-translate-y-1">
                Lakukan Pembayaran
            </a>
            <p class="mt-4 text-xs text-slate-400">
                Akses akan otomatis terbuka setelah pembayaran dikonfirmasi.
            </p>
        </div>
        
    </div>
</div>
@endsection
