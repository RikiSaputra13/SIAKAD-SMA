<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    
    public function index()
    {
        $kelas = Kelas::with(['guru'])->withCount('siswas')->get();
        return view('admin.kelas.index', compact('kelas'));
    }

    
    public function create()
    {
        $gurus = Guru::all();
        return view('admin.kelas.create', compact('gurus'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'wali_kelas_id' => 'required|exists:gurus,id', // Diperbarui
        ]);

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas_id' => $request->wali_kelas_id, // Diperbarui
        ]);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    
    public function show(Kelas $kelas)
    {
        // Metode ini dapat digunakan untuk menampilkan detail kelas
    }

    
    public function edit(Kelas $kelas)
    {
        $gurus = Guru::all();
        return view('admin.kelas.edit', compact('kelas', 'gurus'));
    }

    
    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'wali_kelas_id' => 'required|exists:gurus,id', 
        ]);

        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas_id' => $request->wali_kelas_id, 
        ]);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diupdate.');
    }


    
    public function destroy(Kelas $kelas)
    {
        $kelas->delete();

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus');
    }
}