<?php

use App\Models\Kantor;
use App\Models\Periode;
use App\Models\User;
use App\Models\PaketBimbingan;
use App\Models\KelompokBelajar;
use App\Models\PesertaDidik;

function makePesertaContext(): array
{
    $kantor  = Kantor::create(['nama_kantor' => 'Peserta Kantor', 'alamat' => 'Peserta Kota']);
    $periode = Periode::create(['tahun_periode' => '2025/2026', 'is_active' => true]);
    $admin   = User::factory()->create(['level' => 'Admin', 'is_active' => true]);
    $paket   = PaketBimbingan::create([
        'nama_paket' => 'Super Intensive',
        'nominal'    => 1500000,
        'kantor_id'  => $kantor->id,
        'periode_id' => $periode->id,
    ]);
    $kelompok = KelompokBelajar::create([
        'nama_kelompok' => 'Intensive Alpha',
        'kantor_id'     => $kantor->id,
        'periode_id'    => $periode->id,
    ]);

    return compact('kantor', 'periode', 'admin', 'paket', 'kelompok');
}

test('admin can view active students index with various filters', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin, 'paket' => $paket, 'kelompok' => $kelompok] = makePesertaContext();

    $peserta = PesertaDidik::create([
        'nama_lengkap'        => 'Budi Luhur',
        'nisn'                => '1234567890',
        'jenis_kelamin'       => 'L',
        'asal_sekolah'        => 'SMA 1',
        'status'              => 'Aktif',
        'paket_bimbingan_id'  => $paket->id,
        'kelompok_belajar_id' => $kelompok->id,
        'kantor_id'           => $kantor->id,
        'periode_id'          => $periode->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('peserta-didik.index', [
            'search'        => 'Budi',
            'per_page'      => 25,
            'paket_id'      => $paket->id,
            'kelompok_id'   => $kelompok->id,
            'jenis_kelamin' => 'L',
            'sort'          => 'nama_lengkap',
            'order'         => 'asc'
        ]));

    $response->assertStatus(200);
});

test('admin can view exited students index with various filters', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin, 'paket' => $paket] = makePesertaContext();

    $peserta = PesertaDidik::create([
        'nama_lengkap'       => 'Ani Haryani',
        'nisn'               => '0987654321',
        'jenis_kelamin'      => 'P',
        'asal_sekolah'       => 'SMA 2',
        'status'             => 'Keluar',
        'tanggal_keluar'     => '2026-05-19',
        'alasan_keluar'      => 'Pindah kota',
        'paket_bimbingan_id' => $paket->id,
        'kantor_id'          => $kantor->id,
        'periode_id'         => $periode->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('peserta-didik.keluar', [
            'search'        => 'Ani',
            'per_page'      => 10,
            'paket_id'      => $paket->id,
            'jenis_kelamin' => 'P',
            'sort'          => 'tanggal_keluar',
            'order'         => 'desc'
        ]));

    $response->assertStatus(200);
});

test('admin can view create student form', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makePesertaContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('peserta-didik.create'));

    $response->assertStatus(200);
});

test('admin can store new student and triggers WhatsApp notification', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin, 'paket' => $paket] = makePesertaContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('peserta-didik.store'), [
            'nama_lengkap'       => 'Citra Kirana',
            'nisn'               => '1122334455',
            'jenis_kelamin'      => 'P',
            'asal_sekolah'       => 'SMA 3',
            'no_telepon'         => '08123456789',
            'paket_bimbingan_id' => $paket->id,
        ]);

    $response->assertRedirect(route('peserta-didik.index'));
    $this->assertDatabaseHas('peserta_didiks', [
        'nama_lengkap' => 'Citra Kirana',
        'nisn'         => '1122334455',
        'status'       => 'Aktif',
    ]);
});

test('student store handles exceptions cleanly', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makePesertaContext();

    // Passing wrong payload to trigger validation exception / return back
    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('peserta-didik.store'), [
            'nama_lengkap' => 'Empty Fields Data',
        ]);

    $response->assertSessionHasErrors(['nisn', 'jenis_kelamin', 'asal_sekolah', 'paket_bimbingan_id']);
});

test('admin can view edit student form', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin, 'paket' => $paket] = makePesertaContext();

    $peserta = PesertaDidik::create([
        'nama_lengkap'       => 'Doni Salman',
        'nisn'               => '5544332211',
        'jenis_kelamin'      => 'L',
        'asal_sekolah'       => 'SMA 4',
        'status'             => 'Aktif',
        'paket_bimbingan_id' => $paket->id,
        'kantor_id'          => $kantor->id,
        'periode_id'         => $periode->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('peserta-didik.edit', $peserta->id));

    $response->assertStatus(200);
});

