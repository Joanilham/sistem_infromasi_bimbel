<?php

use App\Models\CbtUjian;
use App\Models\CbtUjianSoal;
use App\Models\CbtBankSoal;
use App\Models\CbtOpsiJawaban;
use App\Models\CbtPeserta;
use App\Models\CbtPesertaJawaban;
use App\Models\CbtUjianAssign;
use App\Models\PesertaDidik;
use App\Models\Kantor;
use App\Models\Periode;
use App\Models\PaketBimbingan;
use App\Models\User;

beforeEach(function () {
    $this->kantor = Kantor::create(['nama_kantor' => 'Kantor Siswa', 'alamat' => 'Alamat Siswa']);
    $this->periode = Periode::create(['tahun_periode' => '2026/2027', 'is_active' => true]);
    $this->paket = PaketBimbingan::create([
        'nama_paket' => 'Paket Siswa',
        'nominal'    => 500000,
        'kantor_id'  => $this->kantor->id,
        'periode_id' => $this->periode->id,
    ]);

    $this->siswaUser = User::factory()->create([
        'level'     => 'Siswa',
        'is_active' => true,
    ]);

    $this->pesertaDidik = PesertaDidik::create([
        'nama_lengkap'       => 'Siswa CBT',
        'nisn'               => '9911223344',
        'jenis_kelamin'      => 'L',
        'asal_sekolah'       => 'SMP 1',
        'status'             => 'Aktif',
        'kantor_id'          => $this->kantor->id,
        'periode_id'         => $this->periode->id,
        'paket_bimbingan_id' => $this->paket->id,
    ]);

    // Associate user with student profile
    $this->siswaUser->update([
        'email'            => 'siswa@example.com',
        'peserta_didik_id' => $this->pesertaDidik->id,
    ]);
    $this->pesertaDidik->update(['no_telepon' => '081234567890']);
});

test('siswa can view available exams index', function () {
    $ujian = CbtUjian::create([
        'judul'         => 'Ujian Matematika',
        'durasi'        => 60,
        'mode'          => 'latihan',
        'waktu_mulai'   => now()->subMinutes(10),
        'waktu_selesai' => now()->addHours(2),
    ]);

    CbtUjianAssign::create([
        'cbt_ujian_id' => $ujian->id,
        'tipe_assign'  => 'user',
        'assign_id'    => $this->siswaUser->id,
    ]);

    $response = $this->actingAs($this->siswaUser)->get(route('siswa.ujian.index'));

    $response->assertStatus(200);
    $response->assertSee('Ujian Matematika');
});

test('siswa can view Tata Tertib page', function () {
    $ujian = CbtUjian::create([
        'judul'         => 'Ujian Matematika',
        'durasi'        => 60,
        'mode'          => 'latihan',
        'waktu_mulai'   => now()->subMinutes(10),
        'waktu_selesai' => now()->addHours(2),
    ]);

    CbtUjianAssign::create([
        'cbt_ujian_id' => $ujian->id,
        'tipe_assign'  => 'user',
        'assign_id'    => $this->siswaUser->id,
    ]);

    $response = $this->actingAs($this->siswaUser)->get(route('siswa.ujian.show', $ujian->id));

    $response->assertStatus(200);
    $response->assertSee('Tata Tertib');
});

test('siswa can start exam and load first question', function () {
    $ujian = CbtUjian::create([
        'judul'         => 'Ujian Matematika',
        'durasi'        => 60,
        'mode'          => 'latihan',
        'waktu_mulai'   => now()->subMinutes(10),
        'waktu_selesai' => now()->addHours(2),
        'token'         => 'TOKENCBT',
    ]);

    $soal = CbtBankSoal::create([
        'pertanyaan'        => 'Berapakah 5 + 5?',
        'tipe_soal'         => 'pg',
        'tingkat_kesulitan' => 'easy',
    ]);

    CbtUjianSoal::create([
        'cbt_ujian_id'     => $ujian->id,
        'cbt_bank_soal_id' => $soal->id,
        'bobot'            => 10,
        'urutan'           => 1,
    ]);

    CbtUjianAssign::create([
        'cbt_ujian_id' => $ujian->id,
        'tipe_assign'  => 'user',
        'assign_id'    => $this->siswaUser->id,
    ]);

    $response = $this->actingAs($this->siswaUser)->post(route('siswa.ujian.mulai', $ujian->id), [
        'token' => 'TOKENCBT',
    ]);

    $sesi = CbtPeserta::where('cbt_ujian_id', $ujian->id)->where('user_id', $this->siswaUser->id)->first();
    expect($sesi)->not->toBeNull();

    $response->assertRedirect(route('siswa.ujian.soal', [$sesi->id, 1]));
});

