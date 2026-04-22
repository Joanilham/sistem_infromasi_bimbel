<!-- Modal Create -->
<x-modal-form id="modal-create" title="Tambah Kelompok Belajar" action="{{ route('kelompok-belajar.store') }}">
    <div>
        <label for="nama_kelompok" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Kelompok</label>
        <input type="text" name="nama_kelompok" id="nama_kelompok" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800" placeholder="Misal: kelas 1">
    </div>
</x-modal-form>