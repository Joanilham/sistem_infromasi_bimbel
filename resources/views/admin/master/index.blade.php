@extends('layouts.admin')

@section('title', 'Data Master')

@section('content')
<div class="mb-6">
    <h3 class="text-2xl font-bold leading-7 text-slate-900 dark:text-white sm:truncate sm:text-3x1 sm:tracking-tight">
        Pengaturan Data Master
    </h3>
    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Kelola informasi lembaga, koneksi WhatsApp Gateway, dan logo resmi.</p>
</div>



<form action="{{ route('master.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Box 1: Data Lembaga -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 shadow-md shadow-slate-200/50 dark:shadow-none sm:rounded-xl border border-slate-100 dark:border-slate-800 relative overflow-hidden h-full">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

                <div class="px-4 py-5 sm:px-6 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                    <h3 class="text-lg leading-6 font-semibold text-slate-900 dark:text-white">Identitas Lembaga & WhatsApp</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Informasi utama lembaga Bimbingan Belajar dan kredensial Notifikasi WhatsApp.</p>
                </div>

                <div class="px-4 py-5 sm:p-6 space-y-5">
                    <!-- Nama Lembaga -->
                    <div>
                        <label for="nama_lembaga" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Lembaga</label>
                        <input type="text" name="nama_lembaga" id="nama_lembaga" value="{{ old('nama_lembaga', $master->nama_lembaga) }}" class="mt-2 block w-full border border-slate-300 dark:border-slate-700 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2.5 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white transition-colors">
                    </div>

                    <!-- Alamat Lembaga -->
                    <div>
                        <label for="alamat_lembaga" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Alamat Lengkap</label>
                        <textarea name="alamat_lembaga" id="alamat_lembaga" rows="3" class="mt-2 block w-full border border-slate-300 dark:border-slate-700 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2.5 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white transition-colors">{{ old('alamat_lembaga', $master->alamat_lembaga) }}</textarea>
                    </div>

                    <div class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-800">
                        <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Pengaturan WhatsApp Gateway</h4>

                        <!-- WA URL -->
                        <div class="mb-4">
                            <label for="wa_url" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">URL Gateway / Endpoint (Opsional, Default Fonnte)</label>
                            <input type="text" name="wa_url" id="wa_url" value="{{ old('wa_url', $master->wa_url) }}" class="mt-2 block w-full border border-slate-300 dark:border-slate-700 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm px-4 py-2.5 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white transition-colors" placeholder="Contoh: https://api.fonnte.com/send">
                            <p class="mt-1 text-xs text-slate-500">Bisa dikosongkan jika menggunakan standar (Fonnte API).</p>
                        </div>

                        <!-- Instance ID -->
                        <div class="mb-4">
                            <label for="instance_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Instance ID</label>
                            <input type="text" name="instance_id" id="instance_id" value="{{ old('instance_id', $master->instance_id) }}" class="mt-2 block w-full border border-slate-300 dark:border-slate-700 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm px-4 py-2.5 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white transition-colors" placeholder="Contoh: INSTANCE_XXXX">
                        </div>

                        <!-- WA Token -->
                        <div>
                            <label for="wa_token" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Token Akses / API Key</label>
                            <div class="relative mt-2">
                                <input type="password" name="wa_token" id="wa_token" value="{{ old('wa_token', $master->wa_token) }}" class="block w-full border border-slate-300 dark:border-slate-700 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm px-4 py-2.5 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white transition-colors pr-10">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-800">
                        <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Pengaturan Email Gateway</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Digunakan untuk mengirimkan tautan reset kata sandi ke email pengguna.</p>

                        <!--API Key Email -->
                        <div>
                            <label for="api_key" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">API Key</label>
                            <div class="relative mt-2">
                                <input type="password" name="api_key" id="api_key" value="{{ old('api_key', $master->api_key) }}" class="block w-full border border-slate-300 dark:border-slate-700 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2.5 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white transition-colors">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <!-- Box 2: Koneksi WhatsApp QR -->
            <div class="bg-white dark:bg-slate-900 shadow-md shadow-slate-200/50 dark:shadow-none sm:rounded-xl border border-slate-100 dark:border-slate-800 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
                <div class="px-4 py-5 sm:px-6 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 text-center">
                    <h3 class="text-lg leading-6 font-semibold text-emerald-700 dark:text-emerald-400">Koneksi WhatsApp</h3>
                </div>
                <div class="p-6 flex flex-col items-center justify-center">
                    <div class="w-48 h-48 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center border-2 border-dashed border-slate-300 dark:border-slate-700 relative overflow-hidden group">
                        @if($master->instance_id && $master->wa_token)
                        <!-- Placeholder QR Code Simulation for UI Concept -->
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=example_connection" alt="QR Code" class="w-3/4 h-3/4 object-contain opacity-80" />
                        <div class="absolute inset-0 bg-slate-900/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-white text-xs font-semibold px-2 text-center">Fitur Render Dinamis Menunggu Modul WA</span>
                        </div>
                        @else
                        <div class="text-center">
                            <svg class="mx-auto h-10 w-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Menunggu Kredensial</p>
                        </div>
                        @endif
                    </div>
                    <p class="mt-4 text-xs text-center text-slate-500 dark:text-slate-400">Integrasi WhatsApp Aktif. Untuk QR biasanya di-generate oleh aplikasi Gateway terpisah.</p>
                </div>
            </div>


            <!-- Box 3: Logo Master -->
            <div class="bg-white dark:bg-slate-900 shadow-md shadow-slate-200/50 dark:shadow-none sm:rounded-xl border border-slate-100 dark:border-slate-800 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-slate-600 to-slate-800"></div>
                <div class="px-4 py-5 sm:px-6 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 text-center">
                    <h3 class="text-lg leading-6 font-semibold text-slate-900 dark:text-white">Logo Lembaga</h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-col items-center">
                        <div class="h-32 w-32 mb-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 overflow-hidden flex items-center justify-center p-2 shadow-inner">
                            @if($master->logo)
                            <img src="{{ asset('storage/' . $master->logo) }}" alt="Logo Lembaga" class="h-full w-full object-contain">
                            @else
                            <svg class="h-12 w-12 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            @endif
                        </div>
                        <input type="file" name="logo" id="logo" class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-400 dark:hover:file:bg-indigo-900/50 transition-colors" accept="image/*">
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400 text-center">Format PNG, JPG, JPEG (Max. 2MB). Disarankan persegi untuk tata rias terbaik.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="mt-6 flex justify-end">
        <button type="submit" class="inline-flex items-center justify-center py-2.5 px-6 border border-transparent shadow-md shadow-indigo-500/30 text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
            </svg>
            Simpan Perubahan Master
        </button>
    </div>
</form>

<div class="mt-8 border-t border-slate-200 dark:border-slate-700 pt-8">
    <div class="max-w-3xl mx-auto">
        <!-- Box Test Pengiriman -->
        <div class="bg-white dark:bg-slate-900 shadow-md shadow-slate-200/50 dark:shadow-none sm:rounded-xl border border-slate-100 dark:border-slate-800 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-indigo-600"></div>
            <div class="px-4 py-5 sm:px-6 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-semibold text-slate-900 dark:text-white">Uji Coba Pengiriman WhatsApp</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pastikan Pengaturan Gateway sudah disimpan sebelum melakukan uji coba.</p>
                </div>
                <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </div>
            <div class="p-6 bg-slate-50/30 dark:bg-slate-900/30">
                <form action="{{ route('master.test.wa') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Nomor Handphone (Awalan 08 / 62)</label>
                            <input type="text" name="phone" required class="mt-2 block w-full border border-slate-300 dark:border-slate-700 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2.5 bg-white dark:bg-slate-800 text-slate-900 dark:text-white transition-colors" placeholder="08123456789">
                            
                            <div class="mt-4">
                                <button type="submit" class="w-full inline-flex items-center justify-center py-2.5 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                    Kirim Pesan Uji Coba
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Pesan Uji Coba</label>
                            <textarea name="message" required rows="4" class="mt-2 block w-full border border-slate-300 dark:border-slate-700 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2.5 bg-white dark:bg-slate-800 text-slate-900 dark:text-white transition-colors">Halo, ini pesan percobaan dari SI Bimbel. 
Integrasi WhatsApp Gateway berhasil!</textarea>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection