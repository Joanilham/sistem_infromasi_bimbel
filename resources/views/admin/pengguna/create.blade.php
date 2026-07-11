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
                    <p class="text-[11px] text-amber-600 dark:text-amber-500 flex items-center gap-1.5 mt-1 font-medium">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pastikan menggunakan email aktif agar pengguna bisa melakukan reset password.
                    </p>
                </div>

                {{-- Level --}}
                <div class="space-y-2">
                    <label for="level" class="text-sm font-bold text-slate-700 dark:text-slate-300">Level / Role <span class="text-rose-500">*</span></label>
                    <select name="level" id="level" required class="no-tomselect w-full bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                        <option value="" disabled selected>Pilih Level</option>
                        @if(strtolower(auth()->user()->level) === 'super admin')
                            <option value="Admin" {{ old('level') == 'Admin' ? 'selected' : '' }}>Admin</option>
                        @endif
                        <option value="Staff" {{ old('level') == 'Staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>

                {{-- Kantor Cabang (Hanya untuk Admin & Staff) --}}
                <div class="space-y-2" id="kantor-container" class="{{ in_array(old('level'), ['Admin', 'Staff']) ? '' : 'hidden' }}">
                    <label for="kantor_id" class="text-sm font-bold text-slate-700 dark:text-slate-300">Kantor Cabang</label>
                    <select name="kantor_id" id="kantor_id" class="no-tomselect w-full bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                        <option value="" selected>Semua Cabang (Jika dikosongkan)</option>
                        @foreach($kantors as $kantor)
                            <option value="{{ $kantor->id }}" {{ old('kantor_id') == $kantor->id ? 'selected' : '' }}>
                                {{ $kantor->nama_kantor }}
                            </option>
                        @endforeach
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

            @php
                $modules = [
                    'Akademik & Operasional' => [
                        ['key' => 'paket_bimbingan', 'label' => 'Paket Bimbingan', 'actions' => ['manage', 'create', 'update', 'delete']],
                        ['key' => 'kelompok_belajar', 'label' => 'Kelompok Belajar', 'actions' => ['manage', 'create', 'update', 'delete']],
                        ['key' => 'pendaftaran', 'label' => 'Verifikasi Pendaftaran', 'actions' => ['manage', 'create', 'update', 'delete']],
                        ['key' => 'peserta_didik', 'label' => 'Peserta Didik', 'actions' => ['manage', 'create', 'update', 'delete']],
                        ['key' => 'guru', 'label' => 'Data Guru', 'actions' => ['manage', 'create', 'update', 'delete']],
                        ['key' => 'absensi', 'label' => 'Absensi', 'actions' => ['manage', 'create', 'update', 'delete']],
                        ['key' => 'jadwal', 'label' => 'Jadwal', 'actions' => ['manage', 'create', 'update', 'delete']],
                        ['key' => 'rekapitulasi', 'label' => 'Rekapitulasi Data', 'actions' => ['manage']],
                        ['key' => 'pengumuman', 'label' => 'Berita & Informasi', 'actions' => ['manage', 'create', 'update', 'delete']],
                    ],
                    'Keuangan' => [
                        ['key' => 'keuangan', 'label' => 'Akses Menu Keuangan', 'actions' => ['manage']],
                        ['key' => 'pembayaran_siswa', 'label' => 'Pembayaran Siswa', 'actions' => ['manage', 'create', 'update', 'delete']],
                        ['key' => 'pemasukan', 'label' => 'Pemasukan', 'actions' => ['manage', 'create', 'update', 'delete']],
                        ['key' => 'pengeluaran', 'label' => 'Pengeluaran', 'actions' => ['manage', 'create', 'update', 'delete']],
                        ['key' => 'tagihan', 'label' => 'Tagihan Siswa', 'actions' => ['manage', 'create', 'update', 'delete']],
                        ['key' => 'bank', 'label' => 'Rekening Bank', 'actions' => ['manage', 'create', 'update', 'delete']],
                    ],
                    'Pengaturan Sistem & Lainnya' => [
                        ['key' => 'master', 'label' => 'Pengaturan Master', 'actions' => ['manage', 'update']],
                        ['key' => 'kantor', 'label' => 'Pengaturan Kantor', 'actions' => ['manage', 'create', 'update', 'delete']],
                        ['key' => 'periode', 'label' => 'Pengaturan Periode', 'actions' => ['manage', 'create', 'update', 'delete']],
                        ['key' => 'landing_page', 'label' => 'Landing Page', 'actions' => ['manage', 'create', 'update', 'delete']],
                        ['key' => 'pengguna', 'label' => 'Kelola Admin / Staff', 'actions' => ['manage', 'create', 'update', 'delete']],
                        ['key' => 'backup', 'label' => 'Backup Database', 'actions' => ['manage', 'create', 'delete']],
                        ['key' => 'audit_logs', 'label' => 'Log Aktivitas', 'actions' => ['manage', 'delete']],
                        ['key' => 'recycle_bin', 'label' => 'Recycle Bin', 'actions' => ['manage', 'update', 'delete']],
                    ]
                ];

                $groupDescriptions = [
                    'Akademik & Operasional' => 'Kelola pendaftaran, siswa, guru, jadwal, dan absensi harian.',
                    'Keuangan' => 'Akses penuh untuk mengelola tagihan, pembayaran, pemasukan, serta pengeluaran.',
                    'Pengaturan Sistem & Lainnya' => 'Hak akses khusus konfigurasi master, log aktivitas, dan pengaturan akun.',
                ];
                $oldPerms = old('permissions', []);
            @endphp

            {{-- Permissions (Show for Admin and Staff) --}}
            <div id="permissions-container-create" class="{{ in_array(old('level'), ['Admin', 'Staff']) ? '' : 'hidden' }} mt-8 pt-8 border-t border-slate-100 dark:border-zinc-800">
                <div class="mb-4 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">Hak Akses Modul</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Beri centang pada aksi yang diperbolehkan untuk pengguna ini.</p>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="document.querySelectorAll('input[name=\'permissions[]\']').forEach(cb => cb.checked = true)" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-500/10 dark:hover:bg-emerald-500/20 px-4 py-2 rounded-xl transition-colors border border-emerald-100 dark:border-emerald-500/20 flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            Pilih Semua Fitur
                        </button>
                        <button type="button" onclick="document.querySelectorAll('input[name=\'permissions[]\']').forEach(cb => cb.checked = false)" class="text-xs font-bold text-rose-600 dark:text-rose-400 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 dark:bg-rose-500/10 dark:hover:bg-rose-500/20 px-4 py-2 rounded-xl transition-colors border border-rose-100 dark:border-rose-500/20 flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            Kosongkan Semua
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto border border-slate-200 dark:border-zinc-700 rounded-2xl">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-slate-50 dark:bg-zinc-800/80 text-slate-600 dark:text-slate-400 font-bold">
                            <tr>
                                <th class="p-4 border-b border-slate-200 dark:border-zinc-700">Nama Modul</th>
                                <th class="p-4 border-b border-slate-200 dark:border-zinc-700 text-center w-24">
                                    <div class="flex flex-col items-center">
                                        <span>Lihat</span>
                                        <span class="text-[9px] font-normal text-slate-400 mt-1 normal-case leading-tight">Melihat /<br>Membaca data</span>
                                    </div>
                                </th>
                                <th class="p-4 border-b border-slate-200 dark:border-zinc-700 text-center w-24">
                                    <div class="flex flex-col items-center">
                                        <span>Tambah</span>
                                        <span class="text-[9px] font-normal text-slate-400 mt-1 normal-case leading-tight">Membuat<br>data baru</span>
                                    </div>
                                </th>
                                <th class="p-4 border-b border-slate-200 dark:border-zinc-700 text-center w-24">
                                    <div class="flex flex-col items-center">
                                        <span>Edit</span>
                                        <span class="text-[9px] font-normal text-slate-400 mt-1 normal-case leading-tight">Mengubah<br>data yang ada</span>
                                    </div>
                                </th>
                                <th class="p-4 border-b border-slate-200 dark:border-zinc-700 text-center w-24">
                                    <div class="flex flex-col items-center">
                                        <span>Hapus</span>
                                        <span class="text-[9px] font-normal text-slate-400 mt-1 normal-case leading-tight">Menghapus<br>data sistem</span>
                                    </div>
                                </th>
                                <th class="p-4 border-b border-slate-200 dark:border-zinc-700 text-center w-24 border-l border-slate-200 dark:border-zinc-700 bg-slate-100 dark:bg-zinc-800 text-xs">
                                    <div class="flex flex-col items-center">
                                        <span>Pilih Semua</span>
                                        <span class="text-[9px] font-normal text-slate-400 mt-1 normal-case leading-tight">Centang<br>satu baris</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800 text-slate-700 dark:text-slate-300">
                            @foreach($modules as $group => $items)
                                @php $groupSlug = \Illuminate\Support\Str::slug($group); @endphp
                                <tr class="bg-slate-50/80 dark:bg-zinc-800/60 border-y border-slate-200 dark:border-zinc-700">
                                    <td colspan="6" class="px-4 py-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex flex-col">
                                                <span class="font-black text-[12px] uppercase tracking-wider text-slate-700 dark:text-slate-300">
                                                    {{ $group }}
                                                </span>
                                                @if(isset($groupDescriptions[$group]))
                                                    <span class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium normal-case tracking-normal">{{ $groupDescriptions[$group] }}</span>
                                                @endif
                                            </div>
                                            <div class="flex gap-2">
                                                <button type="button" onclick="document.querySelectorAll('.chk-{{ $groupSlug }}').forEach(cb => cb.checked = true)" class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-500/10 dark:hover:bg-emerald-500/20 px-3 py-1.5 rounded-lg transition-colors border border-emerald-100 dark:border-emerald-500/20 flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                                    Pilih
                                                </button>
                                                <button type="button" onclick="document.querySelectorAll('.chk-{{ $groupSlug }}').forEach(cb => cb.checked = false)" class="text-[10px] font-bold text-rose-600 dark:text-rose-400 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 dark:bg-rose-500/10 dark:hover:bg-rose-500/20 px-3 py-1.5 rounded-lg transition-colors border border-rose-100 dark:border-rose-500/20 flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                                    Kosongkan
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @foreach($items as $item)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors group">
                                        <td class="p-4 font-semibold text-slate-700 dark:text-slate-300">{{ $item['label'] }}</td>
                                        
                                        <td class="p-4 text-center">
                                            @if(in_array('manage', $item['actions']))
                                                <input type="checkbox" name="permissions[]" value="manage_{{ $item['key'] }}" class="chk-{{ $groupSlug }} w-5 h-5 text-indigo-600 rounded border-slate-300 dark:border-zinc-600 dark:bg-zinc-900 focus:ring-indigo-500" {{ in_array('manage_'.$item['key'], $oldPerms) ? 'checked' : '' }}>
                                            @else
                                                <span class="text-slate-300 dark:text-zinc-600">-</span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-center">
                                            @if(in_array('create', $item['actions']))
                                                <input type="checkbox" name="permissions[]" value="create_{{ $item['key'] }}" class="chk-{{ $groupSlug }} w-5 h-5 text-emerald-500 rounded border-slate-300 dark:border-zinc-600 dark:bg-zinc-900 focus:ring-emerald-500" {{ in_array('create_'.$item['key'], $oldPerms) ? 'checked' : '' }}>
                                            @else
                                                <span class="text-slate-300 dark:text-zinc-600">-</span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-center">
                                            @if(in_array('update', $item['actions']))
                                                <input type="checkbox" name="permissions[]" value="update_{{ $item['key'] }}" class="chk-{{ $groupSlug }} w-5 h-5 text-amber-500 rounded border-slate-300 dark:border-zinc-600 dark:bg-zinc-900 focus:ring-amber-500" {{ in_array('update_'.$item['key'], $oldPerms) ? 'checked' : '' }}>
                                            @else
                                                <span class="text-slate-300 dark:text-zinc-600">-</span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-center">
                                            @if(in_array('delete', $item['actions']))
                                                <input type="checkbox" name="permissions[]" value="delete_{{ $item['key'] }}" class="chk-{{ $groupSlug }} w-5 h-5 text-rose-500 rounded border-slate-300 dark:border-zinc-600 dark:bg-zinc-900 focus:ring-rose-500" {{ in_array('delete_'.$item['key'], $oldPerms) ? 'checked' : '' }}>
                                            @else
                                                <span class="text-slate-300 dark:text-zinc-600">-</span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-center border-l border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50">
                                            <input type="checkbox" onchange="const row = this.closest('tr'); row.querySelectorAll('.chk-{{ $groupSlug }}').forEach(cb => cb.checked = this.checked)" class="w-5 h-5 text-slate-800 rounded border-slate-300 dark:border-zinc-600 dark:bg-zinc-900 focus:ring-slate-800 shadow-sm" title="Pilih/Kosongkan Baris Ini">
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-zinc-800">
                <a href="{{ route('pengguna.index') }}" class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-white bg-rose-500 hover:bg-rose-600 rounded-2xl transition-all shadow-xl shadow-rose-500/20 active:scale-95">
                    Batal
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95">
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
        const kantorContainer = document.getElementById('kantor-container');

        levelSelect.addEventListener('change', function() {
            if (this.value === 'Admin' || this.value === 'Staff') {
                permContainer.classList.remove('hidden');
                kantorContainer.classList.remove('hidden');
            } else {
                permContainer.classList.add('hidden');
                kantorContainer.classList.add('hidden');
            }
        });
    });
</script>
@endpush