@extends('layouts.guru')

@section('title', 'Guru Dashboard')
@section('page-title', 'Dashboard Ringkasan Pengajar')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="dashboard-welcome-card text-white" style="background: linear-gradient(135deg, #7c3aed 0%, #4c1d95 100%) !important;">
            <div class="position-relative z-1">
                <h3 class="font-bold text-white mb-1">Selamat Pagi, {{ Auth::user()->name }}!</h3>
                <p class="mb-0 text-white-50 text-sm">Dashboard Pengajar CBT Portal. Siapkan bank soal dan jadwalkan ujian siswa Anda.</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards Row -->
<div class="row g-4 mb-4">
    <!-- Soal Dibuat -->
    <div class="col-md-6">
        <div class="stat-card-modern">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <span class="text-xs text-custom-secondary d-block uppercase font-bold tracking-wider mb-1">Soal Dibuat Anda</span>
                    <h3 class="font-bold mb-0 text-custom-primary">{{ $soalDibuat }}</h3>
                </div>
                <div class="stat-icon-wrapper bg-purple bg-opacity-10 text-purple">
                    <i class="bi bi-question-circle-fill"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between text-xs mb-2">
                <span class="text-success font-bold"><i class="bi bi-arrow-up me-1"></i>+5 soal</span>
                <span class="text-custom-secondary">Bulan Ini</span>
            </div>
            <div class="progress" style="height: 6px; border-radius: 3px; background-color: var(--border-color);">
                <div class="progress-bar bg-purple" role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <!-- Ujian Aktif -->
    <div class="col-md-6">
        <div class="stat-card-modern">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <span class="text-xs text-custom-secondary d-block uppercase font-bold tracking-wider mb-1">Ujian Aktif Saat Ini</span>
                    <h3 class="font-bold mb-0 text-custom-primary">{{ $ujianAktif }}</h3>
                </div>
                <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
                    <i class="bi bi-calendar-event-fill"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between text-xs mb-2">
                <span class="text-success font-bold">Sedang Berjalan</span>
                <span class="text-custom-secondary">Waktu Aktif</span>
            </div>
            <div class="progress" style="height: 6px; border-radius: 3px; background-color: var(--border-color);">
                <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Performance Chart & Quick Actions (7-cols) -->
    <div class="col-lg-7 mb-4">
        <!-- Chart -->
        <div class="card-modern mb-4 shadow-sm border-0 bg-custom-card">
            <h5 class="font-bold text-custom-primary mb-3">Statistik Distribusi Kelulusan</h5>
            <div style="height: 280px; position: relative;">
                <canvas id="guruChart"></canvas>
            </div>
        </div>

        <!-- Quick Actions Shortcuts -->
        <div class="card-modern mb-0 shadow-sm border-0 bg-custom-card">
            <h5 class="font-bold text-custom-primary mb-3">Pintasan Tindakan Pengajar</h5>
            <div class="row g-3">
                <div class="col-4">
                    <a href="{{ route('guru.soal.create') }}" class="shortcut-card-btn">
                        <i class="bi bi-plus-circle"></i>
                        <span class="text-xs font-bold text-wrap text-center">Tambah Soal</span>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('guru.ujian.create') }}" class="shortcut-card-btn">
                        <i class="bi bi-calendar-plus"></i>
                        <span class="text-xs font-bold text-wrap text-center">Buat Ujian</span>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('guru.laporan.index') }}" class="shortcut-card-btn">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        <span class="text-xs font-bold text-wrap text-center">Lihat Nilai</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Student Exam Results (5-cols) -->
    <div class="col-lg-5 mb-4">
        <div class="card-modern mb-0 shadow-sm border-0 bg-custom-card" style="height: 100%;">
            <h5 class="font-bold text-custom-primary mb-3">Hasil Ujian Siswa Terbaru</h5>
            <div class="list-group list-group-flush">
                @forelse ($recentResults as $result)
                    <div class="list-group-item px-0 py-2.5 border-0 border-bottom border-custom bg-transparent d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="text-custom-primary d-block text-sm">{{ $result->user->name }}</strong>
                            <span class="text-custom-secondary text-xs">{{ $result->ujian->judul }}</span>
                        </div>
                        <span class="badge {{ $result->nilai >= 70 ? 'bg-success' : 'bg-danger' }} px-2.5 py-1.5 font-bold rounded-pill text-xs">
                            Skor: {{ $result->nilai }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-5 text-custom-secondary text-sm">
                        <i class="bi bi-emoji-neutral fs-3 d-block mb-1"></i>
                        Belum ada riwayat hasil ujian dari siswa.
                    </div>
                @endforelse
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

        const ctx = document.getElementById('guruChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Ujian 1', 'Ujian 2', 'Ujian 3', 'Ujian 4', 'Ujian 5'],
                datasets: [{
                    label: 'Rata-rata Nilai Siswa',
                    data: [75, 82, 68, 90, 85],
                    borderColor: '#7c3aed',
                    backgroundColor: 'rgba(124, 58, 237, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
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
                            stepSize: 10
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
