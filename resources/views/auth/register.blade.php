<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - CBT Portal</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .register-container {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 580px;
            padding: 2.5rem;
            transition: all var(--transition-speed) ease;
        }
        .step-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--border-color);
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            margin: 0 auto 8px auto;
            transition: all 0.2s ease;
        }
        .step-item.active .step-circle {
            background-color: var(--primary);
            color: #ffffff;
            box-shadow: 0 0 0 4px var(--primary-light);
        }
        .step-item.completed .step-circle {
            background-color: var(--success);
            color: #ffffff;
        }
        .step-line {
            flex-grow: 1;
            height: 2px;
            background-color: var(--border-color);
            margin-top: 16px;
        }
        .step-item {
            text-align: center;
            position: relative;
            flex-basis: 0;
            flex-grow: 1;
        }
        .step-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .step-item.active .step-label {
            color: var(--primary);
            font-weight: 700;
        }
    </style>
</head>
<body class="bg-custom-body">

<!-- Theme Toggler (Top Right) -->
<div class="position-absolute top-0 end-0 p-4">
    <button class="btn text-custom-primary border-0" onclick="toggleTheme()" title="Ganti Tema">
        <i class="bi bi-sun-fill fs-5 d-none" id="themeSunIcon"></i>
        <i class="bi bi-moon-fill fs-5 d-none" id="themeMoonIcon"></i>
    </button>
</div>

<div class="register-container mx-auto">
    
    <!-- Stepper Progress Header -->
    <div class="d-flex justify-content-between align-items-center mb-5" id="stepper">
        <div class="step-item active" id="stepItem_1">
            <div class="step-circle">1</div>
            <span class="step-label">Data Diri</span>
        </div>
        <div class="step-line" id="stepLine_1"></div>
        <div class="step-item" id="stepItem_2">
            <div class="step-circle">2</div>
            <span class="step-label">Akademik</span>
        </div>
        <div class="step-line" id="stepLine_2"></div>
        <div class="step-item" id="stepItem_3">
            <div class="step-circle">3</div>
            <span class="step-label">Akun</span>
        </div>
    </div>

    <!-- Registration Form -->
    <form method="POST" action="{{ route('register') }}" id="registerForm" class="needs-validation">
        @csrf
        
        <!-- Step 1: Data Diri -->
        <div class="step-container" id="stepContainer_1">
            <h4 class="font-bold text-custom-primary mb-2">Informasi Data Diri</h4>
            <p class="text-custom-secondary text-sm mb-4">Silakan masukkan nama lengkap dan username unik Anda.</p>
            
            <!-- Nama Lengkap -->
            <div class="mb-3">
                <label for="name" class="form-label text-custom-secondary text-xs uppercase font-bold tracking-wider">Nama Lengkap</label>
                <input type="text" class="form-control form-modern @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Username -->
            <div class="mb-4">
                <label for="username" class="form-label text-custom-secondary text-xs uppercase font-bold tracking-wider">Username</label>
                <input type="text" class="form-control form-modern @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required placeholder="Contoh: budis123">
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Step 2: Data Akademik -->
        <div class="step-container d-none" id="stepContainer_2">
            <h4 class="font-bold text-custom-primary mb-2">Informasi Akademik</h4>
            <p class="text-custom-secondary text-sm mb-4">Nomor Induk Siswa diperlukan untuk pencatatan kelulusan.</p>
            
            <!-- NIS -->
            <div class="mb-4">
                <label for="nis" class="form-label text-custom-secondary text-xs uppercase font-bold tracking-wider">NIS (Nomor Induk Siswa)</label>
                <input type="text" class="form-control form-modern @error('nis') is-invalid @enderror" id="nis" name="nis" value="{{ old('nis') }}" required placeholder="Contoh: 12345678">
                <div class="form-text text-xs text-custom-secondary mt-1">Pastikan NIS Anda valid sesuai dengan data sekolah.</div>
                @error('nis')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Step 3: Akun -->
        <div class="step-container d-none" id="stepContainer_3">
            <h4 class="font-bold text-custom-primary mb-2">Informasi Akun</h4>
            <p class="text-custom-secondary text-sm mb-4">Gunakan email aktif Anda dan kata sandi yang kuat.</p>
            
            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label text-custom-secondary text-xs uppercase font-bold tracking-wider">Alamat Email</label>
                <input type="email" class="form-control form-modern @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="Contoh: budi@gmail.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label text-custom-secondary text-xs uppercase font-bold tracking-wider">Kata Sandi</label>
                <input type="password" class="form-control form-modern @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Minimal 8 karakter">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="form-label text-custom-secondary text-xs uppercase font-bold tracking-wider">Konfirmasi Kata Sandi</label>
                <input type="password" class="form-control form-modern" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi kata sandi">
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="d-flex justify-content-between border-top pt-4 mt-4">
            <button type="button" class="btn btn-modern btn-modern-secondary btn-sm px-4 d-none" id="btnPrev" onclick="navigateStep(-1)">
                Sebelumnya
            </button>
            <a href="{{ route('login') }}" class="text-primary text-sm text-decoration-none py-2" id="lnkLogin">
                Sudah punya akun? Masuk
            </a>
            <button type="button" class="btn btn-modern btn-modern-primary btn-sm px-4 ms-auto" id="btnNext" onclick="navigateStep(1)">
                Berikutnya
            </button>
            <button type="submit" class="btn btn-modern btn-modern-primary btn-sm px-4 d-none" id="btnSubmit">
                Daftar Sekarang
            </button>
        </div>
    </form>
</div>

<script>
    let currentStep = 1;
    const totalSteps = 3;

    function navigateStep(direction) {
        // Form validations before advancing
        if (direction === 1 && !validateCurrentStep()) {
            return;
        }

        currentStep += direction;
        
        // Toggle Step Views
        for (let i = 1; i <= totalSteps; i++) {
            const container = document.getElementById(`stepContainer_${i}`);
            const item = document.getElementById(`stepItem_${i}`);
            const line = document.getElementById(`stepLine_${i - 1}`);
            
            if (i === currentStep) {
                container.classList.remove('d-none');
                item.className = 'step-item active';
            } else {
                container.classList.add('d-none');
                if (i < currentStep) {
                    item.className = 'step-item completed';
                } else {
                    item.className = 'step-item';
                }
            }
            
            // Sync Connector Lines
            if (line) {
                line.style.backgroundColor = (i < currentStep) ? 'var(--success)' : 'var(--border-color)';
            }
        }

        // Toggle Buttons View
        const btnPrev = document.getElementById('btnPrev');
        const btnNext = document.getElementById('btnNext');
        const btnSubmit = document.getElementById('btnSubmit');
        const lnkLogin = document.getElementById('lnkLogin');

        btnPrev.classList.toggle('d-none', currentStep === 1);
        lnkLogin.classList.toggle('d-none', currentStep !== 1);
        btnNext.classList.toggle('d-none', currentStep === totalSteps);
        btnSubmit.classList.toggle('d-none', currentStep !== totalSteps);
    }

    function validateCurrentStep() {
        const container = document.getElementById(`stepContainer_${currentStep}`);
        const inputs = container.querySelectorAll('input');
        let valid = true;
        inputs.forEach(input => {
            if (!input.checkValidity()) {
                input.classList.add('is-invalid');
                valid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });
        return valid;
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
        
        // If there are validation errors on steps, auto-advance to step 3
        @if ($errors->any())
            navigateStep(2);
        @endif
    });
</script>
</body>
</html>
