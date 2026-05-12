<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
 * @property-read \App\Models\PesertaDidik|null $pesertaDidik
 * @property-read \App\Models\PendaftaranSiswa|null $pendaftaranSiswa
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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
    ];

    public function pesertaDidik(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\PesertaDidik::class, 'peserta_didik_id');
    }

    public function scopeInContext($query)
    {
        $kantorId = session('kantor_id');
        $periodeId = session('periode_id');

        if (!$kantorId || !$periodeId) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('kantor_id', $kantorId)
                     ->where('periode_id', $periodeId);
    }

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
        ];
    }
}
