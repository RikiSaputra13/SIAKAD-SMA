<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ujian;
use App\Models\TipeUjian;
use App\Models\Jadwal;
use App\Models\PengumpulanUjian;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UjianHarianController extends Controller
{
    public function index(Request $request)
{
    Log::info('=== Memasuki method GuruPenilaianController@index ===');

    // Ambil data guru dari user yang sedang login
    $user = auth()->user();
    Log::info('User login:', ['id' => $user->id, 'role' => $user->role]);

    $guru = $user->guru;

    // Jika user login tidak terhubung dengan data guru
    if (!$guru) {
        Log::warning('User tidak memiliki data guru yang terkait.', ['user_id' => $user->id]);
        return redirect()->back()->with('error', 'Data guru tidak ditemukan untuk akun ini!');
    }

    $guruId = $guru->id;
    Log::info('Guru ditemukan:', ['guru_id' => $guruId, 'nama' => $guru->nama]);

    // Ambil tipe ujian harian
    $tipeUjianHarian = TipeUjian::where('kode', 'uh')->first();
    if (!$tipeUjianHarian) {
        Log::error('Tipe ujian harian (kode: uh) tidak ditemukan di database.');
        return redirect()->back()->with('error', 'Tipe ujian harian tidak ditemukan!');
    }
    Log::info('Tipe ujian harian ditemukan:', ['id' => $tipeUjianHarian->id]);

    // Query ujian harian berdasarkan guru login dan tipe ujian
    $query = Ujian::with(['kelas', 'tipeUjian'])
                  ->where('guru_id', $guruId)
                  ->where('tipe_ujian_id', $tipeUjianHarian->id);

    // Filter berdasarkan kelas
    if ($request->filled('kelas_id')) {
        $query->where('kelas_id', $request->kelas_id);
        Log::info('Filter kelas diterapkan:', ['kelas_id' => $request->kelas_id]);
    }

    // Filter berdasarkan status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
        Log::info('Filter status diterapkan:', ['status' => $request->status]);
    }

    // Ambil data ujian
    $ujian = $query->orderBy('created_at', 'desc')->get();
    Log::info('Jumlah ujian ditemukan:', ['total' => $ujian->count()]);

    // Ambil daftar kelas yang diajar guru ini
    $kelasOptions = Jadwal::where('guru_id', $guruId)
                        ->with('kelas')
                        ->get()
                        ->pluck('kelas.nama_kelas', 'kelas.id')
                        ->unique();

    Log::info('Jumlah kelas ditemukan untuk guru:', ['total_kelas' => $kelasOptions->count()]);

    // Hitung statistik ujian
    $statistik = [
        'total'      => $ujian->count(),
        'draft'      => $ujian->where('status', 'draft')->count(),
        'published'  => $ujian->where('status', 'published')->count(),
        'completed'  => $ujian->where('status', 'completed')->count(),
    ];

    Log::info('Statistik ujian:', $statistik);

    // Tampilkan ke view
    Log::info('Render view: guru.penilaian.uh-index');
    return view('guru.penilaian.uh-index', compact(
        'ujian',
        'kelasOptions',
        'statistik'
    ));
}

    /**
     * Show the form for creating a new ujian harian.
     */
    public function create()
    {
        $guruId = auth()->user()->guru->id;
        
        // Ambil tipe ujian harian
        $tipeUjianHarian = TipeUjian::where('kode', 'uh')->first();
        
        if (!$tipeUjianHarian) {
            return redirect()->back()->with('error', 'Tipe ujian harian tidak ditemukan!');
        }

        // Ambil data untuk dropdown
        $kelasOptions = Jadwal::where('guru_id', $guruId)
                            ->with('kelas')
                            ->get()
                            ->pluck('kelas.nama_kelas', 'kelas.id')
                            ->unique();

        $mapelOptions = Jadwal::where('guru_id', $guruId)
                            ->distinct()
                            ->pluck('mata_pelajaran');

        // Set waktu default
        $waktuDefault = [
            'mulai' => now()->format('Y-m-d\TH:i'),
            'selesai' => now()->addHours(2)->format('Y-m-d\TH:i'),
            'batas' => now()->addHours(3)->format('Y-m-d\TH:i')
        ];

        return view('guru.penilaian.uh-create', compact(
            'kelasOptions',
            'mapelOptions',
            'tipeUjianHarian',
            'waktuDefault'
        ));
    }

    /**
     * Store a newly created ujian harian.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul_ujian' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran' => 'required|string|max:255',
            'berkas_soal' => 'required|file|mimes:pdf|max:10240', // max 10MB
            'berkas_kunci_jawaban' => 'nullable|file|mimes:pdf|max:10240',
            'total_nilai' => 'required|integer|min:1|max:100',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'batas_pengumpulan' => 'nullable|date|after:waktu_mulai',
            'instruksi' => 'nullable|string',
            'deskripsi' => 'nullable|string'
        ]);

        // Ambil tipe ujian harian
        $tipeUjianHarian = TipeUjian::where('kode', 'uh')->first();

        // Upload berkas soal
        $berkasSoalPath = $request->file('berkas_soal')->store('ujian/harian/soal', 'public');
        
        $berkasKunciPath = null;
        if ($request->hasFile('berkas_kunci_jawaban')) {
            $berkasKunciPath = $request->file('berkas_kunci_jawaban')->store('ujian/harian/kunci-jawaban', 'public');
        }

        // Buat ujian harian
        $ujian = Ujian::create([
            'guru_id' => auth()->user()->guru->id,
            'kelas_id' => $request->kelas_id,
            'tipe_ujian_id' => $tipeUjianHarian->id,
            'mata_pelajaran' => $request->mata_pelajaran,
            'judul_ujian' => $request->judul_ujian,
            'deskripsi' => $request->deskripsi,
            'berkas_soal' => $berkasSoalPath,
            'berkas_kunci_jawaban' => $berkasKunciPath,
            'total_nilai' => $request->total_nilai,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'batas_pengumpulan' => $request->batas_pengumpulan,
            'instruksi' => $request->instruksi,
            'status' => 'draft',
            'is_active' => true
        ]);

        return redirect()->route('guru.penilaian.uh')
                         ->with('success', 'Ujian harian berhasil dibuat!');
    }

    /**
     * Display the specified ujian harian.
     */
    public function show(string $id)
    {
        $guruId = auth()->user()->guru->id;
        $ujian = Ujian::with(['kelas', 'tipeUjian', 'pengumpulan.siswa'])
                     ->where('id', $id)
                     ->where('guru_id', $guruId)
                     ->firstOrFail();

        // Hitung statistik pengumpulan
        $statistikPengumpulan = [
            'total_siswa' => $ujian->kelas->siswa->count() ?? 0,
            'sudah_dikumpulkan' => $ujian->pengumpulan->where('status', '!=', 'belum_dikumpulkan')->count(),
            'belum_dikumpulkan' => $ujian->pengumpulan->where('status', 'belum_dikumpulkan')->count(),
            'sudah_dinilai' => $ujian->pengumpulan->where('status', 'dinilai')->count(),
        ];

        return view('guru.penilaian.uh-show', compact('ujian', 'statistikPengumpulan'));
    }

    /**
     * Show the form for editing the specified ujian harian.
     */
    public function edit(string $id)
    {
        $guruId = auth()->user()->guru->id;
        $ujian = Ujian::with(['kelas', 'tipeUjian'])
                     ->where('id', $id)
                     ->where('guru_id', $guruId)
                     ->firstOrFail();

        // Pastikan ini ujian harian
        if ($ujian->tipeUjian->kode !== 'uh') {
            abort(404);
        }

        $kelasOptions = Jadwal::where('guru_id', $guruId)
                            ->with('kelas')
                            ->get()
                            ->pluck('kelas.nama_kelas', 'kelas.id')
                            ->unique();

        $mapelOptions = Jadwal::where('guru_id', $guruId)
                            ->distinct()
                            ->pluck('mata_pelajaran');

        return view('guru.penilaian.uh-edit', compact(
            'ujian',
            'kelasOptions',
            'mapelOptions'
        ));
    }

    /**
     * Update the specified ujian harian.
     */
    public function update(Request $request, string $id)
    {
        $guruId = auth()->user()->guru->id;
        $ujian = Ujian::where('id', $id)
                     ->where('guru_id', $guruId)
                     ->firstOrFail();

        $request->validate([
            'judul_ujian' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran' => 'required|string|max:255',
            'berkas_soal' => 'nullable|file|mimes:pdf|max:10240',
            'berkas_kunci_jawaban' => 'nullable|file|mimes:pdf|max:10240',
            'total_nilai' => 'required|integer|min:1|max:100',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'batas_pengumpulan' => 'nullable|date|after:waktu_mulai',
            'instruksi' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:draft,published,completed'
        ]);

        // Update berkas soal jika ada
        if ($request->hasFile('berkas_soal')) {
            // Hapus file lama
            if ($ujian->berkas_soal) {
                Storage::disk('public')->delete($ujian->berkas_soal);
            }
            $berkasSoalPath = $request->file('berkas_soal')->store('ujian/harian/soal', 'public');
        } else {
            $berkasSoalPath = $ujian->berkas_soal;
        }

        // Update berkas kunci jawaban jika ada
        if ($request->hasFile('berkas_kunci_jawaban')) {
            // Hapus file lama
            if ($ujian->berkas_kunci_jawaban) {
                Storage::disk('public')->delete($ujian->berkas_kunci_jawaban);
            }
            $berkasKunciPath = $request->file('berkas_kunci_jawaban')->store('ujian/harian/kunci-jawaban', 'public');
        } else {
            $berkasKunciPath = $ujian->berkas_kunci_jawaban;
        }

        $ujian->update([
            'kelas_id' => $request->kelas_id,
            'mata_pelajaran' => $request->mata_pelajaran,
            'judul_ujian' => $request->judul_ujian,
            'deskripsi' => $request->deskripsi,
            'berkas_soal' => $berkasSoalPath,
            'berkas_kunci_jawaban' => $berkasKunciPath,
            'total_nilai' => $request->total_nilai,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'batas_pengumpulan' => $request->batas_pengumpulan,
            'instruksi' => $request->instruksi,
            'status' => $request->status
        ]);

        return redirect()->route('guru.penilaian.uh')
                         ->with('success', 'Ujian harian berhasil diperbarui!');
    }

    /**
     * Remove the specified ujian harian.
     */
    public function destroy(string $id)
    {
        $guruId = auth()->user()->guru->id;
        $ujian = Ujian::where('id', $id)
                     ->where('guru_id', $guruId)
                     ->firstOrFail();

        // Hapus file yang terkait
        if ($ujian->berkas_soal) {
            Storage::disk('public')->delete($ujian->berkas_soal);
        }
        if ($ujian->berkas_kunci_jawaban) {
            Storage::disk('public')->delete($ujian->berkas_kunci_jawaban);
        }

        $ujian->delete();

        return redirect()->route('guru.penilaian.uh')
                         ->with('success', 'Ujian harian berhasil dihapus!');
    }

    /**
     * Publish ujian harian
     */
    public function publish(string $id)
    {
        $guruId = auth()->user()->guru->id;
        $ujian = Ujian::where('id', $id)
                     ->where('guru_id', $guruId)
                     ->firstOrFail();

        $ujian->update([
            'status' => 'published',
            'is_active' => true
        ]);

        return redirect()->back()->with('success', 'Ujian harian berhasil dipublish!');
    }

    /**
     * Download berkas soal
     */
    public function downloadSoal(string $id)
    {
        $guruId = auth()->user()->guru->id;
        $ujian = Ujian::where('id', $id)
                     ->where('guru_id', $guruId)
                     ->firstOrFail();

        if (!$ujian->berkas_soal) {
            return redirect()->back()->with('error', 'Berkas soal tidak ditemukan!');
        }

        return Storage::disk('public')->download($ujian->berkas_soal, 
            'Soal UH - ' . $ujian->judul_ujian . '.pdf');
    }
    public function showSoal(string $id)
{
    $guruId = auth()->user()->guru->id;
    $ujian = Ujian::where('id', $id)
                 ->where('guru_id', $guruId)
                 ->firstOrFail();

    if (!$ujian->berkas_soal) {
        return redirect()->back()->with('error', 'Berkas soal tidak ditemukan!');
    }

    // Ambil file path
    $filePath = storage_path('app/public/' . $ujian->berkas_soal);

    if (!file_exists($filePath)) {
        return redirect()->back()->with('error', 'File soal tidak ditemukan di server!');
    }

    // Tampilkan PDF di browser (inline)
    return response()->file($filePath, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="Soal UH - ' . $ujian->judul_ujian . '.pdf"'
    ]);
}

    /**
     * Download berkas kunci jawaban
     */
    public function downloadKunci(string $id)
    {
        $guruId = auth()->user()->guru->id;
        $ujian = Ujian::where('id', $id)
                     ->where('guru_id', $guruId)
                     ->firstOrFail();

        if (!$ujian->berkas_kunci_jawaban) {
            return redirect()->back()->with('error', 'Berkas kunci jawaban tidak ditemukan!');
        }

        return Storage::disk('public')->download($ujian->berkas_kunci_jawaban, 
            'Kunci Jawaban UH - ' . $ujian->judul_ujian . '.pdf');
    }


    // Tambahkan method ini di UjianHarianController

