<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_mapel',
        'kode_mapel',
    ];

    public function soals()
    {
        return $this->hasMany(Soal::class);
    }

    public function ujians()
    {
        return $this->hasMany(Ujian::class);
    }
}
