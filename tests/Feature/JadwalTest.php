<?php

use App\Models\Jadwal;
use App\Models\JadwalMapel;
use App\Models\Kantor;
use App\Models\KelompokBelajar;
use App\Models\Periode;
use App\Models\User;

function makeJadwalAdminContext(): array
{
    $kantor  = Kantor::create(['nama_kantor' => 'Jadwal Kantor', 'alamat' => 'Jadwal Kota']);
    $periode = Periode::create(['tahun_periode' => '2025/2026', 'is_active' => true]);
    $admin   = User::factory()->create(['level' => 'Admin', 'is_active' => true]);
    $guru    = User::factory()->create(['level' => 'Guru',  'is_active' => true]);
    return compact('kantor', 'periode', 'admin', 'guru');
}

// ─────────────────────────────────────────────────────────────────────────────
// ADMIN JADWAL
// ─────────────────────────────────────────────────────────────────────────────

test('admin can view jadwal index', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeJadwalAdminContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('admin.jadwal.index'));

    $response->assertStatus(200);
});

test('admin can store jadwal', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin, 'guru' => $guru] = makeJadwalAdminContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('admin.jadwal.store'), [
            'guru_id'     => $guru->id,
            'hari'        => 'Senin',
            'jam_mulai'   => '08:00',
            'jam_selesai' => '10:00',
        ]);

    $response->assertRedirect(route('admin.jadwal.index'));
    $this->assertDatabaseHas('jadwals', [
        'guru_id' => $guru->id,
        'hari'    => 'Senin',
    ]);
});

test('store jadwal fails with invalid hari', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin, 'guru' => $guru] = makeJadwalAdminContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('admin.jadwal.store'), [
            'guru_id'     => $guru->id,
            'hari'        => 'Holiday',
            'jam_mulai'   => '08:00',
            'jam_selesai' => '10:00',
        ]);

    $response->assertSessionHasErrors('hari');
});

test('admin can update jadwal', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin, 'guru' => $guru] = makeJadwalAdminContext();

    $jadwal = Jadwal::create([
        'guru_id'     => $guru->id,
        'hari'        => 'Selasa',
        'jam_mulai'   => '09:00',
        'jam_selesai' => '11:00',
        'kantor_id'   => $kantor->id,
        'periode_id'  => $periode->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->put(route('admin.jadwal.update', $jadwal->id), [
            'guru_id'     => $guru->id,
            'hari'        => 'Rabu',
            'jam_mulai'   => '10:00',
            'jam_selesai' => '12:00',
        ]);

    $response->assertRedirect(route('admin.jadwal.index'));
    $this->assertDatabaseHas('jadwals', ['id' => $jadwal->id, 'hari' => 'Rabu']);
});

test('admin can delete jadwal', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin, 'guru' => $guru] = makeJadwalAdminContext();

    $jadwal = Jadwal::create([
        'guru_id'     => $guru->id,
        'hari'        => 'Kamis',
        'jam_mulai'   => '07:00',
        'jam_selesai' => '09:00',
        'kantor_id'   => $kantor->id,
        'periode_id'  => $periode->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->delete(route('admin.jadwal.destroy', $jadwal->id));

    $response->assertRedirect(route('admin.jadwal.index'));
    $this->assertDatabaseMissing('jadwals', ['id' => $jadwal->id]);
});

test('admin can view jadwal konflik endpoint', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeJadwalAdminContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('admin.jadwal.konflik'));

    $response->assertStatus(200);
    $response->assertJsonIsArray();
});

test('admin cannot duplikasi to same periode', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeJadwalAdminContext();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('admin.jadwal.duplikasi'), [
            'target_periode_id' => $periode->id,
        ]);

    $response->assertSessionHas('error');
});

// ─────────────────────────────────────────────────────────────────────────────
// GURU JADWAL
// ─────────────────────────────────────────────────────────────────────────────

test('guru can view their jadwal index', function () {
    $guru = User::factory()->create(['level' => 'Guru', 'is_active' => true]);

    $response = $this->actingAs($guru)->get(route('guru.jadwal.index'));
    $response->assertStatus(200);
});

test('guru can store their jadwal', function () {
    $guru = User::factory()->create(['level' => 'Guru', 'is_active' => true]);

    $response = $this->actingAs($guru)->post(route('guru.jadwal.store'), [
        'hari'        => 'Senin',
        'jam_mulai'   => '08:00',
        'jam_selesai' => '10:00',
        'kelas'       => 'XII IPA',
        'mapel'       => 'Matematika',
    ]);

    $response->assertRedirect(route('guru.jadwal.index'));
    $this->assertDatabaseHas('jadwal_mapels', [
        'guru_id' => $guru->id,
        'kelas'   => 'XII IPA',
    ]);
});

test('guru can update their jadwal', function () {
    $guru   = User::factory()->create(['level' => 'Guru', 'is_active' => true]);
    $jadwal = JadwalMapel::create([
        'guru_id'     => $guru->id,
        'hari'        => 'Selasa',
        'jam_mulai'   => '09:00',
        'jam_selesai' => '11:00',
        'kelas'       => 'XI IPS',
        'mapel'       => 'Fisika',
    ]);

    $response = $this->actingAs($guru)->put(route('guru.jadwal.update', $jadwal->id), [
        'hari'        => 'Rabu',
        'jam_mulai'   => '10:00',
        'jam_selesai' => '12:00',
        'kelas'       => 'XI IPA',
        'mapel'       => 'Kimia',
    ]);

    $response->assertRedirect(route('guru.jadwal.index'));
    $this->assertDatabaseHas('jadwal_mapels', ['id' => $jadwal->id, 'mapel' => 'Kimia']);
});

