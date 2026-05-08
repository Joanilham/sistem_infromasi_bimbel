@extends('layouts.siswa_cbt')

@section('title', 'Hasil - ' . $ujian->judul)

@section('content')
<div class="space-y-6 max-w-3xl mx-auto">

    {{-- Score Card --}}
    <div class="rounded-2xl overflow-hidden shadow-sm"
        style="background: {{ $peserta->skor >= 75 ? 'linear-gradient(135deg, #059669, #10B981)' : ($peserta->skor >= 50 ? 'linear-gradient(135deg, #D97706, #F59E0B)' : 'linear-gradient(135deg, #DC2626, #EF4444)') }}">
        <div class="p-8 text-center text-white">
            <div class="text-sm font-semibold opacity-80 mb-2 uppercase tracking-wider">Skor Kamu</div>
            <div class="text-8xl font-black leading-none mb-2">{{ number_format($peserta->skor, 0) }}</div>
            <div class="text-white/70 text-sm mb-6">dari 100</div>
            <div class="flex justify-center gap-8 text-sm">
                <div class="text-center">
                    <div class="text-2xl font-black">{{ $totalBenar }}</div>
                    <div class="opacity-70">Benar</div>
                </div>
                <div class="w-px bg-white/20"></div>
                <div class="text-center">
                    <div class="text-2xl font-black">{{ $totalSalah }}</div>
                    <div class="opacity-70">Salah</div>
                </div>
                <div class="w-px bg-white/20"></div>
                <div class="text-center">
                    <div class="text-2xl font-black">{{ $totalSoal - $totalBenar - $totalSalah }}</div>
                    <div class="opacity-70">Belum dijawab</div>
                </div>
            </div>
        </div>
        <div class="bg-black/10 px-8 py-4 flex items-center justify-between text-white text-sm">
            <span>⏱ Selesai: {{ $peserta->waktu_selesai?->format('d M Y, H:i') }}</span>
            @if($peserta->waktu_mulai && $peserta->waktu_selesai)
                <span>Durasi: {{ $peserta->waktu_mulai->diff($peserta->waktu_selesai)->format('%H:%I:%S') }}</span>
            @endif
        </div>
    </div>

    {{-- Action buttons --}}
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('siswa.ujian.index') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors bg-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Ujian
        </a>
        @if($ujian->limit_attempt == 0)
        <a href="{{ route('siswa.ujian.show', $ujian->id) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white transition-colors" style="background: var(--primary)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Ulangi
        </a>
        @endif
    </div>

    {{-- Pembahasan --}}
    @if($ujian->tampilkan_hasil)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-bold text-slate-800">Pembahasan Jawaban</h2>
        </div>
        <div class="divide-y divide-slate-50">
            @foreach($ujian->soals as $i => $soal)
            @php
                $jawaban = $jawabans[$soal->id] ?? null;
                $isBenar = $jawaban?->is_benar;
                $isPending = $isBenar === null && $soal->tipe_soal === 'essay';
            @endphp
            <div class="p-5">
                {{-- Question header --}}
                <div class="flex items-start gap-3 mb-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold
                        {{ $isPending ? 'bg-slate-100 text-slate-500' : ($isBenar ? 'bg-emerald-100 text-emerald-700' : ($jawaban ? 'bg-red-100 text-red-600' : 'bg-slate-100 text-slate-400')) }}">
                        @if($isPending) ? @elseif($isBenar) ✓ @elseif($jawaban) ✗ @else – @endif
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-1.5 mb-1.5 text-xs">
                            <span class="font-semibold text-slate-500">Soal {{ $i + 1 }}</span>
                            @if($soal->mapel)
                                <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-600">{{ $soal->mapel->nama }}</span>
                            @endif
                            @if($isPending)
                                <span class="px-1.5 py-0.5 rounded bg-amber-100 text-amber-700">Menunggu Penilaian</span>
                            @elseif($isBenar)
                                <span class="px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-700">✓ Benar</span>
                            @elseif($jawaban)
                                <span class="px-1.5 py-0.5 rounded bg-red-100 text-red-600">✗ Salah</span>
                            @else
                                <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-500">Tidak Dijawab</span>
                            @endif
                        </div>
                        <div class="text-sm text-slate-700 leading-relaxed">{!! $soal->pertanyaan !!}</div>
                    </div>
                </div>

                {{-- PG options review --}}
                @if($soal->tipe_soal === 'pg')
                @php $letters = ['A','B','C','D','E']; @endphp
                <div class="space-y-1.5 ml-11">
                    @foreach($soal->opsi_jawabans as $oi => $opsi)
                    @php
                        $isJawaban = $jawaban?->cbt_opsi_jawaban_id == $opsi->id;
                        $isKunci = $opsi->is_benar;
                    @endphp
                    <div class="flex items-start gap-2 px-3 py-2 rounded-lg text-sm
                        {{ $isKunci ? 'bg-emerald-50 border border-emerald-200' : ($isJawaban && !$isKunci ? 'bg-red-50 border border-red-200' : 'bg-slate-50') }}">
                        <span class="flex-shrink-0 font-bold {{ $isKunci ? 'text-emerald-700' : ($isJawaban ? 'text-red-600' : 'text-slate-400') }}">{{ $letters[$oi] ?? $oi+1 }}</span>
                        <span class="{{ $isKunci ? 'text-emerald-800' : ($isJawaban && !$isKunci ? 'text-red-700' : 'text-slate-600') }}">{!! $opsi->teks_opsi !!}</span>
                        @if($isKunci) <span class="ml-auto text-xs font-bold text-emerald-600">✓ Kunci</span> @endif
                        @if($isJawaban && !$isKunci) <span class="ml-auto text-xs font-bold text-red-500">← Jawaban Anda</span> @endif
                    </div>
                    @endforeach
                </div>
                @elseif($soal->tipe_soal === 'essay' && $jawaban?->jawaban_teks)
                <div class="ml-11 p-3 rounded-lg bg-slate-50 border border-slate-200 text-sm text-slate-700">
                    <p class="text-xs font-semibold text-slate-400 mb-1">Jawaban Anda:</p>
                    {{ $jawaban->jawaban_teks }}
                </div>
                @endif

                {{-- Pembahasan --}}
                @if($soal->pembahasan)
                <div class="ml-11 mt-3 p-3 rounded-lg" style="background: #EEF2FF; border: 1px solid #C7D2FE;">
                    <p class="text-xs font-bold mb-1" style="color: #4318FF;">💡 Pembahasan</p>
                    <p class="text-sm text-slate-700">{{ $soal->pembahasan->teks_pembahasan }}</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center">
        <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
        <p class="text-slate-500 font-medium">Pembahasan tidak ditampilkan</p>
        <p class="text-sm text-slate-400 mt-1">Guru menonaktifkan tampilan pembahasan untuk ujian ini.</p>
    </div>
    @endif

</div>
@endsection
