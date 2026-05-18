<!-- Modal Create -->
<x-modal-form id="modal-create" title="Tambah Kantor Baru" action="{{ route('kantor.store') }}">
    <div class="pd-grid" style="margin-top: 10px;">
        <div class="pd-field full">
            <label for="nama_kantor">Nama Kantor <span>*</span></label>
            <input type="text" name="nama_kantor" id="nama_kantor" required placeholder="Misal: Kantor Pusat">
        </div>
        <div class="pd-field full">
            <label for="alamat">Alamat Lengkap <span>*</span></label>
            <textarea name="alamat" id="alamat" rows="3" required placeholder="Masukkan alamat lengkap kantor..."></textarea>
        </div>
    </div>
</x-modal-form>