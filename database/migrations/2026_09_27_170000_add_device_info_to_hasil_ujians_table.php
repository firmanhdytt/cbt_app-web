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
            $table->boolean('is_exambro')->default(false)->after('alasan_buka_kunci');
            $table->string('user_agent', 255)->nullable()->after('is_exambro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_ujians', function (Blueprint $table) {
            $table->dropColumn(['is_exambro', 'user_agent']);
        });
    }
};
