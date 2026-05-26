<?php

use App\Models\CbtBab;
use App\Models\CbtBankSoal;
use App\Models\CbtMapel;
use App\Models\CbtOpsiJawaban;
use App\Models\User;

function makeGuruUser(): User
{
    return User::factory()->create(['level' => 'Guru', 'is_active' => true]);
}

// ── Index ──────────────────────────────────────────────────────────────────
test('guru can view bank soal index', function () {
    $guru = makeGuruUser();

    $response = $this->actingAs($guru)->get(route('guru.bank-soal.index'));
    $response->assertStatus(200);
});

test('guru bank soal index supports search', function () {
    $guru = makeGuruUser();

    $response = $this->actingAs($guru)->get(route('guru.bank-soal.index', [
        'search' => 'matematika',
        'tipe'   => 'pg',
    ]));
    $response->assertStatus(200);
});

// ── Create ─────────────────────────────────────────────────────────────────
test('guru can view bank soal create form', function () {
    $guru = makeGuruUser();

    $response = $this->actingAs($guru)->get(route('guru.bank-soal.create'));
    $response->assertStatus(200);
});

// ── Store ──────────────────────────────────────────────────────────────────
test('guru can store essay bank soal', function () {
    $guru = makeGuruUser();

    $response = $this->actingAs($guru)->post(route('guru.bank-soal.store'), [
        'pertanyaan'        => 'Apa yang dimaksud dengan integral?',
        'tipe_soal'         => 'essay',
        'tingkat_kesulitan' => 'medium',
        'pembahasan'        => 'Integral adalah kebalikan dari diferensial.',
    ]);

    $response->assertRedirect(route('guru.bank-soal.index'));
    $this->assertDatabaseHas('cbt_bank_soals', [
        'pertanyaan'  => 'Apa yang dimaksud dengan integral?',
        'created_by'  => $guru->id,
    ]);
});

test('guru can store pg bank soal with options', function () {
    $guru = makeGuruUser();

    $response = $this->actingAs($guru)->post(route('guru.bank-soal.store'), [
        'pertanyaan'        => 'Hasil 2+2 adalah?',
        'tipe_soal'         => 'pg',
        'tingkat_kesulitan' => 'easy',
        'opsi'              => ['1', '2', '3', '4'],
        'kunci'             => 3, // index 3 → '4'
    ]);

    $response->assertRedirect(route('guru.bank-soal.index'));
    $this->assertDatabaseHas('cbt_bank_soals', ['pertanyaan' => 'Hasil 2+2 adalah?']);
});

test('store bank soal fails without required fields', function () {
    $guru = makeGuruUser();

    $response = $this->actingAs($guru)->post(route('guru.bank-soal.store'), []);
    $response->assertSessionHasErrors(['pertanyaan', 'tipe_soal', 'tingkat_kesulitan']);
});

// ── Show ───────────────────────────────────────────────────────────────────
test('guru can view their bank soal detail', function () {
    $guru = makeGuruUser();
    $soal = CbtBankSoal::create([
        'pertanyaan'        => 'Soal Detail Test?',
        'tipe_soal'         => 'essay',
        'tingkat_kesulitan' => 'easy',
        'status'            => 'published',
        'created_by'        => $guru->id,
    ]);

    $response = $this->actingAs($guru)->get(route('guru.bank-soal.show', $soal->id));
    $response->assertStatus(200);
});

// ── Edit ───────────────────────────────────────────────────────────────────
test('guru can view bank soal edit form', function () {
    $guru = makeGuruUser();
    $soal = CbtBankSoal::create([
        'pertanyaan'        => 'Edit soal ini?',
        'tipe_soal'         => 'essay',
        'tingkat_kesulitan' => 'hard',
        'status'            => 'published',
        'created_by'        => $guru->id,
    ]);

    $response = $this->actingAs($guru)->get(route('guru.bank-soal.edit', $soal->id));
    $response->assertStatus(200);
});

