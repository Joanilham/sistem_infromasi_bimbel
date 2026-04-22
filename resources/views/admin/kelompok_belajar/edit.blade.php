<!-- Modal Edit -->
<x-modal-form id="modal-edit" title="Edit Kelompok Belajar" action="">
    <x-slot name="method">
        @method('PUT')
    </x-slot>
    <div>
        <label for="edit_nama_kelompok" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Kelompok</label>
        <input type="text" name="nama_kelompok" id="edit_nama_kelompok" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800">
    </div>
</x-modal-form>