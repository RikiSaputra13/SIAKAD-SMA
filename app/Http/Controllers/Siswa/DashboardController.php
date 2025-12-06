<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
//     public function index()
//     {
//         $siswa = Auth::user()->siswa;
//         $datasiswa = Siswa::findall();
//         // Absensi terakhir 5 hari
//         $absensi = Absensi::where('siswa_id', $siswa->id)->latest()->take(5)->get();

//         // Jadwal hari ini
//         $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');
//         $jadwalHariIni = Jadwal::where('kelas_id', $siswa->kelas_id)
//                                 ->where('hari', $hariIni)
//                                 ->get();

//         // Pembayaran terakhir
//         $pembayaran = Pembayaran::where('siswa_id', $siswa->id)->latest()->take(5)->get();

//         // Rekap absensi per bulan
//         $absensiBulan = Absensi::selectRaw('MONTH(tanggal) as bulan, status, COUNT(*) as total')
//                                 ->where('siswa_id', $siswa->id)
//                                 ->whereYear('tanggal', Carbon::now()->year)
//                                 ->groupBy('bulan','status')
//                                 ->get();

//         // Siapkan data chart
//         $bulanLabels = collect(range(1,12))->map(function($m){
//             return Carbon::create()->month($m)->isoFormat('MMMM');
//         });

//         $statusTypes = ['Hadir','Izin','Sakit','Alpha'];
//         $chartData = [];

//         foreach($statusTypes as $status) {
//             $data = [];
//             foreach(range(1,12) as $m) {
//                 // Hitung jumlah tiap status per bulan
//                 $count = $absensiBulan->where('bulan', $m)->where('status', $status)->sum('total');
//                 $data[] = $count ?? 0;
//             }
//             $chartData[$status] = $data;
//         }

//         return view('siswa.dashboard', compact(
//             'siswa','datasiswa', 'absensi','jadwalHariIni','pembayaran','bulanLabels','chartData'
//         )); // Tambahkan 'siswa' di compact
// }

public function index()
{
    $siswa = Auth::user()->siswa;

    // ✅ Total Jadwal kelas siswa
    $totalJadwal = Jadwal::where('kelas_id', $siswa->kelas_id)->count();

    $bulanIni = Carbon::now()->month;
    $tahunIni = Carbon::now()->year;

    $absensiBulanIni = Absensi::where('siswa_id', $siswa->id)
                        ->whereMonth('tanggal', $bulanIni)
                        ->whereYear('tanggal', $tahunIni)
                        ->get();

    $hadirBulan = $absensiBulanIni->where('status', 'Hadir')->count();
    $totalHariSekolah = 20;

    $nilai = Penilaian::where('siswa_id', $siswa->id)->get();
    $rataNilai = $nilai->count() > 0 ? number_format($nilai->avg('nilai_akhir'), 1) : 0;

    $pembayaranTertunda = Pembayaran::where('siswa_id', $siswa->id)
                            ->where('status', 'belum_lunas')
                            ->count();

    $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');
    $jadwalHariIni = Jadwal::where('kelas_id', $siswa->kelas_id)
                        ->where('hari', $hariIni)
                        ->orderBy('jam_mulai')
                        ->get();

    $jamSekarang = Carbon::now()->format('H:i');
    $jadwalSekarang = $jadwalHariIni->firstWhere('jam_mulai', '<=', $jamSekarang)->mata_pelajaran ?? null;

    $absensi = Absensi::where('siswa_id', $siswa->id)->latest()->take(5)->get();
    $pembayaran = Pembayaran::where('siswa_id', $siswa->id)->latest()->take(5)->get();

    $statistik = [
        'hadir_bulan' => $hadirBulan,
        'persentase_hadir' => $totalHariSekolah > 0 ? round(($hadirBulan / $totalHariSekolah) * 100) : 0,
        'rata_nilai' => $rataNilai,
        'total_mapel' => $nilai->count(),
        'jadwal_hari_ini' => $jadwalHariIni->count(),
    ];

    return view('siswa.dashboard', compact(
        'siswa', 'absensi', 'jadwalHariIni', 'pembayaran', 
        'statistik', 'jadwalSekarang', 'totalJadwal'
    ));
}



// Helper function untuk color status
private function getStatusColor($status)
{
    return match($status) {
        'Hadir' => 'success',
        'Izin' => 'info',
        'Sakit' => 'warning',
        'Alpha' => 'danger',
        default => 'secondary'
    };
}

    public function jadwal()
    {
        $siswa = Auth::user()->siswa;
        $jadwal = Jadwal::where('kelas_id', $siswa->kelas_id)->get();
        return view('siswa.jadwal', compact('jadwal'));
    }

    public function absensi()
    {
        $siswa = Auth::user()->siswa;
        $absensi = Absensi::where('siswa_id', $siswa->id)->get();
        return view('siswa.absensi', compact('absensi'));
    }

    public function pembayaran()
    {
        $siswa = Auth::user()->siswa;
        $pembayaran = Pembayaran::where('siswa_id', $siswa->id)->get();
        return view('siswa.pembayaran', compact('pembayaran'));
    }
}
