<script>
    function editKelompok(button) {
        const id = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');

        document.getElementById('form-modal-edit').action = '/kelompok-belajar/' + id;
        document.getElementById('edit_nama_kelompok').value = nama;
        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>