<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'name',
            'username',
            'email',
            'password',
            'role',
            'nis',
            'kelas',
        ];
    }

    public function array(): array
    {
        return [
            [
                'Budi Santoso',
                'budi123',
                'budi@sman5medan.sch.id',
                'password123',
                'siswa',
                '20261001',
                'X IPA 1',
            ],
            [
                'Siti Rahma',
                'siti123',
                'siti@sman5medan.sch.id',
                'password123',
                'siswa',
                '20261002',
                'X IPA 1',
            ],
        ];
    }
}
