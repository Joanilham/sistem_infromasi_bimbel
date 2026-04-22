<script>
    function editKantor(button) {
        const id = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');
        const alamat = button.getAttribute('data-alamat');

        document.getElementById('form-modal-edit').action = '/kantor/' + id;
        document.getElementById('edit_nama_kantor').value = nama;
        document.getElementById('edit_alamat').value = alamat;
        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>