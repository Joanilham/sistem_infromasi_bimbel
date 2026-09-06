<?php

namespace App\Support\Security;

class PlatformIntegrity
{
    /**
     * Konfigurasi Default Supabase Project milik Joan Ilham
     */
    protected const DEFAULT_SUPABASE_URL = 'https://izxmmncksjdnrehxatsm.supabase.co';
    protected const DEFAULT_SUPABASE_KEY = '';

    /**
     * Durasi cache lisensi (detik).
     * 15 detik: website tetap sangat cepat (0ms per request), dan responsif terhadap tombol Kill-Switch!
     */
    protected const CACHE_TTL = 15;

    /**
     * Salt offline fallback (asymmetric/HMAC)
     */
    protected const INTEGRITY_SALT = 'Joanilham_Bimbel_Platform_Secure_Salt_2026_@#9821_Integrity';

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

        // 3. Cek File Cache Lokal — jika masih di dalam rentang CACHE_TTL detik
        $cacheFile = self::getCachePath();
        if (!$forceRefresh && file_exists($cacheFile)) {
            $cacheData = json_decode((string) @file_get_contents($cacheFile), true);
            if (is_array($cacheData) && isset($cacheData['status'], $cacheData['cached_at'])) {
                // Jika data cache adalah suspended, langsung tolak!
                if ($cacheData['status'] !== 'active') {
                    $result = [
                        'valid' => false,
                        'reason' => 'Status lisensi Anda adalah ' . strtoupper($cacheData['status']) . '. Akses sistem telah dinonaktifkan oleh administrator.',
                        'installation_id' => $installationId,
                        'data' => $cacheData,
                    ];
                    self::$cachedVerification = $result;
                    self::$lastCheckedAt = $now;
                    return $result;
                }

                // Jika cache masih segar (< CACHE_TTL detik)
                if (($now - $cacheData['cached_at']) < self::CACHE_TTL) {
                    $currentDate = date('Y-m-d');
                    if ($cacheData['expires_at'] === 'lifetime' || $currentDate <= $cacheData['expires_at']) {
                        $result = [
                            'valid' => true,
                            'reason' => 'Lisensi aktif (cached)',
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

        // 4. Query langsung ke Supabase Cloud
        $supabaseResult = self::querySupabase($installationId);

        if ($supabaseResult['success']) {
            $row = $supabaseResult['data'];

            if (!$row) {
                // Hapus cache lama jika data tidak ditemukan
                self::clearCache();
                $result = [
                    'valid' => false,
                    'reason' => 'Perangkat/Instansi ini belum terdaftar di otorisasi Supabase. Berikan Installation ID kepada Joan Ilham.',
                    'installation_id' => $installationId,
                    'data' => null,
                ];
                self::$cachedVerification = $result;
                self::$lastCheckedAt = $now;
                return $result;
            }

            $status = strtolower($row['status'] ?? 'pending');
            $expiresAt = $row['expires_at'] ?? '2000-01-01';
            $currentDate = date('Y-m-d');

            // Simpan status terbaru ke cache file
            $row['cached_at'] = $now;
            @file_put_contents($cacheFile, json_encode($row));

            // Jika status BUKAN active (misal: suspended atau expired)
            if ($status !== 'active') {
                $result = [
                    'valid' => false,
                    'reason' => 'Status lisensi Anda saat ini adalah ' . strtoupper($status) . '. Akses platform dinonaktifkan oleh administrator.',
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
                    'reason' => 'Masa berlaku lisensi telah berakhir pada ' . date('d F Y', strtotime($expiresAt)) . '. Silakan hubungi Joan Ilham untuk perpanjangan.',
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
            $cacheData = json_decode((string) @file_get_contents($cacheFile), true);
            if (is_array($cacheData) && isset($cacheData['cached_at'], $cacheData['status'])) {
                if ($cacheData['status'] === 'active' && ($now - $cacheData['cached_at']) < 86400) {
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
            'Accept: application/json',
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($ch);
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
     * Validasi key offline (HMAC) jika digunakan.
     */
    public static function verifyOfflineKey(string $rawKey): array
    {
        $parts = explode('.', $rawKey);
        if (count($parts) !== 2) {
            return ['valid' => false, 'reason' => 'Format kunci tidak valid.'];
        }

        [$payloadBase64, $signature] = $parts;
        $expectedSignature = hash_hmac('sha256', $payloadBase64, self::INTEGRITY_SALT);

        if (!hash_equals($expectedSignature, $signature)) {
            return ['valid' => false, 'reason' => 'Tanda tangan digital tidak valid.'];
        }

        $payload = json_decode(base64_decode($payloadBase64, true), true);
        if (!is_array($payload) || !isset($payload['app'], $payload['expires_at'])) {
            return ['valid' => false, 'reason' => 'Data lisensi rusak.'];
        }

        if ($payload['expires_at'] !== 'lifetime' && date('Y-m-d') > $payload['expires_at']) {
            return ['valid' => false, 'reason' => 'Lisensi offline telah kadaluarsa.'];
        }

        return ['valid' => true, 'reason' => 'Lisensi offline valid.', 'data' => $payload];
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
