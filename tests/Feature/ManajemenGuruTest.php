<?php

use App\Models\Kantor;
use App\Models\Periode;
use App\Models\User;

function makeAdminGuruContext(): array
{
    $kantor  = Kantor::create(['nama_kantor' => 'Guru Kantor', 'alamat' => 'G Kota']);
    $periode = Periode::create(['tahun_periode' => '2025/2026', 'is_active' => true]);
    $admin   = User::factory()->create(['level' => 'Admin', 'is_active' => true]);
    return compact('kantor', 'periode', 'admin');
}

// ── Index ──────────────────────────────────────────────────────────────────
test('admin can view manajemen guru index', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeAdminGuruContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('manajemen-guru.index'));

    $response->assertStatus(200);
});

test('admin can view guru keluar list', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeAdminGuruContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('manajemen-guru.keluar'));

    $response->assertStatus(200);
});

// ── Create ─────────────────────────────────────────────────────────────────
test('admin can view guru create form', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeAdminGuruContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('manajemen-guru.create'));

    $response->assertStatus(200);
});

// ── Store ──────────────────────────────────────────────────────────────────
test('admin can store guru', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeAdminGuruContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('manajemen-guru.store'), [
            'name'                  => 'Guru Baru',
            'email'                 => 'gurubaru@school.com',
            'matapelajaran'         => 'Matematika',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

    $response->assertRedirect(route('manajemen-guru.index'));
    $this->assertDatabaseHas('users', ['email' => 'gurubaru@school.com', 'level' => 'Guru']);
});

test('store guru fails with duplicate email', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeAdminGuruContext();
    User::factory()->create(['email' => 'taken_guru@school.com']);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('manajemen-guru.store'), [
            'name'                  => 'Test',
            'email'                 => 'taken_guru@school.com',
            'matapelajaran'         => 'Fisika',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

    $response->assertSessionHasErrors('email');
});

test('store guru fails without matapelajaran', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeAdminGuruContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('manajemen-guru.store'), [
            'name'                  => 'Guru Tanpa Mapel',
            'email'                 => 'gurutanpamapel@school.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

    $response->assertSessionHasErrors('matapelajaran');
});

// ── Edit ───────────────────────────────────────────────────────────────────
test('admin can view guru edit form', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeAdminGuruContext();

    $guru = User::factory()->create([
        'level'     => 'Guru',
        'status'    => 'Aktif',
        'is_active' => true,
        'matapelajaran' => 'IPA',
        'kantor_id' => $kantor->id,
        'periode_id' => $periode->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('manajemen-guru.edit', $guru->id));

    $response->assertStatus(200);
});

// ── Update ─────────────────────────────────────────────────────────────────
test('admin can update guru', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeAdminGuruContext();

    $guru = User::factory()->create([
        'level'         => 'Guru',
        'status'        => 'Aktif',
        'is_active'     => true,
        'matapelajaran' => 'IPA',
        'kantor_id'     => $kantor->id,
        'periode_id'    => $periode->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->put(route('manajemen-guru.update', $guru->id), [
            'name'          => 'Guru Updated',
            'email'         => $guru->email,
            'matapelajaran' => 'Kimia',
            'status'        => 'Aktif',
        ]);

    $response->assertRedirect(route('manajemen-guru.index'));
    $this->assertDatabaseHas('users', ['id' => $guru->id, 'matapelajaran' => 'Kimia']);
});

// ── Destroy ────────────────────────────────────────────────────────────────
test('admin can delete guru', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeAdminGuruContext();

    $guru = User::factory()->create([
        'level'         => 'Guru',
        'status'        => 'Aktif',
        'is_active'     => true,
        'kantor_id'     => $kantor->id,
        'periode_id'    => $periode->id,
        'matapelajaran' => 'IPS',
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->delete(route('manajemen-guru.destroy', $guru->id));

    $response->assertStatus(302);
    $this->assertDatabaseMissing('users', ['id' => $guru->id]);
});

// ── Export ─────────────────────────────────────────────────────────────────
test('admin can export guru aktif', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeAdminGuruContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('manajemen-guru.export'));

    $response->assertStatus(200);
    $this->assertStringContainsString('application/vnd.ms-excel', $response->headers->get('Content-Type'));
});

test('admin can export guru keluar', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeAdminGuruContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('manajemen-guru.keluar.export'));

    $response->assertStatus(200);
    $this->assertStringContainsString('application/vnd.ms-excel', $response->headers->get('Content-Type'));
});
