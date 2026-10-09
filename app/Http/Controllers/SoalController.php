<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use App\Models\Mapel;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Exports\SoalExport;
use App\Imports\SoalImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class SoalController extends Controller
{
    /**
     * Display a listing of the resource with filters.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role === 'guru') {
            $assignedKelasIds = $user->getAssignedKelasIds();
            $assignedMapelIds = $user->getAssignedMapelIds();

            $kelass = Kelas::whereIn('id', $assignedKelasIds)->orderBy('nama_kelas')->get();
            $mapels = Mapel::whereIn('id', $assignedMapelIds)->orderBy('nama_mapel')->get();

            $query = Soal::with('mapel', 'kelas', 'creator')
                ->whereIn('kelas_id', $assignedKelasIds)
                ->whereIn('mapel_id', $assignedMapelIds)
                ->latest();
        } else {
            $mapels = Mapel::orderBy('nama_mapel')->get();
            $kelass = Kelas::orderBy('nama_kelas')->get();
            $query  = Soal::with('mapel', 'kelas', 'creator')->latest();
        }

        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->mapel_id);
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $soals = $query->paginate(10)->withQueryString();

        return view('soal.index', compact('soals', 'mapels', 'kelass'));
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
        } else {
            $mapels = Mapel::orderBy('nama_mapel')->get();
            $kelass = Kelas::orderBy('nama_kelas')->get();
        }

        return view('soal.create', compact('mapels', 'kelass'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'mapel_id'     => 'required|exists:mapels,id',
            'kelas_id'     => 'required|exists:kelas,id',
            'pertanyaan'   => 'required|string',
            'pilihan_a'    => 'required|string',
            'pilihan_b'    => 'required|string',
            'pilihan_c'    => 'required|string',
            'pilihan_d'    => 'required|string',
            'jawaban_benar' => 'required|in:A,B,C,D',
        ], [
            'mapel_id.required'     => 'Mata pelajaran wajib dipilih.',
            'kelas_id.required'     => 'Kelas wajib dipilih.',
            'pertanyaan.required'   => 'Pertanyaan wajib diisi.',
            'pilihan_a.required'    => 'Pilihan A tidak boleh kosong.',
            'pilihan_b.required'    => 'Pilihan B tidak boleh kosong.',
            'pilihan_c.required'    => 'Pilihan C tidak boleh kosong.',
            'pilihan_d.required'    => 'Pilihan D tidak boleh kosong.',
            'jawaban_benar.required' => 'Jawaban benar wajib ditentukan.',
        ]);

        if (!$user->hasWorkspaceAccess($request->kelas_id, $request->mapel_id)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki akses workspace untuk kelas & mata pelajaran ini.');
        }

        Soal::create([
            'mapel_id'     => $request->mapel_id,
            'kelas_id'     => $request->kelas_id,
            'created_by'   => $user->id,
            'pertanyaan'   => $request->pertanyaan,
            'pilihan_a'    => $request->pilihan_a,
            'pilihan_b'    => $request->pilihan_b,
            'pilihan_c'    => $request->pilihan_c,
            'pilihan_d'    => $request->pilihan_d,
            'jawaban_benar' => $request->jawaban_benar,
        ]);

        return redirect()->route($user->role . '.soal.index')->with('success', 'Soal berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Soal $soal)
    {
        $user = Auth::user();
        if (!$user->hasWorkspaceAccess($soal->kelas_id, $soal->mapel_id)) {
            abort(403, 'Akses ditolak: Soal ini berada di luar workspace Anda.');
        }

        return view('soal.show', compact('soal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Soal $soal)
    {
        $user = Auth::user();
        if (!$user->hasWorkspaceAccess($soal->kelas_id, $soal->mapel_id)) {
            abort(403, 'Akses ditolak: Soal ini berada di luar workspace Anda.');
        }

        if ($user->role === 'guru') {
            $assignedKelasIds = $user->getAssignedKelasIds();
            $assignedMapelIds = $user->getAssignedMapelIds();

            $kelass = Kelas::whereIn('id', $assignedKelasIds)->orderBy('nama_kelas')->get();
            $mapels = Mapel::whereIn('id', $assignedMapelIds)->orderBy('nama_mapel')->get();
        } else {
            $mapels = Mapel::orderBy('nama_mapel')->get();
            $kelass = Kelas::orderBy('nama_kelas')->get();
        }

        return view('soal.edit', compact('soal', 'mapels', 'kelass'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Soal $soal)
    {
        $user = Auth::user();
        if (!$user->hasWorkspaceAccess($soal->kelas_id, $soal->mapel_id)) {
            abort(403, 'Akses ditolak: Soal ini berada di luar workspace Anda.');
        }

        $request->validate([
            'mapel_id'     => 'required|exists:mapels,id',
            'kelas_id'     => 'required|exists:kelas,id',
            'pertanyaan'   => 'required|string',
            'pilihan_a'    => 'required|string',
            'pilihan_b'    => 'required|string',
            'pilihan_c'    => 'required|string',
            'pilihan_d'    => 'required|string',
            'jawaban_benar' => 'required|in:A,B,C,D',
        ], [
            'mapel_id.required'     => 'Mata pelajaran wajib dipilih.',
            'kelas_id.required'     => 'Kelas wajib dipilih.',
            'pertanyaan.required'   => 'Pertanyaan wajib diisi.',
            'pilihan_a.required'    => 'Pilihan A tidak boleh kosong.',
            'pilihan_b.required'    => 'Pilihan B tidak boleh kosong.',
            'pilihan_c.required'    => 'Pilihan C tidak boleh kosong.',
            'pilihan_d.required'    => 'Pilihan D tidak boleh kosong.',
            'jawaban_benar.required' => 'Jawaban benar wajib ditentukan.',
        ]);

        if (!$user->hasWorkspaceAccess($request->kelas_id, $request->mapel_id)) {
            abort(403, 'Akses ditolak: Anda tidak dapat memindahkan soal ke kelas & mapel di luar workspace Anda.');
        }

        $soal->update([
            'mapel_id'     => $request->mapel_id,
            'kelas_id'     => $request->kelas_id,
            'pertanyaan'   => $request->pertanyaan,
            'pilihan_a'    => $request->pilihan_a,
            'pilihan_b'    => $request->pilihan_b,
            'pilihan_c'    => $request->pilihan_c,
            'pilihan_d'    => $request->pilihan_d,
            'jawaban_benar' => $request->jawaban_benar,
        ]);

        return redirect()->route($user->role . '.soal.index')->with('success', 'Soal berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Soal $soal)
    {
        $user = Auth::user();
        if (!$user->hasWorkspaceAccess($soal->kelas_id, $soal->mapel_id)) {
            abort(403, 'Akses ditolak: Soal ini berada di luar workspace Anda.');
        }

        $soal->delete();
        return redirect()->route($user->role . '.soal.index')->with('success', 'Soal berhasil dihapus.');
    }

    /**
     * Export questions.
     */
    public function export()
    {
        return Excel::download(new SoalExport(Auth::user()), 'bank-soal.xlsx');
    }

    /**
     * Import questions.
     */
    public function import(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'file'     => 'required|mimes:xlsx,xls,csv|max:5120',
            'mapel_id' => $user->role === 'guru' ? 'required|exists:mapels,id' : 'nullable|exists:mapels,id',
            'kelas_id' => $user->role === 'guru' ? 'required|exists:kelas,id' : 'nullable|exists:kelas,id',
        ];

        $request->validate($rules, [
            'file.required'     => 'File Excel/CSV wajib diunggah.',
            'file.mimes'        => 'Format file harus berupa XLSX, XLS, atau CSV.',
            'file.max'          => 'Ukuran file tidak boleh lebih dari 5MB.',
            'mapel_id.required' => 'Mata pelajaran wajib dipilih untuk akun Guru.',
            'kelas_id.required' => 'Kelas wajib dipilih untuk akun Guru.',
        ]);

        if ($user->role === 'guru' && !$user->hasWorkspaceAccess($request->kelas_id, $request->mapel_id)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki akses workspace untuk kelas & mapel ini.');
        }

        $import = new SoalImport($request->mapel_id, $request->kelas_id);
        Excel::import($import, $request->file('file'));

        $count = $import->getImportedCount();

        return redirect()->route($user->role . '.soal.index')->with('success', "Berhasil mengimpor {$count} soal ke bank soal.");
    }
}
