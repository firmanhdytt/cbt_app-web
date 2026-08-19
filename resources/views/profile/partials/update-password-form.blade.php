<section>
    <header class="mb-4">
        <h5 class="font-bold text-custom-primary">
            {{ __('Ubah Kata Sandi') }}
        </h5>
        <p class="text-xs text-custom-secondary mb-0">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk menjaga keamanan.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="needs-validation">
        @csrf
        @method('put')

        <!-- Kata Sandi Saat Ini -->
        <div class="mb-3">
            <label for="update_password_current_password" class="form-label text-custom-secondary text-xs uppercase font-bold tracking-wider">{{ __('Kata Sandi Saat Ini') }}</label>
            <input type="password" class="form-control form-modern @error('current_password', 'updatePassword') is-invalid @enderror" id="update_password_current_password" name="current_password" autocomplete="current-password" required>
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Kata Sandi Baru -->
        <div class="mb-3">
            <label for="update_password_password" class="form-label text-custom-secondary text-xs uppercase font-bold tracking-wider">{{ __('Kata Sandi Baru') }}</label>
            <input type="password" class="form-control form-modern @error('password', 'updatePassword') is-invalid @enderror" id="update_password_password" name="password" autocomplete="new-password" required>
            @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Konfirmasi Kata Sandi Baru -->
        <div class="mb-4">
            <label for="update_password_password_confirmation" class="form-label text-custom-secondary text-xs uppercase font-bold tracking-wider">{{ __('Konfirmasi Kata Sandi') }}</label>
            <input type="password" class="form-control form-modern @error('password_confirmation', 'updatePassword') is-invalid @enderror" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password" required>
            @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-modern btn-modern-primary btn-sm px-4">{{ __('Ubah Password') }}</button>
            @if (session('status') === 'password-updated')
                <span class="text-xs text-success font-semibold animate-fade"><i class="bi bi-check-circle-fill me-1"></i>Tersimpan.</span>
            @endif
        </div>
    </form>
</section>