/**
 * Show student submissions for the exam
 */
/**
 * Show student submissions for the exam
 */
/**
 * Show student submissions for the exam
 */
// public function showSubmissions(string $id)
// {
//     $guruId = auth()->user()->guru->id;

//     // Ambil ujian beserta relasi
//     $ujian = Ujian::with(['kelas.siswas', 'tipeUjian', 'pengumpulan.siswa'])
//         ->where('id', $id)
//         ->where('guru_id', $guruId)
//         ->firstOrFail();

//     if (!$ujian->kelas) {
//         return redirect()->route('guru.penilaian.uh.index')
//                          ->with('error', 'Data kelas tidak ditemukan untuk ujian ini!');
//     }

//     // Gunakan helper dari model
//     $statistikPengumpulan = $ujian->getPengumpulanStats();

//     // Ambil daftar siswa (fallback aman)
//     $allStudents = $ujian->kelas->siswas ?? collect();

//     return view('guru.penilaian.uh-submissions', compact('ujian', 'statistikPengumpulan', 'allStudents'));
// }


public function showSubmissions(string $id)
{
    $guruId = auth()->user()->guru->id;

    // Ambil ujian beserta semua relasi penting
    $ujian = Ujian::with([
        'kelas.siswas',
        'tipeUjian',
        'pengumpulan.siswa'
    ])
    ->where('id', $id)
    ->where('guru_id', $guruId)
    ->firstOrFail();

    if (!$ujian->kelas) {
        return redirect()->route('guru.penilaian.uh.index')
                         ->with('error', 'Data kelas tidak ditemukan untuk ujian ini!');
    }

    // 🔹 Ambil data pengumpulan langsung dari tabel pengumpulan_ujian
    $pengumpulan = \App\Models\PengumpulanUjian::with('siswa')
        ->where('ujian_id', $ujian->id)
        ->get();

    // 🔹 Buat koleksi siswa dari kelas
    $semuaSiswa = $ujian->kelas->siswas ?? collect();

    // 🔹 Jadikan pengumpulan keyed by siswa_id agar mudah dicocokkan
    $pengumpulanMap = $pengumpulan->keyBy('siswa_id');

    // 🔹 Hitung statistik dinamis
    $totalSiswa = $semuaSiswa->count();

$sudahKumpul = $pengumpulan->whereNotNull('berkas_jawaban')->count();

$sudahDinilai = $pengumpulan->filter(function ($item) {
    return $item->nilai !== null && $item->nilai > 0;
})->count();

$belumDinilai = $pengumpulan->filter(function ($item) {
    return $item->nilai === null || $item->nilai <= 0;
})->count();

$belumKumpul = $totalSiswa - $sudahKumpul;


    $statistikPengumpulan = [
        'total_siswa' => $totalSiswa,
        'sudah_dikumpulkan' => $sudahKumpul,
        'belum_dikumpulkan' => $belumKumpul,
        'sudah_dinilai' => $sudahDinilai,
        'belum_dinilai' => $belumDinilai,
    ];

    return view('guru.penilaian.uh-submissions', compact(
        'ujian',
        'statistikPengumpulan',
        'semuaSiswa',
        'pengumpulanMap'
    ));
}



