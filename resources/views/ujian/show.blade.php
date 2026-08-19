@extends('layouts.' . Auth::user()->role)

@section('title', 'Detail Ujian: ' . $ujian->judul)
@section('page-title', 'Detail Jadwal & Soal Ujian')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role . '.ujian.index') }}" class="text-decoration-none">Ujian</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
@endsection

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route(Auth::user()->role . '.ujian.edit', $ujian->id) }}" class="btn btn-warning btn-sm">
            <i class="bi bi-pencil me-1"></i> Edit Ujian
        </a>
        <a href="{{ route(Auth::user()->role . '.ujian.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="row g-4">
    {{-- Left Column: Info Ringkas --}}
    <div class="col-lg-4">
        <div class="card card-custom shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-info-circle me-2 text-primary"></i>Ringkasan Ujian</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-start px-4 py-3">
                        <span class="text-muted small">Judul Ujian</span>
                        <strong class="text-dark text-end" style="max-width:60%">{{ $ujian->judul }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                        <span class="text-muted small">Mata Pelajaran</span>
                        <span class="badge bg-dark bg-opacity-75 font-monospace">{{ $ujian->mapel->kode_mapel ?? '-' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                        <span class="text-muted small">Kelas Peserta</span>
                        @if($ujian->kelas)
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-people me-1"></i>{{ $ujian->kelas->nama_kelas }}
                            </span>
                        @else
                            <span class="text-muted small">Semua Kelas</span>
                        @endif
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                        <span class="text-muted small">Durasi</span>
                        <span class="badge bg-light text-dark border"><i class="bi bi-clock me-1 text-primary"></i>{{ $ujian->durasi_menit }} Menit</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                        <span class="text-muted small">Jumlah Soal</span>
                        <span class="badge bg-info text-white">{{ $ujian->soals->count() }} Butir</span>
                    </li>
                    <li class="list-group-item px-4 py-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small"><i class="bi bi-calendar-check me-1 text-success"></i>Mulai</span>
                            <strong class="text-success small">{{ $ujian->tanggal_mulai->format('d M Y, H:i') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small"><i class="bi bi-calendar-x me-1 text-danger"></i>Selesai</span>
                            <strong class="text-danger small">{{ $ujian->tanggal_selesai->format('d M Y, H:i') }}</strong>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                        <span class="text-muted small">Status</span>
                        @if($ujian->status)
                            <span class="badge bg-success px-2 py-1"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                        @else
                            <span class="badge bg-secondary px-2 py-1"><i class="bi bi-pause-circle me-1"></i>Nonaktif</span>
                        @endif
                    </li>
                </ul>

                @if ($ujian->deskripsi)
                <div class="px-4 py-3">
                    <div class="p-3 bg-light rounded-3 border">
                        <span class="text-xs text-muted d-block mb-1 text-uppercase fw-bold">Petunjuk / Deskripsi:</span>
                        <p class="mb-0 text-dark small" style="white-space: pre-wrap;">{{ $ujian->deskripsi }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right Column: Daftar Soal --}}
    <div class="col-lg-8">
        <div class="card card-custom shadow-sm border-0">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-list-ol me-2 text-primary"></i>Daftar Soal Terdaftar</h5>
                <span class="badge bg-info text-white px-2 py-1">{{ $ujian->soals->count() }} Soal</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush" style="max-height: 520px; overflow-y: auto;">
                    @forelse ($ujian->soals as $index => $soal)
                        <div class="list-group-item p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-secondary fw-semibold">Soal {{ $index + 1 }}</span>
                                <span class="badge bg-success"><i class="bi bi-key me-1"></i>Kunci: {{ $soal->jawaban_benar }}</span>
                            </div>
                            <p class="mb-3 fw-medium" style="white-space: pre-wrap; font-size:0.9rem;">{{ $soal->pertanyaan }}</p>

                            <div class="row g-2">
                                @foreach(['a' => $soal->pilihan_a, 'b' => $soal->pilihan_b, 'c' => $soal->pilihan_c, 'd' => $soal->pilihan_d] as $key => $val)
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start gap-2 p-2 rounded {{ strtolower($soal->jawaban_benar) === $key ? 'bg-success bg-opacity-10 border border-success' : 'bg-light' }}">
                                        <span class="badge {{ strtolower($soal->jawaban_benar) === $key ? 'bg-success' : 'bg-secondary' }} flex-shrink-0">{{ strtoupper($key) }}</span>
                                        <span class="small">{{ $val }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-exclamation-triangle display-4 d-block mb-2 opacity-25"></i>
                            <p class="mb-0">Belum ada soal yang dipilih untuk ujian ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
