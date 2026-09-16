<?php

namespace App\Support\Security;

class PlatformIntegrity
{
    /**
     * Konfigurasi Default Supabase Project milik Joan Ilham
     */
    protected const DEFAULT_SUPABASE_URL = 'https://izxmmncksjdnrehxatsm.supabase.co';
    protected const DEFAULT_SUPABASE_KEY = 'sb_publishable_-2l-w7dr5aNglAOpHDNGxQ_pslZb7ga';

    /**
     * Durasi cache lisensi (detik).
     * 15 detik: website tetap sangat cepat (0ms per request), dan responsif terhadap tombol Kill-Switch!
     */
    protected const CACHE_TTL = 15;

    /**
     * Kunci Publik RSA 2048-bit untuk verifikasi lisensi offline.
     * Kunci privat HANYA dipegang oleh Joan Ilham (tools/keys/license_private_key.pem).
     * Secara matematis MUSTAHIL dipalsukan tanpa kunci privat.
     */
    protected const RSA_PUBLIC_KEY = "-----BEGIN PUBLIC KEY-----\n"
        . "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAzjp2A4ARqB4i9+M9B7ov\n"
        . "OI6PB8ykYeD9fBzdiJhp0rrhBGgX3GKB1Oxbxkbn2u/POQug7i4cm+tp/HzBXI8F\n"
        . "Q1xMrhRXcWcZnBcWVfa91+yTqTkGlBk2Erz0U7HGbNL3XwZso3OD7iHP7+bdX6kI\n"
        . "uvmxS/sYORZIjKXuwGHbhjsi0CmmsEObTJVKls5YDA/HhYSSaLAKMx+jPBWeHx0Q\n"
        . "Q0qRweBU6z7vLuGKjjTgS2M6Cb7F+9qlT6J359qvUEXQep+oFvyhTCJJQ7oIxrvX\n"
        . "n5TpGZB+mPBZlmZEkrdbZ3IPnXSRgX2VsT/ObggktgOQYN02NGOcy/jfG16QSkvH\n"
        . "JwIDAQAB\n"
        . "-----END PUBLIC KEY-----";

    /**
     * Rahasia lokal penandatangan cache untuk mencegah manipulasi timestamp / status secara offline.
     */
    protected static function getCacheSecret(): string
    {
        return hash('sha256', self::getInstallationId() . '|' . php_uname('s') . '|' . php_uname('m') . '|' . __DIR__ . 'Seal#2026@Integrity');
    }

    /**
     * Cache in-memory dalam siklus RoadRunner Octane dengan timestamp
     */
    protected static int $lastCheckedAt = 0;
    protected static ?array $cachedVerification = null;

    /**
     * Ambil atau buat unik Installation ID untuk instansi ini.
     * Tersimpan permanen di storage agar ID tidak berubah-ubah.
     */
    public static function getInstallationId(): string
    {
        $dir = dirname(self::getStoragePath());
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $idPath = $dir . '/installation_id.key';
        if (file_exists($idPath)) {
            $id = trim((string) @file_get_contents($idPath));
            if (!empty($id)) {
                return $id;
            }
        }

        // Generate ID unik format: BIMBEL-XXXX-XXXX-XXXX
        $seed = php_uname() . '|' . (__DIR__) . '|' . microtime(true) . '|' . random_bytes(16);
        $hash = strtoupper(hash('sha256', $seed));
        $id = 'BIMBEL-' . substr($hash, 0, 4) . '-' . substr($hash, 4, 4) . '-' . substr($hash, 8, 4);

        @file_put_contents($idPath, $id);
        return $id;
    }

    /**
     * Path file fallback penyimpanan key lisensi.
     */
    public static function getStoragePath(): string
    {
        if (function_exists('storage_path')) {
            return storage_path('framework/cache/integrity.dat');
        }
        return dirname(__DIR__, 3) . '/storage/framework/cache/integrity.dat';
    }

    /**
     * Path file cache status Supabase lokal.
     */
    public static function getCachePath(): string
    {
        $dir = dirname(self::getStoragePath());
        return $dir . '/platform_status.json';
    }

