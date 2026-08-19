<section>
    <header class="mb-4">
        <h5 class="font-bold text-danger">
            {{ __('Hapus Akun') }}
        </h5>
        <p class="text-xs text-custom-secondary mb-0">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun, mohon unduh data apa pun yang ingin Anda simpan.') }}
        </p>
    </header>

    <!-- Trigger Button -->
    <button type="button" class="btn btn-modern btn-danger btn-sm px-4" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
        {{ __('Hapus Akun Saya') }}
    </button>

    <!-- Bootstrap 5 Confirm Deletion Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-custom-card border-custom">
                <div class="modal-header border-custom">
                    <h5 class="modal-title font-bold text-danger" id="confirmDeleteModalLabel">Hapus Akun Permanen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-body">
                        <p class="text-sm text-custom-primary">
                            {{ __('Apakah Anda yakin ingin menghapus akun Anda secara permanen? Masukkan kata sandi Anda untuk mengonfirmasi tindakan ini.') }}
                        </p>

                        <!-- Password input -->
                        <div class="mt-3">
                            <label for="password" class="form-label text-custom-secondary text-xs uppercase font-bold tracking-wider">Kata Sandi Konfirmasi</label>
                            <input type="password" class="form-control form-modern @error('password', 'userDeletion') is-invalid @enderror" id="password" name="password" required placeholder="Masukkan kata sandi saat ini">
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="modal-footer border-custom justify-content-between">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('Batal') }}</button>
                        <button type="submit" class="btn btn-danger btn-sm px-4">{{ __('Ya, Hapus Akun') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Auto-open Modal on Validation Error -->
    @if ($errors->userDeletion->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                deleteModal.show();
            });
        </script>
    @endif
</section>
