@extends('layouts.siswa')

@section('title', 'Daftar Ujian Tersedia')
@section('page-title', 'Ujian Tersedia untuk Kelas Anda')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Ujian Tersedia</li>
@endsection

@section('content')

{{-- Info kelas siswa --}}
@if(Auth::user()->kelas)
<div class="alert alert-info border-0 d-flex align-items-center gap-2 mb-4 py-2 px-3" style="background:rgba(13,110,253,0.07)">
    <i class="bi bi-people-fill text-primary"></i>
    <span class="small">Menampilkan ujian untuk kelas: <strong>{{ Auth::user()->kelas->nama_kelas }}</strong>
        @if(Auth::user()->kelas->tahun_ajaran)
            <span class="text-muted">({{ Auth::user()->kelas->tahun_ajaran }})</span>
        @endif
    </span>
</div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    @forelse ($ujians as $ujian)
        <div class="col-md-6 col-lg-4">
            <div class="card card-custom h-100 border-0 shadow-sm" style="transition: transform .2s, box-shadow .2s;"
                onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 32px rgba(0,0,0,0.12)'"
                onmouseleave="this.style.transform='';this.style.boxShadow=''">
                {{-- Header strip --}}
                <div class="rounded-top" style="height:5px;background:linear-gradient(90deg,#4f46e5,#7c3aed)"></div>
                <div class="card-body d-flex flex-column p-4">
                    {{-- Badges --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-dark bg-opacity-75 font-monospace px-2 py-1">
                            {{ $ujian->mapel->kode_mapel ?? '-' }}
                        </span>
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-clock me-1 text-primary"></i>{{ $ujian->durasi_menit }} Menit
                        </span>
                    </div>

                    {{-- Title --}}
                    <h5 class="fw-bold text-dark mb-1">{{ $ujian->judul }}</h5>
                    <p class="text-muted small mb-1">{{ $ujian->mapel->nama_mapel ?? '-' }}</p>

                    @if($ujian->kelas)
                    <span class="badge bg-primary bg-opacity-10 text-primary mb-3" style="width:fit-content">
                        <i class="bi bi-people me-1"></i>{{ $ujian->kelas->nama_kelas }}
                    </span>
                    @endif

                    @if ($ujian->deskripsi)
                        <p class="text-muted small mb-3" style="overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                            {{ $ujian->deskripsi }}
                        </p>
                    @endif

                    {{-- Schedule --}}
                    <div class="p-3 rounded-3 mb-3 mt-auto" style="background:#f8f9fc">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted" style="font-size:12px"><i class="bi bi-calendar-event me-1 text-success"></i>Mulai</span>
                            <span class="fw-semibold small text-dark">{{ $ujian->tanggal_mulai->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted" style="font-size:12px"><i class="bi bi-calendar-x me-1 text-danger"></i>Berakhir</span>
                            <span class="fw-semibold small text-danger">{{ $ujian->tanggal_selesai->format('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    {{-- CTA --}}
                    <a href="{{ route('siswa.ujian.konfirmasi', $ujian->id) }}"
                        class="btn btn-primary btn-sm w-100 py-2 fw-semibold">
                        Ikuti Ujian <i class="bi bi-arrow-right-short ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card card-custom text-center py-5 border-0 shadow-sm">
                <div class="card-body">
                    <i class="bi bi-clipboard2-x display-3 text-muted d-block mb-3 opacity-50"></i>
                    <h5 class="fw-bold text-dark">Tidak Ada Ujian Aktif</h5>
                    <p class="text-muted small">
                        @if(Auth::user()->kelas)
                            Saat ini tidak ada jadwal ujian aktif untuk kelas <strong>{{ Auth::user()->kelas->nama_kelas }}</strong>,
                            atau seluruh ujian yang tersedia telah Anda selesaikan.
                        @else
                            Anda belum terdaftar di kelas manapun. Hubungi administrator untuk mendaftarkan Anda ke kelas.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endforelse
</div>
@endsection
