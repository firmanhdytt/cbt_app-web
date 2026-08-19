<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Sertifikat - CBT Portal</title>
    
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
        .verify-container {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-modal);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 540px;
            overflow: hidden;
            transition: all var(--transition-speed) ease;
        }
        .status-header {
            padding: 2.5rem;
            text-align: center;
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

<div class="verify-container mx-auto">
    @if ($sertifikat)
        <!-- Valid State -->
        <div class="status-header bg-success text-white" style="background: linear-gradient(135deg, var(--success) 0%, #047857 100%) !important;">
            <i class="bi bi-patch-check-fill fs-1 d-block mb-2"></i>
            <h4 class="mb-0 font-bold text-white">Sertifikat Terverifikasi Asli</h4>
            <p class="mb-0 text-white-50 text-xs mt-1">Sertifikat ini resmi diterbitkan oleh CBT Portal.</p>
        </div>
        
        <div class="p-4 bg-custom-card">
            <h6 class="text-custom-secondary text-xs uppercase font-bold tracking-wider mb-3">Informasi Sertifikat:</h6>
            
            <div class="p-3 bg-custom-body border border-custom rounded-3 text-xs mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-custom-secondary">No. Sertifikat:</span>
                    <span class="font-bold text-custom-primary font-mono">{{ $nomor_sertifikat }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-custom-secondary">Nama Siswa:</span>
                    <span class="font-bold text-custom-primary">{{ $sertifikat->user->name }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-custom-secondary">NIS (Nomor Induk):</span>
                    <span class="font-bold text-custom-primary">{{ $sertifikat->user->nis ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-custom-secondary">Kelas Peserta:</span>
                    <span class="font-bold text-custom-primary">{{ $sertifikat->user->kelas->nama_kelas ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-custom-secondary">Mata Pelajaran:</span>
                    <span class="font-bold text-custom-primary">{{ $sertifikat->ujian->mapel->nama_mapel ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-custom-secondary">Judul Ujian:</span>
                    <span class="font-bold text-custom-primary">{{ $sertifikat->ujian->judul }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-custom-secondary">Skor Ujian:</span>
                    <span class="font-bold text-success">{{ $sertifikat->ujian->hasilUjians()->where('user_id', $sertifikat->user_id)->first()->nilai ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-custom-secondary">Tanggal Terbit:</span>
                    <span class="font-bold text-custom-primary">{{ $sertifikat->created_at->format('d F Y') }}</span>
                </div>
            </div>
            
            <div class="text-center">
                <i class="bi bi-shield-fill-check text-success fs-5 me-1"></i>
                <span class="text-xs text-custom-secondary">Verifikasi digital terenkripsi aman</span>
            </div>
        </div>
    @else
        <!-- Invalid/Not Found State -->
        <div class="status-header bg-danger text-white" style="background: linear-gradient(135deg, var(--danger) 0%, #b91c1c 100%) !important;">
            <i class="bi bi-x-octagon-fill fs-1 d-block mb-2"></i>
            <h4 class="mb-0 font-bold text-white">Sertifikat Tidak Valid</h4>
            <p class="mb-0 text-white-50 text-xs mt-1">Nomor sertifikat tidak terdaftar di dalam database kami.</p>
        </div>
        
        <div class="p-4 text-center bg-custom-card">
            <p class="text-sm text-custom-secondary mb-4">
                Sertifikat dengan nomor: <strong class="font-mono text-danger">{{ $nomor_sertifikat }}</strong> tidak ditemukan atau data sertifikat telah diubah/dihapus secara ilegal.
            </p>
            <a href="/" class="btn btn-modern btn-modern-secondary btn-sm px-4">
                <i class="bi bi-house me-1"></i> Kembali ke Beranda
            </a>
        </div>
    @endif
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
