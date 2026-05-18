@extends('layouts.admin')
@section('title', 'Pemberitahuan')

@section('content')
@php
    $totalBadge = $pendaftaranMenunggu->count()
                + $pembayaranBelumDikonfirmasi->count()
                + $transferSpp->count()
                + $tagihanJatuhTempo->count();
@endphp

<div x-data="{ tab: new URLSearchParams(window.location.search).get('tab') || 'pendaftaran' }" class="space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-6 sm:p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-3">
                Pemberitahuan
                @if($totalBadge > 0)
                    <span class="bg-red-500 text-white text-xs font-black px-2.5 py-1 rounded-full">{{ $totalBadge }}</span>
                @endif
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">Notifikasi pendaftaran, keuangan, dan tagihan siswa.</p>
        </div>
    </div>

    {{-- Search & Filter Bar --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-6 border border-slate-100 dark:border-zinc-800 shadow-sm">
        <form method="GET" action="{{ route('notifikasi.index') }}" class="flex flex-col sm:flex-row items-center gap-4">
            <input type="hidden" name="tab" :value="tab">
            <div class="relative w-full group">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari berdasarkan nama siswa, email, nomor kwitansi, atau penerima..."
                    class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-semibold py-3.5 pl-12 pr-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-slate-200">
                <svg class="w-5 h-5 absolute left-4.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto shrink-0">
                <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs px-8 py-4 rounded-2xl transition-all active:scale-95 shadow-md hover:shadow-lg hover:shadow-indigo-500/10 uppercase tracking-widest shrink-0">
                    Cari
                </button>
                @if($search)
                    <a href="{{ route('notifikasi.index') }}?tab={{ request('tab', 'pendaftaran') }}" class="bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/40 p-4 rounded-2xl transition-all shrink-0" title="Reset Pencarian">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tab Navigation --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="flex overflow-x-auto border-b border-slate-100 dark:border-zinc-800 px-4">
            {{-- Tab: Pendaftaran --}}
            <button @click="tab = 'pendaftaran'"
                :class="tab === 'pendaftaran'
                    ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 font-black'
                    : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 font-semibold'"
                class="whitespace-nowrap flex items-center gap-2 py-4 px-4 border-b-2 text-sm transition-all duration-200">
                <span class="w-7 h-7 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </span>
                Pendaftaran Siswa
                @if($pendaftaranMenunggu->count() > 0)
                    <span class="bg-amber-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $pendaftaranMenunggu->count() }}</span>
                @endif
            </button>

            {{-- Tab: Verifikasi Keuangan --}}
            <button @click="tab = 'keuangan'"
                :class="tab === 'keuangan'
                    ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 font-black'
                    : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 font-semibold'"
                class="whitespace-nowrap flex items-center gap-2 py-4 px-4 border-b-2 text-sm transition-all duration-200">
                <span class="w-7 h-7 rounded-lg bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </span>
                Verifikasi Keuangan
                @if($pembayaranBelumDikonfirmasi->count() > 0)
                    <span class="bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $pembayaranBelumDikonfirmasi->count() }}</span>
                @endif
            </button>

            {{-- Tab: Transfer SPP --}}
            <button @click="tab = 'transfer'"
                :class="tab === 'transfer'
                    ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 font-black'
                    : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 font-semibold'"
                class="whitespace-nowrap flex items-center gap-2 py-4 px-4 border-b-2 text-sm transition-all duration-200">
                <span class="w-7 h-7 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </span>
                Transfer SPP
                @if($transferSpp->count() > 0)
                    <span class="bg-blue-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $transferSpp->count() }}</span>
                @endif
            </button>

            {{-- Tab: Tagihan Jatuh Tempo --}}
            <button @click="tab = 'tagihan'"
                :class="tab === 'tagihan'
                    ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 font-black'
                    : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 font-semibold'"
                class="whitespace-nowrap flex items-center gap-2 py-4 px-4 border-b-2 text-sm transition-all duration-200">
                <span class="w-7 h-7 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </span>
                Tagihan Jatuh Tempo
                @if($tagihanJatuhTempo->count() > 0)
                    <span class="bg-yellow-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $tagihanJatuhTempo->count() }}</span>
                @endif
            </button>
        </div>

        {{-- ── TAB 1: Pendaftaran Siswa ─────────────────────────── --}}
        <div x-show="tab === 'pendaftaran'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            @if($pendaftaranMenunggu->isEmpty())
                <div class="py-16 text-center text-slate-400">
                    <div class="text-4xl mb-3">✅</div>
                    <div class="font-bold text-slate-600 dark:text-slate-300">Tidak ada pendaftaran baru yang menunggu</div>
                    <p class="text-sm mt-1">Semua pendaftaran sudah diproses.</p>
                </div>
            @else
                <ul class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @foreach($pendaftaranMenunggu as $item)
                        <li class="flex items-center justify-between gap-4 px-6 py-4 hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $item->nama_lengkap }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        Paket: <span class="font-semibold">{{ $item->paketBimbingan?->nama_paket ?? '-' }}</span>
                                        &bull; Kantor: {{ $item->kantor?->nama_kantor ?? '-' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <span class="text-xs text-slate-400">{{ $item->created_at->diffForHumans() }}</span>
                                <a href="{{ route('admin.pendaftaran.show', $item->id) }}"
                                   class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition active:scale-95">
                                    Verifikasi
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- ── TAB 2: Verifikasi Keuangan ──────────────────────── --}}
        <div x-show="tab === 'keuangan'" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            @if($pembayaranBelumDikonfirmasi->isEmpty())
                <div class="py-16 text-center text-slate-400">
                    <div class="text-4xl mb-3">✅</div>
                    <div class="font-bold text-slate-600 dark:text-slate-300">Tidak ada pembayaran yang menunggu konfirmasi</div>
                </div>
            @else
                <ul class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @foreach($pembayaranBelumDikonfirmasi as $item)
                        <li class="flex items-center justify-between gap-4 px-6 py-4 hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $item->pendaftaranSiswa?->nama_lengkap ?? 'Tanpa Nama' }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        Metode: <span class="font-semibold">{{ $item->metode_pembayaran }}</span>
                                        &bull; <span class="text-emerald-600 font-bold">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <span class="text-xs text-slate-400">{{ $item->created_at->diffForHumans() }}</span>
                                <a href="{{ route('admin.pendaftaran.show', $item->pendaftaran_siswa_id) }}"
                                   class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition active:scale-95">
                                    Konfirmasi
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- ── TAB 3: Transfer SPP (7 hari terakhir) ───────────── --}}
        <div x-show="tab === 'transfer'" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            @if($transferSpp->isEmpty())
                <div class="py-16 text-center text-slate-400">
                    <div class="text-4xl mb-3">📭</div>
                    <div class="font-bold text-slate-600 dark:text-slate-300">Belum ada transfer SPP dalam 7 hari terakhir</div>
                </div>
            @else
                <ul class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @foreach($transferSpp as $item)
                        @php $siswa = $item->pembayaranSiswa?->pesertaDidik; @endphp
                        <li class="flex items-center justify-between gap-4 px-6 py-4 hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-white">
                                        {{ $siswa?->nama_lengkap ?? 'Siswa' }}
                                        <span class="ml-2 px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-[10px] font-black uppercase">TRANSFER</span>
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        No. Kwitansi: <span class="font-mono font-bold">{{ $item->no_kwitansi }}</span>
                                        &bull; <span class="text-emerald-600 font-bold">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                                        &bull; Diterima oleh: {{ $item->penerima }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <span class="text-xs text-slate-400">{{ $item->created_at->diffForHumans() }}</span>
                                @if($siswa)
                                    <a href="{{ route('keuangan.pembayaran.show', $siswa->id) }}"
                                       class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition active:scale-95">
                                        Lihat Detail
                                    </a>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- ── TAB 4: Tagihan Jatuh Tempo ──────────────────────── --}}
        <div x-show="tab === 'tagihan'" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            @if($tagihanJatuhTempo->isEmpty())
                <div class="py-16 text-center text-slate-400">
                    <div class="text-4xl mb-3">🎉</div>
                    <div class="font-bold text-slate-600 dark:text-slate-300">Tidak ada tagihan yang akan jatuh tempo</div>
                    <p class="text-sm mt-1">Semua siswa dalam kondisi aman.</p>
                </div>
            @else
                <ul class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @foreach($tagihanJatuhTempo as $item)
                        @php
                            $siswa    = $item->pesertaDidik;
                            $overdue  = $item->batas_waktu && $item->batas_waktu->isPast();
                            $hasDate  = !empty($item->batas_waktu);
                        @endphp
                        <li class="flex items-center justify-between gap-4 px-6 py-4 hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition {{ $overdue ? 'bg-red-50/40 dark:bg-red-900/5' : '' }}">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0
                                    {{ $overdue ? 'bg-red-100 dark:bg-red-900/30' : ($hasDate ? 'bg-yellow-100 dark:bg-yellow-900/30' : 'bg-slate-100 dark:bg-zinc-800') }}">
                                    <svg class="w-5 h-5 {{ $overdue ? 'text-red-600' : ($hasDate ? 'text-yellow-600' : 'text-slate-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                        {{ $siswa?->nama_lengkap }}
                                        @if($overdue)
                                            <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 text-[10px] font-black uppercase">Overdue!</span>
                                        @elseif($hasDate)
                                            <span class="px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 text-[10px] font-black uppercase">Segera</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 dark:bg-zinc-800 dark:text-slate-400 text-[10px] font-black uppercase">Belum Diatur</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        Kekurangan: <span class="font-black text-red-600">Rp {{ number_format($item->kekurangan, 0, ',', '.') }}</span>
                                        &bull; Deadline: <span class="font-semibold {{ $overdue ? 'text-red-600' : ($hasDate ? 'text-yellow-600' : 'text-slate-400') }}">{{ $item->batas_waktu?->format('d/m/Y') ?? 'Belum Diatur' }}</span>
                                        &bull; Paket: {{ $siswa?->paketBimbingan?->nama_paket ?? '-' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <span class="text-xs text-slate-400">{{ $item->batas_waktu?->diffForHumans() ?? 'Deadline Kosong' }}</span>
                                @if($siswa)
                                    <a href="{{ route('keuangan.pembayaran.show', $siswa->id) }}"
                                       class="{{ $overdue ? 'bg-red-600 hover:bg-red-700' : ($hasDate ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-slate-600 hover:bg-slate-700') }} text-white text-xs font-bold px-4 py-2 rounded-xl transition active:scale-95">
                                        Tagih
                                    </a>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
