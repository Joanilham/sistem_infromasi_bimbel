<?php

use App\Models\User;

// ── Admin Profile ─────────────────────────────────────────────────────────
test('admin can view their profile edit page', function () {
    $admin = User::factory()->create(['level' => 'Admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->get(route('profile.edit'));
    $response->assertStatus(200);
});

test('admin can update profile info', function () {
    $admin = User::factory()->create([
        'level'     => 'Admin',
        'is_active' => true,
        'name'      => 'Nama Lama',
    ]);

    $response = $this->actingAs($admin)->patch(route('profile.update'), [
        'name'  => 'Nama Baru',
        'email' => $admin->email,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('users', ['id' => $admin->id, 'name' => 'Nama Baru']);
});

test('admin can update password', function () {
    $admin = User::factory()->create([
        'level'     => 'Admin',
        'is_active' => true,
        'password'  => bcrypt('oldpassword'),
    ]);

    $response = $this->actingAs($admin)->patch(route('profile.update'), [
        'current_password'      => 'oldpassword',
        'password'              => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertRedirect();
});

test('admin update profile fails with duplicate email', function () {
    $other = User::factory()->create(['email' => 'taken@example.com']);
    $admin = User::factory()->create(['level' => 'Admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->patch(route('profile.update'), [
        'name'  => $admin->name,
        'email' => 'taken@example.com',
    ]);

    $response->assertSessionHasErrors('email');
});

test('admin update password fails with wrong current password', function () {
    $admin = User::factory()->create([
        'level'    => 'Admin',
        'is_active'=> true,
        'password' => bcrypt('correctpassword'),
    ]);

    $response = $this->actingAs($admin)->patch(route('profile.update'), [
        'current_password'      => 'wrongpassword',
        'password'              => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertSessionHasErrors('current_password');
});

// ── Guru Profile ──────────────────────────────────────────────────────────
test('guru can view their profile edit page', function () {
    $guru = User::factory()->create(['level' => 'Guru', 'is_active' => true]);

    $response = $this->actingAs($guru)->get(route('guru.profile.edit'));
    $response->assertStatus(200);
});

test('guru can update profile info', function () {
    $guru = User::factory()->create([
        'level'     => 'Guru',
        'is_active' => true,
        'name'      => 'Guru Lama',
    ]);

    $response = $this->actingAs($guru)->patch(route('guru.profile.update'), [
        'name'  => 'Guru Baru',
        'email' => $guru->email,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');
});

// ── Admin Profile remove photo ─────────────────────────────────────────────
test('admin can remove profile photo', function () {
    $admin = User::factory()->create([
        'level'     => 'Admin',
        'is_active' => true,
        'photo'     => null,
    ]);

    $response = $this->actingAs($admin)->patch(route('profile.update'), [
        'remove_photo' => '1',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('users', ['id' => $admin->id, 'photo' => null]);
});
