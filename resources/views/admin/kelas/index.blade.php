@extends('layouts.admin')

@section('title', 'Kelola Kelas')
@section('page-title', 'Kelola Kelas')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Kelola Kelas</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0">Kelola daftar kelas yang akan digunakan sebagai peserta ujian.</p>
    </div>
    <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Tambah Kelas
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter & Search Bar Card -->
<div class="card card-custom mb-4 border-0 shadow-sm">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.kelas.index') }}" class="row g-2 align-items-center">
            <!-- Filter Tingkat -->
            <div class="col-12 col-md-3">
                <label class="form-label text-xs fw-semibold text-muted mb-1">Filter Kategori Tingkat:</label>
                <select name="tingkat" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Tingkat --</option>
                    @foreach($tingkatList as $t)
                        <option value="{{ $t }}" {{ $tingkat === $t ? 'selected' : '' }}>
                            Tingkat {{ $t }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Jurusan -->
            <div class="col-12 col-md-3">
                <label class="form-label text-xs fw-semibold text-muted mb-1">Filter Kategori Jurusan:</label>
                <select name="jurusan" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Jurusan --</option>
                    @foreach($jurusanList as $j)
                        <option value="{{ $j }}" {{ $jurusan === $j ? 'selected' : '' }}>
                            {{ $j }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Tahun Ajaran -->
            <div class="col-6 col-md-3">
                <label class="form-label text-xs fw-semibold text-muted mb-1">Tahun Ajaran:</label>
                <select name="tahun_ajaran" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Th. Ajaran --</option>
                    @foreach($tahunAjaranList as $ta)
                        <option value="{{ $ta }}" {{ $tahunAjaran === $ta ? 'selected' : '' }}>
                            {{ $ta }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Search Nama Kelas -->
            <div class="col-6 col-md-3">
                <label class="form-label text-xs fw-semibold text-muted mb-1">Cari Nama Kelas:</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Nama kelas..." value="{{ $search }}">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                </div>
            </div>

            @if($tingkat || $jurusan || $tahunAjaran || $search)
            <div class="col-12 text-end mt-2">
                <a href="{{ route('admin.kelas.index') }}" class="btn btn-link btn-sm text-decoration-none text-danger p-0">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter Kelas
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-people me-2 text-primary"></i>Daftar Kelas Terdaftar</h6>
        <span class="badge bg-secondary bg-opacity-10 text-secondary">Total: {{ $kelass->total() }} Kelas</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width:5%">#</th>
                        <th>Nama Kelas</th>
                        <th>Tingkat</th>
                        <th>Jurusan</th>
                        <th>Tahun Ajaran</th>
                        <th class="text-center">Jumlah Siswa</th>
                        <th class="text-center">Jumlah Ujian</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelass as $kelas)
                    <tr>
                        <td class="ps-4 text-muted">{{ $kelass->firstItem() + $loop->index }}</td>
                        <td>
                            <span class="fw-semibold">{{ $kelas->nama_kelas }}</span>
                        </td>
                        <td>
                            @if($kelas->tingkat)
                                <span class="badge bg-primary bg-opacity-10 text-primary">{{ $kelas->tingkat }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $kelas->jurusan ?? '-' }}</td>
                        <td>{{ $kelas->tahun_ajaran ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill">
                                {{ $kelas->siswas_count }} Siswa
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill">
                                {{ $kelas->ujians_count }} Ujian
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('admin.kelas.edit', $kelas->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.kelas.destroy', $kelas->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas {{ $kelas->nama_kelas }}? Pastikan tidak ada siswa di kelas ini.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-people display-4 d-block mb-2 opacity-25"></i>
                            Belum ada kelas yang terdaftar. <a href="{{ route('admin.kelas.create') }}">Tambahkan sekarang</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($kelass->hasPages())
    <div class="card-footer bg-white border-0 pt-0">
        {{ $kelass->links() }}
    </div>
    @endif
</div>
@endsection
