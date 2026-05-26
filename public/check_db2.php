<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pakets = App\Models\PaketBimbingan::all(['id', 'nama_paket', 'kantor_id']);
$kelompoks = App\Models\KelompokBelajar::all(['id', 'nama_kelompok', 'kantor_id']);

echo "Paket:\n";
foreach($pakets as $p) {
    echo "ID: {$p->id}, Nama: {$p->nama_paket}, Kantor: " . var_export($p->kantor_id, true) . "\n";
}

echo "\nKelompok:\n";
foreach($kelompoks as $k) {
    echo "ID: {$k->id}, Nama: {$k->nama_kelompok}, Kantor: " . var_export($k->kantor_id, true) . "\n";
}
