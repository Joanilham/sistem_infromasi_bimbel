<!-- Modal Edit -->
<x-modal-form id="modal-edit" title="Edit Kelompok Belajar" action="">
    <x-slot name="method">
        @method('PUT')
    </x-slot>
    <div class="pd-grid" style="margin-top: 10px;">
        <div class="pd-field full">
            <label for="edit_nama_kelompok">Nama Kelompok <span>*</span></label>
            <input type="text" name="nama_kelompok" id="edit_nama_kelompok" required placeholder="Misal: Kelas 1">
        </div>
    </div>
</x-modal-form>