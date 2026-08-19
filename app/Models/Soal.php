<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    protected $fillable = [
        'mapel_id',
        'kelas_id',
        'created_by',
        'pertanyaan',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'jawaban_benar',
    ];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ujians()
    {
        return $this->belongsToMany(Ujian::class, 'ujian_soal');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
