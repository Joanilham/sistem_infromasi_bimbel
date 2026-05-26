<?php

use App\Models\User;

test('superadmin can access admin dashboard', function () {
    $user = User::factory()->create([
        'level' => 'Super Admin',
        'is_active' => true,
    ]);

    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertStatus(200);
});

test('admin can access admin dashboard', function () {
    $user = User::factory()->create([
        'level' => 'Admin',
        'is_active' => true,
    ]);

    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertStatus(200);
});

test('guru cannot access admin dashboard', function () {
    $user = User::factory()->create([
        'level' => 'Guru',
        'is_active' => true,
    ]);

    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertRedirect('/guru/dashboard');
});

test('siswa cannot access admin dashboard', function () {
    $user = User::factory()->create([
        'level' => 'Siswa',
        'is_active' => true,
    ]);

    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertRedirect('/siswa/dashboard');
});

test('guru can access guru dashboard', function () {
    $user = User::factory()->create([
        'level' => 'Guru',
        'is_active' => true,
    ]);

    $response = $this->actingAs($user)->get('/guru/dashboard');
    $response->assertStatus(200);
});

test('siswa can access siswa dashboard', function () {
    $user = User::factory()->create([
        'level' => 'Siswa',
        'is_active' => true,
    ]);

    $response = $this->actingAs($user)->get('/siswa/dashboard');
    $response->assertStatus(403);
});

test('admin can access admin dashboard with context session and triggers stats queries', function () {
    $kantor  = \App\Models\Kantor::create(['nama_kantor' => 'Dash Kantor', 'alamat' => 'Dash Kota']);
    $periode = \App\Models\Periode::create(['tahun_periode' => '2025/2026', 'is_active' => true]);
    $admin   = User::factory()->create(['level' => 'Super Admin', 'is_active' => true]);

    $kategoriPem = \App\Models\KategoriPemasukan::create(['nama' => 'Kat Pem']);
    $kategoriPeng = \App\Models\KategoriPengeluaran::create(['nama' => 'Kat Peng']);

    $paket = \App\Models\PaketBimbingan::create([
        'nama_paket' => 'Paket Dash',
        'nominal'    => 1000000,
        'kantor_id'  => $kantor->id,
        'periode_id' => $periode->id,
    ]);

    $peserta = \App\Models\PesertaDidik::create([
        'nama_lengkap'       => 'Siswa Dash',
        'nisn'               => '1122334455',
        'jenis_kelamin'      => 'L',
        'asal_sekolah'       => 'SMP 1',
        'status'             => 'Aktif',
        'kantor_id'          => $kantor->id,
        'periode_id'         => $periode->id,
        'paket_bimbingan_id' => $paket->id,
        'tanggal_keluar'     => now()->toDateString(),
    ]);

    $pembayaran = \App\Models\PembayaranSiswa::where('peserta_didik_id', $peserta->id)->first();
    $pembayaran->update([
        'batas_waktu' => now()->addDays(2)->toDateString(),
    ]);

    $transaksi = $pembayaran->transaksi()->create([
        'no_kwitansi'     => 'KW-DASH-123',
        'nominal'         => 100000,
        'tanggal'         => now()->toDateString(),
        'tipe_pembayaran' => 'TUNAI',
        'penerima'        => $admin->name,
        'user_id'         => $admin->id,
    ]);

    \App\Models\Pemasukan::create([
        'tanggal'     => now()->toDateString(),
        'kategori_id' => $kategoriPem->id,
        'nominal'     => 50000,
        'keterangan'  => 'Pemasukan Dash',
        'user_id'     => $admin->id,
    ]);

    \App\Models\Pengeluaran::create([
        'tanggal'     => now()->toDateString(),
        'kategori_id' => $kategoriPeng->id,
        'nominal'     => 30000,
        'keterangan'  => 'Pengeluaran Dash',
        'user_id'     => $admin->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get('/dashboard');

    $response->assertStatus(200);
});
