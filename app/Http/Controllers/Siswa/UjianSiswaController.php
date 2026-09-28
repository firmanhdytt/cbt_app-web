<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use App\Models\HasilUjian;
use App\Models\JawabanPeserta;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\LaporanController;

class UjianSiswaController extends Controller
{
    /**
     * Tampilkan daftar ujian yang tersedia.
     */
    public function index()
    {
        $user   = Auth::user();
        $userId = $user->id;

        // Ambil ID ujian yang sudah dikerjakan siswa
        $completedUjianIds = HasilUjian::where('user_id', $userId)->pluck('ujian_id')->toArray();

        // Query dasar: ujian aktif, dalam periode waktu, belum dikerjakan
        $query = Ujian::with('mapel', 'kelas')
            ->where('status', true)
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->whereNotIn('id', $completedUjianIds);

        // Filter berdasarkan kelas siswa yang login:
        // Siswa melihat ujian yang dikhususkan untuk kelasnya ATAU ujian umum (tanpa batas kelas)
        if ($user->kelas_id) {
            $query->where(function ($q) use ($user) {
                $q->where('kelas_id', $user->kelas_id)
                  ->orWhereNull('kelas_id');
            });
        } else {
            // Siswa belum memiliki kelas -> hanya tampilkan ujian umum (tanpa batas kelas)
            $query->whereNull('kelas_id');
        }

        $ujians = $query->latest()->get();

        return view('siswa.ujian.index', compact('ujians'));
    }

    /**
     * Konfirmasi sebelum memulai ujian.
     */
    public function confirm(Ujian $ujian)
    {
        $userId = Auth::id();

        $existingHasil = HasilUjian::where('user_id', $userId)->where('ujian_id', $ujian->id)->first();
        if ($existingHasil) {
            if ($existingHasil->status_pengerjaan === 'terkunci') {
                $msg = $existingHasil->perlu_buka_kunci 
                    ? 'Ujian Anda sedang terkunci akibat pelanggaran. Permohonan buka kunci telah terkirim dan sedang menunggu persetujuan Guru/Admin.'
                    : 'Ujian Anda terkunci akibat 2 kali pelanggaran proctoring.';
                return redirect()->route('siswa.ujian.index')->with('error', $msg);
            }
            return redirect()->route('siswa.ujian.index')->with('error', 'Anda telah menyelesaikan ujian ini.');
        }

        // Cek masa aktif ujian
        if (!$ujian->status || now()->isBefore($ujian->tanggal_mulai) || now()->isAfter($ujian->tanggal_selesai)) {
            return redirect()->route('siswa.ujian.index')->with('error', 'Jadwal ujian ini sudah ditutup atau belum dimulai.');
        }

        return view('siswa.ujian.confirm', compact('ujian'));
    }

    /**
     * Mulai mengerjakan ujian (ruang ujian).
     */
    public function start(Ujian $ujian)
    {
        $userId = Auth::id();

        $existingHasil = HasilUjian::where('user_id', $userId)->where('ujian_id', $ujian->id)->first();
        if ($existingHasil) {
            if ($existingHasil->status_pengerjaan === 'terkunci') {
                $msg = $existingHasil->perlu_buka_kunci 
                    ? 'Ujian Anda sedang terkunci akibat pelanggaran. Permohonan buka kunci telah terkirim dan sedang menunggu persetujuan Guru/Admin.'
                    : 'Ujian Anda terkunci akibat 2 kali pelanggaran proctoring.';
                return redirect()->route('siswa.ujian.index')->with('error', $msg);
            }
            return redirect()->route('siswa.ujian.index')->with('error', 'Anda telah menyelesaikan ujian ini.');
        } else {
            // Reset violation session counter jika kunci ujian dibuka oleh Guru/Admin
            if (!session()->has('ujian_started_at_' . $ujian->id)) {
                session()->forget('ujian_violations_' . $ujian->id);
            }
        }

        // Cek masa aktif ujian
        if (!$ujian->status || now()->isBefore($ujian->tanggal_mulai) || now()->isAfter($ujian->tanggal_selesai)) {
            return redirect()->route('siswa.ujian.index')->with('error', 'Jadwal ujian ini sudah ditutup atau belum dimulai.');
        }

        // Ambil waktu mulai pengerjaan dari session (untuk ketahanan refresh page)
        $sessionKey = 'ujian_started_at_' . $ujian->id;
        if (!session()->has($sessionKey)) {
            session()->put($sessionKey, now());
        }

        $startedAt = session()->get($sessionKey);
        $elapsedSeconds = now()->diffInSeconds(Carbon::parse($startedAt));
        $totalSeconds = $ujian->durasi_menit * 60;
        $remainingSeconds = $totalSeconds - $elapsedSeconds;

        // Jika waktu pengerjaan sudah habis
        if ($remainingSeconds <= 0) {
            return $this->autoSubmit($ujian);
        }

        // Load soal-soal ujian
        $soals = $ujian->soals()->get();

        // Ambil jawaban yang sebelumnya sudah disimpan siswa (jika ada refresh)
        $jawabanPesertas = JawabanPeserta::where('user_id', $userId)
            ->where('ujian_id', $ujian->id)
            ->pluck('jawaban', 'soal_id')
            ->toArray();

        return view('siswa.ujian.start', compact('ujian', 'soals', 'jawabanPesertas', 'remainingSeconds'));
    }

