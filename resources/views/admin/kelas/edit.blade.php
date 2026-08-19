@extends('layouts.admin')

@section('title', 'Edit Kelas')
@section('page-title', 'Edit Data Kelas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.kelas.index') }}" class="text-decoration-none">Kelola Kelas</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Kelas</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card card-custom">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title mb-0 font-bold"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit: {{ $kelas->nama_kelas }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.kelas.update', $kelas->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama_kelas" class="form-label font-medium">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_kelas') is-invalid @enderror"
                            id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}"
                            placeholder="Contoh: X IPA 1" required>
                        @error('nama_kelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tingkat" class="form-label font-medium">Tingkat</label>
                            <select class="form-select @error('tingkat') is-invalid @enderror" id="tingkat" name="tingkat">
                                <option value="">-- Pilih Tingkat --</option>
                                <option value="X" {{ old('tingkat', $kelas->tingkat) == 'X' ? 'selected' : '' }}>X (Sepuluh)</option>
                                <option value="XI" {{ old('tingkat', $kelas->tingkat) == 'XI' ? 'selected' : '' }}>XI (Sebelas)</option>
                                <option value="XII" {{ old('tingkat', $kelas->tingkat) == 'XII' ? 'selected' : '' }}>XII (Dua Belas)</option>
                            </select>
                            @error('tingkat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jurusan" class="form-label font-medium">Jurusan</label>
                            <select class="form-select @error('jurusan') is-invalid @enderror" id="jurusan" name="jurusan">
                                <option value="">-- Pilih Jurusan --</option>
                                <option value="IPA" {{ old('jurusan', $kelas->jurusan) == 'IPA' ? 'selected' : '' }}>IPA</option>
                                <option value="IPS" {{ old('jurusan', $kelas->jurusan) == 'IPS' ? 'selected' : '' }}>IPS</option>
                                <option value="Bahasa" {{ old('jurusan', $kelas->jurusan) == 'Bahasa' ? 'selected' : '' }}>Bahasa</option>
                                <option value="Umum" {{ old('jurusan', $kelas->jurusan) == 'Umum' ? 'selected' : '' }}>Umum</option>
                            </select>
                            @error('jurusan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="tahun_ajaran" class="form-label font-medium">Tahun Ajaran</label>
                        <input type="text" class="form-control @error('tahun_ajaran') is-invalid @enderror"
                            id="tahun_ajaran" name="tahun_ajaran" value="{{ old('tahun_ajaran', $kelas->tahun_ajaran) }}"
                            placeholder="Contoh: 2025/2026">
                        @error('tahun_ajaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary btn-sm">Batal</a>
                        <button type="submit" class="btn btn-warning btn-sm text-white">
                            <i class="bi bi-save me-1"></i> Perbarui Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
