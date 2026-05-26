<?php

use App\Models\Kantor;
use App\Models\Periode;
use App\Models\PendaftaranSiswa;
use App\Models\PembayaranPendaftaran;
use App\Models\User;
use App\Models\PaketBimbingan;
use App\Models\KelompokBelajar;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

beforeEach(function () {
    Storage::fake('public');
    $this->kantor = Kantor::create(['nama_kantor' => 'Kantor Cabang', 'alamat' => 'Alamat']);
    $this->periode = Periode::create([
        'tahun_periode' => '2026/2027',
        'is_active'    => true,
    ]);
});

// Helper untuk membuat user admin
function makeAdminUser(): User
{
    return User::factory()->create([
        'level'     => 'Admin',
        'is_active' => true,
    ]);
}

test('step 1 page can be loaded', function () {
    $response = $this->get(route('daftar.step1'));
    $response->assertStatus(200);
});

test('step 1 store redirects to step 2 and sets session', function () {
    $response = $this->post(route('daftar.step1.store'), [
        'email'                 => 'siswa@gmail.com',
        'password'              => 'Password123!',
        'password_confirmation' => 'Password123!',
        'kantor_id'             => $this->kantor->id,
    ]);

    $response->assertRedirect(route('daftar.step2'));
    expect(Session::get('daftar_email'))->toBe('siswa@gmail.com');
    expect(Session::get('daftar_kantor_id'))->toBe($this->kantor->id);
});

test('step 2 page redirects to step 1 if no session', function () {
    $response = $this->get(route('daftar.step2'));
    $response->assertRedirect(route('daftar.step1'));
});

test('step 2 page can be loaded with session', function () {
    Session::put('daftar_email', 'siswa@gmail.com');
    Session::put('daftar_kantor_id', $this->kantor->id);

    $response = $this->get(route('daftar.step2'));
    $response->assertStatus(200);
});

test('step 2 store saves input to session', function () {
    Session::put('daftar_email', 'siswa@gmail.com');

    $response = $this->post(route('daftar.step2.store'), [
        'nama_lengkap'  => 'Siswa Baru',
        'jenis_kelamin' => 'L',
        'asal_sekolah'  => 'SMP 1',
        'no_telepon'    => '081234567890',
    ]);

    $response->assertRedirect(route('daftar.step3'));
    expect(Session::get('daftar_data')['nama_lengkap'])->toBe('Siswa Baru');
});

test('step 3 page redirects to step 1 if missing session', function () {
    $response = $this->get(route('daftar.step3'));
    $response->assertRedirect(route('daftar.step1'));
});

test('step 3 page can be loaded with session', function () {
    Session::put('daftar_email', 'siswa@gmail.com');
    Session::put('daftar_data', [
        'nama_lengkap'  => 'Siswa Baru',
        'jenis_kelamin' => 'L',
        'asal_sekolah'  => 'SMP 1',
    ]);

    $response = $this->get(route('daftar.step3'));
    $response->assertStatus(200);
});

test('step 3 store uploads file and creates pendaftaran', function () {
    $file = UploadedFile::fake()->create('bukti.pdf', 500);

    Session::put('daftar_email', 'siswa@gmail.com');
    Session::put('daftar_password', 'Password123!');
    Session::put('daftar_kantor_id', $this->kantor->id);
    Session::put('daftar_data', [
        'nama_lengkap'  => 'Siswa Baru',
        'jenis_kelamin' => 'L',
        'asal_sekolah'  => 'SMP 1',
        'no_telepon'    => '081234567890',
    ]);

    $response = $this->post(route('daftar.step3.store'), [
        'metode_pembayaran' => 'Transfer',
        'bukti_pembayaran'  => $file,
    ]);

    $response->assertRedirect(route('daftar.selesai'));
    $this->assertDatabaseHas('pendaftaran_siswas', [
        'email'        => 'siswa@gmail.com',
        'nama_lengkap' => 'Siswa Baru',
    ]);
    $this->assertDatabaseHas('pembayaran_pendaftarans', [
        'metode_pembayaran' => 'Transfer',
    ]);
});

