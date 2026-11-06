@extends($layout)

@section('title', 'Pusat Notifikasi')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto pb-12">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-zinc-900 p-6 sm:p-7 rounded-3xl border border-slate-200/80 dark:border-zinc-800 shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 text-[11px] font-black rounded-lg bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300">
                    Sistem Pemberitahuan
                </span>
                <span class="text-xs text-slate-400">&bull;</span>
                <span class="text-xs font-semibold text-slate-500 dark:text-zinc-400">LMS & Aktivitas Belajar</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1 tracking-tight">Pusat Notifikasi</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400 mt-1">Daftar riwayat tugas baru, konfirmasi pengumpulan tugas, dan hasil penilaian.</p>
        </div>

        @if(($counts['unread'] ?? 0) > 0)
            <form action="{{ route('notifikasi.lms.tandai_semua', [], false) }}" method="POST" class="shrink-0">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 dark:bg-indigo-950/60 dark:hover:bg-indigo-900/60 dark:text-indigo-300 text-xs font-bold transition-all shadow-xs cursor-pointer active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/50 text-emerald-800 dark:text-emerald-300 text-xs sm:text-sm font-bold flex items-center gap-2.5">
            <svg class="w-5 h-5 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Search & Filter Controls -->
    <div class="bg-white dark:bg-zinc-900 p-4 sm:p-5 rounded-3xl border border-slate-200/80 dark:border-zinc-800 shadow-sm space-y-4">
        <!-- Search Input -->
        <form method="GET" action="{{ route('notifikasi.lms.index', [], false) }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            @if(request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif

            <div class="relative flex-1">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" 
                       name="search" 
                       value="{{ $search ?? '' }}"
                       placeholder="Cari berdasarkan judul tugas, mata pelajaran, atau pesan..."
                       class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm bg-slate-50 dark:bg-zinc-800/80 border border-slate-200 dark:border-zinc-700/80 rounded-2xl text-slate-800 dark:text-zinc-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" 
                        class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-bold transition-all shadow-xs cursor-pointer active:scale-95">
                    Cari
                </button>
                @if(!empty($search))
                    <a href="{{ route('notifikasi.lms.index', ['filter' => request('filter', 'all')], false) }}" 
                       class="px-4 py-2.5 rounded-2xl bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-300 text-xs sm:text-sm font-bold hover:bg-slate-200 dark:hover:bg-zinc-700 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- Category Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-bold scrollbar-none">
            {{-- Tab: Semua --}}
            <a href="{{ route('notifikasi.lms.index', ['filter' => 'all', 'search' => $search], false) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl transition-all shrink-0 {{ ($filter ?? 'all') === 'all' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 hover:bg-slate-200 dark:hover:bg-zinc-700' }}">
                <span>Semua</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ ($filter ?? 'all') === 'all' ? 'bg-indigo-700/80 text-white' : 'bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-zinc-300' }}">
                    {{ $counts['all'] ?? 0 }}
                </span>
            </a>

            {{-- Tab: Belum Dibaca --}}
            <a href="{{ route('notifikasi.lms.index', ['filter' => 'unread', 'search' => $search], false) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl transition-all shrink-0 {{ ($filter ?? '') === 'unread' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 hover:bg-slate-200 dark:hover:bg-zinc-700' }}">
                <span>Belum Dibaca</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ ($filter ?? '') === 'unread' ? 'bg-indigo-700/80 text-white' : 'bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400' }}">
                    {{ $counts['unread'] ?? 0 }}
                </span>
            </a>

            {{-- Tab: Tugas Baru (Siswa & Admin) --}}
            @if(in_array(strtolower(auth()->user()->level ?? ''), ['siswa', 'admin', 'super admin']))
                <a href="{{ route('notifikasi.lms.index', ['filter' => 'tugas_baru', 'search' => $search], false) }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl transition-all shrink-0 {{ ($filter ?? '') === 'tugas_baru' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 hover:bg-slate-200 dark:hover:bg-zinc-700' }}">
                    <span>Tugas Baru</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ ($filter ?? '') === 'tugas_baru' ? 'bg-indigo-700/80 text-white' : 'bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-zinc-300' }}">
                        {{ $counts['tugas_baru'] ?? 0 }}
                    </span>
                </a>

                <a href="{{ route('notifikasi.lms.index', ['filter' => 'tugas_dinilai', 'search' => $search], false) }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl transition-all shrink-0 {{ ($filter ?? '') === 'tugas_dinilai' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 hover:bg-slate-200 dark:hover:bg-zinc-700' }}">
                    <span>Tugas Dinilai</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ ($filter ?? '') === 'tugas_dinilai' ? 'bg-indigo-700/80 text-white' : 'bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-zinc-300' }}">
                        {{ $counts['tugas_dinilai'] ?? 0 }}
                    </span>
                </a>
            @endif

            {{-- Tab: Pengumpulan Siswa (Guru & Admin) --}}
            @if(in_array(strtolower(auth()->user()->level ?? ''), ['guru', 'admin', 'super admin']))
                <a href="{{ route('notifikasi.lms.index', ['filter' => 'tugas_dikumpulkan', 'search' => $search], false) }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl transition-all shrink-0 {{ ($filter ?? '') === 'tugas_dikumpulkan' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 hover:bg-slate-200 dark:hover:bg-zinc-700' }}">
                    <span>Pengumpulan Siswa</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ ($filter ?? '') === 'tugas_dikumpulkan' ? 'bg-indigo-700/80 text-white' : 'bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-zinc-300' }}">
                        {{ $counts['tugas_dikumpulkan'] ?? 0 }}
                    </span>
                </a>
            @endif
        </div>
    </div>

    <!-- Notifications List Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-slate-200/80 dark:border-zinc-800 shadow-sm overflow-hidden">
        @if($notifications->isEmpty())
            <div class="py-16 px-4 text-center">
                <div class="w-16 h-16 mx-auto rounded-3xl bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <h3 class="mt-4 text-base font-black text-slate-800 dark:text-white">Tidak Ada Notifikasi yang Ditemukan</h3>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400 mt-1 max-w-sm mx-auto">
                    @if(!empty($search))
                        Tidak ada pemberitahuan yang cocok dengan kata kunci "{{ $search }}".
                    @elseif(($filter ?? '') === 'unread')
                        Semua notifikasi sudah Anda baca. Bagus sekali!
                    @else
                        Belum ada riwayat aktivitas penugasan atau pengumpulan tugas saat ini.
                    @endif
                </p>
                @if(!empty($search) || ($filter ?? 'all') !== 'all')
                    <div class="mt-5">
                        <a href="{{ route('notifikasi.lms.index', [], false) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 text-xs font-bold hover:bg-slate-200 dark:hover:bg-zinc-700 transition-colors">
                            <span>Tampilkan Semua Notifikasi</span>
                        </a>
                    </div>
                @endif
            </div>
        @else
            <div class="divide-y divide-slate-100 dark:divide-zinc-800/80">
                @foreach($notifications as $notif)
                    @php
                        $data = $notif->data ?? [];
                        $isRead = $notif->read_at !== null;
                        $type = $data['type'] ?? 'info';
                        $targetUrl = route('notifikasi.lms.baca', $notif->id, false);
                    @endphp
                    <div class="p-5 sm:p-6 transition-colors flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 {{ !$isRead ? 'bg-indigo-50/40 dark:bg-indigo-950/20' : 'hover:bg-slate-50/60 dark:hover:bg-zinc-800/40' }}">
                        <div class="flex items-start gap-4 flex-1 min-w-0">
                            <!-- Icon Indicator -->
                            <div class="shrink-0 mt-0.5">
                                @if($type === 'tugas_baru')
                                    <div class="w-11 h-11 rounded-2xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shadow-xs">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                    </div>
                                @elseif($type === 'tugas_dikumpulkan')
                                    <div class="w-11 h-11 rounded-2xl bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shadow-xs">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                    </div>
                                @elseif($type === 'tugas_dinilai')
                                    <div class="w-11 h-11 rounded-2xl bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shadow-xs">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-11 h-11 rounded-2xl bg-slate-100 dark:bg-zinc-800 text-slate-500 flex items-center justify-center shadow-xs">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Text Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">{{ $data['title'] ?? 'Pemberitahuan' }}</h2>
                                    
                                    {{-- Type Badge --}}
                                    @if($type === 'tugas_baru')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300 border border-indigo-200/50 dark:border-indigo-800/40">
                                            Tugas Baru
                                        </span>
                                    @elseif($type === 'tugas_dikumpulkan')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-800/40">
                                            Pengumpulan
                                        </span>
                                    @elseif($type === 'tugas_dinilai')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-800/40">
                                            Hasil Nilai
                                        </span>
                                    @endif

                                    {{-- Unread Badge --}}
                                    @if(!$isRead)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-600 dark:bg-rose-950 dark:text-rose-400 border border-rose-200/60 dark:border-rose-900/40">
                                            Baru
                                        </span>
                                    @endif
                                </div>

                                <p class="text-xs sm:text-sm text-slate-600 dark:text-zinc-300 mt-1.5 leading-relaxed">{{ $data['message'] ?? '' }}</p>

                                <div class="mt-3 flex flex-wrap items-center gap-2.5 sm:gap-3 text-[11px] text-slate-400 dark:text-zinc-500">
                                    <span class="inline-flex items-center gap-1 font-medium">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $notif->created_at?->diffForHumans() }} ({{ $notif->created_at?->translatedFormat('d M Y, H:i') }} WIB)
                                    </span>

                                    @if(!empty($data['due_at']))
                                        <span class="text-slate-300 dark:text-zinc-700">&bull;</span>
                                        <span class="inline-flex items-center gap-1 font-bold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 px-2 py-0.5 rounded-md">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            Tenggat: {{ $data['due_at'] }}
                                        </span>
                                    @endif

                                    @if(isset($data['is_late']))
                                        <span class="text-slate-300 dark:text-zinc-700">&bull;</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold {{ $data['is_late'] ? 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' }}">
                                            {{ $data['is_late'] ? 'Pengumpulan Terlambat' : 'Tepat Waktu' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="shrink-0 flex items-center gap-2 self-end sm:self-center">
                            <a href="{{ $targetUrl }}" 
                               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/60 dark:text-indigo-300 dark:hover:bg-indigo-900/60 transition-all cursor-pointer shadow-xs active:scale-95">
                                <span>{{ $type === 'tugas_dikumpulkan' ? 'Periksa Jawaban' : 'Buka Halaman' }}</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($notifications->hasPages())
                <div class="p-4 sm:p-5 border-t border-slate-100 dark:border-zinc-800 flex justify-center">
                    {{ $notifications->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
