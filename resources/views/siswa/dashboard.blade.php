@extends('layouts.siswa')

@section('title', 'Siswa Portal')
@section('page-title', 'Dashboard Siswa')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="dashboard-welcome-card text-white" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%) !important;">
            <div class="position-relative z-1">
                <h3 class="font-bold text-white mb-1">Selamat Pagi, {{ Auth::user()->name }}!</h3>
                <p class="mb-0 text-white-50 text-sm">Portal Siswa CBT. Pastikan Anda menyelesaikan ujian yang aktif tepat waktu.</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards Row -->
<div class="row g-4 mb-4">
    <!-- Ujian Tersedia -->
    <div class="col-md-6 col-xl-4">
        <div class="stat-card-modern">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <span class="text-xs text-custom-secondary d-block uppercase font-bold tracking-wider mb-1">Ujian Tersedia</span>
                    <h3 class="font-bold mb-0 text-custom-primary">{{ $ujianTersedia }}</h3>
                </div>
                <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between text-xs mb-2">
                <span class="text-primary font-bold">Harus Dikerjakan</span>
                <span class="text-custom-secondary">Deadline Dekat</span>
            </div>
            <div class="progress" style="height: 6px; border-radius: 3px; background-color: var(--border-color);">
                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $ujianTersedia > 0 ? '70%' : '0%' }};"></div>
            </div>
        </div>
    </div>

    <!-- Nilai Terakhir -->
    <div class="col-md-6 col-xl-4">
        <div class="stat-card-modern">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <span class="text-xs text-custom-secondary d-block uppercase font-bold tracking-wider mb-1">Nilai Terakhir Anda</span>
                    <h3 class="font-bold mb-0 text-custom-primary">
                        {{ $lastResult ? $lastResult->nilai : '0.00' }}
                    </h3>
                </div>
                <div class="stat-icon-wrapper bg-purple bg-opacity-10 text-purple">
                    <i class="bi bi-award-fill"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between text-xs mb-2">
                @if ($lastResult && $lastResult->nilai >= 70)
                    <span class="text-success font-bold"><i class="bi bi-check-circle me-1"></i>Lulus KKM</span>
                @else
                    <span class="text-danger font-bold"><i class="bi bi-x-circle me-1"></i>Mulai Belajar</span>
                @endif
                <span class="text-custom-secondary">Skor Target >= 70</span>
            </div>
            <div class="progress" style="height: 6px; border-radius: 3px; background-color: var(--border-color);">
                <div class="progress-bar bg-purple" role="progressbar" style="width: {{ $lastResult ? $lastResult->nilai : 0 }}%;"></div>
            </div>
        </div>
    </div>

    <!-- E-Sertifikat -->
    <div class="col-md-6 col-xl-4">
        <div class="stat-card-modern">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <span class="text-xs text-custom-secondary d-block uppercase font-bold tracking-wider mb-1">E-Sertifikat Diperoleh</span>
                    <h3 class="font-bold mb-0 text-custom-primary">{{ $totalSertifikat }}</h3>
                </div>
                <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between text-xs mb-2">
                <span class="text-success font-bold">Terverifikasi</span>
                <span class="text-custom-secondary">Unduh PDF</span>
            </div>
            <div class="progress" style="height: 6px; border-radius: 3px; background-color: var(--border-color);">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $totalSertifikat > 0 ? '100%' : '0%' }};"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Quick Navigation (4-cols) -->
    <div class="col-lg-4 mb-4">
        <div class="card-modern mb-0 shadow-sm border-0 bg-custom-card h-100">
            <h5 class="font-bold text-custom-primary mb-3">Menu Cepat Siswa</h5>
            <div class="d-flex flex-column gap-3">
                <a href="{{ route('siswa.ujian.index') }}" class="shortcut-card-btn py-3">
                    <i class="bi bi-file-earmark-play fs-4"></i>
                    <strong class="text-sm">Mulai Ujian Baru</strong>
                </a>
                <a href="{{ route('siswa.riwayat') }}" class="shortcut-card-btn py-3">
                    <i class="bi bi-clock-history fs-4"></i>
                    <strong class="text-sm">Riwayat & Nilai</strong>
                </a>
                <a href="{{ route('siswa.sertifikat.index') }}" class="shortcut-card-btn py-3">
                    <i class="bi bi-patch-check fs-4"></i>
                    <strong class="text-sm">Portal Sertifikat</strong>
                </a>
            </div>
        </div>
    </div>

    <!-- Right Column: Recent Exam Grades (8-cols) -->
    <div class="col-lg-8 mb-4">
        <div class="card-modern mb-0 shadow-sm border-0 bg-custom-card h-100">
            <h5 class="font-bold text-custom-primary mb-3">Riwayat Nilai Terbaru</h5>
            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th style="font-size:0.75rem;" class="text-custom-secondary font-bold uppercase">Mata Pelajaran</th>
                            <th style="font-size:0.75rem;" class="text-custom-secondary font-bold uppercase">Ujian</th>
                            <th style="font-size:0.75rem;" class="text-custom-secondary font-bold uppercase">Tanggal</th>
                            <th style="font-size:0.75rem;" class="text-custom-secondary font-bold text-center uppercase">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentResults as $result)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary mb-1" style="font-size:0.7rem;">{{ $result->ujian->mapel->kode_mapel ?? '-' }}</span>
                                    <div class="text-xs text-custom-secondary" style="font-size:0.75rem;">{{ $result->ujian->mapel->nama_mapel ?? '-' }}</div>
                                </td>
                                <td><strong class="text-custom-primary text-sm">{{ $result->ujian->judul }}</strong></td>
                                <td class="text-xs text-custom-secondary">{{ $result->waktu_selesai->format('d M Y, H:i') }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $result->nilai >= 70 ? 'bg-success' : 'bg-danger' }} px-2 py-1.5 font-bold rounded-pill text-xs">
                                        {{ $result->nilai }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-custom-secondary">
                                    <i class="bi bi-journal-x fs-2 d-block mb-1"></i>
                                    Belum ada riwayat hasil ujian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
