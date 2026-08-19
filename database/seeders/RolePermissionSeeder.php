<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat Permissions
        $permissions = [
            'kelola-soal',
            'kelola-ujian',
            'kelola-pengguna',
            'lihat-hasil-ujian',
            'kerjakan-ujian',
            'export-laporan',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Buat Roles & Assign Permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $guruRole = Role::firstOrCreate(['name' => 'guru']);
        $guruRole->givePermissionTo([
            'kelola-soal',
            'kelola-ujian',
            'lihat-hasil-ujian',
            'export-laporan',
        ]);

        $siswaRole = Role::firstOrCreate(['name' => 'siswa']);
        $siswaRole->givePermissionTo([
            'kerjakan-ujian',
            'lihat-hasil-ujian',
        ]);

        // 3. Buat Pengguna Default & Assign Role
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@cbt.com'],
            [
                'name' => 'Administrator CBT',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole($adminRole);

        // Guru
        $guru = User::firstOrCreate(
            ['email' => 'guru@cbt.com'],
            [
                'name' => 'Guru Pengajar',
                'username' => 'guru',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'email_verified_at' => now(),
            ]
        );
        $guru->assignRole($guruRole);

        // Siswa
        $siswa = User::firstOrCreate(
            ['email' => 'siswa@cbt.com'],
            [
                'name' => 'Siswa Demo',
                'username' => 'siswa',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'nis' => '12345678',
                'class_name' => 'Kelas XII - RPL 1',
                'email_verified_at' => now(),
            ]
        );
        $siswa->assignRole($siswaRole);
    }
}
