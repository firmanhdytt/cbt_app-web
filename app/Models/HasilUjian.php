<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilUjian extends Model
{
    use HasFactory;

    protected $table = 'hasil_ujians';

    protected $fillable = [
        'user_id',
        'ujian_id',
        'nilai',
        'jumlah_benar',
        'jumlah_salah',
        'jumlah_pelanggaran',
        'status_pengerjaan',
        'perlu_buka_kunci',
        'alasan_buka_kunci',
        'waktu_selesai',
    ];

    protected $casts = [
        'waktu_selesai' => 'datetime',
        'nilai' => 'float',
        'perlu_buka_kunci' => 'boolean',
    ];

    protected static function booted()
    {
        static::deleting(function ($hasilUjian) {
            \App\Models\Sertifikat::where('user_id', $hasilUjian->user_id)
                ->where('ujian_id', $hasilUjian->ujian_id)
                ->get()
                ->each(function ($sertifikat) {
                    $sertifikat->delete();
                });
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}
