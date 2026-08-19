<?php

namespace App\Imports;

use App\Models\Soal;
use App\Models\Mapel;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;

class SoalImport implements ToModel, WithHeadingRow
{
    protected $defaultMapelId;
    protected $defaultKelasId;
    protected $importedCount = 0;

    public function __construct($defaultMapelId = null, $defaultKelasId = null)
    {
        $this->defaultMapelId = $defaultMapelId;
        $this->defaultKelasId = $defaultKelasId;
    }

    /**
     * Parse each row from Excel
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // 1. Flexible Column Mapping for Pertanyaan
        $pertanyaan = $row['pertanyaan'] ?? $row['soal'] ?? $row['pertanyaan_soal'] ?? null;
        if (empty($pertanyaan)) {
            return null;
        }

        // 2. Flexible Column Mapping for Jawaban Benar / Kunci
        $jawabanBenar = $row['jawaban_benar'] 
            ?? $row['kunci_jawaban'] 
            ?? $row['kunci'] 
            ?? $row['jawaban'] 
            ?? null;

        if (empty($jawabanBenar)) {
            return null;
        }

        $jawabanBenar = strtoupper(trim((string)$jawabanBenar));
        // Extract first letter if user wrote e.g. "A. Option Text"
        if (preg_match('/^([ABCD])/i', $jawabanBenar, $matches)) {
            $jawabanBenar = strtoupper($matches[1]);
        }

        if (!in_array($jawabanBenar, ['A', 'B', 'C', 'D'])) {
            return null;
        }

        // 3. Flexible Column Mapping for Options
        $pilihanA = $row['pilihan_a'] ?? $row['opsi_a'] ?? $row['option_a'] ?? $row['a'] ?? '-';
        $pilihanB = $row['pilihan_b'] ?? $row['opsi_b'] ?? $row['option_b'] ?? $row['b'] ?? '-';
        $pilihanC = $row['pilihan_c'] ?? $row['opsi_c'] ?? $row['option_c'] ?? $row['c'] ?? '-';
        $pilihanD = $row['pilihan_d'] ?? $row['opsi_d'] ?? $row['option_d'] ?? $row['d'] ?? '-';

        // 4. Mapel Resolution (Row Level Override > Form Dropdown Default)
        $mapelId = $this->defaultMapelId;
        $kodeMapel = $row['kode_mapel'] ?? $row['mapel'] ?? null;

        if (!empty($kodeMapel)) {
            $kodeMapelClean = strtoupper(trim((string)$kodeMapel));
            $mapel = Mapel::where('kode_mapel', $kodeMapelClean)->first();
            if (!$mapel) {
                $namaMapel = $row['mata_pelajaran'] ?? $row['nama_mapel'] ?? $kodeMapelClean;
                $mapel = Mapel::create([
                    'kode_mapel' => $kodeMapelClean,
                    'nama_mapel' => trim((string)$namaMapel),
                ]);
            }
            $mapelId = $mapel->id;
        }

        // Fallback to first existing Mapel if still unassigned
        if (!$mapelId) {
            $firstMapel = Mapel::first();
            if ($firstMapel) {
                $mapelId = $firstMapel->id;
            } else {
                $mapel = Mapel::create([
                    'kode_mapel' => 'UMUM',
                    'nama_mapel' => 'Mata Pelajaran Umum',
                ]);
                $mapelId = $mapel->id;
            }
        }

        // 5. Kelas Resolution (Row Level Override > Form Dropdown Default)
        $kelasId = $this->defaultKelasId;
        $namaKelas = $row['kelas'] ?? $row['nama_kelas'] ?? $row['kode_kelas'] ?? null;

        if (!empty($namaKelas)) {
            $namaKelasClean = trim((string)$namaKelas);
            $kelas = Kelas::where('nama_kelas', $namaKelasClean)->first();
            if ($kelas) {
                $kelasId = $kelas->id;
            }
        }

        $this->importedCount++;

        return new Soal([
            'mapel_id'      => $mapelId,
            'kelas_id'      => $kelasId,
            'created_by'    => Auth::id(),
            'pertanyaan'    => trim((string)$pertanyaan),
            'pilihan_a'     => trim((string)$pilihanA),
            'pilihan_b'     => trim((string)$pilihanB),
            'pilihan_c'     => trim((string)$pilihanC),
            'pilihan_d'     => trim((string)$pilihanD),
            'jawaban_benar' => $jawabanBenar,
        ]);
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }
}
