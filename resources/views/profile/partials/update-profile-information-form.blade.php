<section>
    <header class="mb-4">
        <h5 class="font-bold text-custom-primary">
            {{ __('Informasi Profil') }}
        </h5>
        <p class="text-xs text-custom-secondary mb-0">
            {{ __("Perbarui data profil akun Anda, username, dan alamat email.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="needs-validation">
        @csrf
        @method('patch')

        <!-- Nama Lengkap -->
        <div class="mb-3">
            <label for="name" class="form-label text-custom-secondary text-xs uppercase font-bold tracking-wider">{{ __('Nama Lengkap') }}</label>
            <input type="text" class="form-control form-modern @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Username -->
        <div class="mb-3">
            <label for="username" class="form-label text-custom-secondary text-xs uppercase font-bold tracking-wider">{{ __('Username') }}</label>
            <input type="text" class="form-control form-modern @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username) }}" required autocomplete="username">
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Alamat Email -->
        <div class="mb-4">
            <label for="email" class="form-label text-custom-secondary text-xs uppercase font-bold tracking-wider">{{ __('Alamat Email') }}</label>
            <input type="email" class="form-control form-modern @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-2.5 bg-warning bg-opacity-10 border border-warning rounded-3 text-xs text-custom-primary">
                    <p class="mb-1">Alamat email Anda belum terverifikasi.</p>
                    <button form="send-verification" class="btn btn-link p-0 text-xs text-primary font-bold text-decoration-none">
                        Klik di sini untuk mengirim ulang email verifikasi.
                    </button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 text-success font-bold">Link verifikasi baru telah dikirim ke email Anda.</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-modern btn-modern-primary btn-sm px-4">{{ __('Simpan Profil') }}</button>
            @if (session('status') === 'profile-updated')
                <span class="text-xs text-success font-semibold animate-fade"><i class="bi bi-check-circle-fill me-1"></i>Tersimpan.</span>
            @endif
        </div>
    </form>

    <!-- Gmail Account Linking Section -->
    <div class="mt-5 pt-4 border-top border-custom">
        <h6 class="font-bold text-custom-primary mb-1">
            {{ __('Integrasi Akun Gmail') }}
        </h6>
        <p class="text-xs text-custom-secondary mb-3">
            {{ __('Hubungkan akun Anda dengan Gmail untuk masuk menggunakan Google OAuth secara cepat.') }}
        </p>

        @if ($errors->get('google'))
            <div class="alert alert-danger p-2 text-xs mb-3">
                @foreach ($errors->get('google') as $msg)
                    <div>{{ $msg }}</div>
                @endforeach
            </div>
        @endif

        @if (session('status') === 'google-linked')
            <div class="alert alert-success p-2.5 text-xs mb-3">
                {{ __('Akun Gmail berhasil dikaitkan.') }}
            </div>
        @endif

        @if (session('status') === 'google-unlinked')
            <div class="alert alert-warning p-2.5 text-xs mb-3">
                {{ __('Tautan akun Gmail telah diputuskan.') }}
            </div>
        @endif

        @if ($user->google_id)
            <div class="p-3 bg-success bg-opacity-10 border border-success rounded-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-google text-success fs-5"></i>
                    <div>
                        <span class="badge bg-success mb-1">Gmail Terhubung</span>
                        <div class="text-xs text-custom-secondary">{{ $user->google_email ?? $user->email }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('google.unlink') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm text-xs font-semibold">Putuskan Hubungan</button>
                </form>
            </div>
        @else
            <a href="{{ route('google.redirect', ['mode' => 'link']) }}" class="btn btn-modern btn-modern-secondary btn-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-google text-danger"></i> Hubungkan Akun Gmail
            </a>
        @endif
    </div>
</section>
