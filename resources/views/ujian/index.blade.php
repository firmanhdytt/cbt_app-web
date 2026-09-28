@extends('layouts.' . Auth::user()->role)

@section('title', 'Daftar Ujian')
@section('page-title', 'Kelola Ujian & Penjadwalan')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Ujian</li>
@endsection

@section('page-actions')
    <a href="{{ route(Auth::user()->role . '.ujian.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Buat Ujian Baru
    </a>
@endsection

@section('content')

<!-- Filter & Search Bar Card -->
<div class="card card-custom mb-4 border-0 shadow-sm">
    <div class="card-body p-3">
        <form method="GET" action="{{ route(Auth::user()->role . '.ujian.index') }}" class="row g-2 align-items-center">
            <!-- Filter Mapel -->
            <div class="col-12 col-md-3">
                <label class="form-label text-xs fw-semibold text-muted mb-1">Filter Mata Pelajaran:</label>
                <select name="mapel_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Mapel --</option>
                    @foreach($mapels as $m)
                        <option value="{{ $m->id }}" {{ (string)$mapelId === (string)$m->id ? 'selected' : '' }}>
                            {{ $m->nama_mapel }} ({{ $m->kode_mapel }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Kelas -->
            <div class="col-12 col-md-3">
                <label class="form-label text-xs fw-semibold text-muted mb-1">Filter Kelas Peserta:</label>
                <select name="kelas_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($kelass as $k)
                        <option value="{{ $k->id }}" {{ (string)$kelasId === (string)$k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status Ujian -->
            <div class="col-6 col-md-2">
                <label class="form-label text-xs fw-semibold text-muted mb-1">Filter Status:</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua --</option>
                    <option value="1" {{ (string)$status === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ (string)$status === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <!-- Search Judul Ujian -->
            <div class="col-6 col-md-4">
                <label class="form-label text-xs fw-semibold text-muted mb-1">Cari Judul Ujian:</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Tuliskan judul ujian..." value="{{ $search }}">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                </div>
            </div>

            @if($mapelId || $kelasId || ($status !== null && $status !== '') || $search)
            <div class="col-12 text-end mt-2">
                <a href="{{ route(Auth::user()->role . '.ujian.index') }}" class="btn btn-link btn-sm text-decoration-none text-danger p-0">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter Ujian
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-file-earmark-check me-2 text-primary"></i>Daftar Ujian Terjadwal</h6>
        <span class="badge bg-secondary bg-opacity-10 text-secondary">Total: {{ $ujians->total() }} Ujian</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap text-md-wrap">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas Peserta</th>
                        <th>Judul Ujian</th>
                        <th>Durasi</th>
                        <th>Masa Ujian</th>
                        <th class="text-center">Soal</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ujians as $index => $ujian)
                        <tr>
                            <td>{{ $ujians->firstItem() + $index }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $ujian->mapel->kode_mapel ?? '-' }}</span>
                                <div class="text-muted small mt-1">{{ $ujian->mapel->nama_mapel ?? '-' }}</div>
                            </td>
                            <td>
                                @if($ujian->kelas)
                                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ $ujian->kelas->nama_kelas }}</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                <strong class="text-slate-800">{{ $ujian->judul }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><i class="bi bi-clock me-1 text-primary"></i>{{ $ujian->durasi_menit }} Menit</span>
                            </td>
                            <td>
                                <div class="text-xs">
                                    <span class="text-success"><i class="bi bi-calendar-event me-1"></i>Mulai: {{ $ujian->tanggal_mulai->format('d M Y, H:i') }}</span>
                                    <br>
                                    <span class="text-danger"><i class="bi bi-calendar-x me-1"></i>Selesai: {{ $ujian->tanggal_selesai->format('d M Y, H:i') }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info text-white font-bold">{{ $ujian->soals_count }} Butir</span>
                            </td>
                            <td>
                                <form action="{{ route(Auth::user()->role . '.ujian.toggle', $ujian->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm py-1 px-2.5 rounded-full font-bold {{ $ujian->status ? 'btn-success text-white' : 'btn-outline-secondary' }}" title="Klik untuk mengubah status">
                                        {{ $ujian->status ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route(Auth::user()->role . '.ujian.show', $ujian->id) }}" class="btn btn-outline-info btn-sm py-1 px-2 text-xs" title="Detail">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>
                                    <a href="{{ route(Auth::user()->role . '.ujian.edit', $ujian->id) }}" class="btn btn-outline-warning btn-sm py-1 px-2 text-xs" title="Edit">
                                        <i class="bi bi-pencil me-1"></i>Edit
                                    </a>
                                    <form action="{{ route(Auth::user()->role . '.ujian.destroy', $ujian->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ujian ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2 text-xs" title="Hapus">
                                            <i class="bi bi-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-journal-x fs-2 d-block mb-2"></i>
                                Belum ada data ujian yang dijadwalkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{ $ujians->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
