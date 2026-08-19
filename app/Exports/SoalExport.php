<?php

namespace App\Exports;

use App\Models\Soal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SoalExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Soal::with('mapel', 'creator')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Mata Pelajaran',
            'Kode Mapel',
            'Pembuat',
            'Pertanyaan',
            'Pilihan A',
            'Pilihan B',
            'Pilihan C',
            'Pilihan D',
            'Jawaban Benar',
        ];
    }

    /**
     * @param mixed $soal
     * @return array
     */
    public function map($soal): array
    {
        return [
            $soal->id,
            $soal->mapel->nama_mapel ?? '-',
            $soal->mapel->kode_mapel ?? '-',
            $soal->creator->name ?? '-',
            $soal->pertanyaan,
            $soal->pilihan_a,
            $soal->pilihan_b,
            $soal->pilihan_c,
            $soal->pilihan_d,
            $soal->jawaban_benar,
        ];
    }
}
