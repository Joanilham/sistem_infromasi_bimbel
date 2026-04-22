<!-- jQuery & DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Global Delete Confirmation Script -->
<script>
    function confirmDelete(title, text, formElement) {
        Swal.fire({
            title: title || 'Apakah Anda yakin?',
            text: text || "Data yang dihapus tidak dapat direstore!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5', // Indigo 600
            cancelButtonColor: '#ef4444', // Red 500
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
            customClass: {
                popup: 'rounded-2xl border border-slate-100 dark:border-slate-700',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                formElement.submit();
            }
        })
    }
</script>

@yield('scripts')