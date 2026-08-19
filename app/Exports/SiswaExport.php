<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SiswaExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::with('kelas')->where('role', 'siswa')->latest()->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Siswa',
            'Nama Lengkap',
            'Username',
            'Email',
            'NIS',
            'Nomor Telepon',
            'Kelas Peserta',
            'Tanggal Terdaftar',
        ];
    }

    /**
     * @param mixed $siswa
     * @return array
     */
    public function map($siswa): array
    {
        return [
            $siswa->id,
            $siswa->name,
            $siswa->username ?? '-',
            $siswa->email,
            $siswa->nis ?? '-',
            $siswa->phone ?? '-',
            $siswa->kelas->nama_kelas ?? $siswa->class_name ?? '-',
            $siswa->created_at ? $siswa->created_at->format('Y-m-d H:i:s') : '-',
        ];
    }
}
