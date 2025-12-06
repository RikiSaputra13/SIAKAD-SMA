<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ujian;
use App\Models\TipeUjian;
use App\Models\PengumpulanUjian;
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

        // Validasi
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
            // Hapus validasi status karena kita akan menentukannya berdasarkan action
        ]);

        // Tentukan status berdasarkan action
        $status = $ujian->status; // Default: tetap status saat ini
        
        if ($request->has('action')) {
            if ($request->action === 'publish') {
                $status = 'published';
            } elseif ($request->action === 'draft') {
                $status = 'draft';
            } elseif ($request->action === 'update') {
                $status = 'published'; // atau tetap status sebelumnya
            }
        }

        // Handle file upload untuk berkas soal
        $berkasSoalPath = $ujian->berkas_soal;
        if ($request->hasFile('berkas_soal')) {
            $fileSoal = $request->file('berkas_soal');
            
            // Validasi file soal
            if (!$fileSoal->isValid()) {
                return redirect()->back()->with('error', 'File soal tidak valid!');
            }

            // Hapus file lama jika ada
            if ($ujian->berkas_soal && Storage::disk('public')->exists($ujian->berkas_soal)) {
                Storage::disk('public')->delete($ujian->berkas_soal);
            }

            // Simpan file baru
            $berkasSoalPath = $fileSoal->store('ujian/uts/soal', 'public');
            
            // Log untuk debugging
            \Log::info('Berkas soal UTS diupdate', [
                'ujian_id' => $ujian->id,
                'file_path' => $berkasSoalPath,
                'file_size' => $fileSoal->getSize(),
                'original_name' => $fileSoal->getClientOriginalName()
            ]);
        }

        // Handle file upload untuk berkas kunci jawaban
        $berkasKunciPath = $ujian->berkas_kunci_jawaban;
        if ($request->hasFile('berkas_kunci_jawaban')) {
            $fileKunci = $request->file('berkas_kunci_jawaban');
            
            // Validasi file kunci jawaban
            if (!$fileKunci->isValid()) {
                return redirect()->back()->with('error', 'File kunci jawaban tidak valid!');
    }

            // Hapus file lama jika ada
            if ($ujian->berkas_kunci_jawaban && Storage::disk('public')->exists($ujian->berkas_kunci_jawaban)) {
                Storage::disk('public')->delete($ujian->berkas_kunci_jawaban);
            }

            // Simpan file baru
            $berkasKunciPath = $fileKunci->store('ujian/uts/kunci-jawaban', 'public');
            
            // Log untuk debugging
            \Log::info('Berkas kunci jawaban UTS diupdate', [
                'ujian_id' => $ujian->id,
                'file_path' => $berkasKunciPath,
                'file_size' => $fileKunci->getSize(),
                'original_name' => $fileKunci->getClientOriginalName()
            ]);
        }

        // Update data ujian
        try {
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
                'status' => $status
            ]);

            // Log keberhasilan update
            \Log::info('Ujian Tengah Semester berhasil diupdate', [
                'ujian_id' => $ujian->id,
                'status' => $status,
                'action' => $request->action
            ]);

            $message = 'Ujian Tengah Semester berhasil diperbarui!';
            if ($status === 'published') {
                $message .= ' Ujian telah dipublish dan dapat diakses siswa.';
            } elseif ($status === 'draft') {
                $message .= ' Ujian disimpan sebagai draft.';
            }

            return redirect()->route('guru.penilaian.uts.index')
                            ->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Error updating Ujian Tengah Semester: ' . $e->getMessage(), [
                'ujian_id' => $ujian->id,
                'error' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                            ->with('error', 'Terjadi kesalahan saat memperbarui Ujian Tengah Semester: ' . $e->getMessage())
                            ->withInput();
        }
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

        // Ambil ujian dan pastikan milik guru ini serta tipe ujian adalah UTS
        $ujian = Ujian::where('id', $id)
                    ->where('guru_id', $guruId)
                    ->whereHas('tipeUjian', function($q){
                        $q->where('kode', 'pts'); // Pastikan ini UTS
                    })
                    ->firstOrFail();

        // Pastikan file soal ada
        if (!$ujian->berkas_soal) {
            return redirect()->back()->with('error', 'Berkas soal UTS tidak ditemukan!');
        }

        $filePath = storage_path('app/public/' . $ujian->berkas_soal);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File soal UTS tidak ditemukan di server!');
        }

        // Tampilkan PDF langsung di browser
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Soal UTS - ' . $ujian->judul_ujian . '.pdf"'
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
        'Content-Disposition' => 'inline; filename="Jawaban - ' . $pengumpulan->siswa->nama . '.pdf"'
    ]);
}

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