@extends('layouts.siswa')

@section('title', $ujian->judul)

@section('content')
@php
    $sudahSelesai = $sudahSelesai ?? false;
    $sedangMengerjakan = $sedangMengerjakan ?? false;
    $attempt = $attempt ?? null;
@endphp
<div class="space-y-5 max-w-2xl mx-auto">

    {{-- Back --}}
    <a href="{{ route('siswa.ujian.index') }}" class="inline-flex items-center gap-2 text-sm font-medium hover:opacity-70 transition-opacity" style="color:var(--primary)">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke daftar ujian
    </a>

    {{-- Card utama --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        {{-- Top banner --}}
        <div class="p-6" style="background: linear-gradient(135deg, #4318FF 0%, #6B4EFF 100%);">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-xs font-bold px-2.5 py-1 rounded-full
                    {{ $ujian->mode === 'resmi' ? 'bg-red-500 text-white' : 'bg-white/20 text-white' }}">
                    {{ $ujian->mode === 'resmi' ? '📋 UJIAN RESMI' : '📝 LATIHAN' }}
                </span>
                @if($sudahSelesai)
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-emerald-400 text-white">✓ Sudah Dikerjakan</span>
                @endif
            </div>
            <h1 class="text-2xl font-black text-white leading-tight">{{ $ujian->judul }}</h1>
            @if($ujian->deskripsi)
                <p class="text-white/70 text-sm mt-2">{{ $ujian->deskripsi }}</p>
            @endif
        </div>

        <div class="p-6 space-y-6">
            {{-- Info stats --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="text-center p-3 rounded-xl" style="background:var(--primary-light)">
                    <p class="text-xs font-medium mb-1" style="color:var(--text-muted)">Total Soal</p>
                    <p class="text-2xl font-black" style="color:var(--primary)">{{ $ujian->soals_count }}</p>
                </div>
                <div class="text-center p-3 rounded-xl bg-amber-50">
                    <p class="text-xs font-medium text-amber-600 mb-1">Durasi</p>
                    <p class="text-2xl font-black text-amber-700">{{ $ujian->durasi }}'</p>
                </div>
                <div class="text-center p-3 rounded-xl bg-emerald-50">
                    <p class="text-xs font-medium text-emerald-600 mb-1">Percobaan</p>
                    <p class="text-2xl font-black text-emerald-700">
                        {{ $ujian->limit_attempt == 0 ? '∞' : $ujian->limit_attempt }}
                    </p>
                </div>
                <div class="text-center p-3 rounded-xl bg-slate-50">
                    <p class="text-xs font-medium text-slate-500 mb-1">Acak Soal</p>
                    <p class="text-xl font-black text-slate-700">{{ $ujian->acak_soal ? 'Ya' : 'Tidak' }}</p>
                </div>
            </div>

            {{-- Jadwal --}}
            @if($ujian->waktu_mulai || $ujian->waktu_selesai)
            <div class="rounded-xl border border-slate-100 p-4 bg-slate-50">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Jadwal Ujian</p>
                <div class="flex flex-wrap gap-4 text-sm">
                    @if($ujian->waktu_mulai)
                    <div>
                        <p class="text-xs text-slate-400">Mulai</p>
                        <p class="font-semibold text-slate-700">{{ $ujian->waktu_mulai->format('d M Y, H:i') }}</p>
                    </div>
                    @endif
                    @if($ujian->waktu_selesai)
                    <div>
                        <p class="text-xs text-slate-400">Selesai</p>
                        <p class="font-semibold text-slate-700">{{ $ujian->waktu_selesai->format('d M Y, H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Aturan --}}
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Perhatikan sebelum mulai</p>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li class="flex items-start gap-2"><span class="text-amber-500 mt-0.5">⚠</span> Pastikan koneksi internet stabil selama ujian berlangsung.</li>
                    <li class="flex items-start gap-2"><span class="text-amber-500 mt-0.5">⚠</span> Timer akan berjalan otomatis dan ujian akan dikumpulkan saat waktu habis.</li>
                    <li class="flex items-start gap-2"><span class="text-blue-500 mt-0.5">ℹ</span> Kamu bisa melewati soal dan kembali lagi sebelum submit.</li>
                    @if($ujian->acak_soal)
                    <li class="flex items-start gap-2"><span class="text-blue-500 mt-0.5">ℹ</span> Urutan soal diacak secara otomatis.</li>
                    @endif
                    @if($ujian->token)
                    <li class="flex items-start gap-2"><span class="text-red-500 mt-0.5">🔒</span> Ujian ini memerlukan token dari guru untuk memulai.</li>
                    @endif
                </ul>
            </div>

            {{-- Hasil sebelumnya --}}
            @if($sudahSelesai && $attempt)
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wide mb-2">Hasil Terakhir</p>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-black {{ $attempt->skor >= 75 ? 'text-emerald-600' : ($attempt->skor >= 50 ? 'text-amber-600' : 'text-red-500') }}">
                            {{ number_format($attempt->skor, 1) }}
                        </p>
                        <p class="text-sm text-emerald-600">Diselesaikan {{ $attempt->waktu_selesai?->format('d M Y, H:i') }}</p>
                    </div>
                    <a href="{{ route('siswa.ujian.hasil', $attempt->id) }}" class="px-4 py-2 rounded-xl text-sm font-semibold bg-emerald-600 text-white hover:bg-emerald-700 transition-colors">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @endif

            {{-- CTA --}}
            @if($sedangMengerjakan)
                <a href="{{ route('siswa.ujian.soal', [$attempt->id, 1]) }}"
                    class="block w-full text-center py-3.5 rounded-xl text-base font-bold text-white transition-all hover:opacity-90"
                    style="background: linear-gradient(135deg, #F59E0B, #EF4444);">
                    ▶ Lanjutkan Ujian (Masih Berlangsung)
                </a>
            @elseif($sudahSelesai && $ujian->limit_attempt > 0)
                <div class="text-center py-3 text-sm text-slate-400">
                    Kamu telah menyelesaikan ujian ini dan tidak bisa mengulang.
                </div>
            @else
                <div x-data="{ setuju: false, token: '' }">
                    <label class="flex items-center gap-3 mb-6 p-4 border border-emerald-200 bg-emerald-50 rounded-xl cursor-pointer">
                        <input type="checkbox" x-model="setuju" class="w-5 h-5 text-emerald-600 rounded border-emerald-300 focus:ring-emerald-500">
                        <span class="text-sm font-medium text-emerald-800">Saya telah membaca dan menyetujui tata tertib ujian.</span>
                    </label>

                    @if($ujian->token)
                    <form action="{{ route('siswa.ujian.mulai', $ujian->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Masukkan Token Ujian</label>
                            <input type="text" name="token" required x-model="token" placeholder="Masukkan token dari guru..."
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                        </div>
                        <button type="submit"
                            :disabled="!setuju || token.trim() === ''"
                            :class="!setuju || token.trim() === '' ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-90 hover:scale-[1.01]'"
                            class="w-full py-3.5 rounded-xl text-base font-bold text-white transition-all"
                            style="background: linear-gradient(135deg, #4318FF, #6B4EFF);">
                            🔓 Mulai Ujian
                        </button>
                    </form>
                    @else
                    <form action="{{ route('siswa.ujian.mulai', $ujian->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            :disabled="!setuju"
                            :class="!setuju ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-90 hover:scale-[1.01]'"
                            class="w-full py-3.5 rounded-xl text-base font-bold text-white transition-all"
                            style="background: linear-gradient(135deg, #4318FF, #6B4EFF);">
                            🚀 {{ $sudahSelesai ? 'Ulangi Ujian' : 'Mulai Ujian Sekarang' }}
                        </button>
                    </form>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
