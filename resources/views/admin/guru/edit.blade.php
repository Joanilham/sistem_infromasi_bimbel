@extends('layouts.admin')

@section('title', 'Edit Data Guru')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Edit Data Guru</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm font-medium">Ubah informasi tenaga pengajar pada sistem.</p>
        </div>
        <a href="{{ url()->previous() == route('manajemen-guru.edit', $guru->id) ? route('manajemen-guru.index') : url()->previous() }}" class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-zinc-800 hover:bg-slate-100 dark:hover:bg-zinc-700 text-slate-500 dark:text-slate-400 transition-all">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden w-full" x-data="{ status: '{{ old('status', $guru->status ?? 'Aktif') }}' }">
        <form action="{{ route('manajemen-guru.update', $guru->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-8 sm:p-10 space-y-10">
                
                {{-- Data Pribadi --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-widest text-indigo-500 flex items-center gap-2 border-b border-slate-100 dark:border-zinc-800 pb-2">
                        Data Pribadi
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2 sm:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required value="{{ old('name', $guru->name) }}" placeholder="Nama lengkap guru" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-Laki" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">NIP</label>
                            <input type="text" name="nip" value="{{ old('nip', $guru->nip) }}" placeholder="Nomor Induk Pegawai" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'')" maxlength="20" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">No. Telp</label>
                            <input type="tel" name="no_telp" value="{{ old('no_telp', $guru->no_telp) }}" placeholder="08xxxxxxxxxx" inputmode="numeric" pattern="[0-9]{8,12}" maxlength="12" oninput="this.value=this.value.replace(/[^0-9]/g,'')" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                        <div class="space-y-2 sm:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                            <textarea name="alamat" rows="2" placeholder="Alamat lengkap guru" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white custom-scrollbar">{{ old('alamat', $guru->alamat) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Data Kepegawaian --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-widest text-emerald-500 flex items-center gap-2 border-b border-slate-100 dark:border-zinc-800 pb-2">
                        Data Kepegawaian & Mata Pelajaran
                    </h3>
                    
                    @php
                        $assignedMapelIds = old('mata_pelajaran_ids', $guru->mataPelajarans->pluck('id')->toArray());
                    @endphp

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">
                                Mata Pelajaran yang Diampu <span class="text-rose-500">*</span>
                                <span class="text-slate-400 font-normal normal-case ml-1">(Dapat memilih 1 atau lebih pelajaran)</span>
                            </label>
                            @error('mata_pelajaran_ids')
                                <span class="text-xs text-rose-500 font-bold">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5 max-h-48 overflow-y-auto p-3 rounded-2xl border border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-950/50 custom-scrollbar">
                            @forelse($allMapels ?? [] as $mapelItem)
                                <label class="flex items-center gap-2.5 p-2 rounded-xl border border-slate-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 cursor-pointer hover:border-indigo-400 dark:hover:border-indigo-600 transition-all select-none">
                                    <input type="checkbox" name="mata_pelajaran_ids[]" value="{{ $mapelItem->id }}"
                                           {{ in_array($mapelItem->id, $assignedMapelIds) ? 'checked' : '' }}
                                           class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900">
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate" title="{{ $mapelItem->nama }}">
                                        {{ $mapelItem->nama }}
                                    </span>
                                </label>
                            @empty
                                <div class="col-span-full text-center py-2 text-xs text-slate-400 font-medium">
                                    Belum ada data mata pelajaran di master data.
                                </div>
                            @endforelse
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
                            <div class="space-y-1.5 sm:col-span-1">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">+ Tambah Mapel Baru</label>
                                <input type="text" name="new_matapelajaran" value="{{ old('new_matapelajaran') }}" placeholder="Contoh: Robotika..." class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-xs font-bold py-2.5 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                            </div>

                            <div class="space-y-1.5 sm:col-span-1">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Status Kepegawaian <span class="text-rose-500">*</span></label>
                                <select name="status" x-model="status" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-xs font-bold py-2.5 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                                    <option value="Aktif">Aktif</option>
                                    <option value="Keluar">Keluar</option>
                                </select>
                            </div>

                            <div class="space-y-1.5 sm:col-span-1">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Landing Page</label>
                                <label class="flex items-center gap-3 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-2xl py-2.5 px-4 cursor-pointer hover:bg-slate-100 dark:hover:bg-zinc-900 transition-colors">
                                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $guru->is_featured) ? 'checked' : '' }} class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900">
                                    <span class="text-xs font-bold text-slate-800 dark:text-white">Guru Unggulan</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Keluar Section --}}
                    <div x-show="status === 'Keluar'" x-collapse>
                        <div class="p-6 bg-rose-50 dark:bg-rose-900/10 border border-rose-100 dark:border-rose-900/50 rounded-2xl space-y-6 mt-4">
                            <h4 class="text-[10px] font-black text-rose-600 dark:text-rose-500 uppercase tracking-widest border-b border-rose-200/50 pb-2">Informasi Keluar</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-black text-rose-500 uppercase tracking-widest ml-1">Tanggal Keluar <span class="text-rose-500">*</span></label>
                                    <input type="date" name="tanggal_keluar" :required="status === 'Keluar'" value="{{ old('tanggal_keluar', $guru->tanggal_keluar) }}" class="w-full border bg-white dark:bg-zinc-950 border-rose-200 dark:border-rose-900/50 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-slate-800 dark:text-white">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-black text-rose-500 uppercase tracking-widest ml-1">Alasan Keluar <span class="text-rose-500">*</span></label>
                                    <textarea name="alasan_keluar" :required="status === 'Keluar'" rows="2" placeholder="Tuliskan alasan keluar..." class="w-full border bg-white dark:bg-zinc-950 border-rose-200 dark:border-rose-900/50 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-slate-800 dark:text-white custom-scrollbar">{{ old('alasan_keluar', $guru->alasan_keluar) }}</textarea>
                                </div>
                            </div>
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
                            <input type="email" name="email" required value="{{ old('email', $guru->email) }}" placeholder="Email aktif" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Password</label>
                            <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password baru (jika diubah)" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                    </div>
                </div>

            </div>
            
            {{-- Footer --}}
            <div class="px-8 sm:px-10 py-6 bg-slate-50 dark:bg-zinc-950 border-t border-slate-200 dark:border-zinc-800 flex items-center justify-end gap-4">
                <a href="{{ url()->previous() == route('manajemen-guru.edit', $guru->id) ? route('manajemen-guru.index') : url()->previous() }}" class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-white bg-rose-500 hover:bg-rose-600 rounded-2xl transition-all shadow-xl shadow-rose-500/20 active:scale-95">Batal</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

