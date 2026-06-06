<?php

use App\Models\CbtUjian;
use App\Models\CbtUjianSoal;
use App\Models\CbtUjianAssign;
use App\Models\CbtBankSoal;
use App\Models\CbtMapel;
use App\Models\KelompokBelajar;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    $this->guru = User::factory()->create([
        'level'     => 'Guru',
        'is_active' => true,
    ]);
});

test('guru can view exam list', function () {
    CbtUjian::create([
        'judul'         => 'Ujian Biologi',
        'durasi'        => 60,
        'mode'          => 'latihan',
        'created_by'    => $this->guru->id,
        'waktu_mulai'   => now()->subDays(1),
        'waktu_selesai' => now()->addDays(1),
    ]);

    $response = $this->actingAs($this->guru)->get(route('guru.ujian.index'));

    $response->assertStatus(200);
    $response->assertSee('Ujian Biologi');
});

test('guru can filter exam list', function () {
    // Active
    CbtUjian::create([
        'judul'         => 'Ujian Aktif',
        'durasi'        => 60,
        'mode'          => 'latihan',
        'created_by'    => $this->guru->id,
        'waktu_mulai'   => now()->subDays(1),
        'waktu_selesai' => now()->addDays(1),
    ]);

    // Upcoming
    CbtUjian::create([
        'judul'         => 'Ujian Mendatang',
        'durasi'        => 60,
        'mode'          => 'latihan',
        'created_by'    => $this->guru->id,
        'waktu_mulai'   => now()->addDays(1),
        'waktu_selesai' => now()->addDays(2),
    ]);

    // Finished
    CbtUjian::create([
        'judul'         => 'Ujian Selesai',
        'durasi'        => 60,
        'mode'          => 'latihan',
        'created_by'    => $this->guru->id,
        'waktu_mulai'   => now()->subDays(5),
        'waktu_selesai' => now()->subDays(4),
    ]);

    $responseAktif = $this->actingAs($this->guru)->get(route('guru.ujian.index', ['status' => 'aktif']));
    $responseAktif->assertStatus(200);
    $responseAktif->assertSee('Ujian Aktif');

    $responseMendatang = $this->actingAs($this->guru)->get(route('guru.ujian.index', ['status' => 'mendatang']));
    $responseMendatang->assertStatus(200);
    $responseMendatang->assertSee('Ujian Mendatang');

    $responseSelesai = $this->actingAs($this->guru)->get(route('guru.ujian.index', ['status' => 'selesai']));
    $responseSelesai->assertStatus(200);
    $responseSelesai->assertSee('Ujian Selesai');
});

test('guru can view create form', function () {
    $response = $this->actingAs($this->guru)->get(route('guru.ujian.create'));
    $response->assertStatus(200);
});

test('guru can store exam', function () {
    $response = $this->actingAs($this->guru)->post(route('guru.ujian.store'), [
        'judul'           => 'Ujian Fisika Quantum',
        'deskripsi'       => 'Ujian fisika dasar semester ganjil',
        'durasi'          => 90,
        'mode'            => 'resmi',
        'acak_soal'       => 1,
        'acak_opsi'       => 1,
        'tampilkan_hasil' => 1,
        'token'           => 'FISIKA123',
    ]);

    $this->assertDatabaseHas('cbt_ujians', [
        'judul'      => 'Ujian Fisika Quantum',
        'created_by' => $this->guru->id,
    ]);

    $ujian = CbtUjian::where('judul', 'Ujian Fisika Quantum')->first();
    $response->assertRedirect(route('guru.ujian.soal', $ujian->id));
});

test('guru can view exam show/detail', function () {
    $ujian = CbtUjian::create([
        'judul'      => 'Ujian Detail',
        'durasi'     => 60,
        'mode'       => 'latihan',
        'created_by' => $this->guru->id,
    ]);

    $response = $this->actingAs($this->guru)->get(route('guru.ujian.show', $ujian->id));
    $response->assertStatus(200);
    $response->assertSee('Ujian Detail');
});

