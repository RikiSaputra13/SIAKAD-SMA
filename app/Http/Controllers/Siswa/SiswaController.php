<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SiswaController extends Controller
{
    /**
     * Dashboard siswa.
     */
    public function index()
{
    $user = Auth::user();

    // Cek apakah user sudah punya relasi siswa dan kelas_id
    $siswa = $user->siswa;
    $kelasId = $siswa ? $siswa->kelas_id : null;

    if ($kelasId) {
        $jadwals = Jadwal::where('kelas_id', $kelasId)
            ->orderByRaw("FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('jam_mulai')
            ->get();
    } else {
        $jadwals = collect(); // kosongkan jadwal jika belum ada kelas
    }

    $absensis = Absensi::where('siswa_id', $user->id)->get();
    $pembayarans = Pembayaran::where('siswa_id', $user->id)->get();

    return view('siswa.dashboard', [
        'jadwals' => $jadwals,
        'absensis' => $absensis,
        'pembayarans' => $pembayarans,
        'kelasBelumAda' => !$kelasId // untuk notifikasi di blade
    ]);
}

    /**
     * Halaman jadwal lengkap siswa.
     */
    public function jadwalIndex()
    {
        $user = Auth::user();

        $jadwals = Jadwal::with(['guru','kelas'])
                         ->where('kelas_id', $user->siswa->kelas_id)
                         ->orderByRaw("FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
                         ->orderBy('jam_mulai')
                         ->get();

        return view('siswa.jadwal', compact('jadwals'));
    }

    /**
     * Halaman absensi siswa.
     */
    public function absensi()
    {
        $user = Auth::user();

        $absensi = Absensi::where('siswa_id', $user->id)
                          ->orderBy('tanggal', 'desc')
                          ->get();

        return view('siswa.absensi.index', compact('absensi'));
    }

    /**
     * Halaman pembayaran siswa.
     */
    // public function pembayaranIndex()
    // {
    //     $user = Auth::user();
    //     $pembayarans = Pembayaran::where('siswa_id', $user->id)->get();

    //     return view('siswa.pembayaran', compact('pembayarans'));
    // }

    public function pembayaranIndex()
{
    $user = Auth::user();

    // Cari siswa berdasarkan user_id
    $siswa = \App\Models\Siswa::where('user_id', $user->id)->first();

    if (!$siswa) {
        return back()->with('error', 'Data siswa tidak ditemukan.');
    }

    // Ambil semua pembayaran milik siswa ini
    $pembayarans = \App\Models\Pembayaran::where('siswa_id', $siswa->id)
                                         ->orderBy('tanggal_bayar', 'desc')
                                         ->get();

    return view('siswa.pembayaran', compact('pembayarans'));
}


    /**
     * Form ubah password.
     */
    public function showChangePasswordForm()
    {
        return view('siswa.ubah-password');
    }

    /**
     * Proses ubah password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('siswa.dashboard')->with('success', 'Password berhasil diubah!');
    }

    /**
     * Halaman profil siswa.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('siswa.profile', compact('user'));
    }
}
