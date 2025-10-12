<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penilaian;
use App\Models\Ujian;
use App\Models\Kelas;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $guruId = auth()->user();
        
        // Ambil kelas yang diajar oleh guru ini
        $kelasOptions = Jadwal::where('guru_id', $guruId)
                            ->with('kelas')
                            ->get()
                            ->pluck('kelas.nama_kelas', 'kelas.id')
                            ->unique();

        // Ambil mata pelajaran yang diajar
        $mapelOptions = Jadwal::where('guru_id', $guruId)
                            ->distinct()
                            ->pluck('mata_pelajaran');

        // Query penilaian
        $query = Penilaian::with(['siswa', 'kelas'])
                        ->where('guru_id', $guruId);

        // Filter berdasarkan kelas
        if ($request->has('kelas_id') && $request->kelas_id != '') {
            $query->where('kelas_id', $request->kelas_id);
        }

        // Filter berdasarkan mata pelajaran
        if ($request->has('mata_pelajaran') && $request->mata_pelajaran != '') {
            $query->where('mata_pelajaran', $request->mata_pelajaran);
        }

        // Filter berdasarkan semester
        if ($request->has('semester') && $request->semester != '') {
            $query->where('semester', $request->semester);
        }

        // Filter berdasarkan tahun ajaran
        if ($request->has('tahun_ajaran') && $request->tahun_ajaran != '') {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        $penilaian = $query->orderBy('kelas_id')
                          ->orderBy('mata_pelajaran')
                          ->orderBy('siswa_id')
                          ->get();

        // Hitung statistik
        $statistik = [
            'total_siswa' => $penilaian->unique('siswa_id')->count(),
            'total_mapel' => $penilaian->unique('mata_pelajaran')->count(),
            'rata_rata' => $penilaian->avg('nilai_akhir') ?? 0,
            'tertinggi' => $penilaian->max('nilai_akhir') ?? 0,
            'terendah' => $penilaian->min('nilai_akhir') ?? 0,
        ];

        return view('guru.penilaian.list', compact(
            'penilaian', 
            'kelasOptions', 
            'mapelOptions',
            'statistik'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
//    public function create()
// {
//     // Ambil user login
//     $user = Auth::user();

//     // Ambil data guru berdasarkan user_id
//     $guru = \App\Models\Guru::where('user_id', $user->id)->first();

//     if (!$guru) {
//         return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
//     }

//     $guruId = $guru->id;

//     // Ambil data kelas dari jadwal guru
//     $kelasOptions = \App\Models\Jadwal::where('guru_id', $guruId)
//                         ->with('kelas')
//                         ->get()
//                         ->pluck('kelas.nama_kelas', 'kelas.id')
//                         ->unique();

//     // Ambil mata pelajaran dari jadwal guru
//     $mapelOptions = \App\Models\Jadwal::where('guru_id', $guruId)
//                         ->distinct()
//                         ->pluck('mata_pelajaran');

//     // Ambil siswa dari kelas yang diajar guru
//     $siswaOptions = \App\Models\Siswa::whereIn('kelas_id', $kelasOptions->keys())
//                         ->get()
//                         ->pluck('nama', 'id');

//     $tahunAjaran = now()->year;
//     $semesterOptions = [
//         '1' => 'Semester 1',
//         '2' => 'Semester 2',
//     ];

//     return view('guru.penilaian.create', compact(
//         'kelasOptions',
//         'mapelOptions',
//         'siswaOptions',
//         'tahunAjaran',
//         'semesterOptions'
//     ));
// }



public function create()
{
    try {
        // Ambil user login
        $user = Auth::user();

        // Ambil data guru berdasarkan user_id
        $guru = \App\Models\Guru::where('user_id', $user->id)->first();

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        $guruId = $guru->id;

        // Debug: Cek apakah guru memiliki jadwal
        Log::info("Guru ID: {$guruId}");

        // Ambil data kelas dari jadwal guru dengan handling yang lebih baik
        $kelasOptions = \App\Models\Jadwal::where('guru_id', $guruId)
                            ->with(['kelas' => function($query) {
                                $query->select('id', 'nama_kelas');
                            }])
                            ->get()
                            ->filter(function($jadwal) {
                                return $jadwal->kelas !== null;
                            })
                            ->mapWithKeys(function($jadwal) {
                                return [$jadwal->kelas->id => $jadwal->kelas->nama_kelas];
                            })
                            ->unique();

        // Debug: Cek hasil query kelas
        Log::info("Kelas Options: " . json_encode($kelasOptions));

        // Ambil mata pelajaran dari jadwal guru
        $mapelOptions = \App\Models\Jadwal::where('guru_id', $guruId)
                            ->whereNotNull('mata_pelajaran')
                            ->distinct()
                            ->pluck('mata_pelajaran')
                            ->filter()
                            ->values();

        // Debug: Cek hasil query mapel
        Log::info("Mapel Options: " . json_encode($mapelOptions));

        // Handle jika tidak ada data
        if ($kelasOptions->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada kelas yang diajar oleh guru ini.');
        }

        if ($mapelOptions->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada mata pelajaran yang diajar oleh guru ini.');
        }

        $tahunAjaran = now()->year;
        $semesterOptions = [
            '1' => 'Semester 1',
            '2' => 'Semester 2',
        ];

        return view('guru.penilaian.create', compact(
            'kelasOptions',
            'mapelOptions',
            'tahunAjaran',
            'semesterOptions'
        ));

    } catch (\Exception $e) {
        Log::error('Error in create method: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
    }
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran' => 'required|string|max:255',
            'tahun_ajaran' => 'required|integer',
            'semester' => 'required|in:1,2',
            'nilai_uh' => 'required|numeric|min:0|max:100',
            'nilai_uts' => 'required|numeric|min:0|max:100',
            'nilai_uas' => 'required|numeric|min:0|max:100',
            'nilai_tugas' => 'required|numeric|min:0|max:100',
            'nilai_praktik' => 'nullable|numeric|min:0|max:100',
            'deskripsi' => 'nullable|string'
        ]);

        // Hitung nilai akhir (contoh perhitungan)
        $nilaiAkhir = (
            ($request->nilai_uh * 0.2) +
            ($request->nilai_uts * 0.3) +
            ($request->nilai_uas * 0.4) +
            ($request->nilai_tugas * 0.1)
        );

        // Tentukan predikat
        $predikat = $this->getPredikat($nilaiAkhir);

        Penilaian::create([
            'siswa_id' => $request->siswa_id,
            'kelas_id' => $request->kelas_id,
            'guru_id' => auth()->user()->guru->id,
            'mata_pelajaran' => $request->mata_pelajaran,
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester' => $request->semester,
            'nilai_uh' => $request->nilai_uh,
            'nilai_uts' => $request->nilai_uts,
            'nilai_uas' => $request->nilai_uas,
            'nilai_tugas' => $request->nilai_tugas,
            'nilai_praktik' => $request->nilai_praktik ?? 0,
            'nilai_akhir' => $nilaiAkhir,
            'predikat' => $predikat,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('guru.penilaian.list')
                         ->with('success', 'Data penilaian berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $penilaian = Penilaian::with(['siswa', 'kelas', 'guru'])
                            ->where('id', $id)
                            ->where('guru_id', auth()->user()->guru->id)
                            ->firstOrFail();

        return view('guru.penilaian.show', compact('penilaian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $penilaian = Penilaian::where('id', $id)
                            ->where('guru_id', auth()->user()->guru->id)
                            ->firstOrFail();

        $guruId = auth()->user()->guru->id;
        $kelasOptions = Jadwal::where('guru_id', $guruId)
                            ->with('kelas')
                            ->get()
                            ->pluck('kelas.nama_kelas', 'kelas.id')
                            ->unique();

        $mapelOptions = Jadwal::where('guru_id', $guruId)
                            ->distinct()
                            ->pluck('mata_pelajaran');

        $siswaOptions = Siswa::where('kelas_id', $penilaian->kelas_id)
                           ->get()
                           ->pluck('nama', 'id');

        $semesterOptions = [
            '1' => 'Semester 1',
            '2' => 'Semester 2'
        ];

        return view('guru.penilaian.edit', compact(
            'penilaian',
            'kelasOptions',
            'mapelOptions',
            'siswaOptions',
            'semesterOptions'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $penilaian = Penilaian::where('id', $id)
                            ->where('guru_id', auth()->user()->guru->id)
                            ->firstOrFail();

        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran' => 'required|string|max:255',
            'tahun_ajaran' => 'required|integer',
            'semester' => 'required|in:1,2',
            'nilai_uh' => 'required|numeric|min:0|max:100',
            'nilai_uts' => 'required|numeric|min:0|max:100',
            'nilai_uas' => 'required|numeric|min:0|max:100',
            'nilai_tugas' => 'required|numeric|min:0|max:100',
            'nilai_praktik' => 'nullable|numeric|min:0|max:100',
            'deskripsi' => 'nullable|string'
        ]);

        // Hitung nilai akhir
        $nilaiAkhir = (
            ($request->nilai_uh * 0.2) +
            ($request->nilai_uts * 0.3) +
            ($request->nilai_uas * 0.4) +
            ($request->nilai_tugas * 0.1)
        );

        $predikat = $this->getPredikat($nilaiAkhir);

        $penilaian->update([
            'siswa_id' => $request->siswa_id,
            'kelas_id' => $request->kelas_id,
            'mata_pelajaran' => $request->mata_pelajaran,
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester' => $request->semester,
            'nilai_uh' => $request->nilai_uh,
            'nilai_uts' => $request->nilai_uts,
            'nilai_uas' => $request->nilai_uas,
            'nilai_tugas' => $request->nilai_tugas,
            'nilai_praktik' => $request->nilai_praktik ?? 0,
            'nilai_akhir' => $nilaiAkhir,
            'predikat' => $predikat,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('guru.penilaian.list')
                         ->with('success', 'Data penilaian berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $penilaian = Penilaian::where('id', $id)
                            ->where('guru_id', auth()->user()->guru->id)
                            ->firstOrFail();

        $penilaian->delete();

        return redirect()->route('guru.penilaian.list')
                         ->with('success', 'Data penilaian berhasil dihapus!');
    }
    /**
     * Get siswa by kelas for AJAX
     */
    public function getSiswaByKelas(Request $request)
    {
        $siswa = Siswa::where('kelas_id', $request->kelas_id)
                     ->get(['id', 'nama', 'nis']);

        return response()->json($siswa);
    }

    /**
     * Helper function to determine predikat
     */
    private function getPredikat($nilai)
    {
        if ($nilai >= 90) return 'A';
        if ($nilai >= 80) return 'B';
        if ($nilai >= 70) return 'C';
        if ($nilai >= 60) return 'D';
        return 'E';
    }


 public function getSiswa($kelasId)
{
    try {
        Log::info("getSiswa called with kelasId: {$kelasId}");

        // Validasi input
        if (!$kelasId || $kelasId == 'null') {
            return response()->json(['error' => 'Kelas ID tidak valid'], 400);
        }

        // Cek apakah kelas exists
        $kelas = \App\Models\Kelas::find($kelasId);
        if (!$kelas) {
            return response()->json(['error' => 'Kelas tidak ditemukan'], 404);
        }

        // Ambil semua siswa berdasarkan kelas_id
        $siswa = \App\Models\Siswa::where('kelas_id', $kelasId)
            ->select('id', 'nama', 'nis')
            ->orderBy('nama')
            ->get();

        Log::info("Siswa found: " . $siswa->count());

        // Format response
        $formattedSiswa = $siswa->map(function($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'nis' => $item->nis
            ];
        });

        return response()->json($formattedSiswa);

    } catch (\Exception $e) {
        Log::error('Error in getSiswa: ' . $e->getMessage());
        return response()->json(['error' => 'Terjadi kesalahan server: ' . $e->getMessage()], 500);
    }
}
}