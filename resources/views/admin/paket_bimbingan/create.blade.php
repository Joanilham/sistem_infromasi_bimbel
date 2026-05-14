@extends('layouts.admin')

@section('title', 'Tambah Paket Bimbingan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Tambah Paket Bimbingan</h1>
            <p class="text-slate-500 dark:text-slate-400">Buat paket bimbingan baru untuk ditampilkan di landing page dan sistem pendaftaran.</p>
        </div>
        <a href="{{ route('paket-bimbingan.index') }}" class="flex items-center text-sm font-semibold text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
    </div>

    <form action="{{ route('paket-bimbingan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
            <div class="p-8 space-y-8">
                
                <!-- Section 1: Informasi Dasar -->
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mr-3 text-sm">1</span>
                        Informasi Dasar
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nama Paket <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_paket" value="{{ old('nama_paket') }}" required class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 focus:ring-indigo-500 transition-all" placeholder="Misal: Paket Intensif UTBK 2024">
                            @error('nama_paket') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Harga Promo (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium">Rp</span>
                                <input type="text" name="nominal" value="{{ old('nominal') }}" required class="nominal-input w-full pl-12 pr-4 py-3 rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 focus:ring-indigo-500 transition-all" placeholder="500.000">
                            </div>
                            @error('nominal') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Harga Asli / Coret (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium">Rp</span>
                                <input type="text" name="harga_coret" value="{{ old('harga_coret') }}" class="nominal-input w-full pl-12 pr-4 py-3 rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 focus:ring-indigo-500 transition-all" placeholder="750.000">
                            </div>
                            @error('harga_coret') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Durasi Paket</label>
                            <div class="flex gap-2">
                                <input type="number" name="durasi_jumlah" value="{{ old('durasi_jumlah') }}" class="w-24 px-4 py-3 rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 focus:ring-indigo-500 transition-all" placeholder="6">
                                <select name="durasi_satuan" class="flex-1 px-4 py-3 rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 focus:ring-indigo-500 transition-all">
                                    <option value="Bulan" {{ old('durasi_satuan') == 'Bulan' ? 'selected' : '' }}>Bulan</option>
                                    <option value="Tahun" {{ old('durasi_satuan') == 'Tahun' ? 'selected' : '' }}>Tahun</option>
                                </select>
                            </div>
                            @error('durasi_jumlah') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Label Badge</label>
                            <input type="text" name="label_populer" value="{{ old('label_populer') }}" class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 focus:ring-indigo-500 transition-all" placeholder="Misal: Best Seller / Promo">
                            @error('label_populer') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Deskripsi & Benefit -->
                <div class="pt-8 border-t border-slate-100 dark:border-slate-800">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mr-3 text-sm">2</span>
                        Detail & Keunggulan
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Deskripsi Singkat</label>
                            <textarea name="deskripsi" rows="3" class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 focus:ring-indigo-500 transition-all" placeholder="Jelaskan secara singkat tentang paket ini...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Benefit / Fasilitas (Satu per baris)</label>
                            <textarea name="benefits" rows="4" class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 focus:ring-indigo-500 transition-all font-mono text-sm" placeholder="Contoh:&#10;Modul Lengkap PDF&#10;Tryout Berkala&#10;Grup Konsultasi WA">{{ old('benefits') }}</textarea>
                            @error('benefits') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Target Peserta</label>
                            <input type="text" name="target_peserta" value="{{ old('target_peserta') }}" class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 focus:ring-indigo-500 transition-all" placeholder="Misal: Siswa SMA / Mahasiswa">
                            @error('target_peserta') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Fasilitas Tambahan</label>
                            <input type="text" name="fasilitas" value="{{ old('fasilitas') }}" class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 focus:ring-indigo-500 transition-all" placeholder="Misal: AC, WiFi, Co-working Space">
                            @error('fasilitas') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Media & Pengaturan -->
                <div class="pt-8 border-t border-slate-100 dark:border-slate-800">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 flex items-center justify-center mr-3 text-sm">3</span>
                        Media & Pengaturan
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Gambar Paket</label>
                            <input type="file" name="gambar_paket" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-400 cursor-pointer transition-all">
                            @error('gambar_paket') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl flex items-center">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded-lg border-slate-300 focus:ring-indigo-500">
                            <label for="is_featured" class="ml-3 block text-sm font-semibold text-slate-700 dark:text-slate-300">Tampilkan sebagai Unggulan di Landing Page</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Action -->
            <div class="bg-slate-50 dark:bg-slate-800/50 px-8 py-6 flex items-center justify-end gap-4 border-t border-slate-100 dark:border-slate-800">
                <button type="reset" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-all">
                    Reset Form
                </button>
                <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-200 dark:shadow-none transition-all flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Simpan Paket Baru
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function formatRupiah(angka, prefix) {
        if (!angka) return '';
        var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    document.querySelectorAll('.nominal-input').forEach(input => {
        // Format on page load if has value
        if (input.value) {
            input.value = formatRupiah(input.value);
        }

        input.addEventListener('keyup', function(e) {
            this.value = formatRupiah(this.value);
        });
    });
</script>
@endsection