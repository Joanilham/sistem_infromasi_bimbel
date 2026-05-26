<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$kantorId = 1; // dari session daftar_kantor_id

echo "=== Paket Bimbingan ===\n";
$pakets = App\Models\PaketBimbingan::all(['id', 'nama_paket', 'kantor_id', 'periode_id']);
foreach($pakets as $p) {
    echo "ID: {$p->id}, Nama: {$p->nama_paket}, kantor_id: " . var_export($p->kantor_id, true) . ", periode_id: " . var_export($p->periode_id, true) . "\n";
}

echo "\n=== Kelompok Belajar ===\n";
$kelompoks = App\Models\KelompokBelajar::all(['id', 'nama_kelompok', 'kantor_id', 'periode_id']);
foreach($kelompoks as $k) {
    echo "ID: {$k->id}, Nama: {$k->nama_kelompok}, kantor_id: " . var_export($k->kantor_id, true) . ", periode_id: " . var_export($k->periode_id, true) . "\n";
}

echo "\n=== Periode Aktif ===\n";
$periodes = App\Models\Periode::where('is_active', true)->get(['id', 'nama_periode', 'is_active']);
foreach($periodes as $p) {
    echo "ID: {$p->id}, Nama: {$p->nama_periode}, is_active: " . var_export($p->is_active, true) . "\n";
}
