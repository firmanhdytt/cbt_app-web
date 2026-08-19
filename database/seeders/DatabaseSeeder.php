<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil RolePermissionSeeder
        $this->call(RolePermissionSeeder::class);

        // Buat Data Mapel Awal
        \App\Models\Mapel::firstOrCreate(['kode_mapel' => 'MTK'], ['nama_mapel' => 'Matematika']);
        \App\Models\Mapel::firstOrCreate(['kode_mapel' => 'BIN'], ['nama_mapel' => 'Bahasa Indonesia']);
        \App\Models\Mapel::firstOrCreate(['kode_mapel' => 'BIG'], ['nama_mapel' => 'Bahasa Inggris']);
        \App\Models\Mapel::firstOrCreate(['kode_mapel' => 'IPA'], ['nama_mapel' => 'Ilmu Pengetahuan Alam']);
    }
}
