<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with('user')->orderBy('nama', 'asc')->get();
        return view('admin.guru.index', compact('gurus'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|email|max:100|unique:users,email',
            'password'      => 'required|string|min:8',
            'nip'           => 'required|string|max:50|unique:gurus,nip',
            'mapel'         => 'required|string|max:100',
            'alamat'        => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp'         => 'nullable|string|max:15',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('guru', 'public');
            }

            // 1. Buat akun user
            $user = User::create([
                'name'     => $validated['nama'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'guru',
            ]);

            // 2. Buat data guru
            Guru::create([
                'user_id'       => $user->id,
                'nama'          => $validated['nama'],
                'nip'           => $validated['nip'],
                'mapel'         => $validated['mapel'],
                'alamat'        => $validated['alamat'] ?? null,
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'no_hp'         => $validated['no_hp'] ?? null,
                'foto'          => $fotoPath,
            ]);

            DB::commit();

            return redirect()->route('admin.guru.index')->with('success', '✅ Data guru berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Guru $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $guru->user->id,
            'nip'           => 'required|string|max:50|unique:gurus,nip,' . $guru->id,
            'mapel'         => 'required|string|max:100',
            'alamat'        => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp'         => 'nullable|string|max:15',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('foto')) {
                if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
                    Storage::disk('public')->delete($guru->foto);
                }
                $guru->foto = $request->file('foto')->store('guru', 'public');
            }

            // Update user
            $guru->user->update([
                'name'  => $validated['nama'],
                'email' => $validated['email'],
            ]);

            // Update data guru
            $guru->update([
                'nama'          => $validated['nama'],
                'nip'           => $validated['nip'],
                'mapel'         => $validated['mapel'],
                'alamat'        => $validated['alamat'] ?? null,
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'no_hp'         => $validated['no_hp'] ?? null,
                'foto'          => $guru->foto,
            ]);

            DB::commit();

            return redirect()->route('admin.guru.index')->with('success', '✅ Data guru berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Guru $guru)
    {
        DB::beginTransaction();

        try {
            if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
                Storage::disk('public')->delete($guru->foto);
            }

            $guru->user->delete();
            $guru->delete();

            DB::commit();

            return redirect()->route('admin.guru.index')->with('success', '✅ Data guru berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
