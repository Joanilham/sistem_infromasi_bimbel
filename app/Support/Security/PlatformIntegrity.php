<?php

namespace App\Support\Security;

use Illuminate\Support\Facades\File;

class PlatformIntegrity
{
    /**
     * Kunci kriptografi rahasia untuk penandatanganan lisensi (HMAC-SHA256).
     * Jangan pernah dibagikan kepada siapapun.
     */
    protected const INTEGRITY_SALT = 'Joanilham_Bimbel_Platform_Secure_Salt_2026_@#9821_Integrity';

    /**
     * Cache hasil verifikasi dalam siklus satu request.
     */
    protected static ?array $cachedVerification = null;

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
     * Ambil raw key lisensi dari .env atau file storage.
     */
    public static function getRawKey(): ?string
    {
        // 1. Cek dari env / config
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

        // 2. Cek dari file storage lokal
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
     * Validasi lisensi platform.
     *
     * @param string|null $customKey
     * @return array ['valid' => bool, 'reason' => string, 'payload' => array|null]
     */
    public static function verify(?string $customKey = null): array
    {
        if ($customKey === null && self::$cachedVerification !== null) {
            return self::$cachedVerification;
        }

        $rawKey = $customKey !== null ? $customKey : self::getRawKey();

        if (empty($rawKey)) {
            $result = [
                'valid' => false,
                'reason' => 'Lisensi sistem belum diaktifkan.',
                'payload' => null,
            ];
            if ($customKey === null) {
                self::$cachedVerification = $result;
            }
            return $result;
        }

        // Format key: base64(payload) . "." . hmac_signature
        $parts = explode('.', $rawKey);
        if (count($parts) !== 2) {
            $result = [
                'valid' => false,
                'reason' => 'Format kunci lisensi tidak valid.',
                'payload' => null,
            ];
            if ($customKey === null) {
                self::$cachedVerification = $result;
            }
            return $result;
        }

        [$payloadBase64, $signature] = $parts;

        // Verifikasi tanda tangan digital HMAC-SHA256
        $expectedSignature = hash_hmac('sha256', $payloadBase64, self::INTEGRITY_SALT);
        if (!hash_equals($expectedSignature, $signature)) {
            $result = [
                'valid' => false,
                'reason' => 'Tanda tangan digital kunci lisensi tidak valid atau telah dimodifikasi.',
                'payload' => null,
            ];
            if ($customKey === null) {
                self::$cachedVerification = $result;
            }
            return $result;
        }

        // Decode payload
        $json = base64_decode($payloadBase64, true);
        if ($json === false) {
            $result = [
                'valid' => false,
                'reason' => 'Payload lisensi rusak.',
                'payload' => null,
            ];
            if ($customKey === null) {
                self::$cachedVerification = $result;
            }
            return $result;
        }

        $payload = json_decode($json, true);
        if (!is_array($payload) || !isset($payload['app'], $payload['expires_at'])) {
            $result = [
                'valid' => false,
                'reason' => 'Data lisensi tidak lengkap.',
                'payload' => null,
            ];
            if ($customKey === null) {
                self::$cachedVerification = $result;
            }
            return $result;
        }

        // Pastikan aplikasi sesuai
        if ($payload['app'] !== 'sistem_informasi_bimbel') {
            $result = [
                'valid' => false,
                'reason' => 'Kunci lisensi ini tidak diperuntukkan bagi sistem ini.',
                'payload' => null,
            ];
            if ($customKey === null) {
                self::$cachedVerification = $result;
            }
            return $result;
        }

        // Periksa tanggal kedaluwarsa
        $expiresAt = $payload['expires_at'];
        if ($expiresAt !== 'lifetime') {
            $currentDate = date('Y-m-d');
            if ($currentDate > $expiresAt) {
                $result = [
                    'valid' => false,
                    'reason' => 'Masa berlaku lisensi telah berakhir pada tanggal ' . date('d F Y', strtotime($expiresAt)) . '. Silakan lakukan perpanjangan lisensi.',
                    'payload' => $payload,
                ];
                if ($customKey === null) {
                    self::$cachedVerification = $result;
                }
                return $result;
            }
        }

        // Periksa batasan domain (opsional)
        if (!empty($payload['domain']) && $payload['domain'] !== '*') {
            $host = 'localhost';
            if (function_exists('request') && request()) {
                $host = request()->getHost();
            } elseif (!empty($_SERVER['HTTP_HOST'])) {
                $host = parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST) ?: $_SERVER['HTTP_HOST'];
            }

            $allowedDomains = array_map('trim', explode(',', $payload['domain']));
            if (!in_array($host, $allowedDomains, true) && !in_array('localhost', $allowedDomains, true) && $host !== '127.0.0.1') {
                $result = [
                    'valid' => false,
                    'reason' => "Lisensi ini hanya sah untuk domain [{$payload['domain']}], bukan untuk host [{$host}].",
                    'payload' => $payload,
                ];
                if ($customKey === null) {
                    self::$cachedVerification = $result;
                }
                return $result;
            }
        }

        $result = [
            'valid' => true,
            'reason' => 'Lisensi valid.',
            'payload' => $payload,
        ];

        if ($customKey === null) {
            self::$cachedVerification = $result;
        }

        return $result;
    }

    /**
     * Simpan key lisensi baru.
     *
     * @param string $key
     * @return array
     */
    public static function saveKey(string $key): array
    {
        $key = trim($key);
        $verify = self::verify($key);

        if (!$verify['valid']) {
            return [
                'success' => false,
                'message' => $verify['reason'],
            ];
        }

        // Simpan ke storage fallback
        $path = self::getStoragePath();
        $dir = dirname($path);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        @file_put_contents($path, $key);

        // Coba perbarui .env jika bisa ditulis
        $envPath = function_exists('base_path') ? base_path('.env') : (dirname(__DIR__, 3) . '/.env');
        if (file_exists($envPath) && is_writable($envPath)) {
            $envContent = (string) @file_get_contents($envPath);
            if (str_contains($envContent, 'PLATFORM_INTEGRITY_KEY=')) {
                $envContent = preg_replace('/PLATFORM_INTEGRITY_KEY=.*/', 'PLATFORM_INTEGRITY_KEY="' . $key . '"', $envContent);
            } else {
                $envContent .= "\nPLATFORM_INTEGRITY_KEY=\"" . $key . "\"\n";
            }
            @file_put_contents($envPath, $envContent);
        }

        // Reset cache verifikasi
        self::$cachedVerification = null;

        return [
            'success' => true,
            'message' => 'Lisensi berhasil diperbarui. Masa aktif berlaku hingga: ' . ($verify['payload']['expires_at'] === 'lifetime' ? 'Selamanya (Lifetime)' : date('d F Y', strtotime($verify['payload']['expires_at']))),
            'payload' => $verify['payload'],
        ];
    }

    /**
     * Buat token lisensi baru (digunakan oleh generator offline).
     */
    public static function generate(string $client, string $expiresAt, string $domain = '*'): string
    {
        $payload = [
            'app' => 'sistem_informasi_bimbel',
            'client' => $client,
            'issued_at' => date('Y-m-d H:i:s'),
            'expires_at' => $expiresAt, // YYYY-MM-DD atau 'lifetime'
            'domain' => $domain,
        ];

        $payloadBase64 = base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', $payloadBase64, self::INTEGRITY_SALT);

        return $payloadBase64 . '.' . $signature;
    }
}
