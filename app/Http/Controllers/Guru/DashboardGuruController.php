<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\Absensi;

class DashboardGuruController extends Controller
{
    public function index()
    {
        // Ambil user guru dari Auth default
        $guru = Auth::user();
        $guruId = $guru->id;

        // Total kelas yang diampu guru
        $totalKelasDiampu = Kelas::where('wali_kelas_id', $guruId)->count();

        // Total jadwal mengajar guru
        $totalJadwalMengajar = Jadwal::where('id', $guruId)->count();

        // Total siswa di semua kelas yang diampu guru
        $kelasIds = Kelas::where('wali_kelas_id', $guruId)->pluck('id');
        $totalSiswa = Siswa::whereIn('kelas_id', $kelasIds)->count();

        // Total absensi hari ini yang diinput guru
        $today = now()->toDateString();
        $totalAbsensiHariIni = Absensi::where('siswa_id', $guruId)
            ->whereDate('tanggal', $today)
            ->count();

        // Jadwal mengajar hari ini
        $hariIni = now()->format('l'); // e.g. 'Monday'
        $jadwalHariIni = Jadwal::where('id', $guruId)
            ->where('hari', $hariIni)
            ->get();

        return view('guru.dashboard', compact(
            'totalKelasDiampu',
            'totalJadwalMengajar',
            'totalSiswa',
            'totalAbsensiHariIni',
            'jadwalHariIni'
        ));
    }
}