<?php

namespace Tests\Feature;

use App\Models\Akademik\KelompokBelajar;
use App\Models\Akademik\LmsCompletion;
use App\Models\Akademik\LmsContent;
use App\Models\Akademik\LmsSubmission;
use App\Models\Akademik\PaketBimbingan;
use App\Models\Akademik\PesertaDidik;
use App\Models\MasterData\Kantor;
use App\Models\MasterData\Periode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LmsFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected Kantor $kantor;
    protected Periode $periode;
    protected KelompokBelajar $kelompok;
    protected User $guru;
    protected User $siswaUser;
    protected PesertaDidik $pesertaDidik;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->kantor = Kantor::create(['nama_kantor' => 'Kantor Utama', 'alamat' => 'Alamat']);
        $this->periode = Periode::create(['tahun_periode' => '2026/2027', 'is_active' => true]);

        $paket = PaketBimbingan::create([
            'nama_paket' => 'Paket Reguler',
            'nominal'    => 500000,
            'kantor_id'  => $this->kantor->id,
            'periode_id' => $this->periode->id,
        ]);

        $this->kelompok = KelompokBelajar::create([
            'nama_kelompok' => 'Kelas 10-A',
            'kantor_id'     => $this->kantor->id,
            'periode_id'    => $this->periode->id,
        ]);

        $this->guru = User::factory()->create([
            'name'          => 'Budi Guru',
            'level'         => 'Guru',
            'matapelajaran' => 'Matematika',
            'kantor_id'     => $this->kantor->id,
            'periode_id'    => $this->periode->id,
            'is_active'     => true,
        ]);

        $this->siswaUser = User::factory()->create([
            'name'      => 'Ahmad Siswa',
            'level'     => 'Siswa',
            'is_active' => true,
        ]);

        $this->pesertaDidik = PesertaDidik::create([
            'nama_lengkap'        => 'Ahmad Siswa',
            'nisn'                => '1234567890',
            'nomor_induk'         => 'NIS001',
            'jenis_kelamin'       => 'L',
            'asal_sekolah'        => 'SMP 1',
            'status'              => 'Aktif',
            'kantor_id'           => $this->kantor->id,
            'periode_id'          => $this->periode->id,
            'paket_bimbingan_id'  => $paket->id,
            'kelompok_belajar_id' => $this->kelompok->id,
        ]);

        $this->siswaUser->update([
            'peserta_didik_id' => $this->pesertaDidik->id,
        ]);
    }

    public function test_guru_can_view_lms_index(): void
    {
        $response = $this->actingAs($this->guru)
            ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
            ->get(route('guru.lms.index'));

        $response->assertOk();
        $response->assertViewIs('guru.lms.index');
        $response->assertViewHasAll(['contents', 'kelompokBelajars', 'guruMapels', 'totalMateri', 'totalTugas']);
    }

    public function test_guru_can_create_and_store_material(): void
    {
        $file = UploadedFile::fake()->create('materi_aljabar.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->guru)
            ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
            ->post(route('guru.lms.store'), [
                'type'                => 'materi',
                'title'               => 'Materi Aljabar',
                'subject'             => 'Matematika',
                'description'         => 'Modul materi dasar aljabar',
                'kelompok_belajar_id' => $this->kelompok->id,
                'file'                => $file,
            ]);

        $response->assertRedirect(route('guru.lms.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('lms_contents', [
            'guru_id'             => $this->guru->id,
            'title'               => 'Materi Aljabar',
            'subject'             => 'Matematika',
            'type'                => 'materi',
            'kelompok_belajar_id' => $this->kelompok->id,
        ]);
    }

    public function test_siswa_can_view_lms_index_and_material(): void
    {
        $content = LmsContent::create([
            'guru_id'             => $this->guru->id,
            'kantor_id'           => $this->kantor->id,
            'periode_id'          => $this->periode->id,
            'subject'             => 'Matematika',
            'type'                => 'materi',
            'title'               => 'Pengantar Geometri',
            'description'         => 'Penjelasan sudut & bidang',
            'kelompok_belajar_id' => $this->kelompok->id,
        ]);

        $response = $this->actingAs($this->siswaUser)
            ->get(route('siswa.lms.index'));

        $response->assertOk();
        $response->assertViewIs('siswa.lms.index');
        $response->assertViewHasAll(['contents', 'materials', 'assignments', 'stats']);

        // Student views show -> records read
        $showResponse = $this->actingAs($this->siswaUser)
            ->get(route('siswa.lms.show', $content));

        $showResponse->assertOk();
        $this->assertDatabaseHas('lms_completions', [
            'lms_content_id'   => $content->id,
            'peserta_didik_id' => $this->pesertaDidik->id,
        ]);
    }

    public function test_siswa_can_submit_and_unsubmit_assignment(): void
    {
        $tugas = LmsContent::create([
            'guru_id'             => $this->guru->id,
            'kantor_id'           => $this->kantor->id,
            'periode_id'          => $this->periode->id,
            'subject'             => 'Matematika',
            'type'                => 'tugas',
            'title'               => 'Tugas 1 Kalkulus',
            'description'         => 'Kerjakan soal 1-5',
            'kelompok_belajar_id' => $this->kelompok->id,
            'due_at'              => now()->addDays(2),
        ]);

        $file = UploadedFile::fake()->create('jawaban_ahmad.pdf', 50, 'application/pdf');

        $response = $this->actingAs($this->siswaUser)
            ->post(route('siswa.lms.submit', $tugas), [
                'catatan_siswa' => 'Berikut lembar jawaban saya.',
                'file'          => $file,
            ]);

        $response->assertSessionHas('success');

        $submission = LmsSubmission::where('lms_content_id', $tugas->id)
            ->where('peserta_didik_id', $this->pesertaDidik->id)
            ->first();

        $this->assertNotNull($submission);
        $this->assertEquals('1234567890', $submission->nisn);
        $this->assertEquals('submitted', $submission->status);

        // Guru grades submission
        $gradeResponse = $this->actingAs($this->guru)
            ->post(route('guru.lms.submission.grade', $submission), [
                'nilai'         => 95,
                'feedback_guru' => 'Bagus sekali!',
                'status'        => 'graded',
            ]);

        $gradeResponse->assertSessionHas('success');
        $this->assertEquals(95, $submission->fresh()->nilai);
        $this->assertTrue($submission->fresh()->isGraded());
    }

    public function test_guru_can_view_readers_and_submissions(): void
    {
        $materi = LmsContent::create([
            'guru_id'             => $this->guru->id,
            'kantor_id'           => $this->kantor->id,
            'periode_id'          => $this->periode->id,
            'subject'             => 'Matematika',
            'type'                => 'materi',
            'title'               => 'Materi Statistik',
            'kelompok_belajar_id' => $this->kelompok->id,
        ]);

        $response = $this->actingAs($this->guru)
            ->get(route('guru.lms.readers', $materi));

        $response->assertOk();
        $response->assertViewIs('guru.lms.readers');
        $response->assertViewHasAll(['studentList', 'totalRombel', 'totalRead']);

        $ajaxResponse = $this->actingAs($this->guru)
            ->getJson(route('guru.lms.readers', $materi));

        $ajaxResponse->assertOk();
        $ajaxResponse->assertJsonStructure([
            'success', 'lms_id', 'title', 'total_rombel', 'total_read', 'students',
        ]);
    }

    public function test_submissions_view_works_properly(): void
    {
        $tugas = LmsContent::create([
            'guru_id'             => $this->guru->id,
            'kantor_id'           => $this->kantor->id,
            'periode_id'          => $this->periode->id,
            'subject'             => 'Matematika',
            'type'                => 'tugas',
            'title'               => 'Tugas Rombel Kosong',
            'kelompok_belajar_id' => $this->kelompok->id,
            'due_at'              => now()->addDays(2),
        ]);

        $response = $this->actingAs($this->guru)
            ->get(route('guru.lms.submissions', $tugas));

        $response->assertOk();
        $response->assertViewIs('guru.lms.submissions');
    }

    public function test_cannot_unsubmit_graded_assignment(): void
    {
        $tugas = LmsContent::create([
            'guru_id'             => $this->guru->id,
            'kantor_id'           => $this->kantor->id,
            'periode_id'          => $this->periode->id,
            'subject'             => 'Matematika',
            'type'                => 'tugas',
            'title'               => 'Tugas Fisika',
            'kelompok_belajar_id' => $this->kelompok->id,
        ]);

        $submission = LmsSubmission::create([
            'lms_content_id'   => $tugas->id,
            'peserta_didik_id' => $this->pesertaDidik->id,
            'submitted_at'     => now(),
            'status'           => 'graded',
            'nilai'            => 88,
        ]);

        $response = $this->actingAs($this->siswaUser)
            ->delete(route('siswa.lms.unsubmit', $tugas));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('lms_submissions', [
            'id' => $submission->id,
        ]);
    }

    public function test_viewer_and_raw_stream_for_guru_and_siswa(): void
    {
        $file = UploadedFile::fake()->create('modul_teori.pdf', 80, 'application/pdf');
        $stored = $file->store('lms/materials', 'public');

        $content = LmsContent::create([
            'guru_id'             => $this->guru->id,
            'kantor_id'           => $this->kantor->id,
            'periode_id'          => $this->periode->id,
            'subject'             => 'Matematika',
            'type'                => 'materi',
            'title'               => 'Modul Teori PDF',
            'kelompok_belajar_id' => $this->kelompok->id,
            'file_path'           => $stored,
            'file_name'           => 'modul_teori.pdf',
        ]);

        // Guru viewer page
        $guruViewer = $this->actingAs($this->guru)->get(route('guru.lms.viewer', $content));
        $guruViewer->assertOk();
        $guruViewer->assertViewIs('guru.lms.viewer');

        // Guru raw stream for PDF.js canvas
        $guruRaw = $this->actingAs($this->guru)->get(route('guru.lms.open', ['lms' => $content, 'raw' => 1]));
        $guruRaw->assertOk();
        $this->assertEquals('application/octet-stream', $guruRaw->headers->get('Content-Type'));

        // Siswa viewer page
        $siswaViewer = $this->actingAs($this->siswaUser)->get(route('siswa.lms.viewer', $content));
        $siswaViewer->assertOk();
        $siswaViewer->assertViewIs('siswa.lms.viewer');

        // Siswa raw stream
        $siswaRaw = $this->actingAs($this->siswaUser)->get(route('siswa.lms.open', ['lms' => $content, 'raw' => 1]));
        $siswaRaw->assertOk();
        $this->assertEquals('application/octet-stream', $siswaRaw->headers->get('Content-Type'));

        // Verify completion record was created with read_at
        $this->assertDatabaseHas('lms_completions', [
            'lms_content_id'   => $content->id,
            'peserta_didik_id' => $this->pesertaDidik->id,
        ]);
    }

    public function test_guru_can_update_and_delete_content(): void
    {
        $oldFile = UploadedFile::fake()->create('versi1.pdf', 30, 'application/pdf');
        $oldPath = $oldFile->store('lms/materials', 'public');

        $content = LmsContent::create([
            'guru_id'             => $this->guru->id,
            'kantor_id'           => $this->kantor->id,
            'periode_id'          => $this->periode->id,
            'subject'             => 'Matematika',
            'type'                => 'materi',
            'title'               => 'Judul Awal',
            'kelompok_belajar_id' => $this->kelompok->id,
            'file_path'           => $oldPath,
            'file_name'           => 'versi1.pdf',
        ]);

        $this->assertTrue(Storage::disk('public')->exists($oldPath));

        $newFile = UploadedFile::fake()->create('versi2.pdf', 40, 'application/pdf');

        $updateResp = $this->actingAs($this->guru)
            ->put(route('guru.lms.update', $content), [
                'type'                => 'materi',
                'title'               => 'Judul Baru Diperbarui',
                'subject'             => 'Matematika',
                'kelompok_belajar_id' => $this->kelompok->id,
                'file'                => $newFile,
            ]);

        $updateResp->assertRedirect(route('guru.lms.index'));
        $content->refresh();
        $this->assertEquals('Judul Baru Diperbarui', $content->title);
        $this->assertFalse(Storage::disk('public')->exists($oldPath));
        $this->assertTrue(Storage::disk('public')->exists($content->file_path));

        // Delete content
        $deleteResp = $this->actingAs($this->guru)
            ->delete(route('guru.lms.destroy', $content));

        $deleteResp->assertRedirect(route('guru.lms.index'));
        $this->assertDatabaseMissing('lms_contents', ['id' => $content->id]);
        $this->assertFalse(Storage::disk('public')->exists($content->file_path));
    }

    public function test_siswa_can_toggle_material_completion(): void
    {
        $material = LmsContent::create([
            'guru_id'             => $this->guru->id,
            'kantor_id'           => $this->kantor->id,
            'periode_id'          => $this->periode->id,
            'subject'             => 'Matematika',
            'type'                => 'materi',
            'title'               => 'Materi Teks Mandiri',
            'kelompok_belajar_id' => $this->kelompok->id,
        ]);

        // Toggle 1: Mark completed
        $response1 = $this->actingAs($this->siswaUser)
            ->post(route('siswa.lms.complete', $material));

        $response1->assertSessionHas('success');
        $this->assertDatabaseHas('lms_completions', [
            'lms_content_id'   => $material->id,
            'peserta_didik_id' => $this->pesertaDidik->id,
        ]);

        // Toggle 2: Unmark completed
        $response2 = $this->actingAs($this->siswaUser)
            ->post(route('siswa.lms.complete', $material));

        $response2->assertSessionHas('success');
        $this->assertDatabaseMissing('lms_completions', [
            'lms_content_id'   => $material->id,
            'peserta_didik_id' => $this->pesertaDidik->id,
        ]);
    }

    public function test_guru_create_material_with_all_kantor_session_sanitizes_id(): void
    {
        $file = UploadedFile::fake()->create('materi_fisika.pdf', 50, 'application/pdf');

        $response = $this->actingAs($this->guru)
            ->withSession(['kantor_id' => 'all', 'periode_id' => $this->periode->id])
            ->post(route('guru.lms.store'), [
                'type'                => 'materi',
                'title'               => 'Materi Fisika Terbuka',
                'subject'             => 'Matematika',
                'kelompok_belajar_id' => $this->kelompok->id,
                'file'                => $file,
            ]);

        $response->assertRedirect(route('guru.lms.index'));
        $created = LmsContent::where('title', 'Materi Fisika Terbuka')->first();
        $this->assertNotNull($created);
        $this->assertIsInt($created->kantor_id);
        $this->assertEquals($this->kantor->id, $created->kantor_id);
    }
}


