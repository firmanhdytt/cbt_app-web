<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas', 100);        // Misal: X IPA 1
            $table->string('tingkat', 20)->nullable(); // Misal: X, XI, XII
            $table->string('jurusan', 50)->nullable(); // Misal: IPA, IPS, Bahasa
            $table->string('tahun_ajaran', 20)->nullable(); // Misal: 2025/2026
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
