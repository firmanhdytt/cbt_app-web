@extends('layouts.siswa')

@section('title', 'E-Sertifikat')
@section('page-title', 'E-Sertifikat Kelulusan')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">E-Sertifikat</li>
@endsection

@section('content')
<div class="row">
    @forelse ($sertifikats as $sertifikat)
        <div class="col-md-6 mb-4">
            <div class="card-modern h-100 mb-0 d-flex flex-column justify-content-between p-4">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 bg-success bg-opacity-10 text-success rounded-3 me-3">
                            <i class="bi bi-patch-check-fill fs-2"></i>
                        </div>
                        <div>
                            <span class="text-xs text-custom-secondary d-block uppercase font-bold tracking-wider mb-0.5">Nomor Sertifikat:</span>
                            <strong class="text-custom-primary font-mono text-sm">{{ $sertifikat->nomor_sertifikat }}</strong>
                        </div>
                    </div>
                    
                    <h5 class="font-bold text-custom-primary mb-2">{{ $sertifikat->ujian->judul }}</h5>
                    <p class="text-xs text-custom-secondary mb-3">Mata Pelajaran: {{ $sertifikat->ujian->mapel->nama_mapel ?? '-' }}</p>
                    
                    <div class="p-3 bg-custom-body border border-custom rounded-3 text-xs mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-custom-secondary">Nama Peserta:</span>
                            <span class="font-bold text-custom-primary">{{ Auth::user()->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-custom-secondary">Skor Kelulusan:</span>
                            <span class="font-bold text-success">{{ $sertifikat->ujian->hasilUjians()->where('user_id', Auth::id())->first()->nilai ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-custom-secondary">Tanggal Terbit:</span>
                            <span class="font-bold text-custom-primary">{{ $sertifikat->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('siswa.sertifikat.download', $sertifikat->ujian_id) }}" class="btn btn-modern btn-modern-primary w-100 py-2.5 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-download"></i> Unduh Sertifikat (PDF)
                </a>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card-modern text-center py-5">
                <i class="bi bi-patch-exclamation-fill fs-1 text-custom-secondary d-block mb-3"></i>
                <h5 class="font-bold text-custom-primary mb-2">Belum Ada Sertifikat Kelulusan</h5>
                <p class="text-sm text-custom-secondary max-w-md mx-auto mb-0">Sertifikat kelulusan otomatis diterbitkan setelah Anda menyelesaikan ujian dengan nilai kelulusan minimal 70.00.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection
