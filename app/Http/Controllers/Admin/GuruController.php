<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::orderBy('nama', 'asc')->get();
        return view('admin.guru.index', compact('guru'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'nip'           => 'required|string|max:50|unique:gurus,nip',
            'mapel'         => 'required|string|max:100',
            'alamat'        => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp'         => 'nullable|string|max:15',
        ]);

        // Simpan data guru baru
        Guru::create($validated);

        return redirect()->route('admin.guru.index')
            ->with('success', '✅ Data guru berhasil ditambahkan.');
    }

    public function edit(Guru $guru)
    {
        // Tampilkan form edit
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        // Validasi input
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'nip'           => 'required|string|max:50|unique:gurus,nip,' . $guru->id,
            'mapel'         => 'required|string|max:100',
            'alamat'        => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp'         => 'nullable|string|max:15',
        ]);

        // Perbarui data guru
        $guru->update($validated);

        return redirect()->route('admin.guru.index')
            ->with('success', '✅ Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        // Hapus data guru
        $guru->delete();

        return redirect()->route('admin.guru.index')
            ->with('success', '✅ Data guru berhasil dihapus.');
    }
}
