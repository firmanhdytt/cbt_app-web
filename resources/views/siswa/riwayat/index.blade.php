@extends('layouts.siswa')

@section('title', 'Riwayat Nilai Ujian')
@section('page-title', 'Riwayat Nilai & Hasil Ujian')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Riwayat & Nilai</li>
@endsection

@section('content')

{{-- Summary Stats --}}
@if($hasilUjians->count() > 0)
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card card-custom border-0">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:42px;height:42px;flex-shrink:0">
                    <i class="bi bi-journal-check text-primary"></i>
                </div>
                <div>
                    <div class="fs-5 fw-bold">{{ $hasilUjians->count() }}</div>
                    <div class="text-muted" style="font-size:12px">Ujian Dikerjakan</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-custom border-0">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width:42px;height:42px;flex-shrink:0">
                    <i class="bi bi-check-circle-fill text-success"></i>
                </div>
                <div>
                    <div class="fs-5 fw-bold text-success">{{ $hasilUjians->where('nilai', '>=', 70)->count() }}</div>
                    <div class="text-muted" style="font-size:12px">Lulus</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-custom border-0">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="width:42px;height:42px;flex-shrink:0">
                    <i class="bi bi-x-circle-fill text-danger"></i>
                </div>
                <div>
                    <div class="fs-5 fw-bold text-danger">{{ $hasilUjians->where('nilai', '<', 70)->count() }}</div>
                    <div class="text-muted" style="font-size:12px">Perlu Remidi</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-custom border-0">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="width:42px;height:42px;flex-shrink:0">
                    <i class="bi bi-star-fill text-warning"></i>
                </div>
                <div>
                    <div class="fs-5 fw-bold">{{ number_format($hasilUjians->avg('nilai'), 1) }}</div>
                    <div class="text-muted" style="font-size:12px">Rata-rata Nilai</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="card card-custom">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="mb-0 fw-bold text-muted"><i class="bi bi-clock-history me-2"></i>Semua Riwayat Ujian Anda</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width:5%">No</th>
                        <th style="width:18%">Mata Pelajaran</th>
                        <th style="width:25%">Judul Ujian</th>
                        <th style="width:15%">Waktu Selesai</th>
                        <th style="width:12%" class="text-center">Analisis</th>
                        <th style="width:12%" class="text-center">Nilai</th>
                        <th style="width:13%" class="text-center pe-4">Sertifikat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hasilUjians as $index => $hasil)
                        <tr>
                            <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                            <td>
                                <span class="badge bg-dark bg-opacity-75 font-monospace mb-1">{{ $hasil->ujian->mapel->kode_mapel ?? '-' }}</span>
                                <div class="text-muted small">{{ $hasil->ujian->mapel->nama_mapel ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark small">{{ $hasil->ujian->judul }}</div>
                                @if($hasil->ujian && $hasil->ujian->kelas)
                                    <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:10px">
                                        <i class="bi bi-people me-1"></i>{{ $hasil->ujian->kelas->nama_kelas }}
                                    </span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                <i class="bi bi-calendar3 me-1"></i>{{ $hasil->waktu_selesai->format('d M Y') }}<br>
                                <span style="font-size:11px">{{ $hasil->waktu_selesai->format('H:i') }} WIB</span>
                            </td>
                            <td class="text-center">
                                <span class="text-success fw-semibold small d-block"><i class="bi bi-check-circle me-1"></i>{{ $hasil->jumlah_benar }} Benar</span>
                                <span class="text-danger fw-semibold small d-block"><i class="bi bi-x-circle me-1"></i>{{ $hasil->jumlah_salah }} Salah</span>
                            </td>
                            <td class="text-center">
                                <div class="fs-5 fw-bold {{ $hasil->nilai >= 70 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($hasil->nilai, 0) }}
                                </div>
                                @if($hasil->nilai >= 70)
                                    <span class="badge bg-success bg-opacity-10 text-success" style="font-size:10px">Lulus</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger" style="font-size:10px">Remidi</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                @if ($hasil->nilai >= 70.00)
                                    <a href="{{ route('siswa.sertifikat.download', $hasil->ujian_id) }}"
                                        class="btn btn-outline-primary btn-sm px-3 py-1">
                                        <i class="bi bi-file-earmark-pdf me-1"></i>Unduh
                                    </a>
                                @else
                                    <span class="badge bg-secondary px-2 py-1 text-white">
                                        <i class="bi bi-lock-fill me-1"></i>Terkunci
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x display-4 d-block mb-2 opacity-25"></i>
                                Belum ada riwayat pengerjaan ujian yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
