<!-- Modal Create -->
<x-modal-form id="modal-create" title="Tambah Kelompok Belajar" action="{{ route('kelompok-belajar.store') }}">
    <div class="pd-grid" style="margin-top: 10px;">
        <div class="pd-field full">
            <label for="nama_kelompok">Nama Kelompok <span>*</span></label>
            <input type="text" name="nama_kelompok" id="nama_kelompok" required placeholder="Misal: Kelas 1">
        </div>
    </div>
</x-modal-form>