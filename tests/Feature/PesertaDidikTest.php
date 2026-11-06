<?php

use App\Models\Kantor;
use App\Models\Periode;
use App\Models\PesertaDidik;
use App\Models\PaketBimbingan;
use App\Models\KelompokBelajar;
use App\Models\User;
use Illuminate\Support\Facades\Session;

// Inisialisasi data master awal untuk setiap pengujian (Kantor, Periode, Paket, Kelompok, dan Admin)
beforeEach(function () {
    $this->kantor = Kantor::create(['nama_kantor' => 'Kantor Utama', 'alamat' => 'Bandung']);
    $this->periode = Periode::create([
        'tahun_periode' => '2026/2027',
        'is_active'     => true,
    ]);
    $this->paket = PaketBimbingan::create([
        'nama_paket' => 'Paket Platinum',
        'nominal'    => 1000000,
        'kantor_id'  => $this->kantor->id,
        'periode_id' => $this->periode->id,
    ]);
    $this->kelompok = KelompokBelajar::create([
        'nama_kelompok' => 'Kelompok 1A',
        'kantor_id'     => $this->kantor->id,
        'periode_id'    => $this->periode->id,
    ]);
    $this->admin = User::factory()->create([
        'level'     => 'Admin',
        'is_active' => true,
    ]);
});

// Skenario: Menampilkan daftar peserta didik aktif
test('admin can view active peserta didik index', function () {
    // [Arrange] Siapkan data peserta didik dengan status 'Aktif'
    PesertaDidik::create([
        'nama_lengkap'       => 'Peserta Aktif',
        'nisn'               => '1234567890',
        'jenis_kelamin'      => 'L',
        'asal_sekolah'       => 'SMP 1',
        'status'             => 'Aktif',
        'kantor_id'          => $this->kantor->id,
        'periode_id'         => $this->periode->id,
        'paket_bimbingan_id' => $this->paket->id,
    ]);

    // [Act] Admin mengakses halaman daftar peserta didik dengan sesi kantor & periode aktif
    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->get(route('peserta-didik.index'));

    // [Assert] Respon sukses (HTTP 200) dan teks nama peserta muncul pada tampilan
    $response->assertStatus(200);
    $response->assertSee('Peserta Aktif');
});

// Skenario: Menampilkan daftar peserta didik yang berstatus keluar
test('admin can view keluar peserta didik list', function () {
    // [Arrange] Siapkan data peserta didik dengan status 'Keluar' beserta riwayat tanggal dan alasan
    PesertaDidik::create([
        'nama_lengkap'       => 'Peserta Keluar',
        'nisn'               => '0987654321',
        'jenis_kelamin'      => 'P',
        'asal_sekolah'       => 'SMP 2',
        'status'             => 'Keluar',
        'kantor_id'          => $this->kantor->id,
        'periode_id'         => $this->periode->id,
        'paket_bimbingan_id' => $this->paket->id,
        'tanggal_keluar'     => '2026-05-01',
        'alasan_keluar'      => 'Lulus',
    ]);

    // [Act] Admin mengakses halaman daftar peserta didik keluar
    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->get(route('peserta-didik.keluar'));

    // [Assert] Respon sukses (HTTP 200) dan teks nama peserta keluar tampil
    $response->assertStatus(200);
    $response->assertSee('Peserta Keluar');
});

// Skenario: Menampilkan formulir pendaftaran peserta didik baru
test('admin can view create form', function () {
    // [Act] Admin mengakses halaman form create
    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->get(route('peserta-didik.create'));

    // [Assert] Respon sukses (HTTP 200) memuat tampilan form create
    $response->assertStatus(200);
});

// Skenario: Menyimpan data pendaftaran peserta didik baru (Happy Path)
test('admin can store peserta didik', function () {
    // [Act] Mengirim data pendaftaran peserta didik baru via HTTP POST
    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->post(route('peserta-didik.store'), [
            'nama_lengkap'        => 'Peserta Baru',
            'nisn'                => '1122334455',
            'jenis_kelamin'       => 'L',
            'asal_sekolah'        => 'SMP 3',
            'paket_bimbingan_id'  => $this->paket->id,
            'kelompok_belajar_id' => $this->kelompok->id,
        ]);

    // [Assert] Redirect otomatis ke halaman tagihan/pembayaran dan data tercatat di database dengan status Aktif
    $peserta = PesertaDidik::where('nisn', '1122334455')->first();
    $response->assertRedirect(route('keuangan.pembayaran.show', $peserta->id));
    $this->assertDatabaseHas('peserta_didiks', [
        'nama_lengkap' => 'Peserta Baru',
        'nisn'         => '1122334455',
        'status'       => 'Aktif',
    ]);
});

