@extends('layouts.admin')

@section('title', 'Tambah Berita & Informasi')

@section('content')
<div class="space-y-6">

    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.pengumuman.index') }}" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-500 hover:bg-indigo-100 hover:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Tambah Berita</h1>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium ml-11">Buat pengumuman atau berita baru untuk ditampilkan di aplikasi mobile.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
        <form action="{{ route('admin.pengumuman.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                {{-- Judul --}}
                <div class="space-y-2">
                    <label for="judul" class="text-sm font-bold text-slate-700 dark:text-slate-300">Judul Berita / Informasi <span class="text-rose-500">*</span></label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required placeholder="Masukkan judul..."
                           class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                </div>

                {{-- Isi / Konten --}}
                <div class="space-y-2">
                    <label for="isi" class="text-sm font-bold text-slate-700 dark:text-slate-300">Isi Konten <span class="text-rose-500">*</span></label>
                    <textarea name="isi" id="isi" rows="6" required placeholder="Tuliskan detail berita atau informasi..."
                              class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">{{ old('isi') }}</textarea>
                </div>

                {{-- Foto / Cover --}}
                <div class="space-y-2">
                    <label for="foto" class="text-sm font-bold text-slate-700 dark:text-slate-300">Foto Cover (Opsional)</label>
                    <input type="file" name="foto" id="foto" accept="image/*"
                           class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                {{-- Status Aktif --}}
                <div class="space-y-2">
                    <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 dark:border-zinc-800 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors w-max">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-700 focus:ring-indigo-500">
                        <div>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300 block">Langsung Aktifkan Berita</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">Berita akan langsung tampil di beranda Mobile Apps.</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-zinc-800">
                <a href="{{ route('admin.pengumuman.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-zinc-800 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/20 active:scale-95 transition-all">
                    Simpan Berita
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
