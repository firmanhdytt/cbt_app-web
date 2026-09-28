@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Ringkasan Admin')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="dashboard-welcome-card text-white" style="background: linear-gradient(135deg, var(--primary) 0%, #1e40af 100%) !important;">
            <div class="position-relative z-1">
                <h3 class="font-bold text-white mb-1">Selamat Pagi, {{ Auth::user()->name }}!</h3>
                <p class="mb-0 text-white-50 text-sm">Hari ini adalah {{ now()->translatedFormat('d F Y') }}. Semua sistem CBT berjalan normal dan aman.</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards Row -->
<div class="row g-4 mb-4">
    <!-- Total Siswa -->
    <div class="col-md-6 col-xl-3">
        <div class="stat-card-modern">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <span class="text-xs text-custom-secondary d-block uppercase font-bold tracking-wider mb-1">Total Siswa</span>
                    <h3 class="font-bold mb-0 text-custom-primary">{{ $totalSiswa }}</h3>
                </div>
                <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between text-xs mb-2">
                <span class="text-success font-bold"><i class="bi bi-arrow-up me-1"></i>+4%</span>
                <span class="text-custom-secondary">Target Terdaftar</span>
            </div>
            <div class="progress" style="height: 6px; border-radius: 3px; background-color: var(--border-color);">
                <div class="progress-bar bg-primary" role="progressbar" style="width: 85%;" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <!-- Total Guru -->
    <div class="col-md-6 col-xl-3">
        <div class="stat-card-modern">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <span class="text-xs text-custom-secondary d-block uppercase font-bold tracking-wider mb-1">Total Guru</span>
                    <h3 class="font-bold mb-0 text-custom-primary">{{ $totalGuru }}</h3>
                </div>
                <div class="stat-icon-wrapper bg-info bg-opacity-10 text-info">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between text-xs mb-2">
                <span class="text-success font-bold"><i class="bi bi-arrow-up me-1"></i>+2%</span>
                <span class="text-custom-secondary">Pengajar Aktif</span>
            </div>
            <div class="progress" style="height: 6px; border-radius: 3px; background-color: var(--border-color);">
                <div class="progress-bar bg-info" role="progressbar" style="width: 70%;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <!-- Total Soal -->
    <div class="col-md-6 col-xl-3">
        <div class="stat-card-modern">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <span class="text-xs text-custom-secondary d-block uppercase font-bold tracking-wider mb-1">Bank Soal</span>
                    <h3 class="font-bold mb-0 text-custom-primary">{{ $totalSoal }}</h3>
                </div>
                <div class="stat-icon-wrapper bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-question-circle-fill"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between text-xs mb-2">
                <span class="text-success font-bold"><i class="bi bi-arrow-up me-1"></i>+12 data</span>
                <span class="text-custom-secondary">Peningkatan Butir</span>
            </div>
            <div class="progress" style="height: 6px; border-radius: 3px; background-color: var(--border-color);">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <!-- Total Ujian -->
    <div class="col-md-6 col-xl-3">
        <div class="stat-card-modern">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <span class="text-xs text-custom-secondary d-block uppercase font-bold tracking-wider mb-1">Jadwal Ujian</span>
                    <h3 class="font-bold mb-0 text-custom-primary">{{ $totalUjian }}</h3>
                </div>
                <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between text-xs mb-2">
                <span class="text-danger font-bold"><i class="bi bi-arrow-down me-1"></i>-1 ujian</span>
                <span class="text-custom-secondary">Minggu Ini</span>
            </div>
            <div class="progress" style="height: 6px; border-radius: 3px; background-color: var(--border-color);">
                <div class="progress-bar bg-success" role="progressbar" style="width: 45%;" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Statistics Chart & Quick Actions (7-cols) -->
    <div class="col-lg-7 mb-4">
        <!-- Chart -->
        <div class="card-modern mb-4 shadow-sm border-0 bg-custom-card">
            <h5 class="font-bold text-custom-primary mb-3">Grafik Aktivitas Ujian & Kelulusan</h5>
            <div style="height: 230px; position: relative;">
                <canvas id="adminChart"></canvas>
            </div>
        </div>

        <!-- Quick Actions Shortcuts -->
        <div class="card-modern mb-0 shadow-sm border-0 bg-custom-card">
            <h5 class="font-bold text-custom-primary mb-3">Pintasan Tindakan Cepat</h5>
            <div class="row g-3">
                <div class="col-6 col-sm-3">
                    <a href="{{ route('admin.soal.create') }}" class="shortcut-card-btn">
                        <i class="bi bi-plus-circle"></i>
                        <span class="text-xs font-bold text-wrap text-center">Tambah Soal</span>
                    </a>
                </div>
                <div class="col-6 col-sm-3">
                    <a href="{{ route('admin.ujian.create') }}" class="shortcut-card-btn">
                        <i class="bi bi-calendar-plus"></i>
                        <span class="text-xs font-bold text-wrap text-center">Buat Ujian</span>
                    </a>
                </div>
                <div class="col-6 col-sm-3">
                    <a href="{{ route('admin.pengguna.index') }}" class="shortcut-card-btn">
                        <i class="bi bi-person-gear"></i>
                        <span class="text-xs font-bold text-wrap text-center">Kelola User</span>
                    </a>
                </div>
                <div class="col-6 col-sm-3">
                    <a href="{{ route('admin.laporan.index') }}" class="shortcut-card-btn">
                        <i class="bi bi-file-earmark-arrow-down"></i>
                        <span class="text-xs font-bold text-wrap text-center">Laporan Nilai</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Recent Activities & Exam Results (5-cols) -->
    <div class="col-lg-5 mb-4">
        <!-- Recent Results -->
        <div class="card-modern mb-4 shadow-sm border-0 bg-custom-card">
            <h5 class="font-bold text-custom-primary mb-3">Hasil Ujian Terbaru</h5>
            <div class="list-group list-group-flush">
                @forelse ($recentResults as $result)
                    <div class="list-group-item px-0 py-2.5 border-0 border-bottom border-custom bg-transparent d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="text-custom-primary d-block text-sm">{{ $result->user->name }}</strong>
                            <span class="text-custom-secondary text-xs">{{ $result->ujian->judul }}</span>
                        </div>
                        <span class="badge {{ $result->nilai >= 70 ? 'bg-success' : 'bg-danger' }} px-2 py-1.5 font-bold rounded-pill text-xs">
                            {{ $result->nilai }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-4 text-custom-secondary text-sm">
                        <i class="bi bi-emoji-neutral fs-3 d-block mb-1"></i>
                        Belum ada riwayat hasil ujian.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Activities Timeline -->
        <div class="card-modern mb-0 shadow-sm border-0 bg-custom-card">
            <h5 class="font-bold text-custom-primary mb-3">Aktivitas Sistem Terbaru</h5>
            <div class="position-relative ps-3 border-start border-custom ms-2 d-flex flex-column gap-3 py-1">
                <div class="position-relative">
                    <div class="position-absolute translate-middle-x bg-primary rounded-circle" style="left: -20px; top: 8px; width: 10px; height: 10px;"></div>
                    <strong class="text-custom-primary d-block text-sm">Admin (Anda)</strong>
                    <span class="text-custom-secondary text-xs">Menjadwalkan ujian tengah semester baru</span>
                </div>
                <div class="position-relative">
                    <div class="position-absolute translate-middle-x bg-purple rounded-circle" style="left: -20px; top: 8px; width: 10px; height: 10px;"></div>
                    <strong class="text-custom-primary d-block text-sm">Guru Pengajar</strong>
                    <span class="text-custom-secondary text-xs">Menambahkan 5 bank soal matematika baru</span>
                </div>
                <div class="position-relative">
                    <div class="position-absolute translate-middle-x bg-success rounded-circle" style="left: -20px; top: 8px; width: 10px; height: 10px;"></div>
                    <strong class="text-custom-primary d-block text-sm">Siswa Terdaftar</strong>
                    <span class="text-custom-secondary text-xs">Menyelesaikan ujian Bahasa Inggris kelas XII</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const textColor = isDark ? '#94a3b8' : '#475569';
        const gridColor = isDark ? '#334155' : '#e2e8f0';

        const ctx = document.getElementById('adminChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Siswa', 'Guru', 'Bank Soal', 'Ujian'],
                datasets: [{
                    label: 'Jumlah Data',
                    data: [{{ $totalSiswa }}, {{ $totalGuru }}, {{ $totalSoal }}, {{ $totalUjian }}],
                    backgroundColor: [
                        'rgba(37, 99, 235, 0.85)',
                        '#8b5cf6',
                        '#f59e0b',
                        '#10b981'
                    ],
                    borderWidth: 0,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            color: textColor,
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: textColor
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
