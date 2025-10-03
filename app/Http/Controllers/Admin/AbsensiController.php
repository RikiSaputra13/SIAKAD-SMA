<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Services\WhatsappService;
use Carbon\Carbon;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiRekapExport;
use Illuminate\Support\Facades\Log;

class AbsensiController extends Controller
{
    // ================= CRUD =================
    public function index()
    {
        $absensis = Absensi::with('siswa.kelas')->latest()->get();
        $kelas = Kelas::all(); // untuk filter di view
        return view('admin.absensi.index', compact('absensis', 'kelas'));
    }

    public function create()
    {
        $siswas = Siswa::all();
        return view('admin.absensi.create', compact('siswas'));
    }

    public function store(Request $request, WhatsappService $wa)
    {
        $validated = $request->validate([
            'siswa_id'       => 'required|exists:siswas,id',
            'status'         => 'required|in:Hadir,Izin,Sakit,Alpha',
            'tanggal'        => 'required|date',
            'keterangan_izin'=> 'nullable|string'
        ]);

        $absensi = Absensi::create($validated);
        $absensi->load('siswa');

        $this->sendWhatsAppNotification($absensi, $wa, 'tambah');

        return redirect()->route('admin.absensi.index')
                         ->with('success', 'Absensi berhasil ditambahkan dan notifikasi terkirim.');
    }

    public function edit(Absensi $absensi)
    {
        $siswas = Siswa::all();
        return view('admin.absensi.edit', compact('absensi', 'siswas'));
    }

    public function update(Request $request, Absensi $absensi, WhatsappService $wa)
    {
        $validated = $request->validate([
            'siswa_id'       => 'required|exists:siswas,id',
            'status'         => 'required|in:Hadir,Izin,Sakit,Alpha',
            'tanggal'        => 'required|date',
            'keterangan_izin'=> 'nullable|string'
        ]);

        $absensi->update($validated);
        $absensi->load('siswa');

        $this->sendWhatsAppNotification($absensi, $wa, 'update');

        return redirect()->route('admin.absensi.index')
                         ->with('success', 'Absensi berhasil diperbarui dan notifikasi terkirim.');
    }

    public function destroy(Absensi $absensi)
    {
        $absensi->delete();
        return redirect()->route('admin.absensi.index')
                         ->with('success', 'Absensi berhasil dihapus.');
    }

    // ================= Notifikasi WhatsApp =================
    private function sendWhatsAppNotification(Absensi $absensi, WhatsappService $wa, $aksi = 'tambah')
    {
        try {
            $siswa = $absensi->siswa;
            if ($siswa && $siswa->tlp_orang_tua) {
                $tanggal = Carbon::parse($absensi->tanggal)->translatedFormat('d F Y');
                $kelas   = $siswa->kelas->nama_kelas ?? '-';

                switch ($absensi->status) {
                    case 'Hadir':
                        $pesan = "Yth. Orang Tua/Wali,\n\n".
                                 "Nama   : {$siswa->nama}\n".
                                 "Kelas  : {$kelas}\n".
                                 "Tanggal: {$tanggal}\n".
                                 "Telah hadir di sekolah.\n\nTerima kasih.";
                        break;
                    case 'Sakit':
                        $pesan = "Yth. Orang Tua/Wali,\n\n".
                                 "Nama   : {$siswa->nama}\n".
                                 "Kelas  : {$kelas}\n".
                                 "Tanggal: {$tanggal}\n".
                                 "Sedang sakit.\n\nSemoga lekas sembuh.";
                        break;
                    case 'Izin':
                        $pesan = "Yth. Orang Tua/Wali,\n\n".
                                 "Nama   : {$siswa->nama}\n".
                                 "Kelas  : {$kelas}\n".
                                 "Tanggal: {$tanggal}\n".
                                 "Izin tidak hadir.\n".
                                 ($absensi->keterangan_izin ? "Keterangan: {$absensi->keterangan_izin}\n" : "").
                                 "\nTerima kasih.";
                        break;
                    case 'Alpha':
                        $pesan = "Yth. Orang Tua/Wali,\n\n".
                                 "Nama   : {$siswa->nama}\n".
                                 "Kelas  : {$kelas}\n".
                                 "Tanggal: {$tanggal}\n".
                                 "Tidak hadir tanpa keterangan.\n\nTerima kasih.";
                        break;
                    default:
                        $pesan = "Yth. Orang Tua/Wali,\n\n".
                                 "Nama   : {$siswa->nama}\n".
                                 "Kelas  : {$kelas}\n".
                                 "Tanggal: {$tanggal}\n".
                                 "Status absensi: {$absensi->status}\n\nMohon diperiksa.";
                }

                $wa->sendMessage($siswa->tlp_orang_tua, $pesan);
            }
        } catch (\Exception $e) {
            Log::error('Error sending WhatsApp notification: '.$e->getMessage());
        }
    }

