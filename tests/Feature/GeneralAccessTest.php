<?php

use App\Models\User;

// ── Guest Access ─────────────────────────────────────────────────────────────
test('welcome page is accessible to guests', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

test('guest is redirected from protected routes to login', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

// ── Notification ─────────────────────────────────────────────────────────────
test('admin can view notifikasi page', function () {
    $admin = User::factory()->create(['level' => 'Admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->get(route('notifikasi.index'));
    $response->assertStatus(200);
});

test('guru cannot access notifikasi (admin area)', function () {
    $guru = User::factory()->create(['level' => 'Guru', 'is_active' => true]);

    $response = $this->actingAs($guru)->get(route('notifikasi.index'));
    $response->assertRedirect(); // EnsureCorrectRole redirects, does not 403
});

// ── Password Reset Page ───────────────────────────────────────────────────────
test('guest can view forgot password page', function () {
    $response = $this->get(route('password.request'));
    $response->assertStatus(200);
});

test('authenticated user is redirected from forgot-password page', function () {
    $user = User::factory()->create(['is_active' => true]);

    $response = $this->actingAs($user)->get(route('password.request'));
    // Guest middleware redirects authenticated users
    $response->assertStatus(302);
});

// ── Paket Detail Page (Public) ────────────────────────────────────────────────
test('guest can view public landing page', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

// ── Role Middleware ───────────────────────────────────────────────────────────
test('siswa cannot access admin dashboard', function () {
    $siswa = User::factory()->create(['level' => 'Siswa', 'is_active' => true]);

    $response = $this->actingAs($siswa)->get('/dashboard');
    $response->assertRedirect('/siswa/dashboard');
});

test('siswa cannot access guru routes', function () {
    $siswa = User::factory()->create(['level' => 'Siswa', 'is_active' => true]);

    $response = $this->actingAs($siswa)->get('/guru/dashboard');
    $response->assertRedirect(); // redirected to siswa dashboard
});

test('guru cannot access siswa routes', function () {
    $guru = User::factory()->create(['level' => 'Guru', 'is_active' => true]);

    $response = $this->actingAs($guru)->get('/siswa/dashboard');
    $response->assertRedirect(); // redirected to guru dashboard
});

test('admin cannot access siswa dashboard', function () {
    $admin = User::factory()->create(['level' => 'Admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->get('/siswa/dashboard');
    $response->assertRedirect(); // redirected to admin dashboard
});

test('unauthenticated user cannot access any protected page', function () {
    $pages = [
        '/dashboard',
        '/guru/dashboard',
        '/profile',
    ];

    foreach ($pages as $page) {
        $this->get($page)->assertRedirect('/login');
    }
});
