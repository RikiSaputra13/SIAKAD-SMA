<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request) {
        $query = Jadwal::with(['guru', 'kelas']);
        
        // Filter berdasarkan kelas
        if ($request->has('kelas_id') && $request->kelas_id != '') {
            $query->where('kelas_id', $request->kelas_id);
        }
        
        $jadwals = $query->get();
        $kelas = Kelas::all();
        
        return view('admin.jadwal.index', compact('jadwals', 'kelas'));
    }

    public function create() {
        $guru = Guru::all();
        $kelas = Kelas::all();
        return view('admin.jadwal.create', compact('guru','kelas'));
    }

    public function store(Request $request) {
        Jadwal::create($request->all());
        return redirect()->route('admin.jadwal.index')->with('success','Jadwal berhasil ditambahkan');
    }

    public function edit(Jadwal $jadwal) {
        $guru = Guru::all();
        $kelas = Kelas::all();
        return view('admin.jadwal.edit', compact('jadwal','guru','kelas'));
    }

    public function update(Request $request, Jadwal $jadwal) {
        $jadwal->update($request->all());
        return redirect()->route('admin.jadwal.index')->with('success','Jadwal berhasil diupdate');
    }

    public function destroy(Jadwal $jadwal) {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success','Jadwal berhasil dihapus');
    }
}