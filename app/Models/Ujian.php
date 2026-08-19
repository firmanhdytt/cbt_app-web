<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    use HasFactory;

    protected $fillable = [
        'mapel_id',
        'kelas_id',
        'judul',
        'deskripsi',
        'durasi_menit',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'status' => 'boolean',
    ];

    protected static function booted()
    {
        static::deleting(function ($ujian) {
            // Delete results via Eloquent to trigger cascading file cleanup
            $ujian->hasilUjians()->get()->each(function ($hasil) {
                $hasil->delete();
            });

            // Delete answers
            $ujian->jawabanPesertas()->delete();

            // Detach questions from pivot table
            $ujian->soals()->detach();
        });
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function soals()
    {
        return $this->belongsToMany(Soal::class, 'ujian_soal');
    }

    public function hasilUjians()
    {
        return $this->hasMany(HasilUjian::class);
    }

    public function jawabanPesertas()
    {
        return $this->hasMany(JawabanPeserta::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
