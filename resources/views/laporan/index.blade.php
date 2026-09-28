@extends('layouts.' . Auth::user()->role)

@section('title', 'Laporan Hasil Ujian')
@section('page-title', 'Laporan Hasil Ujian & Ekspor Data')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Laporan</li>
@endsection

@section('page-actions')
    <div class="d-flex gap-2 flex-wrap">
        @if (Auth::user()->role === 'admin')
            <a href="{{ route('admin.laporan.export.siswa') }}" class="btn btn-outline-success btn-sm">
                <i class="bi bi-people-fill me-1"></i> Ekspor Siswa
            </a>
        @endif
        <a href="{{ route(Auth::user()->role . '.laporan.export.soal') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-question-circle-fill me-1"></i> Ekspor Soal
        </a>
        <a href="{{ route(Auth::user()->role . '.laporan.export.hasil') }}" class="btn btn-success btn-sm">
            <i class="bi bi-file-earmark-spreadsheet-fill me-1"></i> Ekspor Hasil (Excel)
        </a>
    </div>
@endsection

@section('content')

{{-- Summary Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card card-custom border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:44px;height:44px;flex-shrink:0">
                    <i class="bi bi-journal-text text-primary fs-5"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold">{{ $stats['total'] }}</div>
                    <div class="text-muted small">Total Hasil Ujian</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-custom border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width:44px;height:44px;flex-shrink:0">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-success">{{ $stats['lulus'] }}</div>
                    <div class="text-muted small">Peserta Lulus (≥70)</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-custom border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="width:44px;height:44px;flex-shrink:0">
                    <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-danger">{{ $stats['remidi'] }}</div>
                    <div class="text-muted small">Remidi (&lt;70)</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-custom border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="width:44px;height:44px;flex-shrink:0">
                    <i class="bi bi-lock-fill text-warning fs-5"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-dark">{{ $stats['terkunci'] }}</div>
                    <div class="text-muted small">Status Terkunci</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-custom mb-4 border-0 shadow-sm">
    <div class="card-body p-3">
        <form method="GET" action="{{ route(Auth::user()->role . '.laporan.index') }}" class="row g-2 align-items-center">
            <!-- Filter Kelas -->
            <div class="col-12 col-md-3">
                <label class="form-label text-xs fw-semibold text-muted mb-1">Filter Kategori Kelas:</label>
                <select name="kelas_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($kelass as $k)
                        <option value="{{ $k->id }}" {{ (string)$kelasId === (string)$k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Ujian -->
            <div class="col-12 col-md-3">
                <label class="form-label text-xs fw-semibold text-muted mb-1">Filter Judul Ujian:</label>
                <select name="ujian_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Ujian --</option>
                    @foreach($ujians as $u)
                        <option value="{{ $u->id }}" {{ (string)$ujianId === (string)$u->id ? 'selected' : '' }}>
                            {{ $u->judul }} ({{ $u->mapel->kode_mapel ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status -->
            <div class="col-6 col-md-2">
                <label class="form-label text-xs fw-semibold text-muted mb-1">Filter Status:</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua --</option>
                    <option value="lulus" {{ $status === 'lulus' ? 'selected' : '' }}>Lulus</option>
                    <option value="remidi" {{ $status === 'remidi' ? 'selected' : '' }}>Remidi</option>
                    <option value="terkunci" {{ $status === 'terkunci' ? 'selected' : '' }}>Terkunci</option>
                </select>
            </div>

            <!-- Search Nama/NIS -->
            <div class="col-6 col-md-3">
                <label class="form-label text-xs fw-semibold text-muted mb-1">Cari Siswa / NIS:</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Nama / NIS..." value="{{ $search }}">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                </div>
            </div>

            <!-- Reset Filter -->
            @if($kelasId || $ujianId || $status || $search)
            <div class="col-12 text-end mt-2">
                <a href="{{ route(Auth::user()->role . '.laporan.index') }}" class="btn btn-link btn-sm text-decoration-none text-danger p-0">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Semua Filter
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-table me-2 text-primary"></i>Riwayat Hasil Pengerjaan Ujian</h6>
        <span class="badge bg-secondary bg-opacity-10 text-secondary">Total: {{ $hasilUjians->total() }} Data</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap text-md-wrap">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Nama Siswa</th>
                        <th>NIS / Kelas</th>
                        <th>Ujian</th>
                        <th>Mode Akses</th>
                        <th>Waktu Selesai</th>
                        <th class="text-center">B / S</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-center">Pelanggaran</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hasilUjians as $index => $hasil)
                        <tr>
                            <td class="ps-4 text-muted">{{ $hasilUjians->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-semibold">{{ $hasil->user->name ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="small">NIS: {{ $hasil->user->nis ?? '-' }}</div>
                                @if($hasil->user && $hasil->user->kelas)
                                    <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:10px">{{ $hasil->user->kelas->nama_kelas }}</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-dark bg-opacity-75 font-monospace mb-1">{{ $hasil->ujian->mapel->kode_mapel ?? '-' }}</span>
                                <div class="small fw-medium">{{ $hasil->ujian->judul ?? '-' }}</div>
                                @if($hasil->ujian && $hasil->ujian->kelas)
                                    <div style="font-size:10px" class="text-muted">Kelas: {{ $hasil->ujian->kelas->nama_kelas }}</div>
                                @endif
                            </td>
                            <td>
                                @if($hasil->is_exambro)
                                    <span class="badge bg-indigo text-white px-2 py-1" style="background-color:#4F46E5;" title="Dikerjakan menggunakan Aplikasi CBT Exambro Mobile resmi">
                                        <i class="bi bi-phone-vibrate me-1"></i> Exambro Mobile
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1" title="Dikerjakan menggunakan Web Browser Standard">
                                        <i class="bi bi-laptop me-1"></i> Web Browser
                                    </span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $hasil->waktu_selesai ? $hasil->waktu_selesai->format('d M Y') : '-' }}<br><span style="font-size:10px">{{ $hasil->waktu_selesai ? $hasil->waktu_selesai->format('H:i') : '' }}</span></td>
                            <td class="text-center">
                                <span class="text-success fw-semibold small">{{ $hasil->jumlah_benar }}B</span>
                                <span class="text-muted">/</span>
                                <span class="text-danger fw-semibold small">{{ $hasil->jumlah_salah }}S</span>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold fs-6 {{ $hasil->nilai >= 70 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($hasil->nilai, 0) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($hasil->status_pengerjaan === 'terkunci')
                                    <span class="badge bg-danger text-white px-2 py-1 mb-1 d-inline-block" title="{{ $hasil->alasan_buka_kunci ? 'Alasan Ajuan: '.$hasil->alasan_buka_kunci : 'Terkunci 2x Pelanggaran' }}">
                                        <i class="bi bi-lock-fill me-1"></i> Terkunci ({{ $hasil->jumlah_pelanggaran }}x)
                                    </span>
                                    @if($hasil->alasan_buka_kunci)
                                        <div class="text-danger font-semibold" style="font-size:10px" title="{{ $hasil->alasan_buka_kunci }}">
                                            "{{ Str::limit($hasil->alasan_buka_kunci, 25) }}"
                                        </div>
                                    @endif
                                @elseif($hasil->jumlah_pelanggaran > 0)
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1">
                                        {{ $hasil->jumlah_pelanggaran }}x Teguran
                                    </span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">
                                        0 Pelanggaran
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hasil->nilai >= 70)
                                    <span class="badge bg-success px-2 py-1"><i class="bi bi-check2 me-1"></i>Lulus</span>
                                @else
                                    <span class="badge bg-danger px-2 py-1"><i class="bi bi-x me-1"></i>Remidi</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-inline-flex gap-1">
                                    @if($hasil->status_pengerjaan === 'terkunci')
                                        <form action="{{ route(Auth::user()->role . '.laporan.buka_kunci', $hasil->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui dan membuka kunci ujian siswa ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm py-1 px-2 text-xs font-semibold" title="Buka Kunci & Mengulang Ujian">
                                                <i class="bi bi-unlock-fill me-1"></i> Buka Kunci
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route(Auth::user()->role . '.laporan.pdf.hasil', $hasil->id) }}"
                                        class="btn btn-outline-danger btn-sm py-1 px-2 text-xs" title="Cetak PDF Rapor">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x display-4 d-block mb-2 opacity-25"></i>
                                Belum ada riwayat hasil ujian yang masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($hasilUjians->hasPages())
    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
        <small class="text-muted">Menampilkan {{ $hasilUjians->firstItem() }}–{{ $hasilUjians->lastItem() }} dari {{ $hasilUjians->total() }} hasil</small>
        {{ $hasilUjians->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
