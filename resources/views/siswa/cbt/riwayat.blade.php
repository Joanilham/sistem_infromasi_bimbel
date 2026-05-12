@extends('layouts.siswa_cbt')

@section('title', 'Riwayat Ujian')

@section('content')
<div class="space-y-5">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold" style="color:var(--text-main)">Nilai & Riwayat</h1>
            <p class="text-sm mt-1" style="color:var(--text-muted)">Semua ujian yang pernah kamu kerjakan</p>
        </div>
        <a href="{{ route('siswa.ujian.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium text-white transition-opacity hover:opacity-80" style="background: var(--primary)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            Ujian Tersedia
        </a>
    </div>

    @if($pesertas->count() > 0)
        {{-- Ringkasan --}}
        @php
            $selesai = $pesertas->where('status', 'selesai');
            $avgSkor = $selesai->avg('skor');
            $maxSkor = $selesai->max('skor');
        @endphp
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-4 text-center">
                <p class="text-xs font-medium mb-1" style="color:var(--text-muted)">Total Dikerjakan</p>
                <p class="text-3xl font-black" style="color:var(--primary)">{{ $pesertas->count() }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-4 text-center">
                <p class="text-xs font-medium mb-1" style="color:var(--text-muted)">Rata-rata Skor</p>
                <p class="text-3xl font-black text-amber-600">{{ number_format($avgSkor ?? 0, 0) }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-4 text-center">
                <p class="text-xs font-medium mb-1" style="color:var(--text-muted)">Skor Terbaik</p>
                <p class="text-3xl font-black text-emerald-600">{{ number_format($maxSkor ?? 0, 0) }}</p>
            </div>
        </div>

        {{-- List riwayat --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="divide-y divide-slate-50">
                @foreach($pesertas as $p)
                <div class="p-5 flex items-center gap-4 hover:bg-slate-50/50 transition-colors">
                    {{-- Score badge --}}
                    <div class="flex-shrink-0 w-14 h-14 rounded-2xl flex flex-col items-center justify-center font-black text-sm
                        {{ $p->status === 'selesai' ? ($p->skor >= 75 ? 'bg-emerald-100 text-emerald-700' : ($p->skor >= 50 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-600')) : 'bg-slate-100 text-slate-500' }}">
                        @if($p->status === 'selesai')
                            <span class="text-xl font-black">{{ number_format($p->skor, 0) }}</span>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-800 truncate">{{ $p->ujian->judul }}</p>
                        <div class="flex flex-wrap gap-2 mt-1 text-xs text-slate-500">
                            <span>{{ $p->waktu_mulai?->format('d M Y, H:i') }}</span>
                            <span>•</span>
                            <span class="capitalize">{{ $p->ujian->mode }}</span>
                            @if($p->status === 'selesai' && $p->waktu_mulai && $p->waktu_selesai)
                                <span>•</span>
                                <span>{{ $p->waktu_mulai->diff($p->waktu_selesai)->format('%H:%I:%S') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex-shrink-0 flex items-center gap-2">
                        @if($p->status === 'selesai')
                            <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 font-semibold">Selesai</span>
                            <a href="{{ route('siswa.ujian.hasil', $p->cbt_ujian_id) }}"
                                class="text-xs px-3 py-1.5 rounded-xl text-white font-semibold transition-opacity hover:opacity-80"
                                style="background: var(--primary)">
                                Detail
                            </a>
                        @else
                            <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-700 font-semibold">{{ ucfirst($p->status) }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Pagination --}}
        <div>{{ $pesertas->links() }}</div>

    @else
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
            <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background:var(--primary-light)">
                <svg class="w-8 h-8" style="color:var(--primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <h3 class="font-bold text-slate-700 text-lg">Belum ada riwayat ujian</h3>
            <p class="text-sm text-slate-400 mt-1 mb-5">Kamu belum pernah mengikuti ujian apapun.</p>
            <a href="{{ route('siswa.ujian.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white" style="background: var(--primary)">
                Lihat Ujian Tersedia
            </a>
        </div>
    @endif

</div>
@endsection
