<?php

namespace App\Models\System;
use App\Models\User;
use App\Models\Keuangan\Pemasukan;
use App\Models\System\AuditLog;
use App\Models\Akademik\PesertaDidik;
use App\Models\Keuangan\PembayaranSiswa;
use App\Models\MasterData\Bank;
use App\Models\Akademik\PaketBimbingan;
use App\Models\CBT\CbtBankSoal;
use App\Models\Pendaftaran\PendaftaranSiswa;
use App\Models\CBT\CbtUjian;
use App\Models\Akademik\KelompokBelajar;
use App\Models\Akademik\Jadwal;
use App\Models\Keuangan\TransaksiPembayaran;
use App\Models\Akademik\Absensi;
use App\Models\Keuangan\Pengeluaran;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    protected $appends = [
        'formatted_model_name',
        'formatted_user_agent',
        'description',
        'record_title',
    ];

    /**
     * Field sensitif yang TIDAK BOLEH tersimpan di audit log.
     * Mencegah kebocoran password hash, token, dan secret key.
     */
    public const SENSITIVE_FIELDS = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auditable()
    {
        return $this->morphTo();
    }

    /**
     * Method sentral untuk menulis audit log secara aman dan konsisten.
     * Digunakan oleh Auditable trait, LogAuthenticationEvents, dan logSystemEvent.
     *
     * - Menjalankan insert secara deferred (setelah response terkirim)
     * - Melakukan json_encode secara konsisten untuk bypass Eloquent cast
     * - Menangkap error agar tidak mengganggu alur utama aplikasi
     */
    public static function record(array $data): void
    {
        $auditData = [
            'user_id'        => $data['user_id'] ?? Auth::id(),
            'event'          => $data['event'],
            'auditable_type' => $data['auditable_type'],
            'auditable_id'   => $data['auditable_id'] ?? 0,
            'old_values'     => !empty($data['old_values']) ? $data['old_values'] : null,
            'new_values'     => !empty($data['new_values']) ? $data['new_values'] : null,
            'url'            => $data['url'] ?? Request::fullUrl(),
            'ip_address'     => $data['ip_address'] ?? Request::ip(),
            'user_agent'     => $data['user_agent'] ?? Request::userAgent(),
        ];

        app()->terminating(function () use ($auditData) {
            try {
                self::create($auditData);
            } catch (\Throwable $e) {
                Log::error('Audit log write failed: ' . $e->getMessage());
            }
        });
    }

    /**
     * Catat aktivitas sistem secara manual (non-Eloquent model).
     * Contoh: backup database, ubah konfigurasi, dll.
     */
    public static function logSystemEvent(string $event, string $type = 'SystemEvent', ?array $oldValues = null, ?array $newValues = null): void
    {
        self::record([
            'event'          => $event,
            'auditable_type' => $type,
            'auditable_id'   => 0,
            'old_values'     => $oldValues,
            'new_values'     => $newValues,
        ]);
    }

    /**
     * Hapus field sensitif dari array data sebelum disimpan ke audit log.
     */
    public static function sanitize(array $data): array
    {
        foreach (self::SENSITIVE_FIELDS as $field) {
            unset($data[$field]);
        }
        return $data;
    }

    /**
     * Dapatkan nama modul yang ramah pengguna.
     */
    public function getFormattedModelNameAttribute()
    {
        $baseName = class_basename($this->auditable_type);
        
        $map = [
            'PesertaDidik' => 'Peserta Didik',
            'PendaftaranSiswa' => 'Pendaftaran Murid Baru',
            'Pemasukan' => 'Uang Masuk / Pemasukan',
            'Pengeluaran' => 'Uang Keluar / Pengeluaran',
            'PembayaranSiswa' => 'Cicilan SPP Siswa',
            'TransaksiPembayaran' => 'Transaksi Finansial',
            'User' => 'Pengguna & Akun',
            'Jadwal' => 'Jadwal Kelas',
            'KelompokBelajar' => 'Kelompok Belajar',
            'PaketBimbingan' => 'Paket Bimbingan',
            'CbtUjian' => 'Ujian CBT',
            'CbtBankSoal' => 'Bank Soal CBT',
            'Absensi' => 'Kehadiran / Absensi',
            'SystemBackup' => 'Manajemen Backup Database',
            'SystemSettings' => 'Pengaturan Konfigurasi Sistem',
        ];

        return $map[$baseName] ?? $baseName;
    }

    /**
     * Dapatkan nama spesifik dari data yang diubah (misal: nama siswa, nama paket).
     */
    public function getRecordTitleAttribute()
    {
        // 1. Coba ambil dari model auditable (jika data belum dihapus permanen)
        // Pastikan auditable_type adalah class yang valid sebelum memanggil relasi
        if ($this->auditable_type && (class_exists($this->auditable_type) || \Illuminate\Database\Eloquent\Relations\Relation::getMorphedModel($this->auditable_type))) {
            try {
                if ($this->auditable) {
                    return $this->auditable->nama_lengkap 
                        ?? $this->auditable->nama_paket 
                        ?? $this->auditable->name 
                        ?? $this->auditable->nama 
                        ?? $this->auditable->judul 
                        ?? $this->auditable->no_transaksi 
                        ?? $this->auditable->invoice_number 
                        ?? null;
                }
            } catch (\Exception $e) {
                // Abaikan jika relasi gagal diload karena class tidak ditemukan
            }
        }

        // 2. Jika data sudah terhapus, coba cari namanya di history (old_values atau new_values)
        $values = $this->old_values ?? $this->new_values ?? [];
        return $values['nama_lengkap'] 
            ?? $values['nama_paket'] 
            ?? $values['name'] 
            ?? $values['nama'] 
            ?? $values['judul'] 
            ?? $values['no_transaksi'] 
            ?? $values['invoice_number'] 
            ?? null;
    }

    /**
     * Dapatkan deskripsi singkat aktivitas yang ramah dibaca manusia.
     */
    public function getDescriptionAttribute()
    {
        $userName = $this->user?->name ?? 'Sistem/Guest';
        $modelName = $this->formatted_model_name;
        
        if ($this->event === 'created') {
            return "{$userName} menambahkan {$modelName} baru.";
        } elseif ($this->event === 'updated') {
            $changedColumns = array_keys($this->new_values ?? []);
            
            if (empty($changedColumns)) {
                return "{$userName} mengubah Kata Sandi.";
            }

            // Terjemahkan nama kolom agar lebih user friendly
            $translatedColumns = array_map(function($col) {
                $map = [
                    'name' => 'Nama',
                    'email' => 'Email',
                    'photo' => 'Foto Profil',
                    'password' => 'Kata Sandi',
                    'username' => 'Username',
                    'is_active' => 'Status Aktif',
                    'role' => 'Hak Akses',
                    'nama_lengkap' => 'Nama Lengkap',
                    'no_hp' => 'Nomor HP',
                    'alamat' => 'Alamat',
                    'jenis_kelamin' => 'Jenis Kelamin',
                    'tanggal_lahir' => 'Tanggal Lahir',
                    'tempat_lahir' => 'Tempat Lahir',
                ];
                return $map[$col] ?? ucwords(str_replace('_', ' ', $col));
            }, $changedColumns);

            $columnsStr = implode(', ', $translatedColumns);
            return "{$userName} mengubah {$columnsStr}.";
        } elseif ($this->event === 'deleted') {
            return "{$userName} menghapus {$modelName}.";
        } elseif (in_array($this->event, ['Login Sistem', 'Logout Sistem'])) {
            return "{$userName} melakukan {$this->event}.";
        }

        return "{$userName} mengeksekusi operasi '{$this->event}'.";
    }

    /**
     * Dapatkan interpretasi ramah dari User Agent.
     * Urutan pengecekan: Edge/Opera harus sebelum Chrome (karena UA Edge mengandung "Chrome").
     */
    public function getFormattedUserAgentAttribute()
    {
        $ua = $this->user_agent;
        if (empty($ua)) return 'Unknown';

        // Deteksi OS
        $os = 'Unknown OS';
        if (preg_match('/windows|win32/i', $ua)) $os = 'Windows';
        elseif (preg_match('/macintosh|mac os x/i', $ua)) $os = 'macOS';
        elseif (preg_match('/android/i', $ua)) $os = 'Android';
        elseif (preg_match('/iphone|ipad/i', $ua)) $os = 'iOS';
        elseif (preg_match('/linux/i', $ua)) $os = 'Linux';

        // Deteksi Browser (Edge & Opera HARUS dicek sebelum Chrome)
        $browser = 'Unknown Browser';
        if (preg_match('/edg/i', $ua)) $browser = 'Microsoft Edge';
        elseif (preg_match('/opr|opera/i', $ua)) $browser = 'Opera';
        elseif (preg_match('/chrome|crios/i', $ua)) $browser = 'Google Chrome';
        elseif (preg_match('/firefox/i', $ua)) $browser = 'Mozilla Firefox';
        elseif (preg_match('/safari/i', $ua)) $browser = 'Apple Safari';

        return "{$browser} ({$os})";
    }
}


