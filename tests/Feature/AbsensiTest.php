<?php

use App\Models\Absensi;
use App\Models\PesertaDidik;
use App\Models\Kantor;
use App\Models\Periode;
use App\Models\PaketBimbingan;
use App\Models\KelompokBelajar;
use App\Models\User;
use App\Http\Controllers\Siswa\QrController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    Http::fake();
    
    // Create base setup
    $this->kantor = Kantor::create(['nama_kantor' => 'Kantor Cabang Utama', 'alamat' => 'Alamat Cabang']);
    $this->periode = Periode::create(['tahun_periode' => '2026/2027', 'is_active' => true]);
    
    $this->paket = PaketBimbingan::create([
        'nama_paket' => 'Super Intensive',
        'nominal'    => 750000,
        'kantor_id'  => $this->kantor->id,
        'periode_id' => $this->periode->id,
    ]);

    $this->kelompok = KelompokBelajar::create([
        'nama_kelompok' => 'Intensive-A',
        'kantor_id'     => $this->kantor->id,
        'periode_id'    => $this->periode->id,
    ]);

    $this->admin = User::factory()->create([
        'level'     => 'Admin',
        'is_active' => true,
    ]);

    $this->peserta = PesertaDidik::create([
        'nama_lengkap'        => 'Ahmad Absen',
        'nisn'                => '1234567890',
        'jenis_kelamin'       => 'L',
        'asal_sekolah'        => 'SMA 1',
        'status'              => 'Aktif',
        'no_telepon'          => '08123456789',
        'kantor_id'           => $this->kantor->id,
        'periode_id'          => $this->periode->id,
        'paket_bimbingan_id'  => $this->paket->id,
        'kelompok_belajar_id' => $this->kelompok->id,
    ]);
});

// ── Page Views ──

test('admin can view scan masuk page', function () {
    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->get(route('absensi.scan.masuk.page'));

    $response->assertStatus(200);
    $response->assertSee('scan');
});

test('admin can view scan pulang page', function () {
    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->get(route('absensi.scan.pulang.page'));

    $response->assertStatus(200);
    $response->assertSee('scan');
});

test('admin can view rekap page', function () {
    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->get(route('absensi.rekap', [
            'bulan' => now()->month,
            'tahun' => now()->year,
            'paket_id' => $this->paket->id,
            'kelompok_id' => $this->kelompok->id,
        ]));

    $response->assertStatus(200);
    $response->assertSee('Ahmad Absen');
});

// ── scanMasuk API Tests ──

test('scanMasuk rejects invalid token', function () {
    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->postJson(route('absensi.scan.masuk'), [
            'nisn' => 'invalid_token_string'
        ]);

    $response->assertStatus(200);
    $response->assertJsonFragment(['success' => false]);
    $response->assertJsonStructure(['message']);
});

test('scanMasuk rejects student not found in current context', function () {
    // A token for a NISN that doesn't exist
    $timeBlock = (int) floor(time() / 30);
    $hmac = QrController::buildHmac('0000000000', $timeBlock);
    $token = '0000000000|' . $timeBlock . '|' . $hmac;

    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->postJson(route('absensi.scan.masuk'), [
            'nisn' => $token
        ]);

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'success' => false,
        'message' => 'Siswa tidak ditemukan di cabang ini.'
    ]);
});

test('scanMasuk registers attendance on first scan and sends whatsapp', function () {
    $timeBlock = (int) floor(time() / 30);
    $hmac = QrController::buildHmac('1234567890', $timeBlock);
    $token = '1234567890|' . $timeBlock . '|' . $hmac;

    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->postJson(route('absensi.scan.masuk'), [
            'nisn' => $token
        ]);

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'success' => true,
        'sudah'   => false,
        'nama'    => 'Ahmad Absen',
        'nisn'    => '1234567890',
        'tipe'    => 'masuk',
    ]);

    $this->assertDatabaseHas('absensis', [
        'peserta_didik_id' => $this->peserta->id,
        'status_masuk'     => 'hadir',
        'wa_masuk_sent'    => true
    ]);
});

