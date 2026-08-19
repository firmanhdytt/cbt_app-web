<?php

namespace App\Exports;

use App\Models\HasilUjian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HasilUjianExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return HasilUjian::with(['user.kelas', 'ujian.mapel'])->latest()->get();
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
