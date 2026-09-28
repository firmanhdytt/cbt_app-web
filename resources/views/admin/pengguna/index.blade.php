@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Kelola Akun Pengguna')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Pengguna</li>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.pengguna.template') }}" class="btn btn-outline-secondary btn-sm px-2.5 text-xs font-semibold" title="Unduh Template Excel">
            <i class="bi bi-download me-1"></i> Template
        </a>
        <button type="button" class="btn btn-outline-success btn-sm px-3 text-xs font-semibold" data-bs-toggle="modal" data-bs-target="#modalImportExcel">
            <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
        </button>
        <a href="{{ route('admin.pengguna.create') }}" class="btn btn-primary btn-sm px-3 text-xs font-semibold">
            <i class="bi bi-plus-lg me-1"></i> Tambah Pengguna
        </a>
    </div>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3 border-0 shadow-sm" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3 border-0 shadow-sm" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Category Pill Nav & Search / Class Filter Bar --}}
<div class="card card-custom mb-3 border-0 shadow-sm">
    <div class="card-body py-3 px-3">
        <form method="GET" action="{{ route('admin.pengguna.index') }}" class="row g-2 align-items-center">
            
            {{-- Hidden role state --}}
            @if($role)
                <input type="hidden" name="role" value="{{ $role }}">
            @endif

            {{-- Role Category Navigation Pills --}}
            <div class="col-12 col-lg-auto mb-2 mb-lg-0">
                <div class="nav nav-pills gap-1">
                    <a href="{{ route('admin.pengguna.index', array_filter(['kelas_id' => $kelasId, 'search' => $search])) }}" 
                       class="nav-link nav-link-sm py-1.5 px-3 rounded-pill text-xs font-semibold {{ !$role ? 'active bg-primary text-white' : 'text-secondary bg-light' }}">
                        Semua <span class="badge {{ !$role ? 'bg-white text-primary' : 'bg-secondary text-white' }} ms-1">{{ $counts['all'] }}</span>
                    </a>
                    <a href="{{ route('admin.pengguna.index', array_filter(['role' => 'admin', 'kelas_id' => $kelasId, 'search' => $search])) }}" 
                       class="nav-link nav-link-sm py-1.5 px-3 rounded-pill text-xs font-semibold {{ $role === 'admin' ? 'active bg-danger text-white' : 'text-secondary bg-light' }}">
                        Admin <span class="badge {{ $role === 'admin' ? 'bg-white text-danger' : 'bg-secondary text-white' }} ms-1">{{ $counts['admin'] }}</span>
                    </a>
                    <a href="{{ route('admin.pengguna.index', array_filter(['role' => 'guru', 'kelas_id' => $kelasId, 'search' => $search])) }}" 
                       class="nav-link nav-link-sm py-1.5 px-3 rounded-pill text-xs font-semibold {{ $role === 'guru' ? 'active bg-warning text-dark' : 'text-secondary bg-light' }}">
                        Guru <span class="badge {{ $role === 'guru' ? 'bg-dark text-white' : 'bg-secondary text-white' }} ms-1">{{ $counts['guru'] }}</span>
                    </a>
                    <a href="{{ route('admin.pengguna.index', array_filter(['role' => 'siswa', 'kelas_id' => $kelasId, 'search' => $search])) }}" 
                       class="nav-link nav-link-sm py-1.5 px-3 rounded-pill text-xs font-semibold {{ $role === 'siswa' ? 'active bg-info text-white' : 'text-secondary bg-light' }}">
                        Siswa <span class="badge {{ $role === 'siswa' ? 'bg-white text-info' : 'bg-secondary text-white' }} ms-1">{{ $counts['siswa'] }}</span>
                    </a>
                </div>
            </div>

            {{-- Class Filter (Dropdown) --}}
            <div class="col-6 col-md-4 col-lg-3 ms-lg-auto">
                <select name="kelas_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Kelas Siswa --</option>
                    @foreach($kelass as $kelas)
                        <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}{{ $kelas->tahun_ajaran ? ' ('.$kelas->tahun_ajaran.')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Module In-Page Search Input --}}
            <div class="col-6 col-md-5 col-lg-3">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Cari Nama / NIS / Email..." value="{{ $search }}">
                    <button type="submit" class="btn btn-outline-secondary">Cari</button>
                    @if($search || $kelasId)
                        <a href="{{ route('admin.pengguna.index', $role ? ['role' => $role] : []) }}" class="btn btn-outline-danger" title="Reset Filter">✕</a>
                    @endif
                </div>
            </div>

        </form>
    </div>
</div>

{{-- Data Table --}}
<div class="card card-custom border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap text-md-wrap">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Pengguna</th>
                        <th>Kontak & Username</th>
                        <th>Peran</th>
                        @if(!$role || $role === 'siswa')
                        <th>NIS & Kelas</th>
                        @endif
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td class="ps-4 text-muted small">{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    @if($user->avatar && !str_starts_with($user->avatar, 'http'))
                                        <img src="{{ Storage::url($user->avatar) }}" class="rounded-circle border" width="34" height="34" style="object-fit:cover">
                                    @elseif($user->avatar && str_starts_with($user->avatar, 'http'))
                                        <img src="{{ $user->avatar }}" class="rounded-circle border" width="34" height="34" style="object-fit:cover">
                                    @else
                                        <div class="rounded-circle bg-light border text-secondary fw-bold d-flex align-items-center justify-content-center" style="width:34px;height:34px;font-size:12px;flex-shrink:0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold text-dark mb-0 text-sm">{{ $user->name }}</div>
                                        <div class="text-muted" style="font-size:11px;">Tersimpan {{ $user->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark small">{{ $user->email }}</div>
                                <div class="text-muted" style="font-size:11px;">@ {{ $user->username ?? '-' }}</div>
                            </td>
                            <td>
                                @if ($user->role === 'admin')
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle px-2.5 py-1">Admin</span>
                                @elseif ($user->role === 'guru')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle px-2.5 py-1">Guru</span>
                                @else
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info-subtle px-2.5 py-1">Siswa</span>
                                @endif
                            </td>
                            @if(!$role || $role === 'siswa')
                            <td>
                                @if ($user->role === 'siswa')
                                    <div class="small text-dark">NIS: {{ $user->nis ?? '-' }}</div>
                                    @if($user->kelas)
                                        <span class="badge bg-primary bg-opacity-10 text-primary fw-normal" style="font-size:10px;">
                                            {{ $user->kelas->nama_kelas }}
                                        </span>
                                    @else
                                        <span class="text-muted" style="font-size:11px;">-</span>
                                    @endif
                                @else
                                    <span class="text-muted" style="font-size:11px;">-</span>
                                @endif
                            </td>
                            @endif
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1">
                                    @if ($user->role === 'siswa')
                                        <a href="{{ route('admin.pengguna.kartu', $user->id) }}" class="btn btn-outline-success btn-sm py-1 px-2 text-xs" title="Cetak Kartu Peserta">
                                            Kartu
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.pengguna.edit', $user->id) }}" class="btn btn-outline-warning btn-sm py-1 px-2 text-xs" title="Edit Data">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.pengguna.destroy', $user->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ addslashes($user->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2 text-xs"
                                            {{ $user->id === Auth::id() ? 'disabled' : '' }}>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="py-3">
                                    <p class="mb-1 text-secondary font-semibold">Tidak ada data pengguna yang sesuai</p>
                                    <span class="small text-muted">
                                        Coba sesuaikan kata kunci pencarian atau pilihan filter kelas Anda.
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($users->hasPages())
    <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
        <small class="text-muted">Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} akun</small>
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<!-- Modal Import Excel Pengguna -->
<div class="modal fade" id="modalImportExcel" tabindex="-1" aria-labelledby="modalImportExcelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3">
                <h6 class="modal-title font-bold text-dark" id="modalImportExcelLabel">
                    <i class="bi bi-file-earmark-excel text-success me-2"></i> Import Pengguna via Excel / CSV
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.pengguna.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 text-xs mb-3">
                        <i class="bi bi-info-circle me-1"></i> Unduh <a href="{{ route('admin.pengguna.template') }}" class="fw-bold text-primary">Template Excel Pengguna</a> untuk memastikan struktur kolom sesuai.
                    </div>
                    <div class="mb-3">
                        <label for="excelFile" class="form-label text-xs font-semibold text-custom-secondary">Pilih File Excel / CSV (.xlsx, .xls, .csv)</label>
                        <input type="file" class="form-control form-control-sm" id="excelFile" name="file" accept=".xlsx, .xls, .csv" required>
                    </div>
                </div>
                <div class="modal-footer border-top py-2">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm px-4 font-semibold">
                        <i class="bi bi-upload me-1"></i> Upload & Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
