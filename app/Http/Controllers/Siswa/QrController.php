<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PesertaDidik;
use Illuminate\Support\Facades\Auth;

class QrController extends Controller
{
    // ── Halaman QR Siswa ──────────────────────────────────────
    public function show()
    {
        $peserta = $this->getPeserta();
        $absensiToday = \App\Models\Absensi::where('peserta_didik_id', $peserta->id)
            ->where('tanggal', now()->toDateString())
            ->first();
            
        return view('siswa.qr', compact('peserta', 'absensiToday'));
    }

    // ── API: Cek Status Absensi (polling JS) ──────────────────
    public function status()
    {
        $peserta = $this->getPeserta();
        $absensi = \App\Models\Absensi::where('peserta_didik_id', $peserta->id)
            ->where('tanggal', now()->toDateString())
            ->first();

        return response()->json([
            'jam_masuk'  => $absensi->jam_masuk ?? null,
            'jam_pulang' => $absensi->jam_pulang ?? null,
        ]);
    }

    // ── API: Token QR baru (dipanggil tiap 30 detik dari JS) ──
    public function token()
    {
        $peserta = $this->getPeserta();

        $timeBlock = (int) floor(time() / 30);
        $hmac      = $this->buildHmac($peserta->nisn, $timeBlock);
        $expiresIn = 30 - (time() % 30); // detik tersisa di blok ini

        return response()->json([
            'qr_content' => $peserta->nisn . '|' . $timeBlock . '|' . $hmac,
            'expires_in' => $expiresIn,
            'nama'       => $peserta->nama_lengkap,
            'nisn'       => $peserta->nisn,
        ]);
    }

    // ── Shared: Build HMAC token ───────────────────────────────
    public static function buildHmac(string $nisn, int $timeBlock): string
    {
        return substr(hash_hmac('sha256', $nisn . '|' . $timeBlock, self::secret()), 0, 16);
    }

    // ── Shared: Validate QR token, return NISN or null ────────
    public static function validateToken(string $raw): ?string
    {
        $parts = explode('|', $raw);

        // Jika hanya plain NISN (misalnya dari barcode scanner atau input manual)
        if (count($parts) === 1) {
            return $parts[0];
        }

        // Format lama atau format salah
        if (count($parts) !== 3) {
            return null;
        }

        [$nisn, $timeBlock, $hmac] = $parts;
        $timeBlock = (int) $timeBlock;

        // Cek window waktu: max 2 blok = 60 detik toleransi
        $currentBlock = (int) floor(time() / 30);
        if (abs($currentBlock - $timeBlock) > 2) {
            return null; // Token kedaluwarsa
        }

        // Verifikasi HMAC (constant-time compare)
        $expected = self::buildHmac($nisn, $timeBlock);
        if (!hash_equals($expected, $hmac)) {
            return null; // Token palsu
        }

        return $nisn;
    }

    // ── Private helpers ────────────────────────────────────────
    private function getPeserta(): PesertaDidik
    {
        $user    = Auth::user();
        $peserta = PesertaDidik::find($user->peserta_didik_id);

        if (!$peserta) {
            abort(403, 'Data peserta tidak ditemukan.');
        }

        return $peserta;
    }

    private static function secret(): string
    {
        $key = config('app.key');
        if (str_starts_with($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }
        return $key;
    }
}
