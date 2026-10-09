<?php

namespace App\Exports;

use App\Models\Soal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;

class SoalExport implements FromCollection, WithHeadings, WithMapping
{
    protected $user;

    public function __construct($user = null)
    {
        $this->user = $user ?? Auth::user();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Soal::with(['mapel', 'kelas', 'creator']);

        if ($this->user && $this->user->role === 'guru') {
            $assignedKelasIds = $this->user->getAssignedKelasIds();
            $assignedMapelIds = $this->user->getAssignedMapelIds();
            $query->whereIn('kelas_id', $assignedKelasIds)->whereIn('mapel_id', $assignedMapelIds);
        }

        return $query->latest()->get();
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
            'Kelas',
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
            $soal->kelas->nama_kelas ?? 'Semua Kelas',
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
