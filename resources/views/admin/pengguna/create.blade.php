@extends('layouts.admin')

@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna Baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.pengguna.index') }}" class="text-decoration-none">Pengguna</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title mb-0 font-bold">Formulir Pembuatan Akun</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.pengguna.store') }}" method="POST">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div class="mb-3">
                        <label for="name" class="form-label font-medium">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label font-medium">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required placeholder="Contoh: budis123">
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- E-Mail Address -->
                    <div class="mb-3">
                        <label for="email" class="form-label font-medium">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="Contoh: budi@gmail.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Peran (Role) -->
                    <div class="mb-3">
                        <label for="role" class="form-label font-medium">Peran / Role <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">-- Pilih Peran --</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="guru" {{ old('role') === 'guru' ? 'selected' : '' }}>Guru / Pengajar</option>
                            <option value="siswa" {{ old('role') === 'siswa' ? 'selected' : '' }}>Siswa / Peserta</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Fields Khusus Siswa (NIS & Kelas) -->
                    <div id="studentFields" class="{{ old('role') === 'siswa' ? '' : 'd-none' }} border p-3 rounded bg-light mb-3">
                        <h6 class="border-bottom pb-2 mb-3 text-muted">Informasi Siswa</h6>
                        
                        <!-- NIS -->
                        <div class="mb-3">
                            <label for="nis" class="form-label font-medium">NIS (Nomor Induk Siswa) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nis') is-invalid @enderror" id="nis" name="nis" value="{{ old('nis') }}" placeholder="Contoh: 12345678">
                            @error('nis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kelas (Dropdown dari tabel kelas) -->
                        <div class="mb-3">
                            <label for="kelas_id" class="form-label font-medium">Kelas <span class="text-danger">*</span></label>
                            <select class="form-select @error('kelas_id') is-invalid @enderror" id="kelas_id" name="kelas_id">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelass as $kelas)
                                    <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                        {{ $kelas->nama_kelas }}{{ $kelas->tahun_ajaran ? ' ('.$kelas->tahun_ajaran.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Nomor Telepon (Opsional) -->
                    <div class="mb-3">
                        <label for="phone" class="form-label font-medium">Nomor Telepon</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 08123456789">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label font-medium">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Minimal 8 karakter">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label font-medium">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Ketik ulang password">
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-4">
                        <a href="{{ route('admin.pengguna.index') }}" class="btn btn-secondary btn-sm">Batal</a>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i> Simpan Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('role').addEventListener('change', function() {
            const studentFields = document.getElementById('studentFields');
            const nisInput = document.getElementById('nis');
            if (this.value === 'siswa') {
                studentFields.classList.remove('d-none');
                nisInput.setAttribute('required', 'required');
            } else {
                studentFields.classList.add('d-none');
                nisInput.removeAttribute('required');
            }
        });
    });
</script>
@endsection
