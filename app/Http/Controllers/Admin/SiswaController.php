<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    /**
     * Tampilkan daftar siswa.
     */
    public function index()
    {
        $siswas = Siswa::with(['kelas', 'user'])->get();
        return view('admin.siswa.index', compact('siswas'));
    }

    /**
     * Tampilkan formulir untuk membuat siswa baru.
     */
    public function create()
    {
        $kelas = Kelas::all();
        return view('admin.siswa.create', compact('kelas'));
    }

    /**
     * Simpan siswa baru ke dalam penyimpanan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'           => 'required|string|max:50',
            'email'          => 'required|string|email|max:100|unique:users',
            'password'       => 'required|string|min:8',
            'nis'            => 'required|digits:7|unique:siswas,nis',
            'kelas_id'       => 'required|exists:kelas,id',
            'alamat'         => 'nullable|string|max:255',
            'tlp_orang_tua'  => 'nullable|string|max:14',
            'no_hp'          => 'nullable|string|max:14',
            'jenis_kelamin'  => 'required|in:L,P',
            'tempat_lahir'   => 'nullable|string|max:50',
            'tanggal_lahir'  => 'nullable|date',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Upload foto jika ada
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('siswa', 'public');
            }

            // 1. Buat user baru
            $user = User::create([
                'name'     => $validated['nama'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'siswa',
            ]);

            // 2. Buat siswa baru
            Siswa::create([
                'user_id'       => $user->id,
                'nis'           => $validated['nis'],
                'nama'          => $validated['nama'],
                'kelas_id'      => $validated['kelas_id'],
                'alamat'        => $validated['alamat'] ?? null,
                'tlp_orang_tua' => $validated['tlp_orang_tua'] ?? null,
                'no_hp'         => $validated['no_hp'] ?? null,
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tempat_lahir'  => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'foto'          => $fotoPath,
            ]);

            DB::commit();

            return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tampilkan formulir edit siswa.
     */
    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::all();
        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }

    /**
     * Update siswa.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nis'           => 'required|digits:7|unique:siswas,nis,' . $siswa->id,
            'nama'          => 'required|string|max:50',
            'email'         => 'required|email|unique:users,email,' . $siswa->user->id,
            'kelas_id'      => 'required|exists:kelas,id',
            'alamat'        => 'nullable|string|max:255',
            'tlp_orang_tua' => 'nullable|string|max:14',
            'no_hp'         => 'nullable|string|max:14',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'nullable|string|max:50',
            'tanggal_lahir' => 'nullable|date',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Upload foto baru jika ada
            if ($request->hasFile('foto')) {
                // Hapus foto lama
                if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                    Storage::disk('public')->delete($siswa->foto);
                }
                $siswa->foto = $request->file('foto')->store('siswa', 'public');
            }

            // Update user
            $siswa->user->update([
                'name'  => $validated['nama'],
                'email' => $validated['email'],
            ]);

            // Update siswa
            $siswa->update([
                'nis'           => $validated['nis'],
                'nama'          => $validated['nama'],
                'kelas_id'      => $validated['kelas_id'],
                'alamat'        => $validated['alamat'] ?? null,
                'tlp_orang_tua' => $validated['tlp_orang_tua'] ?? null,
                'no_hp'         => $validated['no_hp'] ?? null,
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tempat_lahir'  => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'foto'          => $siswa->foto,
            ]);

            DB::commit();

            return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus siswa.
     */
    public function destroy(Siswa $siswa)
    {
        DB::beginTransaction();

        try {
            // Hapus foto dari storage
            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }

            // Hapus user
            $siswa->user->delete();

            // Hapus siswa
            $siswa->delete();

            DB::commit();

            return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
