@extends('layouts.guru')
@section('title', 'Monitoring - ' . $ujian->judul)
@section('content')
<div class="space-y-6" x-data="{ autoRefresh: true }" x-init="setInterval(() => { if(autoRefresh) location.reload() }, 15000)">
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
        <a href="{{ route('guru.ujian.show', $ujian->id) }}" class="text-indigo-600 text-sm inline-flex items-center gap-1 mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Kembali
        </a>
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    Monitoring Live
                </h2>
                <p class="text-sm text-slate-500 mt-1">{{ $ujian->judul }} — {{ $ujian->ujian_soals_count }} soal</p>
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-500">
                <input type="checkbox" x-model="autoRefresh" class="w-4 h-4 text-emerald-600 rounded">
                Auto refresh (15s)
            </label>
        </div>
    </div>

    {{-- Stats --}}
    @php
        $total = $assignedUsers->count();
        $sedang = $sesis->where('status', 'mengerjakan')->count();
        $selesai = $sesis->whereIn('status', ['selesai', 'timeout'])->count();
        $belum = $total - $sedang - $selesai;
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-800 p-4 text-center">
            <div class="text-2xl font-extrabold text-indigo-600">{{ $total }}</div>
            <div class="text-xs text-slate-500 mt-1">Total Peserta</div>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-800 p-4 text-center">
            <div class="text-2xl font-extrabold text-amber-600">{{ $sedang }}</div>
            <div class="text-xs text-slate-500 mt-1">Sedang Mengerjakan</div>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-800 p-4 text-center">
            <div class="text-2xl font-extrabold text-emerald-600">{{ $selesai }}</div>
            <div class="text-xs text-slate-500 mt-1">Selesai</div>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-800 p-4 text-center">
            <div class="text-2xl font-extrabold text-slate-400">{{ $belum }}</div>
            <div class="text-xs text-slate-500 mt-1">Belum Mengerjakan</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 overflow-hidden mt-6">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-zinc-800/50 border-b border-slate-100 dark:border-zinc-800">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase">Siswa</th>
                    <th class="text-center px-3 py-3 text-xs font-bold text-slate-500 uppercase">Status</th>
                    <th class="text-center px-3 py-3 text-xs font-bold text-slate-500 uppercase">Jawaban</th>
                    <th class="text-center px-3 py-3 text-xs font-bold text-slate-500 uppercase">Mulai</th>
                    <th class="text-center px-3 py-3 text-xs font-bold text-slate-500 uppercase">Skor</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-zinc-800">
                @forelse($assignedUsers as $user)
                @php
                    $p = $sesis[$user->id] ?? null;
                @endphp
                <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/30">
                    <td class="px-5 py-3 font-medium text-slate-800 dark:text-slate-200">{{ $user->name }}</td>
                    <td class="px-3 py-3 text-center">
                        @if(!$p)
                        <span class="text-xs font-semibold text-slate-600 bg-slate-100 dark:bg-zinc-800/50 dark:text-slate-400 px-2 py-1 rounded-lg">Belum Mengerjakan</span>
                        @elseif($p->status === 'mengerjakan')
                        <span class="text-xs font-semibold text-amber-700 bg-amber-50 dark:bg-amber-900/30 dark:text-amber-400 px-2 py-1 rounded-lg">Mengerjakan</span>
                        @elseif($p->status === 'selesai')
                        <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 dark:bg-emerald-900/30 dark:text-emerald-400 px-2 py-1 rounded-lg">Selesai</span>
                        @else
                        <span class="text-xs font-semibold text-red-700 bg-red-50 dark:bg-red-900/30 dark:text-red-400 px-2 py-1 rounded-lg">Timeout</span>
                        @endif
                    </td>
                    <td class="px-3 py-3 text-center text-slate-600 dark:text-slate-300">
                        @if($p)
                            {{ $p->jawabans_count }} / {{ $ujian->ujian_soals_count }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-3 py-3 text-center text-slate-500 text-xs">
                        {{ $p?->waktu_mulai?->format('H:i') ?? '-' }}
                    </td>
                    <td class="px-3 py-3 text-center font-bold">
                        @if(!$p)
                            <span class="text-slate-400">-</span>
                        @elseif(in_array($p->status, ['selesai', 'timeout']))
                            @if($p->belum_dikoreksi_count > 0)
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-[10px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded">Menunggu Koreksi</span>
                                    <a href="{{ route('guru.ujian.koreksi', ['id' => $ujian->id, 'peserta_id' => $p->id]) }}" class="text-[10px] text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1 rounded-md shadow-sm">Koreksi</a>
                                </div>
                            @else
                                <span class="{{ $p->skor >= 70 ? 'text-emerald-600' : 'text-amber-600' }}">{{ number_format($p->skor, 1) }}</span>
                                <div class="mt-1">
                                    <a href="{{ route('guru.ujian.koreksi', ['id' => $ujian->id, 'peserta_id' => $p->id]) }}" class="text-[10px] text-indigo-500 hover:text-indigo-700 underline font-normal">Review</a>
                                </div>
                            @endif
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada peserta yang ditugaskan untuk ujian ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
