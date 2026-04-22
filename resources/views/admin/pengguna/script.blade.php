<script>
    function editPengguna(button) {
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const username = button.getAttribute('data-username');
        const email = button.getAttribute('data-email');
        const level = button.getAttribute('data-level');

        // Set action form
        document.getElementById('form-modal-edit').action = `/pengguna/${id}`;

        // Set input values
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_level').value = level;

        // Clear password fields on edit open
        document.getElementById('edit_password').value = '';
        document.getElementById('edit_password_confirmation').value = '';

        // Tampilkan modal
        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>