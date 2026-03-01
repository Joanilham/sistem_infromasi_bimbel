<!-- Modal Edit -->
<x-modal-form id="modal-edit" title="Edit Periode" action="">
    <x-slot name="method">
        @method('PUT')
    </x-slot>
    <div>
        <label for="edit_tahun_periode" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Tahun Periode</label>
        <input type="text" name="tahun_periode" id="edit_tahun_periode" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800">
    </div>
    <div class="flex items-start mt-4 bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl border border-slate-100 dark:border-slate-700">
        <div class="flex items-center h-5">
            <input id="edit_is_active" name="is_active" type="checkbox" value="1" class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-600 rounded cursor-pointer dark:bg-slate-800">
        </div>
        <div class="ml-3 text-sm">
            <label for="edit_is_active" class="font-medium text-slate-800 dark:text-slate-200 cursor-pointer">Periode Aktif</label>
            <p class="text-slate-500 dark:text-slate-400">Jadikan sebagai periode yang sedang berjalan saat ini.</p>
        </div>
    </div>
</x-modal-form>