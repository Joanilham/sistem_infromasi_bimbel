<!-- Modal Create -->
<x-modal-form id="modal-create" title="Tambah Paket Bimbingan" action="{{ route('paket-bimbingan.store') }}" enctype="multipart/form-data">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Nama Paket -->
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Paket</label>
            <input type="text" name="nama_paket" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800" placeholder="Misal: Paket Intensif SMP">
        </div>

        <!-- Nominal -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Harga Promo (Rp)</label>
            <input type="text" name="nominal" required class="nominal-input focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800" placeholder="500.000">
        </div>

        <!-- Harga Coret -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Harga Asli / Coret (Rp)</label>
            <input type="text" name="harga_coret" class="nominal-input focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800" placeholder="750.000">
        </div>

        <!-- Durasi -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Durasi Paket</label>
            <div class="flex gap-2">
                <input type="number" name="durasi_jumlah" class="w-20 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800" placeholder="6">
                <select name="durasi_satuan" class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800">
                    <option value="Bulan">Bulan</option>
                    <option value="Tahun">Tahun</option>
                </select>
            </div>
        </div>

        <!-- Label Populer -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Label (Opsional)</label>
            <input type="text" name="label_populer" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800" placeholder="Misal: Terlaris / Promo">
        </div>

        <!-- Deskripsi Singkat -->
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Deskripsi Singkat</label>
            <textarea name="deskripsi" rows="2" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800" placeholder="Jelaskan secara singkat target paket ini..."></textarea>
        </div>

        <!-- Fasilitas / Benefit -->
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Fasilitas / Benefit (Satu per baris)</label>
            <textarea name="benefits" rows="4" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800 font-mono text-xs" placeholder="Contoh:&#10;Modul Lengkap&#10;Tryout Rutin&#10;Ruang Kelas AC"></textarea>
        </div>

        <!-- Gambar / Ikon -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Ikon / Gambar Paket</label>
            <input type="file" name="gambar_paket" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-400 cursor-pointer transition-all">
        </div>

        <!-- Featured -->
        <div class="flex items-center pt-6">
            <input type="checkbox" name="is_featured" id="is_featured_create" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded">
            <label for="is_featured_create" class="ml-2 block text-sm text-slate-700 dark:text-slate-300">Tampilkan di Hero Landing Page</label>
        </div>
    </div>
</x-modal-form>