<!-- Modal Edit -->
<x-modal-form id="modal-edit" title="Edit Paket Bimbingan Belajar" action="">
    <x-slot name="method">
        @method('PUT')
    </x-slot>
    <div>
        <label for="edit_nama_paket" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Paket</label>
        <input type="text" name="nama_paket" id="edit_nama_paket" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800">
    </div>
    <div>
        <label for="edit_nominal" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nominal (Rp)</label>
        <input type="text" name="nominal" id="edit_nominal" required class="nominal-input focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800">
    </div>
</x-modal-form>