<script>
    function editPaket(button) {
        const id = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');
        const nominal = button.getAttribute('data-nominal');
        const coret = button.getAttribute('data-coret');
        const durasiJml = button.getAttribute('data-durasi-jml');
        const durasiSat = button.getAttribute('data-durasi-sat');
        const label = button.getAttribute('data-label');
        const deskripsi = button.getAttribute('data-deskripsi');
        const benefits = button.getAttribute('data-benefits');
        const target = button.getAttribute('data-target');
        const fasilitas = button.getAttribute('data-fasilitas');
        const featured = button.getAttribute('data-featured');

        document.getElementById('form-modal-edit').action = '/paket-bimbingan/' + id;
        document.getElementById('edit_nama_paket').value = nama;
        document.getElementById('edit_nominal').value = formatRupiah(nominal || '');
        document.getElementById('edit_harga_coret').value = formatRupiah(coret || '');
        document.getElementById('edit_durasi_jumlah').value = durasiJml || '';
        document.getElementById('edit_durasi_satuan').value = durasiSat || 'Bulan';
        document.getElementById('edit_label_populer').value = label || '';
        document.getElementById('edit_deskripsi').value = deskripsi || '';
        document.getElementById('edit_benefits').value = benefits || '';
        document.getElementById('edit_target_peserta').value = target || '';
        document.getElementById('edit_fasilitas').value = fasilitas || '';
        document.getElementById('edit_is_featured').checked = featured === '1';

        document.getElementById('modal-edit').classList.remove('hidden');
    }

    // Fungsi format ribuan (titik)
    function formatRupiah(angka, prefix) {
        if (!angka) return '';
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