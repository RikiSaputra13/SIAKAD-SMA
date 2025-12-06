<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SiswaUjianController extends Controller
{
    /**
     * Menampilkan daftar ujian harian yang tersedia untuk siswa
     */
   public function index(Request $request)
{
    $siswa = Auth::user()->siswa;

    // Cari tipe ujian dengan kode 'uh' (Ujian Harian) sebagai default
    $tipeUjianUH = \App\Models\TipeUjian::where('kode', 'uh')->first();

    // Jika tipe UH tidak ada, kembalikan koleksi kosong
    if (!$tipeUjianUH) {
        $ujian = collect();
        $tipeUjianOptions = \App\Models\TipeUjian::pluck('nama', 'id');
        return view('siswa.ujian.uh-index', compact('ujian', 'tipeUjianOptions'))->with('error', 'Tipe ujian "uh" tidak ditemukan.');
    }

    // Ambil filter dari request, jika tidak ada gunakan UH sebagai default
    $tipeUjianId = $request->input('tipe_ujian_id', $tipeUjianUH->id);

    $ujian = Ujian::where('kelas_id', $siswa->kelas_id)
        ->where('status', 'published')
        ->when($tipeUjianId, function ($query, $tipeUjianId) {
            return $query->where('tipe_ujian_id', $tipeUjianId);
        })
        ->with([
            'tipeUjian',
            'jawaban' => function ($query) use ($siswa) {
                $query->where('siswa_id', $siswa->id);
            }
        ])
        ->orderBy('waktu_mulai', 'desc')
        ->get();

    $tipeUjianOptions = \App\Models\TipeUjian::pluck('nama', 'id');

    return view('siswa.ujian.uh-index', compact('ujian', 'tipeUjianOptions', 'tipeUjianId'));
}

public function show($id)
{
    $siswa = Auth::user()->siswa;

    // Ambil ujian beserta relasi
    $ujian = Ujian::with([
        'tipeUjian',
        'pengumpulan' => function ($query) use ($siswa) {
            $query->where('siswa_id', $siswa->id);
        }
    ])->where('id', $id)
      ->where('kelas_id', $siswa->kelas_id)
      ->where('status', 'published')
      ->firstOrFail();

    // Cek apakah waktu ujian sudah lewat
    $sekarang = now();
    $bisaKumpul = $sekarang->between($ujian->waktu_mulai, $ujian->batas_pengumpulan);

    return view('siswa.ujian.uh-show', compact('ujian', 'siswa', 'bisaKumpul'));
}

public function submit(Request $request, $id)
{
    $siswa = Auth::user()->siswa;
    $ujian = Ujian::findOrFail($id);

    if (now()->greaterThan($ujian->batas_pengumpulan)) {
        return back()->with('error', 'Waktu pengumpulan sudah lewat.');
    }

    $request->validate([
        'berkas_jawaban' => 'required|file|mimes:pdf,doc,docx|max:2048',
        'catatan_siswa' => 'nullable|string'
    ]);

    $path = $request->file('berkas_jawaban')->store('jawaban_siswa', 'public');

    \App\Models\PengumpulanUjian::updateOrCreate(
        [
            'siswa_id' => $siswa->id,
            'ujian_id' => $ujian->id
        ],
        [
            'berkas_jawaban' => $path,
            'catatan_siswa' => $request->catatan_siswa,
            'waktu_pengumpulan' => now(),
            'status' => 'dikumpulkan'
        ]
    );

    return redirect()->route('siswa.ujian-harian.show', $ujian->id)
        ->with('success', 'Jawaban berhasil dikumpulkan!');
}


}