    /**
     * Hapus cache agar aplikasi langsung memeriksa ulang ke Supabase seketika.
     */
    public static function clearCache(): void
    {
        self::$cachedVerification = null;
        self::$lastCheckedAt = 0;
        $cacheFile = self::getCachePath();
        if (file_exists($cacheFile)) {
            @unlink($cacheFile);
        }
    }

    /**
     * Ambil URL Supabase dari .env atau default.
     */
    public static function getSupabaseUrl(): string
    {
        $url = null;
        if (function_exists('env')) {
            $url = env('SUPABASE_LICENSE_URL');
        }
        if (empty($url)) {
            $url = getenv('SUPABASE_LICENSE_URL');
        }
        return rtrim($url ?: self::DEFAULT_SUPABASE_URL, '/');
    }

    /**
     * Ambil Anon API Key Supabase dari .env atau default.
     */
    public static function getSupabaseKey(): string
    {
        $key = null;
        if (function_exists('env')) {
            $key = env('SUPABASE_LICENSE_KEY');
        }
        if (empty($key)) {
            $key = getenv('SUPABASE_LICENSE_KEY');
        }
        return trim((string) ($key ?: self::DEFAULT_SUPABASE_KEY));
    }

    /**
     * Ambil raw offline key dari .env atau file storage lokal.
     */
    public static function getRawKey(): ?string
    {
        $key = null;
        if (function_exists('env')) {
            $key = env('PLATFORM_INTEGRITY_KEY') ?: env('APP_LICENSE_KEY');
        }
        if (empty($key)) {
            $key = getenv('PLATFORM_INTEGRITY_KEY') ?: getenv('APP_LICENSE_KEY');
        }

        if (!empty($key)) {
            return trim((string) $key);
        }

        $path = self::getStoragePath();
        if (file_exists($path)) {
            $content = trim((string) @file_get_contents($path));
            if (!empty($content)) {
                return $content;
            }
        }

        return null;
    }

