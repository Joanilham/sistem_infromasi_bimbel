<?php

use App\Models\Kantor;
use App\Models\Periode;
use App\Models\PesertaDidik;
use App\Models\PaketBimbingan;
use App\Models\KelompokBelajar;
use App\Models\User;
use Illuminate\Support\Facades\Session;

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

test('admin can view active peserta didik index', function () {
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

    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->get(route('peserta-didik.index'));

    $response->assertStatus(200);
    $response->assertSee('Peserta Aktif');
});

test('admin can view keluar peserta didik list', function () {
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

    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->get(route('peserta-didik.keluar'));

    $response->assertStatus(200);
    $response->assertSee('Peserta Keluar');
});

test('admin can view create form', function () {
    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->get(route('peserta-didik.create'));

    $response->assertStatus(200);
});

test('admin can store peserta didik', function () {
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

    $response->assertRedirect(route('peserta-didik.index'));
    $this->assertDatabaseHas('peserta_didiks', [
        'nama_lengkap' => 'Peserta Baru',
        'nisn'         => '1122334455',
        'status'       => 'Aktif',
    ]);
});

test('store peserta didik fails with duplicate nisn', function () {
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

    $response->assertSessionHasErrors('nisn');
});

test('admin can view edit form', function () {
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

    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->get(route('peserta-didik.edit', $peserta->id));

    $response->assertStatus(200);
});

test('admin can update peserta didik', function () {
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

    $response->assertRedirect(route('peserta-didik.index'));
    $this->assertDatabaseHas('peserta_didiks', [
        'id'           => $peserta->id,
        'nama_lengkap' => 'Siswa Update Baru',
        'jenis_kelamin'=> 'P',
    ]);
});

test('admin can delete peserta didik', function () {
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

    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->delete(route('peserta-didik.destroy', $peserta->id));

    $response->assertRedirect();
    $this->assertSoftDeleted('peserta_didiks', [
        'id' => $peserta->id,
    ]);
});

test('admin can can export active peserta didik', function () {
    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->get(route('peserta-didik.export'));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/vnd.ms-excel; charset=UTF-8');
});

test('admin can can export keluar peserta didik', function () {
    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->get(route('peserta-didik.keluar.export'));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/vnd.ms-excel; charset=UTF-8');
});
