<!-- Modal Create -->
<x-modal-form id="modal-create" title="Tambah Paket Bimbingan Belajar" action="{{ route('paket-bimbingan.store') }}">
    <div>
        <label for="nama_paket" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Paket</label>
        <input type="text" name="nama_paket" id="nama_paket" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800" placeholder="Misal: Paket Intensif SMP">
    </div>
    <div>
        <label for="nominal" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nominal (Rp)</label>
        <input type="text" name="nominal" id="nominal" required class="nominal-input focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800" placeholder="Contoh: 500.000">
    </div>
</x-modal-form>