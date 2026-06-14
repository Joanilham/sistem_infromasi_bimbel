@extends('layouts.guru')

@section('title', 'Koreksi Essay - ' . $sesi->user->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-20">
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
        <a href="{{ route('guru.ujian.monitoring', $ujian->id) }}" class="text-indigo-600 text-sm inline-flex items-center gap-1 mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Kembali ke Monitoring
        </a>
        <h2 class="text-xl font-bold text-slate-800 dark:text-white">Koreksi Jawaban Ujian</h2>
        <div class="mt-4 flex flex-col sm:flex-row sm:items-center gap-4 text-sm text-slate-600 dark:text-slate-400">
            <div><span class="font-bold text-slate-500 uppercase text-[10px] tracking-wider block">Siswa</span><span class="font-medium text-slate-800 dark:text-slate-200">{{ $sesi->user->name }}</span></div>
            <div><span class="font-bold text-slate-500 uppercase text-[10px] tracking-wider block">Ujian</span><span class="font-medium text-slate-800 dark:text-slate-200">{{ $ujian->judul }}</span></div>
            <div><span class="font-bold text-slate-500 uppercase text-[10px] tracking-wider block">Status</span><span class="font-medium text-slate-800 dark:text-slate-200">{{ ucfirst($sesi->status) }}</span></div>
        </div>
    </div>

    <form method="POST" action="{{ route('guru.ujian.koreksi.store', ['id' => $ujian->id, 'peserta_id' => $sesi->id]) }}">
        @csrf
        <div class="space-y-4">
            @foreach($sesi->jawabans->sortBy('urutan') as $i => $j)
                @php
                    $soal = $j->bankSoal;
                    $isEssay = $soal->tipe_soal === 'essay';
                    $bobot = $ujianSoalsMap->get($soal->id)?->bobot ?? 1;
                @endphp
                <div class="bg-white dark:bg-zinc-900 rounded-xl border {{ $isEssay ? 'border-indigo-200 dark:border-indigo-900/50 shadow-md' : 'border-slate-100 dark:border-zinc-800 shadow-sm' }} overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="shrink-0 w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm bg-slate-100 text-slate-500 dark:bg-zinc-800 dark:text-zinc-400">
                                {{ $i + 1 }}
                            </div>
                            <div class="flex-1 space-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $isEssay ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400' : 'bg-slate-100 text-slate-600 dark:bg-zinc-800 dark:text-zinc-400' }}">
                                        {{ $isEssay ? 'Essay' : 'Pilihan Ganda' }}
                                    </span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Bobot: {{ $bobot }}</span>
                                </div>
                                <div class="text-sm font-medium text-slate-800 dark:text-slate-200 leading-relaxed">
                                    {!! \App\Helpers\HtmlSanitizer::clean($soal->pertanyaan) !!}
                                </div>
                                @if($soal->file_media)
                                    <img src="{{ asset('storage/' . $soal->file_media) }}" class="mt-2 rounded-xl max-h-48 border border-slate-100 dark:border-zinc-800" alt="Media Soal">
                                @endif
                            </div>
                        </div>

                        <div class="ml-12 p-4 rounded-xl {{ $isEssay ? 'bg-slate-50 dark:bg-zinc-800/50' : '' }}">
                            @if($isEssay)
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Jawaban Siswa:</p>
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap">{{ $j->jawaban_essay ?: '(Tidak ada jawaban)' }}</p>
                                
                                <div class="mt-4 pt-4 border-t border-slate-200 dark:border-zinc-700 flex items-center gap-4">
                                    <label class="text-xs font-bold text-slate-600 dark:text-slate-400">Berikan Skor (0 - {{ $bobot }}):</label>
                                    <input type="number" name="skor[{{ $j->id }}]" value="{{ $j->skor ?? '' }}" min="0" max="{{ $bobot }}" step="0.1" class="w-24 px-3 py-1.5 rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white transition-shadow" placeholder="Skor" required>
                                </div>
                            @else
                                @php
                                    $isCorrect = $j->is_benar;
                                    $isKosong = !$j->cbt_opsi_jawaban_id;
                                @endphp
                                <div class="flex items-center gap-2">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status:</p>
                                    @if($isKosong)
                                        <span class="text-xs font-bold text-slate-500 bg-slate-100 dark:bg-zinc-800 px-2 py-0.5 rounded">Tidak Dijawab (Skor: 0)</span>
                                    @elseif($isCorrect)
                                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-0.5 rounded">Benar (Skor: {{ $j->skor }})</span>
                                    @else
                                        <span class="text-xs font-bold text-red-600 bg-red-50 dark:bg-red-900/30 px-2 py-0.5 rounded">Salah (Skor: 0)</span>
                                    @endif
                                </div>
                                @if(!$isKosong && $j->opsiJawaban)
                                    <div class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                                        Jawaban: {!! strip_tags($j->opsiJawaban->teks_opsi) !!}
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="sticky bottom-4 mt-8 bg-white/90 dark:bg-zinc-900/90 backdrop-blur-md p-4 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-xl flex justify-between items-center z-10">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Skor Sementara</p>
                <p class="text-2xl font-black text-slate-800 dark:text-slate-200">{{ number_format($sesi->skor, 1) }}</p>
            </div>
            <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl text-sm font-black hover:bg-indigo-700 transition-colors shadow-md hover:shadow-lg">
                Simpan Penilaian
            </button>
        </div>
    </form>
</div>
@endsection
