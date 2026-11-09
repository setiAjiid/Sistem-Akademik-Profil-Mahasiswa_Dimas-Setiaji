<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_DOSEN = 'dosen';

    public const ROLE_ASDOS = 'asdos';

    public const ROLE_MAHASISWA = 'mahasiswa';

    /** Semua peran yang dikenal sistem. */
    public const DAFTAR_ROLE = [
        self::ROLE_DOSEN,
        self::ROLE_ASDOS,
        self::ROLE_MAHASISWA,
    ];

    /** Peran yang boleh masuk ke area pengelola (dosen dan asisten dosen). */
    public const ROLE_PENGELOLA = [
        self::ROLE_DOSEN,
        self::ROLE_ASDOS,
    ];

    /** Proposal yang dibuat user ini (khusus mahasiswa). */
    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }

    /** Cek apakah user memiliki salah satu peran yang diberikan. */
    public function punyaPeran(string ...$peran): bool
    {
        return in_array($this->role, $peran, true);
    }

    /** Dosen dan asdos termasuk pengelola. */
    public function adalahPengelola(): bool
    {
        return $this->punyaPeran(...self::ROLE_PENGELOLA);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
