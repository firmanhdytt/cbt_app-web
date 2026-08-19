<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Soal;
use App\Models\Ujian;
use App\Models\HasilUjian;
use App\Models\Sertifikat;
use App\Exports\SiswaExport;
use App\Exports\HasilUjianExport;
use App\Exports\SoalExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman pengelolaan laporan dan ekspor data (untuk Admin/Guru).
     */
    /**
     * Tampilkan halaman pengelolaan laporan dan ekspor data (untuk Admin/Guru).
     */
    public function index(Request $request)
    {
        $kelass = \App\Models\Kelas::orderBy('nama_kelas')->get();
        $ujians = Ujian::orderBy('judul')->get();

        $kelasId = $request->get('kelas_id');
        $ujianId = $request->get('ujian_id');
        $status  = $request->get('status');
        $search  = $request->get('search');

        $query = HasilUjian::with(['user.kelas', 'ujian.mapel', 'ujian.kelas'])->latest();

        if ($kelasId) {
            $query->whereHas('user', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        if ($ujianId) {
            $query->where('ujian_id', $ujianId);
        }

        if ($status === 'lulus') {
            $query->where('nilai', '>=', 70)->where(function ($q) {
                $q->whereNull('status_pengerjaan')->orWhere('status_pengerjaan', '!=', 'terkunci');
            });
        } elseif ($status === 'remidi') {
            $query->where('nilai', '<', 70)->where(function ($q) {
                $q->whereNull('status_pengerjaan')->orWhere('status_pengerjaan', '!=', 'terkunci');
            });
        } elseif ($status === 'terkunci') {
            $query->where('status_pengerjaan', 'terkunci');
        }

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $hasilUjians = $query->paginate(15)->withQueryString();

        // Summary Stats
        $stats = [
            'total'    => HasilUjian::count(),
            'lulus'    => HasilUjian::where('nilai', '>=', 70)->where(function($q){ $q->whereNull('status_pengerjaan')->orWhere('status_pengerjaan', '!=', 'terkunci'); })->count(),
            'remidi'   => HasilUjian::where('nilai', '<', 70)->where(function($q){ $q->whereNull('status_pengerjaan')->orWhere('status_pengerjaan', '!=', 'terkunci'); })->count(),
            'terkunci' => HasilUjian::where('status_pengerjaan', 'terkunci')->count(),
            'rata_rata'=> round(HasilUjian::avg('nilai') ?? 0, 1),
        ];

        return view('laporan.index', compact(
            'hasilUjians', 'kelass', 'ujians', 'kelasId', 'ujianId', 'status', 'search', 'stats'
        ));
    }

    /**
     * Tampilkan laporan khusus per siswa (Daftar Rapor Siswa).
     */
    public function laporanSiswa(Request $request)
    {
        $kelass  = \App\Models\Kelas::orderBy('nama_kelas')->get();
        $kelasId = $request->get('kelas_id');
        $search  = $request->get('search');

        $query = User::with('kelas')
            ->withCount(['hasilUjians as total_lulus' => function ($q) {
                $q->where('nilai', '>=', 70)->where(function($sq){ $sq->whereNull('status_pengerjaan')->orWhere('status_pengerjaan', '!=', 'terkunci'); });
            }])
            ->withCount('hasilUjians')
            ->where('role', 'siswa')
            ->latest();

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $siswas = $query->paginate(12)->withQueryString();

        foreach ($siswas as $siswa) {
            $siswa->avg_score = round(HasilUjian::where('user_id', $siswa->id)->avg('nilai') ?? 0, 1);
            $siswa->total_terkunci = HasilUjian::where('user_id', $siswa->id)->where('status_pengerjaan', 'terkunci')->count();
        }

        return view('laporan.siswa', compact('siswas', 'kelass', 'kelasId', 'search'));
    }

    /**
     * Detail Rapor Lengkap Per Siswa.
     */
    public function detailSiswa(User $siswa)
    {
        $siswa->load('kelas');

        $hasilUjians = HasilUjian::with('ujian.mapel', 'ujian.kelas')
            ->where('user_id', $siswa->id)
            ->latest()
            ->get();

        $avgScore = round($hasilUjians->avg('nilai') ?? 0, 1);
        $totalLulus = $hasilUjians->where('nilai', '>=', 70)->where('status_pengerjaan', '!=', 'terkunci')->count();
        $totalRemidi = $hasilUjians->where('nilai', '<', 70)->where('status_pengerjaan', '!=', 'terkunci')->count();
        $totalTerkunci = $hasilUjians->where('status_pengerjaan', 'terkunci')->count();

        return view('laporan.siswa_detail', compact(
            'siswa', 'hasilUjians', 'avgScore', 'totalLulus', 'totalRemidi', 'totalTerkunci'
        ));
    }

    /**
     * Ekspor Daftar Siswa ke Excel (Admin).
     */
    public function exportSiswa()
    {
        return Excel::download(new SiswaExport, 'daftar-siswa.xlsx');
    }

    /**
     * Ekspor Hasil Ujian ke Excel.
     */
    public function exportHasilUjian()
    {
        return Excel::download(new HasilUjianExport, 'hasil-ujian.xlsx');
    }

    /**
     * Ekspor Bank Soal ke Excel.
     */
    public function exportSoal()
    {
        return Excel::download(new SoalExport, 'bank-soal.xlsx');
    }

    /**
     * Cetak PDF Hasil Ujian Siswa (Personal).
     */
    public function pdfHasilUjian(HasilUjian $hasil)
    {
        $hasil->load(['user.kelas', 'ujian.mapel']);
        $pdf = Pdf::loadView('pdf.hasil-ujian', compact('hasil'));
        $nis = $hasil->user->nis ?? $hasil->user->id;
        return $pdf->download("hasil-ujian-{$nis}-{$hasil->ujian->id}.pdf");
    }

    /**
     * Cetak PDF Kartu Peserta Ujian.
     */
    public function pdfKartuPeserta(User $user)
    {
        $user->load('kelas');
        $pdf = Pdf::loadView('pdf.kartu-peserta', compact('user'));
        $nis = $user->nis ?? $user->id;
        return $pdf->download("kartu-peserta-{$nis}.pdf");
    }

    /**
     * Logika untuk men-generate Sertifikat secara fisik dan menyimpannya ke Storage.
     * Dipanggil otomatis saat siswa lulus ujian (skor >= 70).
     */
    public function generateSertifikat(HasilUjian $hasil)
    {
        $hasil->load(['user.kelas', 'ujian.mapel']);

        // Cek jika record sertifikat sudah ada
        $existing = Sertifikat::where('user_id', $hasil->user_id)
            ->where('ujian_id', $hasil->ujian_id)
            ->first();
            
        if ($existing) {
            return $existing;
        }

        // Format nomor sertifikat unik: CBT/KODE-MAPEL/TANGGAL/ID-HASIL
        $kodeMapel = $hasil->ujian->mapel->kode_mapel ?? 'CBT';
        $tanggal = now()->format('Ymd');
        $certificateNumber = "CBT/{$kodeMapel}/{$tanggal}/" . sprintf('%04d', $hasil->id);
        
        // URL untuk verifikasi keaslian via QR Code
        $verificationUrl = route('sertifikat.verify', $certificateNumber);

        // Generate QR Code as base64 SVG for DomPDF compatibility
        $qrCode = base64_encode(QrCode::format('svg')->size(90)->generate($verificationUrl));

        // Generate PDF Sertifikat Kelulusan
        $pdf = Pdf::loadView('pdf.sertifikat', compact('hasil', 'certificateNumber', 'verificationUrl', 'qrCode'));
        
        // Simpan PDF ke storage/app/public/sertifikats/
        $fileName = "sertifikats/sertifikat-{$hasil->user_id}-{$hasil->ujian_id}.pdf";
        Storage::disk('public')->put($fileName, $pdf->output());

        // Simpan data sertifikat ke database
        return Sertifikat::create([
            'user_id' => $hasil->user_id,
            'ujian_id' => $hasil->ujian_id,
            'nomor_sertifikat' => $certificateNumber,
            'file_path' => $fileName,
        ]);
    }

    /**
     * Unduh PDF Sertifikat (untuk Siswa).
     */
    public function downloadSertifikat(Ujian $ujian)
    {
        $userId = Auth::id();
        $sertifikat = Sertifikat::where('user_id', $userId)
            ->where('ujian_id', $ujian->id)
            ->firstOrFail();

        if (!Storage::disk('public')->exists($sertifikat->file_path)) {
            abort(404, 'File sertifikat tidak ditemukan di penyimpanan server.');
        }

        return Storage::disk('public')->download($sertifikat->file_path, "Sertifikat-Kelulusan-{$ujian->judul}.pdf");
    }

    /**
     * Halaman verifikasi publik keaslian sertifikat via QR Code.
     */
    public function verifySertifikat($nomor_sertifikat)
    {
        // Decode nomor sertifikat jika ada encoding karakter URL
        $sertifikat = Sertifikat::with(['user.kelas', 'ujian.mapel'])
            ->where('nomor_sertifikat', $nomor_sertifikat)
            ->first();

        return view('sertifikat.verify', compact('sertifikat', 'nomor_sertifikat'));
    }

    /**
     * Menyetujui (Approve) Buka Kunci Ujian Siswa yang terkunci akibat pelanggaran.
     */
    public function bukaKunci(HasilUjian $hasil)
    {
        $ujianId = $hasil->ujian_id;
        $userId  = $hasil->user_id;

        // Hapus jawaban peserta terdahulu agar siswa bisa mengerjakan dari awal
        \App\Models\JawabanPeserta::where('user_id', $userId)->where('ujian_id', $ujianId)->delete();

        // Hapus record hasil ujian terkunci
        $hasil->delete();

        return redirect()->back()->with('success', 'Kunci ujian peserta berhasil dibuka. Siswa dapat mengerjakan kembali ujian tersebut.');
    }
}
