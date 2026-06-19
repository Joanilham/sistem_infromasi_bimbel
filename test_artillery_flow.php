<?php

// Simulate Artillery Flow
$cookieJar = tempnam(sys_get_temp_dir(), 'cookies');
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // We want to capture redirects manually

$baseUrl = 'http://geniusedu-nginx:8080';

// 1. GET /login
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
$loginHtml = curl_exec($ch);

// Extract CSRF
preg_match('/name="_token" value="([^"]+)"/', $loginHtml, $matches);
$csrf = $matches[1] ?? '';

echo "CSRF: $csrf\n";

// 2. POST /login
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    '_token' => $csrf,
    'email' => 'siswa1@siswa.com',
    'password' => 'password',
]));
curl_setopt($ch, CURLOPT_HEADER, true);
$loginResp = curl_exec($ch);
echo "Login Status: " . curl_getinfo($ch, CURLINFO_HTTP_CODE) . "\n";

// 3. POST /siswa/ujian/1/mulai
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/siswa/ujian/1/mulai');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    '_token' => $csrf,
]));
$mulaiResp = curl_exec($ch);
echo "Mulai Status: " . curl_getinfo($ch, CURLINFO_HTTP_CODE) . "\n";

// Extract Location
preg_match('/[Ll]ocation: (.*)/', $mulaiResp, $matches);
$location = trim($matches[1] ?? '');
echo "Location Header: $location\n";

if ($location) {
    // 4. GET /soal/1
    curl_setopt($ch, CURLOPT_URL, $location);
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $soalResp = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    echo "Soal Status: $status\n";
    if ($status == 500) {
        echo "500 Response Body:\n";
        echo substr(strip_tags($soalResp), 0, 1000);
    }
} else {
    echo "NO LOCATION HEADER!\n";
}

curl_close($ch);
