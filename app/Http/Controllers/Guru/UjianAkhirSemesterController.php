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

class UjianAkhirSemesterController extends Controller
{
    public function index(Request $request)
    {
        Log::info('=== Memasuki method UjianAkhirSemesterController@index ===');

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

        // Ambil tipe ujian UAS
        $tipeUjianUAS = TipeUjian::where('kode', 'pas')->first();
        if (!$tipeUjianUAS) {
            Log::error('Tipe ujian UAS (kode: pas) tidak ditemukan di database.');
            return redirect()->back()->with('error', 'Tipe ujian UAS tidak ditemukan!');
        }
        Log::info('Tipe ujian UAS ditemukan:', ['id' => $tipeUjianUAS->id]);

        // Query ujian UAS berdasarkan guru login dan tipe ujian
        $query = Ujian::with(['kelas', 'tipeUjian'])
                      ->where('guru_id', $guruId)
                      ->where('tipe_ujian_id', $tipeUjianUAS->id);

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
        Log::info('Jumlah ujian UAS ditemukan:', ['total' => $ujian->count()]);

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

        Log::info('Statistik ujian PAS:', $statistik);

        // Tampilkan ke view
        Log::info('Render view: guru.penilaian.uas-index');
        return view('guru.penilaian.uas-index', compact(
            'ujian',
            'kelasOptions',
            'statistik'
        ));
    }

    /**
     * Show the form for creating a new ujian UAS.
     */
    public function create()
    {
        $user = auth()->user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan untuk akun ini!');
        }

        $guruId = $guru->id;
        
        // Ambil tipe ujian UAS
        $tipeUjianUAS = TipeUjian::where('kode', 'pas')->first();
        
        if (!$tipeUjianUAS) {
            return redirect()->back()->with('error', 'Tipe ujian UAS tidak ditemukan!');
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

        // Set waktu default untuk UAS (biasanya paling lama)
        $waktuDefault = [
            'mulai' => now()->format('Y-m-d\TH:i'),
            'selesai' => now()->addHours(4)->format('Y-m-d\TH:i'), // 4 jam untuk UAS
            'batas' => now()->addHours(5)->format('Y-m-d\TH:i')
        ];

        return view('guru.penilaian.uas-create', compact(
            'kelasOptions',
            'mapelOptions',
            'tipeUjianUAS',
            'waktuDefault'
        ));
    }

    /**
     * Store a newly created ujian UAS.
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

        // Ambil tipe ujian UAS
        $tipeUjianUAS = TipeUjian::where('kode', 'pas')->first();

        // Upload berkas soal
        $berkasSoalPath = $request->file('berkas_soal')->store('ujian/uas/soal', 'public');
        
        $berkasKunciPath = null;
        if ($request->hasFile('berkas_kunci_jawaban')) {
            $berkasKunciPath = $request->file('berkas_kunci_jawaban')->store('ujian/uas/kunci-jawaban', 'public');
        }

        // Buat ujian UAS
        $ujian = Ujian::create([
            'guru_id' => $guru->id,
            'kelas_id' => $request->kelas_id,
            'tipe_ujian_id' => $tipeUjianUAS->id,
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

        return redirect()->route('guru.penilaian.uas')
                         ->with('success', 'Ujian Akhir Semester berhasil dibuat!');
    }

    /**
     * Display the specified ujian UAS.
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

        // Pastikan ini ujian UAS
        if ($ujian->tipeUjian->kode !== 'pas') {
            abort(404, 'Ujian bukan Ujian Akhir Semester');
        }

        // Hitung statistik pengumpulan
        $statistikPengumpulan = [
            'total_siswa' => $ujian->kelas->siswa->count() ?? 0,
            'sudah_dikumpulkan' => $ujian->pengumpulan->where('status', '!=', 'belum_dikumpulkan')->count(),
            'belum_dikumpulkan' => $ujian->pengumpulan->where('status', 'belum_dikumpulkan')->count(),
            'sudah_dinilai' => $ujian->pengumpulan->where('status', 'dinilai')->count(),
        ];

        return view('guru.penilaian.uas-show', compact('ujian', 'statistikPengumpulan'));
    }

    /**
     * Show the form for editing the specified ujian UAS.
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

        // Pastikan ini ujian PAS
        if ($ujian->tipeUjian->kode !== 'pas') {
            abort(404, 'Ujian bukan Ujian Akhir Semester');
        }

        $kelasOptions = Jadwal::where('guru_id', $guruId)
                            ->with('kelas')
                            ->get()
                            ->pluck('kelas.nama_kelas', 'kelas.id')
                            ->unique();

        $mapelOptions = Jadwal::where('guru_id', $guruId)
                            ->distinct()
                            ->pluck('mata_pelajaran');

        return view('guru.penilaian.uas-edit', compact(
            'ujian',
            'kelasOptions',
            'mapelOptions'
        ));
    }

    /**
     * Update the specified ujian UAS.
     */
    /**
     *
 * Update the specified ujian UAS.
 */

