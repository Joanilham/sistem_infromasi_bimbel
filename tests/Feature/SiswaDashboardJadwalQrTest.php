<?php

use App\Models\Jadwal;
use App\Models\KelompokBelajar;
use App\Models\PesertaDidik;
use App\Models\Kantor;
use App\Models\Periode;
use App\Models\PaketBimbingan;
use App\Models\User;
use App\Models\Absensi;
use App\Models\CbtUjian;
use App\Models\CbtUjianAssign;
use App\Models\CbtPeserta;
use App\Http\Controllers\Siswa\QrController;

beforeEach(function () {
    $this->kantor = Kantor::create(['nama_kantor' => 'Kantor Siswa', 'alamat' => 'Alamat Siswa']);
    $this->periode = Periode::create(['tahun_periode' => '2026/2027', 'is_active' => true]);
    $this->paket = PaketBimbingan::create([
        'nama_paket' => 'Paket Siswa',
        'nominal'    => 500000,
        'kantor_id'  => $this->kantor->id,
        'periode_id' => $this->periode->id,
    ]);

    $this->kelompok = KelompokBelajar::create([
        'nama_kelompok' => 'Kelas X-A',
        'kantor_id'     => $this->kantor->id,
        'periode_id'    => $this->periode->id,
    ]);

    $this->siswaUser = User::factory()->create([
        'level'     => 'Siswa',
        'is_active' => true,
    ]);

    $this->pesertaDidik = PesertaDidik::create([
        'nama_lengkap'        => 'Siswa CBT',
        'nisn'                => '9911223344',
        'jenis_kelamin'       => 'L',
        'asal_sekolah'        => 'SMP 1',
        'status'              => 'Aktif',
        'kantor_id'           => $this->kantor->id,
        'periode_id'          => $this->periode->id,
        'paket_bimbingan_id'  => $this->paket->id,
        'kelompok_belajar_id' => $this->kelompok->id,
    ]);

    $this->siswaUser->update([
        'email'            => 'siswa@example.com',
        'peserta_didik_id' => $this->pesertaDidik->id,
    ]);
});

// ── DashboardController Tests ──

test('siswa dashboard shows correct information', function () {
    // Generate an attendance record
    Absensi::create([
        'peserta_didik_id' => $this->pesertaDidik->id,
        'tanggal'          => now()->toDateString(),
        'status_masuk'     => 'hadir',
        'jam_masuk'        => '07:30:00',
    ]);

    // Active exam
    $ujian = CbtUjian::create([
        'judul'         => 'Ujian Sejarah',
        'durasi'        => 45,
        'mode'          => 'latihan',
        'waktu_mulai'   => now()->subMinutes(5),
        'waktu_selesai' => now()->addHours(1),
    ]);

    CbtUjianAssign::create([
        'cbt_ujian_id' => $ujian->id,
        'tipe_assign'  => 'kelas',
        'assign_id'    => $this->kelompok->id,
    ]);

    $response = $this->actingAs($this->siswaUser)->get(route('siswa.dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Ujian Sejarah');
});

test('siswa dashboard aborts if student record not found', function () {
    $this->siswaUser->update(['peserta_didik_id' => null]);

    $response = $this->actingAs($this->siswaUser)->get(route('siswa.dashboard'));

    $response->assertStatus(403);
});

// ── JadwalController Tests ──

test('siswa can view empty schedule page when not in group', function () {
    $this->pesertaDidik->update(['kelompok_belajar_id' => null]);

    $response = $this->actingAs($this->siswaUser)->get(route('siswa.jadwal.index'));

    $response->assertStatus(200);
    $response->assertSee('Jadwal');
});

test('siswa can view weekly schedule correctly', function () {
    $guru = User::factory()->create(['level' => 'Guru']);
    $mapel = \App\Models\CbtMapel::create(['nama' => 'Matematika']);

    $jadwal = Jadwal::create([
        'rombel_id'         => $this->kelompok->id,
        'hari'              => 'Senin',
        'jam_mulai'         => '08:00:00',
        'jam_selesai'       => '09:30:00',
        'mata_pelajaran_id' => $mapel->id,
        'guru_id'           => $guru->id,
        'ruangan'           => 'R-1',
        'kantor_id'          => $this->kantor->id,
        'periode_id'         => $this->periode->id,
    ]);

    $response = $this->actingAs($this->siswaUser)->get(route('siswa.jadwal.index'));

    $response->assertStatus(200);
    $response->assertSee('Jadwal');
});

// ── QrController Tests ──

test('siswa can view QR scan page and get status', function () {
    Absensi::create([
        'peserta_didik_id' => $this->pesertaDidik->id,
        'tanggal'          => now()->toDateString(),
        'status_masuk'     => 'hadir',
        'jam_masuk'        => '07:45:00',
    ]);

    $responseView = $this->actingAs($this->siswaUser)->get(route('siswa.qr.show'));
    $responseView->assertStatus(200);

    $responseJson = $this->actingAs($this->siswaUser)->getJson(route('siswa.qr.status'));
    $responseJson->assertStatus(200);
    $responseJson->assertJsonFragment(['jam_masuk' => '07:45:00']);
});

test('siswa can generate dynamic dynamic QR token', function () {
    $response = $this->actingAs($this->siswaUser)->getJson(route('siswa.qr.token'));
    
    $response->assertStatus(200);
    $response->assertJsonStructure(['qr_content', 'expires_in', 'nama', 'nisn']);
});

test('validateToken static method matches correctly', function () {
    $timeBlock = (int) floor(time() / 30);
    $hmac = QrController::buildHmac('9911223344', $timeBlock);
    
    // Valid token
    $tokenString = '9911223344|' . $timeBlock . '|' . $hmac;
    $validated = QrController::validateToken($tokenString);
    expect($validated)->toBe('9911223344');

    // Mismatched token
    $invalidToken = '9911223344|' . $timeBlock . '|wronghmac';
    expect(QrController::validateToken($invalidToken))->toBeNull();

    // Expired timeblock token
    $expiredToken = '9911223344|' . ($timeBlock - 5) . '|' . $hmac;
    expect(QrController::validateToken($expiredToken))->toBeNull();

    // Invalid format token
    $badFormat = '9911223344|' . $timeBlock;
    expect(QrController::validateToken($badFormat))->toBeNull();

    // Plain NISN token
    expect(QrController::validateToken('9911223344'))->toBe('9911223344');
});
