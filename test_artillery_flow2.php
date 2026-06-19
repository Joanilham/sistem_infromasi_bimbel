<?php

$cookieJar = tempnam(sys_get_temp_dir(), 'cookies');
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

$baseUrl = 'http://geniusedu-nginx:8080';

curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
$loginHtml = curl_exec($ch);
preg_match('/name="_token" value="([^"]+)"/', $loginHtml, $matches);
$csrf = $matches[1] ?? '';

curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    '_token' => $csrf,
    'email' => 'loadtest1@example.com',
    'password' => 'password',
]));
curl_setopt($ch, CURLOPT_HEADER, true);
$loginResp = curl_exec($ch);
preg_match('/[Ll]ocation: (.*)/', $loginResp, $matches);
echo "Login Redirect: " . trim($matches[1] ?? '') . "\n";

// Now test mulai
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/siswa/ujian/1/mulai');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    '_token' => $csrf,
]));
$mulaiResp = curl_exec($ch);
preg_match('/[Ll]ocation: (.*)/', $mulaiResp, $matches);
$location = trim($matches[1] ?? '');
echo "Mulai Redirect: " . $location . "\n";

curl_close($ch);
