<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Pembayaran;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;
        $datasiswa = Siswa::findall();
        // Absensi terakhir 5 hari
        $absensi = Absensi::where('siswa_id', $siswa->id)->latest()->take(5)->get();

        // Jadwal hari ini
        $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');
        $jadwalHariIni = Jadwal::where('kelas_id', $siswa->kelas_id)
                                ->where('hari', $hariIni)
                                ->get();

        // Pembayaran terakhir
        $pembayaran = Pembayaran::where('siswa_id', $siswa->id)->latest()->take(5)->get();

        // Rekap absensi per bulan
        $absensiBulan = Absensi::selectRaw('MONTH(tanggal) as bulan, status, COUNT(*) as total')
                                ->where('siswa_id', $siswa->id)
                                ->whereYear('tanggal', Carbon::now()->year)
                                ->groupBy('bulan','status')
                                ->get();

        // Siapkan data chart
        $bulanLabels = collect(range(1,12))->map(function($m){
            return Carbon::create()->month($m)->isoFormat('MMMM');
        });

        $statusTypes = ['Hadir','Izin','Sakit','Alpha'];
        $chartData = [];

        foreach($statusTypes as $status) {
            $data = [];
            foreach(range(1,12) as $m) {
                // Hitung jumlah tiap status per bulan
                $count = $absensiBulan->where('bulan', $m)->where('status', $status)->sum('total');
                $data[] = $count ?? 0;
            }
            $chartData[$status] = $data;
        }

        return view('siswa.dashboard', compact(
            'siswa','datasiswa', 'absensi','jadwalHariIni','pembayaran','bulanLabels','chartData'
        )); // Tambahkan 'siswa' di compact
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
