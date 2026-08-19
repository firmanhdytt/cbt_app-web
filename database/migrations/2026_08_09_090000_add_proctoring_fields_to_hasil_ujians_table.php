<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hasil_ujians', function (Blueprint $table) {
            $table->integer('jumlah_pelanggaran')->default(0)->after('jumlah_salah');
            $table->string('status_pengerjaan')->default('selesai')->after('jumlah_pelanggaran');
            $table->boolean('perlu_buka_kunci')->default(false)->after('status_pengerjaan');
            $table->text('alasan_buka_kunci')->nullable()->after('perlu_buka_kunci');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_ujians', function (Blueprint $table) {
            $table->dropColumn(['jumlah_pelanggaran', 'status_pengerjaan', 'perlu_buka_kunci', 'alasan_buka_kunci']);
        });
    }
};
