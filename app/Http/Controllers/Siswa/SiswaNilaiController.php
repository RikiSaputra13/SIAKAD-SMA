<?php

namespace App\Http\Controllers\Siswa;

use App\Models\Penilaian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SiswaNilaiController extends Controller
{
    public function index(Request $request)
    {
        $siswaId = auth()->user()->siswa->id;
        
        // Query nilai untuk siswa ini
        $query = Penilaian::with(['guru', 'kelas'])
                    ->where('siswa_id', $siswaId);

        // Filter semester
        if ($request->has('semester') && $request->semester != '') {
            $query->where('semester', $request->semester);
        }

        // Filter tahun ajaran
        if ($request->has('tahun_ajaran') && $request->tahun_ajaran != '') {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        // Filter status (tuntas/belum tuntas)
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'tuntas') {
                $query->where('nilai_akhir', '>=', 75);
            } elseif ($request->status == 'belum_tuntas') {
                $query->where('nilai_akhir', '<', 75);
            }
        }

        $nilai = $query->orderBy('mata_pelajaran')
                      ->orderBy('tahun_ajaran', 'desc')
                      ->orderBy('semester', 'desc')
                      ->get();

        // Hitung statistik
        $totalMapel = $nilai->count();
        $mapelTuntas = $nilai->where('nilai_akhir', '>=', 75)->count();
        $mapelBelumTuntas = $nilai->where('nilai_akhir', '<', 75)->count();
        $mapelSedang = $nilai->whereBetween('nilai_akhir', [60, 74])->count();

        $statistik = [
            'total_mapel' => $totalMapel,
            'mapel_tuntas' => $mapelTuntas,
            'mapel_belum_tuntas' => $mapelBelumTuntas,
            'mapel_sedang' => $mapelSedang,
            'persentase_tuntas' => $totalMapel > 0 ? round(($mapelTuntas / $totalMapel) * 100) : 0,
            'rata_rata' => $totalMapel > 0 ? number_format($nilai->avg('nilai_akhir'), 1) : 0,
            'nilai_tertinggi' => $totalMapel > 0 ? number_format($nilai->max('nilai_akhir'), 1) : 0,
            'nilai_terendah' => $totalMapel > 0 ? number_format($nilai->min('nilai_akhir'), 1) : 0,
        ];

        // Data untuk filter
        $tahunAjaranOptions = Penilaian::where('siswa_id', $siswaId)
                                    ->distinct()
                                    ->pluck('tahun_ajaran')
                                    ->sortDesc();

        $semesterAktif = '1'; // Bisa disesuaikan dengan logic semester aktif
        $tahunAjaranAktif = date('Y');

        return view('siswa.penilaian.index', compact(
            'nilai',
            'statistik',
            'tahunAjaranOptions',
            'semesterAktif',
            'tahunAjaranAktif'
        ));
    }
}