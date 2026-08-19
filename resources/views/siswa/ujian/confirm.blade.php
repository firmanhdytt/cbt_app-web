@extends('layouts.siswa')

@section('title', 'Konfirmasi Ujian')
@section('page-title', 'Konfirmasi Pengerjaan Ujian')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('siswa.ujian.index') }}" class="text-decoration-none">Ujian Tersedia</a></li>
    <li class="breadcrumb-item active" aria-current="page">Konfirmasi</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title mb-0 font-bold text-center">Lembar Konfirmasi Peserta</h5>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="alert alert-warning mb-4 card-custom border-warning-subtle" role="alert">
                    <div class="d-flex">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-warning"></i>
                        <div>
                            <strong class="d-block mb-1">PENTING: Mohon Perhatikan Aturan Ujian!</strong>
                            <p class="mb-0 text-sm">Setelah Anda menekan tombol "Mulai Ujian" di bawah, waktu hitung mundur ujian akan segera dimulai. Waktu akan terus berjalan meskipun Anda menutup atau me-refresh peramban (browser) Anda.</p>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered mb-4">
                    <tbody>
                        <tr>
                            <td class="bg-light font-medium" style="width: 30%">Nama Peserta</td>
                            <td>{{ Auth::user()->name }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light font-medium">NIS</td>
                            <td>{{ Auth::user()->nis ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light font-medium">Kelas Peserta</td>
                            <td>{{ Auth::user()->kelas->nama_kelas ?? Auth::user()->class_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light font-medium">Mata Pelajaran</td>
                            <td><strong>{{ $ujian->mapel->nama_mapel ?? '-' }} ({{ $ujian->mapel->kode_mapel ?? '-' }})</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-light font-medium">Judul Ujian</td>
                            <td><strong>{{ $ujian->judul }}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-light font-medium">Durasi Pengerjaan</td>
                            <td><span class="badge bg-primary px-2.5 py-1.5"><i class="bi bi-clock me-1 text-white"></i>{{ $ujian->durasi_menit }} Menit</span></td>
                        </tr>
                        @if ($ujian->deskripsi)
                            <tr>
                                <td class="bg-light font-medium">Petunjuk / Deskripsi</td>
                                <td class="text-slate-600 text-sm" style="white-space: pre-wrap;">{{ $ujian->deskripsi }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <div class="d-flex justify-content-between border-top pt-4">
                    <a href="{{ route('siswa.ujian.index') }}" class="btn btn-secondary btn-sm px-4">Batal</a>
                    
                    <a href="{{ route('siswa.ujian.mulai', $ujian->id) }}" class="btn btn-success btn-sm px-5 font-semibold py-2">
                        <i class="bi bi-play-circle me-1"></i> Mulai Ujian Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
