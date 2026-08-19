@extends('layouts.' . Auth::user()->role)

@section('title', 'Laporan Per Siswa')
@section('page-title', 'Laporan Rapor Per Siswa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role . '.laporan.index') }}">Laporan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Per Siswa</li>
@endsection

@section('content')

<!-- Filter Bar -->
<div class="card card-custom border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route(Auth::user()->role . '.laporan.siswa') }}" class="row g-2 align-items-center">
            <!-- Filter Kelas -->
            <div class="col-12 col-md-4">
                <label class="form-label text-xs fw-semibold text-muted mb-1">Filter Kategori Kelas:</label>
                <select name="kelas_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($kelass as $k)
                        <option value="{{ $k->id }}" {{ (string)$kelasId === (string)$k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Search Nama / NIS -->
            <div class="col-12 col-md-6">
                <label class="form-label text-xs fw-semibold text-muted mb-1">Cari Nama Siswa / NIS / Username:</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Tuliskan nama atau NIS siswa..." value="{{ $search }}">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i> Cari</button>
                </div>
            </div>

            <!-- Reset Filter -->
            @if($kelasId || $search)
            <div class="col-12 col-md-2 text-end">
                <label class="form-label d-block text-xs text-transparent mb-1">&nbsp;</label>
                <a href="{{ route(Auth::user()->role . '.laporan.siswa') }}" class="btn btn-outline-danger btn-sm w-100">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

<!-- Student Grid Cards -->
<div class="row g-3">
    @forelse ($siswas as $siswa)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card card-custom h-100 border-0 shadow-sm hover-shadow transition-all">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <!-- Header & Avatar -->
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="avatar-circle rounded-circle bg-primary bg-opacity-10 text-primary font-bold d-flex align-items-center justify-content-center" style="width:48px;height:48px;font-size:1.2rem;">
                                {{ strtoupper(substr($siswa->name, 0, 1)) }}
                            </div>
                            <div class="overflow-hidden">
                                <h6 class="fw-bold text-dark mb-0 text-truncate" title="{{ $siswa->name }}">{{ $siswa->name }}</h6>
                                <div class="text-muted small">NIS: {{ $siswa->nis ?? '-' }}</div>
                            </div>
                        </div>

                        <!-- Class & Email Badge -->
                        <div class="d-flex align-items-center gap-2 mb-3">
                            @if($siswa->kelas)
                                <span class="badge bg-primary bg-opacity-10 text-primary px-2.5 py-1">{{ $siswa->kelas->nama_kelas }}</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-2.5 py-1">Tanpa Kelas</span>
                            @endif
                            <span class="text-muted text-xs text-truncate" title="{{ $siswa->email }}"><i class="bi bi-envelope me-1"></i>{{ $siswa->email }}</span>
                        </div>

                        <!-- Summary Mini Stats -->
                        <div class="row g-2 text-center my-3 bg-light rounded-3 p-2">
                            <div class="col-4 border-end">
                                <div class="text-muted" style="font-size:10px;">Total Ujian</div>
                                <div class="fw-bold text-dark fs-6">{{ $siswa->hasil_ujians_count }}</div>
                            </div>
                            <div class="col-4 border-end">
                                <div class="text-muted" style="font-size:10px;">Lulus (≥70)</div>
                                <div class="fw-bold text-success fs-6">{{ $siswa->total_lulus }}</div>
                            </div>
                            <div class="col-4">
                                <div class="text-muted" style="font-size:10px;">Rata-Rata</div>
                                <div class="fw-bold text-primary fs-6">{{ $siswa->avg_score }}</div>
                            </div>
                        </div>

                        @if($siswa->total_terkunci > 0)
                            <div class="alert alert-danger py-1 px-2.5 text-xs mb-3 d-flex align-items-center justify-content-between">
                                <span><i class="bi bi-lock-fill me-1"></i> {{ $siswa->total_terkunci }} Ujian Terkunci</span>
                                <span class="badge bg-danger text-white">Terkunci</span>
                            </div>
                        @endif
                    </div>

                    <!-- Action Button -->
                    <div class="mt-3 pt-3 border-top">
                        <a href="{{ route(Auth::user()->role . '.laporan.siswa.detail', $siswa->id) }}" class="btn btn-outline-primary btn-sm w-100 fw-semibold">
                            <i class="bi bi-file-earmark-person-fill me-1"></i> Lihat Rapor Lengkap Siswa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card card-custom p-5 text-center text-muted">
                <i class="bi bi-person-x display-4 d-block mb-2 opacity-25"></i>
                Tidak ditemukan data siswa terdaftar yang sesuai kriteria pencarian.
            </div>
        </div>
    @endforelse
</div>

@if($siswas->hasPages())
    <div class="mt-4 d-flex justify-content-between align-items-center">
        <small class="text-muted">Menampilkan {{ $siswas->firstItem() }}–{{ $siswas->lastItem() }} dari {{ $siswas->total() }} siswa</small>
        {{ $siswas->links('pagination::bootstrap-5') }}
    </div>
@endif

@endsection
