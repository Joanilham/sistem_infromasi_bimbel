@extends('layouts.admin')

@section('title', 'Tambah Kelompok Belajar')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Tambah Kelompok</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm font-medium">Buat kelompok belajar baru di sistem.</p>
        </div>
        <a href="{{ route('kelompok-belajar.index') }}" class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-zinc-800 hover:bg-slate-100 dark:hover:bg-zinc-700 text-slate-500 dark:text-slate-400 transition-all">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden max-w-4xl mx-auto">
        <div class="p-8 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50">
            <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Formulir Kelompok</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-xs font-bold uppercase tracking-widest">Informasi Dasar Kelompok Belajar</p>
        </div>
        <form action="{{ route('kelompok-belajar.store') }}" method="POST">
            @csrf
            <div class="p-8 space-y-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 dark:text-slate-500 uppercase tracking-widest ml-1">Nama Kelompok <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_kelompok" required placeholder="Misal: Kelas 1, UTBK Intensif, dll" 
                        class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                    @error('nama_kelompok')
                        <p class="text-xs font-bold text-rose-500 mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="p-8 border-t border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50 flex items-center justify-end gap-4">
                <a href="{{ route('kelompok-belajar.index') }}" class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 hover:bg-slate-100 dark:hover:bg-zinc-800 rounded-2xl transition-all">Batal</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection