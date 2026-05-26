<?php

use App\Models\KategoriPemasukan;
use App\Models\KategoriPengeluaran;
use App\Models\Kantor;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Periode;
use App\Models\User;
use App\Models\PesertaDidik;
use App\Models\PembayaranSiswa;
use App\Models\TransaksiPembayaran;
use App\Models\PaketBimbingan;

function makeKeuanganContext(): array
{
    $kantor  = Kantor::create(['nama_kantor' => 'Keu Kantor', 'alamat' => 'Keu Kota']);
    $periode = Periode::create(['tahun_periode' => '2025/2026', 'is_active' => true]);
    $admin   = User::factory()->create(['level' => 'Super Admin', 'is_active' => true]);
    return compact('kantor', 'periode', 'admin');
}

// ─────────────────────────────────────────────────────────────────────────────
// PEMASUKAN
// ─────────────────────────────────────────────────────────────────────────────

test('admin can view pemasukan index', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('keuangan.pemasukan.index'));

    $response->assertStatus(200);

    // Call with search term to cover lines 30..31
    $responseSearch = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('keuangan.pemasukan.index', ['search' => 'Bulanan']));

    $responseSearch->assertStatus(200);
});

test('admin can view kategori pemasukan index', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('keuangan.pemasukan.kategori.index'));

    $response->assertStatus(200);
});

test('admin can store kategori pemasukan', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('keuangan.pemasukan.kategori.store'), ['nama' => 'SPP Bulanan']);

    $response->assertRedirect();
    $this->assertDatabaseHas('kategori_pemasukan', ['nama' => 'SPP Bulanan']);
});

test('store kategori pemasukan fails with duplicate name', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();
    KategoriPemasukan::create(['nama' => 'Duplikat']);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('keuangan.pemasukan.kategori.store'), ['nama' => 'Duplikat']);

    $response->assertSessionHasErrors('nama');
});

test('admin can delete kategori pemasukan with no entries', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();
    $kategori = KategoriPemasukan::create(['nama' => 'Kategori Hapus']);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->delete(route('keuangan.pemasukan.kategori.destroy', $kategori));

    $response->assertRedirect();
    $this->assertDatabaseMissing('kategori_pemasukan', ['id' => $kategori->id]);
});

test('cannot delete kategori pemasukan that has entries', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();
    $kategori = KategoriPemasukan::create(['nama' => 'Terpakai']);

    Pemasukan::create([
        'tanggal'     => now()->toDateString(),
        'kategori_id' => $kategori->id,
        'nominal'     => 100000,
        'keterangan'  => 'Test pemasukan',
        'user_id'     => $admin->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->delete(route('keuangan.pemasukan.kategori.destroy', $kategori));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('kategori_pemasukan', ['id' => $kategori->id]);
});

test('admin can store pemasukan', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();
    $kategori = KategoriPemasukan::create(['nama' => 'Kas Masuk']);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('keuangan.pemasukan.store'), [
            'tanggal'     => now()->toDateString(),
            'kategori_id' => $kategori->id,
            'nominal'     => 500000,
            'keterangan'  => 'Uang masuk',
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('pemasukan', ['keterangan' => 'Uang masuk']);
});

test('store pemasukan fails without required fields', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('keuangan.pemasukan.store'), []);

    $response->assertSessionHasErrors(['tanggal', 'kategori_id', 'nominal']);
});

test('super admin can view edit pemasukan form', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();
    $kategori = KategoriPemasukan::create(['nama' => 'Edit Kat']);

    $pemasukan = Pemasukan::create([
        'tanggal'     => now()->toDateString(),
        'kategori_id' => $kategori->id,
        'nominal'     => 200000,
        'keterangan'  => 'Edit test',
        'user_id'     => $admin->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('keuangan.pemasukan.edit', $pemasukan));

    $response->assertStatus(200);
});

test('super admin can update pemasukan', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();
    $kategori  = KategoriPemasukan::create(['nama' => 'Update Kat']);

    $pemasukan = Pemasukan::create([
        'tanggal'     => now()->toDateString(),
        'kategori_id' => $kategori->id,
        'nominal'     => 100000,
        'keterangan'  => 'Sebelum update',
        'user_id'     => $admin->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->put(route('keuangan.pemasukan.update', $pemasukan), [
            'tanggal'     => now()->toDateString(),
            'kategori_id' => $kategori->id,
            'nominal'     => 200000,
            'keterangan'  => 'Sesudah update',
        ]);

    $response->assertRedirect(route('keuangan.pemasukan.index'));
    $this->assertDatabaseHas('pemasukan', ['id' => $pemasukan->id, 'keterangan' => 'Sesudah update']);
});

test('super admin can delete pemasukan', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();
    $kategori  = KategoriPemasukan::create(['nama' => 'Del Kat']);

    $pemasukan = Pemasukan::create([
        'tanggal'     => now()->toDateString(),
        'kategori_id' => $kategori->id,
        'nominal'     => 50000,
        'keterangan'  => 'Akan dihapus',
        'user_id'     => $admin->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->delete(route('keuangan.pemasukan.destroy', $pemasukan));

    $response->assertRedirect();
    $this->assertSoftDeleted('pemasukan', ['id' => $pemasukan->id]);
});

// ─────────────────────────────────────────────────────────────────────────────
// PENGELUARAN
// ─────────────────────────────────────────────────────────────────────────────

test('admin can view pengeluaran index', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('keuangan.pengeluaran.index'));

    $response->assertStatus(200);

    // Call with search parameter to cover lines 30..31
    $responseSearch = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('keuangan.pengeluaran.index', ['search' => 'Buku']));

    $responseSearch->assertStatus(200);
});

test('super admin can view edit pengeluaran form', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();
    $kategori = KategoriPengeluaran::create(['nama' => 'Edit Kat']);

    $pengeluaran = Pengeluaran::create([
        'tanggal'     => now()->toDateString(),
        'kategori_id' => $kategori->id,
        'nominal'     => 150000,
        'keterangan'  => 'Edit test',
        'user_id'     => $admin->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('keuangan.pengeluaran.edit', $pengeluaran->id));

    $response->assertStatus(200);
});

test('admin can view kategori pengeluaran index', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('keuangan.pengeluaran.kategori.index'));

    $response->assertStatus(200);
});

test('admin can store kategori pengeluaran', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('keuangan.pengeluaran.kategori.store'), ['nama' => 'Operasional']);

    $response->assertRedirect();
    $this->assertDatabaseHas('kategori_pengeluaran', ['nama' => 'Operasional']);
});

test('admin can delete unused kategori pengeluaran', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();
    $kategori = KategoriPengeluaran::create(['nama' => 'Hapus Pengeluaran']);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->delete(route('keuangan.pengeluaran.kategori.destroy', $kategori));

    $response->assertRedirect();
    $this->assertDatabaseMissing('kategori_pengeluaran', ['id' => $kategori->id]);
});

test('cannot delete kategori pengeluaran that has entries', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();
    $kategori = KategoriPengeluaran::create(['nama' => 'Dipakai Pengeluaran']);

    Pengeluaran::create([
        'tanggal'     => now()->toDateString(),
        'kategori_id' => $kategori->id,
        'nominal'     => 150000,
        'keterangan'  => 'Pengeluaran test',
        'user_id'     => $admin->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->delete(route('keuangan.pengeluaran.kategori.destroy', $kategori));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('kategori_pengeluaran', ['id' => $kategori->id]);
});

test('admin can store pengeluaran', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();
    $kategori = KategoriPengeluaran::create(['nama' => 'ATK']);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('keuangan.pengeluaran.store'), [
            'tanggal'     => now()->toDateString(),
            'kategori_id' => $kategori->id,
            'nominal'     => 75000,
            'keterangan'  => 'Beli pulpen',
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('pengeluaran', ['keterangan' => 'Beli pulpen']);
});

test('super admin can update pengeluaran', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();
    $kategori = KategoriPengeluaran::create(['nama' => 'Update Kat']);

    $pengeluaran = Pengeluaran::create([
        'tanggal'     => now()->toDateString(),
        'kategori_id' => $kategori->id,
        'nominal'     => 50000,
        'keterangan'  => 'Lama',
        'user_id'     => $admin->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->put(route('keuangan.pengeluaran.update', $pengeluaran), [
            'tanggal'     => now()->toDateString(),
            'kategori_id' => $kategori->id,
            'nominal'     => 80000,
            'keterangan'  => 'Baru',
        ]);

    $response->assertRedirect(route('keuangan.pengeluaran.index'));
    $this->assertDatabaseHas('pengeluaran', ['id' => $pengeluaran->id, 'keterangan' => 'Baru']);
});

test('super admin can delete pengeluaran', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();
    $kategori = KategoriPengeluaran::create(['nama' => 'Del Kat']);

    $pengeluaran = Pengeluaran::create([
        'tanggal'     => now()->toDateString(),
        'kategori_id' => $kategori->id,
        'nominal'     => 25000,
        'keterangan'  => 'Akan dihapus',
        'user_id'     => $admin->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->delete(route('keuangan.pengeluaran.destroy', $pengeluaran));

    $response->assertRedirect();
    $this->assertSoftDeleted('pengeluaran', ['id' => $pengeluaran->id]);
});

// ─────────────────────────────────────────────────────────────────────────────
// PEMBAYARAN SISWA (PembayaranController & TagihanController)
// ─────────────────────────────────────────────────────────────────────────────

test('admin can view pembayaran siswa index', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('keuangan.pembayaran.index'));

    $response->assertStatus(200);

    // Call with search and sort parameters to cover lines 36-38 and 45-47
    $responseFilter = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('keuangan.pembayaran.index', [
            'search' => 'Siswa',
            'sort' => 'batas_waktu',
            'order' => 'asc'
        ]));

    $responseFilter->assertStatus(200);
});