test('guru can view edit form', function () {
    $ujian = CbtUjian::create([
        'judul'      => 'Ujian Edit',
        'durasi'     => 60,
        'mode'       => 'latihan',
        'created_by' => $this->guru->id,
    ]);

    $response = $this->actingAs($this->guru)->get(route('guru.ujian.edit', $ujian->id));
    $response->assertStatus(200);
});

test('guru can update exam', function () {
    $ujian = CbtUjian::create([
        'judul'      => 'Ujian Update Lama',
        'durasi'     => 60,
        'mode'       => 'latihan',
        'created_by' => $this->guru->id,
    ]);

    $response = $this->actingAs($this->guru)->put(route('guru.ujian.update', $ujian->id), [
        'judul'           => 'Ujian Update Baru',
        'deskripsi'       => 'Deskripsi Baru',
        'durasi'          => 120,
        'mode'            => 'resmi',
        'acak_soal'       => 0,
        'acak_opsi'       => 0,
        'tampilkan_hasil' => 0,
        'token'           => 'NEWTOKEN',
    ]);

    $response->assertRedirect(route('guru.ujian.show', $ujian->id));
    $this->assertDatabaseHas('cbt_ujians', [
        'id'    => $ujian->id,
        'judul' => 'Ujian Update Baru',
    ]);
});

test('guru can delete exam without participants', function () {
    $ujian = CbtUjian::create([
        'judul'      => 'Ujian Hapus',
        'durasi'     => 60,
        'mode'       => 'latihan',
        'created_by' => $this->guru->id,
    ]);

    $response = $this->actingAs($this->guru)->delete(route('guru.ujian.destroy', $ujian->id));

    $response->assertRedirect(route('guru.ujian.index'));
    $this->assertSoftDeleted('cbt_ujians', ['id' => $ujian->id]);
});

test('guru cannot delete exam that has participants', function () {
    $ujian = CbtUjian::create([
        'judul'      => 'Ujian Dengan Peserta',
        'durasi'     => 60,
        'mode'       => 'latihan',
        'created_by' => $this->guru->id,
    ]);

    $siswa = User::factory()->create([
        'level' => 'Siswa',
    ]);

    // Create participant record
    $ujian->pesertas()->create([
        'user_id'     => $siswa->id,
        'waktu_mulai' => now(),
        'status'      => 'mengerjakan',
    ]);

    $response = $this->actingAs($this->guru)->delete(route('guru.ujian.destroy', $ujian->id));

    $response->assertRedirect();
    $response->assertSessionHas('error');
    $this->assertDatabaseHas('cbt_ujians', [
        'id'         => $ujian->id,
        'deleted_at' => null,
    ]);
});

test('guru can view question management page (kelolaSoal)', function () {
    $ujian = CbtUjian::create([
        'judul'      => 'Ujian Soal',
        'durasi'     => 60,
        'mode'       => 'latihan',
        'created_by' => $this->guru->id,
    ]);

    $response = $this->actingAs($this->guru)->get(route('guru.ujian.soal', $ujian->id));
    $response->assertStatus(200);
});

test('guru can add and remove questions from exam', function () {
    $ujian = CbtUjian::create([
        'judul'      => 'Ujian Soal Add',
        'durasi'     => 60,
        'mode'       => 'latihan',
        'created_by' => $this->guru->id,
    ]);

    $soal = CbtBankSoal::create([
        'pertanyaan'        => 'Pertanyaan Test?',
        'tipe_soal'         => 'essay',
        'tingkat_kesulitan' => 'easy',
        'created_by'        => $this->guru->id,
    ]);

    // Add Question
    $responseAdd = $this->actingAs($this->guru)->post(route('guru.ujian.soal.store', $ujian->id), [
        'soal_ids' => [$soal->id],
    ]);

    $responseAdd->assertRedirect();
    $this->assertDatabaseHas('cbt_ujian_soals', [
        'cbt_ujian_id'     => $ujian->id,
        'cbt_bank_soal_id' => $soal->id,
    ]);

    // Remove Question
    $responseRemove = $this->actingAs($this->guru)->delete(route('guru.ujian.soal.destroy', [
        'id'     => $ujian->id,
        'soalId' => $soal->id,
    ]));

    $responseRemove->assertRedirect();
    $this->assertDatabaseMissing('cbt_ujian_soals', [
        'cbt_ujian_id'     => $ujian->id,
        'cbt_bank_soal_id' => $soal->id,
    ]);
});

