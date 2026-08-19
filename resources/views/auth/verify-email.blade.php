<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - CBT Portal</title>
    
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

        .verify-card {
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

<!-- Clean Verify Card -->
<div class="verify-card text-center">
    
    <!-- Brand Header -->
    <div class="brand-logo mb-3 mx-auto">
        <i class="bi bi-envelope-check-fill"></i>
    </div>
    <h4 class="fw-bold text-custom-primary mb-1">Verifikasi Email Anda</h4>
    <p class="text-custom-secondary small mb-4">
        Terima kasih telah mendaftar! Silakan lakukan verifikasi alamat email Anda untuk melanjutkan ke dashboard.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success p-3 mb-4 text-xs border-0 shadow-sm rounded-3 text-start" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> Tautan verifikasi baru telah dikirimkan ke email Anda.
        </div>
    @endif

    <!-- Direct 1-Click Verification Form -->
    <form method="POST" action="{{ route('verification.direct') }}" class="mb-3">
        @csrf
        <button type="submit" class="btn btn-submit-clean w-100 mb-2">
            <i class="bi bi-patch-check-fill me-1"></i> Verifikasi Akun Sekarang (Satu Klik)
        </button>
    </form>

    <!-- Resend Email Verification Form -->
    <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
        @csrf
        <button type="submit" class="btn btn-outline-secondary btn-sm w-100 py-2">
            <i class="bi bi-send me-1"></i> Kirim Ulang Email Verifikasi
        </button>
    </form>

    <!-- Logout Link -->
    <div class="pt-3 text-center text-xs text-custom-secondary border-top">
        Ingin menggunakan akun lain? 
        <form method="POST" action="{{ route('logout') }}" class="d-inline ms-1">
            @csrf
            <button type="submit" class="btn btn-link text-danger p-0 text-xs font-bold text-decoration-none">
                Keluar / Logout
            </button>
        </form>
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
