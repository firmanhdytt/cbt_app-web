<?php

namespace App\Exports;

use App\Models\HasilUjian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;

class HasilUjianExport implements FromCollection, WithHeadings, WithMapping
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
        $query = HasilUjian::with(['user.kelas', 'ujian.mapel']);

        if ($this->user && $this->user->role === 'guru') {
            $assignedKelasIds = $this->user->getAssignedKelasIds();
            $assignedMapelIds = $this->user->getAssignedMapelIds();
            $query->whereHas('ujian', function ($q) use ($assignedKelasIds, $assignedMapelIds) {
                $q->whereIn('kelas_id', $assignedKelasIds)->whereIn('mapel_id', $assignedMapelIds);
            });
        }

        return $query->latest()->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Hasil',
            'Nama Siswa',
            'NIS',
            'Kelas Peserta',
            'Mata Pelajaran',
            'Judul Ujian',
            'Jumlah Benar',
            'Jumlah Salah',
            'Nilai Akhir',
            'Tanggal Selesai',
        ];
    }

    /**
     * @param mixed $hasil
     * @return array
     */
    public function map($hasil): array
    {
        return [
            $hasil->id,
            $hasil->user->name ?? '-',
            $hasil->user->nis ?? '-',
            $hasil->user->kelas->nama_kelas ?? $hasil->user->class_name ?? '-',
            $hasil->ujian->mapel->nama_mapel ?? '-',
            $hasil->ujian->judul ?? '-',
            $hasil->jumlah_benar,
            $hasil->jumlah_salah,
            $hasil->nilai,
            $hasil->waktu_selesai ? $hasil->waktu_selesai->format('Y-m-d H:i:s') : '-',
        ];
    }
}
