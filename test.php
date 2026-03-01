<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

try {
    $app->make('auth')->loginUsingId(1);
} catch (\Exception $e) {
    // ignore
}

$request = Illuminate\Http\Request::create('/peserta-didik', 'GET');
$response = $kernel->handle($request);
echo "HTTP: " . $response->getStatusCode() . "\n";
file_put_contents('peserta.html', $response->getContent());
