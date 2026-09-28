<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class AdminPenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $role    = $request->get('role', '');
        $kelasId = $request->get('kelas_id', '');
        $search  = $request->get('search', '');

        $query = User::with('kelas')->latest();

        if ($role && in_array($role, ['admin', 'guru', 'siswa'])) {
            $query->where('role', $role);
        }

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();

        $counts = [
            'all'   => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'guru'  => User::where('role', 'guru')->count(),
            'siswa' => User::where('role', 'siswa')->count(),
        ];

        $kelass = Kelas::orderBy('nama_kelas')->get();

        return view('admin.pengguna.index', compact('users', 'counts', 'role', 'kelasId', 'search', 'kelass'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelass = Kelas::orderBy('nama_kelas')->get();
        return view('admin.pengguna.create', compact('kelass'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'username'   => 'required|string|max:255|unique:users',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:8|confirmed',
            'role'       => 'required|in:admin,guru,siswa',
            'nis'        => 'nullable|required_if:role,siswa|string|max:50|unique:users',
            'kelas_id'   => 'nullable|exists:kelas,id',
            'class_name' => 'nullable|string|max:100',
            'phone'      => 'nullable|string|max:20',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'username.unique'    => 'Username sudah terpakai.',
            'email.unique'       => 'E-Mail sudah terdaftar.',
            'password.min'       => 'Password minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'nis.required_if'    => 'NIS (Nomor Induk Siswa) wajib diisi untuk siswa.',
            'nis.unique'         => 'NIS sudah digunakan oleh siswa lain.',
        ]);

        $className = $request->class_name;
        if ($request->kelas_id) {
            $kelas = Kelas::find($request->kelas_id);
            if ($kelas) {
                $className = $kelas->nama_kelas;
            }
        }

        $user = User::create([
            'name'              => $request->name,
            'username'          => $request->username,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'role'              => $request->role,
            'nis'               => $request->role === 'siswa' ? $request->nis : null,
            'kelas_id'          => $request->role === 'siswa' ? $request->kelas_id : null,
            'class_name'        => $request->role === 'siswa' ? $className : null,
            'phone'             => $request->phone,
            'email_verified_at' => now(),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $pengguna)
    {
        $kelass = Kelas::orderBy('nama_kelas')->get();
        return view('admin.pengguna.edit', ['user' => $pengguna, 'kelass' => $kelass]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $pengguna)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'username'   => ['required', 'string', 'max:255', Rule::unique('users')->ignore($pengguna->id)],
            'email'      => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($pengguna->id)],
            'password'   => 'nullable|string|min:8|confirmed',
            'role'       => 'required|in:admin,guru,siswa',
            'nis'        => ['nullable', 'required_if:role,siswa', 'string', 'max:50', Rule::unique('users')->ignore($pengguna->id)],
            'kelas_id'   => 'nullable|exists:kelas,id',
            'class_name' => 'nullable|string|max:100',
            'phone'      => 'nullable|string|max:20',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'username.unique'    => 'Username sudah terpakai.',
            'email.unique'       => 'E-Mail sudah terdaftar.',
            'password.min'       => 'Password minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'nis.required_if'    => 'NIS (Nomor Induk Siswa) wajib diisi untuk siswa.',
            'nis.unique'         => 'NIS sudah digunakan oleh siswa lain.',
        ]);

        $className = $request->class_name;
        if ($request->kelas_id) {
            $kelas = Kelas::find($request->kelas_id);
            if ($kelas) {
                $className = $kelas->nama_kelas;
            }
        }

        $data = [
            'name'       => $request->name,
            'username'   => $request->username,
            'email'      => $request->email,
            'role'       => $request->role,
            'nis'        => $request->role === 'siswa' ? $request->nis : null,
            'kelas_id'   => $request->role === 'siswa' ? $request->kelas_id : null,
            'class_name' => $request->role === 'siswa' ? $className : null,
            'phone'      => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pengguna->update($data);
        $pengguna->syncRoles([$request->role]);

        return redirect()->route('admin.pengguna.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $pengguna)
    {
        $pengguna->delete();
        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Import Data Pengguna dari File Excel/CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'Pilih file Excel/CSV terlebih dahulu.',
            'file.mimes'    => 'Format file harus berupa Excel (.xlsx, .xls) atau CSV (.csv).',
            'file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\UserImport, $request->file('file'));
            return redirect()->route('admin.pengguna.index')->with('success', 'Data pengguna berhasil diimpor dari Excel.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * Unduh Template Excel Data Pengguna.
     */
    public function downloadTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\UserTemplateExport, 'template-import-pengguna.xlsx');
    }
}
