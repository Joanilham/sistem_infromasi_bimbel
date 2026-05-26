<?php

use App\Models\Kantor;
use App\Models\PaketBimbingan;
use App\Models\Periode;
use App\Models\User;

function makeContextForPaket(): array
{
    $kantor  = Kantor::create(['nama_kantor' => 'Kantor Paket', 'alamat' => 'Kota P']);
    $periode = Periode::create(['tahun_periode' => '2025/2026', 'is_active' => true]);
    $admin   = User::factory()->create(['level' => 'Super Admin', 'is_active' => true]);
    return compact('kantor', 'periode', 'admin');
}

// ── Index ──────────────────────────────────────────────────────────────────
test('admin can view paket bimbingan index', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeContextForPaket();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('paket-bimbingan.index'));

    $response->assertStatus(200);
});

// ── Create form ─────────────────────────────────────────────────────────────
test('admin can view paket bimbingan create form', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeContextForPaket();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('paket-bimbingan.create'));

    $response->assertStatus(200);
});

// ── Store ──────────────────────────────────────────────────────────────────
test('admin can store paket bimbingan', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeContextForPaket();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('paket-bimbingan.store'), [
            'nama_paket'  => 'Paket Gold',
            'nominal'     => '500000',
            'harga_coret' => '700000',
            'deskripsi'   => 'Paket terbaik',
            'urutan'      => 1,
        ]);

    $response->assertRedirect(route('paket-bimbingan.index'));
    $this->assertDatabaseHas('paket_bimbingans', ['nama_paket' => 'Paket Gold']);
});

test('store paket bimbingan fails without required fields', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeContextForPaket();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('paket-bimbingan.store'), [
            'deskripsi' => 'Tidak ada nama',
        ]);

    $response->assertSessionHasErrors(['nama_paket', 'nominal']);
});

// ── Edit form ───────────────────────────────────────────────────────────────
test('admin can view paket bimbingan edit form', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeContextForPaket();

    $paket = PaketBimbingan::create([
        'kantor_id'  => $kantor->id,
        'periode_id' => $periode->id,
        'nama_paket' => 'Paket Silver',
        'nominal'    => 300000,
        'urutan'     => 2,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('paket-bimbingan.edit', $paket));

    $response->assertStatus(200);
});

// ── Update ─────────────────────────────────────────────────────────────────
test('admin can update paket bimbingan', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeContextForPaket();

    $paket = PaketBimbingan::create([
        'kantor_id'  => $kantor->id,
        'periode_id' => $periode->id,
        'nama_paket' => 'Paket Lama',
        'nominal'    => 200000,
        'urutan'     => 3,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->put(route('paket-bimbingan.update', $paket), [
            'nama_paket' => 'Paket Baru',
            'nominal'    => '250000',
            'urutan'     => 3,
        ]);

    $response->assertRedirect(route('paket-bimbingan.index'));
    $this->assertDatabaseHas('paket_bimbingans', ['id' => $paket->id, 'nama_paket' => 'Paket Baru']);
});

// ── Destroy ────────────────────────────────────────────────────────────────
test('admin can delete paket bimbingan', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeContextForPaket();

    $paket = PaketBimbingan::create([
        'kantor_id'  => $kantor->id,
        'periode_id' => $periode->id,
        'nama_paket' => 'Paket Hapus',
        'nominal'    => 100000,
        'urutan'     => 5,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->delete(route('paket-bimbingan.destroy', $paket));

    $response->assertRedirect(route('paket-bimbingan.index'));
    $this->assertDatabaseMissing('paket_bimbingans', ['id' => $paket->id]);
});