test('siswa can save answer during exam (autosave)', function () {
    $ujian = CbtUjian::create([
        'judul'         => 'Ujian Matematika',
        'durasi'        => 60,
        'mode'          => 'latihan',
        'waktu_mulai'   => now()->subMinutes(10),
        'waktu_selesai' => now()->addHours(2),
    ]);

    $soal = CbtBankSoal::create([
        'pertanyaan'        => 'Siapakah Penemu Lampu?',
        'tipe_soal'         => 'essay',
        'tingkat_kesulitan' => 'easy',
    ]);

    $sesi = CbtPeserta::create([
        'cbt_ujian_id'  => $ujian->id,
        'user_id'       => $this->siswaUser->id,
        'waktu_mulai'   => now(),
        'status'        => 'mengerjakan',
        'attempt_ke'    => 1,
        'session_token' => session()->getId(),
    ]);

    $jawaban = CbtPesertaJawaban::create([
        'cbt_peserta_id'   => $sesi->id,
        'cbt_bank_soal_id' => $soal->id,
        'urutan'           => 1,
        'ragu_ragu'        => false,
    ]);

    $response = $this->actingAs($this->siswaUser)->postJson(route('siswa.ujian.jawab', $sesi->id), [
        'urutan'       => 1,
        'jawaban_teks' => 'Thomas Alva Edison',
        'ragu_ragu'    => true,
    ]);

    $response->assertStatus(200);
    $response->assertJson(['status' => 'saved']);

    $jawaban->refresh();
    expect($jawaban->jawaban_teks)->toBe('Thomas Alva Edison');
    expect($jawaban->ragu_ragu)->toBeTrue();
});

test('siswa can submit exam and view results', function () {
    $ujian = CbtUjian::create([
        'judul'         => 'Ujian Matematika',
        'durasi'        => 60,
        'mode'          => 'latihan',
        'waktu_mulai'   => now()->subMinutes(10),
        'waktu_selesai' => now()->addHours(2),
    ]);

    $soal = CbtBankSoal::create([
        'pertanyaan'        => 'Ibukota Indonesia?',
        'tipe_soal'         => 'pg',
        'tingkat_kesulitan' => 'easy',
    ]);

    $opsi = CbtOpsiJawaban::create([
        'cbt_bank_soal_id' => $soal->id,
        'teks_opsi'        => 'Jakarta',
        'is_benar'         => true,
    ]);

    CbtUjianSoal::create([
        'cbt_ujian_id'     => $ujian->id,
        'cbt_bank_soal_id' => $soal->id,
        'bobot'            => 10,
        'urutan'           => 1,
    ]);

    $sesi = CbtPeserta::create([
        'cbt_ujian_id'  => $ujian->id,
        'user_id'       => $this->siswaUser->id,
        'waktu_mulai'   => now(),
        'status'        => 'mengerjakan',
        'attempt_ke'    => 1,
        'session_token' => session()->getId(),
    ]);

    $jawaban = CbtPesertaJawaban::create([
        'cbt_peserta_id'      => $sesi->id,
        'cbt_bank_soal_id'    => $soal->id,
        'urutan'              => 1,
        'cbt_opsi_jawaban_id' => $opsi->id,
        'ragu_ragu'           => false,
    ]);

    $responseSubmit = $this->actingAs($this->siswaUser)->post(route('siswa.ujian.submit', $sesi->id));

    $responseSubmit->assertRedirect(route('siswa.ujian.hasil', $sesi->id));
    
    $sesi->refresh();
    expect($sesi->status)->toBe('selesai');
    expect((float) $sesi->skor)->toBe(100.0);

    $responseHasil = $this->actingAs($this->siswaUser)->get(route('siswa.ujian.hasil', $sesi->id));
    $responseHasil->assertStatus(200);
    $responseHasil->assertSee('Hasil Akhir');
});

test('siswa can log cheat attempt (logBlur)', function () {
    $ujian = CbtUjian::create([
        'judul'         => 'Ujian Matematika',
        'durasi'        => 60,
        'mode'          => 'latihan',
        'waktu_mulai'   => now()->subMinutes(10),
        'waktu_selesai' => now()->addHours(2),
    ]);

    $sesi = CbtPeserta::create([
        'cbt_ujian_id'  => $ujian->id,
        'user_id'       => $this->siswaUser->id,
        'waktu_mulai'   => now(),
        'status'        => 'mengerjakan',
        'attempt_ke'    => 1,
        'session_token' => session()->getId(),
    ]);

    $response = $this->actingAs($this->siswaUser)->postJson(route('siswa.ujian.log-blur', $sesi->id));

    $response->assertStatus(200);
    $response->assertJson(['status' => 'logged', 'count' => 1]);
});

test('siswa can view exam history (riwayat)', function () {
    $ujian = CbtUjian::create([
        'judul'         => 'Ujian Matematika 101',
        'durasi'        => 60,
        'mode'          => 'latihan',
        'waktu_mulai'   => now()->subMinutes(10),
        'waktu_selesai' => now()->addHours(2),
    ]);

    CbtPeserta::create([
        'cbt_ujian_id'  => $ujian->id,
        'user_id'       => $this->siswaUser->id,
        'waktu_mulai'   => now()->subHours(2),
        'waktu_selesai' => now()->subHours(1),
        'status'        => 'selesai',
        'attempt_ke'    => 1,
        'skor'          => 85.0,
    ]);

    $response = $this->actingAs($this->siswaUser)->get(route('siswa.ujian.riwayat'));

    $response->assertStatus(200);
    $response->assertSee('Ujian Matematika 101');
});
