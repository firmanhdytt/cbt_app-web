<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Peserta - {{ $user->name }}</title>
    <style>
        @page {
            margin: 15px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            background-color: #ffffff;
            margin: 0;
            padding: 10px;
        }
        .card-wrapper {
            width: 480px;
            margin: 0 auto;
            border: 2px solid #334155;
            border-radius: 12px;
            overflow: hidden;
            background-color: #ffffff;
            position: relative;
        }
        /* Top Header Strip */
        .card-header {
            background-color: #0f172a;
            color: #ffffff;
            padding: 14px 18px;
            border-bottom: 3px solid #4f46e5;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-title {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #ffffff;
            margin: 0;
        }
        .header-subtitle {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 3px;
        }
        .header-tag {
            text-align: right;
            font-size: 10px;
            font-weight: bold;
            color: #38bdf8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Body Section */
        .card-body {
            padding: 16px 18px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .avatar-cell {
            width: 105px;
            vertical-align: top;
            padding-right: 15px;
        }
        .avatar-box {
            width: 95px;
            height: 120px;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            background-color: #f8fafc;
            text-align: center;
            line-height: 120px;
            font-size: 10px;
            color: #94a3b8;
            font-weight: bold;
        }
        .details-cell {
            vertical-align: top;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .details-table td {
            padding: 4px 0;
            vertical-align: middle;
        }
        .label-col {
            width: 85px;
            color: #64748b;
            font-weight: bold;
            font-size: 11px;
        }
        .separator-col {
            width: 12px;
            color: #94a3b8;
        }
        .value-col {
            color: #0f172a;
            font-weight: 500;
        }
        .value-name {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
        }
        .badge-kelas {
            background-color: #e0e7ff;
            color: #3730a3;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }

        /* Bottom Signature & Footer */
        .card-footer {
            border-top: 1px solid #e2e8f0;
            padding: 12px 18px;
            background-color: #f8fafc;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .instructions {
            font-size: 9px;
            color: #64748b;
            line-height: 1.4;
        }
        .signature-cell {
            text-align: center;
            width: 140px;
            font-size: 10px;
        }
        .signature-title {
            color: #475569;
            margin-bottom: 32px;
        }
        .signature-name {
            font-weight: bold;
            color: #0f172a;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="card-wrapper">
        <!-- Header -->
        <div class="card-header">
            <table class="header-table">
                <tr>
                    <td>
                        <div class="header-title">SMA NEGERI 5 MEDAN</div>
                        <div class="header-subtitle">Kartu Peserta Ujian CBT - Tahun Ajaran {{ date('Y') }}/{{ date('Y')+1 }}</div>
                    </td>
                    <td class="header-tag">
                        OFFICIAL PASS
                    </td>
                </tr>
            </table>
        </div>

        <!-- Body -->
        <div class="card-body">
            <table class="info-table">
                <tr>
                    <!-- Left: Foto Avatar -->
                    <td class="avatar-cell">
                        <div class="avatar-box" style="line-height: normal; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                            @if ($user->avatar && !str_starts_with($user->avatar, 'http') && file_exists(public_path('storage/' . $user->avatar)))
                                <img src="{{ public_path('storage/' . $user->avatar) }}" style="width: 95px; height: 120px; object-fit: cover; border-radius: 6px;">
                            @elseif ($user->avatar && str_starts_with($user->avatar, 'http'))
                                <img src="{{ $user->avatar }}" style="width: 95px; height: 120px; object-fit: cover; border-radius: 6px;">
                            @else
                                <div style="line-height: 120px;">FOTO 3 X 4</div>
                            @endif
                        </div>
                    </td>

                    <!-- Right: Student Details -->
                    <td class="details-cell">
                        <table class="details-table">
                            <tr>
                                <td class="label-col">Nama Peserta</td>
                                <td class="separator-col">:</td>
                                <td class="value-col value-name">{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td class="label-col">NIS / No. Induk</td>
                                <td class="separator-col">:</td>
                                <td class="value-col" style="font-family: monospace; font-weight: bold;">{{ $user->nis ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="label-col">Kelas Peserta</td>
                                <td class="separator-col">:</td>
                                <td class="value-col">
                                    <span class="badge-kelas">
                                        {{ $user->kelas->nama_kelas ?? $user->class_name ?? 'Umum' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-col">Username / Email</td>
                                <td class="separator-col">:</td>
                                <td class="value-col" style="font-size:11px;">{{ $user->username ?? $user->email }}</td>
                            </tr>
                            <tr>
                                <td class="label-col">Status Akun</td>
                                <td class="separator-col">:</td>
                                <td class="value-col" style="color:#059669; font-weight:bold; font-size:11px;">TERVERIFIKASI AKTIF</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Footer & Signature -->
        <div class="card-footer">
            <table class="footer-table">
                <tr>
                    <td class="instructions">
                        <strong>Catatan Peserta:</strong><br>
                        1. Wajib menunjukkan kartu ini sebelum ujian.<br>
                        2. Jaga kerahasiaan kata sandi akun Anda.<br>
                        3. Dilarang melakukan kecurangan proctoring.
                    </td>
                    <td class="signature-cell">
                        <div class="signature-title">Panitia Pelaksana,</div>
                        <div class="signature-name">ADMINISTRATOR</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
