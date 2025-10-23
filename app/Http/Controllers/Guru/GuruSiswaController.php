<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Absensi;
use Illuminate\Http\Request;

class GuruSiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with(['kelas', 'user'])->paginate(10); 
        return view('guru.siswa.index', compact('siswas'));
    }
    
    public function absensiSiswa()
    {
        $absensis = Absensi::with('siswa.kelas')->latest()->paginate(10);
        $kelas = Kelas::all(); 
        return view('guru.siswa.absensi', compact('absensis', 'kelas'));
    }
}