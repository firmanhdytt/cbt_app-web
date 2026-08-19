<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Soal;
use App\Models\Ujian;
use App\Models\HasilUjian;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard untuk role Admin.
     */
    public function admin()
    {
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalGuru = User::where('role', 'guru')->count();
        $totalSoal = Soal::count();
        $totalUjian = Ujian::count();

        // Ambil hasil ujian terbaru untuk dipajang di dashboard
        $recentResults = HasilUjian::with(['user', 'ujian.mapel'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalGuru',
            'totalSoal',
            'totalUjian',
            'recentResults'
        ));
    }

    /**
     * Dashboard untuk role Guru.
     */
    public function guru()
    {
        $userId = Auth::id();
        
        $soalDibuat = Soal::where('created_by', $userId)->count();
        $ujianAktif = Ujian::where('status', true)
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->count();
            
        // Ambil seluruh hasil ujian siswa terbaru
        $recentResults = HasilUjian::with(['user', 'ujian.mapel'])
            ->latest()
            ->take(5)
            ->get();

        return view('guru.dashboard', compact(
            'soalDibuat',
            'ujianAktif',
            'recentResults'
        ));
    }

    /**
     * Dashboard untuk role Siswa.
     */
    public function siswa()
    {
        $user   = Auth::user();
        $userId = $user->id;

        // Hitung ujian yang tersedia dan belum dikerjakan (sesuai kelas siswa)
        $completedUjianIds = HasilUjian::where('user_id', $userId)->pluck('ujian_id')->toArray();
        
        $query = Ujian::where('status', true)
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->whereNotIn('id', $completedUjianIds);

        if ($user->kelas_id) {
            $query->where(function ($q) use ($user) {
                $q->where('kelas_id', $user->kelas_id)
                  ->orWhereNull('kelas_id');
            });
        } else {
            $query->whereNull('kelas_id');
        }

        $ujianTersedia = $query->count();

        // Nilai terakhir siswa
        $lastResult = HasilUjian::with('ujian.mapel')
            ->where('user_id', $userId)
            ->latest()
            ->first();

        // Jumlah sertifikat kelulusan yang diperoleh
        $totalSertifikat = Sertifikat::where('user_id', $userId)->count();

        // Riwayat nilai singkat (3 terbaru)
        $recentResults = HasilUjian::with('ujian.mapel')
            ->where('user_id', $userId)
            ->latest()
            ->take(3)
            ->get();

        return view('siswa.dashboard', compact(
            'ujianTersedia',
            'lastResult',
            'totalSertifikat',
            'recentResults'
        ));
    }
}
