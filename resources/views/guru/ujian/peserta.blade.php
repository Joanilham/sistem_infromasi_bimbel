@extends('layouts.guru')
@section('title', 'Atur Peserta - ' . $ujian->judul)
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
        <a href="{{ route('guru.ujian.show', $ujian->id) }}" class="text-indigo-600 text-sm inline-flex items-center gap-1 mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Kembali
        </a>
        <h2 class="text-xl font-bold text-slate-800 dark:text-white">Atur Peserta Ujian</h2>
        <p class="text-sm text-slate-500 mt-1">{{ $ujian->judul }}</p>
    </div>
    <form method="POST" action="{{ route('guru.ujian.peserta.store', $ujian->id) }}" class="space-y-6">
        @csrf
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-4">Per Kelompok Belajar</h3>
            @if($kelompoks->isEmpty())
            <p class="text-sm text-slate-400">Belum ada kelompok belajar.</p>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                @foreach($kelompoks as $k)
                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 dark:border-zinc-800 cursor-pointer hover:bg-slate-50 dark:hover:bg-zinc-800/50">
                    <input type="checkbox" name="kelompok_ids[]" value="{{ $k->id }}" {{ in_array($k->id, $assignedKelompokIds) ? 'checked' : '' }} class="w-4 h-4 text-violet-600 rounded">
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-200">{{ $k->nama_kelompok }}</span>
                </label>
                @endforeach
            </div>
            @endif
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6" x-data="{ show: true }">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">Per Siswa Individual</h3>
                <button type="button" @click="show=!show" class="text-xs text-indigo-600 font-medium" x-text="show?'Sembunyikan':'Tampilkan'"></button>
            </div>
            <div x-show="show" x-transition class="max-h-60 overflow-y-auto border border-slate-100 dark:border-zinc-800 rounded-xl p-2 space-y-1">
                @foreach($siswaList as $siswa)
                <label class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-50 dark:hover:bg-zinc-800/50 cursor-pointer">
                    <input type="checkbox" name="siswa_ids[]" value="{{ $siswa->id }}" {{ in_array($siswa->id, $assignedUserIds) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded">
                    <span class="text-sm text-slate-700 dark:text-slate-200">{{ $siswa->name }}</span>
                </label>
                @endforeach
            </div>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('guru.ujian.show', $ujian->id) }}" class="px-6 py-2.5 border border-slate-200 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-50">Batal</a>
            <button type="submit" class="px-8 py-2.5 bg-gradient-to-r from-violet-500 to-purple-600 text-white rounded-xl text-sm font-semibold shadow-md">Simpan Peserta</button>
        </div>
    </form>
</div>
@endsection
