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
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
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
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Left Banner Column */
        .login-banner-left {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3.5rem 4rem;
            position: relative;
            overflow: hidden;
        }

        .login-banner-left::before {
            content: '';
            position: absolute;
            width: 380px;
            height: 380px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, rgba(99, 102, 241, 0) 70%);
            top: -100px;
            left: -100px;
            pointer-events: none;
        }

        .brand-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 700;
            color: #ffffff;
            backdrop-filter: blur(8px);
        }

        .feature-badge-item {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 12px 18px;
            border-radius: 12px;
            backdrop-filter: blur(6px);
        }

        /* Right Form Column */
        .login-form-right {
            background-color: var(--bg-card);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3.5rem 4rem;
            position: relative;
            transition: background-color var(--transition-speed) ease;
        }

        .form-control-clean {
            background-color: var(--bg-body);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            font-size: 0.925rem;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .form-control-clean:focus {
            background-color: var(--bg-card);
            border-color: #4f46e5;
            box-shadow: 0 0 0 3.5px rgba(79, 70, 229, 0.12);
            color: var(--text-primary);
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
            color: var(--text-secondary);
            font-size: 1.1rem;
            cursor: pointer;
            padding: 2px 6px;
        }

        .btn-submit-clean {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: #ffffff;
            border: none;
            font-weight: 600;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        .btn-submit-clean:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(79, 70, 229, 0.35);
            color: #ffffff;
        }

        .divider-clean {
            display: flex;
            align-items: center;
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.75rem;
            margin: 1.5rem 0 1rem 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .divider-clean::before,
        .divider-clean::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border-color);
        }

        .divider-clean span {
            padding: 0 0.75rem;
        }

        .btn-google-clean {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.65rem 1rem;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .btn-google-clean:hover {
            background-color: var(--bg-body);
            border-color: #cbd5e1;
            color: var(--text-primary);
        }

        @media (max-width: 991px) {
            .login-banner-left {
                display: none;
            }
            .login-form-right {
                padding: 2.5rem 1.5rem;
                min-height: 100vh;
            }
        }
    </style>
</head>
<body class="bg-custom-body">

<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">
        
        <!-- Left Banner Column -->
        <div class="col-lg-5 col-xl-6 login-banner-left">
            <div>
                <div class="brand-pill mb-4">
                    <img src="/images/logo-sman5medan.png" alt="Logo SMAN 5 Medan" width="28" height="28" class="object-contain">
                    <span>SMA NEGERI 5 MEDAN</span>
                </div>
            </div>

            <div class="my-auto py-4">
                <h2 class="display-6 fw-bold text-white mb-3" style="line-height: 1.25;">
                    Portal Ujian CBT SMA Negeri 5 Medan
                </h2>
                <p class="text-white-50 text-base mb-4" style="max-width: 440px;">
                    Sistem evaluasi dan ujian digital resmi SMAN 5 Medan untuk pelaksanaan ujian yang jujur, terintegrasi, dan transparan.
                </p>

                <!-- Simple Minimalist Feature Badges -->
                <div class="d-flex flex-column gap-2.5" style="max-width: 380px;">
                    <div class="feature-badge-item">
                        <i class="bi bi-shield-check text-success fs-5"></i>
                        <span class="text-xs fw-semibold text-white">Exambro Mobile & Proctoring Security</span>
                    </div>
                    <div class="feature-badge-item">
                        <i class="bi bi-lightning-charge-fill text-warning fs-5"></i>
                        <span class="text-xs fw-semibold text-white">Simpan Jawaban Real-Time Otomatis</span>
                    </div>
                </div>
            </div>

            <div class="text-white-50 text-xs">
                &copy; {{ date('Y') }} SMA Negeri 5 Medan. All rights reserved.
            </div>
        </div>

        <!-- Right Form Column -->
        <div class="col-lg-7 col-xl-6 login-form-right">
            
            <!-- Theme Toggler (Top Right) -->
            <div class="position-absolute top-0 end-0 p-3 p-md-4">
                <button class="btn text-custom-primary border-0 p-2" onclick="toggleTheme()" title="Ganti Tema">
                    <i class="bi bi-sun-fill fs-5 d-none" id="themeSunIcon"></i>
                    <i class="bi bi-moon-fill fs-5 d-none" id="themeMoonIcon"></i>
                </button>
            </div>

            <!-- Form Container -->
            <div class="mx-auto w-100" style="max-width: 400px;">
                
                <!-- Mobile Logo Header -->
                <div class="d-lg-none text-center mb-4">
                    <img src="/images/logo-sman5medan.png" alt="Logo SMAN 5 Medan" width="64" height="64" class="mx-auto mb-2 object-contain">
                    <div class="fs-5 fw-bold text-custom-primary">CBT SMAN 5 Medan</div>
                </div>

                <h3 class="fw-bold text-custom-primary mb-1">Selamat Datang Kembali</h3>
                <p class="text-custom-secondary text-sm mb-4">Silakan masuk menggunakan email atau username Anda.</p>

                <!-- Session Status Alert -->
                @if (session('status'))
                    <div class="alert alert-success p-2.5 mb-3 text-xs border-0 shadow-sm" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email or Username Input -->
                    <div class="mb-3">
                        <label for="email" class="form-label text-custom-secondary small font-medium mb-1">Email atau Username</label>
                        <input type="text" class="form-control form-control-clean @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required autofocus 
                               placeholder="Masukkan email atau username">
                        @error('email')
                            <div class="invalid-feedback text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="form-label text-custom-secondary small font-medium mb-0">Kata Sandi</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs text-primary text-decoration-none fw-medium">Lupa Password?</a>
                            @endif
                        </div>
                        <div class="input-pwd-wrapper">
                            <input type="password" class="form-control form-control-clean @error('password') is-invalid @enderror" 
                                   id="password" name="password" required placeholder="Masukkan kata sandi">
                            <button type="button" class="pwd-toggle-btn" onclick="togglePasswordVisibility()" title="Lihat Password">
                                <i class="bi bi-eye" id="pwdEyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                            <label class="form-check-label text-xs text-custom-secondary" for="remember_me">
                                Ingat saya di perangkat ini
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-submit-clean w-100 mb-2">
                        Masuk Sekarang
                    </button>
                </form>

                <!-- Social SSO Login Divider -->
                <div class="divider-clean">
                    <span>Atau masuk dengan</span>
                </div>

                <!-- Google Login Button -->
                <a href="{{ route('google.redirect', ['mode' => 'login']) }}" class="btn btn-google-clean w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-google text-danger"></i> Google GMail
                </a>

                <!-- Register Link Footer -->
                <div class="mt-4 pt-3 text-center text-xs text-custom-secondary border-top">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-primary font-bold text-decoration-none ms-1">Daftar Sekarang</a>
                </div>

            </div>
        </div>

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
</body>
</html>