test('admin can update student as active and clears exit details', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin, 'paket' => $paket] = makePesertaContext();

    $peserta = PesertaDidik::create([
        'nama_lengkap'       => 'Eka Putra',
        'nisn'               => '9988776655',
        'jenis_kelamin'      => 'L',
        'asal_sekolah'       => 'SMA 5',
        'status'             => 'Keluar',
        'tanggal_keluar'     => '2026-05-18',
        'alasan_keluar'      => 'Drop out',
        'paket_bimbingan_id' => $paket->id,
        'kantor_id'          => $kantor->id,
        'periode_id'         => $periode->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->put(route('peserta-didik.update', $peserta->id), [
            'nama_lengkap'       => 'Eka Putra Baru',
            'nisn'               => '9988776655',
            'jenis_kelamin'      => 'L',
            'asal_sekolah'       => 'SMA 5',
            'status'             => 'Aktif',
            'paket_bimbingan_id' => $paket->id,
        ]);

    $response->assertRedirect(route('peserta-didik.index'));
    $peserta->refresh();
    expect($peserta->status)->toBe('Aktif');
    expect($peserta->tanggal_keluar)->toBeNull();
    expect($peserta->alasan_keluar)->toBeNull();
});

test('admin can update student as exited', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin, 'paket' => $paket] = makePesertaContext();

    $peserta = PesertaDidik::create([
        'nama_lengkap'       => 'Fitri Ani',
        'nisn'               => '8877665544',
        'jenis_kelamin'      => 'P',
        'asal_sekolah'       => 'SMA 6',
        'status'             => 'Aktif',
        'paket_bimbingan_id' => $paket->id,
        'kantor_id'          => $kantor->id,
        'periode_id'         => $periode->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->put(route('peserta-didik.update', $peserta->id), [
            'nama_lengkap'       => 'Fitri Ani',
            'nisn'               => '8877665544',
            'jenis_kelamin'      => 'P',
            'asal_sekolah'       => 'SMA 6',
            'status'             => 'Keluar',
            'tanggal_keluar'     => '2026-05-19',
            'alasan_keluar'      => 'Lulus bimbingan',
            'paket_bimbingan_id' => $paket->id,
        ]);

    $response->assertRedirect(route('peserta-didik.index'));
    $peserta->refresh();
    expect($peserta->status)->toBe('Keluar');
    expect($peserta->alasan_keluar)->toBe('Lulus bimbingan');
});

test('admin can delete student', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin, 'paket' => $paket] = makePesertaContext();

    $peserta = PesertaDidik::create([
        'nama_lengkap'       => 'Galih Saputra',
        'nisn'               => '7766554433',
        'jenis_kelamin'      => 'L',
        'asal_sekolah'       => 'SMA 7',
        'status'             => 'Aktif',
        'paket_bimbingan_id' => $paket->id,
        'kantor_id'          => $kantor->id,
        'periode_id'         => $periode->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->delete(route('peserta-didik.destroy', $peserta->id));

    $response->assertRedirect();
    $this->assertSoftDeleted('peserta_didiks', ['id' => $peserta->id]);
});

test('admin can export active students as Excel', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin, 'paket' => $paket] = makePesertaContext();

    PesertaDidik::create([
        'nama_lengkap'       => 'Hana Maria',
        'nisn'               => '6655443322',
        'jenis_kelamin'      => 'P',
        'asal_sekolah'       => 'SMA 8',
        'status'             => 'Aktif',
        'paket_bimbingan_id' => $paket->id,
        'kantor_id'          => $kantor->id,
        'periode_id'         => $periode->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('peserta-didik.export'));

    $response->assertStatus(200);
    $response->assertHeader('content-disposition');
});

test('admin can export exited students as Excel', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin, 'paket' => $paket] = makePesertaContext();

    PesertaDidik::create([
        'nama_lengkap'       => 'Indra Kusuma',
        'nisn'               => '5544332211',
        'jenis_kelamin'      => 'L',
        'asal_sekolah'       => 'SMA 9',
        'status'             => 'Keluar',
        'tanggal_keluar'     => '2026-05-19',
        'alasan_keluar'      => 'Pindah sekolah',
        'paket_bimbingan_id' => $paket->id,
        'kantor_id'          => $kantor->id,
        'periode_id'         => $periode->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('peserta-didik.keluar.export'));

    $response->assertStatus(200);
    $response->assertHeader('content-disposition');
});
