<?php
$f = __DIR__ . '/../resources/views/admin/absensi/masuk.blade.php';
$c = file_get_contents($f);
$m = '@endsection';
// Cari POSISI PERTAMA @endsection
$i = strpos($c, $m);
if ($i !== false) {
    $clean = substr($c, 0, $i + strlen($m)) . "\n";
    file_put_contents($f, $clean);
    $lines = count(file($f));
    echo "OK - total lines: $lines\n";
} else {
    echo "NOT FOUND\n";
}
