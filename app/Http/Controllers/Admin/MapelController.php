<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MapelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mapels = Mapel::latest()->paginate(10);
        return view('admin.mapel.index', compact('mapels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.mapel.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'required|string|max:50|unique:mapels',
        ], [
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
            'kode_mapel.required' => 'Kode mata pelajaran wajib diisi.',
            'kode_mapel.unique' => 'Kode mata pelajaran ini sudah digunakan.',
        ]);

        Mapel::create([
            'nama_mapel' => $request->nama_mapel,
            'kode_mapel' => strtoupper($request->kode_mapel),
        ]);

        return redirect()->route('admin.mapel.index')->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mapel $mapel)
    {
        return view('admin.mapel.edit', compact('mapel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mapel $mapel)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => ['required', 'string', 'max:50', Rule::unique('mapels')->ignore($mapel->id)],
        ], [
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
            'kode_mapel.required' => 'Kode mata pelajaran wajib diisi.',
            'kode_mapel.unique' => 'Kode mata pelajaran ini sudah digunakan.',
        ]);

        $mapel->update([
            'nama_mapel' => $request->nama_mapel,
            'kode_mapel' => strtoupper($request->kode_mapel),
        ]);

        return redirect()->route('admin.mapel.index')->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mapel $mapel)
    {
        $mapel->delete();
        return redirect()->route('admin.mapel.index')->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}