// Skenario: Validasi penolakan duplikasi NISN (Negative Test)
test('store peserta didik fails with duplicate nisn', function () {
    // [Arrange] Buat data siswa yang telah terdaftar dengan nomor NISN tertentu
    PesertaDidik::create([
        'nama_lengkap'       => 'Siswa Lama',
        'nisn'               => '1122334455',
        'jenis_kelamin'      => 'P',
        'asal_sekolah'       => 'SMP 1',
        'status'             => 'Aktif',
        'kantor_id'          => $this->kantor->id,
        'periode_id'         => $this->periode->id,
        'paket_bimbingan_id' => $this->paket->id,
    ]);

    // [Act] Mengirim data siswa baru dengan NISN yang sama persis
    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->post(route('peserta-didik.store'), [
            'nama_lengkap'        => 'Siswa Kloning',
            'nisn'                => '1122334455',
            'jenis_kelamin'       => 'L',
            'asal_sekolah'        => 'SMP 3',
            'paket_bimbingan_id'  => $this->paket->id,
            'kelompok_belajar_id' => $this->kelompok->id,
        ]);

    // [Assert] Sistem menolak dan memicu error validasi sesi pada kolom 'nisn'
    $response->assertSessionHasErrors('nisn');
});

// Skenario: Menampilkan formulir ubah data peserta didik
test('admin can view edit form', function () {
    // [Arrange] Siapkan data peserta didik yang akan diedit
    $peserta = PesertaDidik::create([
        'nama_lengkap'       => 'Siswa Edit',
        'nisn'               => '8888888888',
        'jenis_kelamin'      => 'L',
        'asal_sekolah'       => 'SMP 1',
        'status'             => 'Aktif',
        'kantor_id'          => $this->kantor->id,
        'periode_id'         => $this->periode->id,
        'paket_bimbingan_id' => $this->paket->id,
    ]);

    // [Act] Admin mengakses halaman edit berdasarkan ID peserta
    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->get(route('peserta-didik.edit', $peserta->id));

    // [Assert] Respon sukses (HTTP 200) memuat tampilan form edit
    $response->assertStatus(200);
});

// Skenario: Memperbarui data peserta didik
test('admin can update peserta didik', function () {
    // [Arrange] Siapkan data peserta didik awal
    $peserta = PesertaDidik::create([
        'nama_lengkap'       => 'Siswa Update',
        'nisn'               => '9999999999',
        'jenis_kelamin'      => 'L',
        'asal_sekolah'       => 'SMP 1',
        'status'             => 'Aktif',
        'kantor_id'          => $this->kantor->id,
        'periode_id'         => $this->periode->id,
        'paket_bimbingan_id' => $this->paket->id,
    ]);

    // [Act] Mengirim data pembaruan nama dan jenis kelamin via HTTP PUT
    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->put(route('peserta-didik.update', $peserta->id), [
            'nama_lengkap'       => 'Siswa Update Baru',
            'nisn'               => '9999999999',
            'jenis_kelamin'      => 'P',
            'asal_sekolah'       => 'SMP 10',
            'status'             => 'Aktif',
            'paket_bimbingan_id' => $this->paket->id,
        ]);

    // [Assert] Redirect ke halaman index dan data di tabel database terbukti terupdate
    $response->assertRedirect(route('peserta-didik.index'));
    $this->assertDatabaseHas('peserta_didiks', [
        'id'           => $peserta->id,
        'nama_lengkap' => 'Siswa Update Baru',
        'jenis_kelamin'=> 'P',
    ]);
});

// Skenario: Menghapus data peserta didik menggunakan metode Soft Delete
test('admin can delete peserta didik', function () {
    // [Arrange] Siapkan data peserta didik yang akan dihapus
    $peserta = PesertaDidik::create([
        'nama_lengkap'       => 'Siswa Hapus',
        'nisn'               => '7777777777',
        'jenis_kelamin'      => 'L',
        'asal_sekolah'       => 'SMP 1',
        'status'             => 'Aktif',
        'kantor_id'          => $this->kantor->id,
        'periode_id'         => $this->periode->id,
        'paket_bimbingan_id' => $this->paket->id,
    ]);

    // [Act] Mengirim permintaan HTTP DELETE ke endpoint destroy
    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->delete(route('peserta-didik.destroy', $peserta->id));

    // [Assert] Redirect berhasil dan data tercatat sebagai Soft Deleted (kolom deleted_at terisi)
    $response->assertRedirect();
    $this->assertSoftDeleted('peserta_didiks', [
        'id' => $peserta->id,
    ]);
});

// Skenario: Ekspor data peserta didik aktif ke format Excel
test('admin can can export active peserta didik', function () {
    // [Act] Mengakses endpoint ekspor data siswa aktif
    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->get(route('peserta-didik.export'));

    // [Assert] Respon sukses (HTTP 200) dengan header MIME type spreadsheet Excel
    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/vnd.ms-excel; charset=UTF-8');
});

// Skenario: Ekspor data peserta didik keluar ke format Excel
test('admin can can export keluar peserta didik', function () {
    // [Act] Mengakses endpoint ekspor data siswa keluar
    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->get(route('peserta-didik.keluar.export'));

    // [Assert] Respon sukses (HTTP 200) dengan header MIME type spreadsheet Excel
    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/vnd.ms-excel; charset=UTF-8');
});
