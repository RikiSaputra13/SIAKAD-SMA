<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Guru;
use Illuminate\Http\Request;

class DashboardGuruController extends Controller
{
    public function index()
    {
        // Ambil user guru dari Auth default
        $user = Auth::user();
        
        // Cari data guru berdasarkan user_id
        $guru = Guru::where('user_id', $user->id)->first();
        
        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        $guruId = $guru->id;

        // Total kelas yang diampu guru (sebagai wali kelas)
        $totalKelasDiampu = Kelas::where('wali_kelas_id', $guruId)->count();

        // Total jadwal mengajar guru - PERBAIKAN: gunakan guru_id bukan id
        $totalJadwalMengajar = Jadwal::where('guru_id', $guruId)->count();

        // Total siswa di semua kelas yang diampu guru sebagai wali kelas
        $kelasIds = Kelas::where('wali_kelas_id', $guruId)->pluck('id');
        $totalSiswa = Siswa::whereIn('kelas_id', $kelasIds)->count();

        // Total absensi hari ini yang diinput guru - PERBAIKAN: sesuaikan dengan relasi yang benar
        $today = now()->toDateString();
        $totalAbsensiHariIni = Absensi::whereDate('tanggal', $today)
            ->whereIn('siswa_id', function($query) use ($kelasIds) {
                $query->select('id')
                      ->from('siswas')
                      ->whereIn('kelas_id', $kelasIds);
            })
            ->count();

        // Jadwal mengajar hari ini - PERBAIKAN: gunakan guru_id dan konversi hari
        $hariIni = $this->getHariIndonesia(now()->format('l'));
        $jadwalHariIni = Jadwal::with(['kelas', 'guru'])
            ->where('guru_id', $guruId)
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai')
            ->get()
            ->map(function($jadwal) {
                return (object)[
                    'mapel' => $jadwal->mata_pelajaran,
                    'kelas' => $jadwal->kelas->nama_kelas,
                    'jam_mulai' => $jadwal->jam_mulai,
                    'jam_selesai' => $jadwal->jam_selesai
                ];
            });

        return view('guru.dashboard', compact(
            'totalKelasDiampu',
            'totalJadwalMengajar',
            'totalSiswa',
            'totalAbsensiHariIni',
            'jadwalHariIni'
        ));
    }

    /**
     * Konversi hari dari English ke Indonesia
     */
    private function getHariIndonesia($hariEnglish)
    {
        $hariMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];

        return $hariMap[$hariEnglish] ?? $hariEnglish;
    }

    public function jadwalGuru(Request $request) 
    {
        // Ambil guru yang sedang login
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();
        
        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        $query = Jadwal::with(['kelas', 'guru'])
                    ->where('guru_id', $guru->id);
        
        // Optional: Filter berdasarkan kelas jika diperlukan
        if ($request->has('kelas_id') && $request->kelas_id != '') {
            $query->where('kelas_id', $request->kelas_id);
        }
        
        $jadwals = $query->orderBy('hari')
                        ->orderBy('jam_mulai')
                        ->get();
        $kelas = Kelas::all();
        
        return view('guru.jadwal.index', compact('jadwals', 'kelas'));
    }
}