test('scanMasuk returns status already scanned on subsequent scans', function () {
    // Pre-create scan masuk record
    Illuminate\Support\Facades\DB::table('absensis')->insert([
        'peserta_didik_id' => $this->peserta->id,
        'tanggal'          => now()->toDateString(),
        'status_masuk'     => 'hadir',
        'jam_masuk'        => '08:00:00',
        'wa_masuk_sent'    => 1,
        'created_at'       => now(),
        'updated_at'       => now()
    ]);

    $timeBlock = (int) floor(time() / 30);
    $hmac = QrController::buildHmac('1234567890', $timeBlock);
    $token = '1234567890|' . $timeBlock . '|' . $hmac;

    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->postJson(route('absensi.scan.masuk'), [
            'nisn' => $token
        ]);

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'success' => true,
        'sudah'   => true,
        'nama'    => 'Ahmad Absen',
        'nisn'    => '1234567890',
        'jam'     => '08:00',
        'tipe'    => 'masuk',
    ]);
});

// ── scanPulang API Tests ──

test('scanPulang rejects invalid token', function () {
    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->postJson(route('absensi.scan.pulang'), [
            'nisn' => 'invalid_token'
        ]);

    $response->assertStatus(200);
    $response->assertJsonFragment(['success' => false]);
});

test('scanPulang rejects student not found in current context', function () {
    $timeBlock = (int) floor(time() / 30);
    $hmac = QrController::buildHmac('0000000000', $timeBlock);
    $token = '0000000000|' . $timeBlock . '|' . $hmac;

    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->postJson(route('absensi.scan.pulang'), [
            'nisn' => $token
        ]);

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'success' => false,
        'message' => 'Siswa tidak ditemukan di cabang ini.'
    ]);
});

test('scanPulang rejects student if they have not scanned masuk yet', function () {
    $timeBlock = (int) floor(time() / 30);
    $hmac = QrController::buildHmac('1234567890', $timeBlock);
    $token = '1234567890|' . $timeBlock . '|' . $hmac;

    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->postJson(route('absensi.scan.pulang'), [
            'nisn' => $token
        ]);

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'success' => false,
        'message' => 'Ahmad Absen belum absen masuk hari ini.'
    ]);
});

test('scanPulang registers scan pulang successfully and sends whatsapp', function () {
    // Pre-create attendance with jam_masuk
    Illuminate\Support\Facades\DB::table('absensis')->insert([
        'peserta_didik_id' => $this->peserta->id,
        'tanggal'          => now()->toDateString(),
        'status_masuk'     => 'hadir',
        'jam_masuk'        => '08:00:00',
        'wa_masuk_sent'    => 1,
        'created_at'       => now(),
        'updated_at'       => now()
    ]);

    $timeBlock = (int) floor(time() / 30);
    $hmac = QrController::buildHmac('1234567890', $timeBlock);
    $token = '1234567890|' . $timeBlock . '|' . $hmac;

    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->postJson(route('absensi.scan.pulang'), [
            'nisn' => $token
        ]);

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'success' => true,
        'sudah'   => false,
        'nama'    => 'Ahmad Absen',
        'nisn'    => '1234567890',
        'tipe'    => 'pulang',
    ]);

    $this->assertDatabaseHas('absensis', [
        'peserta_didik_id' => $this->peserta->id,
        'wa_pulang_sent'   => true
    ]);
});

test('scanPulang returns status already scanned pulang on subsequent scans', function () {
    // Pre-create attendance with both jam_masuk and jam_pulang
    Illuminate\Support\Facades\DB::table('absensis')->insert([
        'peserta_didik_id' => $this->peserta->id,
        'tanggal'          => now()->toDateString(),
        'status_masuk'     => 'hadir',
        'jam_masuk'        => '08:00:00',
        'jam_pulang'       => '17:00:00',
        'wa_masuk_sent'    => 1,
        'wa_pulang_sent'   => 1,
        'created_at'       => now(),
        'updated_at'       => now()
    ]);

    $timeBlock = (int) floor(time() / 30);
    $hmac = QrController::buildHmac('1234567890', $timeBlock);
    $token = '1234567890|' . $timeBlock . '|' . $hmac;

    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->postJson(route('absensi.scan.pulang'), [
            'nisn' => $token
        ]);

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'success' => true,
        'sudah'   => true,
        'nama'    => 'Ahmad Absen',
        'nisn'    => '1234567890',
        'jam'     => '17:00',
        'tipe'    => 'pulang',
    ]);
});

// ── Export XML rekap ──

test('admin can export rekap to excel xml format', function () {
    $response = $this->actingAs($this->admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->get(route('absensi.export.rekap', [
            'bulan' => now()->month,
            'tahun' => now()->year,
        ]));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/vnd.ms-excel; charset=utf-8');
});