    /**
     * Verifikasi Lisensi Platform (Supabase Online + Offline Fallback).
     *
     * @param bool $forceRefresh
     * @return array
     */
    public static function verify(bool $forceRefresh = false): array
    {
        $now = time();

        // 1. Cek in-memory cache (Octane RoadRunner) — valid selama CACHE_TTL detik
        if (!$forceRefresh && self::$cachedVerification !== null && ($now - self::$lastCheckedAt) < self::CACHE_TTL) {
            return self::$cachedVerification;
        }

        $installationId = self::getInstallationId();

        // 2. Cek Offline Master Key (Jika ada di .env)
        $offlineKey = self::getRawKey();
        if (!empty($offlineKey)) {
            $offlineVerify = self::verifyOfflineKey($offlineKey);
            if ($offlineVerify['valid']) {
                $offlineVerify['installation_id'] = $installationId;
                self::$cachedVerification = $offlineVerify;
                self::$lastCheckedAt = $now;
                return $offlineVerify;
            }
        }

        // 3. Cek File Cache Lokal — diverifikasi tanda tangan anti-tamper
        $cacheFile = self::getCachePath();
        if (!$forceRefresh && file_exists($cacheFile)) {
            $rawCache = @file_get_contents($cacheFile);
            $cacheEnvelope = json_decode((string) $rawCache, true);

            if (is_array($cacheEnvelope) && isset($cacheEnvelope['data'], $cacheEnvelope['sig'])) {
                $expectedSig = hash_hmac('sha256', json_encode($cacheEnvelope['data']), self::getCacheSecret());

                // Jika tanda tangan rusak/diedit manual: tolak dan hancurkan cache
                if (!hash_equals($expectedSig, $cacheEnvelope['sig'])) {
                    self::clearCache();
                    $result = [
                        'valid' => false,
                        'reason' => 'Integritas cache status sistem rusak atau telah dimanipulasi secara ilegal.',
                        'installation_id' => $installationId,
                        'data' => null,
                    ];
                    self::$cachedVerification = $result;
                    self::$lastCheckedAt = $now;
                    return $result;
                }

                $cacheData = $cacheEnvelope['data'];
                if (isset($cacheData['status'], $cacheData['cached_at'])) {
                    // Periksa apakah cache masih dalam rentang validitas CACHE_TTL
                    if (($now - $cacheData['cached_at']) < self::CACHE_TTL) {
                        // Jika data cache adalah status selain active (pending / suspended), tolak cepat (0ms)
                        if ($cacheData['status'] !== 'active') {
                            $reason = match ($cacheData['status']) {
                                'pending' => 'Perangkat ini sedang menunggu persetujuan otorisasi dari Administrator. Silakan hubungi Admin untuk aktivasi lisensi.',
                                'suspended' => 'Status lisensi Anda telah DITANGGUHKAN (SUSPENDED). Akses platform dinonaktifkan oleh administrator.',
                                default => 'Status lisensi Anda adalah ' . strtoupper($cacheData['status']) . '. Akses platform dinonaktifkan oleh administrator.',
                            };

                            $result = [
                                'valid' => false,
                                'reason' => $reason,
                                'installation_id' => $installationId,
                                'data' => $cacheData,
                            ];
                            self::$cachedVerification = $result;
                            self::$lastCheckedAt = $now;
                            return $result;
                        }

                        // Jika status active, periksa tanggal kadaluarsa
                        $currentDate = date('Y-m-d');
                        if ($cacheData['expires_at'] === 'lifetime' || $currentDate <= $cacheData['expires_at']) {
                            $result = [
                                'valid' => true,
                                'reason' => 'Lisensi resmi aktif.',
                                'installation_id' => $installationId,
                                'data' => $cacheData,
                            ];
                            self::$cachedVerification = $result;
                            self::$lastCheckedAt = $now;
                            return $result;
                        }
                    }
                }
            }
        }

        // 4. Query langsung ke Supabase Cloud
        $supabaseResult = self::querySupabase($installationId);

        if ($supabaseResult['success']) {
            $row = $supabaseResult['data'];

            if (!$row) {
                // Perangkat belum terdaftar: Lakukan auto-registration siluman
                $regResult = self::registerDevice($installationId);
                if ($regResult['success'] && !empty($regResult['data'])) {
                    $row = $regResult['data'];
                } else {
                    // Hapus cache lama jika registrasi belum diizinkan atau gagal
                    self::clearCache();
                    $result = [
                        'valid' => false,
                        'reason' => 'Perangkat/Instansi ini belum terdaftar di otorisasi Supabase. Berikan Installation ID kepada Admin.',
                        'installation_id' => $installationId,
                        'data' => null,
                    ];
                    self::$cachedVerification = $result;
                    self::$lastCheckedAt = $now;
                    return $result;
                }
            }

            // Perbarui waktu aktivitas perangkat secara berkala
            self::pingDevice($installationId);

            $status = strtolower($row['status'] ?? 'pending');
            $expiresAt = $row['expires_at'] ?? '2000-01-01';
            $currentDate = date('Y-m-d');

            // Simpan status terbaru ke cache file lengkap dengan tanda tangan digital anti-tamper
            $row['cached_at'] = $now;
            $envelope = [
                'data' => $row,
                'sig'  => hash_hmac('sha256', json_encode($row), self::getCacheSecret()),
            ];
            @file_put_contents($cacheFile, json_encode($envelope));

            // Jika status BUKAN active (misal: pending atau suspended)
            if ($status !== 'active') {
                $reason = match ($status) {
                    'pending' => 'Perangkat ini sedang menunggu persetujuan otorisasi dari Administrator. Silakan berikan Installation ID di bawah kepada Admin.',
                    'suspended' => 'Status lisensi Anda telah DITANGGUHKAN (SUSPENDED). Akses platform dinonaktifkan oleh administrator.',
                    default => 'Status lisensi Anda saat ini adalah ' . strtoupper($status) . '. Akses platform dinonaktifkan oleh administrator.',
                };

                $result = [
                    'valid' => false,
                    'reason' => $reason,
                    'installation_id' => $installationId,
                    'data' => $row,
                ];
                self::$cachedVerification = $result;
                self::$lastCheckedAt = $now;
                return $result;
            }

            // Jika tanggal kadaluarsa lewat
            if ($expiresAt !== 'lifetime' && $currentDate > $expiresAt) {
                $result = [
                    'valid' => false,
                    'reason' => 'Masa berlaku lisensi telah berakhir pada ' . date('d F Y', strtotime($expiresAt)) . '. Silakan hubungi Admin untuk perpanjangan.',
                    'installation_id' => $installationId,
                    'data' => $row,
                ];
                self::$cachedVerification = $result;
                self::$lastCheckedAt = $now;
                return $result;
            }

            $result = [
                'valid' => true,
                'reason' => 'Lisensi resmi aktif.',
                'installation_id' => $installationId,
                'data' => $row,
            ];
            self::$cachedVerification = $result;
            self::$lastCheckedAt = $now;
            return $result;
        }

        // 5. Offline Grace Period (hanya berlaku jika koneksi internet terputus DAN status terakhir adalah active)
        if (file_exists($cacheFile)) {
            $rawCache = @file_get_contents($cacheFile);
            $cacheEnvelope = json_decode((string) $rawCache, true);
            if (is_array($cacheEnvelope) && isset($cacheEnvelope['data'], $cacheEnvelope['sig'])) {
                $expectedSig = hash_hmac('sha256', json_encode($cacheEnvelope['data']), self::getCacheSecret());
                if (hash_equals($expectedSig, $cacheEnvelope['sig'])) {
                    $cacheData = $cacheEnvelope['data'];
                    if (isset($cacheData['cached_at'], $cacheData['status']) && $cacheData['status'] === 'active') {
                        if (($now - $cacheData['cached_at']) < 86400) {
                            $result = [
                                'valid' => true,
                                'reason' => 'Lisensi aktif (Grace Period offline mode)',
                                'installation_id' => $installationId,
                                'data' => $cacheData,
                            ];
                            self::$cachedVerification = $result;
                            self::$lastCheckedAt = $now;
                            return $result;
                        }
                    }
                }
            }
        }

        $result = [
            'valid' => false,
            'reason' => $supabaseResult['message'] ?: 'Sistem lisensi belum diaktifkan.',
            'installation_id' => $installationId,
            'data' => null,
        ];
        self::$cachedVerification = $result;
        self::$lastCheckedAt = $now;
        return $result;
    }

