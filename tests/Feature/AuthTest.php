<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    //
});

test('guest can view login page', function () {
    $response = $this->get('/login');
    $response->assertStatus(200);
});

test('user can login with correct credentials', function () {
    $user = User::factory()->create([
        'username' => 'superadmin',
        'email' => 'superadmin@example.com',
        'password' => Hash::make('password123'),
        'level' => 'Super Admin',
        'is_active' => true,
    ]);

    $response = $this->post('/login', [
        'email' => 'superadmin@example.com',
        'password' => 'password123',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect();
});

test('user cannot login with incorrect password', function () {
    $user = User::factory()->create([
        'username' => 'admin',
        'email' => 'admin@example.com',
        'password' => Hash::make('password123'),
        'is_active' => true,
    ]);

    $response = $this->post('/login', [
        'email' => 'admin@example.com',
        'password' => 'wrongpassword',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors();
});

test('inactive user cannot login', function () {
    $user = User::factory()->create([
        'username' => 'guru1',
        'email' => 'guru@example.com',
        'password' => Hash::make('password123'),
        'is_active' => false,
    ]);

    $response = $this->post('/login', [
        'email' => 'guru@example.com',
        'password' => 'password123',
    ]);

    $this->assertGuest();
});

test('user can logout', function () {
    $user = User::factory()->create([
        'is_active' => true,
    ]);

    $this->actingAs($user);
    $this->assertAuthenticatedAs($user);

    $response = $this->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
