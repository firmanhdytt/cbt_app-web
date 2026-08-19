@extends('layouts.' . Auth::user()->role)

@section('title', 'Bank Soal')
@section('page-title', 'Kelola Bank Soal')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Bank Soal</li>
@endsection

@section('page-actions')
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
        </button>
        <a href="{{ route(Auth::user()->role . '.soal.export') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-download me-1"></i> Export Excel
        </a>
        <a href="{{ route(Auth::user()->role . '.soal.create') }}" class="btn btn-indigo btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Soal
        </a>
    </div>
@endsection

@section('content')

{{-- Filter Bar --}}
<div class="card card-custom mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route(Auth::user()->role . '.soal.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label form-label-sm mb-1 font-medium text-muted">Filter Mata Pelajaran</label>
                <select name="mapel_id" class="form-select form-select-sm">
                    <option value="">-- Semua Mata Pelajaran --</option>
                    @foreach($mapels as $mapel)
                        <option value="{{ $mapel->id }}" {{ request('mapel_id') == $mapel->id ? 'selected' : '' }}>
                            [{{ $mapel->kode_mapel }}] {{ $mapel->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label form-label-sm mb-1 font-medium text-muted">Filter Kelas</label>
                <select name="kelas_id" class="form-select form-select-sm">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($kelass as $kelas)
                        <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @if(request('mapel_id') || request('kelas_id'))
                    <a href="{{ route(Auth::user()->role . '.soal.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width:5%">No</th>
                        <th style="width:15%">Mata Pelajaran</th>
                        <th style="width:12%">Kelas</th>
                        <th style="width:38%">Pertanyaan</th>
                        <th style="width:10%">Kunci</th>
                        <th style="width:10%">Pembuat</th>
                        <th style="width:10%" class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($soals as $index => $soal)
                        <tr>
                            <td class="ps-4">{{ $soals->firstItem() + $index }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $soal->mapel->kode_mapel ?? '-' }}</span>
                                <div class="text-muted small mt-1">{{ $soal->mapel->nama_mapel ?? '-' }}</div>
                            </td>
                            <td>
                                @if($soal->kelas)
                                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ $soal->kelas->nama_kelas }}</span>
                                @else
                                    <span class="text-muted small">Semua Kelas</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 360px;" title="{{ strip_tags($soal->pertanyaan) }}">
                                    {{ strip_tags($soal->pertanyaan) }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-success fw-bold">Pilihan {{ $soal->jawaban_benar }}</span>
                            </td>
                            <td>
                                <span class="text-sm">{{ $soal->creator->name ?? '-' }}</span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route(Auth::user()->role . '.soal.show', $soal->id) }}" class="btn btn-outline-info btn-sm" title="Detail">
                                        <i class="bi bi-info-circle"></i>
                                    </a>
                                    <a href="{{ route(Auth::user()->role . '.soal.edit', $soal->id) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route(Auth::user()->role . '.soal.destroy', $soal->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x fs-2 d-block mb-2 opacity-50"></i>
                                @if(request('mapel_id') || request('kelas_id'))
                                    Tidak ada soal yang sesuai dengan filter yang dipilih.
                                @else
                                    Belum ada data soal di bank soal.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0">
        {{ $soals->links('pagination::bootstrap-5') }}
    </div>
</div>

<!-- Import Excel Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark" id="importModalLabel">
                    <i class="bi bi-file-earmark-excel text-success me-2"></i>Import Soal dari Excel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route(Auth::user()->role . '.soal.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    
                    {{-- Default Mapel Option --}}
                    <div class="mb-3">
                        <label for="import_mapel_id" class="form-label font-medium small text-muted">Mata Pelajaran Tujuan (Opsional)</label>
                        <select name="mapel_id" id="import_mapel_id" class="form-select form-select-sm">
                            <option value="">-- Gunakan Kode Mapel di Excel / Buat Otomatis --</option>
                            @foreach($mapels as $mapel)
                                <option value="{{ $mapel->id }}">[{{ $mapel->kode_mapel }}] {{ $mapel->nama_mapel }}</option>
                            @endforeach
                        </select>
                        <div class="form-text" style="font-size:11px;">Jika dipilih, seluruh soal yang diimpor akan dimasukkan ke mata pelajaran ini (kecuali jika di Excel ada kolom <code>kode_mapel</code>).</div>
                    </div>

                    {{-- Default Kelas Option --}}
                    <div class="mb-3">
                        <label for="import_kelas_id" class="form-label font-medium small text-muted">Target Kelas Siswa (Opsional)</label>
                        <select name="kelas_id" id="import_kelas_id" class="form-select form-select-sm">
                            <option value="">-- Semua Kelas (Umum) --</option>
                            @foreach($kelass as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- File Input --}}
                    <div class="mb-3">
                        <label for="file" class="form-label font-medium">Pilih File Excel (.xlsx, .xls, .csv) <span class="text-danger">*</span></label>
                        <input class="form-control" type="file" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                    </div>

                    {{-- Help Banner --}}
                    <div class="p-3 bg-light rounded-3 border text-xs text-muted">
                        <div class="fw-bold text-dark mb-1">Format Kolom Excel yang Didukung:</div>
                        <code class="d-block bg-white p-2 rounded border text-dark font-monospace mb-2" style="font-size:11px;">
                            pertanyaan | pilihan_a | pilihan_b | pilihan_c | pilihan_d | jawaban_benar
                        </code>
                        <ul class="mb-0 ps-3" style="font-size:11px;">
                            <li>Kolom <code>jawaban_benar</code> berisi huruf: <strong>A, B, C, atau D</strong>.</li>
                            <li>Dapat ditambahkan kolom opsional: <code>kode_mapel</code>, <code>mata_pelajaran</code>, <code>kelas</code>.</li>
                        </ul>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm px-4 fw-semibold">
                        <i class="bi bi-upload me-1"></i> Mulai Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