    /**
     * Endpoint AJAX untuk menyimpan jawaban per nomor soal secara real-time.
     */
    public function saveAnswer(Request $request, Ujian $ujian)
    {
        $request->validate([
            'soal_id' => 'required|exists:soals,id',
            'jawaban' => 'nullable|in:A,B,C,D,a,b,c,d',
        ]);

        // Keamanan Tambahan: Pastikan soal_id benar-benar milik Ujian ini
        $belongsToUjian = $ujian->soals()->where('soals.id', $request->soal_id)->exists();
        if (!$belongsToUjian) {
            return response()->json(['status' => 'error', 'message' => 'Soal tidak valid untuk ujian ini.'], 422);
        }

        $jawaban = $request->jawaban ? strtoupper(trim($request->jawaban)) : null;

        JawabanPeserta::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'ujian_id' => $ujian->id,
                'soal_id' => $request->soal_id,
            ],
            [
                'jawaban' => $jawaban,
            ]
        );

        return response()->json(['status' => 'success', 'message' => 'Jawaban berhasil disimpan otomatis.']);
    }

    /**
     * Catat pelanggaran proctoring (misal: keluar fullscreen / pindah tab).
     * Jika pelanggaran mencapai 2 kali, ujian terkunci otomatis.
     */
    public function recordViolation(Request $request, Ujian $ujian)
    {
        $userId = Auth::id();

        $sessionKey = 'ujian_violations_' . $ujian->id;
        $count = (int) session()->get($sessionKey, 0) + 1;
        session()->put($sessionKey, $count);

        if ($count >= 2) {
            $soals = $ujian->soals()->get();
            $totalQuestions = $soals->count();

            $jawabanPesertas = JawabanPeserta::where('user_id', $userId)
                ->where('ujian_id', $ujian->id)
                ->pluck('jawaban', 'soal_id')
                ->toArray();

            $jumlahBenar = 0;
            $jumlahSalah = 0;
            foreach ($soals as $soal) {
                $j = $jawabanPesertas[$soal->id] ?? null;
                if ($j !== null && strtoupper(trim($j)) === strtoupper(trim($soal->jawaban_benar))) {
                    $jumlahBenar++;
                } else {
                    $jumlahSalah++;
                }
            }

            $nilai = $totalQuestions > 0 ? round(($jumlahBenar / $totalQuestions) * 100, 2) : 0;

            $userAgent = request()->header('User-Agent');
            $isExambro = \Illuminate\Support\Str::contains($userAgent ?? '', ['CBT-Exambro', 'Exambro']);

            $hasil = HasilUjian::updateOrCreate(
                [
                    'user_id'  => $userId,
                    'ujian_id' => $ujian->id,
                ],
                [
                    'nilai'              => $nilai,
                    'jumlah_benar'       => $jumlahBenar,
                    'jumlah_salah'       => $jumlahSalah,
                    'jumlah_pelanggaran' => $count,
                    'status_pengerjaan'  => 'terkunci',
                    'is_exambro'         => $isExambro,
                    'user_agent'         => substr($userAgent ?? '', 0, 250),
                    'waktu_selesai'      => now(),
                ]
            );

            session()->forget('ujian_started_at_' . $ujian->id);

            return response()->json([
                'status'           => 'locked',
                'violation_count'  => $count,
                'hasil_id'         => $hasil->id,
                'message'          => 'Ujian terkunci otomatis akibat 2 kali pelanggaran proctoring.'
            ]);
        }

        return response()->json([
            'status'          => 'warning',
            'violation_count' => $count,
            'message'         => 'Pelanggaran ke-' . $count . ' terdeteksi.'
        ]);
    }

    /**
     * Ajukan permohonan buka kunci ujian ke Guru / Admin.
     */
    public function requestUnlock(Request $request, Ujian $ujian)
    {
        $request->validate([
            'alasan' => 'required|string|max:500',
        ], [
            'alasan.required' => 'Mohon tuliskan alasan pengajuan buka kunci.',
        ]);

        $userId = Auth::id();

        $hasil = HasilUjian::where('user_id', $userId)
            ->where('ujian_id', $ujian->id)
            ->first();

        if (!$hasil) {
            return response()->json(['status' => 'error', 'message' => 'Data pengerjaan tidak ditemukan.'], 404);
        }

        $hasil->update([
            'perlu_buka_kunci'  => true,
            'alasan_buka_kunci' => $request->alasan,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Permohonan buka kunci berhasil dikirim. Silakan beritahu Guru atau Admin Anda.'
        ]);
    }

    /**
     * Submit ujian secara manual.
     */
    public function submit(Request $request, Ujian $ujian)
    {
        return $this->processSubmission($ujian);
    }

    /**
     * Submit ujian otomatis saat waktu habis.
     */
    protected function autoSubmit(Ujian $ujian)
    {
        return $this->processSubmission($ujian, true);
    }

    /**
     * Proses perhitungan nilai dan submit hasil ujian.
     */
    protected function processSubmission(Ujian $ujian, $isAutoSubmit = false)
    {
        $userId = Auth::id();

        // Pastikan siswa belum pernah submit ujian ini sebelumnya
        if (HasilUjian::where('user_id', $userId)->where('ujian_id', $ujian->id)->exists()) {
            return redirect()->route('siswa.riwayat')->with('success', 'Ujian Anda telah berhasil tersimpan.');
        }

        // Ambil semua soal ujian
        $soals = $ujian->soals()->get();
        $totalQuestions = $soals->count();

        // Ambil jawaban yang diinputkan siswa
        $jawabanPesertas = JawabanPeserta::where('user_id', $userId)
            ->where('ujian_id', $ujian->id)
            ->pluck('jawaban', 'soal_id')
            ->toArray();

        $jumlahBenar = 0;
        $jumlahSalah = 0;

        foreach ($soals as $soal) {
            $jawabanSiswa = $jawabanPesertas[$soal->id] ?? null;
            if ($jawabanSiswa !== null && strtoupper(trim($jawabanSiswa)) === strtoupper(trim($soal->jawaban_benar))) {
                $jumlahBenar++;
            } else {
                $jumlahSalah++;
            }
        }

        $nilai = $totalQuestions > 0 ? ($jumlahBenar / $totalQuestions) * 100 : 0;
        $nilai = round($nilai, 2);

        $userAgent = request()->header('User-Agent');
        $isExambro = \Illuminate\Support\Str::contains($userAgent ?? '', ['CBT-Exambro', 'Exambro']);

        // Catat hasil ujian
        $hasil = HasilUjian::create([
            'user_id' => $userId,
            'ujian_id' => $ujian->id,
            'nilai' => $nilai,
            'jumlah_benar' => $jumlahBenar,
            'jumlah_salah' => $jumlahSalah,
            'is_exambro' => $isExambro,
            'user_agent' => substr($userAgent ?? '', 0, 250),
            'waktu_selesai' => now(),
        ]);

        // Bersihkan session timer pengerjaan
        session()->forget('ujian_started_at_' . $ujian->id);

        // Pemicu e-sertifikat jika nilai kelulusan >= 70
        if ($nilai >= 70.00) {
            try {
                $laporanController = new LaporanController();
                $laporanController->generateSertifikat($hasil);
            } catch (\Exception $e) {
                // Log exception atau biarkan agar tidak crash saat ujian selesai
                logger('Gagal menerbitkan e-sertifikat otomatis: ' . $e->getMessage());
            }
        }

        $message = $isAutoSubmit 
            ? 'Waktu ujian telah habis! Jawaban Anda telah disubmit otomatis secara aman.' 
            : 'Ujian berhasil diselesaikan. Nilai Anda telah tercatat.';

        return redirect()->route('siswa.riwayat')->with('success', $message);
    }

    /**
     * Tampilkan riwayat nilai ujian siswa.
     */
    public function history()
    {
        $hasilUjians = HasilUjian::with('ujian.mapel', 'ujian.kelas')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('siswa.riwayat.index', compact('hasilUjians'));
    }

    /**
     * Tampilkan daftar e-sertifikat siswa.
     */
    public function certificates()
    {
        $sertifikats = Sertifikat::with('ujian.mapel')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('siswa.sertifikat.index', compact('sertifikats'));
    }
}
