<?php

// Define global mocks if imagewebp is not available to allow tests to run on any environment
if (!function_exists('imagewebp')) {
    function imagewebp($image, $tempPath = null, $quality = 75) {
        if ($tempPath) {
            file_put_contents($tempPath, 'fake-webp-content');
        }
        return true;
    }
}
if (!function_exists('imagecreatefromwebp')) {
    function imagecreatefromwebp($path) {
        return tmpfile();
    }
}

// Backward-compatibility class alias mapping for tests
spl_autoload_register(function ($class) {
    if (str_starts_with($class, 'App\\Models\\')) {
        $sub = substr($class, strlen('App\\Models\\'));
        if (!str_contains($sub, '\\')) {
            $map = [
                'Kantor' => 'App\\Models\\MasterData\\Kantor',
                'Periode' => 'App\\Models\\MasterData\\Periode',
                'Master' => 'App\\Models\\MasterData\\Master',
                'Bank' => 'App\\Models\\MasterData\\Bank',
                'PesertaDidik' => 'App\\Models\\Akademik\\PesertaDidik',
                'KelompokBelajar' => 'App\\Models\\Akademik\\KelompokBelajar',
                'PaketBimbingan' => 'App\\Models\\Akademik\\PaketBimbingan',
                'Absensi' => 'App\\Models\\Akademik\\Absensi',
                'Jadwal' => 'App\\Models\\Akademik\\Jadwal',
                'JadwalMapel' => 'App\\Models\\Akademik\\JadwalMapel',
                'LmsContent' => 'App\\Models\\Akademik\\LmsContent',
                'LmsSubmission' => 'App\\Models\\Akademik\\LmsSubmission',
                'LmsCompletion' => 'App\\Models\\Akademik\\LmsCompletion',
                'CbtUjian' => 'App\\Models\\CBT\\CbtUjian',
                'CbtUjianSoal' => 'App\\Models\\CBT\\CbtUjianSoal',
                'CbtUjianAssign' => 'App\\Models\\CBT\\CbtUjianAssign',
                'CbtBankSoal' => 'App\\Models\\CBT\\CbtBankSoal',
                'CbtMapel' => 'App\\Models\\CBT\\CbtMapel',
                'CbtBab' => 'App\\Models\\CBT\\CbtBab',
                'CbtOpsiJawaban' => 'App\\Models\\CBT\\CbtOpsiJawaban',
                'CbtPembahasan' => 'App\\Models\\CBT\\CbtPembahasan',
                'CbtPeserta' => 'App\\Models\\CBT\\CbtPeserta',
                'CbtPesertaJawaban' => 'App\\Models\\CBT\\CbtPesertaJawaban',
                'KategoriPemasukan' => 'App\\Models\\Keuangan\\KategoriPemasukan',
                'KategoriPengeluaran' => 'App\\Models\\Keuangan\\KategoriPengeluaran',
                'Pemasukan' => 'App\\Models\\Keuangan\\Pemasukan',
                'Pengeluaran' => 'App\\Models\\Keuangan\\Pengeluaran',
                'PembayaranSiswa' => 'App\\Models\\Keuangan\\PembayaranSiswa',
                'PembayaranPendaftaran' => 'App\\Models\\Keuangan\\PembayaranPendaftaran',
                'TransaksiPembayaran' => 'App\\Models\\Keuangan\\TransaksiPembayaran',
                'PendaftaranSiswa' => 'App\\Models\\Pendaftaran\\PendaftaranSiswa',
                'AuditLog' => 'App\\Models\\System\\AuditLog',
                'Faq' => 'App\\Models\\System\\Faq',
                'Feature' => 'App\\Models\\System\\Feature',
                'Gallery' => 'App\\Models\\System\\Gallery',
                'Message' => 'App\\Models\\System\\Message',
                'MitraLogo' => 'App\\Models\\System\\MitraLogo',
                'Pengumuman' => 'App\\Models\\System\\Pengumuman',
                'Testimonial' => 'App\\Models\\System\\Testimonial',
                'Conversation' => 'App\\Models\\System\\Conversation',
            ];
            if (isset($map[$sub]) && class_exists($map[$sub])) {
                class_alias($map[$sub], $class);
            }
        }
    }
});

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}