test('guru can reorder exam questions via AJAX', function () {
    $ujian = CbtUjian::create([
        'judul'      => 'Ujian Reorder',
        'durasi'     => 60,
        'mode'       => 'latihan',
        'created_by' => $this->guru->id,
    ]);

    $soal1 = CbtBankSoal::create([
        'pertanyaan'        => 'Q1?',
        'tipe_soal'         => 'essay',
        'tingkat_kesulitan' => 'easy',
        'created_by'        => $this->guru->id,
    ]);

    $soal2 = CbtBankSoal::create([
        'pertanyaan'        => 'Q2?',
        'tipe_soal'         => 'essay',
        'tingkat_kesulitan' => 'easy',
        'created_by'        => $this->guru->id,
    ]);

    $ujianSoal1 = CbtUjianSoal::create([
        'cbt_ujian_id'     => $ujian->id,
        'cbt_bank_soal_id' => $soal1->id,
        'bobot'            => 1,
        'urutan'           => 1,
    ]);

    $ujianSoal2 = CbtUjianSoal::create([
        'cbt_ujian_id'     => $ujian->id,
        'cbt_bank_soal_id' => $soal2->id,
        'bobot'            => 1,
        'urutan'           => 2,
    ]);

    $response = $this->actingAs($this->guru)->postJson(route('guru.ujian.soal.reorder', $ujian->id), [
        'order' => [$ujianSoal2->id, $ujianSoal1->id],
    ]);

    $response->assertStatus(200);
    $response->assertJson(['status' => 'ok']);

    $ujianSoal1->refresh();
    $ujianSoal2->refresh();

    expect($ujianSoal2->urutan)->toBe(1);
    expect($ujianSoal1->urutan)->toBe(2);
});

test('guru can view assign participants page (kelolaPeserta)', function () {
    $ujian = CbtUjian::create([
        'judul'      => 'Ujian Peserta',
        'durasi'     => 60,
        'mode'       => 'latihan',
        'created_by' => $this->guru->id,
    ]);

    $response = $this->actingAs($this->guru)->get(route('guru.ujian.peserta', $ujian->id));
    $response->assertStatus(200);
});

test('guru can set exam participants (setPeserta)', function () {
    $ujian = CbtUjian::create([
        'judul'      => 'Ujian Assign',
        'durasi'     => 60,
        'mode'       => 'latihan',
        'created_by' => $this->guru->id,
    ]);

    $siswa = User::factory()->create(['level' => 'Siswa']);
    $kelompok = KelompokBelajar::create(['nama_kelompok' => 'Kelompok CBT']);

    $response = $this->actingAs($this->guru)->post(route('guru.ujian.peserta.store', $ujian->id), [
        'kelompok_ids' => [$kelompok->id],
        'siswa_ids'    => [$siswa->id],
    ]);

    $response->assertRedirect(route('guru.ujian.show', $ujian->id));
    $this->assertDatabaseHas('cbt_ujian_assigns', [
        'cbt_ujian_id' => $ujian->id,
        'tipe_assign'  => 'kelas',
        'assign_id'    => $kelompok->id,
    ]);
    $this->assertDatabaseHas('cbt_ujian_assigns', [
        'cbt_ujian_id' => $ujian->id,
        'tipe_assign'  => 'user',
        'assign_id'    => $siswa->id,
    ]);
});

