<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

function makeSuperAdminPengguna(): User
{
    return User::factory()->create([
        'level'     => 'Super Admin',
        'is_active' => true,
    ]);
}

// ── Index ──────────────────────────────────────────────────────────────────
test('super admin can view pengguna index', function () {
    $admin = makeSuperAdminPengguna();

    $response = $this->actingAs($admin)->get(route('pengguna.index'));
    $response->assertStatus(200);
});

test('pengguna index supports search filter', function () {
    $admin = makeSuperAdminPengguna();
    User::factory()->create(['name' => 'Budi Admin', 'level' => 'Admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->get(route('pengguna.index', ['search' => 'Budi']));
    $response->assertStatus(200);
    $response->assertSee('Budi Admin');
});

test('non super admin cannot access pengguna index', function () {
    $admin = User::factory()->create(['level' => 'Admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->get(route('pengguna.index'));
    $response->assertRedirect(route('dashboard'));
});

// ── Store ──────────────────────────────────────────────────────────────────
test('super admin can store new pengguna', function () {
    $admin = makeSuperAdminPengguna();

    $response = $this->actingAs($admin)->post(route('pengguna.store'), [
        'name'                  => 'Admin Baru',
        'username'              => 'adminbaru',
        'email'                 => 'adminbaru@example.com',
        'level'                 => 'Admin',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('pengguna.index'));
    $this->assertDatabaseHas('users', ['email' => 'adminbaru@example.com']);
});

test('store pengguna fails with duplicate email', function () {
    $admin = makeSuperAdminPengguna();
    User::factory()->create(['email' => 'existing@example.com']);

    $response = $this->actingAs($admin)->post(route('pengguna.store'), [
        'name'                  => 'Duplikat',
        'email'                 => 'existing@example.com',
        'level'                 => 'Admin',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors('email');
});

test('store pengguna fails with mismatched password', function () {
    $admin = makeSuperAdminPengguna();

    $response = $this->actingAs($admin)->post(route('pengguna.store'), [
        'name'                  => 'Test User',
        'email'                 => 'testuser@example.com',
        'level'                 => 'Admin',
        'password'              => 'password123',
        'password_confirmation' => 'different456',
    ]);

    $response->assertSessionHasErrors('password');
});

test('store pengguna fails with invalid level', function () {
    $admin = makeSuperAdminPengguna();

    $response = $this->actingAs($admin)->post(route('pengguna.store'), [
        'name'                  => 'Guru Baru',
        'email'                 => 'guru@example.com',
        'level'                 => 'Guru',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors('level');
});

// ── Update ─────────────────────────────────────────────────────────────────
test('super admin can update other user', function () {
    $admin  = makeSuperAdminPengguna();
    $target = User::factory()->create(['level' => 'Admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->put(route('pengguna.update', $target->id), [
        'name'     => 'Updated Name',
        'email'    => $target->email,
        'level'    => 'Admin',
        'is_active' => 1,
    ]);

    $response->assertRedirect(route('pengguna.index'));
    $this->assertDatabaseHas('users', ['id' => $target->id, 'name' => 'Updated Name']);
});

test('user cannot deactivate their own account via update', function () {
    $admin = makeSuperAdminPengguna();

    $response = $this->actingAs($admin)->put(route('pengguna.update', $admin->id), [
        'name'      => $admin->name,
        'email'     => $admin->email,
        'level'     => 'Super Admin',
        'is_active' => 0,
    ]);

    $response->assertSessionHasErrors('error');
});

test('user cannot change their own level via update', function () {
    $admin = makeSuperAdminPengguna();

    $response = $this->actingAs($admin)->put(route('pengguna.update', $admin->id), [
        'name'      => $admin->name,
        'email'     => $admin->email,
        'level'     => 'Admin',
        'is_active' => 1,
    ]);

    $response->assertSessionHasErrors('error');
});

// ── Toggle Active ───────────────────────────────────────────────────────────
test('super admin can toggle active status of other user', function () {
    $admin  = makeSuperAdminPengguna();
    $target = User::factory()->create(['level' => 'Admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->patch(route('pengguna.toggle-active', $target->id));
    $response->assertRedirect(route('pengguna.index'));

    $this->assertDatabaseHas('users', ['id' => $target->id, 'is_active' => false]);
});

test('super admin cannot toggle their own active status', function () {
    $admin = makeSuperAdminPengguna();

    $response = $this->actingAs($admin)->patch(route('pengguna.toggle-active', $admin->id));
    $response->assertSessionHasErrors('error');
});

// ── Destroy ─────────────────────────────────────────────────────────────────
test('super admin can delete other user', function () {
    $admin  = makeSuperAdminPengguna();
    $target = User::factory()->create(['level' => 'Admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->delete(route('pengguna.destroy', $target->id));
    $response->assertRedirect(route('pengguna.index'));
    $this->assertDatabaseMissing('users', ['id' => $target->id]);
});

test('super admin cannot delete their own account', function () {
    $admin = makeSuperAdminPengguna();

    $response = $this->actingAs($admin)->delete(route('pengguna.destroy', $admin->id));
    $response->assertSessionHasErrors('error');
});
