@extends('layouts.guru')

@section('title', 'Buat Ujian Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
        <a href="{{ route('guru.ujian.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 text-sm inline-flex items-center gap-1 mb-3 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <h2 class="text-xl font-bold text-slate-800 dark:text-white">Buat Ujian Baru</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Langkah 1: Isi informasi dasar ujian, lalu pilih soal di langkah berikutnya</p>
    </div>

    <form method="POST" action="{{ route('guru.ujian.store') }}" class="space-y-6">
        @csrf

        {{-- Info Dasar --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6 space-y-5">
            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 bg-violet-100 dark:bg-violet-900/40 rounded-lg flex items-center justify-center text-violet-600 dark:text-violet-400 text-xs font-bold">1</span>
                Informasi Dasar
            </h3>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Judul Ujian <span class="text-red-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}" required placeholder="Contoh: UTS Matematika Kelas 10"
                    class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-4 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('judul') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat ujian..."
                    class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-4 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Durasi (menit) <span class="text-red-500">*</span></label>
                    <input type="number" name="durasi" value="{{ old('durasi', 60) }}" required min="5" max="300"
                        class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-4 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('durasi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Mode Ujian</label>
                    <select name="mode" class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-4 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="latihan" {{ old('mode') == 'latihan' ? 'selected' : '' }}>📝 Latihan</option>
                        <option value="resmi" {{ old('mode') == 'resmi' ? 'selected' : '' }}>🏅 Resmi</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Jadwal --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6 space-y-5">
            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 bg-violet-100 dark:bg-violet-900/40 rounded-lg flex items-center justify-center text-violet-600 dark:text-violet-400 text-xs font-bold">2</span>
                Jadwal Ujian
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Waktu Mulai</label>
                    <input type="datetime-local" name="waktu_mulai" value="{{ old('waktu_mulai') }}"
                        class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-4 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('waktu_mulai') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Waktu Selesai</label>
                    <input type="datetime-local" name="waktu_selesai" value="{{ old('waktu_selesai') }}"
                        class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-4 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('waktu_selesai') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <p class="text-xs text-slate-400 dark:text-slate-500">💡 Kosongkan untuk menyimpan sebagai draft. Jadwal bisa diatur nanti.</p>
        </div>

        {{-- Konfigurasi --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 bg-violet-100 dark:bg-violet-900/40 rounded-lg flex items-center justify-center text-violet-600 dark:text-violet-400 text-xs font-bold">3</span>
                Konfigurasi
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 dark:border-zinc-800 hover:bg-slate-50 dark:hover:bg-zinc-800/50 cursor-pointer transition-colors">
                    <input type="checkbox" name="acak_soal" value="1" {{ old('acak_soal') ? 'checked' : '' }}
                        class="w-4 h-4 text-indigo-600 border-slate-300 dark:border-zinc-600 rounded focus:ring-indigo-500">
                    <div>
                        <div class="text-sm font-medium text-slate-700 dark:text-slate-200">Acak Urutan Soal</div>
                        <div class="text-xs text-slate-400 dark:text-slate-500">Soal ditampilkan dalam urutan acak per siswa</div>
                    </div>
                </label>

                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 dark:border-zinc-800 hover:bg-slate-50 dark:hover:bg-zinc-800/50 cursor-pointer transition-colors">
                    <input type="checkbox" name="acak_opsi" value="1" {{ old('acak_opsi') ? 'checked' : '' }}
                        class="w-4 h-4 text-indigo-600 border-slate-300 dark:border-zinc-600 rounded focus:ring-indigo-500">
                    <div>
                        <div class="text-sm font-medium text-slate-700 dark:text-slate-200">Acak Opsi Jawaban</div>
                        <div class="text-xs text-slate-400 dark:text-slate-500">Urutan opsi A–E diacak per siswa</div>
                    </div>
                </label>

                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 dark:border-zinc-800 hover:bg-slate-50 dark:hover:bg-zinc-800/50 cursor-pointer transition-colors">
                    <input type="checkbox" name="tampilkan_hasil" value="1" {{ old('tampilkan_hasil', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-indigo-600 border-slate-300 dark:border-zinc-600 rounded focus:ring-indigo-500">
                    <div>
                        <div class="text-sm font-medium text-slate-700 dark:text-slate-200">Tampilkan Hasil</div>
                        <div class="text-xs text-slate-400 dark:text-slate-500">Siswa bisa melihat skor langsung setelah submit</div>
                    </div>
                </label>

                <div class="p-3 rounded-xl border border-slate-100 dark:border-zinc-800">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Token Ujian</label>
                    <input type="text" name="token" value="{{ old('token') }}" placeholder="Kosongkan jika tidak perlu"
                        class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Kode yang harus dimasukkan siswa untuk memulai</p>
                </div>

                <div class="p-3 rounded-xl border border-slate-100 dark:border-zinc-800">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Batas Mengerjakan (Kali) <span class="text-red-500">*</span></label>
                    <input type="number" name="limit_attempt" value="{{ old('limit_attempt', 1) }}" required min="0"
                        class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Isi 0 untuk tanpa batas (unlimited).</p>
                    @error('limit_attempt') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-between pt-2 pb-8">
            <a href="{{ route('guru.ujian.index') }}" class="px-5 sm:px-6 py-2.5 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-slate-300 rounded-xl text-sm font-medium shadow-sm hover:bg-slate-50 dark:hover:bg-zinc-700 text-center transition-colors shrink-0">Batal</a>
            <button type="submit" class="px-5 sm:px-8 py-2.5 bg-gradient-to-r from-violet-500 to-purple-600 text-white rounded-xl text-sm font-semibold shadow-md shadow-violet-500/25 hover:from-violet-600 hover:to-purple-700 hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2">
                Simpan & Pilih Soal
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </button>
        </div>
    </form>
</div>
@endsection
