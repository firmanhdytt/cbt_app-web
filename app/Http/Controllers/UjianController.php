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
        $mapels  = Mapel::orderBy('nama_mapel')->get();
        $kelass  = Kelas::orderBy('nama_kelas')->get();

        $mapelId = $request->get('mapel_id');
        $kelasId = $request->get('kelas_id');
        $status  = $request->get('status');
        $search  = $request->get('search');

        $query = Ujian::with('mapel', 'kelas')->withCount('soals')->latest();

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
        $mapels = Mapel::all();
        $kelass = Kelas::all();
        $soals  = Soal::with('mapel', 'kelas')->latest()->get();
        return view('ujian.create', compact('mapels', 'kelass', 'soals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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

        $role = Auth::user()->role;
        return redirect()->route($role . '.ujian.index')->with('success', 'Ujian berhasil dibuat dan dijadwalkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ujian $ujian)
    {
        $ujian->load('mapel', 'kelas', 'soals');
        return view('ujian.show', compact('ujian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ujian $ujian)
    {
        $ujian->load('soals');
        $mapels = Mapel::all();
        $kelass = Kelas::all();
        $soals  = Soal::with('mapel', 'kelas')->latest()->get();
        return view('ujian.edit', compact('ujian', 'mapels', 'kelass', 'soals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ujian $ujian)
    {
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

        $role = Auth::user()->role;
        return redirect()->route($role . '.ujian.index')->with('success', 'Ujian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ujian $ujian)
    {
        $ujian->delete();
        $role = Auth::user()->role;
        return redirect()->route($role . '.ujian.index')->with('success', 'Ujian berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif ujian.
     */
    public function toggleStatus(Ujian $ujian)
    {
        $ujian->update(['status' => !$ujian->status]);
        $role = Auth::user()->role;
        $statusText = $ujian->status ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route($role . '.ujian.index')->with('success', "Ujian berhasil {$statusText}.");
    }
}
