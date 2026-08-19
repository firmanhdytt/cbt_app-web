<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'jurusan',
        'tahun_ajaran',
    ];

    /**
     * Siswa yang terdaftar di kelas ini.
     */
    public function siswas()
    {
        return $this->hasMany(User::class, 'kelas_id');
    }

    /**
     * Soal yang diperuntukkan untuk kelas ini.
     */
    public function soals()
    {
        return $this->hasMany(Soal::class, 'kelas_id');
    }

    /**
     * Ujian yang ditargetkan untuk kelas ini.
     */
    public function ujians()
    {
        return $this->hasMany(Ujian::class, 'kelas_id');
    }

    /**
     * Accessor untuk nama lengkap kelas (misal: X IPA 1 - 2025/2026).
     */
    public function getNamaLengkapAttribute(): string
    {
        return "{$this->nama_kelas}" . ($this->tahun_ajaran ? " ({$this->tahun_ajaran})" : '');
    }
}