    /**
     * Query data lisensi ke Supabase REST API via cURL.
     */
    protected static function querySupabase(string $installationId): array
    {
        $baseUrl = self::getSupabaseUrl();
        $apiKey = self::getSupabaseKey();

        if (empty($apiKey)) {
            return [
                'success' => false,
                'data' => null,
                'message' => 'Instansi sistem ini belum terhubung ke jaringan otorisasi resmi. Silakan hubungi Administrator Pengembang untuk aktivasi.',
            ];
        }

        $url = $baseUrl . '/rest/v1/licenses?installation_id=eq.' . urlencode($installationId) . '&select=*';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'apikey: ' . $apiKey,
            'Authorization: Bearer ' . $apiKey,
            'x-installation-id: ' . $installationId,
            'Accept: application/json',
            'Content-Type: application/json',
        ]);
        self::configureCurlSsl($ch);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = self::execCurlWithFallback($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError || $httpCode < 200 || $httpCode >= 300) {
            return [
                'success' => false,
                'data' => null,
                'message' => 'Gagal menghubungi server Supabase (HTTP ' . $httpCode . '): ' . $curlError,
            ];
        }

        $rows = json_decode($response, true);
        if (!is_array($rows)) {
            return [
                'success' => false,
                'data' => null,
                'message' => 'Format respons server tidak valid.',
            ];
        }

        return [
            'success' => true,
            'data' => !empty($rows) ? $rows[0] : null,
            'message' => 'Berhasil mengambil data.',
        ];
    }

    /**
     * Mendaftarkan perangkat baru secara otomatis dan diam-diam ke Supabase.
     */
    protected static function registerDevice(string $installationId): array
    {
        $baseUrl = self::getSupabaseUrl();
        $apiKey = self::getSupabaseKey();

        if (empty($apiKey)) {
            return [
                'success' => false,
                'data' => null,
                'message' => 'Kunci otorisasi tidak ditemukan.',
            ];
        }

        $identity = self::resolveDeviceIdentity();

        $payload = [
            'installation_id' => $installationId,
            'client_name'     => $identity['client_name'],
            'location'        => $identity['location'],
            'ip_address'      => $identity['ip_address'],
            'domain'          => $identity['domain'],
            'status'          => 'pending',
            'expires_at'      => '2000-01-01',
            'last_ping'       => date('c'),
        ];

        $url = $baseUrl . '/rest/v1/licenses';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'apikey: ' . $apiKey,
            'Authorization: Bearer ' . $apiKey,
            'x-installation-id: ' . $installationId,
            'Accept: application/json',
            'Content-Type: application/json',
            'Prefer: return=representation',
        ]);
        self::configureCurlSsl($ch);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = self::execCurlWithFallback($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError || $httpCode < 200 || $httpCode >= 300) {
            // Jika kolom location / ip_address belum dibuat di Supabase, fallback otomatis ke format gabungan
            if (str_contains((string) $response, 'location') || str_contains((string) $response, 'ip_address')) {
                $fallbackPayload = [
                    'installation_id' => $installationId,
                    'client_name'     => $identity['client_name'] . ' — ' . $identity['location'] . ' — IP: ' . $identity['ip_address'],
                    'status'          => 'pending',
                    'expires_at'      => '2000-01-01',
                    'domain'          => $identity['domain'],
                    'last_ping'       => date('c'),
                ];
                $chRetry = curl_init();
                curl_setopt($chRetry, CURLOPT_URL, $url);
                curl_setopt($chRetry, CURLOPT_POST, true);
                curl_setopt($chRetry, CURLOPT_POSTFIELDS, json_encode($fallbackPayload));
                curl_setopt($chRetry, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($chRetry, CURLOPT_TIMEOUT, 4);
                curl_setopt($chRetry, CURLOPT_CONNECTTIMEOUT, 2);
                curl_setopt($chRetry, CURLOPT_HTTPHEADER, [
                    'apikey: ' . $apiKey,
                    'Authorization: Bearer ' . $apiKey,
                    'x-installation-id: ' . $installationId,
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'Prefer: return=representation',
                ]);
                self::configureCurlSsl($chRetry);
                curl_setopt($chRetry, CURLOPT_FOLLOWLOCATION, true);
                $response = self::execCurlWithFallback($chRetry);
                $httpCode = curl_getinfo($chRetry, CURLINFO_HTTP_CODE);
                $curlError = curl_error($chRetry);
                curl_close($chRetry);
                $payload = $fallbackPayload;
            }
        }

        if ($curlError || $httpCode < 200 || $httpCode >= 300) {
            return [
                'success' => false,
                'data' => null,
                'message' => 'Gagal mendaftarkan node baru (HTTP ' . $httpCode . '): ' . $curlError,
            ];
        }

        $rows = json_decode($response, true);
        if (is_array($rows) && !empty($rows)) {
            return [
                'success' => true,
                'data' => $rows[0],
                'message' => 'Perangkat berhasil terdaftar otomatis.',
            ];
        }

        if ($httpCode === 201) {
            return [
                'success' => true,
                'data' => $payload,
                'message' => 'Perangkat terdaftar.',
            ];
        }

        return [
            'success' => false,
            'data' => null,
            'message' => 'Respons server tidak valid.',
        ];
    }

    /**
     * Dapatkan identitas perangkat cerdas: Nama Aplikasi/Host, Geolocation (Kota/Negara), IP, dan Domain.
     */
    protected static function resolveDeviceIdentity(): array
    {
        // 1. Nama Aplikasi / Klien
        $appName = null;
        if (function_exists('config')) {
            $appName = config('app.name');
        }
        if (empty($appName) && function_exists('env')) {
            $appName = env('CLIENT_NAME') ?: env('APP_NAME');
        }
        if (empty($appName)) {
            $appName = getenv('CLIENT_NAME') ?: (getenv('APP_NAME') ?: 'Sistem Edukasi');
        }

        // 2. Domain / Host yang sedang diakses (prioritaskan real domain jika HTTP_HOST localhost)
        $domain = '*';
        $httpHost = $_SERVER['HTTP_HOST'] ?? '';
        if (!empty($httpHost) && $httpHost !== 'localhost' && !str_starts_with($httpHost, '127.') && !str_starts_with($httpHost, '0.0.0.0')) {
            $domain = $httpHost;
        } elseif (function_exists('config') && config('app.url')) {
            $parsed = parse_url(config('app.url'), PHP_URL_HOST);
            if ($parsed) {
                $domain = $parsed;
            }
        } elseif (function_exists('env') && env('APP_URL')) {
            $parsed = parse_url(env('APP_URL'), PHP_URL_HOST);
            if ($parsed) {
                $domain = $parsed;
            }
        }

        // 3. Deteksi Info Sistem & VPS Specs
        $cores = @shell_exec('nproc');
        $cpuInfo = $cores ? trim((string) $cores) . ' vCPU' : '';

        // 4. Deteksi Lokasi Publik, ISP & Hosting Provider via GeoIP
        $location = 'Malang, Indonesia';
        $ip = '38.103.171.82';
        $ispName = 'Jagoan Hosting Indonesia';
        try {
            $ch = curl_init('http://ip-api.com/json/?fields=status,country,city,query,org,isp');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            $geoResponse = curl_exec($ch);
            curl_close($ch);

            if ($geoResponse) {
                $geo = json_decode((string) $geoResponse, true);
                if (is_array($geo) && ($geo['status'] ?? '') === 'success') {
                    $city = $geo['city'] ?? '';
                    $country = $geo['country'] ?? '';
                    $queryIp = $geo['query'] ?? '';
                    $org = $geo['org'] ?? ($geo['isp'] ?? '');
                    
                    if ($queryIp) {
                        $ip = $queryIp;
                    }
                    if ($city && $country) {
                        $location = "{$city}, {$country}";
                    } elseif ($country) {
                        $location = $country;
                    }
                    if ($org) {
                        $ispName = str_replace(['PT. ', 'PT '], '', $org);
                        $location .= " ({$ispName})";
                    }
                }
            }
        } catch (\Throwable $e) {
            // Abaikan jika offline / gagal koneksi
        }

        // 5. Susun Client Name mendetail yang mencerminkan VPS Production
        $vpsLabel = $ispName ? "VPS {$ispName}" : "VPS Production";
        $specTags = array_filter([$vpsLabel, $cpuInfo, 'Octane']);
        $clientName = "{$appName} [" . implode(' | ', $specTags) . "]";

        return [
            'client_name' => $clientName,
            'location'    => $location,
            'ip_address'  => $ip,
            'domain'      => $domain,
        ];
    }

    /**
     * Memperbarui timestamp aktivitas perangkat di Supabase secara senyap.
     */
    protected static function pingDevice(string $installationId): void
    {
        $baseUrl = self::getSupabaseUrl();
        $apiKey = self::getSupabaseKey();

        if (empty($apiKey)) {
            return;
        }

        $url = $baseUrl . '/rest/v1/licenses?installation_id=eq.' . urlencode($installationId);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'last_ping' => date('c'),
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'apikey: ' . $apiKey,
            'Authorization: Bearer ' . $apiKey,
            'x-installation-id: ' . $installationId,
            'Content-Type: application/json',
        ]);
        self::configureCurlSsl($ch);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        self::execCurlWithFallback($ch);
        curl_close($ch);
    }

    /**
     * Konfigurasi opsi SSL cURL dengan cerdas dan adaptif.
     * Mencegah kegagalan verifikasi SSL di Windows (error 20 / 60) jika CA bundle belum terkonfigurasi di php.ini.
     */
    protected static function configureCurlSsl($ch): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            if (defined('CURLSSLOPT_NATIVE_CA')) {
                curl_setopt($ch, CURLOPT_SSL_OPTIONS, CURLSSLOPT_NATIVE_CA);
            }
            $ca = ini_get('curl.cainfo') ?: ini_get('openssl.cafile');
            if (empty($ca) || !file_exists($ca)) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                return;
            }
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    }

    /**
     * Eksekusi cURL dengan penanganan toleransi otomatis (self-healing)
     * Jika terjadi kegagalan SSL (misal error 20 / 60 di lingkungan lokal tanpa CA bundle), coba sekali lagi dengan aman.
     */
    protected static function execCurlWithFallback($ch)
    {
        $response = curl_exec($ch);
        $curlError = (string) curl_error($ch);
        $errno = curl_errno($ch);

        // Jika gagal karena SSL certificate verification (error 60 / OpenSSL cert issue)
        if ($errno === 60 || ($curlError !== '' && stripos($curlError, 'certificate') !== false)) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $response = curl_exec($ch);
        }

        return $response;
    }

    /**
     * Validasi key offline menggunakan verifikasi tanda tangan digital RSA Asimetris (2048-bit).
     */
    public static function verifyOfflineKey(string $rawKey): array
    {
        $parts = explode('.', $rawKey);
        if (count($parts) !== 2) {
            return ['valid' => false, 'reason' => 'Format kunci tidak valid.'];
        }

        [$payloadBase64, $sigBase64] = $parts;
        $payloadJson = base64_decode($payloadBase64, true);
        $signature = base64_decode($sigBase64, true);

        if (!$payloadJson || !$signature) {
            return ['valid' => false, 'reason' => 'Data kunci lisensi korup.'];
        }

        $verify = openssl_verify($payloadJson, $signature, self::RSA_PUBLIC_KEY, OPENSSL_ALGO_SHA256);

        if ($verify !== 1) {
            return ['valid' => false, 'reason' => 'Tanda tangan digital RSA tidak valid (Kunci tidak resmi / dipalsukan).'];
        }

        $payload = json_decode($payloadJson, true);
        if (!is_array($payload) || !isset($payload['app'], $payload['expires_at'])) {
            return ['valid' => false, 'reason' => 'Data lisensi rusak atau tidak lengkap.'];
        }

        if ($payload['expires_at'] !== 'lifetime' && date('Y-m-d') > $payload['expires_at']) {
            return ['valid' => false, 'reason' => 'Lisensi offline telah kadaluarsa.'];
        }

        return ['valid' => true, 'reason' => 'Lisensi offline resmi aktif (Terverifikasi RSA).', 'data' => $payload];
    }

    /**
     * Pemeriksaan sekunder (Stealth Guard) untuk mencegah bypass dengan mencabut middleware.
     */
    public static function assertIntegrity(): void
    {
        if (function_exists('request')) {
            $req = request();
            if ($req && ($req->is('system/platform-verify*') || $req->is('up') || $req->is('livewire*'))) {
                return;
            }
        }

        $verification = self::verify();
        if (!$verification['valid']) {
            abort(423, 'Platform integrity validation locked: ' . ($verification['reason'] ?? ''));
        }
    }

    /**
     * Simpan key manual offline (opsional).
     */
    public static function saveKey(string $key): array
    {
        $key = trim($key);
        $verify = self::verifyOfflineKey($key);

        if (!$verify['valid']) {
            return [
                'success' => false,
                'message' => $verify['reason'],
            ];
        }

        $path = self::getStoragePath();
        $dir = dirname($path);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        @file_put_contents($path, $key);

        self::clearCache();

        return [
            'success' => true,
            'message' => 'Lisensi manual berhasil disimpan.',
        ];
    }
}
