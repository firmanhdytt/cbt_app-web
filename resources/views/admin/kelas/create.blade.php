@extends('layouts.admin')

@section('title', 'Tambah Kelas')
@section('page-title', 'Tambah Kelas Baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.kelas.index') }}" class="text-decoration-none">Kelola Kelas</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Kelas</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card card-custom">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title mb-0 font-bold"><i class="bi bi-people me-2 text-primary"></i>Informasi Kelas</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.kelas.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nama_kelas" class="form-label font-medium">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_kelas') is-invalid @enderror"
                            id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas') }}"
                            placeholder="Contoh: X IPA 1" required>
                        @error('nama_kelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Nama lengkap kelas, misal: X IPA 1, XI IPS 2, XII Bahasa</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tingkat" class="form-label font-medium">Tingkat</label>
                            <select class="form-select @error('tingkat') is-invalid @enderror" id="tingkat" name="tingkat">
                                <option value="">-- Pilih Tingkat --</option>
                                <option value="X" {{ old('tingkat') == 'X' ? 'selected' : '' }}>X (Sepuluh)</option>
                                <option value="XI" {{ old('tingkat') == 'XI' ? 'selected' : '' }}>XI (Sebelas)</option>
                                <option value="XII" {{ old('tingkat') == 'XII' ? 'selected' : '' }}>XII (Dua Belas)</option>
                            </select>
                            @error('tingkat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jurusan" class="form-label font-medium">Jurusan</label>
                            <select class="form-select @error('jurusan') is-invalid @enderror" id="jurusan" name="jurusan">
                                <option value="">-- Pilih Jurusan --</option>
                                <option value="IPA" {{ old('jurusan') == 'IPA' ? 'selected' : '' }}>IPA</option>
                                <option value="IPS" {{ old('jurusan') == 'IPS' ? 'selected' : '' }}>IPS</option>
                                <option value="Bahasa" {{ old('jurusan') == 'Bahasa' ? 'selected' : '' }}>Bahasa</option>
                                <option value="Umum" {{ old('jurusan') == 'Umum' ? 'selected' : '' }}>Umum</option>
                            </select>
                            @error('jurusan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="tahun_ajaran" class="form-label font-medium">Tahun Ajaran</label>
                        <input type="text" class="form-control @error('tahun_ajaran') is-invalid @enderror"
                            id="tahun_ajaran" name="tahun_ajaran" value="{{ old('tahun_ajaran') }}"
                            placeholder="Contoh: 2025/2026">
                        @error('tahun_ajaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary btn-sm">Batal</a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-save me-1"></i> Simpan Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
