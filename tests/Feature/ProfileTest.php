<?php

use App\Models\User;

// ── Admin Profile ─────────────────────────────────────────────────────────

// Skenario: Admin melihat halaman formulir edit profil
test('admin can view their profile edit page', function () {
    // [Arrange] Siapkan user dengan level Admin dan status aktif
    $admin = User::factory()->create(['level' => 'Admin', 'is_active' => true]);

    // [Act] Admin mengakses route profile.edit
    $response = $this->actingAs($admin)->get(route('profile.edit'));

    // [Assert] Respon sukses (HTTP 200) memuat tampilan form profil
    $response->assertStatus(200);
});

// Skenario: Admin memperbarui data informasi profil (Nama & Email)
test('admin can update profile info', function () {
    // [Arrange] Siapkan user Admin dengan data nama awal
    $admin = User::factory()->create([
        'level'     => 'Admin',
        'is_active' => true,
        'name'      => 'Nama Lama',
    ]);

    // [Act] Mengirim HTTP PATCH ke endpoint profile.update dengan nama baru
    $response = $this->actingAs($admin)->patch(route('profile.update'), [
        'name'  => 'Nama Baru',
        'email' => $admin->email,
    ]);

    // [Assert] Redirect berhasil dan perubahan nama tersimpan di tabel database users
    $response->assertRedirect();
    $this->assertDatabaseHas('users', ['id' => $admin->id, 'name' => 'Nama Baru']);
});

// Skenario: Admin memperbarui password akun dengan verifikasi password lama yang benar (Happy Path)
test('admin can update password', function () {
    // [Arrange] Siapkan user Admin dengan password lama terenkripsi
    $admin = User::factory()->create([
        'level'     => 'Admin',
        'is_active' => true,
        'password'  => bcrypt('oldpassword'),
    ]);

    // [Act] Mengirim permintaan ganti password dengan current_password yang sesuai dan password_confirmation
    $response = $this->actingAs($admin)->patch(route('profile.update'), [
        'current_password'      => 'oldpassword',
        'password'              => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    // [Assert] Redirect berhasil (password terverifikasi dan berhasil diperbarui)
    $response->assertRedirect();
});

// Skenario: Validasi penolakan jika email baru sudah terdaftar pada akun lain (Negative Test)
test('admin update profile fails with duplicate email', function () {
    // [Arrange] Siapkan akun pengguna lain dengan email tertentu, dan siapkan akun Admin
    $other = User::factory()->create(['email' => 'taken@example.com']);
    $admin = User::factory()->create(['level' => 'Admin', 'is_active' => true]);

    // [Act] Admin mencoba memperbarui email profilnya menggunakan email yang sudah digunakan pengguna lain
    $response = $this->actingAs($admin)->patch(route('profile.update'), [
        'name'  => $admin->name,
        'email' => 'taken@example.com',
    ]);

    // [Assert] Sistem menolak pembaruan dan memicu error validasi sesi pada kolom 'email'
    $response->assertSessionHasErrors('email');
});

// Skenario: Validasi keamanan jika password lama yang dimasukkan keliru (Security / Negative Test)
test('admin update password fails with wrong current password', function () {
    // [Arrange] Siapkan user Admin dengan password asli
    $admin = User::factory()->create([
        'level'    => 'Admin',
        'is_active'=> true,
        'password' => bcrypt('correctpassword'),
    ]);

    // [Act] Mengirim permintaan ganti password namun menyertakan current_password yang salah
    $response = $this->actingAs($admin)->patch(route('profile.update'), [
        'current_password'      => 'wrongpassword',
        'password'              => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    // [Assert] Sistem menolak dan memicu error validasi sesi pada kolom 'current_password'
    $response->assertSessionHasErrors('current_password');
});

// ── Guru Profile ──────────────────────────────────────────────────────────

// Skenario: Guru melihat halaman formulir edit profil (Role-Based Access)
test('guru can view their profile edit page', function () {
    // [Arrange] Siapkan akun dengan level Guru dan status aktif
    $guru = User::factory()->create(['level' => 'Guru', 'is_active' => true]);

    // [Act] Guru mengakses route khusus profil guru
    $response = $this->actingAs($guru)->get(route('guru.profile.edit'));

    // [Assert] Respon sukses (HTTP 200) memuat tampilan form profil guru
    $response->assertStatus(200);
});

// Skenario: Guru memperbarui informasi data diri profil
test('guru can update profile info', function () {
    // [Arrange] Siapkan akun Guru dengan data nama awal
    $guru = User::factory()->create([
        'level'     => 'Guru',
        'is_active' => true,
        'name'      => 'Guru Lama',
    ]);

    // [Act] Guru mengirim HTTP PATCH ke endpoint guru.profile.update dengan data baru
    $response = $this->actingAs($guru)->patch(route('guru.profile.update'), [
        'name'  => 'Guru Baru',
        'email' => $guru->email,
    ]);

    // [Assert] Redirect berhasil dan sesi menerima flash message sukses
    $response->assertRedirect();
    $response->assertSessionHas('success');
});

// ── Admin Profile remove photo ─────────────────────────────────────────────

// Skenario: Menghapus foto profil pengguna
test('admin can remove profile photo', function () {
    // [Arrange] Siapkan user Admin
    $admin = User::factory()->create([
        'level'     => 'Admin',
        'is_active' => true,
        'photo'     => null,
    ]);

    // [Act] Mengirim permintaan update profil dengan opsi remove_photo = '1'
    $response = $this->actingAs($admin)->patch(route('profile.update'), [
        'remove_photo' => '1',
    ]);

    // [Assert] Redirect sukses dan kolom 'photo' pada tabel users dipastikan bernilai null
    $response->assertRedirect();
    $this->assertDatabaseHas('users', ['id' => $admin->id, 'photo' => null]);
});