test('admin can view pembayaran siswa show/detail', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();
    $paket = PaketBimbingan::create([
        'nama_paket' => 'Paket Keuangan',
        'nominal'    => 1200000,
        'kantor_id'  => $kantor->id,
        'periode_id' => $periode->id,
    ]);
    $peserta = PesertaDidik::create([
        'nama_lengkap'       => 'Siswa Keuangan',
        'nisn'               => '5566778899',
        'jenis_kelamin'      => 'L',
        'asal_sekolah'       => 'SMP 1',
        'status'             => 'Aktif',
        'kantor_id'          => $kantor->id,
        'periode_id'         => $periode->id,
        'paket_bimbingan_id' => $paket->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('keuangan.pembayaran.show', $peserta->id));

    $response->assertStatus(200);
    $response->assertSee('Siswa Keuangan');
});

test('admin can update discount and register fee in pembayaran', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();
    $paket = PaketBimbingan::create([
        'nama_paket' => 'Paket Keuangan 2',
        'nominal'    => 1200000,
        'kantor_id'  => $kantor->id,
        'periode_id' => $periode->id,
    ]);
    $peserta = PesertaDidik::create([
        'nama_lengkap'       => 'Siswa Keuangan 2',
        'nisn'               => '4433221199',
        'jenis_kelamin'      => 'L',
        'asal_sekolah'       => 'SMP 1',
        'status'             => 'Aktif',
        'kantor_id'          => $kantor->id,
        'periode_id'         => $periode->id,
        'paket_bimbingan_id' => $paket->id,
    ]);

    $pembayaran = PembayaranSiswa::where('peserta_didik_id', $peserta->id)->first();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->put(route('keuangan.pembayaran.update', $pembayaran->id), [
            'diskon_persen'     => 10,
            'diskon_nominal'    => 120000,
            'biaya_pendaftaran' => 50000,
        ]);

    $response->assertRedirect();
    $pembayaran->refresh();
    expect($pembayaran->diskon_nominal)->toBe(120000);
    expect($pembayaran->biaya_pendaftaran)->toBe(50000);
});

test('admin can store new transaction (storeTransaksi)', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();
    $paket = PaketBimbingan::create([
        'nama_paket' => 'Paket Keuangan 3',
        'nominal'    => 1000000,
        'kantor_id'  => $kantor->id,
        'periode_id' => $periode->id,
    ]);
    $peserta = PesertaDidik::create([
        'nama_lengkap'       => 'Siswa Keuangan 3',
        'nisn'               => '3344556677',
        'jenis_kelamin'      => 'L',
        'asal_sekolah'       => 'SMP 1',
        'status'             => 'Aktif',
        'no_telepon'         => '08123456789',
        'kantor_id'          => $kantor->id,
        'periode_id'         => $periode->id,
        'paket_bimbingan_id' => $paket->id,
    ]);

    $pembayaran = PembayaranSiswa::where('peserta_didik_id', $peserta->id)->first();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('keuangan.pembayaran.transaksi.store', $pembayaran->id), [
            'nominal'         => 500000,
            'tanggal'         => '2026-05-19',
            'tipe_pembayaran' => 'TRANSFER',
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('transaksi_pembayaran', [
        'pembayaran_siswa_id' => $pembayaran->id,
        'nominal'             => 500000,
        'tipe_pembayaran'     => 'TRANSFER',
    ]);
});