// ── Update ─────────────────────────────────────────────────────────────────
test('guru can update their bank soal', function () {
    $guru = makeGuruUser();
    $soal = CbtBankSoal::create([
        'pertanyaan'        => 'Soal lama',
        'tipe_soal'         => 'essay',
        'tingkat_kesulitan' => 'easy',
        'status'            => 'published',
        'created_by'        => $guru->id,
    ]);

    $response = $this->actingAs($guru)->put(route('guru.bank-soal.update', $soal->id), [
        'pertanyaan'        => 'Soal baru yang lebih baik',
        'tipe_soal'         => 'essay',
        'tingkat_kesulitan' => 'medium',
        'pembahasan'        => 'Pembahasan updated.',
    ]);

    $response->assertRedirect(route('guru.bank-soal.index'));
    $this->assertDatabaseHas('cbt_bank_soals', [
        'id'          => $soal->id,
        'pertanyaan'  => 'Soal baru yang lebih baik',
    ]);
});

// ── Destroy ────────────────────────────────────────────────────────────────
test('guru can delete their bank soal', function () {
    $guru = makeGuruUser();
    $soal = CbtBankSoal::create([
        'pertanyaan'        => 'Soal yang akan dihapus',
        'tipe_soal'         => 'essay',
        'tingkat_kesulitan' => 'hard',
        'status'            => 'published',
        'created_by'        => $guru->id,
    ]);

    $response = $this->actingAs($guru)->delete(route('guru.bank-soal.destroy', $soal->id));
    $response->assertRedirect(route('guru.bank-soal.index'));
    $this->assertSoftDeleted('cbt_bank_soals', ['id' => $soal->id]);
});

test('guru cannot delete another guru bank soal', function () {
    $guru1 = makeGuruUser();
    $guru2 = makeGuruUser();
    $soal  = CbtBankSoal::create([
        'pertanyaan'        => 'Milik Guru 2',
        'tipe_soal'         => 'essay',
        'tingkat_kesulitan' => 'easy',
        'status'            => 'published',
        'created_by'        => $guru2->id,
    ]);

    $response = $this->actingAs($guru1)->delete(route('guru.bank-soal.destroy', $soal->id));
    $response->assertStatus(404);
});

// ── AJAX Endpoints ─────────────────────────────────────────────────────────
test('guru can get bab by mapel (json)', function () {
    $guru  = makeGuruUser();
    $mapel = CbtMapel::create(['nama' => 'Biologi']);
    CbtBab::create(['nama' => 'Sel', 'cbt_mapel_id' => $mapel->id]);

    $response = $this->actingAs($guru)->get(route('guru.bank-soal.bab', ['mapel_id' => $mapel->id]));
    $response->assertStatus(200);
    $response->assertJsonFragment(['nama' => 'Sel']);
});

test('guru can store mapel via ajax', function () {
    $guru = makeGuruUser();

    $response = $this->actingAs($guru)->post(route('guru.bank-soal.mapel.store'), [
        'nama' => 'Fisika Dasar',
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('cbt_mapels', ['nama' => 'Fisika Dasar']);
});

test('guru can store bab via ajax', function () {
    $guru  = makeGuruUser();
    $mapel = CbtMapel::create(['nama' => 'Kimia']);

    $response = $this->actingAs($guru)->post(route('guru.bank-soal.bab.store'), [
        'nama'         => 'Ikatan Kimia',
        'cbt_mapel_id' => $mapel->id,
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('cbt_babs', ['nama' => 'Ikatan Kimia']);
});

// ── Template Download ──────────────────────────────────────────────────────
test('guru can download bank soal template', function () {
    $guru = makeGuruUser();

    $response = $this->actingAs($guru)->get(route('guru.bank-soal.template'));
    $response->assertStatus(200);
    $this->assertStringContainsString('application/vnd.ms-excel', $response->headers->get('Content-Type'));
});
