@extends('layouts.admin')

@section('title', 'Edit Paket Bimbingan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('paket-bimbingan.index') }}" class="w-10 h-10 rounded-2xl bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Edit Paket Bimbingan</h1>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">Perbarui informasi paket bimbingan <strong class="text-slate-700 dark:text-slate-300">{{ $paketBimbingan->nama_paket }}</strong>.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-900/50 rounded-[2rem] p-6 mb-6">
            <div class="flex items-center gap-3 mb-3 text-rose-600 dark:text-rose-400 font-black">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Ada kesalahan pada input:
            </div>
            <ul class="list-disc list-inside text-rose-500 dark:text-rose-400 text-sm font-bold ml-2 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('paket-bimbingan.update', $paketBimbingan->id) }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden flex flex-col">
        @csrf
        @method('PUT')

        <div class="p-8 space-y-10 flex-1">
            
            {{-- Section 1: Informasi Dasar --}}
            <div class="space-y-6">
                <h3 class="text-xs font-black uppercase tracking-widest text-indigo-500 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center">1</span> Informasi Dasar
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Paket <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_paket" value="{{ old('nama_paket', $paketBimbingan->nama_paket) }}" required placeholder="Misal: Paket Intensif UTBK 2024" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Harga Promo (Rp) <span class="text-rose-500">*</span></label>
                        <input type="text" name="nominal" value="{{ old('nominal', $paketBimbingan->nominal) }}" required placeholder="500.000" class="nominal-input w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Harga Coret (Rp)</label>
                        <input type="text" name="harga_coret" value="{{ old('harga_coret', $paketBimbingan->harga_coret) }}" placeholder="750.000" class="nominal-input w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Minimal DP (%) <span class="text-rose-500">*</span></label>
                        <input type="number" name="dp_persen_minimal" value="{{ old('dp_persen_minimal', $paketBimbingan->dp_persen_minimal ?? 10) }}" min="1" max="100" required placeholder="10" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        <p class="text-[10px] font-bold text-slate-400 mt-1 ml-1">Persentase DP wajib saat mendaftar.</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Bisa Dicicil?</label>
                        <select name="bisa_dicicil" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                            <option value="1" {{ old('bisa_dicicil', $paketBimbingan->bisa_dicicil) ? 'selected' : '' }}>Ya, Bisa Dicicil</option>
                            <option value="0" {{ !old('bisa_dicicil', $paketBimbingan->bisa_dicicil) ? 'selected' : '' }}>Tidak (Hanya Lunas)</option>
                        </select>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Maksimal Cicilan (Tenor) <span class="text-rose-500">*</span></label>
                        <input type="number" name="max_cicilan" value="{{ old('max_cicilan', $paketBimbingan->max_cicilan ?? 1) }}" min="1" required placeholder="Misal: 6" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Durasi Paket</label>
                        <div class="flex gap-4">
                            <input type="number" name="durasi_jumlah" value="{{ old('durasi_jumlah', $paketBimbingan->durasi_jumlah) }}" placeholder="Misal: 6" class="w-2/3 bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                            <select name="durasi_satuan" class="w-1/3 bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                                <option value="Bulan" {{ old('durasi_satuan', $paketBimbingan->durasi_satuan) == 'Bulan' ? 'selected' : '' }}>Bulan</option>
                                <option value="Tahun" {{ old('durasi_satuan', $paketBimbingan->durasi_satuan) == 'Tahun' ? 'selected' : '' }}>Tahun</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Label Badge</label>
                        <input type="text" name="label_populer" value="{{ old('label_populer', $paketBimbingan->label_populer) }}" placeholder="Misal: Best Seller / Promo" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                    </div>
                </div>
            </div>
            
            <hr class="border-slate-100 dark:border-zinc-800">
            
            {{-- Section 2: Detail & Keunggulan --}}
            <div class="space-y-6">
                <h3 class="text-xs font-black uppercase tracking-widest text-emerald-500 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center">2</span> Detail & Keunggulan
                </h3>
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Deskripsi Singkat</label>
                        <textarea name="deskripsi" rows="3" placeholder="Jelaskan secara singkat tentang paket ini..." class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">{{ old('deskripsi', $paketBimbingan->deskripsi) }}</textarea>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Benefit / Fasilitas (Satu per baris)</label>
                        <textarea name="benefits" rows="4" placeholder="Contoh:&#10;Modul Lengkap PDF&#10;Tryout Berkala&#10;Grup Konsultasi WA" class="font-mono w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">{{ old('benefits', $paketBimbingan->benefits) }}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Target Peserta</label>
                            <input type="text" name="target_peserta" value="{{ old('target_peserta', $paketBimbingan->target_peserta) }}" placeholder="Misal: Siswa SMA / Mahasiswa" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Fasilitas Tambahan</label>
                            <input type="text" name="fasilitas" value="{{ old('fasilitas', $paketBimbingan->fasilitas) }}" placeholder="Misal: AC, WiFi, Snack" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                    </div>
                </div>
            </div>
            
            <hr class="border-slate-100 dark:border-zinc-800">
            
            {{-- Section 3: Media & Pengaturan --}}
            <div class="space-y-6">
                <h3 class="text-xs font-black uppercase tracking-widest text-amber-500 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center">3</span> Media & Pengaturan
                </h3>
                
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Gambar Paket</label>
                        @if($paketBimbingan->gambar_paket)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $paketBimbingan->gambar_paket) }}" class="w-24 h-24 object-cover rounded-xl border border-slate-200 dark:border-zinc-800">
                            </div>
                        @endif
                        <input type="file" name="gambar_paket" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all dark:file:bg-indigo-900/30 dark:file:text-indigo-400">
                    </div>
                    
                    <div class="pt-4 pb-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative flex items-center justify-center">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $paketBimbingan->is_featured) ? 'checked' : '' }} class="peer sr-only">
                                <div class="w-12 h-6 bg-slate-200 dark:bg-zinc-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 transition-colors"></div>
                            </div>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Tampilkan sebagai Unggulan di Landing Page</span>
                        </label>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="px-8 py-6 bg-slate-50 dark:bg-zinc-950 border-t border-slate-200 dark:border-zinc-800 flex items-center justify-end gap-4">
            <a href="{{ route('paket-bimbingan.index') }}" class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-white bg-rose-500 hover:bg-rose-600 rounded-2xl transition-all shadow-xl shadow-rose-500/20 active:scale-95">Batal</a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95">Simpan Data</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function formatRupiah(angka, prefix) {
        if (!angka) return '';
        var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    document.querySelectorAll('.nominal-input').forEach(input => {
        if (input.value) {
            input.value = formatRupiah(input.value);
        }

        input.addEventListener('keyup', function(e) {
            this.value = formatRupiah(this.value);
        });
    });
</script>
@endpush