<?php

namespace App\Models;
use App\Models\Akademik\PesertaDidik;
use App\Models\MasterData\Periode;
use App\Models\Pendaftaran\PendaftaranSiswa;
use App\Models\MasterData\Kantor;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasContextScope;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\Akademik\PesertaDidik|null $pesertaDidik
 * @property-read \App\Models\Pendaftaran\PendaftaranSiswa|null $pendaftaranSiswa
 */
use Laravel\Sanctum\HasApiTokens;
use App\Traits\Auditable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasContextScope, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'photo',
        'level',
        'is_active',
        'password',
        'jenis_kelamin',
        'alamat',
        'matapelajaran',
        'nip',
        'no_telp',
        'status',
        'tanggal_keluar',
        'alasan_keluar',
        'peserta_didik_id',
        'kantor_id',
        'periode_id',
        'permissions',
        'is_featured',
    ];

    /**
     * Mengecek apakah user memiliki hak akses tertentu.
     */
    public function hasPermission(string $permission): bool
    {
        // Super Admin selalu memiliki semua akses
        if ($this->level === 'Super Admin') {
            return true;
        }

        // Admin biasa dan Staff dicek melalui kolom permissions
        if (in_array($this->level, ['Admin', 'Staff'])) {
            return is_array($this->permissions) && in_array($permission, $this->permissions);
        }

        return false;
    }

    public function pesertaDidik(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Akademik\PesertaDidik::class, 'peserta_didik_id');
    }

    public function conversations()
    {
        return $this->belongsToMany(\App\Models\System\Conversation::class, 'conversation_user');
    }

    // scopeInContext() disediakan oleh HasContextScope trait

    public function kantor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Kantor::class);
    }

    public function periode(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
            'permissions'       => 'array',
        ];
    }
}


