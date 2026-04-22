<script>
    function editPaket(button) {
        const id = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');
        const nominal = button.getAttribute('data-nominal');

        document.getElementById('form-modal-edit').action = '/paket-bimbingan/' + id;
        document.getElementById('edit_nama_paket').value = nama;
        document.getElementById('edit_nominal').value = formatRupiah(nominal);
        document.getElementById('modal-edit').classList.remove('hidden');
    }

    // Fungsi format ribuan (titik)
    function formatRupiah(angka, prefix) {
        var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    // Listener untuk semua input dengan class nominal-input
    document.querySelectorAll('.nominal-input').forEach(input => {
        input.addEventListener('keyup', function(e) {
            this.value = formatRupiah(this.value);
        });
    });
</script>