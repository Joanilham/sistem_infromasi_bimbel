@php
    $user = Auth::user();
    $pesertaDidik = $user->pesertaDidik;
    $isPending = strtolower($user->status) !== 'aktif';
    $pembayaranOverdue = false;
    $pembayaranBelumLunas = false;
    $kekurangan = 0;
    
    if ($pesertaDidik && !$isPending) {
        $statusPembayaran = $pesertaDidik->getStatusPembayaran();
        $pembayaranOverdue = $statusPembayaran['is_locked'];
        $pembayaranBelumLunas = $statusPembayaran['kekurangan'] > 0;
        $kekurangan = $statusPembayaran['kekurangan'];
    }

    $isRestricted = $isPending || $pembayaranOverdue;

    $alertTitle = $isPending ? 'Akun Belum Diverifikasi' : 'Akses Ditangguhkan!';
    $alertText = $isPending 
        ? 'Harap tunggu, akun Anda sedang dalam proses verifikasi oleh Admin. Menu kelas belum bisa diakses.'
        : 'Tagihan paket Anda belum dilunasi dan durasi bimbingan hampir habis / terlampaui. Silakan melunasi tagihan di menu Pembayaran.';
@endphp

<!-- Sidebar Header -->
<div class="flex items-center justify-center h-20 border-b border-slate-100 dark:border-zinc-800 px-4 bg-white dark:bg-zinc-950">
    <a href="{{ route('siswa.dashboard') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
        @if($masterData && $masterData->hasCustomLogo())
        <div class="w-9 h-9 rounded-xl overflow-hidden shadow-md border border-slate-100 dark:border-zinc-700 shrink-0">
            <img src="{{ asset('storage/' . $masterData->logo) }}" alt="Logo" class="w-full h-full object-contain bg-white rounded-xl">
        </div>
        @else
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-md shadow-emerald-500/30 shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
        </div>
        @endif
        @if($masterData && $masterData->nama_lembaga)
        <span class="text-base font-extrabold text-slate-800 dark:text-white tracking-tight leading-tight max-w-[130px] truncate">
            {{ $masterData->nama_lembaga }}
        </span>
        @else
        <span class="text-base font-extrabold text-slate-800 dark:text-white tracking-tight leading-tight max-w-[130px] truncate">{{ config('app.name') }}</span>
        @endif
    </a>
</div>

