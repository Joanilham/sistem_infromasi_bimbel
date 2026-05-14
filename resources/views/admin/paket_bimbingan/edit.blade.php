<!-- Modal Edit -->
<x-modal-form id="modal-edit" title="Edit Paket Bimbingan" action="" enctype="multipart/form-data">
    <x-slot name="method">
        @method('PUT')
    </x-slot>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Nama Paket -->
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Paket</label>
            <input type="text" name="nama_paket" id="edit_nama_paket" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800">
        </div>

        <!-- Nominal -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Harga Promo (Rp)</label>
            <input type="text" name="nominal" id="edit_nominal" required class="nominal-input focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800">
        </div>

        <!-- Harga Coret -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Harga Asli / Coret (Rp)</label>
            <input type="text" name="harga_coret" id="edit_harga_coret" class="nominal-input focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800">
        </div>

        <!-- Durasi -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Durasi Paket</label>
            <div class="flex gap-2">
                <input type="number" name="durasi_jumlah" id="edit_durasi_jumlah" class="w-20 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800">
                <select name="durasi_satuan" id="edit_durasi_satuan" class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800">
                    <option value="Bulan">Bulan</option>
                    <option value="Tahun">Tahun</option>
                </select>
            </div>
        </div>

        <!-- Label Populer -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Label (Opsional)</label>
            <input type="text" name="label_populer" id="edit_label_populer" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800">
        </div>

        <!-- Deskripsi Singkat -->
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Deskripsi Singkat</label>
            <textarea name="deskripsi" id="edit_deskripsi" rows="2" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800"></textarea>
        </div>

        <!-- Fasilitas / Benefit -->
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Fasilitas / Benefit (Satu per baris)</label>
            <textarea name="benefits" id="edit_benefits" rows="4" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800 font-mono text-xs"></textarea>
        </div>

        <!-- Gambar / Ikon -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Ikon / Gambar Paket</label>
            <input type="file" name="gambar_paket" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-400 cursor-pointer transition-all">
        </div>

        <!-- Featured -->
        <div class="flex items-center pt-6">
            <input type="checkbox" name="is_featured" id="edit_is_featured" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded">
            <label for="edit_is_featured" class="ml-2 block text-sm text-slate-700 dark:text-slate-300">Tampilkan di Hero Landing Page</label>
        </div>
    </div>
</x-modal-form>