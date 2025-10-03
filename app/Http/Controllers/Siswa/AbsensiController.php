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

        $absensis = Absensi::where('siswa_id', $siswa->id)->latest()->get();
        return view('siswa.absensi.index', compact('absensis'));
    }

    public function history()
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();

        if (!$siswa) {
            return view('siswa.absensi.history')
                ->with('error', 'Data siswa tidak ditemukan.');
        }

        $absensis = Absensi::where('siswa_id', $siswa->id)->latest()->get();
        return view('siswa.absensi.history', compact('absensis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $userId = Auth::id();
        $userEmail = Auth::user()->email;

        \Log::info('Mulai proses absensi', [
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
            'nama' => $siswa->nama
        ]);

        $token = Token::where('token_kode', $request->token)
                      ->where('is_used', false)
                      ->where('expired_at', '>', now())
                      ->first();

        if (!$token) {
            \Log::warning('Token tidak valid', [
                'user_id' => $userId,
                'token' => $request->token
            ]);
            return back()->withErrors(['token' => 'Token tidak valid, sudah digunakan, atau kadaluarsa.'])
                         ->withInput();
        }

        try {
            // Cek absensi hari ini
            $existingAbsensi = Absensi::where('siswa_id', $siswa->id)
                                      ->whereDate('tanggal', today())
                                      ->first();

            if ($existingAbsensi) {
                \Log::warning('Absensi sudah ada hari ini', [
                    'siswa_id' => $siswa->id,
                    'tanggal' => today()->toDateString()
                ]);
                return back()->with('error', 'Anda sudah melakukan absensi hari ini.');
            }

            // Simpan absensi
            $absensi = Absensi::create([
                'siswa_id' => $siswa->id,
                'tanggal' => now(),
                'status' => 'Hadir',
                'keterangan' => $request->keterangan,
            ]);

            \Log::info('Absensi berhasil dibuat', [
                'absensi_id' => $absensi->id,
                'siswa_id' => $siswa->id
            ]);

            // Update token
            $token->update(['is_used' => true]);

            // Kirim notifikasi WhatsApp jika ada no HP orang tua
           // Kirim notifikasi WhatsApp jika ada no HP orang tua
if ($siswa->tlp_orang_tua) {
    $nomorHP = preg_replace('/^0/', '62', $siswa->tlp_orang_tua);
    $pesan = "Yth. Orang tua/wali dari {$siswa->nama}, Ananda telah melakukan absensi dengan status 'Hadir' pada " . now()->format('d F Y') . ".";

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

            return back()->with('success', 'Absensi berhasil direkam.');

        } catch (\Exception $e) {
            \Log::error('Error absensi', [
                'user_id' => $userId,
                'siswa_id' => $siswa->id ?? null,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat menyimpan absensi: ' . $e->getMessage());
        }
    }
}
