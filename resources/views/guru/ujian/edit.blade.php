@extends('layouts.guru')

@section('title', 'Edit Ujian - ' . $ujian->judul)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
        <a href="{{ route('guru.ujian.show', $ujian->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 text-sm inline-flex items-center gap-1 mb-3 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <h2 class="text-xl font-bold text-slate-800 dark:text-white">Edit Ujian</h2>
    </div>

    <form method="POST" action="{{ route('guru.ujian.update', $ujian->id) }}" class="space-y-6">
        @csrf @method('PUT')

        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6 space-y-5">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Judul Ujian <span class="text-red-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul', $ujian->judul) }}" required
                    class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-4 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-4 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('deskripsi', $ujian->deskripsi) }}</textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Durasi (menit)</label>
                    <input type="number" name="durasi" value="{{ old('durasi', $ujian->durasi) }}" required min="5" max="300"
                        class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-4 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Mode Ujian</label>
                    <select name="mode" class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-4 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        <option value="latihan" {{ old('mode', $ujian->mode) == 'latihan' ? 'selected' : '' }}>📝 Latihan</option>
                        <option value="resmi" {{ old('mode', $ujian->mode) == 'resmi' ? 'selected' : '' }}>🏅 Resmi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Waktu Mulai</label>
                    <input type="datetime-local" name="waktu_mulai" value="{{ old('waktu_mulai', $ujian->waktu_mulai?->format('Y-m-d\TH:i')) }}"
                        class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-4 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Waktu Selesai</label>
                    <input type="datetime-local" name="waktu_selesai" value="{{ old('waktu_selesai', $ujian->waktu_selesai?->format('Y-m-d\TH:i')) }}"
                        class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-4 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6 space-y-3">
            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">Konfigurasi</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 dark:border-zinc-800 cursor-pointer">
                    <input type="checkbox" name="acak_soal" value="1" {{ old('acak_soal', $ujian->acak_soal) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded">
                    <div><div class="text-sm font-medium text-slate-700 dark:text-slate-200">Acak Urutan Soal</div></div>
                </label>
                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 dark:border-zinc-800 cursor-pointer">
                    <input type="checkbox" name="acak_opsi" value="1" {{ old('acak_opsi', $ujian->acak_opsi) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded">
                    <div><div class="text-sm font-medium text-slate-700 dark:text-slate-200">Acak Opsi Jawaban</div></div>
                </label>
                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 dark:border-zinc-800 cursor-pointer">
                    <input type="checkbox" name="tampilkan_hasil" value="1" {{ old('tampilkan_hasil', $ujian->tampilkan_hasil) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded">
                    <div><div class="text-sm font-medium text-slate-700 dark:text-slate-200">Tampilkan Hasil</div></div>
                </label>
                <div class="p-3 rounded-xl border border-slate-100 dark:border-zinc-800">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Token</label>
                    <input type="text" name="token" value="{{ old('token', $ujian->token) }}" placeholder="Kosongkan jika tidak perlu"
                        class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="p-3 rounded-xl border border-slate-100 dark:border-zinc-800">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Batas Mengerjakan (Kali)</label>
                    <input type="number" name="limit_attempt" value="{{ old('limit_attempt', $ujian->limit_attempt) }}" required min="0"
                        class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Isi 0 untuk unlimited.</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between pt-2 pb-8">
            <a href="{{ route('guru.ujian.show', $ujian->id) }}" class="px-5 sm:px-6 py-2.5 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-600 rounded-xl text-sm font-medium shadow-sm hover:bg-slate-50 dark:hover:bg-zinc-700 text-center transition-colors shrink-0">Batal</a>
            <button type="submit" class="px-5 sm:px-8 py-2.5 bg-gradient-to-r from-indigo-500 to-blue-600 text-white rounded-xl text-sm font-semibold shadow-md hover:shadow-lg transition-all flex items-center justify-center">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
