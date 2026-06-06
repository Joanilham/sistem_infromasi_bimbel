<?php

use App\Models\Kantor;
use App\Models\Periode;
use App\Models\User;

// ── selectContext ───────────────────────────────────────────────────────────
test('super admin can view select context page', function () {
    $admin  = User::factory()->create(['level' => 'Super Admin', 'is_active' => true]);
    Kantor::create(['nama_kantor' => 'Kantor A', 'alamat' => 'Kota A']);
    Periode::create(['tahun_periode' => '2024/2025', 'is_active' => true]);

    $response = $this->actingAs($admin)->get(route('konteks.select'));
    $response->assertStatus(200);
});

test('admin can view select context page with limited kantors', function () {
    $kantor = Kantor::create(['nama_kantor' => 'Kantor Cabang', 'alamat' => 'Cabang']);
    $admin  = User::factory()->create([
        'level'     => 'Admin',
        'is_active' => true,
        'kantor_id' => $kantor->id,
    ]);
    Periode::create(['tahun_periode' => '2024/2025', 'is_active' => true]);

    $response = $this->actingAs($admin)->get(route('konteks.select'));
    $response->assertStatus(200);
});

test('guru cannot access select context page', function () {
    $guru = User::factory()->create(['level' => 'Guru', 'is_active' => true]);

    $response = $this->actingAs($guru)->get(route('konteks.select'));
    $response->assertRedirect(route('guru.dashboard'));
});

// ── update (session.konteks) ────────────────────────────────────────────────
test('super admin can update konteks session', function () {
    $admin  = User::factory()->create(['level' => 'Super Admin', 'is_active' => true]);
    $kantor = Kantor::create(['nama_kantor' => 'Kantor X', 'alamat' => 'X']);
    $periode = Periode::create(['tahun_periode' => '2025/2026', 'is_active' => true]);

    $response = $this->actingAs($admin)->post(route('session.konteks'), [
        'kantor_id'  => $kantor->id,
        'periode_id' => $periode->id,
    ]);

    $response->assertStatus(302);
    $this->assertEquals($kantor->id, session('kantor_id'));
    $this->assertEquals($periode->id, session('periode_id'));
});

test('super admin update konteks with redirect param redirects correctly', function () {
    $admin  = User::factory()->create(['level' => 'Super Admin', 'is_active' => true]);
    $kantor  = Kantor::create(['nama_kantor' => 'K1', 'alamat' => 'A1']);
    $periode = Periode::create(['tahun_periode' => '2025/2026', 'is_active' => true]);

    $response = $this->actingAs($admin)->post(route('session.konteks'), [
        'kantor_id'  => $kantor->id,
        'periode_id' => $periode->id,
        'redirect'   => '/dashboard',
    ]);

    $response->assertRedirect('/dashboard');
});

test('admin is forced to use their assigned kantor', function () {
    $kantor = Kantor::create(['nama_kantor' => 'Cabang Y', 'alamat' => 'Y']);
    $admin  = User::factory()->create([
        'level'     => 'Admin',
        'is_active' => true,
        'kantor_id' => $kantor->id,
    ]);
    $periode = Periode::create(['tahun_periode' => '2025/2026', 'is_active' => true]);

    // Even if admin tries to pass a different kantor_id, it should be overridden
    $otherKantor = Kantor::create(['nama_kantor' => 'Other', 'alamat' => 'Other']);

    $response = $this->actingAs($admin)->post(route('session.konteks'), [
        'kantor_id'  => $otherKantor->id,
        'periode_id' => $periode->id,
    ]);

    $response->assertStatus(302);
    $this->assertEquals($kantor->id, session('kantor_id'));
});

test('update konteks clears periode session when not filled', function () {
    $admin  = User::factory()->create(['level' => 'Super Admin', 'is_active' => true]);
    $kantor = Kantor::create(['nama_kantor' => 'K2', 'alamat' => 'A2']);

    // Set initial session value
    session(['periode_id' => 99]);

    $response = $this->actingAs($admin)->post(route('session.konteks'), [
        'kantor_id' => $kantor->id,
    ]);

    $response->assertStatus(302);
    $this->assertNull(session('periode_id'));
});
