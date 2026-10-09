<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\Mapel;
use App\Models\Soal;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            $assignedKelasIds = $user->getAssignedKelasIds();
            $assignedMapelIds = $user->getAssignedMapelIds();

            $kelass = Kelas::whereIn('id', $assignedKelasIds)->orderBy('nama_kelas')->get();
            $mapels = Mapel::whereIn('id', $assignedMapelIds)->orderBy('nama_mapel')->get();

            $query = Ujian::with('mapel', 'kelas')
                ->withCount('soals')
                ->whereIn('kelas_id', $assignedKelasIds)
                ->whereIn('mapel_id', $assignedMapelIds)
                ->latest();
        } else {
            $mapels = Mapel::orderBy('nama_mapel')->get();
            $kelass = Kelas::orderBy('nama_kelas')->get();
            $query  = Ujian::with('mapel', 'kelas')->withCount('soals')->latest();
        }

        $mapelId = $request->get('mapel_id');
        $kelasId = $request->get('kelas_id');
        $status  = $request->get('status');
        $search  = $request->get('search');

        if ($mapelId) {
            $query->where('mapel_id', $mapelId);
        }

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        if ($status !== null && $status !== '') {
            $query->where('status', (bool)$status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $ujians = $query->paginate(10)->withQueryString();

        return view('ujian.index', compact(
            'ujians', 'mapels', 'kelass', 'mapelId', 'kelasId', 'status', 'search'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            $assignedKelasIds = $user->getAssignedKelasIds();
            $assignedMapelIds = $user->getAssignedMapelIds();

            $kelass = Kelas::whereIn('id', $assignedKelasIds)->orderBy('nama_kelas')->get();
            $mapels = Mapel::whereIn('id', $assignedMapelIds)->orderBy('nama_mapel')->get();

            $soals  = Soal::with('mapel', 'kelas')
                ->whereIn('kelas_id', $assignedKelasIds)
                ->whereIn('mapel_id', $assignedMapelIds)
                ->latest()
                ->get();
        } else {
            $mapels = Mapel::orderBy('nama_mapel')->get();
            $kelass = Kelas::orderBy('nama_kelas')->get();
            $soals  = Soal::with('mapel', 'kelas')->latest()->get();
        }

        return view('ujian.create', compact('mapels', 'kelass', 'soals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'mapel_id'        => 'required|exists:mapels,id',
            'kelas_id'        => 'required|exists:kelas,id',
            'judul'           => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'durasi_menit'    => 'required|integer|min:1',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status'          => 'nullable|boolean',
            'soals'           => 'required|array|min:1',
            'soals.*'         => 'exists:soals,id',
        ], [
            'mapel_id.required'        => 'Mata pelajaran wajib dipilih.',
            'kelas_id.required'        => 'Kelas peserta wajib dipilih.',
            'judul.required'           => 'Judul ujian wajib diisi.',
            'durasi_menit.required'    => 'Durasi menit wajib diisi dan minimal 1 menit.',
            'tanggal_mulai.required'   => 'Tanggal mulai wajib ditentukan.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib ditentukan.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'soals.required'           => 'Minimal pilih 1 soal untuk ujian ini.',
        ]);

        if (!$user->hasWorkspaceAccess($request->kelas_id, $request->mapel_id)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki akses workspace untuk kelas & mapel ini.');
        }

        // Validasi seluruh soal yang dipilih harus sesuai dengan workspace dan kelas/mapel ujian
        $invalidSoalsCount = Soal::whereIn('id', $request->soals)
            ->where(function ($q) use ($request) {
                $q->where('kelas_id', '!=', $request->kelas_id)
                  ->orWhere('mapel_id', '!=', $request->mapel_id);
            })
            ->count();

        if ($invalidSoalsCount > 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['soals' => 'Beberapa soal yang dipilih tidak sesuai dengan Kelas atau Mata Pelajaran ujian ini.']);
        }

        $ujian = Ujian::create([
            'mapel_id'        => $request->mapel_id,
            'kelas_id'        => $request->kelas_id,
            'judul'           => $request->judul,
            'deskripsi'       => $request->deskripsi,
            'durasi_menit'    => $request->durasi_menit,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status'          => $request->has('status') ? (bool) $request->status : false,
        ]);

        $ujian->soals()->sync($request->soals);

        return redirect()->route($user->role . '.ujian.index')->with('success', 'Ujian berhasil dibuat dan dijadwalkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ujian $ujian)
    {
        $user = Auth::user();
        if (!$user->hasWorkspaceAccess($ujian->kelas_id, $ujian->mapel_id)) {
            abort(403, 'Akses ditolak: Ujian ini berada di luar workspace Anda.');
        }

        $ujian->load('mapel', 'kelas', 'soals');
        return view('ujian.show', compact('ujian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ujian $ujian)
    {
        $user = Auth::user();
        if (!$user->hasWorkspaceAccess($ujian->kelas_id, $ujian->mapel_id)) {
            abort(403, 'Akses ditolak: Ujian ini berada di luar workspace Anda.');
        }

        $ujian->load('soals');

        if ($user->role === 'guru') {
            $assignedKelasIds = $user->getAssignedKelasIds();
            $assignedMapelIds = $user->getAssignedMapelIds();

            $kelass = Kelas::whereIn('id', $assignedKelasIds)->orderBy('nama_kelas')->get();
            $mapels = Mapel::whereIn('id', $assignedMapelIds)->orderBy('nama_mapel')->get();

            $soals  = Soal::with('mapel', 'kelas')
                ->whereIn('kelas_id', $assignedKelasIds)
                ->whereIn('mapel_id', $assignedMapelIds)
                ->latest()
                ->get();
        } else {
            $mapels = Mapel::orderBy('nama_mapel')->get();
            $kelass = Kelas::orderBy('nama_kelas')->get();
            $soals  = Soal::with('mapel', 'kelas')->latest()->get();
        }

        return view('ujian.edit', compact('ujian', 'mapels', 'kelass', 'soals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ujian $ujian)
    {
        $user = Auth::user();
        if (!$user->hasWorkspaceAccess($ujian->kelas_id, $ujian->mapel_id)) {
            abort(403, 'Akses ditolak: Ujian ini berada di luar workspace Anda.');
        }

        $request->validate([
            'mapel_id'        => 'required|exists:mapels,id',
            'kelas_id'        => 'required|exists:kelas,id',
            'judul'           => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'durasi_menit'    => 'required|integer|min:1',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status'          => 'nullable|boolean',
            'soals'           => 'required|array|min:1',
            'soals.*'         => 'exists:soals,id',
        ], [
            'mapel_id.required'        => 'Mata pelajaran wajib dipilih.',
            'kelas_id.required'        => 'Kelas peserta wajib dipilih.',
            'judul.required'           => 'Judul ujian wajib diisi.',
            'durasi_menit.required'    => 'Durasi menit wajib diisi dan minimal 1 menit.',
            'tanggal_mulai.required'   => 'Tanggal mulai wajib ditentukan.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib ditentukan.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'soals.required'           => 'Minimal pilih 1 soal untuk ujian ini.',
        ]);

        if (!$user->hasWorkspaceAccess($request->kelas_id, $request->mapel_id)) {
            abort(403, 'Akses ditolak: Anda tidak dapat memindahkan ujian ke kelas & mapel di luar workspace Anda.');
        }

        // Validasi seluruh soal yang dipilih harus sesuai dengan kelas & mapel baru
        $invalidSoalsCount = Soal::whereIn('id', $request->soals)
            ->where(function ($q) use ($request) {
                $q->where('kelas_id', '!=', $request->kelas_id)
                  ->orWhere('mapel_id', '!=', $request->mapel_id);
            })
            ->count();

        if ($invalidSoalsCount > 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['soals' => 'Beberapa soal yang dipilih tidak sesuai dengan Kelas atau Mata Pelajaran ujian ini.']);
        }

        $ujian->update([
            'mapel_id'        => $request->mapel_id,
            'kelas_id'        => $request->kelas_id,
            'judul'           => $request->judul,
            'deskripsi'       => $request->deskripsi,
            'durasi_menit'    => $request->durasi_menit,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status'          => $request->has('status') ? (bool) $request->status : false,
        ]);

        $ujian->soals()->sync($request->soals);

        return redirect()->route($user->role . '.ujian.index')->with('success', 'Ujian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ujian $ujian)
    {
        $user = Auth::user();
        if (!$user->hasWorkspaceAccess($ujian->kelas_id, $ujian->mapel_id)) {
            abort(403, 'Akses ditolak: Ujian ini berada di luar workspace Anda.');
        }

        $ujian->delete();
        return redirect()->route($user->role . '.ujian.index')->with('success', 'Ujian berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif ujian.
     */
    public function toggleStatus(Ujian $ujian)
    {
        $user = Auth::user();
        if (!$user->hasWorkspaceAccess($ujian->kelas_id, $ujian->mapel_id)) {
            abort(403, 'Akses ditolak: Ujian ini berada di luar workspace Anda.');
        }

        $ujian->update(['status' => !$ujian->status]);
        $statusText = $ujian->status ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route($user->role . '.ujian.index')->with('success', "Ujian berhasil {$statusText}.");
    }
}