<!-- Sidebar Content -->
<div id="sidebar-scroll-container" class="flex-1 min-h-0 overflow-y-auto p-4 pb-28 custom-scrollbar bg-white dark:bg-zinc-950">
    <nav class="space-y-1 mb-6">
        @if($isRestricted)
        <a href="javascript:void(0)" onclick="Swal.fire({icon: 'warning', title: '{{ $alertTitle }}', text: '{{ $alertText }}', confirmButtonColor: '#d33'})"
            class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 text-slate-400 dark:text-zinc-600 opacity-60 cursor-not-allowed group">
            <svg class="w-5 h-5 mr-3 shrink-0 text-slate-400 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-sm font-semibold">Dashboard</span>
            <svg class="w-4 h-4 text-amber-500 ml-auto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </a>
        @else
        <a href="{{ route('siswa.dashboard') }}" aria-label="Dashboard"
            class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('siswa.dashboard') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
            <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('siswa.dashboard') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-sm">Dashboard</span>
        </a>
        @endif
    </nav>

    @if(!$isPending)
    <!-- MENU BELAJAR -->
    <div class="mb-6">
        <h3 class="px-3 text-[10px] font-bold uppercase text-slate-400 dark:text-zinc-500 tracking-widest mb-2">MENU BELAJAR</h3>
        <nav class="space-y-1">
            {{-- Jadwal Pelajaran --}}
            @if($isRestricted)
            <a href="javascript:void(0)" onclick="Swal.fire({icon: 'warning', title: '{{ $alertTitle }}', text: '{{ $alertText }}', confirmButtonColor: '#d33'})"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 text-slate-400 dark:text-zinc-600 opacity-60 cursor-not-allowed group">
                <svg class="w-5 h-5 mr-3 shrink-0 text-slate-400 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-sm font-semibold">Jadwal Pelajaran</span>
                <svg class="w-4 h-4 text-amber-500 ml-auto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </a>
            @else
            <a href="{{ route('siswa.jadwal.index') }}" aria-label="Jadwal Pelajaran"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('siswa.jadwal.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('siswa.jadwal.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-sm">Jadwal Pelajaran</span>
            </a>
            @endif

            {{-- Ujian Online --}}
            @if($isRestricted)
            <a href="javascript:void(0)" onclick="Swal.fire({icon: 'warning', title: '{{ $alertTitle }}', text: '{{ $alertText }}', confirmButtonColor: '#d33'})"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 text-slate-400 dark:text-zinc-600 opacity-60 cursor-not-allowed group">
                <svg class="w-5 h-5 mr-3 shrink-0 text-slate-400 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span class="text-sm font-semibold">Ujian Online</span>
                <svg class="w-4 h-4 text-amber-500 ml-auto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </a>
            @else
            <a href="{{ route('siswa.ujian.index') }}" aria-label="Ujian Online"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('siswa.ujian.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('siswa.ujian.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span class="text-sm">Ujian Online</span>
            </a>
            @endif

            {{-- Nilai Saya --}}
            @if($isRestricted)
            <a href="javascript:void(0)" onclick="Swal.fire({icon: 'warning', title: '{{ $alertTitle }}', text: '{{ $alertText }}', confirmButtonColor: '#d33'})"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 text-slate-400 dark:text-zinc-600 opacity-60 cursor-not-allowed group">
                <svg class="w-5 h-5 mr-3 shrink-0 text-slate-400 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span class="text-sm font-semibold">Nilai Saya</span>
                <svg class="w-4 h-4 text-amber-500 ml-auto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </a>
            @else
            <a href="{{ route('siswa.hasil.index') }}" aria-label="Nilai Saya"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('siswa.hasil.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('siswa.hasil.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span class="text-sm">Nilai Saya</span>
            </a>
            @endif
        </nav>
    </div>
    @endif

    @if(!$isPending)
    <!-- MENU KEUANGAN -->
    <div class="mb-6">
        <h3 class="px-3 text-[10px] font-bold uppercase text-slate-400 dark:text-zinc-500 tracking-widest mb-2">MENU KEUANGAN</h3>
        <nav class="space-y-1">
            <a href="{{ route('siswa.pembayaran.index') }}" aria-label="Pembayaran"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('siswa.pembayaran.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold shadow-xs' : 'text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('siswa.pembayaran.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-zinc-500 group-hover:text-slate-600 dark:group-hover:text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="text-sm">Pembayaran</span>
            </a>
        </nav>
    </div>
    @endif

    <!-- BANTUAN -->
    <div class="mb-6">
        <h3 class="px-3 text-[10px] font-bold uppercase text-slate-400 dark:text-zinc-500 tracking-widest mb-2">BANTUAN</h3>
        <nav class="space-y-1">
            @php
                $master = \App\Models\MasterData\Master::first();
                $waNum = $master?->wa_number ?? '6281234567890';
                $pesertaDidik = Auth::user()->pesertaDidik;
                $pesanAduan = "Halo Admin, saya " . ($pesertaDidik?->nama_lengkap ?? Auth::user()->name) . " (NISN: " . ($pesertaDidik?->nisn ?? '-') . ") ingin mengajukan keluhan / aduan.";
                $waUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $waNum) . "?text=" . urlencode($pesanAduan);
            @endphp
            <a href="{{ $waUrl }}" target="_blank" aria-label="Aduan & Keluhan"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-150 group text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 hover:text-slate-900 dark:hover:text-white font-semibold">
                <svg class="w-5 h-5 mr-3 shrink-0 text-emerald-500 group-hover:text-emerald-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span class="text-sm">Aduan & Keluhan</span>
            </a>
        </nav>
    </div>

</div>
<script>
(function(){
    var s = document.getElementById('sidebar-scroll-container');
    var v = localStorage.getItem('sidebarScrollTop');
    if (s && v) s.scrollTop = parseInt(v, 10);
})();
</script>
