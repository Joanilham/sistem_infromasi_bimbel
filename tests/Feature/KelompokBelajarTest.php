<?php

use App\Models\Kantor;
use App\Models\KelompokBelajar;
use App\Models\Periode;
use App\Models\User;

function makeAdminWithContext(): array
{
    $kantor  = Kantor::create(['nama_kantor' => 'KBKantor', 'alamat' => 'Kota KB']);
    $periode = Periode::create(['tahun_periode' => '2025/2026', 'is_active' => true]);
    $admin   = User::factory()->create(['level' => 'Admin', 'is_active' => true]);

    return compact('kantor', 'periode', 'admin');
}

function withKBContext(User $admin, int $kantorId, int $periodeId): void
{
    session(['kantor_id' => $kantorId, 'periode_id' => $periodeId]);
}

test('admin can view kelompok belajar index', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeAdminWithContext();
    withKBContext($admin, $kantor->id, $periode->id);

    $response = $this->actingAs($admin)->get(route('kelompok-belajar.index'));
    $response->assertStatus(200);

    // Call with search, perPage, sort, order filters to cover lines 17 and 25
    $responseFilter = $this->actingAs($admin)->get(route('kelompok-belajar.index', [
        'search' => 'Alpha',
        'per_page' => 25,
        'sort' => 'nama_kelompok',
        'order' => 'asc'
    ]));
    $responseFilter->assertStatus(200);
});

// ── Store ──────────────────────────────────────────────────────────────────
test('admin can store kelompok belajar', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeAdminWithContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('kelompok-belajar.store'), [
            'nama_kelompok' => 'Kelas Alpha',
        ]);

    $response->assertRedirect(route('kelompok-belajar.index'));
    $this->assertDatabaseHas('kelompok_belajars', ['nama_kelompok' => 'Kelas Alpha']);
});

test('store kelompok belajar fails without nama_kelompok', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeAdminWithContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('kelompok-belajar.store'), []);

    $response->assertSessionHasErrors('nama_kelompok');
});

// ── Update ─────────────────────────────────────────────────────────────────
test('admin can update kelompok belajar', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeAdminWithContext();

    $kelompok = KelompokBelajar::create([
        'nama_kelompok' => 'Kelas Lama',
        'kantor_id'     => $kantor->id,
        'periode_id'    => $periode->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->put(route('kelompok-belajar.update', $kelompok), [
            'nama_kelompok' => 'Kelas Baru',
        ]);

    $response->assertRedirect(route('kelompok-belajar.index'));
    $this->assertDatabaseHas('kelompok_belajars', ['id' => $kelompok->id, 'nama_kelompok' => 'Kelas Baru']);
});

// ── Destroy ────────────────────────────────────────────────────────────────
test('admin can delete kelompok belajar', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeAdminWithContext();

    $kelompok = KelompokBelajar::create([
        'nama_kelompok' => 'Kelas Hapus',
        'kantor_id'     => $kantor->id,
        'periode_id'    => $periode->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->delete(route('kelompok-belajar.destroy', $kelompok));

    $response->assertRedirect(route('kelompok-belajar.index'));
    $this->assertDatabaseMissing('kelompok_belajars', ['id' => $kelompok->id]);
});