    public function update(Request $request, string $id)
{
    $guruId = auth()->user()->guru->id;
    $ujian = Ujian::where('id', $id)
                 ->where('guru_id', $guruId)
                 ->firstOrFail();

    // Pastikan ini ujian harian
    if ($ujian->tipeUjian->kode !== 'pas') {
        abort(404, 'Ujian bukan Ujian Akhir');
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
        $berkasSoalPath = $fileSoal->store('ujian/uas/soal', 'public');
        
        // Log untuk debugging
        \Log::info('Berkas soal UH diupdate', [
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
        $berkasKunciPath = $fileKunci->store('ujian/uas/kunci-jawaban', 'public');
        
        // Log untuk debugging
        \Log::info('Berkas kunci jawaban UAS diupdate', [
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
        \Log::info('Ujian Harian berhasil diupdate', [
            'ujian_id' => $ujian->id,
            'status' => $status,
            'action' => $request->action
        ]);

        $message = 'Ujian Harian berhasil diperbarui!';
        if ($status === 'published') {
            $message .= ' Ujian telah dipublish dan dapat diakses siswa.';
        } elseif ($status === 'draft') {
            $message .= ' Ujian disimpan sebagai draft.';
        }

        return redirect()->route('guru.penilaian.uas.index')
                         ->with('success', $message);

    } catch (\Exception $e) {
        \Log::error('Error updating Ujian Akhir: ' . $e->getMessage(), [
            'ujian_id' => $ujian->id,
            'error' => $e->getTraceAsString()
        ]);

        return redirect()->back()
                         ->with('error', 'Terjadi kesalahan saat memperbarui Ujian Harian: ' . $e->getMessage())
                         ->withInput();
    }
}

 

    /**
     * Remove the specified ujian UAS.
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

        // Pastikan ini ujian UAS
        if ($ujian->tipeUjian->kode !== 'pas') {
            abort(404, 'Ujian bukan Ujian Akhir Semester');
        }

        // Hapus file yang terkait
        if ($ujian->berkas_soal) {
            Storage::disk('public')->delete($ujian->berkas_soal);
        }
        if ($ujian->berkas_kunci_jawaban) {
            Storage::disk('public')->delete($ujian->berkas_kunci_jawaban);
        }

        $ujian->delete();

        return redirect()->route('guru.penilaian.uas')
                         ->with('success', 'Ujian Akhir Semester berhasil dihapus!');
    }

    /**
     * Publish ujian UAS
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

        // Pastikan ini ujian UAS
        if ($ujian->tipeUjian->kode !== 'pas') {
            abort(404, 'Ujian bukan Ujian Akhir Semester');
        }

        $ujian->update([
            'status' => 'published',
            'is_active' => true
        ]);

        return redirect()->back()->with('success', 'Ujian Akhir Semester berhasil dipublish!');
    }

    /**
     * Download berkas soal UAS
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
            'Soal UAS - ' . $ujian->judul_ujian . '.pdf');
    }

    /**
     * Download berkas kunci jawaban UAS
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
            'Kunci Jawaban PAS - ' . $ujian->judul_ujian . '.pdf');
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
        'Content-Disposition' => 'inline; filename="Soal UAS - ' . $ujian->judul_ujian . '.pdf"'
    ]);
}
}