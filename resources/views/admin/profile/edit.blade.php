@extends('layouts.admin')

@section('title', 'Edit Profil')

@section('content')
<div class="mb-6">
    <h3 class="text-2xl font-bold leading-7 text-slate-900 dark:text-white sm:truncate sm:text-3xl sm:tracking-tight">
        Pengaturan Akun
    </h3>
    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Kelola informasi profil dan kata sandi Anda.</p>
</div>

{{-- Foto Profil --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="md:col-span-1">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-medium leading-6 text-slate-900 dark:text-white">Foto Profil</h3>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Upload foto profil Anda. Maksimal 2MB (JPG, PNG, WebP).</p>
        </div>
    </div>

    <div class="md:col-span-2">
        <div class="bg-white dark:bg-slate-900 shadow-md shadow-slate-200/50 dark:shadow-none sm:rounded-xl border border-slate-100 dark:border-slate-800 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-teal-500"></div>

            <div class="px-4 py-6 sm:p-6" x-data="{ preview: null }">
                <div class="flex items-center gap-6">
                    {{-- Avatar Preview --}}
                    <div class="relative group">
                        <div class="w-24 h-24 rounded-2xl overflow-hidden border-2 border-slate-200 dark:border-slate-700 shadow-lg">
                            @if($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto Profil" class="w-full h-full object-cover" x-show="!preview">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(trim($user->name)) }}&background=4318FF&color=fff&size=256&bold=true&format=svg" alt="Foto Profil" class="w-full h-full object-cover" x-show="!preview">
                            @endif
                            <img :src="preview" alt="Preview" class="w-full h-full object-cover" x-show="preview" x-cloak>
                        </div>
                    </div>

                    <div class="flex-1">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="flex flex-wrap items-center gap-3">
                                <label class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl cursor-pointer transition-all hover:-translate-y-0.5 shadow-sm shadow-indigo-500/30">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Pilih Foto
                                    <input type="file" name="photo" accept="image/*" class="hidden" @change="const file = $event.target.files[0]; if(file) { preview = URL.createObjectURL(file); $el.closest('form').submit(); }">
                                </label>
                            </div>
                        </form>

                        @if($user->photo)
                        <form action="{{ route('profile.update') }}" method="POST" class="mt-2">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="remove_photo" value="1">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Hapus Foto
                            </button>
                        </form>
                        @endif

                        @error('photo') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        <p class="mt-2 text-xs text-slate-400">JPG, PNG atau WebP. Maks 2MB.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

            <form action="{{ route('profile.update') }}" method="POST">
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
                </div>

                <div class="bg-slate-50/80 dark:bg-slate-800/50 px-6 py-6 sm:px-8 flex justify-end flex-row-reverse sm:rounded-b-xl border-t border-slate-200 dark:border-slate-800">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95 flex items-center gap-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
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

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="px-4 py-5 sm:p-6 space-y-4" x-data="{ show: false }">
                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Kata Sandi Saat Ini</label>
                        <input :type="show ? 'text' : 'password'" name="current_password" id="current_password" class="mt-2 block w-full border border-slate-300 dark:border-slate-700 rounded-lg shadow-sm focus:ring-slate-500 focus:border-slate-500 sm:text-sm px-4 py-2.5 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white transition-colors">
                        @error('current_password') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Kata Sandi Baru</label>
                        <input :type="show ? 'text' : 'password'" name="password" id="password" class="mt-2 block w-full border border-slate-300 dark:border-slate-700 rounded-lg shadow-sm focus:ring-slate-500 focus:border-slate-500 sm:text-sm px-4 py-2.5 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white transition-colors">
                        @error('password') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Konfirmasi Kata Sandi</label>
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" class="mt-2 block w-full border border-slate-300 dark:border-slate-700 rounded-lg shadow-sm focus:ring-slate-500 focus:border-slate-500 sm:text-sm px-4 py-2.5 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white transition-colors">
                    </div>

                    <div class="pt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" x-model="show" class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 focus:ring-slate-500 bg-white dark:bg-slate-800">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300 select-none">Tampilkan Semua Sandi</span>
                        </label>
                    </div>
                </div>

                <div class="bg-slate-50/80 dark:bg-slate-800/50 px-6 py-6 sm:px-8 flex justify-end flex-row-reverse sm:rounded-b-xl border-t border-slate-200 dark:border-slate-800">
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest px-8 py-4 rounded-2xl shadow-xl shadow-slate-500/20 transition-all active:scale-95 flex items-center gap-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Perbarui Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection