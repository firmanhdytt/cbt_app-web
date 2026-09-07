<section>
    @if (session('status') === 'avatar-deleted')
        <div class="alert alert-success alert-dismissible fade show p-2.5 text-xs mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i>Foto profil berhasil dihapus.
            <button type="button" class="btn-close text-xs" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Avatar & Profile Header (Clean Modern Layout) -->
        <div class="d-flex align-items-center gap-4 mb-4 pb-3 border-bottom border-custom">
            <div class="position-relative flex-shrink-0">
                @if ($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" width="84" height="84" class="rounded-circle object-fit-cover border border-custom shadow-sm">
                @else
                    <div class="avatar-circle" style="width:84px; height:84px; font-size:2rem; font-weight:700; background-color:var(--primary); color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 12px rgba(79,70,229,0.2);">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            <div>
                <h5 class="fw-bold text-custom-primary mb-1">{{ $user->name }}</h5>
                <p class="text-xs text-custom-secondary mb-2">{{ '@' . $user->username }} &bull; <span class="badge bg-primary bg-opacity-10 text-primary text-uppercase">{{ $user->role }}</span></p>
                
                <div class="d-flex align-items-center gap-2">
                    <label for="avatar" class="btn btn-outline-primary btn-sm text-xs px-3 py-1 font-semibold mb-0" style="cursor:pointer;">
                        <i class="bi bi-camera me-1"></i>Pilih Foto Baru
                    </label>
                    <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*" onchange="document.getElementById('fileNameSpan').textContent = this.files[0] ? this.files[0].name : ''">
                    
                    @if ($user->avatar)
                        <button type="submit" form="delete-avatar-form" class="btn btn-link text-danger btn-sm text-xs p-0 ms-2 text-decoration-none font-semibold" onclick="return confirm('Apakah Anda yakin ingin menghapus foto profil ini?')">
                            Hapus Foto
                        </button>
                    @endif
                </div>
                <span id="fileNameSpan" class="d-block text-xs text-primary mt-1 font-monospace"></span>
                @error('avatar')
                    <div class="text-danger text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Form Inputs -->
        <div class="row g-3">
            <div class="col-md-6">
                <label for="name" class="form-label text-xs fw-semibold text-custom-secondary">Nama Lengkap</label>
                <input type="text" class="form-control form-control-sm form-modern @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="username" class="form-label text-xs fw-semibold text-custom-secondary">Username</label>
                <input type="text" class="form-control form-control-sm form-modern @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label for="email" class="form-label text-xs fw-semibold text-custom-secondary">Alamat Email</label>
                <input type="email" class="form-control form-control-sm form-modern @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-4 pt-2 d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold">Simpan Perubahan</button>
            @if (session('status') === 'profile-updated')
                <span class="text-xs text-success font-semibold"><i class="bi bi-check-circle-fill me-1"></i>Tersimpan.</span>
            @endif
        </div>
    </form>

    <!-- Hidden Form for Avatar Deletion -->
    <form id="delete-avatar-form" method="post" action="{{ route('profile.avatar.destroy') }}" class="d-none">
        @csrf
        @method('delete')
    </form>

    <!-- Gmail Integration (Minimal & Clean) -->
    <div class="mt-4 pt-3 border-top border-custom d-flex justify-content-between align-items-center">
        <div>
            <span class="fw-semibold text-custom-primary text-xs d-block">Integrasi Google GMail</span>
            <span class="text-xs text-custom-secondary">
                {{ $user->google_id ? 'Akun Gmail terhubung (' . ($user->google_email ?? $user->email) . ')' : 'Hubungkan untuk masuk cepat via Google.' }}
            </span>
        </div>
        <div>
            @if ($user->google_id)
                <form method="POST" action="{{ route('google.unlink') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm text-xs py-1">Putuskan</button>
                </form>
            @else
                <a href="{{ route('google.redirect', ['mode' => 'link']) }}" class="btn btn-outline-secondary btn-sm text-xs py-1 d-inline-flex align-items-center gap-1">
                    <i class="bi bi-google text-danger"></i> Hubungkan
                </a>
            @endif
        </div>
    </div>
</section>
