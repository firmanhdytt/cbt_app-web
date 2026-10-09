<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - CBT SMAN 5 Medan</title>
    <link rel="icon" type="image/png" href="/images/logo-sman5medan.png">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CBT Design System CSS -->
    <link href="/css/design-system.css" rel="stylesheet">
    
    <!-- Theme Initializer -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('cbt-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    <style>
        html {
            overflow-x: hidden !important;
            overflow-y: auto !important;
            height: auto !important;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif !important;
            min-height: 100vh !important;
            height: auto !important;
            overflow-x: hidden !important;
            overflow-y: auto !important;
            background-color: var(--bg-body, #f8fafc) !important;
            color: var(--text-primary, #1e293b) !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: flex-start !important;
            align-items: center !important;
            position: relative !important;
            padding: 3.5rem 1.25rem 5rem !important;
            margin: 0 !important;
        }

        /* Subtle ambient glow in background */
        .ambient-bg {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 15% 15%, rgba(99, 102, 241, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 85% 85%, rgba(16, 185, 129, 0.07) 0%, transparent 40%);
            pointer-events: none;
            z-index: 0;
        }

        [data-theme="dark"] .ambient-bg {
            background: 
                radial-gradient(circle at 15% 15%, rgba(99, 102, 241, 0.15) 0%, transparent 45%),
                radial-gradient(circle at 85% 85%, rgba(16, 185, 129, 0.12) 0%, transparent 45%);
        }

        .login-wrapper {
            width: 100%;
            max-width: 460px;
            margin: auto;
            position: relative;
            z-index: 1;
        }

        .auth-card {
            background-color: var(--bg-card, #ffffff);
            border: 1px solid var(--border-color, #e2e8f0);
            border-radius: 20px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.08), 0 4px 12px rgba(0, 0, 0, 0.03);
            padding: 2.25rem 2.25rem;
            transition: all 0.3s ease;
        }

        @media (max-width: 576px) {
            .auth-card {
                padding: 1.75rem 1.25rem;
                border-radius: 16px;
            }
        }

        .school-logo-badge {
            width: 68px;
            height: 68px;
            border-radius: 18px;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.08) 0%, rgba(99, 102, 241, 0.03) 100%);
            border: 1px solid rgba(79, 70, 229, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.1);
        }

        .form-control-clean {
            background-color: var(--bg-body, #f8fafc);
            border: 1.5px solid var(--border-color, #cbd5e1);
            color: var(--text-primary, #0f172a);
            font-size: 0.925rem;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .form-control-clean:focus {
            background-color: var(--bg-card, #ffffff);
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
            color: var(--text-primary, #0f172a);
        }

        .input-pwd-wrapper {
            position: relative;
        }

        .pwd-toggle-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: var(--text-secondary, #64748b);
            font-size: 1.15rem;
            cursor: pointer;
            padding: 2px 6px;
            transition: color 0.15s ease;
        }

        .pwd-toggle-btn:hover {
            color: #4f46e5;
        }

        .btn-submit-clean {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: #ffffff;
            border: none;
            font-weight: 600;
            padding: 0.8rem 1rem;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.28);
        }

        .btn-submit-clean:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.38);
            color: #ffffff;
        }

        /* APK Download Card Banner */
        .apk-download-box {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(5, 150, 105, 0.04) 100%);
            border: 1px dashed rgba(16, 185, 129, 0.4);
            border-radius: 14px;
            padding: 1rem 1.15rem;
            transition: all 0.2s ease;
        }

        .apk-download-box:hover {
            border-color: rgba(16, 185, 129, 0.8);
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.12) 0%, rgba(5, 150, 105, 0.07) 100%);
            transform: translateY(-1px);
        }

        .btn-download-apk {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: #ffffff;
            border: none;
            font-weight: 600;
            font-size: 0.825rem;
            padding: 0.5rem 0.9rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        .btn-download-apk:hover {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        /* APK Guide Details & Accordion */
        details summary::-webkit-details-marker {
            display: none;
        }
        details summary {
            list-style: none;
        }
        details[open] .guide-chevron {
            transform: rotate(180deg);
        }
        .guide-chevron {
            transition: transform 0.2s ease;
        }
        .cursor-pointer {
            cursor: pointer;
        }

        .divider-clean {
            display: flex;
            align-items: center;
            text-align: center;
            color: var(--text-secondary, #64748b);
            font-size: 0.75rem;
            margin: 1.25rem 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .divider-clean::before,
        .divider-clean::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border-color, #e2e8f0);
        }

        .divider-clean span {
            padding: 0 0.75rem;
        }

        .btn-google-clean {
            background-color: var(--bg-card, #ffffff);
            border: 1px solid var(--border-color, #cbd5e1);
            color: var(--text-primary, #0f172a);
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.7rem 1rem;
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .btn-google-clean:hover {
            background-color: var(--bg-body, #f8fafc);
            border-color: #94a3b8;
            color: var(--text-primary, #0f172a);
        }

        .top-action-bar {
            position: absolute;
            top: 1.25rem;
            right: 1.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 10;
        }

        .btn-icon-soft {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background-color: var(--bg-card, #ffffff);
            border: 1px solid var(--border-color, #e2e8f0);
            color: var(--text-secondary, #64748b);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-icon-soft:hover {
            color: #4f46e5;
            border-color: #c7d2fe;
            background-color: var(--bg-body, #f8fafc);
        }
    </style>
</head>
<body>

<div class="ambient-bg"></div>

<!-- Top Navigation & Controls -->
<div class="top-action-bar">
    <a href="/" class="btn-icon-soft text-decoration-none" title="Kembali ke Beranda">
        <i class="bi bi-house-door-fill fs-6"></i>
    </a>
    <button class="btn-icon-soft border-0" onclick="toggleTheme()" title="Ganti Mode Gelap/Terang">
        <i class="bi bi-sun-fill fs-6 d-none" id="themeSunIcon"></i>
        <i class="bi bi-moon-fill fs-6 d-none" id="themeMoonIcon"></i>
    </button>
</div>

<!-- Main Centered Container -->
<div class="login-wrapper">

    <!-- Header Section -->
    <div class="text-center mb-4">
        <div class="school-logo-badge">
            <img src="/images/logo-sman5medan.png" alt="Logo SMAN 5 Medan" width="46" height="46" class="object-contain">
        </div>
        <h4 class="fw-bold mb-1" style="letter-spacing: -0.02em;">CBT SMA NEGERI 5 MEDAN</h4>
        <p class="text-custom-secondary small mb-0">Portal Ujian Komputer & Evaluasi Akademik Online</p>
    </div>

    <!-- Auth Card -->
    <div class="auth-card">

        <!-- Card Title -->
        <div class="mb-4 text-center">
            <h5 class="fw-bold mb-1">Masuk ke Akun Anda</h5>
            <p class="text-custom-secondary small mb-0">Silakan masukkan akun siswa, guru, atau admin</p>
        </div>

        <!-- Session Status Alert -->
        @if (session('status'))
            <div class="alert alert-success d-flex align-items-center gap-2 p-2.5 mb-3 text-xs border-0 shadow-sm rounded-3" role="alert">
                <i class="bi bi-check-circle-fill text-success fs-6"></i>
                <div>{{ session('status') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger d-flex align-items-start gap-2 p-2.5 mb-3 text-xs border-0 shadow-sm rounded-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-6 mt-0.5"></i>
                <div class="small">
                    {{ $errors->first() }}
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email or Username Input -->
            <div class="mb-3">
                <label for="email" class="form-label text-custom-secondary small fw-semibold mb-1">
                    <i class="bi bi-person me-1"></i> Email atau Username
                </label>
                <input type="text" class="form-control form-control-clean @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email') }}" required autofocus 
                       placeholder="Contoh: 123456 atau nama@sekolah.id">
            </div>

            <!-- Password Input -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label text-custom-secondary small fw-semibold mb-0">
                        <i class="bi bi-shield-lock me-1"></i> Kata Sandi
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-primary text-decoration-none fw-semibold">Lupa Password?</a>
                    @endif
                </div>
                <div class="input-pwd-wrapper">
                    <input type="password" class="form-control form-control-clean @error('password') is-invalid @enderror" 
                           id="password" name="password" required placeholder="Masukkan kata sandi">
                    <button type="button" class="pwd-toggle-btn" onclick="togglePasswordVisibility()" title="Lihat/Sembunyikan Sandi">
                        <i class="bi bi-eye" id="pwdEyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me Checkbox -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                    <label class="form-check-label text-xs text-custom-secondary cursor-pointer" for="remember_me">
                        Ingat saya di perangkat ini
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-submit-clean w-100 mb-2 d-flex align-items-center justify-content-center gap-2">
                <span>Masuk Sekarang</span>
                <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <!-- Social SSO Login Divider -->
        <div class="divider-clean">
            <span>Atau masuk dengan</span>
        </div>

        <!-- Google Login Button -->
        <a href="{{ route('google.redirect', ['mode' => 'login']) }}" class="btn btn-google-clean w-100 d-flex align-items-center justify-content-center gap-2">
            <svg width="18" height="18" viewBox="0 0 24 24">
                <path fill="#EA4335" d="M12 5c1.56 0 2.97.57 4.07 1.51l3.05-3.05C17.26 1.7 14.81 1 12 1 7.42 1 3.51 3.58 1.63 7.34l3.71 2.88C6.26 7.42 8.87 5 12 5z"/>
                <path fill="#4285F4" d="M23.49 12.27c0-.79-.07-1.54-.19-2.27H12v4.51h6.47c-.29 1.48-1.14 2.73-2.4 3.58l3.71 2.88c2.16-1.99 3.71-4.93 3.71-8.7z"/>
                <path fill="#FBBC05" d="M5.34 14.78c-.24-.72-.38-1.49-.38-2.28s.14-1.56.38-2.28L1.63 7.34C.59 9.42 0 11.64 0 12.5s.59 3.08 1.63 5.16l3.71-2.88z"/>
                <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.71-2.88c-1.07.72-2.45 1.15-4.22 1.15-3.13 0-5.74-2.42-6.66-5.22L1.63 17.02C3.51 20.78 7.42 24 12 24z"/>
            </svg>
            <span class="fw-medium">Google Akun Belajar / GMail</span>
        </a>

        <!-- Register Link -->
        <div class="mt-3 text-center text-xs text-custom-secondary">
            Belum memiliki akun peserta? 
            <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none ms-1">Daftar Akun Baru</a>
        </div>

    </div>

    <!-- Official Exambro APK Download Card -->
    <div class="apk-download-box mt-3">
        <div class="d-flex align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2.5">
                <div class="rounded-3 bg-success bg-opacity-10 text-success p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="bi bi-android2 fs-4"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold text-custom-primary small" style="line-height: 1.2;">Exambro Mobile APK</span>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size: 0.65rem;">v1.0 • 11 MB</span>
                    </div>
                    <span class="text-custom-secondary" style="font-size: 0.72rem;">Wajib untuk ujian via smartphone Android</span>
                </div>
            </div>
            <a href="{{ route('download.exambro') }}" class="btn-download-apk" title="Download Aplikasi Exambro Resmi">
                <i class="bi bi-cloud-arrow-down-fill"></i>
                <span>Unduh APK</span>
            </a>
        </div>

        <!-- Panduan Singkat Pemasangan APK -->
        <details class="mt-2.5 pt-2 border-top border-success-subtle apk-guide-details">
            <summary class="d-flex align-items-center justify-content-between text-success fw-semibold cursor-pointer user-select-none" style="font-size: 0.74rem;">
                <span class="text-custom-secondary">
                    <i class="bi bi-shield-lock-fill text-success me-1"></i> Mode Kiosk Aman & Anti-Curang
                </span>
                <span class="d-inline-flex align-items-center gap-1">
                    Panduan Pasang <i class="bi bi-chevron-down guide-chevron"></i>
                </span>
            </summary>
            <div class="mt-2 p-2.5 rounded-3 bg-body border text-custom-secondary" style="font-size: 0.74rem; line-height: 1.55;">
                <ol class="mb-0 ps-3">
                    <li>Klik tombol <b>Unduh APK</b> dan pasang (install) di smartphone Android.</li>
                    <li>Izinkan <i>"Install Unknown Apps" / Sumber Tidak Dikenal</i> pada pengaturan bila diminta.</li>
                    <li>Buka aplikasi <b>CBT Exambro</b>, layar otomatis terkunci selama ujian.</li>
                    <li>Masukkan email / username dan kata sandi peserta untuk mulai ujian.</li>
                </ol>
            </div>
        </details>
    </div>

    <!-- Footer Copyright -->
    <div class="text-center mt-4 text-custom-secondary" style="font-size: 0.75rem;">
        &copy; {{ date('Y') }} SMA Negeri 5 Medan. Dilindungi Hak Cipta.
    </div>

</div>

<script>
    function togglePasswordVisibility() {
        const pwdInput = document.getElementById('password');
        const eyeIcon = document.getElementById('pwdEyeIcon');
        if (pwdInput.type === 'password') {
            pwdInput.type = 'text';
            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');
        } else {
            pwdInput.type = 'password';
            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');
        }
    }

    function toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('cbt-theme', newTheme);
        syncThemeIcons(newTheme);
    }

    function syncThemeIcons(theme) {
        const sun = document.getElementById('themeSunIcon');
        const moon = document.getElementById('themeMoonIcon');
        if (sun && moon) {
            if (theme === 'dark') {
                sun.classList.remove('d-none');
                moon.classList.add('d-none');
            } else {
                sun.classList.add('d-none');
                moon.classList.remove('d-none');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('cbt-theme') || 'light';
        syncThemeIcons(savedTheme);
    });
</script>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
