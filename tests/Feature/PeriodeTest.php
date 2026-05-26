<?php

use App\Models\Periode;
use App\Models\User;

function makeSuperAdminForPeriode(): User
{
    return User::factory()->create([
        'level'     => 'Super Admin',
        'is_active' => true,
    ]);
}

// ── Index ───────────────────────────────────────────────────────────────────
test('super admin can view periode index', function () {
    $admin = makeSuperAdminForPeriode();
    Periode::create(['tahun_periode' => '2024/2025', 'is_active' => true]);

    $response = $this->actingAs($admin)->get(route('periode.index'));
    $response->assertStatus(200);
});

test('periode index supports search filter', function () {
    $admin = makeSuperAdminForPeriode();
    Periode::create(['tahun_periode' => '2025/2026', 'is_active' => true]);

    $response = $this->actingAs($admin)->get(route('periode.index', ['search' => '2025']));
    $response->assertStatus(200);
    $response->assertSee('2025/2026');
});

// ── Store ───────────────────────────────────────────────────────────────────
test('super admin can store periode with valid format', function () {
    $admin = makeSuperAdminForPeriode();

    $response = $this->actingAs($admin)->post(route('periode.store'), [
        'tahun_periode' => '2026/2027',
        'is_active'     => 'on',
    ]);

    $response->assertRedirect(route('periode.index'));
    $this->assertDatabaseHas('periodes', ['tahun_periode' => '2026/2027']);
});

test('store periode fails with invalid format', function () {
    $admin = makeSuperAdminForPeriode();

    $response = $this->actingAs($admin)->post(route('periode.store'), [
        'tahun_periode' => '2026-2027',
    ]);

    $response->assertSessionHasErrors('tahun_periode');
});

test('store periode without is_active creates inactive periode', function () {
    $admin = makeSuperAdminForPeriode();

    $response = $this->actingAs($admin)->post(route('periode.store'), [
        'tahun_periode' => '2027/2028',
    ]);

    $response->assertRedirect(route('periode.index'));
    $this->assertDatabaseHas('periodes', ['tahun_periode' => '2027/2028', 'is_active' => 0]);
});

// ── Update ──────────────────────────────────────────────────────────────────
test('super admin can update periode', function () {
    $admin   = makeSuperAdminForPeriode();
    $periode = Periode::create(['tahun_periode' => '2020/2021', 'is_active' => false]);

    $response = $this->actingAs($admin)->put(route('periode.update', $periode->id), [
        'tahun_periode' => '2021/2022',
        'is_active'     => 'on',
    ]);

    $response->assertRedirect(route('periode.index'));
    $this->assertDatabaseHas('periodes', ['id' => $periode->id, 'tahun_periode' => '2021/2022']);
});

// ── Destroy ─────────────────────────────────────────────────────────────────
test('cannot delete the last periode', function () {
    $admin   = makeSuperAdminForPeriode();
    $periode = Periode::create(['tahun_periode' => '2024/2025', 'is_active' => true]);

    Periode::whereNot('id', $periode->id)->forceDelete();

    $response = $this->actingAs($admin)->delete(route('periode.destroy', $periode->id));
    $response->assertRedirect(route('periode.index'));
    $response->assertSessionHas('error');
});

test('super admin can soft delete periode when multiple exist', function () {
    $admin    = makeSuperAdminForPeriode();
    $periode1 = Periode::create(['tahun_periode' => '2022/2023', 'is_active' => false]);
    Periode::create(['tahun_periode' => '2023/2024', 'is_active' => true]);

    $response = $this->actingAs($admin)->delete(route('periode.destroy', $periode1->id));
    $response->assertRedirect(route('periode.index'));
    $this->assertSoftDeleted('periodes', ['id' => $periode1->id]);
});

// ── Restore ─────────────────────────────────────────────────────────────────
test('super admin can restore soft deleted periode', function () {
    $admin   = makeSuperAdminForPeriode();
    $periode = Periode::create(['tahun_periode' => '2019/2020', 'is_active' => false]);
    $periode->delete();

    $response = $this->actingAs($admin)->patch(route('periode.restore', $periode->id));
    $response->assertRedirect(route('periode.index'));
    $this->assertNotSoftDeleted('periodes', ['id' => $periode->id]);
});

// ── Access Control ───────────────────────────────────────────────────────────
test('non super admin cannot access periode index', function () {
    $admin = User::factory()->create(['level' => 'Admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->get(route('periode.index'));
    $response->assertRedirect(route('dashboard'));
});
