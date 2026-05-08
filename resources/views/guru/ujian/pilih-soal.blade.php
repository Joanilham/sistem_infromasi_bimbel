@extends('layouts.guru')

@section('title', 'Pilih Soal - ' . $ujian->judul)

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
        <a href="{{ route('guru.ujian.show', $ujian->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 text-sm inline-flex items-center gap-1 mb-3 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Detail Ujian
        </a>
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="text-xl font-bold text-slate-800 dark:text-white">Kelola Soal Ujian</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $ujian->judul }} — Pilih soal dari bank soal Anda</p>
            </div>
            <span class="px-4 py-2 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-xl text-sm font-bold">
                {{ $ujian->ujianSoals->count() }} soal dipilih
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Panel Kiri: Bank Soal (belum dipilih) --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 overflow-hidden flex flex-col">
            <div class="p-4 bg-slate-50 dark:bg-zinc-800/50 border-b border-slate-100 dark:border-zinc-800">
                <h3 class="font-semibold text-slate-700 dark:text-slate-200 text-sm flex items-center gap-2">
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                    Bank Soal Tersedia
                </h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Klik tombol + untuk menambahkan soal ke ujian</p>
            </div>
            <div class="flex-1 overflow-y-auto max-h-[60vh] divide-y divide-slate-50 dark:divide-zinc-800">
                @forelse($bankSoals as $soal)
                <div class="p-4 hover:bg-slate-50/50 dark:hover:bg-zinc-800/30 transition-colors group">
                    <div class="flex gap-3 items-start">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap gap-1 mb-1">
                                @if($soal->mapel)
                                <span class="text-[10px] text-slate-500 bg-slate-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">{{ $soal->mapel->nama }}</span>
                                @endif
                                <span class="text-[10px] font-semibold {{ $soal->tipe_soal === 'pg' ? 'text-blue-600 bg-blue-50 dark:bg-blue-900/30' : 'text-purple-600 bg-purple-50 dark:bg-purple-900/30' }} px-1.5 py-0.5 rounded">{{ $soal->tipe_soal === 'pg' ? 'PG' : 'Essay' }}</span>
                            </div>
                            <p class="text-sm text-slate-700 dark:text-slate-200 line-clamp-2 leading-relaxed">{!! Str::limit(strip_tags($soal->pertanyaan), 100) !!}</p>
                        </div>
                        <form method="POST" action="{{ route('guru.ujian.soal.store', $ujian->id) }}">
                            @csrf
                            <input type="hidden" name="soal_ids[]" value="{{ $soal->id }}">
                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-200 dark:hover:bg-emerald-900/50 transition-colors shrink-0" title="Tambahkan ke ujian">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 dark:text-slate-500 text-sm">
                    <p>Semua soal sudah dipilih atau belum ada soal di bank soal.</p>
                    <a href="{{ route('guru.bank-soal.create') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline mt-1 inline-block">Buat soal baru →</a>
                </div>
                @endforelse
            </div>
            @if($bankSoals->hasPages())
            <div class="p-3 border-t border-slate-100 dark:border-zinc-800">
                {{ $bankSoals->links() }}
            </div>
            @endif
        </div>

        {{-- Panel Kanan: Soal yang Dipilih --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 overflow-hidden flex flex-col">
            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border-b border-emerald-100 dark:border-emerald-900/30">
                <h3 class="font-semibold text-emerald-800 dark:text-emerald-300 text-sm flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                    Soal Ujian ({{ $ujian->ujianSoals->count() }})
                </h3>
                <p class="text-xs text-emerald-600/70 dark:text-emerald-400/60 mt-0.5">Soal yang akan ditampilkan ke siswa pada ujian ini</p>
            </div>
            <div class="flex-1 overflow-y-auto max-h-[60vh] divide-y divide-slate-50 dark:divide-zinc-800">
                @forelse($ujian->ujianSoals as $idx => $us)
                <div class="p-4 hover:bg-slate-50/50 dark:hover:bg-zinc-800/30 transition-colors group">
                    <div class="flex gap-3 items-start">
                        <span class="w-7 h-7 bg-indigo-100 dark:bg-indigo-900/40 rounded-lg flex items-center justify-center text-indigo-700 dark:text-indigo-300 text-xs font-bold shrink-0">{{ $idx + 1 }}</span>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap gap-1 mb-1">
                                @if($us->bankSoal->mapel)
                                <span class="text-[10px] text-slate-500 bg-slate-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">{{ $us->bankSoal->mapel->nama }}</span>
                                @endif
                                <span class="text-[10px] text-slate-400 bg-slate-50 dark:bg-zinc-800 px-1.5 py-0.5 rounded">Bobot: {{ $us->bobot }}</span>
                            </div>
                            <p class="text-sm text-slate-700 dark:text-slate-200 line-clamp-2 leading-relaxed">{!! Str::limit(strip_tags($us->bankSoal->pertanyaan), 100) !!}</p>
                        </div>
                        <form method="POST" action="{{ route('guru.ujian.soal.destroy', [$ujian->id, $us->bankSoal->id]) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-900/30 dark:hover:text-red-400 transition-colors shrink-0 opacity-0 group-hover:opacity-100" title="Hapus dari ujian" onclick="return confirm('Hapus soal ini dari ujian?')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 dark:text-slate-500 text-sm">
                    <p>Belum ada soal yang dipilih untuk ujian ini.</p>
                    <p class="text-xs mt-1">Klik + pada panel kiri untuk menambahkan.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Action footer --}}
    <div class="flex justify-between items-center pt-2">
        <a href="{{ route('guru.ujian.edit', $ujian->id) }}" class="text-sm text-slate-500 dark:text-slate-400 hover:text-indigo-600 font-medium">← Edit Info Ujian</a>
        <a href="{{ route('guru.ujian.peserta', $ujian->id) }}" class="px-6 py-2.5 bg-gradient-to-r from-violet-500 to-purple-600 text-white rounded-xl text-sm font-semibold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
            Atur Peserta
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
    </div>
</div>
@endsection
