<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'google_id',
        'role',
        'nis',
        'kelas_id',
        'phone',
        'class_name',
        'avatar',
    ];

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
            'password' => 'hashed',
        ];
    }

    protected static function booted()
    {
        static::deleting(function ($user) {
            // Delete certificates via Eloquent to trigger file cleanup
            $user->sertifikats()->get()->each(function ($sertifikat) {
                $sertifikat->delete();
            });

            // Delete results via Eloquent
            $user->hasilUjians()->get()->each(function ($hasil) {
                $hasil->delete();
            });

            // Delete answers
            $user->jawabanPesertas()->delete();

            // Delete local avatar file
            if ($user->avatar && !str_starts_with($user->avatar, 'http') && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
        });
    }

    public function hasilUjians()
    {
        return $this->hasMany(HasilUjian::class);
    }

    public function jawabanPesertas()
    {
        return $this->hasMany(JawabanPeserta::class);
    }

    public function sertifikats()
    {
        return $this->hasMany(Sertifikat::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Relasi penempatan kelas dan mata pelajaran untuk Guru.
     */
    public function guruKelasMapels()
    {
        return $this->hasMany(GuruKelasMapel::class, 'user_id');
    }

    /**
     * Cek apakah user (Admin / Guru) memiliki akses ke workspace (Kelas + Mapel) tertentu.
     */
    public function hasWorkspaceAccess($kelasId, $mapelId): bool
    {
        if ($this->role === 'admin') {
            return true;
        }

        if ($this->role !== 'guru') {
            return false;
        }

        if (!$kelasId || !$mapelId) {
            return false;
        }

        return $this->guruKelasMapels()
            ->where('kelas_id', $kelasId)
            ->where('mapel_id', $mapelId)
            ->exists();
    }

    /**
     * Mengambil daftar ID kelas yang ditugaskan ke Guru (atau semua ID kelas jika Admin).
     */
    public function getAssignedKelasIds()
    {
        if ($this->role === 'admin') {
            return Kelas::pluck('id');
        }

        return $this->guruKelasMapels()->pluck('kelas_id')->unique();
    }

    /**
     * Mengambil daftar ID mapel yang ditugaskan ke Guru (atau semua ID mapel jika Admin).
     */
    public function getAssignedMapelIds($kelasId = null)
    {
        if ($this->role === 'admin') {
            return Mapel::pluck('id');
        }

        $query = $this->guruKelasMapels();
        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        return $query->pluck('mapel_id')->unique();
    }

    /**
     * Get the full URL for the user's avatar photo.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) {
            return null;
        }

        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        return \Illuminate\Support\Facades\Storage::url($this->avatar);
    }
}
