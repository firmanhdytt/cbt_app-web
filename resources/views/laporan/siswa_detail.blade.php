@extends('layouts.' . Auth::user()->role)

@section('title', 'Detail Rapor - ' . $siswa->name)
@section('page-title', 'Detail Rapor & Riwayat Ujian Siswa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role . '.laporan.index') }}">Laporan</a></li>
    <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role . '.laporan.siswa') }}">Per Siswa</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $siswa->name }}</li>
@endsection

@section('page-actions')
    <a href="{{ route(Auth::user()->role . '.laporan.siswa') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Siswa
    </a>
@endsection

@section('content')

<!-- Profile Banner Card -->
<div class="card card-custom border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="row align-items-center g-3">
            <div class="col-auto">
                <div class="avatar-circle rounded-circle bg-primary bg-opacity-10 text-primary font-bold d-flex align-items-center justify-content-center" style="width:64px;height:64px;font-size:1.6rem;">
                    {{ strtoupper(substr($siswa->name, 0, 1)) }}
                </div>
            </div>
            <div class="col">
                <h4 class="fw-bold text-dark mb-1">{{ $siswa->name }}</h4>
                <div class="d-flex flex-wrap align-items-center gap-3 text-muted small">
                    <span><i class="bi bi-card-text me-1"></i>NIS: <strong>{{ $siswa->nis ?? '-' }}</strong></span>
                    <span><i class="bi bi-people me-1"></i>Kelas: <strong>{{ $siswa->kelas->nama_kelas ?? 'Tanpa Kelas' }}</strong></span>
                    <span><i class="bi bi-envelope me-1"></i>{{ $siswa->email }}</span>
                </div>
            </div>
            <div class="col-12 col-md-auto text-md-end">
                <div class="p-3 bg-light rounded-3 d-inline-block text-center px-4">
                    <div class="text-muted text-xs uppercase font-semibold">Rata-Rata Nilai</div>
                    <div class="fs-3 fw-bold text-primary">{{ $avgScore }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summary Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card card-custom border-0 shadow-sm">
            <div class="card-body py-3 d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                    <i class="bi bi-file-earmark-check fs-5"></i>
                </div>
                <div>
                    <div class="fw-bold fs-5">{{ $hasilUjians->count() }}</div>
                    <div class="text-muted text-xs">Ujian Dikerjakan</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-custom border-0 shadow-sm">
            <div class="card-body py-3 d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                </div>
                <div>
                    <div class="fw-bold fs-5 text-success">{{ $totalLulus }}</div>
                    <div class="text-muted text-xs">Lulus (≥70)</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-custom border-0 shadow-sm">
            <div class="card-body py-3 d-flex align-items-center gap-3">
                <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                    <i class="bi bi-x-circle-fill fs-5"></i>
                </div>
                <div>
                    <div class="fw-bold fs-5 text-danger">{{ $totalRemidi }}</div>
                    <div class="text-muted text-xs">Remidi (&lt;70)</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-custom border-0 shadow-sm">
            <div class="card-body py-3 d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                    <i class="bi bi-lock-fill fs-5"></i>
                </div>
                <div>
                    <div class="fw-bold fs-5 text-dark">{{ $totalTerkunci }}</div>
                    <div class="text-muted text-xs">Ujian Terkunci</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Exam Results Table -->
<div class="card card-custom border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-list-stars me-2 text-primary"></i>Riwayat Seluruh Hasil Ujian Siswa</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width:5%">No</th>
                        <th style="width:30%">Judul Ujian / Mapel</th>
                        <th style="width:15%">Tanggal Selesai</th>
                        <th style="width:12%" class="text-center">Benar / Salah</th>
                        <th style="width:12%" class="text-center">Nilai Akhir</th>
                        <th style="width:13%" class="text-center">Pelanggaran</th>
                        <th style="width:13%" class="text-center pe-4">Aksi / PDF</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hasilUjians as $index => $hasil)
                        <tr>
                            <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                            <td>
                                <span class="badge bg-dark bg-opacity-75 font-monospace mb-1">{{ $hasil->ujian->mapel->kode_mapel ?? '-' }}</span>
                                <div class="fw-semibold text-dark">{{ $hasil->ujian->judul ?? '-' }}</div>
                                <div class="text-muted" style="font-size:11px;">Mapel: {{ $hasil->ujian->mapel->nama_mapel ?? '-' }}</div>
                            </td>
                            <td class="small text-muted">
                                {{ $hasil->waktu_selesai ? $hasil->waktu_selesai->format('d M Y') : '-' }}<br>
                                <span style="font-size:10px">{{ $hasil->waktu_selesai ? $hasil->waktu_selesai->format('H:i WIB') : '' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="text-success fw-semibold small">{{ $hasil->jumlah_benar }}B</span>
                                <span class="text-muted">/</span>
                                <span class="text-danger fw-semibold small">{{ $hasil->jumlah_salah }}S</span>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold fs-6 {{ $hasil->nilai >= 70 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($hasil->nilai, 0) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($hasil->status_pengerjaan === 'terkunci')
                                    <span class="badge bg-danger text-white px-2 py-1">
                                        <i class="bi bi-lock-fill me-1"></i> Terkunci ({{ $hasil->jumlah_pelanggaran }}x)
                                    </span>
                                @elseif($hasil->jumlah_pelanggaran > 0)
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1">
                                        {{ $hasil->jumlah_pelanggaran }}x Teguran
                                    </span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">
                                        0 Pelanggaran
                                    </span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-inline-flex gap-1">
                                    @if($hasil->status_pengerjaan === 'terkunci')
                                        <form action="{{ route(Auth::user()->role . '.laporan.buka_kunci', $hasil->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membuka kunci ujian ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm py-1 px-2 text-xs fw-semibold">
                                                <i class="bi bi-unlock-fill me-1"></i> Buka Kunci
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route(Auth::user()->role . '.laporan.pdf.hasil', $hasil->id) }}" class="btn btn-outline-danger btn-sm py-1 px-2 text-xs" title="Cetak PDF Rapor">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x display-4 d-block mb-2 opacity-25"></i>
                                Siswa ini belum memiliki riwayat pengerjaan ujian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
