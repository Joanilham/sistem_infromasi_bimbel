<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$kantorId = 1;
$pakets = App\Models\PaketBimbingan::when($kantorId, fn($q) => $q->where(fn($q2) => $q2->where('kantor_id', $kantorId)->orWhereNull('kantor_id')))->get();
$kelompoks = App\Models\KelompokBelajar::when($kantorId, fn($q) => $q->where(fn($q2) => $q2->where('kantor_id', $kantorId)->orWhereNull('kantor_id')))->get();

echo "Paket found: " . count($pakets) . "\n";
echo "Kelompok found: " . count($kelompoks) . "\n";

foreach($pakets as $p) {
    echo "Paket: {$p->nama_paket}\n";
}
