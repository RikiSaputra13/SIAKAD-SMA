<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Token;
use App\Models\Absensi;
use App\Models\Siswa; 
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; 

class AbsensiController extends Controller
{
    public function index()
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();

        if (!$siswa) {
            return view('siswa.absensi.index')
                ->with('error', 'Data siswa tidak ditemukan.');
        }

        return view('siswa.absensi.index', compact('siswa'));
    }

    public function history()
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();

        if (!$siswa) {
            return view('siswa.absensi.history')
                ->with('error', 'Data siswa tidak ditemukan.');
        }

        $absensis = Absensi::with(['guru', 'token'])
                    ->where('siswa_id', $siswa->id)
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('waktu', 'desc')
                    ->get();
                    
        return view('siswa.absensi.history', compact('absensis', 'siswa'));
    }

    // Tambahkan method untuk redirect ke dashboard
    public function dashboard()
    {
        return redirect()->route('siswa.dashboard');
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string|size:6'
        ]);

        $userId = Auth::id();
        $userEmail = Auth::user()->email;

        \Log::info('Mulai proses absensi dengan token guru', [
            'user_id' => $userId,
            'email' => $userEmail,
            'token' => $request->token,
            'waktu' => now()->toDateTimeString()
        ]);

        $siswa = Siswa::where('user_id', $userId)->first();

        if (!$siswa) {
            \Log::error('Data siswa tidak ditemukan', ['user_id' => $userId]);
            return back()->with('error', 'Data siswa tidak ditemukan. Hubungi administrator.');
        }

        \Log::info('Data siswa ditemukan', [
            'siswa_id' => $siswa->id,
            'nama' => $siswa->nama,
            'kelas_id' => $siswa->kelas_id
        ]);

        // Cek token valid dari guru
        $token = strtoupper($request->token);
        $validToken = Token::with(['guru', 'kelas'])
                        ->where('token_kode', $token)
                        ->where('kelas_id', $siswa->kelas_id)
                        ->whereDate('attendance_date', today())
                        ->where('expired_at', '>', now())
                        ->where('is_used', false)
                        ->first();

        if (!$validToken) {
            \Log::warning('Token tidak valid', [
                'user_id' => $userId,
                'token' => $token,
                'kelas_siswa' => $siswa->kelas_id
            ]);
            return back()->with('error', 'Token tidak valid, sudah kadaluarsa, atau bukan untuk kelas Anda!');
        }

        try {
            // Cek apakah sudah absen untuk sesi ini (token yang sama)
            $existingAbsensi = Absensi::where('siswa_id', $siswa->id)
                                ->where('token_id', $validToken->id)
                                ->whereDate('tanggal', today())
                                ->first();

            if ($existingAbsensi) {
                \Log::warning('Sudah absen untuk sesi ini', [
                    'siswa_id' => $siswa->id,
                    'token_id' => $validToken->id,
                    'tanggal' => today()->toDateString()
                ]);
                return back()->with('error', 'Anda sudah melakukan absensi untuk pelajaran ini!');
            }

            // Tentukan status (hadir/terlambat)
            $currentTime = now();
            $jamMulai = \Carbon\Carbon::createFromFormat('H:i', $validToken->jam_mulai);
            $status = $currentTime->gt($jamMulai->addMinutes(15)) ? 'terlambat' : 'hadir';

            // Simpan absensi dengan data lengkap
            $absensi = Absensi::create([
                'siswa_id' => $siswa->id,
                'guru_id' => $validToken->guru_id,
                'mapel_id' => $validToken->mapel_id,
                'token_id' => $validToken->id,
                'tanggal' => today(),
                'waktu' => now()->format('H:i:s'),
                'status' => $status,
                'token_used' => $token,
                'keterangan' => 'Absensi menggunakan token - ' . $validToken->guru->nama,
                'sesi' => $validToken->jam_mulai . '-' . $validToken->jam_selesai
            ]);

            \Log::info('Absensi berhasil dibuat dengan sistem token guru', [
                'absensi_id' => $absensi->id,
                'siswa_id' => $siswa->id,
                'guru_id' => $validToken->guru_id,
                'token_id' => $validToken->id,
                'status' => $status
            ]);

            // Update jumlah absensi di token (tidak mark as used agar bisa dipakai siswa lain)
            // Token tetap aktif untuk siswa lain di kelas yang sama

            // Kirim notifikasi WhatsApp jika ada no HP orang tua
            if ($siswa->tlp_orang_tua) {
                $nomorHP = preg_replace('/^0/', '62', $siswa->tlp_orang_tua);
                $pesan = "Yth. Orang tua/wali dari {$siswa->nama}, Ananda telah melakukan absensi dengan status '{$status}' pada " . now()->format('d F Y H:i') . " untuk pelajaran dengan {$validToken->guru->nama}.";

                if (env('WHATSAPP_API_URL') && env('WHATSAPP_API_KEY')) {
                    $response = Http::withHeaders([
                        'Authorization' => env('WHATSAPP_API_KEY'),
                    ])->post(env('WHATSAPP_API_URL'), [
                        'target'  => $nomorHP,
                        'message' => $pesan,
                    ]);

                    if ($response->failed()) {
                        \Log::error('Gagal kirim WhatsApp', [
                            'siswa_id' => $siswa->id,
                            'status' => $response->status(),
                            'body' => $response->body()
                        ]);
                    } else {
                        \Log::info('WhatsApp berhasil dikirim', [
                            'siswa_id' => $siswa->id,
                            'response' => $response->json()
                        ]);
                    }
                } else {
                    \Log::warning('WhatsApp API belum dikonfigurasi');
                }
            }

            return redirect()->route('siswa.dashboard')
                ->with('success', 
                    'Absensi berhasil! ' . 
                    $validToken->guru->nama . 
                    ($status == 'terlambat' ? ' (Terlambat)' : '')
                );

        } catch (\Exception $e) {
            \Log::error('Error absensi dengan token guru', [
                'user_id' => $userId,
                'siswa_id' => $siswa->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat menyimpan absensi: ' . $e->getMessage());
        }
    }
}