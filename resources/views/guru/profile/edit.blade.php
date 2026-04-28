@extends('layouts.guru')

@section('title', 'Edit Profil')

@section('content')
<div class="mb-6">
    <h3 class="text-2xl font-bold leading-7 text-slate-900 dark:text-white sm:truncate sm:text-3xl sm:tracking-tight">
        Pengaturan Akun
    </h3>
    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Kelola informasi profil dan kata sandi Anda.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Informasi Profil -->
    <div class="md:col-span-1">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-medium leading-6 text-slate-900 dark:text-white">Informasi Profil</h3>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Perbarui informasi nama dan alamat surel akun Anda.</p>
        </div>
    </div>

    <div class="md:col-span-2">
        <div class="bg-white dark:bg-slate-900 shadow-md shadow-slate-200/50 dark:shadow-none sm:rounded-xl border border-slate-100 dark:border-slate-800 mb-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

            <form action="{{ route('guru.profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="px-4 py-5 sm:p-6 space-y-4">
                    <!-- Nama -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="mt-2 block w-full border border-slate-300 dark:border-slate-700 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2.5 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white transition-colors">
                        @error('name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Alamat Surel (Email)</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="mt-2 block w-full border border-slate-300 dark:border-slate-700 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2.5 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white transition-colors">
                        @error('email') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Role (read-only, tidak bisa diubah) -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Role Akun</label>
                        <div class="mt-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-500 dark:text-slate-400 select-none">
                            Guru
                        </div>
                        <p class="mt-1 text-xs text-slate-400">Role tidak dapat diubah sendiri.</p>
                    </div>
                </div>

                <div class="bg-slate-50/80 dark:bg-slate-800/50 px-4 py-4 sm:px-6 flex justify-end flex-row-reverse sm:rounded-b-xl border-t border-slate-200 dark:border-slate-800">
                    <button type="submit" class="inline-flex items-center justify-center py-2.5 px-5 border border-transparent shadow-sm shadow-indigo-500/30 text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="hidden sm:block" aria-hidden="true">
    <div class="py-5">
        <div class="border-t border-slate-200 dark:border-slate-800"></div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
    <!-- Ubah Password -->
    <div class="md:col-span-1">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-medium leading-6 text-slate-900 dark:text-white">Perbarui Kata Sandi</h3>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.</p>
        </div>
    </div>

    <div class="md:col-span-2">
        <div class="bg-white dark:bg-slate-900 shadow-md shadow-slate-200/50 dark:shadow-none sm:rounded-xl border border-slate-100 dark:border-slate-800 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-slate-600 to-slate-800"></div>

            <form action="{{ route('guru.profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="px-4 py-5 sm:p-6 space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" class="mt-2 block w-full border border-slate-300 dark:border-slate-700 rounded-lg shadow-sm focus:ring-slate-500 focus:border-slate-500 sm:text-sm px-4 py-2.5 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white transition-colors">
                        @error('current_password') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Kata Sandi Baru</label>
                        <input type="password" name="password" id="password" class="mt-2 block w-full border border-slate-300 dark:border-slate-700 rounded-lg shadow-sm focus:ring-slate-500 focus:border-slate-500 sm:text-sm px-4 py-2.5 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white transition-colors">
                        @error('password') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-2 block w-full border border-slate-300 dark:border-slate-700 rounded-lg shadow-sm focus:ring-slate-500 focus:border-slate-500 sm:text-sm px-4 py-2.5 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white transition-colors">
                    </div>
                </div>

                <div class="bg-slate-50/80 dark:bg-slate-800/50 px-4 py-4 sm:px-6 flex justify-end flex-row-reverse sm:rounded-b-xl border-t border-slate-200 dark:border-slate-800">
                    <button type="submit" class="inline-flex items-center justify-center py-2.5 px-5 border border-transparent shadow-sm shadow-slate-500/30 text-sm font-semibold rounded-xl text-white bg-slate-800 hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-all hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Perbarui Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
