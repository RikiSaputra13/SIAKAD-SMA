<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\Pembayaran;

class AdminController extends Controller
{
    public function index()
    {
        $totalSiswa     = Siswa::count();
        $totalGuru      = Guru::count();
        $totalKelas     = Kelas::count();
        $totalJadwal    = Jadwal::count();
        $totalAbsensi   = Absensi::count();
        $totalPembayaran= Pembayaran::count();

        // Data chart: jumlah siswa per kelas
        $kelasLabels     = Kelas::pluck('nama_kelas');
        // Perbaikan di sini: Menggunakan 'siswas' sesuai dengan nama relasi di model Kelas
        $siswaPerKelas   = Kelas::withCount('siswas')->pluck('siswas_count');

        return view('admin.dashboard', compact(
            'totalSiswa', 'totalGuru', 'totalKelas', 'totalJadwal',
            'totalAbsensi', 'totalPembayaran', 'kelasLabels', 'siswaPerKelas'
        ));
    }
}
