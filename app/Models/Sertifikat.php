<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ujian_id',
        'nomor_sertifikat',
        'file_path',
    ];

    protected static function booted()
    {
        static::deleting(function ($sertifikat) {
            if ($sertifikat->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($sertifikat->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($sertifikat->file_path);
            }
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
