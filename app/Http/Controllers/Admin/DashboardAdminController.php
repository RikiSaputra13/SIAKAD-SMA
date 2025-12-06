<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Absensi;


class DashboardAdminController extends Controller
{
    public function index()
    {
        $totalGuru = Guru::count();
        $totalSiswa = Siswa::count();
        $totalKelas = Kelas::count();
        $totalJadwal = Jadwal::count();
        $totalAbsensi = Absensi::count();
        

        return view('admin.dashboard', compact(
            'totalSiswa','totalGuru','totalKelas',
            'totalJadwal','totalAbsensi'
        ));
    }

        public function profile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

}
