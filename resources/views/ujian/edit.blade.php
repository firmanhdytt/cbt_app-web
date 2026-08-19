@extends('layouts.' . Auth::user()->role)

@section('title', 'Edit Ujian')
@section('page-title', 'Edit & Jadwalkan Ujian')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role . '.ujian.index') }}" class="text-decoration-none">Ujian</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Ujian</li>
@endsection

@section('content')
<form action="{{ route(Auth::user()->role . '.ujian.update', $ujian->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <!-- Left Panel: Ujian Configuration (6-cols) -->
        <div class="col-lg-5 mb-4">
            <div class="card card-custom h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="card-title mb-0 font-bold">Informasi Penjadwalan</h5>
                </div>
                <div class="card-body">
                    <!-- Mata Pelajaran -->
                    <div class="mb-3">
                        <label for="mapel_id" class="form-label font-medium">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select class="form-select @error('mapel_id') is-invalid @enderror" id="mapel_id" name="mapel_id" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach ($mapels as $mapel)
                                <option value="{{ $mapel->id }}" {{ old('mapel_id', $ujian->mapel_id) == $mapel->id ? 'selected' : '' }}>
                                    [{{ $mapel->kode_mapel }}] {{ $mapel->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                        @error('mapel_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kelas Peserta -->
                    <div class="mb-3">
                        <label for="kelas_id" class="form-label font-medium">Kelas Peserta <span class="text-danger">*</span></label>
                        <select class="form-select @error('kelas_id') is-invalid @enderror" id="kelas_id" name="kelas_id" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelass as $kelas)
                                <option value="{{ $kelas->id }}" {{ old('kelas_id', $ujian->kelas_id) == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }}{{ $kelas->tahun_ajaran ? ' ('.$kelas->tahun_ajaran.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Judul Ujian -->
                    <div class="mb-3">
                        <label for="judul" class="form-label font-medium">Judul Ujian <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul', $ujian->judul) }}" required placeholder="Contoh: Ujian Tengah Semester Ganjil">
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deskripsi Ujian -->
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label font-medium">Deskripsi / Petunjuk Ujian</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3" placeholder="Contoh: Bacalah soal dengan teliti dan pilih satu jawaban yang benar...">{{ old('deskripsi', $ujian->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Durasi Ujian -->
                    <div class="mb-3">
                        <label for="durasi_menit" class="form-label font-medium">Durasi Ujian (Menit) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('durasi_menit') is-invalid @enderror" id="durasi_menit" name="durasi_menit" value="{{ old('durasi_menit', $ujian->durasi_menit) }}" min="1" required>
                            <span class="input-group-text">Menit</span>
                            @error('durasi_menit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div class="mb-3">
                        <label for="tanggal_mulai" class="form-label font-medium">Tanggal & Waktu Mulai <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $ujian->tanggal_mulai ? $ujian->tanggal_mulai->format('Y-m-d\TH:i') : '') }}" required>
                        @error('tanggal_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Selesai -->
                    <div class="mb-3">
                        <label for="tanggal_selesai" class="form-label font-medium">Tanggal & Waktu Selesai <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $ujian->tanggal_selesai ? $ujian->tanggal_selesai->format('Y-m-d\TH:i') : '') }}" required>
                        @error('tanggal_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status Aktif -->
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" value="1" {{ old('status', $ujian->status) == '1' ? 'checked' : '' }}>
                        <label class="form-check-label font-medium" for="status">Aktifkan Ujian</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Question Selection (7-cols) -->
        <div class="col-lg-7 mb-4">
            <div class="card card-custom h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 font-bold">Pilih Soal Ujian <span class="text-danger">*</span></h5>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="btnSelectAll">Pilih Semua</button>
                </div>
                <div class="card-body">
                    @error('soals')
                        <div class="alert alert-danger p-2 mb-3 text-xs">{{ $message }}</div>
                    @enderror

                    <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                        <table class="table table-hover align-middle table-sm" id="soalsTable">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th style="width: 10%" class="text-center">Pilih</th>
                                    <th style="width: 25%">Mapel</th>
                                    <th style="width: 65%">Pertanyaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $selectedSoalIds = old('soals', $ujian->soals->pluck('id')->toArray());
                                @endphp
                                @forelse ($soals as $soal)
                                    <tr data-mapel-id="{{ $soal->mapel_id }}" data-kelas-id="{{ $soal->kelas_id ?? '' }}" class="question-row">
                                        <td class="text-center">
                                            <input class="form-check-input question-checkbox" type="checkbox" name="soals[]" value="{{ $soal->id }}" {{ in_array($soal->id, $selectedSoalIds) ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary text-xs">{{ $soal->mapel->kode_mapel ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <div class="text-sm text-truncate" style="max-width: 320px;" title="{{ strip_tags($soal->pertanyaan) }}">
                                                {{ strip_tags($soal->pertanyaan) }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">
                                            Belum ada soal terdaftar di bank soal. Silakan isi bank soal terlebih dahulu.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky Bottom Bar -->
    <div class="d-flex justify-content-end gap-2 border-top pt-3 bg-white p-3 shadow-sm rounded-3">
        <a href="{{ route(Auth::user()->role . '.ujian.index') }}" class="btn btn-secondary btn-sm">Batal</a>
        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i> Perbarui & Simpan</button>
    </div>
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const mapelSelect = document.getElementById('mapel_id');
        const kelasSelect = document.getElementById('kelas_id');
        const questionRows = document.querySelectorAll('.question-row');
        const btnSelectAll = document.getElementById('btnSelectAll');

        function filterQuestions() {
            const selectedMapelId = mapelSelect.value;
            const selectedKelasId = kelasSelect.value;
            let visibleCount = 0;

            questionRows.forEach(row => {
                const rowMapelId = row.getAttribute('data-mapel-id');
                const rowKelasId = row.getAttribute('data-kelas-id');
                const checkbox = row.querySelector('.question-checkbox');

                if (!selectedMapelId) {
                    row.style.display = 'none';
                    if (checkbox) checkbox.checked = false;
                    return;
                }

                const mapelMatch = rowMapelId === selectedMapelId;
                const kelasMatch = !selectedKelasId || rowKelasId === '' || rowKelasId === selectedKelasId;

                if (mapelMatch && kelasMatch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                    if (checkbox) checkbox.checked = false;
                }
            });

            btnSelectAll.disabled = (visibleCount === 0);
            updateSelectAllButtonText();
        }

        function updateSelectAllButtonText() {
            const visibleCheckboxes = Array.from(questionRows)
                .filter(row => row.style.display !== 'none')
                .map(row => row.querySelector('.question-checkbox'));

            if (visibleCheckboxes.length === 0) {
                btnSelectAll.textContent = 'Pilih Semua';
                btnSelectAll.classList.add('btn-outline-primary');
                btnSelectAll.classList.remove('btn-outline-secondary');
                return;
            }

            const allChecked = visibleCheckboxes.every(cb => cb.checked);
            btnSelectAll.textContent = allChecked ? 'Batal Pilih Semua' : 'Pilih Semua';
            btnSelectAll.classList.toggle('btn-outline-primary', !allChecked);
            btnSelectAll.classList.toggle('btn-outline-secondary', allChecked);
        }

        mapelSelect.addEventListener('change', filterQuestions);
        kelasSelect.addEventListener('change', filterQuestions);

        questionRows.forEach(row => {
            const checkbox = row.querySelector('.question-checkbox');
            if (checkbox) checkbox.addEventListener('change', updateSelectAllButtonText);
        });

        btnSelectAll.addEventListener('click', function() {
            const visibleCheckboxes = Array.from(questionRows)
                .filter(row => row.style.display !== 'none')
                .map(row => row.querySelector('.question-checkbox'));
            if (visibleCheckboxes.length === 0) return;
            const allChecked = visibleCheckboxes.every(cb => cb.checked);
            visibleCheckboxes.forEach(cb => cb.checked = !allChecked);
            updateSelectAllButtonText();
        });

        filterQuestions();
    });
</script>
@endsection
