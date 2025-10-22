<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ujian;
use App\Models\TipeUjian;
use App\Models\Jadwal;
use App\Models\Kelas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UjianTengahSemesterController extends Controller
{
    public function index(Request $request)
    {
        Log::info('=== Memasuki method UjianTengahSemesterController@index ===');

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

        // Ambil tipe ujian UTS
        $tipeUjianUTS = TipeUjian::where('kode', 'pts')->first();
        if (!$tipeUjianUTS) {
            Log::error('Tipe ujian UTS (kode: pts) tidak ditemukan di database.');
            return redirect()->back()->with('error', 'Tipe ujian UTS tidak ditemukan!');
        }
        Log::info('Tipe ujian UTS ditemukan:', ['id' => $tipeUjianUTS->id]);

        // Query ujian UTS berdasarkan guru login dan tipe ujian
        $query = Ujian::with(['kelas', 'tipeUjian'])
                      ->where('guru_id', $guruId)
                      ->where('tipe_ujian_id', $tipeUjianUTS->id);

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
        Log::info('Jumlah ujian UTS ditemukan:', ['total' => $ujian->count()]);

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

        Log::info('Statistik ujian UTS:', $statistik);

        // Tampilkan ke view
        Log::info('Render view: guru.penilaian.uts-index');
        return view('guru.penilaian.uts-index', compact(
            'ujian',
            'kelasOptions',
            'statistik'
        ));
    }

    /**
     * Show the form for creating a new ujian UTS.
     */
    public function create()
    {
        $user = auth()->user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan untuk akun ini!');
        }

        $guruId = $guru->id;
        
        // Ambil tipe ujian UTS
        $tipeUjianUTS = TipeUjian::where('kode', 'pts')->first();
        
        if (!$tipeUjianUTS) {
            return redirect()->back()->with('error', 'Tipe ujian UTS tidak ditemukan!');
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

        // Set waktu default untuk UTS (biasanya lebih lama dari UH)
        $waktuDefault = [
            'mulai' => now()->format('Y-m-d\TH:i'),
            'selesai' => now()->addHours(3)->format('Y-m-d\TH:i'), // 3 jam untuk UTS
            'batas' => now()->addHours(4)->format('Y-m-d\TH:i')
        ];

        return view('guru.penilaian.uts-create', compact(
            'kelasOptions',
            'mapelOptions',
            'tipeUjianUTS',
            'waktuDefault'
        ));
    }

    /**
     * Store a newly created ujian UTS.
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

        $user = auth()->user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan untuk akun ini!');
        }

        // Ambil tipe ujian UTS
        $tipeUjianUTS = TipeUjian::where('kode', 'pts')->first();

        // Upload berkas soal
        $berkasSoalPath = $request->file('berkas_soal')->store('ujian/uts/soal', 'public');
        
        $berkasKunciPath = null;
        if ($request->hasFile('berkas_kunci_jawaban')) {
            $berkasKunciPath = $request->file('berkas_kunci_jawaban')->store('ujian/uts/kunci-jawaban', 'public');
        }

        // Buat ujian UTS
        $ujian = Ujian::create([
            'guru_id' => $guru->id,
            'kelas_id' => $request->kelas_id,
            'tipe_ujian_id' => $tipeUjianUTS->id,
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

        return redirect()->route('guru.penilaian.uts')
                         ->with('success', 'Ujian Tengah Semester berhasil dibuat!');
    }

    /**
     * Display the specified ujian UTS.
     */
    public function show(string $id)
    {
        $user = auth()->user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan untuk akun ini!');
        }

        $guruId = $guru->id;
        $ujian = Ujian::with(['kelas', 'tipeUjian', 'pengumpulan.siswa'])
                     ->where('id', $id)
                     ->where('guru_id', $guruId)
                     ->firstOrFail();

        // Pastikan ini ujian UTS
        if ($ujian->tipeUjian->kode !== 'pts') {
            abort(404, 'Ujian bukan Ujian Tengah Semester');
        }

        // Hitung statistik pengumpulan
        $statistikPengumpulan = [
            'total_siswa' => $ujian->kelas->siswa->count() ?? 0,
            'sudah_dikumpulkan' => $ujian->pengumpulan->where('status', '!=', 'belum_dikumpulkan')->count(),
            'belum_dikumpulkan' => $ujian->pengumpulan->where('status', 'belum_dikumpulkan')->count(),
            'sudah_dinilai' => $ujian->pengumpulan->where('status', 'dinilai')->count(),
        ];

        return view('guru.penilaian.uts-show', compact('ujian', 'statistikPengumpulan'));
    }

    /**
     * Show the form for editing the specified ujian UTS.
     */
    public function edit(string $id)
    {
        $user = auth()->user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan untuk akun ini!');
        }

        $guruId = $guru->id;
        $ujian = Ujian::with(['kelas', 'tipeUjian'])
                     ->where('id', $id)
                     ->where('guru_id', $guruId)
                     ->firstOrFail();

        // Pastikan ini ujian UTS
        if ($ujian->tipeUjian->kode !== 'pts') {
            abort(404, 'Ujian bukan Ujian Tengah Semester');
        }

        $kelasOptions = Jadwal::where('guru_id', $guruId)
                            ->with('kelas')
                            ->get()
                            ->pluck('kelas.nama_kelas', 'kelas.id')
                            ->unique();

        $mapelOptions = Jadwal::where('guru_id', $guruId)
                            ->distinct()
                            ->pluck('mata_pelajaran');

        return view('guru.penilaian.uts-edit', compact(
            'ujian',
            'kelasOptions',
            'mapelOptions'
        ));
    }

    /**
     * Update the specified ujian UTS.
     */
    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan untuk akun ini!');
        }

        $guruId = $guru->id;
        $ujian = Ujian::where('id', $id)
                     ->where('guru_id', $guruId)
                     ->firstOrFail();

        // Pastikan ini ujian UTS
        if ($ujian->tipeUjian->kode !== 'pts') {
            abort(404, 'Ujian bukan Ujian Tengah Semester');
        }

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
            $berkasSoalPath = $request->file('berkas_soal')->store('ujian/uts/soal', 'public');
        } else {
            $berkasSoalPath = $ujian->berkas_soal;
        }

        // Update berkas kunci jawaban jika ada
        if ($request->hasFile('berkas_kunci_jawaban')) {
            // Hapus file lama
            if ($ujian->berkas_kunci_jawaban) {
                Storage::disk('public')->delete($ujian->berkas_kunci_jawaban);
            }
            $berkasKunciPath = $request->file('berkas_kunci_jawaban')->store('ujian/uts/kunci-jawaban', 'public');
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

        return redirect()->route('guru.penilaian.uts')
                         ->with('success', 'Ujian Tengah Semester berhasil diperbarui!');
    }

    /**
     * Remove the specified ujian UTS.
     */
    public function destroy(string $id)
    {
        $user = auth()->user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan untuk akun ini!');
        }

        $guruId = $guru->id;
        $ujian = Ujian::where('id', $id)
                     ->where('guru_id', $guruId)
                     ->firstOrFail();

        // Pastikan ini ujian UTS
        if ($ujian->tipeUjian->kode !== 'pts') {
            abort(404, 'Ujian bukan Ujian Tengah Semester');
        }

        // Hapus file yang terkait
        if ($ujian->berkas_soal) {
            Storage::disk('public')->delete($ujian->berkas_soal);
        }
        if ($ujian->berkas_kunci_jawaban) {
            Storage::disk('public')->delete($ujian->berkas_kunci_jawaban);
        }

        $ujian->delete();

        return redirect()->route('guru.penilaian.uts')
                         ->with('success', 'Ujian Tengah Semester berhasil dihapus!');
    }

    /**
     * Publish ujian UTS
     */
    public function publish(string $id)
    {
        $user = auth()->user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan untuk akun ini!');
        }

        $guruId = $guru->id;
        $ujian = Ujian::where('id', $id)
                     ->where('guru_id', $guruId)
                     ->firstOrFail();

        // Pastikan ini ujian UTS
        if ($ujian->tipeUjian->kode !== 'pts') {
            abort(404, 'Ujian bukan Ujian Tengah Semester');
        }

        $ujian->update([
            'status' => 'published',
            'is_active' => true
        ]);

        return redirect()->back()->with('success', 'Ujian Tengah Semester berhasil dipublish!');
    }

    /**
     * Download berkas soal UTS
     */
    public function downloadSoal(string $id)
    {
        $user = auth()->user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan untuk akun ini!');
        }

        $guruId = $guru->id;
        $ujian = Ujian::where('id', $id)
                     ->where('guru_id', $guruId)
                     ->firstOrFail();

        if (!$ujian->berkas_soal) {
            return redirect()->back()->with('error', 'Berkas soal tidak ditemukan!');
        }

        return Storage::disk('public')->download($ujian->berkas_soal, 
            'Soal UTS - ' . $ujian->judul_ujian . '.pdf');
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
     * Download berkas kunci jawaban UTS
     */
    public function downloadKunci(string $id)
    {
        $user = auth()->user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan untuk akun ini!');
        }

        $guruId = $guru->id;
        $ujian = Ujian::where('id', $id)
                     ->where('guru_id', $guruId)
                     ->firstOrFail();

        if (!$ujian->berkas_kunci_jawaban) {
            return redirect()->back()->with('error', 'Berkas kunci jawaban tidak ditemukan!');
        }

        return Storage::disk('public')->download($ujian->berkas_kunci_jawaban, 
            'Kunci Jawaban UTS - ' . $ujian->judul_ujian . '.pdf');
    }


    public function showSubmissions(string $id)
{
    $guruId = auth()->user()->guru->id;

    $ujian = Ujian::with([
        'kelas.siswas',
        'tipeUjian',
        'pengumpulan.siswa'
    ])
    ->where('id', $id)
    ->where('guru_id', $guruId)
    ->firstOrFail();

    if (!$ujian->kelas) {
        return redirect()->route('guru.penilaian.uts.index')
                         ->with('error', 'Data kelas tidak ditemukan untuk ujian ini!');
    }

    $pengumpulan = \App\Models\PengumpulanUjian::with('siswa')
        ->where('ujian_id', $ujian->id)
        ->get();

    $semuaSiswa = $ujian->kelas->siswas ?? collect();

    $pengumpulanMap = $pengumpulan->keyBy('siswa_id');

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

    return view('guru.penilaian.uts-submission', compact(
        'ujian',
        'statistikPengumpulan',
        'semuaSiswa',
        'pengumpulanMap'
    ));
}
}