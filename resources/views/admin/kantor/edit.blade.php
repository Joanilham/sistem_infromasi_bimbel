<!-- Modal Edit -->
<x-modal-form id="modal-edit" title="Edit Kantor" action="">
    <x-slot name="method">
        @method('PUT')
    </x-slot>
    <div class="pd-grid" style="margin-top: 10px;">
        <div class="pd-field full">
            <label for="edit_nama_kantor">Nama Kantor <span>*</span></label>
            <input type="text" name="nama_kantor" id="edit_nama_kantor" required placeholder="Misal: Kantor Pusat">
        </div>
        <div class="pd-field full">
            <label for="edit_alamat">Alamat Lengkap <span>*</span></label>
            <textarea name="alamat" id="edit_alamat" rows="3" required placeholder="Masukkan alamat lengkap kantor..."></textarea>
        </div>
    </div>
</x-modal-form>