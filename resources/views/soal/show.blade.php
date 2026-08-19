@extends('layouts.' . Auth::user()->role)

@section('title', 'Detail Soal')
@section('page-title', 'Detail Soal')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role . '.soal.index') }}" class="text-decoration-none">Bank Soal</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
@endsection

@section('page-actions')
    <a href="{{ route(Auth::user()->role . '.soal.index') }}" class="btn btn-modern btn-modern-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card-modern">
            <div class="d-flex justify-content-between align-items-center border-bottom border-custom pb-3 mb-4">
                <h5 class="font-bold text-custom-primary mb-0">Informasi Butir Soal</h5>
                <span class="badge bg-primary px-3 py-2 text-xs font-semibold">Mapel: {{ $soal->mapel->nama_mapel ?? '-' }} ({{ $soal->mapel->kode_mapel ?? '-' }})</span>
            </div>

            <!-- Pertanyaan -->
            <div class="mb-4 p-4 bg-custom-body border border-custom rounded-3">
                <span class="text-xs text-custom-secondary d-block mb-1 font-bold uppercase tracking-wider">Pertanyaan:</span>
                <p class="fs-5 mb-0 text-custom-primary" style="white-space: pre-wrap;">{{ $soal->pertanyaan }}</p>
            </div>

            <!-- Pilihan Jawaban -->
            <div class="mb-4">
                <span class="text-xs text-custom-secondary d-block mb-3 font-bold uppercase tracking-wider">Pilihan Jawaban:</span>
                
                <div class="row g-3">
                    <!-- Pilihan A -->
                    <div class="col-md-6">
                        <div class="p-3 border border-custom rounded-3 d-flex align-items-center {{ $soal->jawaban_benar == 'A' ? 'border-success bg-success bg-opacity-10' : 'bg-custom-card' }}">
                            <span class="badge {{ $soal->jawaban_benar == 'A' ? 'bg-success' : 'bg-secondary' }} px-3 py-2 me-3">A</span>
                            <div class="text-custom-primary flex-grow-1" style="white-space: pre-wrap;">{{ $soal->pilihan_a }}</div>
                            @if ($soal->jawaban_benar == 'A')
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            @endif
                        </div>
                    </div>

                    <!-- Pilihan B -->
                    <div class="col-md-6">
                        <div class="p-3 border border-custom rounded-3 d-flex align-items-center {{ $soal->jawaban_benar == 'B' ? 'border-success bg-success bg-opacity-10' : 'bg-custom-card' }}">
                            <span class="badge {{ $soal->jawaban_benar == 'B' ? 'bg-success' : 'bg-secondary' }} px-3 py-2 me-3">B</span>
                            <div class="text-custom-primary flex-grow-1" style="white-space: pre-wrap;">{{ $soal->pilihan_b }}</div>
                            @if ($soal->jawaban_benar == 'B')
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            @endif
                        </div>
                    </div>

                    <!-- Pilihan C -->
                    <div class="col-md-6">
                        <div class="p-3 border border-custom rounded-3 d-flex align-items-center {{ $soal->jawaban_benar == 'C' ? 'border-success bg-success bg-opacity-10' : 'bg-custom-card' }}">
                            <span class="badge {{ $soal->jawaban_benar == 'C' ? 'bg-success' : 'bg-secondary' }} px-3 py-2 me-3">C</span>
                            <div class="text-custom-primary flex-grow-1" style="white-space: pre-wrap;">{{ $soal->pilihan_c }}</div>
                            @if ($soal->jawaban_benar == 'C')
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            @endif
                        </div>
                    </div>

                    <!-- Pilihan D -->
                    <div class="col-md-6">
                        <div class="p-3 border border-custom rounded-3 d-flex align-items-center {{ $soal->jawaban_benar == 'D' ? 'border-success bg-success bg-opacity-10' : 'bg-custom-card' }}">
                            <span class="badge {{ $soal->jawaban_benar == 'D' ? 'bg-success' : 'bg-secondary' }} px-3 py-2 me-3">D</span>
                            <div class="text-custom-primary flex-grow-1" style="white-space: pre-wrap;">{{ $soal->pilihan_d }}</div>
                            @if ($soal->jawaban_benar == 'D')
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metadata/Actions -->
            <div class="d-flex justify-content-between align-items-center border-top border-custom pt-4 mt-4 text-xs text-custom-secondary">
                <div>
                    <span>Dibuat pada: {{ $soal->created_at->format('d M Y, H:i') }}</span>
                    @if ($soal->updated_at != $soal->created_at)
                        <span class="ms-3">Diperbarui: {{ $soal->updated_at->format('d M Y, H:i') }}</span>
                    @endif
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route(Auth::user()->role . '.soal.edit', $soal->id) }}" class="btn btn-modern btn-modern-primary btn-sm px-3">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
