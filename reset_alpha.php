<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// 2. Hapus riwayat absen alpha
$countDeleted = \App\Models\Akademik\Absensi::where('status_masuk', 'alpha')
    ->orWhere('status_masuk', 'Alpha')
    ->delete();

echo "Berhasil menghapus {$countDeleted} riwayat absensi Alpha.\n";
