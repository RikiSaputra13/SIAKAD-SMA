<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Hash;
use App\Models\Penilaian;
use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\User;

class SiswaController extends Controller
{
    /**
     * Dashboard siswa.
     */
//     public function index()
// {
//     $user = Auth::user();

//     // Cek apakah user sudah punya relasi siswa dan kelas_id
//     $siswa = $user->siswa;
//     $kelasId = $siswa ? $siswa->kelas_id : null;

//     if ($kelasId) {
//         $jadwals = Jadwal::where('kelas_id', $kelasId)
//             ->orderByRaw("FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
//             ->orderBy('jam_mulai')
//             ->get();
//     } else {
//         $jadwals = collect(); // kosongkan jadwal jika belum ada kelas
//     }

//     $absensis = Absensi::where('siswa_id', $user->id)->get();
//     $pembayarans = Pembayaran::where('siswa_id', $user->id)->get();

//     return view('siswa.dashboard', [
//         'jadwals' => $jadwals,
//         'absensis' => $absensis,
//         'pembayarans' => $pembayarans,
//         'kelasBelumAda' => !$kelasId // untuk notifikasi di blade
//     ]);
// }
public function index()
{
    $siswa = Auth::user()->siswa;
    
    // Inisialisasi default values
    $statistik = [
        'hadir_bulan' => 0,
        'persentase_hadir' => 0,
        'rata_nilai' => 0,
        'total_mapel' => 0,
        'pembayaran_tertunda' => 0,
        'jadwal_hari_ini' => 0,
    ];

    $jadwalHariIni = collect();
    $absensi = collect();
    $bulanLabels = collect();
    $chartData = [
        'Hadir' => array_fill(0, 12, 0),
        'Izin' => array_fill(0, 12, 0),
        'Sakit' => array_fill(0, 12, 0),
        'Alpha' => array_fill(0, 12, 0)
    ];

    try {
        if ($siswa) {
            $bulanIni = Carbon::now()->month;
            $tahunIni = Carbon::now()->year;
            
            // Statistik Absensi
            $absensiBulanIni = Absensi::where('siswa_id', $siswa->id)
                                ->whereMonth('tanggal', $bulanIni)
                                ->whereYear('tanggal', $tahunIni)
                                ->get();
            
            $hadirBulan = $absensiBulanIni->where('status', 'Hadir')->count();
            $totalHariSekolah = 20;

            // Statistik Nilai
            $nilai = Penilaian::where('siswa_id', $siswa->id)->get();
            $rataNilai = $nilai->count() > 0 ? number_format($nilai->avg('nilai_akhir'), 1) : 0;
            
            // Statistik Pembayaran
            $pembayaranTertunda = Pembayaran::where('siswa_id', $siswa->id)
                                    ->where('status', 'belum_lunas')
                                    ->count();
            
            // Jadwal Hari Ini
            $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');
            $jadwalHariIni = Jadwal::where('kelas_id', $siswa->kelas_id)
                                    ->where('hari', $hariIni)
                                    ->orderBy('jam_mulai')
                                    ->get();
            
            // Cek jadwal sekarang
            $jamSekarang = Carbon::now()->format('H:i');
            $jadwalSekarang = $jadwalHariIni->firstWhere('jam_mulai', '<=', $jamSekarang);
            $jadwalSekarangNama = $jadwalSekarang->mata_pelajaran ?? 'Libur';

            // Data untuk chart
            $absensiBulan = Absensi::selectRaw('MONTH(tanggal) as bulan, status, COUNT(*) as total')
                                    ->where('siswa_id', $siswa->id)
                                    ->whereYear('tanggal', $tahunIni)
                                    ->groupBy('bulan','status')
                                    ->get();

            $bulanLabels = collect(range(1,12))->map(function($m){
                return Carbon::create()->month($m)->isoFormat('MMMM');
            });

            $statusTypes = ['Hadir','Izin','Sakit','Alpha'];
            
            foreach($statusTypes as $status) {
                $data = [];
                foreach(range(1,12) as $m) {
                    $count = $absensiBulan->where('bulan', $m)->where('status', $status)->sum('total');
                    $data[] = $count ?? 0;
                }
                $chartData[$status] = $data;
            }

            // Data absensi terbaru
            $absensi = Absensi::where('siswa_id', $siswa->id)
                            ->latest()
                            ->take(5)
                            ->get();

            // Update statistik
            $statistik = [
                'hadir_bulan' => $hadirBulan,
                'persentase_hadir' => $totalHariSekolah > 0 ? round(($hadirBulan / $totalHariSekolah) * 100) : 0,
                'rata_nilai' => $rataNilai,
                'total_mapel' => $nilai->count(),
                'pembayaran_tertunda' => $pembayaranTertunda,
                'jadwal_hari_ini' => $jadwalHariIni->count(),
            ];
        }

    } catch (\Exception $e) {
        \Log::error('Error in siswa dashboard: ' . $e->getMessage());
    }

    return view('siswa.dashboard', compact(
        'siswa', 'absensi', 'jadwalHariIni', 'statistik', 
        'bulanLabels', 'chartData', 'jadwalSekarangNama'
    ));
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