    // ================= Rekap / Export =================
    private function buildRekapQuery(Request $request)
    {
        $query = Absensi::with('siswa.kelas');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [
                Carbon::parse($request->start_date)->toDateString(),
                Carbon::parse($request->end_date)->toDateString()
            ]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', Carbon::parse($request->start_date)->toDateString());
        } elseif ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', Carbon::parse($request->end_date)->toDateString());
        }

        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        if ($request->filled('status') && in_array($request->status, ['Hadir','Sakit','Izin','Alpha'])) {
            $query->where('status', $request->status);
        }

        return $query;
    }

   public function rekap(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'nullable|date',
                'end_date'   => 'nullable|date|after_or_equal:start_date',
                'kelas_id'   => 'nullable|exists:kelas,id',
                'status'     => 'nullable|in:Hadir,Sakit,Izin,Alpha',
            ]);

            $absensis = $this->buildRekapQuery($request)->latest()->get();

            $statistik = [
                'Hadir' => $absensis->where('status', 'Hadir')->count(),
                'Sakit' => $absensis->where('status', 'Sakit')->count(),
                'Izin'  => $absensis->where('status', 'Izin')->count(),
                'Alpha' => $absensis->where('status', 'Alpha')->count(),
                'total' => $absensis->count(),
            ];

            $rekap = $absensis->groupBy(fn($row) => $row->siswa->id ?? 'unknown_'.$row->id)
                            ->map(function($rows) {
                                $first = $rows->first();
                                return [
                                    'siswa_id'   => $first->siswa->id ?? null,
                                    'nis'        => $first->siswa->nis ?? '-', // TAMBAH INI
                                    'nama_siswa' => $first->siswa->nama ?? '-',
                                    'Hadir'      => $rows->where('status', 'Hadir')->count(),
                                    'Sakit'      => $rows->where('status', 'Sakit')->count(),
                                    'Izin'       => $rows->where('status', 'Izin')->count(),
                                    'Alpha'      => $rows->where('status', 'Alpha')->count(),
                                ];
                            })->values()->toArray();

            return response()->json([
                'success'    => true,
                'absensis'   => $absensis,
                'rekap'      => $rekap,
                'statistik'  => $statistik,
                'start_date' => $request->start_date ?? null,
                'end_date'   => $request->end_date ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error rekap absensi: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server.'], 500);
        }
    }

    public function cetakRekap(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'nullable|date',
                'end_date'   => 'nullable|date|after_or_equal:start_date',
                'kelas_id'   => 'nullable|exists:kelas,id',
                'status'     => 'nullable|in:Hadir,Sakit,Izin,Alpha',
            ]);

            $absensis = $this->buildRekapQuery($request)->latest()->get();

            $statistik = [
                'Hadir' => $absensis->where('status', 'Hadir')->count(),
                'Sakit' => $absensis->where('status', 'Sakit')->count(),
                'Izin'  => $absensis->where('status', 'Izin')->count(),
                'Alpha' => $absensis->where('status', 'Alpha')->count(),
                'total' => $absensis->count(),
            ];

            $pdf = PDF::loadView('admin.absensi.cetakrekap', [
                'absensis'   => $absensis,
                'statistik'  => $statistik,
                'start_date' => $request->start_date ?? null,
                'end_date'   => $request->end_date ?? null,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('rekap-absensi-'.date('Y-m-d').'.pdf');
        } catch (\Throwable $e) {
            Log::error('Error cetakRekap: '.$e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan server saat mencetak rekap.');
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'nullable|date',
                'end_date'   => 'nullable|date|after_or_equal:start_date',
                'kelas_id'   => 'nullable|exists:kelas,id',
                'status'     => 'nullable|in:Hadir,Sakit,Izin,Alpha',
            ]);

            $absensis = $this->buildRekapQuery($request)->latest()->get();

            return Excel::download(
                new AbsensiRekapExport($absensis),
                'rekap-absensi-'.date('Y-m-d').'.xlsx'
            );
        } catch (\Throwable $e) {
            Log::error('Error exportExcel: '.$e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan server saat export Excel.');
        }
    }
}
