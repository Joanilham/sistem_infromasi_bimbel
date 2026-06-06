<?php

use App\Models\Kantor;
use App\Models\User;

function makeSuperAdmin(): User
{
    return User::factory()->create([
        'level'     => 'Super Admin',
        'is_active' => true,
    ]);
}

// ── Index ───────────────────────────────────────────────────────────────────
test('super admin can view kantor index', function () {
    $admin = makeSuperAdmin();
    Kantor::create(['nama_kantor' => 'Kantor Pusat', 'alamat' => 'Jakarta']);

    $response = $this->actingAs($admin)->get(route('kantor.index'));
    $response->assertStatus(200);
});

test('kantor index supports search filter', function () {
    $admin = makeSuperAdmin();
    Kantor::create(['nama_kantor' => 'Kantor Utama', 'alamat' => 'Bandung']);

    $response = $this->actingAs($admin)->get(route('kantor.index', ['search' => 'Utama']));
    $response->assertStatus(200);
    $response->assertSee('Utama');
});

// ── Store ───────────────────────────────────────────────────────────────────
test('super admin can store kantor', function () {
    $admin = makeSuperAdmin();

    $response = $this->actingAs($admin)->post(route('kantor.store'), [
        'nama_kantor' => 'Kantor Baru',
        'alamat'      => 'Jl. Merdeka No.1',
    ]);

    $response->assertRedirect(route('kantor.index'));
    $this->assertDatabaseHas('kantors', ['nama_kantor' => 'Kantor Baru']);
});

test('store kantor fails without nama_kantor', function () {
    $admin = makeSuperAdmin();

    $response = $this->actingAs($admin)->post(route('kantor.store'), [
        'alamat' => 'Jl. Test',
    ]);

    $response->assertSessionHasErrors('nama_kantor');
});

// ── Update ──────────────────────────────────────────────────────────────────
test('super admin can update kantor', function () {
    $admin  = makeSuperAdmin();
    $kantor = Kantor::create(['nama_kantor' => 'Lama', 'alamat' => 'Old Address']);

    $response = $this->actingAs($admin)->put(route('kantor.update', $kantor->id), [
        'nama_kantor' => 'Baru',
        'alamat'      => 'New Address',
    ]);

    $response->assertRedirect(route('kantor.index'));
    $this->assertDatabaseHas('kantors', ['id' => $kantor->id, 'nama_kantor' => 'Baru']);
});

// ── Destroy ─────────────────────────────────────────────────────────────────
test('cannot delete the last kantor', function () {
    $admin  = makeSuperAdmin();
    $kantor = Kantor::create(['nama_kantor' => 'Satu-satunya', 'alamat' => 'Jakarta']);

    // Delete others if any
    Kantor::whereNot('id', $kantor->id)->forceDelete();

    $response = $this->actingAs($admin)->delete(route('kantor.destroy', $kantor->id));
    $response->assertRedirect(route('kantor.index'));
    $response->assertSessionHas('error');
});

test('super admin can soft delete kantor when multiple exist', function () {
    $admin   = makeSuperAdmin();
    $kantor1 = Kantor::create(['nama_kantor' => 'Kantor A', 'alamat' => 'Kota A']);
    $kantor2 = Kantor::create(['nama_kantor' => 'Kantor B', 'alamat' => 'Kota B']);

    $response = $this->actingAs($admin)->delete(route('kantor.destroy', $kantor1->id));
    $response->assertRedirect(route('kantor.index'));
    $this->assertSoftDeleted('kantors', ['id' => $kantor1->id]);
});

// ── Restore ─────────────────────────────────────────────────────────────────
test('super admin can restore soft deleted kantor', function () {
    $admin  = makeSuperAdmin();
    $kantor = Kantor::create(['nama_kantor' => 'Dipulihkan', 'alamat' => 'Kota X']);
    $kantor->delete();

    $response = $this->actingAs($admin)->patch(route('kantor.restore', $kantor->id));
    $response->assertRedirect(route('kantor.index'));
    $this->assertNotSoftDeleted('kantors', ['id' => $kantor->id]);
});

// ── Access Control ───────────────────────────────────────────────────────────
test('admin (non super admin) cannot access kantor routes', function () {
    $admin = User::factory()->create(['level' => 'Admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->get(route('kantor.index'));
    $response->assertRedirect(route('dashboard'));
});
