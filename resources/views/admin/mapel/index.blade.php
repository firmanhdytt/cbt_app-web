@extends('layouts.admin')

@section('title', 'Kelola Mata Pelajaran')
@section('page-title', 'Kelola Mata Pelajaran')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Mata Pelajaran</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.mapel.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Tambah Mata Pelajaran
    </a>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap text-md-wrap">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Kode Mapel</th>
                        <th>Nama Mata Pelajaran</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mapels as $index => $mapel)
                        <tr>
                            <td class="ps-4 text-muted">{{ $mapels->firstItem() + $index }}</td>
                            <td>
                                <span class="badge bg-dark bg-opacity-75 font-monospace px-3 py-2 fs-6 letter-spacing-wide">
                                    {{ $mapel->kode_mapel }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:38px;height:38px;flex-shrink:0">
                                        <i class="bi bi-book text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $mapel->nama_mapel }}</div>
                                        <div class="text-muted" style="font-size:11px">Ditambahkan {{ $mapel->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.mapel.edit', $mapel->id) }}" class="btn btn-outline-warning btn-sm py-1 px-2.5 text-xs" title="Edit">
                                        <i class="bi bi-pencil me-1"></i>Edit
                                    </a>
                                    <form action="{{ route('admin.mapel.destroy', $mapel->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus mata pelajaran {{ addslashes($mapel->nama_mapel) }}? Soal yang terkait akan tetap ada.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2.5 text-xs" title="Hapus">
                                            <i class="bi bi-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-book display-4 d-block mb-2 opacity-25"></i>
                                Belum ada mata pelajaran terdaftar.
                                <br><a href="{{ route('admin.mapel.create') }}" class="btn btn-sm btn-primary mt-2">Tambah Sekarang</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($mapels->hasPages())
    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
        <small class="text-muted">Total: {{ $mapels->total() }} mata pelajaran</small>
        {{ $mapels->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