test('super admin can delete transaction (destroyTransaksi)', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();
    $paket = PaketBimbingan::create([
        'nama_paket' => 'Paket Keuangan 4',
        'nominal'    => 1000000,
        'kantor_id'  => $kantor->id,
        'periode_id' => $periode->id,
    ]);
    $peserta = PesertaDidik::create([
        'nama_lengkap'       => 'Siswa Keuangan 4',
        'nisn'               => '2233445566',
        'jenis_kelamin'      => 'L',
        'asal_sekolah'       => 'SMP 1',
        'status'             => 'Aktif',
        'kantor_id'          => $kantor->id,
        'periode_id'         => $periode->id,
        'paket_bimbingan_id' => $paket->id,
    ]);

    $pembayaran = PembayaranSiswa::where('peserta_didik_id', $peserta->id)->first();

    $transaksi = $pembayaran->transaksi()->create([
        'no_kwitansi'     => 'KW-TEST-123',
        'nominal'         => 200000,
        'tanggal'         => '2026-05-19',
        'tipe_pembayaran' => 'TUNAI',
        'penerima'        => $admin->name,
        'user_id'         => $admin->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->delete(route('keuangan.transaksi.destroy', $transaksi->id));

    $response->assertRedirect();
    $this->assertSoftDeleted('transaksi_pembayaran', [
        'id' => $transaksi->id,
    ]);
});

test('super admin can edit and update transaction (editTransaksi & updateTransaksi)', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();
    $paket = PaketBimbingan::create([
        'nama_paket' => 'Paket Keuangan 5',
        'nominal'    => 1000000,
        'kantor_id'  => $kantor->id,
        'periode_id' => $periode->id,
    ]);
    $peserta = PesertaDidik::create([
        'nama_lengkap'       => 'Siswa Keuangan 5',
        'nisn'               => '9988776655',
        'jenis_kelamin'      => 'L',
        'asal_sekolah'       => 'SMP 1',
        'status'             => 'Aktif',
        'kantor_id'          => $kantor->id,
        'periode_id'         => $periode->id,
        'paket_bimbingan_id' => $paket->id,
    ]);

    $pembayaran = PembayaranSiswa::where('peserta_didik_id', $peserta->id)->first();

    $transaksi = $pembayaran->transaksi()->create([
        'no_kwitansi'     => 'KW-TEST-456',
        'nominal'         => 300000,
        'tanggal'         => '2026-05-19',
        'tipe_pembayaran' => 'TUNAI',
        'penerima'        => $admin->name,
        'user_id'         => $admin->id,
    ]);

    $responseEdit = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('keuangan.transaksi.edit', $transaksi->id));

    $responseEdit->assertStatus(200);

    $responseUpdate = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->put(route('keuangan.transaksi.update', $transaksi->id), [
            'nominal'         => 400000,
            'tanggal'         => '2026-05-20',
            'tipe_pembayaran' => 'TRANSFER',
        ]);

    $responseUpdate->assertRedirect(route('keuangan.pembayaran.show', $peserta->id));
    $this->assertDatabaseHas('transaksi_pembayaran', [
        'id'              => $transaksi->id,
        'nominal'         => 400000,
        'tanggal'         => '2026-05-20 00:00:00',
        'tipe_pembayaran' => 'TRANSFER',
    ]);
});

test('admin can view tagihan index (outstanding bills)', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeKeuanganContext();
    $paket = PaketBimbingan::create([
        'nama_paket' => 'Paket Tagihan',
        'nominal'    => 500000,
        'kantor_id'  => $kantor->id,
        'periode_id' => $periode->id,
    ]);
    $peserta = PesertaDidik::create([
        'nama_lengkap'       => 'Siswa Tertagih',
        'nisn'               => '1212121212',
        'jenis_kelamin'      => 'L',
        'asal_sekolah'       => 'SMP 1',
        'status'             => 'Aktif',
        'kantor_id'          => $kantor->id,
        'periode_id'         => $periode->id,
        'paket_bimbingan_id' => $paket->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('keuangan.tagihan.index'));

    $response->assertStatus(200);
});

