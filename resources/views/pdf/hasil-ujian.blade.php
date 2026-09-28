<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil Ujian - {{ $hasil->user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #666;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 6px 10px;
            font-size: 14px;
        }
        .info-table td.label {
            width: 25%;
            font-weight: bold;
            background-color: #f5f5f5;
        }
        .score-box {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .score {
            font-size: 32px;
            font-weight: bold;
            color: #4f46e5;
            margin: 5px 0;
        }
        .status {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
        }
        .status.lulus {
            color: #10b981;
        }
        .status.gagal {
            color: #ef4444;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        .details-table th, .details-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .details-table th {
            background-color: #f8fafc;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 50px;
            font-size: 12px;
            text-align: center;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>SMA NEGERI 5 MEDAN</h2>
        <h3 style="margin: 3px 0; font-size: 16px; font-weight: normal; color: #444;">Rapor Hasil Ujian Mandiri CBT</h3>
        <p>Jl. Pelajar No. 21, Teladan Timur, Kec. Medan Kota, Kota Medan, Sumatera Utara</p>
    </div>

    <table class="info-table" border="1" bordercolor="#eee">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td>{{ $hasil->user->name }}</td>
            <td class="label">Mata Pelajaran</td>
            <td>{{ $hasil->ujian->mapel->nama_mapel ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">NIS</td>
            <td>{{ $hasil->user->nis ?? '-' }}</td>
            <td class="label">Judul Ujian</td>
            <td>{{ $hasil->ujian->judul }}</td>
        </tr>
        <tr>
            <td class="label">Kelas / Instansi</td>
            <td>{{ $hasil->user->kelas->nama_kelas ?? $hasil->user->class_name ?? '-' }}</td>
            <td class="label">Waktu Selesai</td>
            <td>{{ $hasil->waktu_selesai ? $hasil->waktu_selesai->format('d M Y - H:i') : '-' }}</td>
        </tr>
    </table>

    <div class="score-box">
        <span style="font-size: 12px; text-transform: uppercase; color: #666; font-weight: bold;">Skor / Nilai Akhir</span>
        <div class="score">{{ $hasil->nilai }}</div>
        <span class="status {{ $hasil->nilai >= 70 ? 'lulus' : 'gagal' }}">
            {{ $hasil->nilai >= 70 ? 'LULUS (SKOR KELULUSAN KKM >= 70)' : 'TIDAK LULUS (SKOR DI BAWAH KKM 70)' }}
        </span>
    </div>

    <h4 style="border-bottom: 1px solid #ddd; padding-bottom: 5px;">Rincian Pengerjaan Soal:</h4>
    <table class="details-table">
        <thead>
            <tr>
                <th style="width: 25%" class="text-center">Jumlah Soal</th>
                <th style="width: 25%" class="text-center" style="color: #10b981;">Jawaban Benar</th>
                <th style="width: 25%" class="text-center" style="color: #ef4444;">Jawaban Salah</th>
                <th style="width: 25%" class="text-center">Persentase Ketepatan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">{{ $hasil->jumlah_benar + $hasil->jumlah_salah }} Butir</td>
                <td class="text-center" style="font-weight: bold; color: #10b981;">{{ $hasil->jumlah_benar }}</td>
                <td class="text-center" style="font-weight: bold; color: #ef4444;">{{ $hasil->jumlah_salah }}</td>
                <td class="text-center" style="font-weight: bold;">{{ $hasil->nilai }}%</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dicetak otomatis secara elektronik oleh sistem CBT Project pada {{ now()->format('d M Y, H:i:s') }}.</p>
    </div>

</body>
</html>
