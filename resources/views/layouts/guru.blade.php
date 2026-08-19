<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Guru Dashboard') - CBT Portal</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- CBT Design System CSS -->
    <link href="/css/design-system.css" rel="stylesheet">
    
    <!-- Theme Initializer (Prevents FOUC) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('cbt-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    
    @yield('styles')
</head>
<body>

<div class="sidebar-layout">
    <!-- Sidebar Panel -->
    <aside class="sidebar-panel" id="sidebar">
        <div class="d-flex align-items-center justify-content-between p-3 border-bottom border-custom">
            <a href="#" class="d-flex align-items-center text-decoration-none">
                <span class="fs-4 font-bold text-custom-primary">
                    <i class="bi bi-journal-check me-2 text-primary"></i>
                    <span class="sidebar-logo-text">CBT <span class="text-primary">Guru</span></span>
                </span>
            </a>
            <button class="btn btn-sm d-none d-md-block text-custom-primary border-0" id="sidebarCollapseBtn" onclick="toggleSidebar()">
                <i class="bi bi-chevron-left" id="sidebarCollapseIcon"></i>
            </button>
        </div>

        <ul class="nav flex-column mt-3 mb-auto w-100">
            <li class="nav-item">
                <a href="{{ route('guru.dashboard') }}" class="nav-link sidebar-item-link d-flex align-items-center {{ Request::routeIs('guru.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-3"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item mt-2">
                <a href="{{ route('guru.soal.index') }}" class="nav-link sidebar-item-link d-flex align-items-center {{ Request::routeIs('guru.soal.*') ? 'active' : '' }}">
                    <i class="bi bi-question-circle me-3"></i>
                    <span class="sidebar-text">Kelola Soal</span>
                </a>
            </li>
            <li class="nav-item mt-2">
                <a href="{{ route('guru.ujian.index') }}" class="nav-link sidebar-item-link d-flex align-items-center {{ Request::routeIs('guru.ujian.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-check me-3"></i>
                    <span class="sidebar-text">Kelola Ujian</span>
                </a>
            </li>
            <li class="nav-item mt-2">
                <a href="{{ route('guru.laporan.index') }}" class="nav-link sidebar-item-link d-flex align-items-center {{ Request::routeIs('guru.laporan.index') ? 'active' : '' }}">
                    <i class="bi bi-graph-up me-3"></i>
                    <span class="sidebar-text">Laporan Ujian</span>
                </a>
            </li>
            <li class="nav-item mt-2">
                <a href="{{ route('guru.laporan.siswa') }}" class="nav-link sidebar-item-link d-flex align-items-center {{ Request::routeIs('guru.laporan.siswa*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge me-3"></i>
                    <span class="sidebar-text">Laporan Per Siswa</span>
                </a>
            </li>
        </ul>

        <div class="p-3 border-top border-custom mt-auto w-100">
            <!-- User Profile Summary in Sidebar -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-custom-primary text-decoration-none dropdown-toggle sidebar-profile" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                    @if (Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="avatar" width="32" height="32" class="rounded-circle me-2">
                    @else
                        <div class="avatar-circle me-2" style="width:32px; height:32px; font-weight:700; background-color:var(--primary); color:white; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="sidebar-text text-truncate" style="max-width: 130px;">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu bg-custom-card border-custom shadow" aria-labelledby="dropdownUser">
                    <li><a class="dropdown-item text-custom-primary text-sm" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                    <li><hr class="dropdown-divider border-custom"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger text-sm"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </aside>

    <!-- Main Content Layout -->
    <div class="main-layout">
        <!-- Navbar -->
        <nav class="navbar navbar-expand navbar-light navbar-modern py-2 px-4 shadow-sm">
            <button class="btn btn-sm d-md-none text-custom-primary border-0 me-2" onclick="toggleMobileSidebar()">
                <i class="bi bi-list fs-4"></i>
            </button>
            
            <div class="me-auto"></div>

            <div class="d-flex align-items-center gap-2">
                <!-- Theme Toggler -->
                <button class="btn theme-toggle-btn p-0" onclick="toggleTheme()" title="Ganti Tema">
                    <i class="bi bi-sun-fill fs-5 d-none" id="themeSunIcon"></i>
                    <i class="bi bi-moon-fill fs-5 d-none" id="themeMoonIcon"></i>
                </button>
                
                <!-- Notifications -->
                <div class="dropdown">
                    <button class="btn notif-btn p-0" data-bs-toggle="dropdown" title="Notifikasi">
                        <i class="bi bi-bell-fill fs-5"></i>
                        <span class="notif-badge"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end bg-custom-card border-custom shadow-lg py-2" style="width: 280px; border-radius:12px;">
                        <li><h6 class="dropdown-header text-custom-primary font-bold py-1 px-3">Notifikasi</h6></li>
                        <li><hr class="dropdown-divider border-custom my-1"></li>
                        <li><a class="dropdown-item text-xs text-custom-primary text-wrap py-2 px-3" href="#"><i class="bi bi-info-circle text-primary me-2"></i>Masa aktif ujian Geografi akan berakhir.</a></li>
                    </ul>
                </div>

                <div class="border-start border-custom h-50 mx-2" style="height: 24px !important;"></div>

                <span class="role-badge d-none d-md-inline">Pengajar / Guru</span>
            </div>
        </nav>

        <!-- Inner Content Area -->
        <div class="p-4 container-fluid flex-grow-1">
            <!-- Breadcrumbs & Title -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 font-bold text-custom-primary">@yield('page-title')</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}" class="text-decoration-none">Home</a></li>
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>
                <div>
                    @yield('page-actions')
                </div>
            </div>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show card-modern p-3 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2 text-success"></i><strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show card-modern p-3 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i><strong>Gagal!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show card-modern p-3 mb-4" role="alert">
                    <i class="bi bi-exclamation-octagon-fill me-2 text-danger"></i><strong>Terjadi Kesalahan!</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Dynamic Content -->
            @yield('content')
        </div>
    </div>
</div>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- UI Core Script -->
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

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const icon = document.getElementById('sidebarCollapseIcon');
        if (sidebar) {
            const isCollapsed = sidebar.classList.toggle('collapsed');
            localStorage.setItem('cbt-sidebar-collapsed', isCollapsed ? '1' : '0');
            if (icon) {
                icon.className = isCollapsed ? 'bi bi-chevron-right' : 'bi bi-chevron-left';
            }
        }
    }

    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (!sidebar) return;
        
        const isOpen = sidebar.classList.toggle('show-mobile');
        
        let backdrop = document.getElementById('sidebar-backdrop');
        if (isOpen) {
            if (!backdrop) {
                backdrop = document.createElement('div');
                backdrop.id = 'sidebar-backdrop';
                backdrop.className = 'sidebar-backdrop';
                backdrop.addEventListener('click', toggleMobileSidebar);
                document.body.appendChild(backdrop);
            }
            setTimeout(() => backdrop.classList.add('show'), 10);
        } else if (backdrop) {
            backdrop.classList.remove('show');
            setTimeout(() => backdrop.remove(), 300);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('cbt-theme') || 'light';
        syncThemeIcons(savedTheme);
        
        const isCollapsed = localStorage.getItem('cbt-sidebar-collapsed') === '1';
        const sidebar = document.getElementById('sidebar');
        const icon = document.getElementById('sidebarCollapseIcon');
        if (isCollapsed && sidebar) {
            sidebar.classList.add('collapsed');
            if (icon) icon.className = 'bi bi-chevron-right';
        }
    });
</script>
@yield('scripts')
</body>
</html>
