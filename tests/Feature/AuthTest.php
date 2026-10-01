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
    $response->assertRedirect(route('login'));
});

test('user cannot login with unregistered email', function () {
    $response = $this->post('/login', [
        'email' => 'unregistered@example.com',
        'password' => 'password123',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

test('rejected applicant receives informative message', function () {
    \App\Models\Pendaftaran\PendaftaranSiswa::create([
        'email' => 'rejected@example.com',
        'password' => Hash::make('password123'),
        'nama_lengkap' => 'Calon Siswa',
        'asal_sekolah' => 'SMK Negeri 1',
        'jenis_kelamin' => 'L',
        'status' => 'ditolak',
        'catatan_admin' => 'Berkas pendaftaran tidak memenuhi syarat.',
    ]);

    $response = $this->post('/login', [
        'email' => 'rejected@example.com',
        'password' => 'password123',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

test('admin is redirected to select context page', function () {
    $admin = User::factory()->create([
        'username' => 'admin_test',
        'email' => 'admin_test@example.com',
        'password' => Hash::make('password123'),
        'level' => 'Admin',
        'is_active' => true,
    ]);

    $response = $this->post('/login', [
        'email' => 'admin_test@example.com',
        'password' => 'password123',
    ]);

    $this->assertAuthenticatedAs($admin);
    $response->assertRedirect(route('konteks.select'));
});

test('guru is redirected to guru dashboard', function () {
    $guru = User::factory()->create([
        'username' => 'guru_test',
        'email' => 'guru_test@example.com',
        'password' => Hash::make('password123'),
        'level' => 'Guru',
        'is_active' => true,
    ]);

    $response = $this->post('/login', [
        'email' => 'guru_test@example.com',
        'password' => 'password123',
    ]);

    $this->assertAuthenticatedAs($guru);
    $response->assertRedirect(route('guru.dashboard'));
});

test('siswa is redirected to siswa dashboard', function () {
    $siswa = User::factory()->create([
        'username' => 'siswa_test',
        'email' => 'siswa_test@example.com',
        'password' => Hash::make('password123'),
        'level' => 'Siswa',
        'is_active' => true,
    ]);

    $response = $this->post('/login', [
        'email' => 'siswa_test@example.com',
        'password' => 'password123',
    ]);

    $this->assertAuthenticatedAs($siswa);
    $response->assertRedirect(route('siswa.dashboard'));
});

test('rate limiter blocks on 6th failed attempt', function () {
    for ($i = 0; $i < 5; $i++) {
        $this->post('/login', [
            'email' => 'rate_limit@example.com',
            'password' => 'wrongpass',
        ]);
    }

    $response = $this->post('/login', [
        'email' => 'rate_limit@example.com',
        'password' => 'wrongpass',
    ]);

    $response->assertStatus(429);
});
