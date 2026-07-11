<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Akademik\PesertaDidik;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// Ambil semua data Peserta Didik
$students = PesertaDidik::all();

$csvData = [];
// Header CSV
$csvData[] = ['ID Peserta', 'Nama Lengkap', 'Username', 'Email Akun', 'Password Akun'];

foreach ($students as $student) {
    // Bersihkan nama untuk format username & email
    $cleanName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $student->nama_lengkap));
    
    // Cari apakah siswa ini sudah punya User account berdasarkan peserta_didik_id
    $user = User::where('peserta_didik_id', $student->id)->first();
    
    if (!$user) {
        // Jika belum ada, buat instans User baru
        $user = new User();
        $user->name = $student->nama_lengkap;
        $user->peserta_didik_id = $student->id;
        $user->level = 'Siswa';
        $user->is_active = 1;
        // Opsional: hubungkan dengan kantor_id dan periode_id jika ada
        if (isset($student->kantor_id)) $user->kantor_id = $student->kantor_id;
        if (isset($student->periode_id)) $user->periode_id = $student->periode_id;
    }

    // Generate Email
    $newEmail = $cleanName . '@geniusedu.my.id';
    $count = 1;
    while (User::where('email', $newEmail)->where('id', '!=', $user->id ?? 0)->exists()) {
        $newEmail = $cleanName . $count . '@geniusedu.my.id';
        $count++;
    }
    
    // Generate Username (jika tabel users butuh username)
    $newUsername = $cleanName;
    $countUser = 1;
    while (User::where('username', $newUsername)->where('id', '!=', $user->id ?? 0)->exists()) {
        $newUsername = $cleanName . $countUser;
        $countUser++;
    }

    // Buat password acak tapi mudah diingat: namadepan123
    $firstName = explode(' ', $student->nama_lengkap)[0];
    $newPassword = strtolower(preg_replace('/[^a-zA-Z]/', '', $firstName)) . '123';
    
    // Pastikan password minimal 8 karakter (standar Laravel)
    if (strlen($newPassword) < 8) {
        $newPassword .= '456';
    }
    
    // Update data User
    $user->username = $newUsername;
    $user->email = $newEmail;
    $user->password = Hash::make($newPassword);
    
    // Simpan User (create atau update)
    $user->save();
    
    // Masukkan ke baris CSV
    $csvData[] = [
        $student->id,
        $student->nama_lengkap,
        $user->username,
        $newEmail,
        $newPassword
    ];
}

// Simpan file CSV dengan nama baru
$filePath = '/var/www/html/public/akun_semua_siswa_final.csv';
$fp = fopen($filePath, 'w');

// Tambahkan BOM agar karakter terbaca benar
fputs($fp, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));

// Trik ajaib untuk Microsoft Excel: paksa Excel menggunakan separator koma
fputs($fp, "sep=,\n");

foreach ($csvData as $fields) {
    // Gunakan pemisah standar (koma)
    fputcsv($fp, $fields, ',');
}
fclose($fp);

echo "Berhasil memproses dan membuat akun untuk " . count($students) . " peserta didik.\n";
echo "File hasil export disimpan di: " . $filePath . "\n";
