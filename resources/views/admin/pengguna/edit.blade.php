@extends('layouts.admin')

@section('title', 'Edit Pengguna')

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
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Edit Pengguna</h1>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium ml-11">Perbarui data atau ubah hak akses pengguna ini.</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
        <form action="{{ route('pengguna.update', $pengguna->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Nama Pengguna --}}
                <div class="space-y-2">
                    <label for="name" class="text-sm font-bold text-slate-700 dark:text-slate-300">Nama Pengguna <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $pengguna->name) }}" required placeholder="Misal: John Doe"
                           class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                </div>

                {{-- Username --}}
                <div class="space-y-2">
                    <label for="username" class="text-sm font-bold text-slate-700 dark:text-slate-300">Username <span class="text-rose-500">*</span></label>
                    <input type="text" name="username" id="username" value="{{ old('username', $pengguna->username) }}" required placeholder="johndoe123"
                           class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                </div>

                {{-- Email --}}
                <div class="space-y-2">
                    <label for="email" class="text-sm font-bold text-slate-700 dark:text-slate-300">Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $pengguna->email) }}" required placeholder="johndoe@example.com"
                           class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                </div>

                {{-- Level --}}
                <div class="space-y-2">
                    <label for="level" class="text-sm font-bold text-slate-700 dark:text-slate-300">Level / Role <span class="text-rose-500">*</span></label>
                    <select name="level" id="level" required class="no-tomselect w-full bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                        <option value="" disabled>Pilih Level</option>
                        @if($pengguna->level === 'Super Admin' || strtolower(auth()->user()->level) === 'super admin')
                        <option value="Super Admin" {{ old('level', $pengguna->level) == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                        @endif
                        <option value="Admin" {{ old('level', $pengguna->level) == 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="Staff" {{ old('level', $pengguna->level) == 'Staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <label for="password" class="text-sm font-bold text-slate-700 dark:text-slate-300">Password Baru</label>
                    <input type="password" name="password" id="password" placeholder="Kosongkan jika tidak ingin diubah"
                           class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                </div>

                {{-- Konfirmasi Password --}}
                <div class="space-y-2">
                    <label for="password_confirmation" class="text-sm font-bold text-slate-700 dark:text-slate-300">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi password baru"
                           class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                </div>

            </div>

            @php
                $perms = is_array($pengguna->permissions) ? $pengguna->permissions : [];
                $oldPerms = old('permissions', $perms);
            @endphp

            {{-- Permissions (Only show if Admin) --}}
            <div id="permissions-container-edit" class="{{ old('level', $pengguna->level) == 'Admin' ? '' : 'hidden' }} mt-8 pt-8 border-t border-slate-100 dark:border-zinc-800">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Hak Akses Modul</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Pilih modul mana saja yang dapat dikelola oleh Admin ini.</p>
                </div>

                <div class="space-y-6">
                    {{-- Akademik & Operasional --}}
                    <div>
                        <h4 class="text-sm font-bold text-slate-600 dark:text-slate-400 mb-3 uppercase tracking-wider">Akademik & Operasional</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_paket_bimbingan" {{ in_array('manage_paket_bimbingan', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Paket Bimbingan</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_pendaftaran" {{ in_array('manage_pendaftaran', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Verifikasi Pendaftaran</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_peserta_didik" {{ in_array('manage_peserta_didik', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Peserta & Kelompok</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_guru" {{ in_array('manage_guru', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Data Guru</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_absensi" {{ in_array('manage_absensi', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Absensi</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_jadwal" {{ in_array('manage_jadwal', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Jadwal</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_rekapitulasi" {{ in_array('manage_rekapitulasi', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Rekapitulasi Data</span>
                            </label>
                        </div>
                    </div>

                    {{-- Keuangan --}}
                    <div>
                        <h4 class="text-sm font-bold text-slate-600 dark:text-slate-400 mb-3 uppercase tracking-wider">Keuangan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_keuangan" {{ in_array('manage_keuangan', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Semua Keuangan</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_pembayaran_siswa" {{ in_array('manage_pembayaran_siswa', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Pembayaran Siswa</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_pemasukan" {{ in_array('manage_pemasukan', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Pemasukan</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_pengeluaran" {{ in_array('manage_pengeluaran', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Pengeluaran</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_tagihan" {{ in_array('manage_tagihan', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Tagihan Siswa</span>
                            </label>
                        </div>
                    </div>

                    {{-- Pengaturan Sistem --}}
                    <div>
                        <h4 class="text-sm font-bold text-slate-600 dark:text-slate-400 mb-3 uppercase tracking-wider">Pengaturan Sistem & Lainnya</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_master" {{ in_array('manage_master', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Pengaturan Master</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_bank" {{ in_array('manage_bank', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Rekening Bank</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_kantor" {{ in_array('manage_kantor', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Pengaturan Kantor</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_periode" {{ in_array('manage_periode', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Pengaturan Periode</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_landing_page" {{ in_array('manage_landing_page', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Landing Page</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_pengguna" {{ in_array('manage_pengguna', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Admin / Pengguna</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_pengumuman" {{ in_array('manage_pengumuman', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Berita & Informasi</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_backup" {{ in_array('manage_backup', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Backup Database</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="manage_audit_logs" {{ in_array('manage_audit_logs', $oldPerms) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 focus:ring-indigo-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kelola Log Aktivitas</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-zinc-800">
                <a href="{{ route('pengguna.index') }}" class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-white bg-rose-500 hover:bg-rose-600 rounded-2xl transition-all shadow-xl shadow-rose-500/20 active:scale-95">
                    Batal
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95">
                    Simpan Perubahan
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
        const permContainer = document.getElementById('permissions-container-edit');

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