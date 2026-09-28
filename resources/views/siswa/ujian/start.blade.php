<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruang Ujian: {{ $ujian->judul }} - CBT Portal</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CBT Design System CSS -->
    <link href="/css/design-system.css" rel="stylesheet">

    <style>
        :root {
            --cbt-font-size: 1rem;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            user-select: none;
            -webkit-user-select: none;
            background-color: var(--bg-body, #f4f6f9);
        }

        /* Fixed Top Header */
        .cbt-header {
            height: 64px;
            background-color: var(--bg-card, #ffffff);
            border-bottom: 2px solid var(--border-color, #e2e8f0);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            z-index: 1020;
            flex-shrink: 0;
        }

        /* Container Layout */
        .cbt-app-container {
            height: calc(100vh - 64px);
            display: flex;
            overflow: hidden;
        }

        /* Left Main Content */
        .cbt-main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 1.25rem 1.5rem;
            overflow-y: auto;
            background-color: var(--bg-body, #f4f6f9);
        }

        .cbt-card-question {
            background-color: var(--bg-card, #ffffff);
            border: 1px solid var(--border-color, #e2e8f0);
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            display: flex;
            flex-direction: column;
            min-height: 100%;
            padding: 1.5rem;
        }

        .cbt-question-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 1rem;
            border-bottom: 1.5px solid var(--border-color, #e2e8f0);
            margin-bottom: 1.25rem;
        }

        .cbt-question-body {
            flex: 1;
            font-size: var(--cbt-font-size);
            line-height: 1.6;
        }

        .question-text {
            font-size: calc(var(--cbt-font-size) * 1.1);
            font-weight: 600;
            color: var(--text-primary, #0f172a);
            margin-bottom: 1.5rem;
            white-space: pre-wrap;
        }

        /* Option Item Cards */
        .option-item {
            border: 2px solid var(--border-color, #e2e8f0);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 0.85rem;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: flex-start;
            background-color: var(--bg-card, #ffffff);
        }

        .option-item:hover {
            border-color: var(--primary, #4f46e5);
            background-color: rgba(79, 70, 229, 0.03);
            transform: translateX(4px);
        }

        .option-item.selected {
            background-color: rgba(79, 70, 229, 0.08) !important;
            border-color: var(--primary, #4f46e5) !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        .option-badge {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background-color: #f1f5f9;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
            margin-right: 1rem;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .option-item.selected .option-badge {
            background-color: var(--primary, #4f46e5);
            color: #ffffff;
        }

        .option-text {
            color: var(--text-primary, #0f172a);
            font-size: calc(var(--cbt-font-size) * 1.02);
            padding-top: 4px;
        }

        /* Bottom Footer Action Bar */
        .cbt-question-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding-top: 1.25rem;
            border-top: 1.5px solid var(--border-color, #e2e8f0);
            margin-top: auto;
            flex-wrap: wrap;
        }

        /* Right Sidebar Grid */
        .cbt-sidebar {
            width: 320px;
            background-color: var(--bg-card, #ffffff);
            border-left: 2px solid var(--border-color, #e2e8f0);
            display: flex;
            flex-direction: column;
            padding: 1.25rem;
            overflow-y: auto;
            flex-shrink: 0;
        }

        .map-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            margin-bottom: 1.5rem;
        }

        .map-soal-btn {
            aspect-ratio: 1;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            border: 1.5px solid var(--border-color, #cbd5e1);
            background-color: #f8fafc;
            color: #334155;
            cursor: pointer;
            transition: all 0.18s ease;
        }

        .map-soal-btn:hover {
            border-color: var(--primary, #4f46e5);
            transform: scale(1.05);
        }

        .map-soal-btn.active {
            border-color: var(--primary, #4f46e5) !important;
            background-color: rgba(79, 70, 229, 0.1) !important;
            color: var(--primary, #4f46e5) !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
            font-weight: 800;
        }

        .map-soal-btn.answered {
            background-color: #10b981 !important;
            border-color: #10b981 !important;
            color: #ffffff !important;
        }

        .map-soal-btn.flagged {
            background-color: #f59e0b !important;
            border-color: #f59e0b !important;
            color: #ffffff !important;
        }

        /* Timer Badge */
        .timer-badge {
            background-color: #f8fafc;
            border: 1.5px solid var(--border-color, #e2e8f0);
            color: #0f172a;
            font-size: 1.15rem;
            font-weight: 800;
            padding: 0.35rem 1rem;
            border-radius: 10px;
            letter-spacing: 0.5px;
            font-family: monospace, sans-serif;
            transition: all 0.3s ease;
        }

        @keyframes pulse-timer {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.03); }
            100% { opacity: 1; transform: scale(1); }
        }

        .pulse-danger {
            animation: pulse-timer 1s infinite ease-in-out;
            background-color: #ef4444 !important;
            border-color: #ef4444 !important;
            color: #ffffff !important;
        }

        /* Toast Notifications */
        .toast-modern-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 2000;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .toast-modern {
            background-color: #0f172a;
            color: #ffffff;
            padding: 12px 18px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            min-width: 280px;
            animation: slideInRight 0.3s ease;
        }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* MANDATORY FULLSCREEN LOCK OVERLAYS */
        .fullscreen-entry-overlay {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .fullscreen-warning-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(12px);
            z-index: 9998;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        @media (max-width: 991px) {
            .cbt-header {
                height: 54px;
                padding: 0.4rem 0.75rem;
                flex-wrap: nowrap;
                background-color: #0f172a;
                color: #ffffff;
                border-bottom: 2px solid #4f46e5;
            }
            .cbt-header .fw-bold, .cbt-header .text-dark {
                color: #ffffff !important;
            }
            .cbt-header .text-muted {
                color: #94a3b8 !important;
            }
            .cbt-app-container {
                height: calc(100vh - 54px - 60px);
                flex-direction: column;
                overflow: hidden;
            }
            html, body {
                overflow: hidden;
                background-color: #f8fafc;
            }
            .cbt-main-content {
                padding: 0.65rem 0.65rem 1rem 0.65rem;
                flex: 1;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }
            .cbt-card-question {
                padding: 1rem 0.85rem;
                border-radius: 12px;
                border: 1px solid #e2e8f0;
                box-shadow: 0 2px 10px rgba(0,0,0,0.03);
                background-color: #ffffff;
                min-height: auto;
            }
            .cbt-sidebar {
                display: none;
            }
            .question-text {
                font-size: 0.98rem;
                font-weight: 600;
                line-height: 1.5;
                margin-bottom: 1rem;
                color: #1e293b;
            }
            .option-item {
                padding: 0.85rem;
                margin-bottom: 0.65rem;
                border-radius: 12px;
                border: 2px solid #e2e8f0;
                background-color: #ffffff;
                touch-action: manipulation;
                transition: all 0.15s ease;
            }
            .option-item.selected {
                border-color: #4f46e5 !important;
                background-color: rgba(79, 70, 229, 0.08) !important;
                box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.15);
            }
            .option-badge {
                width: 32px;
                height: 32px;
                font-size: 0.88rem;
                margin-right: 0.75rem;
                border-radius: 8px;
                background-color: #f1f5f9;
                color: #475569;
            }
            .option-text {
                font-size: 0.92rem;
                padding-top: 3px;
                color: #1e293b;
            }
            .cbt-question-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                height: 60px;
                background-color: #ffffff;
                border-top: 1.5px solid #e2e8f0;
                padding: 0.5rem 0.75rem;
                margin: 0;
                z-index: 1010;
                box-shadow: 0 -4px 15px rgba(0,0,0,0.05);
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.4rem;
            }
            .cbt-question-footer .btn {
                flex: 1;
                padding: 0.5rem 0.35rem !important;
                font-size: 0.78rem !important;
                font-weight: 700 !important;
                white-space: nowrap;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 42px;
                border-radius: 8px;
            }
            .timer-badge {
                font-size: 0.88rem;
                padding: 0.25rem 0.6rem;
                border-radius: 8px;
                background-color: rgba(255, 255, 255, 0.1);
                color: #ffffff;
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
        }
    </style>
</head>
<body>

    <!-- OVERLAY 1: MANDATORY FULLSCREEN START BANNER (Kunci Akses Awal) -->
    <div class="fullscreen-entry-overlay" id="fullscreenEntryOverlay">
        <div class="card border-0 shadow-lg p-4 text-center rounded-4" style="max-width: 480px; background:#ffffff;">
            <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px;">
                <i class="bi bi-shield-lock-fill text-primary display-5"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">Pemberitahuan Wajib Layar Penuh</h4>
            <p class="text-muted small mb-3">
                Ujian <strong>"{{ $ujian->judul }}"</strong> menggunakan sistem proteksi ujian ketat. Anda **WAJIB** berada dalam Mode Layar Penuh (Full Screen) selama seluruh ujian berlangsung.
            </p>
            <div class="alert alert-warning py-2 px-3 text-start small mb-4">
                <i class="bi bi-exclamation-triangle-fill me-1 text-warning"></i>
                <strong>Peraturan Ujian:</strong>
                <ul class="mb-0 ps-3 mt-1" style="font-size:11px;">
                    <li>Dilarang keluar dari Mode Fullscreen.</li>
                    <li>Dilarang berpindah tab, window, atau membuka aplikasi lain.</li>
                    <li>Pelanggaran akan dicatat secara otomatis oleh proctoring system.</li>
                </ul>
            </div>
            <button type="button" class="btn btn-primary btn-lg w-100 fw-bold py-3 rounded-3" id="btnEnterMandatoryFullscreen">
                <i class="bi bi-arrows-fullscreen me-2"></i> MASUK LAYAR PENUH & MULAI UJIAN
            </button>
        </div>
    </div>

    <!-- OVERLAY 2: VIOLATION LOCK SCREEN (Tampilan Terkunci saat Keluar Fullscreen / Pindah Tab) -->
    <div class="fullscreen-warning-overlay d-none" id="fullscreenWarningOverlay">
        <div class="card border-danger shadow-lg p-4 text-center rounded-4" style="max-width: 500px; background:#ffffff; border-width: 2px;">
            <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px;">
                <i class="bi bi-exclamation-octagon-fill text-danger display-5"></i>
            </div>
            <h4 class="fw-bold text-danger mb-1">⚠️ RUANG UJIAN TERKUNCI</h4>
            <span class="badge bg-danger mb-3 px-3 py-1.5" style="font-size:12px;" id="violationCountBadge">Pelanggaran Terdeteksi: 1 Kali</span>

            <p class="text-dark small mb-3" id="violationReasonText">
                Anda terdeteksi keluar dari Mode Layar Penuh (Fullscreen) atau berpindah fokus tab/window. Lembar ujian disembunyikan untuk mencegah kecurangan.
            </p>

            <div class="alert alert-danger py-2 px-3 small mb-4 text-start" style="font-size:11px;">
                <i class="bi bi-shield-x me-1"></i> Klik tombol di bawah ini untuk mengembalikan tampilan ke Mode Layar Penuh dan melanjutkan pengerjaan ujian.
            </div>

            <button type="button" class="btn btn-danger btn-lg w-100 fw-bold py-3 rounded-3" id="btnReenterFullscreen">
                <i class="bi bi-unlock-fill me-2"></i> KEMBALI KE LAYAR PENUH & BUKA KUNCI
            </button>
        </div>
    </div>

    <!-- OVERLAY 3: PERMANENT STRIKE 2 LOCK & REQUEST UNLOCK -->
    <div class="fullscreen-warning-overlay d-none" id="permanentLockOverlay" style="z-index: 10000;">
        <div class="card border-danger shadow-lg p-4 text-center rounded-4" style="max-width: 520px; background:#ffffff; border-width: 3px;">
            <div class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center mb-3 mx-auto" style="width:76px;height:76px;">
                <i class="bi bi-x-octagon-fill display-5"></i>
            </div>
            <h4 class="fw-bold text-danger mb-1">🛑 UJIAN TERKUNCI PERMANEN</h4>
            <span class="badge bg-danger mb-3 px-3 py-1.5 fs-6 mx-auto">Kecurangan Berulang (2 Kali Pelanggaran)</span>

            <p class="text-dark small mb-3">
                Anda terdeteksi melakukan <strong>2 kali pelanggaran proctoring</strong> (keluar dari layar penuh atau berpindah tab). Pengerjaan ujian dihentikan secara otomatis.
            </p>

            <div id="unlockRequestFormContainer" class="text-start">
                <div class="mb-3">
                    <label for="alasanBukaKunci" class="form-label font-medium small text-muted">Tuliskan Alasan Permohonan Buka Kunci Ujian <span class="text-danger">*</span></label>
                    <textarea id="alasanBukaKunci" class="form-control form-control-sm" rows="3" placeholder="Contoh: Maaf Pak/Bu, tadi tidak sengaja menekan tombol ESC / ada notifikasi pop-up sistem. Mohon izinkan saya mengulang ujian."></textarea>
                </div>
                <button type="button" class="btn btn-danger btn-lg w-100 fw-bold py-2.5 rounded-3" id="btnSubmitUnlockRequest">
                    <i class="bi bi-send-fill me-2"></i> KIRIM PENGAJUAN BUKA KUNCI KE GURU / ADMIN
                </button>
            </div>

            <div id="unlockRequestSuccessAlert" class="alert alert-success mt-3 mb-0 d-none text-start small">
                <i class="bi bi-check-circle-fill me-1"></i> <strong>Permohonan Buka Kunci Berhasil Terkirim!</strong><br>
                Silakan hubungi Guru Pengampu atau Admin untuk menyetujui (approve) pembukaan kembali akses ujian Anda.
                <div class="mt-3">
                    <a href="{{ route('siswa.ujian.index') }}" class="btn btn-outline-secondary btn-sm w-100">Kembali ke Daftar Ujian</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Ujian (Fixed Top) -->
    <header class="cbt-header shadow-sm">
        <div class="d-flex align-items-center gap-2 overflow-hidden">
            <span class="fw-bold text-truncate text-dark" style="max-width:280px;" title="{{ $ujian->judul }}">
                <i class="bi bi-file-earmark-text text-primary me-1"></i>{{ $ujian->judul }}
            </span>
            <span class="text-muted d-none d-sm-inline">&bull;</span>
            <span class="text-muted small text-truncate d-none d-sm-inline" style="max-width:180px;">
                <i class="bi bi-person-fill me-1"></i>{{ Auth::user()->name }}
            </span>
        </div>

        <div class="d-flex align-items-center gap-3">
            <!-- Font Size Controls -->
            <div class="btn-group btn-group-sm border rounded-2 bg-light d-none d-md-flex">
                <button type="button" class="btn btn-light text-muted" onclick="changeFontSize('small')" title="Ukuran Teks Kecil">A-</button>
                <button type="button" class="btn btn-light text-muted active" onclick="changeFontSize('medium')" title="Ukuran Teks Normal">A</button>
                <button type="button" class="btn btn-light text-muted" onclick="changeFontSize('large')" title="Ukuran Teks Besar">A+</button>
            </div>

            <!-- Saving Status -->
            <span class="small text-muted d-none d-md-inline" id="savingStatus">
                <i class="bi bi-cloud-check-fill text-success me-1"></i> Tersimpan
            </span>

            <!-- Status Fullscreen Lock Badge -->
            <span class="badge bg-success bg-opacity-10 text-success border border-success d-none d-md-inline-flex align-items-center gap-1 py-1.5 px-2.5">
                <i class="bi bi-fullscreen text-success"></i> Fullscreen Active
            </span>

            <!-- Timer -->
            <div class="timer-badge d-flex align-items-center gap-2" id="timerBadge">
                <i class="bi bi-hourglass-split text-primary" id="timerIcon"></i>
                <span id="countdown">00:00:00</span>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="cbt-app-container">

        <!-- Left: Question Area -->
        <main class="cbt-main-content">
            <div class="cbt-card-question">

                <!-- Question Header -->
                <div class="cbt-question-header">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary px-3 py-2 fs-6 fw-bold">SOAL NO. <span id="displaySoalNumber">1</span></span>
                        <span class="text-muted small">dari {{ $soals->count() }} Soal</span>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <!-- Button Peta Nomor Soal -->
                        <button type="button" class="btn btn-sm btn-outline-primary font-semibold py-1.5 px-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavSoal">
                            <i class="bi bi-grid-3x3-gap-fill me-1"></i> Peta Soal
                        </button>
                    </div>
                </div>

                <!-- Questions List -->
                <div class="cbt-question-body">
                    @foreach ($soals as $index => $soal)
                        <div class="question-container d-none" id="questionContainer_{{ $index }}" data-soal-id="{{ $soal->id }}">
                            <!-- Text Soal -->
                            <div class="question-text">{{ $soal->pertanyaan }}</div>

                            <!-- Options List -->
                            <div class="options-list">
                                @php
                                    $selectedAnswer = $jawabanPesertas[$soal->id] ?? '';
                                @endphp

                                @foreach(['A' => $soal->pilihan_a, 'B' => $soal->pilihan_b, 'C' => $soal->pilihan_c, 'D' => $soal->pilihan_d] as $letter => $text)
                                    <div class="option-item {{ $selectedAnswer === $letter ? 'selected' : '' }}" onclick="selectOption({{ $index }}, '{{ $letter }}')">
                                        <div class="option-badge">{{ $letter }}</div>
                                        <div class="option-text">{{ $text }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Bottom Navigation Buttons -->
                <div class="cbt-question-footer">
                    <button type="button" class="btn btn-outline-secondary px-3.5 py-2 fw-semibold" id="btnPrev" onclick="navigatePrev()">
                        <i class="bi bi-arrow-left me-1"></i> Sebelumnya
                    </button>

                    <button type="button" class="btn btn-outline-warning btn-sm fw-semibold px-3 py-2" id="btnRagu">
                        <i class="bi bi-flag-fill me-1"></i> Ragu-Ragu
                    </button>

                    <button type="button" class="btn btn-primary px-4 py-2 fw-semibold" id="btnNext" onclick="navigateNext()">
                        Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                    </button>

                    <button type="button" class="btn btn-success px-4 py-2 fw-bold d-none" id="btnFinish" data-bs-toggle="modal" data-bs-target="#confirmSubmitModal">
                        <i class="bi bi-check-circle-fill me-1"></i> Kumpulkan Ujian
                    </button>
                </div>

            </div>
        </main>

        <!-- Right: Map Grid Sidebar -->
        <aside class="cbt-sidebar">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>Peta Nomor Soal</h6>
                <span class="badge bg-light text-dark border" style="font-size:10px;">Shortcut A-D & Panah</span>
            </div>

            <!-- Grid Buttons -->
            <div class="map-grid" id="mapContainer">
                @foreach ($soals as $index => $soal)
                    @php
                        $answered = isset($jawabanPesertas[$soal->id]);
                        $class = $answered ? 'answered' : '';
                    @endphp
                    <button type="button" class="map-soal-btn {{ $class }}" id="mapBtn_{{ $index }}" onclick="showQuestion({{ $index }})">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>

            <!-- Legend Info -->
            <div class="border-top pt-3 mt-auto text-xs text-muted">
                <div class="d-flex align-items-center mb-2">
                    <span class="d-inline-block bg-success rounded-circle me-2" style="width:12px; height:12px;"></span>
                    <span>Sudah Dijawab</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="d-inline-block bg-warning rounded-circle me-2" style="width:12px; height:12px;"></span>
                    <span>Ragu-Ragu</span>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <span class="d-inline-block bg-light border border-secondary rounded-circle me-2" style="width:12px; height:12px;"></span>
                    <span>Belum Dijawab</span>
                </div>

                <div class="p-2.5 rounded-3 bg-light border text-muted" style="font-size:11px;">
                    <i class="bi bi-shield-lock-fill me-1 text-danger"></i> <strong>Sistem Ujian Terkunci:</strong><br>
                    Modus layar penuh aktif otomatis. Jika membatalkan fullscreen atau membuka aplikasi lain, ujian akan terkunci secara otomatis.
                </div>
            </div>
        </aside>

    </div>

    <!-- Offcanvas Navigation Grid for Mobile -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavSoal" aria-labelledby="offcanvasNavSoalLabel">
        <div class="offcanvas-header border-bottom py-3">
            <h6 class="offcanvas-title font-bold text-dark" id="offcanvasNavSoalLabel">
                <i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i> Peta Nomor Soal
            </h6>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="map-grid" id="mapContainerMobile">
                @foreach ($soals as $index => $soal)
                    @php
                        $answered = isset($jawabanPesertas[$soal->id]);
                        $class = $answered ? 'answered' : '';
                    @endphp
                    <button type="button" class="map-soal-btn {{ $class }}" id="mapBtnMobile_{{ $index }}" onclick="showQuestion({{ $index }})" data-bs-dismiss="offcanvas">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>

            <div class="border-top pt-3 mt-4 text-xs text-muted">
                <div class="d-flex align-items-center mb-2">
                    <span class="d-inline-block bg-success rounded-circle me-2" style="width:12px; height:12px;"></span>
                    <span>Sudah Dijawab</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="d-inline-block bg-warning rounded-circle me-2" style="width:12px; height:12px;"></span>
                    <span>Ragu-Ragu</span>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <span class="d-inline-block bg-light border border-secondary rounded-circle me-2" style="width:12px; height:12px;"></span>
                    <span>Belum Dijawab</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Confirmation Modal -->
    <div class="modal fade" id="confirmSubmitModal" tabindex="-1" aria-labelledby="confirmSubmitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="confirmSubmitModalLabel">
                        <i class="bi bi-file-earmark-check text-success me-2"></i>Review Lembar Jawaban
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <h6 class="fw-bold text-dark mb-3">Ringkasan Pengerjaan Ujian</h6>

                    <!-- Stats Badges -->
                    <div class="row g-2 mb-4">
                        <div class="col-4">
                            <div class="p-2 bg-success bg-opacity-10 border border-success rounded-3 text-success">
                                <span class="d-block fw-bold fs-4" id="reviewAnsweredCount">0</span>
                                <small class="text-xs">Dijawab</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 bg-warning bg-opacity-10 border border-warning rounded-3 text-warning">
                                <span class="d-block fw-bold fs-4" id="reviewFlaggedCount">0</span>
                                <small class="text-xs">Ragu-Ragu</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 bg-secondary bg-opacity-10 border rounded-3 text-muted">
                                <span class="d-block fw-bold fs-4" id="reviewUnansweredCount">0</span>
                                <small class="text-xs">Kosong</small>
                            </div>
                        </div>
                    </div>

                    <!-- Live Grid Map preview -->
                    <div class="p-3 bg-light border rounded-3 mb-4">
                        <span class="text-xs text-muted d-block mb-2 fw-bold text-uppercase">Peta Visual Jawaban:</span>
                        <div class="d-flex flex-wrap justify-content-center gap-1" id="reviewGridContainer">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>

                    <p class="text-muted small mb-0">
                        Apakah Anda yakin ingin mengumpulkan jawaban sekarang? Anda tidak dapat mengubah jawaban setelah diserahkan.
                    </p>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-between">
                    <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Kembali Periksa</button>
                    <form action="{{ route('siswa.ujian.submit', $ujian->id) }}" method="POST" id="submitExamForm">
                        @csrf
                        <button type="submit" class="btn btn-success fw-bold px-4">
                            <i class="bi bi-send-check me-1"></i> Ya, Kumpulkan Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Strict Exam Engine & Fullscreen Lock JavaScript -->
    <script>
        let currentQuestionIndex = 0;
        const totalQuestions = {{ $soals->count() }};
        let remainingSeconds = {{ $remainingSeconds }};
        const ujianId = {{ $ujian->id }};

        // Ragu-ragu State
        let flaggedQuestions = JSON.parse(localStorage.getItem(`ujian_flagged_${ujianId}`) || '{}');

        // STRICT MANDATORY FULLSCREEN LOCK PROCTORING ENGINE
        let violationCount = 0;
        let isExamStarted = false;

        const entryOverlay = document.getElementById('fullscreenEntryOverlay');
        const warningOverlay = document.getElementById('fullscreenWarningOverlay');
        const btnEnterMandatoryFullscreen = document.getElementById('btnEnterMandatoryFullscreen');
        const btnReenterFullscreen = document.getElementById('btnReenterFullscreen');
        const violationBadge = document.getElementById('violationCountBadge');
        const violationReasonText = document.getElementById('violationReasonText');

        let isHandlingViolation = false;

        // Start Mandatory Fullscreen
        btnEnterMandatoryFullscreen.addEventListener('click', () => {
            requestNativeFullscreen();
            isExamStarted = true;
            entryOverlay.classList.add('d-none');
        });

        // Re-enter Fullscreen on Violation Unlock
        btnReenterFullscreen.addEventListener('click', () => {
            requestNativeFullscreen();
            warningOverlay.classList.add('d-none');
            setTimeout(() => {
                isHandlingViolation = false;
            }, 1200);
        });

        function requestNativeFullscreen() {
            const el = document.documentElement;
            if (el.requestFullscreen) {
                el.requestFullscreen().catch(() => {});
            } else if (el.webkitRequestFullscreen) {
                el.webkitRequestFullscreen();
            } else if (el.msRequestFullscreen) {
                el.msRequestFullscreen();
            }
        }

        function handleViolation(reason) {
            if (!isExamStarted) return; // Only trigger after entry screen dismissed

            const permanentLock = document.getElementById('permanentLockOverlay');
            if (isHandlingViolation || 
                (warningOverlay && !warningOverlay.classList.contains('d-none')) || 
                (permanentLock && !permanentLock.classList.contains('d-none'))) {
                return;
            }

            isHandlingViolation = true;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('siswa.ujian.catat-pelanggaran', $ujian->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ reason: reason })
            })
            .then(res => res.json())
            .then(data => {
                const count = data.violation_count || (violationCount + 1);
                violationCount = count;

                if (data.status === 'locked' || count >= 2) {
                    isExamStarted = false; // Stop further proctoring triggers
                    warningOverlay.classList.add('d-none');
                    if (permanentLock) permanentLock.classList.remove('d-none');
                    showToastAlert("UJIAN TERKUNCI PERMANEN! Terdeteksi 2 kali pelanggaran.", "danger");
                } else {
                    violationBadge.textContent = `Pelanggaran Terdeteksi: ${count} Kali (Batas Maksimal: 2 Kali)`;
                    violationReasonText.innerHTML = `<strong>Peringatan Proctoring:</strong> ${reason}. Lembar ujian disembunyikan. Peringatan ke-${count} dari 2!`;
                    warningOverlay.classList.remove('d-none');
                    showToastAlert(`Pelanggaran ke-${count}: ${reason}`, "danger");
                }
            })
            .catch(() => {
                violationCount++;
                if (violationCount >= 2) {
                    isExamStarted = false;
                    warningOverlay.classList.add('d-none');
                    if (permanentLock) permanentLock.classList.remove('d-none');
                } else {
                    warningOverlay.classList.remove('d-none');
                }
            })
            .finally(() => {
                setTimeout(() => {
                    if (warningOverlay && warningOverlay.classList.contains('d-none')) {
                        isHandlingViolation = false;
                    }
                }, 1000);
            });
        }
        window.handleViolation = handleViolation;

        // Unlock Request Handler
        const btnSubmitUnlockRequest = document.getElementById('btnSubmitUnlockRequest');
        if (btnSubmitUnlockRequest) {
            btnSubmitUnlockRequest.addEventListener('click', function() {
                const alasanInput = document.getElementById('alasanBukaKunci');
                const alasan = alasanInput ? alasanInput.value.trim() : '';

                if (!alasan) {
                    alert("Mohon isi alasan pengajuan buka kunci terlebih dahulu.");
                    return;
                }

                btnSubmitUnlockRequest.disabled = true;
                btnSubmitUnlockRequest.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Mengirim...`;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch("{{ route('siswa.ujian.ajukan-buka-kunci', $ujian->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ alasan: alasan })
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('unlockRequestFormContainer').classList.add('d-none');
                    document.getElementById('unlockRequestSuccessAlert').classList.remove('d-none');
                })
                .catch(() => {
                    alert("Gagal mengirim pengajuan. Silakan coba beberapa saat lagi.");
                    btnSubmitUnlockRequest.disabled = false;
                    btnSubmitUnlockRequest.innerHTML = `<i class="bi bi-send-fill me-2"></i> KIRIM PENGAJUAN BUKA KUNCI KE GURU / ADMIN`;
                });
            });
        }

        // Fullscreen Change Listener
        document.addEventListener('fullscreenchange', () => {
            if (isExamStarted && !document.fullscreenElement) {
                handleViolation("Anda membatalkan Mode Layar Penuh (Fullscreen)");
            }
        });
        document.addEventListener('webkitfullscreenchange', () => {
            if (isExamStarted && !document.webkitFullscreenElement) {
                handleViolation("Anda membatalkan Mode Layar Penuh (Fullscreen)");
            }
        });

        // Tab Switching / Visibility Listener
        document.addEventListener('visibilitychange', () => {
            if (isExamStarted && document.hidden) {
                handleViolation("Anda berpindah tab browser atau me-minimize jendela");
            }
        });

        // Window Focus Loss Listener
        window.addEventListener('blur', () => {
            if (isExamStarted && !document.querySelector('.modal.show')) {
                handleViolation("Fokus browser berpindah ke aplikasi luar");
            }
        });

        // Font Size Adjuster
        function changeFontSize(size) {
            const root = document.documentElement;
            if (size === 'small') root.style.setProperty('--cbt-font-size', '0.875rem');
            if (size === 'medium') root.style.setProperty('--cbt-font-size', '1rem');
            if (size === 'large') root.style.setProperty('--cbt-font-size', '1.175rem');
        }

        // Initialize Flagged Grid Buttons
        Object.keys(flaggedQuestions).forEach(idx => {
            if (flaggedQuestions[idx]) {
                const mapBtn = document.getElementById(`mapBtn_${idx}`);
                if (mapBtn) {
                    mapBtn.classList.remove('answered');
                    mapBtn.classList.add('flagged');
                }
            }
        });

        // Show Initial Question
        showQuestion(0);

        // Run initial display immediately
        displayTime(remainingSeconds);

        // Countdown Timer Loop
        const countdownTimer = setInterval(function() {
            if (remainingSeconds <= 0) {
                clearInterval(countdownTimer);
                autoSubmitExam();
            } else {
                remainingSeconds--;
                displayTime(remainingSeconds);
            }
        }, 1000);

        function displayTime(sec) {
            if (sec < 0) sec = 0;
            const h = Math.floor(sec / 3600);
            const m = Math.floor((sec % 3600) / 60);
            const s = sec % 60;

            const displayM = m < 10 ? '0' + m : m;
            const displayS = s < 10 ? '0' + s : s;

            if (h > 0) {
                const displayH = h < 10 ? '0' + h : h;
                document.getElementById('countdown').textContent = `${displayH}:${displayM}:${displayS}`;
            } else {
                document.getElementById('countdown').textContent = `${displayM}:${displayS}`;
            }

            const timerBadge = document.getElementById('timerBadge');
            const timerIcon = document.getElementById('timerIcon');

            if (sec <= 300) {
                timerBadge.classList.add('pulse-danger');
                timerIcon.className = 'bi bi-alarm-fill text-white';

                if (!window.fiveMinuteWarningShown) {
                    window.fiveMinuteWarningShown = true;
                    showToastAlert("Peringatan: Waktu ujian tersisa kurang dari 5 menit!", "danger");
                }
            }
        }

        // Show Question Box
        function showQuestion(index) {
            if (index < 0 || index >= totalQuestions) return;

            document.querySelectorAll('.question-container').forEach(el => el.classList.add('d-none'));
            document.getElementById(`questionContainer_${index}`).classList.remove('d-none');

            document.querySelectorAll('.map-soal-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById(`mapBtn_${index}`).classList.add('active');

            currentQuestionIndex = index;
            document.getElementById('displaySoalNumber').textContent = index + 1;

            document.getElementById('btnPrev').disabled = (index === 0);

            if (index === totalQuestions - 1) {
                document.getElementById('btnNext').classList.add('d-none');
                document.getElementById('btnFinish').classList.remove('d-none');
            } else {
                document.getElementById('btnNext').classList.remove('d-none');
                document.getElementById('btnFinish').classList.add('d-none');
            }

            const isFlagged = flaggedQuestions[index] === true;
            const btnRagu = document.getElementById('btnRagu');
            if (isFlagged) {
                btnRagu.className = 'btn btn-warning btn-sm fw-semibold px-3';
            } else {
                btnRagu.className = 'btn btn-outline-warning btn-sm fw-semibold px-3';
            }
        }

        function navigateNext() { showQuestion(currentQuestionIndex + 1); }
        function navigatePrev() { showQuestion(currentQuestionIndex - 1); }

        // Ragu-ragu Toggle
        document.getElementById('btnRagu').addEventListener('click', function() {
            const isCurrentlyFlagged = flaggedQuestions[currentQuestionIndex] === true;
            const mapBtn = document.getElementById(`mapBtn_${currentQuestionIndex}`);

            if (isCurrentlyFlagged) {
                flaggedQuestions[currentQuestionIndex] = false;
                this.className = 'btn btn-outline-warning btn-sm fw-semibold px-3';
                mapBtn.classList.remove('flagged');
                if (hasSelectedAnswer(currentQuestionIndex)) {
                    mapBtn.classList.add('answered');
                }
            } else {
                flaggedQuestions[currentQuestionIndex] = true;
                this.className = 'btn btn-warning btn-sm fw-semibold px-3';
                mapBtn.classList.remove('answered');
                mapBtn.classList.add('flagged');
            }

            localStorage.setItem(`ujian_flagged_${ujianId}`, JSON.stringify(flaggedQuestions));
        });

        // Select Option & AJAX Save
        function selectOption(qIndex, optionLetter) {
            const container = document.getElementById(`questionContainer_${qIndex}`);
            const soalId = container.getAttribute('data-soal-id');

            container.querySelectorAll('.option-item').forEach(item => {
                item.classList.remove('selected');
                if (item.querySelector('.option-badge').textContent.trim() === optionLetter) {
                    item.classList.add('selected');
                }
            });

            const mapBtn = document.getElementById(`mapBtn_${qIndex}`);
            if (!flaggedQuestions[qIndex]) {
                mapBtn.classList.add('answered');
            }

            saveAnswerAjax(soalId, optionLetter);
        }

        // Clear Answer
        function clearAnswer() {
            const container = document.getElementById(`questionContainer_${currentQuestionIndex}`);
            const soalId = container.getAttribute('data-soal-id');

            container.querySelectorAll('.option-item').forEach(item => item.classList.remove('selected'));

            const mapBtn = document.getElementById(`mapBtn_${currentQuestionIndex}`);
            mapBtn.classList.remove('answered');

            saveAnswerAjax(soalId, null);
        }

        function hasSelectedAnswer(qIndex) {
            const container = document.getElementById(`questionContainer_${qIndex}`);
            return container.querySelector('.option-item.selected') !== null;
        }

        // Save Answer via AJAX
        function saveAnswerAjax(soalId, answer) {
            const statusEl = document.getElementById('savingStatus');
            statusEl.innerHTML = `<span class="spinner-border spinner-border-sm me-1 text-primary" style="width:12px;height:12px;"></span> Menyimpan...`;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('siswa.ujian.simpan-jawaban', $ujian->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ soal_id: soalId, jawaban: answer })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    statusEl.innerHTML = `<i class="bi bi-cloud-check-fill text-success me-1"></i> Tersimpan`;
                } else {
                    statusEl.innerHTML = `<i class="bi bi-exclamation-circle-fill text-danger me-1"></i> Gagal Menyimpan`;
                }
            })
            .catch(() => {
                statusEl.innerHTML = `<i class="bi bi-wifi-off text-danger me-1"></i> Koneksi Terputus`;
            });
        }

        // Keyboard Shortcuts (A, B, C, D, Arrow Left, Arrow Right, R)
        document.addEventListener('keydown', function(e) {
            if (document.querySelector('.modal.show') || warningOverlay.classList.contains('d-none') === false || entryOverlay.classList.contains('d-none') === false) return;

            const key = e.key.toUpperCase();

            if (['A', 'B', 'C', 'D'].includes(key)) {
                selectOption(currentQuestionIndex, key);
            } else if (key === 'ARROWLEFT') {
                navigatePrev();
            } else if (key === 'ARROWRIGHT') {
                navigateNext();
            } else if (key === 'R') {
                document.getElementById('btnRagu').click();
            } else if (key === 'F11' || key === 'F5') {
                e.preventDefault();
            }
        });

        // Submit Handler (Disable violation checks when submitting)
        const submitForm = document.getElementById('submitExamForm');
        if (submitForm) {
            submitForm.addEventListener('submit', function() {
                isExamStarted = false; // Turn off violation trigger during page submit
                localStorage.removeItem(`ujian_flagged_${ujianId}`);
            });
        }

        // Submit Auto on Timeout
        function autoSubmitExam() {
            isExamStarted = false;
            localStorage.removeItem(`ujian_flagged_${ujianId}`);
            showToastAlert("Waktu ujian telah habis! Jawaban diserahkan secara otomatis...", "danger");
            setTimeout(() => {
                document.getElementById('submitExamForm').submit();
            }, 1000);
        }

        // Block Right Click, Copy, Paste, Print, Inspect
        document.addEventListener('contextmenu', e => e.preventDefault());
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && ['c', 'v', 'u', 'a', 'p', 's'].includes(e.key.toLowerCase())) {
                e.preventDefault();
                showToastAlert("Aksi disalin/tempel/cetak dibatasi!", "warning");
            }
        });

        // Review Modal Dynamic Builder
        const confirmModal = document.getElementById('confirmSubmitModal');
        confirmModal.addEventListener('show.bs.modal', function () {
            let answeredCount = 0, unansweredCount = 0, flaggedCount = 0;
            let gridHtml = '';

            for (let i = 0; i < totalQuestions; i++) {
                const isAnswered = hasSelectedAnswer(i);
                const isFlagged = flaggedQuestions[i] === true;

                let bgClass = 'bg-light border text-dark';
                if (isFlagged) {
                    flaggedCount++;
                    bgClass = 'bg-warning text-white border-warning';
                } else if (isAnswered) {
                    answeredCount++;
                    bgClass = 'bg-success text-white border-success';
                } else {
                    unansweredCount++;
                }

                gridHtml += `<div class="d-inline-flex align-items-center justify-content-center fw-bold rounded-2 text-xs ${bgClass}" style="width:28px; height:28px; margin:2px;">${i + 1}</div>`;
            }

            document.getElementById('reviewAnsweredCount').textContent = answeredCount;
            document.getElementById('reviewUnansweredCount').textContent = unansweredCount;
            document.getElementById('reviewFlaggedCount').textContent = flaggedCount;
            document.getElementById('reviewGridContainer').innerHTML = gridHtml;
        });

        // Toast Notification Function
        function showToastAlert(message, type = 'info') {
            let container = document.querySelector('.toast-modern-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'toast-modern-container';
                document.body.appendChild(container);
            }

            const toast = document.createElement('div');
            toast.className = `toast-modern ${type}`;

            let icon = 'bi-info-circle-fill text-info';
            if (type === 'success') icon = 'bi-check-circle-fill text-success';
            if (type === 'warning') icon = 'bi-exclamation-triangle-fill text-warning';
            if (type === 'danger') icon = 'bi-exclamation-octagon-fill text-danger';

            toast.innerHTML = `<i class="bi ${icon} fs-5 me-2"></i><div class="text-sm font-semibold">${message}</div>`;
            container.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-10px)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }
    </script>
</body>
</html>
