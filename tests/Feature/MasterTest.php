<?php

use App\Models\Master;
use App\Models\User;

// ── Index ──────────────────────────────────────────────────────────────────
test('super admin can view master index', function () {
    $admin = User::factory()->create(['level' => 'Super Admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->get(route('master.index'));
    $response->assertStatus(200);
});

test('master index shows existing master data', function () {
    $admin = User::factory()->create(['level' => 'Super Admin', 'is_active' => true]);
    Master::create(['nama_lembaga' => 'Bimbel Cerdas', 'alamat_lembaga' => 'Jl. Ilmu No.1']);

    $response = $this->actingAs($admin)->get(route('master.index'));
    $response->assertStatus(200);
    $response->assertSee('Bimbel Cerdas');
});

test('non super admin cannot access master', function () {
    $admin = User::factory()->create(['level' => 'Admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->get(route('master.index'));
    $response->assertRedirect(route('dashboard'));
});

// ── Update ─────────────────────────────────────────────────────────────────
test('super admin can update master data', function () {
    $admin = User::factory()->create(['level' => 'Super Admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->put(route('master.update'), [
        'nama_lembaga'   => 'Lembaga Baru',
        'alamat_lembaga' => 'Jl. Baru No.10',
        'wa_url'         => 'https://wa.me/628123456789',
    ]);

    $response->assertRedirect(route('master.index'));
    $this->assertDatabaseHas('masters', ['nama_lembaga' => 'Lembaga Baru']);
});

test('update master fails with invalid wa_url', function () {
    $admin = User::factory()->create(['level' => 'Super Admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->put(route('master.update'), [
        'wa_url' => 'bukan-url-valid',
    ]);

    $response->assertSessionHasErrors('wa_url');
});

test('update master with existing record updates it', function () {
    $admin  = User::factory()->create(['level' => 'Super Admin', 'is_active' => true]);
    $master = Master::create(['nama_lembaga' => 'Lama', 'alamat_lembaga' => 'Jl. Lama']);

    $response = $this->actingAs($admin)->put(route('master.update'), [
        'nama_lembaga' => 'Update Baru',
    ]);

    $response->assertRedirect(route('master.index'));
    $this->assertDatabaseHas('masters', ['nama_lembaga' => 'Update Baru']);
});
