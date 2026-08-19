<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mapels = Mapel::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar mata pelajaran berhasil diambil.',
            'data' => $mapels
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'required|string|unique:mapels,kode_mapel|max:50',
        ], [
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
            'kode_mapel.required' => 'Kode mata pelajaran wajib diisi.',
            'kode_mapel.unique' => 'Kode mata pelajaran sudah digunakan.',
        ]);

        $mapel = Mapel::create([
            'nama_mapel' => $request->nama_mapel,
            'kode_mapel' => strtoupper($request->kode_mapel),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Mata pelajaran berhasil ditambahkan.',
            'data' => $mapel
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $mapel = Mapel::find($id);

        if (!$mapel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mata pelajaran tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Mata pelajaran berhasil ditemukan.',
            'data' => $mapel
        ], 200);
    }
}
