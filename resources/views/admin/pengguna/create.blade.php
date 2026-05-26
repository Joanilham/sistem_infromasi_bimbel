@extends('layouts.admin')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('pengguna.index') }}" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-500 hover:bg-indigo-100 hover:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Tambah Pengguna</h1>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium ml-11">Buat akun akses untuk Administrator atau Admin cabang.</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
        <form action="{{ route('pengguna.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Nama Pengguna --}}
                <div class="space-y-2">
                    <label for="name" class="text-sm font-bold text-slate-700 dark:text-slate-300">Nama Pengguna <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Misal: John Doe"
                           class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                </div>

                {{-- Username --}}
                <div class="space-y-2">
                    <label for="username" class="text-sm font-bold text-slate-700 dark:text-slate-300">Username <span class="text-rose-500">*</span></label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" required placeholder="johndoe123"
                           class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                </div>

                {{-- Email --}}
                <div class="space-y-2">
                    <label for="email" class="text-sm font-bold text-slate-700 dark:text-slate-300">Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="johndoe@example.com"
                           class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                </div>

                {{-- Level --}}
                <div class="space-y-2">
                    <label for="level" class="text-sm font-bold text-slate-700 dark:text-slate-300">Level / Role <span class="text-rose-500">*</span></label>
                    <select name="level" id="level" required class="no-tomselect w-full bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                        <option value="" disabled selected>Pilih Level</option>
                        <option value="Super Admin" {{ old('level') == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="Admin" {{ old('level') == 'Admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <label for="password" class="text-sm font-bold text-slate-700 dark:text-slate-300">Password <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" id="password" required placeholder="Minimal 8 karakter"
                           class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                </div>

                {{-- Konfirmasi Password --}}
                <div class="space-y-2">
                    <label for="password_confirmation" class="text-sm font-bold text-slate-700 dark:text-slate-300">Konfirmasi Password <span class="text-rose-500">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi password di atas"
                           class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                </div>

            </div>

            {{-- Permissions (Only show if Admin) --}}
            <div id="permissions-container-create" class="{{ old('level') == 'Admin' ? '' : 'hidden' }} mt-8 pt-8 border-t border-slate-100 dark:border-zinc-800">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Hak Akses Modul</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Pilih modul mana saja yang dapat dikelola oleh Admin ini.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- Akademik & Operasional --}}
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                        <input type="checkbox" name="permissions[]" value="manage_paket_bimbingan" class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Paket Bimbingan</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                        <input type="checkbox" name="permissions[]" value="manage_peserta_didik" class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Peserta & Kelompok</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                        <input type="checkbox" name="permissions[]" value="manage_guru" class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Data Guru</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                        <input type="checkbox" name="permissions[]" value="manage_absensi" class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Absensi</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                        <input type="checkbox" name="permissions[]" value="manage_jadwal" class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Jadwal</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                        <input type="checkbox" name="permissions[]" value="manage_keuangan" class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Keuangan</span>
                    </label>

                    {{-- Pengaturan Master (Terpisah) --}}
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                        <input type="checkbox" name="permissions[]" value="manage_master" class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Pengaturan Master</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                        <input type="checkbox" name="permissions[]" value="manage_bank" class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Rekening Bank</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                        <input type="checkbox" name="permissions[]" value="manage_kantor" class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Pengaturan Kantor</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                        <input type="checkbox" name="permissions[]" value="manage_periode" class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Pengaturan Periode</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                        <input type="checkbox" name="permissions[]" value="manage_landing_page" class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Landing Page</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                        <input type="checkbox" name="permissions[]" value="manage_pengguna" class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Admin / Pengguna</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                        <input type="checkbox" name="permissions[]" value="manage_backup" class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Backup Database</span>
                    </label>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-zinc-800">
                <a href="{{ route('pengguna.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-zinc-800 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/20 active:scale-95 transition-all">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const levelSelect = document.getElementById('level');
        const permContainer = document.getElementById('permissions-container-create');

        levelSelect.addEventListener('change', function() {
            if (this.value === 'Admin') {
                permContainer.classList.remove('hidden');
            } else {
                permContainer.classList.add('hidden');
            }
        });
    });
</script>
@endpush