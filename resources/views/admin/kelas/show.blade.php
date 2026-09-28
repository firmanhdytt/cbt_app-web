@extends('layouts.admin')

@section('title', 'Detail Kelas: ' . $kelas->nama_kelas)
@section('page-title', 'Detail & Anggota Kelas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.kelas.index') }}">Kelas</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $kelas->nama_kelas }}</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary btn-sm px-3 font-semibold me-1">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <a href="{{ route('admin.kelas.edit', $kelas->id) }}" class="btn btn-warning btn-sm px-3 font-semibold">
        <i class="bi bi-pencil me-1"></i> Edit Kelas
    </a>
@endsection

@section('content')

<!-- Header Detail Kelas Card -->
<div class="card card-custom mb-4 border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width:56px; height:56px; font-size:1.5rem; flex-shrink:0;">
                    <i class="bi bi-door-open-fill"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">{{ $kelas->nama_kelas }}</h4>
                    <div class="text-muted text-xs d-flex flex-wrap align-items-center gap-2">
                        <span><i class="bi bi-layers me-1"></i>Tingkat: <strong>{{ $kelas->tingkat ?? '-' }}</strong></span>
                        <span>&bull;</span>
                        <span><i class="bi bi-journal-bookmark me-1"></i>Jurusan: <strong>{{ $kelas->jurusan ?? '-' }}</strong></span>
                        <span>&bull;</span>
                        <span><i class="bi bi-calendar-event me-1"></i>Tahun Ajaran: <strong>{{ $kelas->tahun_ajaran ?? '-' }}</strong></span>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 text-center">
                <div class="px-3 py-2 bg-light border rounded-3">
                    <span class="d-block fw-bold fs-4 text-primary">{{ $kelas->siswas_count }}</span>
                    <span class="text-xs text-muted">Siswa Terdaftar</span>
                </div>
                <div class="px-3 py-2 bg-light border rounded-3">
                    <span class="d-block fw-bold fs-4 text-success">{{ $kelas->ujians_count }}</span>
                    <span class="text-xs text-muted">Ujian Terkait</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Daftar Siswa Terdaftar (8 cols) -->
    <div class="col-lg-8 mb-4">
        <div class="card card-custom border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="font-bold text-dark mb-0">
                    <i class="bi bi-people-fill text-primary me-2"></i>Daftar Siswa Terdaftar ({{ $kelas->siswas_count }})
                </h6>
                <a href="{{ route('admin.pengguna.create', ['role' => 'siswa', 'kelas_id' => $kelas->id]) }}" class="btn btn-sm btn-outline-primary text-xs">
                    <i class="bi bi-person-plus me-1"></i>Tambah Siswa
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">No</th>
                                <th>Siswa</th>
                                <th>NIS</th>
                                <th>Username / Email</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswas as $index => $siswa)
                                <tr>
                                    <td class="ps-4 text-muted small">{{ $siswas->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($siswa->avatar_url)
                                                <img src="{{ $siswa->avatar_url }}" width="32" height="32" class="rounded-circle object-fit-cover border">
                                            @else
                                                <div class="rounded-circle bg-light border text-secondary fw-bold d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:11px;">
                                                    {{ strtoupper(substr($siswa->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <span class="fw-semibold text-dark text-sm">{{ $siswa->name }}</span>
                                        </div>
                                    </td>
                                    <td><span class="font-monospace fw-bold text-xs">{{ $siswa->nis ?? '-' }}</span></td>
                                    <td><span class="text-xs text-muted">{{ $siswa->username ?? $siswa->email }}</span></td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.pengguna.kartu', $siswa->id) }}" class="btn btn-outline-success btn-sm py-1 px-2 text-xs" title="Cetak Kartu">
                                            Kartu
                                        </a>
                                        <a href="{{ route('admin.pengguna.edit', $siswa->id) }}" class="btn btn-outline-warning btn-sm py-1 px-2 text-xs" title="Edit">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Belum ada siswa yang terdaftar di kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($siswas->hasPages())
                <div class="card-footer bg-white border-top py-2">
                    {{ $siswas->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column: Ujian Khusus Kelas (4 cols) -->
    <div class="col-lg-4 mb-4">
        <div class="card card-custom border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="font-bold text-dark mb-0">
                    <i class="bi bi-file-earmark-check text-success me-2"></i>Ujian Kelas Ini ({{ $ujians->count() }})
                </h6>
            </div>
            <div class="card-body p-3">
                <div class="list-group list-group-flush">
                    @forelse($ujians as $ujian)
                        <div class="list-group-item px-0 py-2.5 border-0 border-bottom border-custom bg-transparent">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="fw-bold text-dark text-sm mb-0">{{ $ujian->judul }}</h6>
                                <span class="badge {{ $ujian->status ? 'bg-success' : 'bg-secondary' }} text-xs">
                                    {{ $ujian->status ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </div>
                            <div class="text-xs text-muted mb-1">
                                Mapel: <strong>{{ $ujian->mapel->nama_mapel ?? '-' }}</strong> &bull; {{ $ujian->durasi_menit }} Menit
                            </div>
                            <div class="text-xs text-secondary">
                                Periode: {{ $ujian->tanggal_mulai ? $ujian->tanggal_mulai->format('d M H:i') : '-' }} s/d {{ $ujian->tanggal_selesai ? $ujian->tanggal_selesai->format('d M H:i') : '-' }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted text-xs">
                            Belum ada jadwal ujian khusus untuk kelas ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