/**
 * Download student's submitted file
 */
public function downloadJawaban($ujianId, $pengumpulanId)
{
    $guruId = auth()->user()->guru->id;
    
    // Pastikan ujian milik guru yang login
    $ujian = Ujian::where('id', $ujianId)
                 ->where('guru_id', $guruId)
                 ->firstOrFail();

    $pengumpulan = PengumpulanUjian::where('id', $pengumpulanId)
                                  ->where('ujian_id', $ujianId)
                                  ->firstOrFail();

    if (!$pengumpulan->berkas_jawaban) {
        return redirect()->back()->with('error', 'Berkas jawaban tidak ditemukan!');
    }

    $fileName = 'Jawaban - ' . $pengumpulan->siswa->nama . ' - ' . $ujian->judul_ujian . '.pdf';
    
    return Storage::disk('public')->download($pengumpulan->berkas_jawaban, $fileName);
}

/**
 * View student's submitted file in browser
 */
public function showJawaban($ujianId, $pengumpulanId)
{
    $guruId = auth()->user()->guru->id;
    
    $ujian = Ujian::where('id', $ujianId)
                 ->where('guru_id', $guruId)
                 ->firstOrFail();

    $pengumpulan = PengumpulanUjian::where('id', $pengumpulanId)
                                  ->where('ujian_id', $ujianId)
                                  ->firstOrFail();

    if (!$pengumpulan->berkas_jawaban) {
        return redirect()->back()->with('error', 'Berkas jawaban tidak ditemukan!');
    }

    $filePath = storage_path('app/public/' . $pengumpulan->berkas_jawaban);

    if (!file_exists($filePath)) {
        return redirect()->back()->with('error', 'File jawaban tidak ditemukan di server!');
    }

    return response()->file($filePath, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="Jawaban - ' . $pengumpulan->siswa->nama . ' - ' . $ujian->judul_ujian . '.pdf"'
    ]);
}

