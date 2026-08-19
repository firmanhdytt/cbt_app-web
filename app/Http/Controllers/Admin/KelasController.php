<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $tingkat      = $request->get('tingkat');
        $jurusan      = $request->get('jurusan');
        $tahunAjaran  = $request->get('tahun_ajaran');
        $search       = $request->get('search');

        $query = Kelas::withCount('siswas', 'ujians')->latest();

        if ($tingkat) {
            $query->where('tingkat', $tingkat);
        }

        if ($jurusan) {
            $query->where('jurusan', $jurusan);
        }

        if ($tahunAjaran) {
            $query->where('tahun_ajaran', $tahunAjaran);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kelas', 'like', "%{$search}%")
                  ->orWhere('jurusan', 'like', "%{$search}%")
                  ->orWhere('tingkat', 'like', "%{$search}%");
            });
        }

        $kelass = $query->paginate(10)->withQueryString();

        // Get distinct list for filter dropdown options
        $tingkatList     = Kelas::whereNotNull('tingkat')->where('tingkat', '!=', '')->distinct()->pluck('tingkat');
        $jurusanList     = Kelas::whereNotNull('jurusan')->where('jurusan', '!=', '')->distinct()->pluck('jurusan');
        $tahunAjaranList = Kelas::whereNotNull('tahun_ajaran')->where('tahun_ajaran', '!=', '')->distinct()->pluck('tahun_ajaran');

        return view('admin.kelas.index', compact(
            'kelass', 'tingkat', 'jurusan', 'tahunAjaran', 'search',
            'tingkatList', 'jurusanList', 'tahunAjaranList'
        ));
    }

    public function create()
    {
        return view('admin.kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas'   => 'required|string|max:100',
            'tingkat'      => 'nullable|string|max:20',
            'jurusan'      => 'nullable|string|max:50',
            'tahun_ajaran' => 'nullable|string|max:20',
        ], [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
        ]);

        Kelas::create($request->only('nama_kelas', 'tingkat', 'jurusan', 'tahun_ajaran'));

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas)
    {
        return view('admin.kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama_kelas'   => 'required|string|max:100',
            'tingkat'      => 'nullable|string|max:20',
            'jurusan'      => 'nullable|string|max:50',
            'tahun_ajaran' => 'nullable|string|max:20',
        ], [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
        ]);

        $kelas->update($request->only('nama_kelas', 'tingkat', 'jurusan', 'tahun_ajaran'));

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        if ($kelas->siswas()->count() > 0) {
            return redirect()->route('admin.kelas.index')
                ->with('error', 'Kelas tidak dapat dihapus karena masih memiliki ' . $kelas->siswas()->count() . ' siswa terdaftar.');
        }

        $kelas->delete();
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
