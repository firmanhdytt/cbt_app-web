<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setel Ulang Password - CBT Portal</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-body);
            padding: 1.5rem 1rem;
        }

        .reset-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
            width: 100%;
            max-width: 440px;
            padding: 2.5rem 2.25rem;
            transition: all 0.25s ease;
        }

        .brand-logo {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
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
    </style>
</head>
<body>

<!-- Theme Toggler (Top Right) -->
<div class="position-absolute top-0 end-0 p-3 p-md-4">
    <button class="btn text-custom-primary border-0 p-2" onclick="toggleTheme()" title="Ganti Tema">
        <i class="bi bi-sun-fill fs-5 d-none" id="themeSunIcon"></i>
        <i class="bi bi-moon-fill fs-5 d-none" id="themeMoonIcon"></i>
    </button>
</div>

<!-- Clean Reset Card -->
<div class="reset-card">
    
    <!-- Brand Header -->
    <div class="text-center mb-4">
        <div class="brand-logo mb-3">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <h4 class="fw-bold text-custom-primary mb-1">Setel Ulang Password</h4>
        <p class="text-custom-secondary small mb-0">Silakan buat kata sandi baru untuk akun Anda.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label text-custom-secondary small font-medium mb-1">Alamat E-Mail</label>
            <input type="email" class="form-control form-control-clean @error('email') is-invalid @enderror" 
                   id="email" name="email" value="{{ old('email', $request->email) }}" required autofocus 
                   placeholder="nama@email.com">
            @error('email')
                <div class="invalid-feedback text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- New Password -->
        <div class="mb-3">
            <label for="password" class="form-label text-custom-secondary small font-medium mb-1">Kata Sandi Baru <span class="text-danger">*</span></label>
            <input type="password" class="form-control form-control-clean @error('password') is-invalid @enderror" 
                   id="password" name="password" required placeholder="Minimal 8 karakter">
            @error('password')
                <div class="invalid-feedback text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm New Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label text-custom-secondary small font-medium mb-1">Konfirmasi Kata Sandi Baru <span class="text-danger">*</span></label>
            <input type="password" class="form-control form-control-clean @error('password_confirmation') is-invalid @enderror" 
                   id="password_confirmation" name="password_confirmation" required placeholder="Ulangi kata sandi baru">
            @error('password_confirmation')
                <div class="invalid-feedback text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-submit-clean w-100 mb-3">
            Simpan Kata Sandi Baru
        </button>
    </form>

    <!-- Back to Login Footer -->
    <div class="pt-3 text-center text-xs text-custom-secondary border-top">
        Batal memperbarui? 
        <a href="{{ route('login') }}" class="text-primary font-bold text-decoration-none ms-1">Kembali ke Halaman Login</a>
    </div>

</div>

<script>
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