test('selesai page can be loaded', function () {
    $response = $this->get(route('daftar.selesai'));
    $response->assertStatus(200);
});

test('email verification works with valid token', function () {
    $pendaftaran = PendaftaranSiswa::create([
        'email'                    => 'verif@gmail.com',
        'password'                 => Hash::make('Password123!'),
        'nama_lengkap'             => 'Verifikasi Siswa',
        'jenis_kelamin'            => 'L',
        'asal_sekolah'             => 'SMP 1',
        'email_verification_token' => 'token123',
    ]);

    $response = $this->get(route('daftar.verifikasi.email', 'token123'));
    $response->assertRedirect(route('login'));
    $response->assertSessionHas('success');

    $pendaftaran->refresh();
    expect($pendaftaran->isEmailVerified())->toBeTrue();
});

test('email verification fails with invalid token', function () {
    $response = $this->get(route('daftar.verifikasi.email', 'invalidtoken'));
    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors('email');
});

test('admin pendaftaran index can be accessed by admin', function () {
    $admin = makeAdminUser();
    $response = $this->actingAs($admin)->get(route('admin.pendaftaran.index'));
    $response->assertStatus(200);
});

test('admin pendaftaran show can be accessed by admin', function () {
    $admin = makeAdminUser();
    $pendaftaran = PendaftaranSiswa::create([
        'email'                    => 'show@gmail.com',
        'password'                 => Hash::make('Password123!'),
        'nama_lengkap'             => 'Show Siswa',
        'jenis_kelamin'            => 'L',
        'asal_sekolah'             => 'SMP 1',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.pendaftaran.show', $pendaftaran->id));
    $response->assertStatus(200);
});

test('admin can verify a pendaftaran', function () {
    $admin = makeAdminUser();
    $pendaftaran = PendaftaranSiswa::create([
        'email'                    => 'verify@gmail.com',
        'password'                 => Hash::make('Password123!'),
        'nama_lengkap'             => 'Verify Siswa',
        'jenis_kelamin'            => 'L',
        'asal_sekolah'             => 'SMP 1',
        'status'                   => 'menunggu',
        'kantor_id'                => $this->kantor->id,
        'periode_id'               => $this->periode->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->post(route('admin.pendaftaran.verifikasi', $pendaftaran->id), [
            'aksi'          => 'diverifikasi',
            'catatan_admin' => 'Dokumen lengkap',
        ]);

    $response->assertRedirect();
    $pendaftaran->refresh();
    expect($pendaftaran->status)->toBe('diverifikasi');
    $this->assertDatabaseHas('peserta_didiks', [
        'nama_lengkap' => 'Verify Siswa',
    ]);
    $this->assertDatabaseHas('users', [
        'email' => 'verify@gmail.com',
        'level' => 'Siswa',
    ]);
});

test('admin can reject a pendaftaran', function () {
    $admin = makeAdminUser();
    $pendaftaran = PendaftaranSiswa::create([
        'email'                    => 'reject@gmail.com',
        'password'                 => Hash::make('Password123!'),
        'nama_lengkap'             => 'Reject Siswa',
        'jenis_kelamin'            => 'L',
        'asal_sekolah'             => 'SMP 1',
        'status'                   => 'menunggu',
        'kantor_id'                => $this->kantor->id,
        'periode_id'               => $this->periode->id,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->post(route('admin.pendaftaran.verifikasi', $pendaftaran->id), [
            'aksi'          => 'ditolak',
            'catatan_admin' => 'Dokumen tidak valid',
        ]);

    $response->assertRedirect();
    $this->assertDatabaseMissing('pendaftaran_siswas', [
        'id' => $pendaftaran->id,
    ]);
});