test('guru can publish exam with questions and times filled', function () {
    $ujian = CbtUjian::create([
        'judul'         => 'Ujian Publish',
        'durasi'        => 60,
        'mode'          => 'latihan',
        'created_by'    => $this->guru->id,
        'waktu_mulai'   => now(),
        'waktu_selesai' => now()->addHours(2),
    ]);

    // Create a question
    $soal = CbtBankSoal::create([
        'pertanyaan'        => 'Pertanyaan Test?',
        'tipe_soal'         => 'essay',
        'tingkat_kesulitan' => 'easy',
        'created_by'        => $this->guru->id,
    ]);
    CbtUjianSoal::create([
        'cbt_ujian_id'     => $ujian->id,
        'cbt_bank_soal_id' => $soal->id,
        'bobot'            => 1,
        'urutan'           => 1,
    ]);

    $response = $this->actingAs($this->guru)->patch(route('guru.ujian.publish', $ujian->id));

    $response->assertRedirect(route('guru.ujian.show', $ujian->id));
    $response->assertSessionHas('success');
});

test('guru publish exam fails without questions', function () {
    $ujian = CbtUjian::create([
        'judul'         => 'Ujian Kosong',
        'durasi'        => 60,
        'mode'          => 'latihan',
        'created_by'    => $this->guru->id,
        'waktu_mulai'   => now(),
        'waktu_selesai' => now()->addHours(2),
    ]);

    $response = $this->actingAs($this->guru)->patch(route('guru.ujian.publish', $ujian->id));

    $response->assertRedirect();
    $response->assertSessionHas('error', 'Ujian harus memiliki minimal 1 soal sebelum di-publish.');
});

test('guru can archive exam', function () {
    $ujian = CbtUjian::create([
        'judul'      => 'Ujian Archive',
        'durasi'     => 60,
        'mode'       => 'latihan',
        'created_by' => $this->guru->id,
    ]);

    $response = $this->actingAs($this->guru)->patch(route('guru.ujian.arsipkan', $ujian->id));

    $response->assertRedirect(route('guru.ujian.index'));
    $this->assertSoftDeleted('cbt_ujians', ['id' => $ujian->id]);
});

test('guru can view exam monitoring page', function () {
    $ujian = CbtUjian::create([
        'judul'      => 'Ujian Monitor',
        'durasi'     => 60,
        'mode'       => 'latihan',
        'created_by' => $this->guru->id,
    ]);

    $response = $this->actingAs($this->guru)->get(route('guru.ujian.monitoring', $ujian->id));
    $response->assertStatus(200);
});

test('guru filtering exam list default status ignores filter', function () {
    $response = $this->actingAs($this->guru)->get(route('guru.ujian.index', ['status' => 'invalid-status']));
    $response->assertStatus(200);
});

test('guru publish exam fails without start and end times', function () {
    $ujian = CbtUjian::create([
        'judul'         => 'Ujian Tanpa Waktu',
        'durasi'        => 60,
        'mode'          => 'latihan',
        'created_by'    => $this->guru->id,
        'waktu_mulai'   => null,
        'waktu_selesai' => null,
    ]);

    $soal = \App\Models\CbtBankSoal::create([
        'pertanyaan'        => 'Pertanyaan Test?',
        'tipe_soal'         => 'essay',
        'tingkat_kesulitan' => 'easy',
        'created_by'        => $this->guru->id,
    ]);

    \Illuminate\Support\Facades\DB::table('cbt_ujian_soals')->insert([
        'cbt_ujian_id'     => $ujian->id,
        'cbt_bank_soal_id' => $soal->id,
        'bobot'            => 1,
        'urutan'           => 1,
    ]);

    $response = $this->actingAs($this->guru)->patch(route('guru.ujian.publish', $ujian->id));

    $response->assertRedirect();
    $response->assertSessionHas('error', 'Waktu mulai dan selesai harus diisi sebelum publish.');
});