test('guru can delete their jadwal', function () {
    $guru   = User::factory()->create(['level' => 'Guru', 'is_active' => true]);
    $jadwal = JadwalMapel::create([
        'guru_id'     => $guru->id,
        'hari'        => 'Jumat',
        'jam_mulai'   => '07:00',
        'jam_selesai' => '08:00',
        'kelas'       => 'X',
        'mapel'       => 'B. Indonesia',
    ]);

    $response = $this->actingAs($guru)->delete(route('guru.jadwal.destroy', $jadwal->id));
    $response->assertRedirect(route('guru.jadwal.index'));
    $this->assertDatabaseMissing('jadwal_mapels', ['id' => $jadwal->id]);
});

test('guru cannot modify another guru jadwal', function () {
    $guru1  = User::factory()->create(['level' => 'Guru', 'is_active' => true]);
    $guru2  = User::factory()->create(['level' => 'Guru', 'is_active' => true]);
    $jadwal = JadwalMapel::create([
        'guru_id'     => $guru2->id,
        'hari'        => 'Sabtu',
        'jam_mulai'   => '07:00',
        'jam_selesai' => '08:00',
        'kelas'       => 'IX',
        'mapel'       => 'IPA',
    ]);

    $response = $this->actingAs($guru1)->delete(route('guru.jadwal.destroy', $jadwal->id));
    $response->assertStatus(404);
});

test('admin store and update jadwal warns on konflik', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin, 'guru' => $guru] = makeJadwalAdminContext();

    // Create baseline schedule
    $firstJadwal = Jadwal::create([
        'guru_id'     => $guru->id,
        'hari'        => 'Senin',
        'jam_mulai'   => '08:00',
        'jam_selesai' => '10:00',
        'kantor_id'   => $kantor->id,
        'periode_id'  => $periode->id,
    ]);

    // Store overlapping schedule to trigger store conflict warning
    $responseStore = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('admin.jadwal.store'), [
            'guru_id'     => $guru->id,
            'hari'        => 'Senin',
            'jam_mulai'   => '09:00',
            'jam_selesai' => '11:00',
        ]);

    $responseStore->assertRedirect(route('admin.jadwal.index'));
    $responseStore->assertSessionHas('success', function ($msg) {
        return str_contains($msg, 'Warning: Guru memiliki') && str_contains($msg, 'jadwal lain');
    });

    // Create a non-overlapping second schedule to update later
    $secondJadwal = Jadwal::create([
        'guru_id'     => $guru->id,
        'hari'        => 'Senin',
        'jam_mulai'   => '13:00',
        'jam_selesai' => '15:00',
        'kantor_id'   => $kantor->id,
        'periode_id'  => $periode->id,
    ]);

    // Update second schedule to overlap baseline schedule to trigger update conflict warning
    $responseUpdate = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->put(route('admin.jadwal.update', $secondJadwal->id), [
            'guru_id'     => $guru->id,
            'hari'        => 'Senin',
            'jam_mulai'   => '09:30',
            'jam_selesai' => '11:30',
        ]);

    $responseUpdate->assertRedirect(route('admin.jadwal.index'));
    $responseUpdate->assertSessionHas('success', function ($msg) {
        return str_contains($msg, 'Warning: Guru memiliki') && str_contains($msg, 'jadwal lain');
    });
});

test('admin view jadwal konflik lists actual overlapping schedules', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin, 'guru' => $guru] = makeJadwalAdminContext();

    // Create overlapping schedules
    Jadwal::create([
        'guru_id'     => $guru->id,
        'hari'        => 'Rabu',
        'jam_mulai'   => '08:00',
        'jam_selesai' => '10:00',
        'kantor_id'   => $kantor->id,
        'periode_id'  => $periode->id,
    ]);

    Jadwal::create([
        'guru_id'     => $guru->id,
        'hari'        => 'Rabu',
        'jam_mulai'   => '09:00',
        'jam_selesai' => '11:00',
        'kantor_id'   => $kantor->id,
        'periode_id'  => $periode->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('admin.jadwal.konflik'));

    $response->assertStatus(200);
    $response->assertJsonIsArray();
    $data = $response->json();
    expect(count($data))->toBeGreaterThan(0);
});

test('admin duplikasi schedules target scenarios', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin, 'guru' => $guru] = makeJadwalAdminContext();
    $targetPeriode = Periode::create(['tahun_periode' => '2026/2027', 'is_active' => false]);

    // Scenario A: Duplikasi when current period is empty
    $responseEmpty = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('admin.jadwal.duplikasi'), [
            'target_periode_id' => $targetPeriode->id,
        ]);

    $responseEmpty->assertSessionHas('error', 'Tidak ada jadwal di periode saat ini untuk diduplikasi.');

    // Create schedule in current period
    Jadwal::create([
        'guru_id'     => $guru->id,
        'hari'        => 'Kamis',
        'jam_mulai'   => '08:00',
        'jam_selesai' => '10:00',
        'kantor_id'   => $kantor->id,
        'periode_id'  => $periode->id,
    ]);

    // Scenario B: Successful duplication to target period
    $responseSuccess = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('admin.jadwal.duplikasi'), [
            'target_periode_id' => $targetPeriode->id,
        ]);

    $responseSuccess->assertSessionHas('success', 'Berhasil menduplikasi 1 jadwal ke periode tujuan.');
    $this->assertDatabaseHas('jadwals', [
        'periode_id' => $targetPeriode->id,
        'hari' => 'Kamis',
    ]);
});
