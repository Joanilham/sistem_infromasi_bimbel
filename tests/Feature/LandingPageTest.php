<?php

use App\Models\Faq;
use App\Models\Kantor;
use App\Models\Master;
use App\Models\Periode;
use App\Models\Testimonial;
use App\Models\User;

function makeSuperAdminLP(): array
{
    $kantor  = Kantor::create(['nama_kantor' => 'LP Kantor', 'alamat' => 'LP Kota']);
    $periode = Periode::create(['tahun_periode' => '2025/2026', 'is_active' => true]);
    $admin   = User::factory()->create(['level' => 'Super Admin', 'is_active' => true]);
    return compact('kantor', 'periode', 'admin');
}

// ── Index ──────────────────────────────────────────────────────────────────
test('super admin can view landing page index', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeSuperAdminLP();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->get(route('admin.landing-page.index'));

    $response->assertStatus(200);
});

// ── Update General ──────────────────────────────────────────────────────────
test('super admin can update landing page general settings', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeSuperAdminLP();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('admin.landing-page.update-general'), [
            'nama_lembaga' => 'Bimbel Hebat',
            'hero_title'   => 'Selamat Datang',
            'tentang_kami' => 'Kami adalah bimbel terbaik.',
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('masters', ['nama_lembaga' => 'Bimbel Hebat']);
});

// ── Store Testimonial ───────────────────────────────────────────────────────
test('super admin can store testimonial', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeSuperAdminLP();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('admin.landing-page.testimonial.store'), [
            'nama'   => 'Budi Santoso',
            'posisi' => 'Siswa Kelas 12',
            'ulasan' => 'Bimbel ini sangat bagus!',
            'bintang'=> 5,
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('testimonials', ['nama' => 'Budi Santoso']);
});

test('store testimonial fails without required fields', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeSuperAdminLP();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('admin.landing-page.testimonial.store'), [
            'posisi' => 'Siswa',
        ]);

    $response->assertSessionHasErrors(['nama', 'ulasan', 'bintang']);
});

// ── Destroy Testimonial ─────────────────────────────────────────────────────
test('super admin can delete testimonial', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeSuperAdminLP();

    $testimonial = Testimonial::create([
        'nama'   => 'Hapus Aku',
        'ulasan' => 'Ulasan hapus',
        'bintang'=> 3,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->delete(route('admin.landing-page.testimonial.destroy', $testimonial));

    $response->assertRedirect();
    $this->assertDatabaseMissing('testimonials', ['id' => $testimonial->id]);
});

// ── Store FAQ ───────────────────────────────────────────────────────────────
test('super admin can store faq', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeSuperAdminLP();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('admin.landing-page.faq.store'), [
            'pertanyaan' => 'Apa itu bimbel?',
            'jawaban'    => 'Bimbel adalah lembaga bimbingan belajar.',
            'urutan'     => 1,
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('faqs', ['pertanyaan' => 'Apa itu bimbel?']);
});

test('store faq fails without required fields', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeSuperAdminLP();

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->post(route('admin.landing-page.faq.store'), []);

    $response->assertSessionHasErrors(['pertanyaan', 'jawaban']);
});

// ── Destroy FAQ ─────────────────────────────────────────────────────────────
test('super admin can delete faq', function () {
    ['kantor' => $kantor, 'periode' => $periode, 'admin' => $admin] = makeSuperAdminLP();

    $faq = Faq::create([
        'pertanyaan' => 'Hapus FAQ ini',
        'jawaban'    => 'Sudah dihapus',
        'urutan'     => 99,
    ]);

    $response = $this->actingAs($admin)
        ->withSession(['kantor_id' => $kantor->id, 'periode_id' => $periode->id])
        ->delete(route('admin.landing-page.faq.destroy', $faq));

    $response->assertRedirect();
    $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
});
