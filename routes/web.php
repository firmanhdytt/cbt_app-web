<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Admin\AdminPenggunaController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Siswa\UjianSiswaController;
use Illuminate\Support\Facades\Route;

// 1. PUBLIC ROUTES
Route::get('/', function () {
    return view('welcome');
});

// Verifikasi e-sertifikat via QR Code (regex parameter agar mendukung karakter slash '/')
Route::get('sertifikat/verify/{nomor_sertifikat}', [LaporanController::class, 'verifySertifikat'])
    ->name('sertifikat.verify')
    ->where('nomor_sertifikat', '.*');

// 2. AUTHENTICATED & VERIFIED BASE REDIRECT
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin') {
        return redirect()->route('admin.dashboard', absolute: false);
    } elseif ($role === 'guru') {
        return redirect()->route('guru.dashboard', absolute: false);
    } else {
        return redirect()->route('siswa.dashboard', absolute: false);
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. PROFILE CRUD (Shared for all logged-in users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 4. ADMIN PANEL ROUTES (Role: admin)
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard', absolute: false);
    });
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    
    // Kelola Pengguna (Siswa, Guru, Admin)
    Route::get('/pengguna/{user}/kartu', [LaporanController::class, 'pdfKartuPeserta'])->name('pengguna.kartu');
    Route::resource('pengguna', AdminPenggunaController::class);

    // Kelola Mata Pelajaran
    Route::resource('mapel', MapelController::class);

    // Kelola Kelas
    Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);

    // Kelola Bank Soal (CRUD + Excel)
    Route::get('/soal/export', [SoalController::class, 'export'])->name('soal.export');
    Route::post('/soal/import', [SoalController::class, 'import'])->name('soal.import');
    Route::resource('soal', SoalController::class);

    // Kelola Jadwal Ujian
    Route::post('/ujian/{ujian}/toggle', [UjianController::class, 'toggleStatus'])->name('ujian.toggle');
    Route::resource('ujian', UjianController::class);

    // Laporan Hasil Ujian & Ekspor
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/siswa', [LaporanController::class, 'laporanSiswa'])->name('laporan.siswa');
    Route::get('/laporan/siswa/{siswa}', [LaporanController::class, 'detailSiswa'])->name('laporan.siswa.detail');
    Route::get('/laporan/export/siswa', [LaporanController::class, 'exportSiswa'])->name('laporan.export.siswa');
    Route::get('/laporan/export/soal', [LaporanController::class, 'exportSoal'])->name('laporan.export.soal');
    Route::get('/laporan/export/hasil', [LaporanController::class, 'exportHasilUjian'])->name('laporan.export.hasil');
    Route::get('/laporan/pdf/hasil/{hasil}', [LaporanController::class, 'pdfHasilUjian'])->name('laporan.pdf.hasil');
    Route::post('/laporan/buka-kunci/{hasil}', [LaporanController::class, 'bukaKunci'])->name('laporan.buka_kunci');
});

// 5. GURU PANEL ROUTES (Role: guru)
Route::middleware(['auth', 'verified', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('guru.dashboard', absolute: false);
    });
    Route::get('/dashboard', [DashboardController::class, 'guru'])->name('dashboard');
    
    // Kelola Bank Soal (CRUD + Excel)
    Route::get('/soal/export', [SoalController::class, 'export'])->name('soal.export');
    Route::post('/soal/import', [SoalController::class, 'import'])->name('soal.import');
    Route::resource('soal', SoalController::class);

    // Kelola Jadwal Ujian
    Route::post('/ujian/{ujian}/toggle', [UjianController::class, 'toggleStatus'])->name('ujian.toggle');
    Route::resource('ujian', UjianController::class);

    // Laporan Hasil Ujian & Ekspor
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/siswa', [LaporanController::class, 'laporanSiswa'])->name('laporan.siswa');
    Route::get('/laporan/siswa/{siswa}', [LaporanController::class, 'detailSiswa'])->name('laporan.siswa.detail');
    Route::get('/laporan/export/soal', [LaporanController::class, 'exportSoal'])->name('laporan.export.soal');
    Route::get('/laporan/export/hasil', [LaporanController::class, 'exportHasilUjian'])->name('laporan.export.hasil');
    Route::get('/laporan/pdf/hasil/{hasil}', [LaporanController::class, 'pdfHasilUjian'])->name('laporan.pdf.hasil');
    Route::post('/laporan/buka-kunci/{hasil}', [LaporanController::class, 'bukaKunci'])->name('laporan.buka_kunci');
});

// 6. SISWA PANEL ROUTES (Role: siswa)
Route::middleware(['auth', 'verified', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('siswa.dashboard', absolute: false);
    });
    Route::get('/dashboard', [DashboardController::class, 'siswa'])->name('dashboard');
    
    // Ujian Tersedia & Pengerjaan
    Route::get('/ujian', [UjianSiswaController::class, 'index'])->name('ujian.index');
    Route::get('/ujian/{ujian}/konfirmasi', [UjianSiswaController::class, 'confirm'])->name('ujian.konfirmasi');
    Route::get('/ujian/{ujian}/confirm', [UjianSiswaController::class, 'confirm'])->name('ujian.confirm');
    Route::get('/ujian/{ujian}/mulai', [UjianSiswaController::class, 'start'])->name('ujian.mulai');
    Route::post('/ujian/{ujian}/simpan-jawaban', [UjianSiswaController::class, 'saveAnswer'])->name('ujian.simpan-jawaban');
    Route::post('/ujian/{ujian}/catat-pelanggaran', [UjianSiswaController::class, 'recordViolation'])->name('ujian.catat-pelanggaran');
    Route::post('/ujian/{ujian}/ajukan-buka-kunci', [UjianSiswaController::class, 'requestUnlock'])->name('ujian.ajukan-buka-kunci');
    Route::post('/ujian/{ujian}/submit', [UjianSiswaController::class, 'submit'])->name('ujian.submit');

    // Riwayat Nilai & E-Sertifikat
    Route::get('/riwayat', [UjianSiswaController::class, 'history'])->name('riwayat');
    Route::get('/sertifikat', [UjianSiswaController::class, 'certificates'])->name('sertifikat.index');
    Route::get('/sertifikat/download/{ujian}', [LaporanController::class, 'downloadSertifikat'])->name('sertifikat.download');
});

require __DIR__.'/auth.php';
