<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PembayaranExport;

class PembayaranController extends Controller
{
    // ================= CRUD =================
    public function index(Request $request)
    {
        $query = Pembayaran::with(['siswa.kelas'])->orderBy('tanggal_bayar', 'desc');

        // Filter tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_bayar', [$request->start_date, $request->end_date]);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter jenis pembayaran
        if ($request->filled('jenis_pembayaran')) {
            $query->where('jenis_pembayaran', $request->jenis_pembayaran);
        }

        // Filter kelas
        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        $pembayarans = $query->get();
        $kelas = Kelas::all(); // 🔹 untuk dropdown filter kelas

        return view('admin.pembayaran.index', compact('pembayarans', 'kelas'));
    }

    public function create()
    {
        $siswas = Siswa::all();
        return view('admin.pembayaran.create', compact('siswas'));
    }

    public function store(Request $request, WhatsappService $wa)
    {
        $validated = $request->validate([
            'siswa_id'          => 'required|exists:siswas,id',
            'jenis_pembayaran'  => 'required|string',
            'total_tagihan'     => 'required|numeric',
            'jumlah_bayar'      => 'required|numeric',
            'metode_pembayaran' => 'required|in:cash,transfer_bank',
            'tanggal_bayar'     => 'required|date',
        ]);

        $validated['status'] = $validated['jumlah_bayar'] >= $validated['total_tagihan']
            ? 'Lunas' : 'Belum Lunas';

        $pembayaran = Pembayaran::create($validated);
        $pembayaran->load('siswa');

        $this->sendWhatsappNotification($pembayaran, $wa, 'tambah');

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran berhasil ditambahkan dan notifikasi terkirim.');
    }

    public function edit(Pembayaran $pembayaran)
    {
        $siswas = Siswa::all();
        return view('admin.pembayaran.edit', compact('pembayaran', 'siswas'));
    }

    public function update(Request $request, Pembayaran $pembayaran, WhatsappService $wa)
    {
        $validated = $request->validate([
            'siswa_id'          => 'required|exists:siswas,id',
            'jenis_pembayaran'  => 'required|string',
            'total_tagihan'     => 'required|numeric',
            'jumlah_bayar'      => 'required|numeric',
            'metode_pembayaran' => 'required|in:cash,transfer_bank',
            'tanggal_bayar'     => 'required|date',
        ]);

        $validated['status'] = $validated['jumlah_bayar'] >= $validated['total_tagihan']
            ? 'Lunas' : 'Belum Lunas';

        $pembayaran->update($validated);
        $pembayaran->load('siswa');

        $this->sendWhatsappNotification($pembayaran, $wa, 'update');

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran berhasil diperbarui dan notifikasi terkirim.');
    }

    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();
        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }

    // ================= FUNGSI REKAP & EXPORT =================
    public function rekap(Request $request)
    {
        $query = Pembayaran::with(['siswa.kelas'])->orderBy('tanggal_bayar', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_bayar', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_pembayaran')) {
            $query->where('jenis_pembayaran', $request->jenis_pembayaran);
        }

        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        $rekap = $query->get()->map(function ($item, $index) {
            return [
                'index'             => $index + 1,
                'id'                => $item->id,
                'nis'               => $item->siswa ? $item->siswa->nis : '-',
                'nama_siswa'        => $item->siswa ? $item->siswa->nama : '-',
                'kelas'             => $item->siswa && $item->siswa->kelas ? $item->siswa->kelas->nama : '-',
                'jenis_pembayaran'  => $item->jenis_pembayaran,
                'total_tagihan'     => $item->total_tagihan,
                'jumlah_bayar'      => $item->jumlah_bayar,
                'metode_pembayaran' => $item->metode_pembayaran,
                'tanggal_bayar'     => $item->tanggal_bayar ? $item->tanggal_bayar->format('Y-m-d') : null,
                'status'            => $item->status,
            ];
        });

        return response()->json([
            'success' => true,
            'rekap'   => $rekap
        ]);
    }

    public function cetakPdf(Request $request)
    {
        $startDate       = $request->start_date;
        $endDate         = $request->end_date;
        $jenisPembayaran = $request->jenis_pembayaran;
        $kelasId         = $request->kelas_id;

        $query = Pembayaran::with(['siswa.kelas'])->orderBy('tanggal_bayar', 'desc');

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_bayar', [$startDate, $endDate]);
        }

        if ($jenisPembayaran) {
            $query->where('jenis_pembayaran', $jenisPembayaran);
        }

        if ($kelasId) {
            $query->whereHas('siswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        $pembayarans = $query->get();

        // 🔹 Rekap per jenis pembayaran
        $rekapData = $pembayarans
            ->groupBy('jenis_pembayaran')
            ->map(function ($group, $jenis) {
                return (object) [
                    'jenis_pembayaran' => $jenis,
                    'jumlah_transaksi' => $group->count(),
                    'total_pemasukan'  => $group->sum('jumlah_bayar'),
                    'lunas'            => $group->where('status', 'Lunas')->count(),
                    'belum_lunas'      => $group->where('status', 'Belum Lunas')->count(),
                ];
            });

        $pdf = Pdf::loadView('admin.pembayaran.cetak-pdf', compact(
            'pembayarans',
            'rekapData',
            'startDate',
            'endDate',
            'jenisPembayaran',
            'kelasId'
        ));

        return $pdf->download('rekap-pembayaran-' . date('Y-m-d') . '.pdf');
    }

    public function cetakExcel(Request $request)
    {
        $startDate       = $request->start_date;
        $endDate         = $request->end_date;
        $jenisPembayaran = $request->jenis_pembayaran;

        return Excel::download(
            new PembayaranExport($startDate, $endDate, $jenisPembayaran),
            'rekap-pembayaran-' . date('Y-m-d') . '.xlsx'
        );
    }

    public function show(Pembayaran $pembayaran)
    {
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    // ================= Fungsi Notifikasi WA =================
    private function sendWhatsappNotification(Pembayaran $pembayaran, WhatsappService $wa, $aksi = 'tambah')
    {
        try {
            $siswa = $pembayaran->siswa;
            if ($siswa && $siswa->tlp_orang_tua) {
                $sisa = $pembayaran->total_tagihan - $pembayaran->jumlah_bayar;
                $pesan = "📢 Info Pembayaran\n".
                         "Nama Siswa   : {$siswa->nama}\n".
                         "Jenis        : {$pembayaran->jenis_pembayaran}\n".
                         "Total Tagihan: Rp ".number_format($pembayaran->total_tagihan,0,',','.')."\n".
                         "Dibayar      : Rp ".number_format($pembayaran->jumlah_bayar,0,',','.')."\n".
                         "Metode       : ".ucfirst(str_replace('_',' ',$pembayaran->metode_pembayaran))."\n".
                         "Status       : {$pembayaran->status}\n".
                         "Sisa         : Rp ".number_format($sisa,0,',','.')."\n";

                $pesan .= $aksi === 'tambah'
                    ? "\n✅ Pembayaran berhasil dicatat. Terima kasih 🙏"
                    : "\n✏️ Data pembayaran telah diperbarui.";

                $wa->sendMessage($siswa->tlp_orang_tua, $pesan);
            }
        } catch (\Exception $e) {
            \Log::error('Error sending WA notification: '.$e->getMessage());
        }
    }
}
