@extends('layouts.' . Auth::user()->role)

@section('title', 'Tambah Soal')
@section('page-title', 'Tambah Soal Baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role . '.soal.index') }}" class="text-decoration-none">Bank Soal</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
<form action="{{ route(Auth::user()->role . '.soal.store') }}" method="POST">
    @csrf

    <div class="row">
        <!-- Left Panel: Editor (6-cols) -->
        <div class="col-lg-6 mb-4">
            <div class="card-modern h-100 mb-0">
                <h5 class="font-bold text-custom-primary mb-3">Formulir Soal</h5>
                
                <!-- Mata Pelajaran -->
                <div class="mb-3">
                    <label for="mapel_id" class="form-label text-custom-secondary text-xs uppercase font-bold tracking-wider">Mata Pelajaran <span class="text-danger">*</span></label>
                    <select class="form-select form-modern @error('mapel_id') is-invalid @enderror" id="mapel_id" name="mapel_id" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach ($mapels as $mapel)
                            <option value="{{ $mapel->id }}" {{ old('mapel_id') == $mapel->id ? 'selected' : '' }}>
                                [{{ $mapel->kode_mapel }}] {{ $mapel->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                    @error('mapel_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Kelas -->
                <div class="mb-3">
                    <label for="kelas_id" class="form-label text-custom-secondary text-xs uppercase font-bold tracking-wider">Kelas Peserta</label>
                    <select class="form-select form-modern @error('kelas_id') is-invalid @enderror" id="kelas_id" name="kelas_id">
                        <option value="">-- Semua Kelas (Tidak Spesifik) --</option>
                        @foreach ($kelass as $kelas)
                            <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}{{ $kelas->tahun_ajaran ? ' ('.$kelas->tahun_ajaran.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('kelas_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Kosongkan jika soal ini berlaku untuk semua kelas.</div>
                </div>

                <!-- Pertanyaan -->
                <div class="mb-3">
                    <label for="pertanyaan" class="form-label text-custom-secondary text-xs uppercase font-bold tracking-wider">Pertanyaan Soal <span class="text-danger">*</span></label>
                    <textarea class="form-control form-modern @error('pertanyaan') is-invalid @enderror" id="pertanyaan" name="pertanyaan" rows="4" required placeholder="Tulis isi pertanyaan di sini...">{{ old('pertanyaan') }}</textarea>
                    @error('pertanyaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <h6 class="border-bottom border-custom pb-2 mb-3 mt-4 text-custom-secondary text-xs uppercase font-bold tracking-wider">Pilihan Jawaban & Kunci</h6>

                <!-- Pilihan A -->
                <div class="mb-3">
                    <label for="pilihan_a" class="form-label text-custom-secondary text-xs uppercase font-bold">Pilihan A <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-modern @error('pilihan_a') is-invalid @enderror" id="pilihan_a" name="pilihan_a" value="{{ old('pilihan_a') }}" required placeholder="Pilihan jawaban A">
                    @error('pilihan_a')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Pilihan B -->
                <div class="mb-3">
                    <label for="pilihan_b" class="form-label text-custom-secondary text-xs uppercase font-bold">Pilihan B <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-modern @error('pilihan_b') is-invalid @enderror" id="pilihan_b" name="pilihan_b" value="{{ old('pilihan_b') }}" required placeholder="Pilihan jawaban B">
                    @error('pilihan_b')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Pilihan C -->
                <div class="mb-3">
                    <label for="pilihan_c" class="form-label text-custom-secondary text-xs uppercase font-bold">Pilihan C <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-modern @error('pilihan_c') is-invalid @enderror" id="pilihan_c" name="pilihan_c" value="{{ old('pilihan_c') }}" required placeholder="Pilihan jawaban C">
                    @error('pilihan_c')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Pilihan D -->
                <div class="mb-3">
                    <label for="pilihan_d" class="form-label text-custom-secondary text-xs uppercase font-bold">Pilihan D <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-modern @error('pilihan_d') is-invalid @enderror" id="pilihan_d" name="pilihan_d" value="{{ old('pilihan_d') }}" required placeholder="Pilihan jawaban D">
                    @error('pilihan_d')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Kunci Jawaban -->
                <div class="mb-4">
                    <label for="jawaban_benar" class="form-label text-custom-secondary text-xs uppercase font-bold tracking-wider">Kunci Jawaban Benar <span class="text-danger">*</span></label>
                    <select class="form-select form-modern @error('jawaban_benar') is-invalid @enderror" id="jawaban_benar" name="jawaban_benar" required>
                        <option value="">-- Pilih Jawaban Benar --</option>
                        <option value="A" {{ old('jawaban_benar') == 'A' ? 'selected' : '' }}>Pilihan A</option>
                        <option value="B" {{ old('jawaban_benar') == 'B' ? 'selected' : '' }}>Pilihan B</option>
                        <option value="C" {{ old('jawaban_benar') == 'C' ? 'selected' : '' }}>Pilihan C</option>
                        <option value="D" {{ old('jawaban_benar') == 'D' ? 'selected' : '' }}>Pilihan D</option>
                    </select>
                    @error('jawaban_benar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Right Panel: Live Visual Preview (6-cols) -->
        <div class="col-lg-6 mb-4">
            <div class="card-modern h-100 mb-0 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center border-bottom border-custom pb-2 mb-3">
                        <h5 class="font-bold text-custom-primary mb-0">Live Preview</h5>
                        <span class="badge bg-primary px-2.5 py-1.5 text-xs">Tampilan Siswa</span>
                    </div>

                    <!-- Live Question Text -->
                    <div class="p-3 bg-custom-body border border-custom rounded-3 mb-4">
                        <span class="text-xs text-custom-secondary d-block mb-1 font-bold uppercase tracking-wider">Pertanyaan:</span>
                        <div class="fs-5 text-custom-primary" id="previewQuestion" style="white-space: pre-wrap;">Tulis pertanyaan Anda untuk melihat pratinjau di sini...</div>
                    </div>

                    <!-- Live Options Cards -->
                    <div class="d-flex flex-column gap-2" id="previewOptionsContainer">
                        <!-- Option A -->
                        <div class="option-item border border-custom p-3 rounded-3 d-flex align-items-center" id="previewOptCard_A">
                            <div class="option-badge">A</div>
                            <div class="text-custom-primary text-sm" id="previewText_A">-</div>
                            <i class="bi bi-check-circle-fill text-success fs-5 ms-auto d-none" id="previewCheck_A"></i>
                        </div>
                        <!-- Option B -->
                        <div class="option-item border border-custom p-3 rounded-3 d-flex align-items-center" id="previewOptCard_B">
                            <div class="option-badge">B</div>
                            <div class="text-custom-primary text-sm" id="previewText_B">-</div>
                            <i class="bi bi-check-circle-fill text-success fs-5 ms-auto d-none" id="previewCheck_B"></i>
                        </div>
                        <!-- Option C -->
                        <div class="option-item border border-custom p-3 rounded-3 d-flex align-items-center" id="previewOptCard_C">
                            <div class="option-badge">C</div>
                            <div class="text-custom-primary text-sm" id="previewText_C">-</div>
                            <i class="bi bi-check-circle-fill text-success fs-5 ms-auto d-none" id="previewCheck_C"></i>
                        </div>
                        <!-- Option D -->
                        <div class="option-item border border-custom p-3 rounded-3 d-flex align-items-center" id="previewOptCard_D">
                            <div class="option-badge">D</div>
                            <div class="text-custom-primary text-sm" id="previewText_D">-</div>
                            <i class="bi bi-check-circle-fill text-success fs-5 ms-auto d-none" id="previewCheck_D"></i>
                        </div>
                    </div>
                </div>

                <!-- Bottom Form Buttons -->
                <div class="d-flex justify-content-end gap-2 border-top border-custom pt-4 mt-4">
                    <a href="{{ route(Auth::user()->role . '.soal.index') }}" class="btn btn-modern btn-modern-secondary btn-sm">Batal</a>
                    <button type="submit" class="btn btn-modern btn-modern-primary btn-sm"><i class="bi bi-save me-1"></i> Simpan Soal</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputQuestion = document.getElementById('pertanyaan');
        const inputOptA = document.getElementById('pilihan_a');
        const inputOptB = document.getElementById('pilihan_b');
        const inputOptC = document.getElementById('pilihan_c');
        const inputOptD = document.getElementById('pilihan_d');
        const selectCorrect = document.getElementById('jawaban_benar');

        const previewQuestion = document.getElementById('previewQuestion');
        const previewTextA = document.getElementById('previewText_A');
        const previewTextB = document.getElementById('previewText_B');
        const previewTextC = document.getElementById('previewText_C');
        const previewTextD = document.getElementById('previewText_D');

        // Update Question text
        inputQuestion.addEventListener('input', function() {
            previewQuestion.textContent = this.value.trim() || 'Tulis pertanyaan Anda untuk melihat pratinjau di sini...';
        });

        // Update Option texts
        inputOptA.addEventListener('input', function() {
            previewTextA.textContent = this.value.trim() || '-';
        });
        inputOptB.addEventListener('input', function() {
            previewTextB.textContent = this.value.trim() || '-';
        });
        inputOptC.addEventListener('input', function() {
            previewTextC.textContent = this.value.trim() || '-';
        });
        inputOptD.addEventListener('input', function() {
            previewTextD.textContent = this.value.trim() || '-';
        });

        // Update Kunci Highlight
        selectCorrect.addEventListener('change', function() {
            const key = this.value;
            const letters = ['A', 'B', 'C', 'D'];
            
            letters.forEach(letter => {
                const card = document.getElementById(`previewOptCard_${letter}`);
                const check = document.getElementById(`previewCheck_${letter}`);
                
                if (letter === key) {
                    card.classList.add('selected', 'border-success', 'bg-success', 'bg-opacity-10');
                    check.classList.remove('d-none');
                } else {
                    card.classList.remove('selected', 'border-success', 'bg-success', 'bg-opacity-10');
                    check.classList.add('d-none');
                }
            });
        });
    });
</script>
@endsection
