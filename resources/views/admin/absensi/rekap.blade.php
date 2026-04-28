@extends('layouts.admin')
@section('title', 'Rekap Absensi')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Rekap Absensi</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Ringkasan kehadiran siswa per bulan</p>
    </div>
    <div class="flex gap-3 items-center">
        <form method="GET" class="flex items-center gap-2">
            <select name="bulan" class="bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl px-3 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200">
                @foreach(range(1,12) as $b)
                <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}</option>
                @endforeach
            </select>
            <input type="number" name="tahun" value="{{ $tahun }}" min="2020" max="2050"
                class="bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl px-3 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 w-24">
            <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-500 text-white text-sm font-bold hover:bg-indigo-600 transition-colors shadow-md shadow-indigo-500/30">Filter</button>
        </form>
        <a href="{{ route('absensi.index') }}" class="px-4 py-2 rounded-xl bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-colors">📋 Harian</a>
    </div>
</div>

<div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-slate-100 dark:border-zinc-800 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-zinc-800 text-xs uppercase text-slate-500 dark:text-slate-400 tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Nama Siswa</th>
                    <th class="px-4 py-3 text-left">NISN</th>
                    <th class="px-4 py-3 text-center">Hadir</th>
                    <th class="px-4 py-3 text-center">Izin</th>
                    <th class="px-4 py-3 text-center">Sakit</th>
                    <th class="px-4 py-3 text-center">Alpha</th>
                    <th class="px-4 py-3 text-center">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                @forelse($pesertaDidiks as $p)
                <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors">
                    <td class="px-4 py-3 text-slate-500">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 font-semibold text-slate-800 dark:text-white">{{ $p->nama_lengkap }}</td>
                    <td class="px-4 py-3 text-slate-500 font-mono text-xs">{{ $p->nisn }}</td>
                    <td class="px-4 py-3 text-center"><span class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-bold">{{ $p->total_hadir }}</span></td>
                    <td class="px-4 py-3 text-center"><span class="px-2 py-1 rounded-lg bg-indigo-100 text-indigo-700 text-xs font-bold">{{ $p->total_izin }}</span></td>
                    <td class="px-4 py-3 text-center"><span class="px-2 py-1 rounded-lg bg-amber-100 text-amber-700 text-xs font-bold">{{ $p->total_sakit }}</span></td>
                    <td class="px-4 py-3 text-center"><span class="px-2 py-1 rounded-lg bg-rose-100 text-rose-700 text-xs font-bold">{{ $p->total_alpha }}</span></td>
                    <td class="px-4 py-3 text-center font-bold text-slate-800 dark:text-white">{{ $p->total_hadir + $p->total_izin + $p->total_sakit + $p->total_alpha }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-slate-400">Tidak ada data siswa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
