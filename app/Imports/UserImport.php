<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $name = trim($row['name'] ?? $row['nama'] ?? '');
        $username = trim($row['username'] ?? '');
        $email = trim($row['email'] ?? '');
        $password = trim($row['password'] ?? 'password123');
        $role = strtolower(trim($row['role'] ?? 'siswa'));
        $nis = trim($row['nis'] ?? '');
        $namaKelas = trim($row['kelas'] ?? $row['nama_kelas'] ?? '');

        if (empty($name) || empty($username) || empty($email)) {
            return null;
        }

        // Hindari duplikasi username atau email
        if (User::where('username', $username)->orWhere('email', $email)->exists()) {
            return null;
        }

        $kelasId = null;
        if (!empty($namaKelas)) {
            $kelas = Kelas::where('nama_kelas', 'like', "%{$namaKelas}%")->first();
            if ($kelas) {
                $kelasId = $kelas->id;
            }
        }

        return new User([
            'name'       => $name,
            'username'   => $username,
            'email'      => $email,
            'password'   => Hash::make($password),
            'role'       => in_array($role, ['admin', 'guru', 'siswa']) ? $role : 'siswa',
            'nis'        => $nis ?: null,
            'kelas_id'   => $kelasId,
            'class_name' => $namaKelas ?: null,
        ]);
    }
}
