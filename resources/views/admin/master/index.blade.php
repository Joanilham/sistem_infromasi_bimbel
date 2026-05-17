@extends('layouts.admin')

@section('title', 'Data Master')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Pengaturan Master</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm font-medium">Konfigurasi identitas lembaga, integrasi WhatsApp Gateway, dan sistem notifikasi.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
             <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 ring-4 ring-indigo-50 dark:ring-indigo-900/10">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 text-emerald-800 dark:text-emerald-400 rounded-3xl px-8 py-4 text-sm font-bold flex items-center gap-3">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('master.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Box 1: Identitas Lembaga & WhatsApp -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-blue-600"></div>
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-wider">Identitas Lembaga</h3>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Resmi Lembaga</label>
                            <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga', $master->nama_lembaga) }}" required
                                class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Operasional</label>
                            <textarea name="alamat_lembaga" rows="3" required
                                class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all resize-none">{{ old('alamat_lembaga', $master->alamat_lembaga) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-12 pt-10 border-t border-slate-100 dark:border-zinc-800">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                </div>
                                <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-wider">WhatsApp Gateway</h3>
                            </div>
                            <span class="px-4 py-1.5 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 text-[10px] font-black rounded-xl uppercase tracking-widest ring-1 ring-rose-100 dark:ring-rose-800/30">Keamanan Tinggi</span>
                        </div>
                        
                        <div class="p-6 bg-amber-50/50 dark:bg-amber-900/10 rounded-3xl border border-amber-100/50 dark:border-amber-900/20 mb-8 flex items-start gap-4">
                            <div class="w-10 h-10 rounded-2xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <p class="text-[11px] text-amber-800 dark:text-amber-300 font-bold leading-relaxed">
                                Konfigurasi gateway ini sangat sensitif. Pastikan Endpoint URL dan Token telah sesuai dengan provider WhatsApp API yang digunakan. Kesalahan konfigurasi akan menghentikan seluruh notifikasi otomatis.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                            <div class="sm:col-span-2 space-y-2">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">URL Endpoint API</label>
                                <input type="text" name="wa_url" value="{{ old('wa_url', $master->wa_url) }}" placeholder="https://api.gateway.com/..."
                                    class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Instance ID</label>
                                <input type="text" name="instance_id" value="{{ old('instance_id', $master->instance_id) }}"
                                    class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">API Access Token</label>
                                <input type="password" name="wa_token" value="{{ old('wa_token', $master->wa_token) }}"
                                    class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 pt-10 border-t border-slate-100 dark:border-zinc-800">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-10 h-10 rounded-2xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-wider">Layanan Email</h3>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">SMTP/Service API Key</label>
                            <input type="password" name="api_key" value="{{ old('api_key', $master->api_key) }}"
                                class="w-full bg-slate-50/50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black text-sm px-10 py-5 rounded-[2rem] shadow-2xl shadow-indigo-500/40 transition-all active:scale-95 flex items-center gap-3 group">
                        <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center group-hover:rotate-12 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        </div>
                        Simpan Seluruh Perubahan
                    </button>
                </div>
            </div>

            <!-- Side Column -->
            <div class="space-y-8">
                <!-- Status Box -->
                <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col items-center relative overflow-hidden group">
                    <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <h3 class="text-xs font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-[0.2em] mb-8 relative">Status Gateway</h3>
                    <div class="w-48 h-48 bg-slate-50 dark:bg-zinc-950 rounded-[3rem] border-2 border-dashed border-slate-200 dark:border-zinc-800 flex items-center justify-center p-6 relative group-hover:scale-105 transition-transform duration-500">
                        @if($master->instance_id && $master->wa_token)
                            <div class="relative">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=genius_connected" class="w-full h-full object-contain grayscale group-hover:grayscale-0 transition-all duration-700">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="bg-emerald-500 text-white font-black text-[10px] px-3 py-1 rounded-lg shadow-xl shadow-emerald-500/50 uppercase">Connected</div>
                                </div>
                            </div>
                        @else
                            <div class="text-center text-slate-300 dark:text-zinc-700">
                                <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                <p class="text-[10px] font-black uppercase tracking-widest">No Connection</p>
                            </div>
                        @endif
                    </div>
                    <p class="text-[10px] text-slate-400 dark:text-zinc-500 font-bold text-center mt-8 italic px-4 leading-relaxed relative">
                        Status sinkronisasi dipantau melalui endpoint pihak ketiga. Pastikan masa aktif API masih berlaku.
                    </p>
                </div>

                <!-- Logo Box -->
                <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col items-center group overflow-hidden">
                    <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] mb-8">Logo Resmi</h3>
                    <div class="w-40 h-40 rounded-[2.5rem] bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 p-6 shadow-inner mb-8 flex items-center justify-center group-hover:rotate-3 transition-all duration-500">
                        @if($master->logo)
                            <img src="{{ asset('storage/' . $master->logo) }}" class="max-w-full max-h-full object-contain drop-shadow-xl">
                        @else
                            <svg class="w-16 h-16 text-slate-200 dark:text-zinc-800" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @endif
                    </div>
                    <div class="w-full relative">
                        <input type="file" name="logo" id="logo-upload" class="hidden">
                        <label for="logo-upload" class="w-full inline-flex items-center justify-center gap-3 bg-slate-50 dark:bg-zinc-800 hover:bg-indigo-600 hover:text-white dark:text-slate-300 font-black text-[10px] uppercase tracking-widest py-4 rounded-2xl cursor-pointer transition-all active:scale-95">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Ganti Logo
                        </label>
                    </div>
                </div>

                <!-- Test WA Box -->
                <div class="bg-indigo-600 rounded-[2.5rem] p-8 border border-indigo-700 shadow-2xl shadow-indigo-500/30">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center text-white">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </div>
                        <h3 class="text-xs font-black text-white uppercase tracking-[0.2em]">Uji Gateway</h3>
                    </div>
                    <form action="{{ route('master.test.wa') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="text" name="phone" required placeholder="No HP (08...)" 
                            class="w-full bg-white/10 border-white/20 rounded-2xl text-xs font-bold py-4 px-4 text-white placeholder-indigo-200 focus:ring-2 focus:ring-white/30 focus:border-white/50 transition-all">
                        <textarea name="message" required rows="2" placeholder="Isi pesan uji..."
                            class="w-full bg-white/10 border-white/20 rounded-2xl text-xs font-bold py-4 px-4 text-white placeholder-indigo-200 focus:ring-2 focus:ring-white/30 focus:border-white/50 transition-all resize-none">Halo, ini pesan uji coba dari SI Bimbel. Gateway aktif!</textarea>
                        <button type="submit" class="w-full bg-white text-indigo-600 font-black text-[10px] uppercase tracking-[0.2em] py-4 rounded-2xl shadow-xl hover:bg-indigo-50 transition-all active:scale-95">
                            Kirim Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection