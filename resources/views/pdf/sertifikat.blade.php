<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat Kelulusan - {{ $hasil->user->name }}</title>
    <style>
        @page {
            size: landscape;
            margin: 0;
        }
        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            color: #0f172a;
            padding: 30px;
            background-color: #ffffff;
            margin: 0;
        }

        /* Outer Double Frame */
        .cert-outer-border {
            border: 12px double #0f172a;
            padding: 20px;
            height: 510px;
            box-sizing: border-box;
            background-color: #fcfbf9;
            position: relative;
        }

        .cert-inner-border {
            border: 2px solid #d97706;
            padding: 25px 35px;
            height: 100%;
            box-sizing: border-box;
            text-align: center;
            position: relative;
        }

        /* Certificate Number Top Right */
        .cert-number-badge {
            position: absolute;
            top: 15px;
            right: 25px;
            font-size: 10px;
            color: #64748b;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            letter-spacing: 0.5px;
        }

        /* Certificate Header Titles */
        .cert-header {
            margin-top: 10px;
            margin-bottom: 25px;
        }

        .cert-title {
            font-size: 34px;
            color: #0f172a;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin: 0;
        }

        .cert-subtitle {
            font-size: 12px;
            color: #d97706;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 5px;
            margin-top: 6px;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }

        /* Recipient Section */
        .award-label {
            font-size: 13px;
            font-style: italic;
            color: #64748b;
            margin-bottom: 8px;
        }

        .recipient-name {
            font-size: 30px;
            color: #0f172a;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }

        .recipient-meta {
            font-size: 12px;
            color: #475569;
            margin-bottom: 22px;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }

        /* Statement Body */
        .cert-statement {
            font-size: 13.5px;
            color: #334155;
            max-width: 680px;
            margin: 0 auto 20px auto;
            line-height: 1.6;
        }

        .highlight-text {
            color: #0f172a;
            font-weight: bold;
        }

        /* Score Box */
        .score-pill-container {
            margin-bottom: 25px;
        }

        .score-pill {
            display: inline-block;
            background-color: #ecfdf5;
            border: 1.5px solid #059669;
            color: #047857;
            padding: 6px 24px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: bold;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }

        .score-value {
            font-size: 18px;
            color: #047857;
            font-weight: bold;
        }

        /* Bottom Section: Signature & QR Verification */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            position: absolute;
            bottom: 25px;
            left: 0;
            right: 0;
            padding: 0 45px;
            box-sizing: border-box;
        }

        .signature-col {
            width: 50%;
            text-align: left;
            vertical-align: bottom;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }

        .signature-title {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 40px;
        }

        .signature-name {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            text-decoration: underline;
        }

        .signature-nip {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        .qr-col {
            width: 50%;
            text-align: right;
            vertical-align: bottom;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }

        .qr-wrapper {
            display: inline-block;
            text-align: center;
        }

        .qr-label {
            font-size: 9px;
            color: #64748b;
            margin-top: 4px;
        }
    </style>
</head>
<body>

    <div class="cert-outer-border">
        <div class="cert-inner-border">

            <!-- Certificate Number Badge -->
            <div class="cert-number-badge">
                NO: <strong>{{ $certificateNumber }}</strong>
            </div>

            <!-- Header Titles -->
            <div class="cert-header">
                <div class="cert-title">Sertifikat Kelulusan</div>
                <div class="cert-subtitle">CERTIFICATE OF ACCOMPLISHMENT</div>
            </div>

            <!-- Recipient Award -->
            <div class="award-label">Sertifikat ini dengan bangga diberikan kepada:</div>
            <div class="recipient-name">{{ $hasil->user->name }}</div>
            <div class="recipient-meta">
                NIS: <strong>{{ $hasil->user->nis ?? '-' }}</strong> &nbsp;|&nbsp; 
                Kelas: <strong>{{ $hasil->user->kelas->nama_kelas ?? $hasil->user->class_name ?? 'Umum' }}</strong>
            </div>

            <!-- Statement -->
            <div class="cert-statement">
                Telah dinyatakan <span class="highlight-text" style="color:#059669; text-transform:uppercase;">LULUS</span> dalam menempuh ujian 
                <span class="highlight-text">"{{ $hasil->ujian->judul }}"</span> 
                pada mata pelajaran <span class="highlight-text">{{ $hasil->ujian->mapel->nama_mapel ?? '-' }}</span> 
                yang diselenggarakan secara elektronik (CBT) pada tanggal 
                {{ $hasil->waktu_selesai ? $hasil->waktu_selesai->format('d F Y') : date('d F Y') }}.
            </div>

            <!-- Score Pill -->
            <div class="score-pill-container">
                <div class="score-pill">
                    SKOR NILAI AKHIR: <span class="score-value">{{ $hasil->nilai }}</span> &nbsp;(KKM KELULUSAN: 70.00)
                </div>
            </div>

            <!-- Footer: Signatures & Digital QR Verification -->
            <table class="footer-table">
                <tr>
                    <!-- Signature Left -->
                    <td class="signature-col">
                        <div class="signature-title">Kepala Panitia Pelaksana CBT,</div>
                        <div class="signature-name">ADMINISTRATOR UTAMA</div>
                        <div class="signature-nip">NIP. 19850314 201012 1 002</div>
                    </td>

                    <!-- QR Code Right -->
                    <td class="qr-col">
                        <div class="qr-wrapper">
                            <img src="data:image/svg+xml;base64,{{ $qrCode }}" width="72" height="72" style="border: 1px solid #e2e8f0; padding: 3px; background: #ffffff; border-radius: 4px;">
                            <div class="qr-label">Pindai untuk Verifikasi Keaslian</div>
                        </div>
                    </td>
                </tr>
            </table>

        </div>
    </div>

</body>
</html>
