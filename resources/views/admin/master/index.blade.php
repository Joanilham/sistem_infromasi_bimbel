@extends('layouts.admin')

@section('title', 'Data Master')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Pengaturan Master</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">Konfigurasi identitas lembaga, integrasi WhatsApp Gateway, dan sistem notifikasi.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
             <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 ring-1 ring-indigo-100 dark:ring-indigo-900/30">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
    </div>



    <form action="{{ route('master.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Box 1: Identitas Lembaga & WhatsApp -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500 rounded-t-xl"></div>
                    <div class="flex items-center gap-3 mb-6 mt-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Identitas Lembaga</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Resmi Lembaga</label>
                            <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga', $master->nama_lembaga) }}" required
                                class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Alamat Operasional</label>
                            <textarea name="alamat_lembaga" rows="3" required
                                class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none">{{ old('alamat_lembaga', $master->alamat_lembaga) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-200 dark:border-zinc-800">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">WhatsApp Gateway</h3>
                            </div>
                            <span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 text-xs font-medium rounded-md border border-amber-200 dark:border-amber-800">Sensitif</span>
                        </div>
                        
                        <div class="p-4 bg-amber-50 dark:bg-amber-900/10 rounded-lg border border-amber-200 dark:border-amber-900/20 mb-6 flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <p class="text-sm text-amber-800 dark:text-amber-200 leading-relaxed">
                                Pastikan Endpoint URL dan Token telah sesuai dengan provider WhatsApp API yang digunakan. Kesalahan konfigurasi akan menghentikan seluruh notifikasi otomatis.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2 space-y-1.5">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">URL Endpoint API</label>
                                <input type="text" name="wa_url" value="{{ old('wa_url', $master->wa_url) }}" placeholder="https://api.gateway.com/..."
                                    class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Instance ID</label>
                                <input type="text" name="instance_id" value="{{ old('instance_id', $master->instance_id) }}"
                                    class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">API Access Token</label>
                                <input type="password" name="wa_token" value="{{ old('wa_token', $master->wa_token) }}"
                                    class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-200 dark:border-zinc-800">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Layanan Email</h3>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">SMTP/Service API Key</label>
                            <input type="password" name="api_key" value="{{ old('api_key', $master->api_key) }}"
                                class="w-full bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                    </div>


                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-6 py-2.5 rounded-lg shadow-sm transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>


            <!-- Side Column -->
            <div class="space-y-6">
                <!-- Status Box -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm flex flex-col items-center">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-6">Status Gateway</h3>
                    <div class="w-40 h-40 bg-slate-50 dark:bg-zinc-950 rounded-lg border border-slate-200 dark:border-zinc-800 flex items-center justify-center p-4">
                        @if($master->instance_id && $master->wa_token)
                            <div class="relative w-full h-full flex flex-col items-center justify-center gap-3">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=genius_connected" class="w-20 h-20 object-contain">
                                <span class="bg-emerald-100 text-emerald-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Terkoneksi</span>
                            </div>
                        @else
                            <div class="text-center text-slate-400 dark:text-zinc-600">
                                <svg class="w-12 h-12 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                <p class="text-xs font-medium">Tidak Terhubung</p>
                            </div>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 dark:text-zinc-400 text-center mt-4 px-2">
                        Pastikan masa aktif API gateway Anda masih berlaku.
                    </p>
                </div>

                <!-- Logo Box -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm flex flex-col items-center">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-6">Logo Instansi</h3>
                    <div class="w-40 h-40 rounded-lg bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 p-4 mb-6 flex items-center justify-center overflow-hidden">
                        @if($master->logo)
                            <img id="logo-preview" src="{{ asset('storage/' . $master->logo) }}" class="max-w-full max-h-full object-contain">
                            <svg id="logo-placeholder" class="w-12 h-12 text-slate-300 dark:text-zinc-700 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @else
                            <img id="logo-preview" class="max-w-full max-h-full object-contain hidden">
                            <svg id="logo-placeholder" class="w-12 h-12 text-slate-300 dark:text-zinc-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @endif
                    </div>
                    <div class="w-full">
                        <input type="file" name="logo" id="logo-upload" class="hidden" accept="image/*" onchange="previewLogo(event)">
                        <label for="logo-upload" class="w-full flex items-center justify-center gap-2 bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-700 text-slate-700 dark:text-slate-300 font-medium text-sm py-2 rounded-lg cursor-pointer transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Pilih Logo Baru
                        </label>
                    </div>
                </div>

                <!-- Test WA Box -->
                <div class="bg-indigo-50 dark:bg-indigo-900/10 rounded-xl p-6 border border-indigo-100 dark:border-indigo-900/30">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-800/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </div>
                        <h3 class="text-sm font-semibold text-indigo-900 dark:text-indigo-100">Uji Gateway</h3>
                    </div>
                    <div class="space-y-3">
                        <input type="text" name="phone" form="form-uji-wa" required placeholder="No HP (08...)" 
                            class="w-full bg-white dark:bg-zinc-950 border border-indigo-200 dark:border-indigo-800 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <textarea name="message" form="form-uji-wa" required rows="2" placeholder="Isi pesan uji..."
                            class="w-full bg-white dark:bg-zinc-950 border border-indigo-200 dark:border-indigo-800 rounded-lg text-sm py-2 px-3 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none">Halo, ini pesan uji coba dari SI Bimbel. Gateway aktif!</textarea>
                        <button type="submit" form="form-uji-wa" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 rounded-lg transition-colors">
                            Kirim Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    <form id="form-uji-wa" action="{{ route('master.test.wa') }}" method="POST" class="hidden">
        @csrf
    </form>
</div>

@push('scripts')
<script>
function previewLogo(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('logo-preview');
        output.src = reader.result;
        output.classList.remove('hidden');
        const placeholder = document.getElementById('logo-placeholder');
        if(placeholder) placeholder.classList.add('hidden');
    };
    if(event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>
@endpush
@endsection