<script>
    function editPeserta(btn) {
        const f = document.getElementById('form-edit-peserta');
        f.action = '/peserta-didik/' + btn.dataset.id;

        document.getElementById('edit_nama_lengkap').value = btn.dataset.nama || '';
        document.getElementById('edit_nomor_induk').value = btn.dataset.nomor || '';
        document.getElementById('edit_jenis_kelamin').value = btn.dataset.jk || '';
        document.getElementById('edit_tempat_lahir').value = btn.dataset.tempat || '';
        document.getElementById('edit_tanggal_lahir').value = btn.dataset.tgl || '';
        document.getElementById('edit_agama').value = btn.dataset.agama || '';
        document.getElementById('edit_alamat_lengkap').value = btn.dataset.alamat || '';
        document.getElementById('edit_asal_sekolah').value = btn.dataset.sekolah || '';
        document.getElementById('edit_no_telepon').value = btn.dataset.telp || '';
        document.getElementById('edit_nama_ayah').value = btn.dataset.ayah || '';
        document.getElementById('edit_nama_ibu').value = btn.dataset.ibu || '';
        document.getElementById('edit_pekerjaan_ayah').value = btn.dataset.pkayah || '';
        document.getElementById('edit_pekerjaan_ibu').value = btn.dataset.pkibu || '';
        document.getElementById('edit_no_telepon_ayah').value = btn.dataset.telpayah || '';
        document.getElementById('edit_no_telepon_ibu').value = btn.dataset.telpibu || '';
        document.getElementById('edit_informasi_dari').value = btn.dataset.info || '';
        document.getElementById('edit_paket_bimbingan_id').value = btn.dataset.paket || '';
        document.getElementById('edit_kelompok_belajar').value = btn.dataset.kelompok || '';
        document.getElementById('edit_status').value = btn.dataset.status || 'Aktif';

        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>