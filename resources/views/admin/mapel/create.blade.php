@extends('layouts.admin')

@section('title', 'Tambah Mapel')
@section('page-title', 'Tambah Mata Pelajaran Baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.mapel.index') }}" class="text-decoration-none">Mata Pelajaran</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card card-custom shadow-sm border-0">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title mb-0 font-bold">Formulir Mata Pelajaran</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.mapel.store') }}" method="POST">
                    @csrf

                    <!-- Kode Mapel -->
                    <div class="mb-3">
                        <label for="kode_mapel" class="form-label font-medium">Kode Mapel <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('kode_mapel') is-invalid @enderror" id="kode_mapel" name="kode_mapel" value="{{ old('kode_mapel') }}" required placeholder="Contoh: MTK, BIN, BIG">
                        <div class="form-text text-xs text-muted">Kode unik pendek, gunakan huruf kapital (misal: MTK).</div>
                        @error('kode_mapel')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nama Mapel -->
                    <div class="mb-4">
                        <label for="nama_mapel" class="form-label font-medium">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_mapel') is-invalid @enderror" id="nama_mapel" name="nama_mapel" value="{{ old('nama_mapel') }}" required placeholder="Contoh: Matematika Wajib">
                        @error('nama_mapel')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-4">
                        <a href="{{ route('admin.mapel.index') }}" class="btn btn-secondary btn-sm">Batal</a>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i> Simpan Mapel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