/**
 * Update nilai for student submission
 */
public function updateNilai(Request $request, $ujianId, $pengumpulanId)
{
    $guruId = auth()->user()->guru->id;

    // Ambil ujian milik guru ini
    $ujian = Ujian::where('id', $ujianId)
                 ->where('guru_id', $guruId)
                 ->firstOrFail();

    // Pastikan pengumpulan milik ujian ini
    $pengumpulan = PengumpulanUjian::where('id', $pengumpulanId)
                                  ->where('ujian_id', $ujianId)
                                  ->firstOrFail();

    // Tentukan nilai maksimum yang valid (fallback ke total_nilai ujian atau 100)
    $maxNilai = $request->max_nilai ?? $ujian->total_nilai ?? 100;

    // Validasi input aman
    $request->validate([
        'nilai' => [
            'required',
            'numeric',
            'min:0',
            function ($attribute, $value, $fail) use ($maxNilai) {
                if ($value > $maxNilai) {
                    $fail("Nilai tidak boleh melebihi batas maksimum ($maxNilai).");
                }
            },
        ],
        'catatan_guru' => 'nullable|string|max:500',
    ]);

    // Update nilai pengumpulan
    $pengumpulan->update([
        'nilai' => $request->nilai,
        'catatan_guru' => $request->catatan_guru,
        'status' => $request->nilai > 0 ? 'dinilai' : 'dikumpulkan', // otomatis ubah status
    ]);

    return redirect()->back()->with('success', 'Nilai berhasil diperbarui!');
}

}