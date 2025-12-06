<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Token;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

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

        // PERBAIKAN: Total kelas yang diampu guru (baik sebagai wali kelas maupun yang mengajar)
        $kelasIdsFromJadwal = Jadwal::where('guru_id', $guruId)->pluck('kelas_id')->unique();
        $totalKelasDiampu = $kelasIdsFromJadwal->count();

        // Total jadwal mengajar guru
        $totalJadwalMengajar = Jadwal::where('guru_id', $guruId)->count();

        // PERBAIKAN: Total siswa di semua kelas yang diajar guru
        $totalSiswa = Siswa::whereIn('kelas_id', $kelasIdsFromJadwal)->count();

        // Total absensi hari ini yang diinput guru
        $today = now()->toDateString();
        $totalAbsensiHariIni = Absensi::whereDate('tanggal', $today)
            ->whereIn('siswa_id', function($query) use ($kelasIdsFromJadwal) {
                $query->select('id')
                      ->from('siswas')
                      ->whereIn('kelas_id', $kelasIdsFromJadwal);
            })
            ->count();

        // Jadwal mengajar hari ini
        $hariIni = $this->getHariIndonesia(now()->format('l'));
        $jadwalHariIni = Jadwal::with(['kelas', 'guru'])
            ->where('guru_id', $guruId)
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai')
            ->get();

        // Token aktif hari ini
        $activeTokens = Token::with(['kelas', 'absensi'])
            ->where('guru_id', $guruId)
            ->whereDate('attendance_date', today())
            ->where('expired_at', '>', now())
            ->where('is_used', false)
            ->get();

        // PERBAIKAN: Ambil data untuk dropdown mata pelajaran dan kelas
        $mataPelajaranOptions = Jadwal::where('guru_id', $guruId)
            ->distinct()
            ->pluck('mata_pelajaran')
            ->filter()
            ->values();

        $kelasOptions = Kelas::whereIn('id', $kelasIdsFromJadwal)->get();

        return view('guru.dashboard', compact(
            'totalKelasDiampu',
            'totalJadwalMengajar',
            'totalSiswa',
            'totalAbsensiHariIni',
            'jadwalHariIni',
            'activeTokens',
            'guru',
            'mataPelajaranOptions', // PERBAIKAN: Kirim data mata pelajaran
            'kelasOptions' // PERBAIKAN: Kirim data kelas
        ));
    }

    /**
     * Generate token absensi untuk guru - VERSI DIPERBAIKI
     */
    public function generateToken(Request $request)
    {
        $request->validate([
            'mata_pelajaran' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required'
        ]);

        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        // PERBAIKAN: Validasi lebih longgar untuk pengecekan jadwal
        $jadwal = Jadwal::where('guru_id', $guru->id)
                        ->where('mata_pelajaran', $request->mata_pelajaran)
                        ->where('kelas_id', $request->kelas_id)
                        ->first();

        // Jika tidak ditemukan jadwal spesifik, tetap izinkan buat token
        // dengan catatan guru mengajar di kelas tersebut
        $guruMengajarDiKelas = Jadwal::where('guru_id', $guru->id)
                                    ->where('kelas_id', $request->kelas_id)
                                    ->exists();

        if (!$guruMengajarDiKelas) {
            return back()->with('error', 'Anda tidak mengajar di kelas tersebut!');
        }

        // Generate token baru
        $token = Str::upper(Str::random(6));
        $expiredAt = Carbon::now()->addHours(1);

        // PERBAIKAN: Hapus field yang tidak ada di tabel
        $tokenRecord = Token::create([
            'guru_id' => $guru->id,
            'kelas_id' => $request->kelas_id,
            'mata_pelajaran' => $request->mata_pelajaran,
            'token_kode' => $token,
            'expired_at' => $expiredAt,
            'attendance_date' => today(),
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'is_used' => false
        ]);

        return redirect()->route('guru.dashboard')
            ->with('success_token', 'Token absensi berhasil dibuat!')
            ->with('current_token', $token)
            ->with('token_expired_at', $expiredAt->format('H:i:s'))
            ->with('mapel', $request->mata_pelajaran)
            ->with('kelas', $tokenRecord->kelas->nama_kelas ?? 'Kelas');
    }

    /**
     * Hapus token absensi
     */
    public function deleteToken($id)
    {
        try {
            $user = Auth::user();
            $guru = Guru::where('user_id', $user->id)->first();

            if (!$guru) {
                return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
            }

            $token = Token::where('id', $id)
                        ->where('guru_id', $guru->id)
                        ->first();

            if (!$token) {
                return redirect()->back()->with('error', 'Token tidak ditemukan atau tidak memiliki akses.');
            }

            $token->delete();

            return redirect()->back()->with('success', 'Token berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus token: ' . $e->getMessage());
        }
    }

    /**
     * Get active tokens for AJAX request - VERSI DIPERBAIKI
     */
    public function getActiveTokens()
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();

        if (!$guru) {
            return response()->json([]);
        }

        $tokens = Token::with(['kelas', 'absensi'])
                    ->where('guru_id', $guru->id)
                    ->whereDate('attendance_date', today())
                    ->where('expired_at', '>', now())
                    ->where('is_used', false)
                    ->get()
                    ->map(function($token) {
                        return [
                            'id' => $token->id,
                            'token' => $token->token_kode,
                            'mapel' => $token->mata_pelajaran ?? 'Mata Pelajaran', // PERBAIKAN: Ambil dari field yang disimpan
                            'kelas' => $token->kelas->nama_kelas ?? 'Kelas',
                            'jam_mulai' => $token->jam_mulai,
                            'jam_selesai' => $token->jam_selesai,
                            'expired_at' => $token->expired_at->format('H:i:s'),
                            'absensi_count' => $token->absensi->count()
                        ];
                    });

        return response()->json($tokens);
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

    /**
     * Get mata pelajaran by kelas - UNTUK AJAX
     */
    public function getMapelByKelas($kelasId)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();

        if (!$guru) {
            return response()->json([]);
        }

        $mataPelajaran = Jadwal::where('guru_id', $guru->id)
                            ->where('kelas_id', $kelasId)
                            ->distinct()
                            ->pluck('mata_pelajaran')
                            ->filter()
                            ->values();

        return response()->json($mataPelajaran);
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
        
        // Filter berdasarkan kelas jika diperlukan
        if ($request->has('kelas_id') && $request->kelas_id != '') {
            $query->where('kelas_id', $request->kelas_id);
        }

        // Filter berdasarkan hari jika ada
        if ($request->has('hari') && $request->hari != '') {
            $query->where('hari', $request->hari);
        }
        
        $jadwals = $query->orderByRaw("
            CASE 
                WHEN hari = 'Senin' THEN 1
                WHEN hari = 'Selasa' THEN 2
                WHEN hari = 'Rabu' THEN 3
                WHEN hari = 'Kamis' THEN 4
                WHEN hari = 'Jumat' THEN 5
                WHEN hari = 'Sabtu' THEN 6
                WHEN hari = 'Minggu' THEN 7
            END
        ")->orderBy('jam_mulai')->get();
        
        $kelas = Kelas::all();
        
        // Kirim juga hari ini untuk keperluan view
        $hariIni = $this->getHariIndonesia(now()->format('l'));
        
        return view('guru.jadwal.index', compact('jadwals', 'kelas', 'hariIni'));
    }

    /**
     * Profile guru
     */
    public function profile()
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        
        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        return view('guru.profile', compact('guru'));
    }
}