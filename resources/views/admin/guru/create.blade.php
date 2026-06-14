@extends('layouts.admin')

@section('title', 'Tambah Guru Baru')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Tambah Guru Baru</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm font-medium">Isi seluruh data guru dengan lengkap dan benar.</p>
        </div>
        <a href="{{ route('manajemen-guru.index') }}" class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-zinc-800 hover:bg-slate-100 dark:hover:bg-zinc-700 text-slate-500 dark:text-slate-400 transition-all">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden w-full" x-data="{ status: 'Aktif' }">
        <form action="{{ route('manajemen-guru.store') }}" method="POST">
            @csrf
            <div class="p-8 sm:p-10 space-y-10">
                
                {{-- Data Pribadi --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-widest text-indigo-500 flex items-center gap-2 border-b border-slate-100 dark:border-zinc-800 pb-2">
                        Data Pribadi
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2 sm:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required value="{{ old('name') }}" placeholder="Nama lengkap guru" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-Laki" {{ old('jenis_kelamin') == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">NIP</label>
                            <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Nomor Induk Pegawai" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'')" maxlength="20" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">No. Telp</label>
                            <input type="tel" name="no_telp" value="{{ old('no_telp') }}" placeholder="08xxxxxxxxxx" inputmode="numeric" pattern="[0-9]{8,12}" maxlength="12" oninput="this.value=this.value.replace(/[^0-9]/g,'')" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                        <div class="space-y-2 sm:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                            <textarea name="alamat" rows="2" placeholder="Alamat lengkap guru" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white custom-scrollbar">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Data Kepegawaian --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-widest text-emerald-500 flex items-center gap-2 border-b border-slate-100 dark:border-zinc-800 pb-2">
                        Data Kepegawaian
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Mata Pelajaran <span class="text-rose-500">*</span></label>
                            <input type="text" name="matapelajaran" required value="{{ old('matapelajaran') }}" placeholder="Mata pelajaran" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Tampilkan di Landing Page</label>
                            <label class="flex items-center gap-3 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-2xl py-3 px-4 cursor-pointer hover:bg-slate-100 dark:hover:bg-zinc-900 transition-colors">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-5 h-5 rounded-md text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900">
                                <span class="text-sm font-bold text-slate-800 dark:text-white">Jadikan Guru Unggulan</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Akun Login --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-widest text-amber-500 flex items-center gap-2 border-b border-slate-100 dark:border-zinc-800 pb-2">
                        Akun Login
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2 sm:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Email <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" required value="{{ old('email') }}" placeholder="Email aktif" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                            <p class="text-[10px] text-amber-600 dark:text-amber-500 flex items-center gap-1.5 mt-1 font-bold ml-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Gunakan email aktif agar guru bisa menerima link Reset Password jika lupa.
                            </p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Password <span class="text-rose-500">*</span></label>
                            <input type="password" name="password" required placeholder="Min 8 Karakter" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Konfirmasi Password <span class="text-rose-500">*</span></label>
                            <input type="password" name="password_confirmation" required placeholder="Ulangi password" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                    </div>
                </div>

            </div>
            
            {{-- Footer --}}
            <div class="px-8 sm:px-10 py-6 bg-slate-50 dark:bg-zinc-950 border-t border-slate-200 dark:border-zinc-800 flex items-center justify-end gap-4">
                <a href="{{ route('manajemen-guru.index') }}" class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-white bg-rose-500 hover:bg-rose-600 rounded-2xl transition-all shadow-xl shadow-rose-500/20 active:scale-95">Batal</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95">Simpan Data Guru</button>
            </div>
        </form>
    </div>
</div>
@endsection

