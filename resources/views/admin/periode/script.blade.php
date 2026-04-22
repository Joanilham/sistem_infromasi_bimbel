<script>
    function editPeriode(button) {
        const id = button.getAttribute('data-id');
        const tahun = button.getAttribute('data-tahun');
        const isActive = button.getAttribute('data-active') === '1';

        document.getElementById('form-modal-edit').action = '/periode/' + id;
        document.getElementById('edit_tahun_periode').value = tahun;
        document.getElementById('edit_is_active').checked = isActive;
        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>